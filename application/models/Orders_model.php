<?php

Class Orders_model extends CI_Model {

// Insert registration data in database
public function registration_insert($data) 
    {
        // Query to check whether username already exist or not
        $condition = "username =" . "'" . $data['username'] . "'";

        $this->db->select('*');
        $this->db->from('employees');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        //print_r($data);exit;
        if ($query->num_rows() == 0) {
            // Query to insert data in database
            $this->db->insert('employees', $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            }
        } else { 
            return false;
        }
    }

// Read data using username and password
public function login($data)
    {
        $status_mode='';
        $login_data=[];
        $condition = "username =" . "'" . $data['username'] . "' AND " . "password =" . "'" . $data['password'] . "'";
        //print_r($condition);exit;
        $this->db->select('employees.*,roles.role as role');
        $this->db->from('employees');
        $this->db->join('roles','employees.role_id=roles.id');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        $login_data=$query->result();

        //print_r($login_data);exit;
        if ($query->num_rows() == 1) 
            {
                if($login_data['0']->status==0){
                    $status_mode='active';
                    return $status_mode;
                }else{
                    $status_mode='Inactive';
                    return $status_mode;
                }
            } 
        else {
            return false;
        }
    }

    // Read data from database to show data in admin page
    public function read_user_information($username) 
        {
            $condition = "username =" . "'" . $username . "'";
            $this->db->select('employees.*,roles.role as role');
            $this->db->from('employees');
            $this->db->join('roles','employees.role_id=roles.id');
            $this->db->where($condition);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
                return $query->result();
            } else {
                return false;
            }
        }
    
    // Insert orders data in database
    public function orders_insert($data) 
    {
		$order_date = $data['order_date'];
		if($data['plan_status']==1) {
			$data['expiry_date'] = date('Y-m-d', strtotime($order_date. ' + 28 days'));
		}
		if($data['plan_status']==3) {
			$data['expiry_date'] = date('Y-m-d', strtotime($order_date. ' + 84 days'));
		}
		if($data['plan_status']==6) {
			$data['expiry_date'] = date('Y-m-d', strtotime($order_date. ' + 168 days'));
		}
		if($data['plan_status']==12) {
			$data['expiry_date'] = date('Y-m-d', strtotime($order_date. ' + 336 days'));
		}
        $this->db->insert('orders', $data);
        $order_id = $this->db->insert_id();
		if(!empty($this->input->post('addon_service_id'))) {
			$this->addOrderDetails($order_id);
		}
        
		if ($this->db->affected_rows() > 0) 
		{
			$this->insertNotif($order_id);
			$this->sendMail($order_id);
            return true;
        }
        else 
		{ 
            return false;
        }
    }
    // Insert Notification in Database
    public function insertNotif($order_id) {
		// Get Details
		$this->db->select('orders.*, subscription.title as subscription_plan_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, subscription.price as subscription_price');
		$this->db->from('orders'); 
		$this->db->join('subscription', 'orders.subscription_plan_id = subscription.id','left'); 
		$this->db->join('users', 'orders.user_id = users.id','left'); 
		$this->db->where(['orders.flag'=>'0', 'orders.id'=>$order_id]);
		$this->db->order_by("orders.id", "asc");
		$query    = $this->db->get()->result_array();
		$user_id  = $query['0']['user_id'];
		$order_id = $query['0']['order_id'];
		// Data for Notification
		$notifData['employee_id'] = $user_id;
		$notifData['type']        = 'Order Create';
		$notifData['subject']     = 'Place Order';
		$notifData['message']     =  'Order Successful, Order ID : '.$order_id;
		$notifData['datetime']    = date('Y-m-d H:i:s');
		$notifData['status']      = '0';
		$this->db->insert('notifications', $notifData);
    }
	public function addOrderDetails($order_id){
        $this->db->where('order_id', $order_id);
        $this->db->delete('order_details');
        $addon = $this->input->post('addon_service_id');
        if(!empty($addon)){
             foreach ($addon as $key => $value) :
                $this->db->set('order_id', $order_id);
                $this->db->set('addon_service_id', $value);
                $this->db->set('qty', $this->input->post('qty')[$key]);
                $this->db->set('price', $this->input->post('price')[$key]);
                $this->db->set('total', $this->input->post('amount')[$key]);
                $this->db->insert('order_details');
            endforeach;
        }
    }

    // view orders data from database
    public function orders_list() 
	{
		$this->db->select('orders.*, subscription.title as subscription_plan_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, subscription.price as subscription_price');
		$this->db->from('orders'); 
		$this->db->join('subscription', 'orders.subscription_plan_id = subscription.id','left'); 
		$this->db->join('users', 'orders.user_id = users.id','left'); 
		$this->db->where('orders.flag','0');
		$this->db->order_by("orders.id", "asc");
		$query =  $this->db->get()->result_array();
		$i=0;
		foreach ($query as $i => $value) {
			$this->db->select('order_details.*, addon_services.title as addon_service_title');
			$this->db->join('addon_services', 'order_details.addon_service_id = addon_services.id','left');
			$this->db->from('order_details');
			$this->db->where(['order_details.order_id'=>$value['id']]);
			$details=$this->db->get()->result_array();
			$query[$i]['order_details']=$details;
		}
		return $query;
	}

    // view orders data from database
    public function orders_list_for_edit($id) 
	{
		$this->db->select('orders.*, subscription.title as subscription_plan_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, subscription.price as subscription_price');
		$this->db->from('orders'); 
		$this->db->join('subscription', 'orders.subscription_plan_id = subscription.id','left'); 
		$this->db->join('users', 'orders.user_id = users.id','left'); 
		$this->db->where(['orders.flag'=>'0', 'orders.id'=>$id]);
		$this->db->order_by("orders.id", "asc");
		$query =  $this->db->get()->result_array();
		$i=0;
		foreach ($query as $i => $value) {
			$this->db->select('order_details.*, addon_services.title as addon_service_title');
			$this->db->join('addon_services', 'order_details.addon_service_id = addon_services.id','left');
			$this->db->from('order_details');
			$this->db->where(['order_details.order_id'=>$value['id']]);
			$details=$this->db->get()->result_array();
			$query[$i]['order_details']=$details;
		}
		return $query;
	}

	function deleteRecord($id)
	{
		$data=array('flag'=>'1');
		$this->db->set('flag','flag',false);
		$this->db->where('id',$id);
		if($this->db->update('orders', $data)){
			$this->db->where('order_id', $id);
			$this->db->delete('order_details');
			return true;
		}
	}

    function editRecord($data, $id)
	{
		$this->db->select('orders.*');
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('id', $id);
		if($this->db->update('orders', $data)){
			$this->addOrderDetails($id);
			return true;
		}
	}

	function getUsers() { 
		$result = $this->db->select('id, name')->get('users')->result_array(); 
		$states = array(); 
		$states[''] = 'Select User...'; 
		foreach($result as $r) { 
			$states[$r['id']] = $r['name']; 
		} 
		return $states; 
	} 

	function getSubscription() { 
	$result = $this->db->select('id, title, price')->get('subscription')->result_array(); 
	// $states = array(); 
	// $states[''] = 'Select Subscription...'; 
	// foreach($result as $r) { 
	//     $states[$r['id']] = $r['title']; 
	// } 
	return $result; 
	} 
	function getAddonServices() { 
	$result = $this->db->select('id, title, price')->get('addon_services')->result_array(); 
	// $states = array(); 
	// $states[''] = 'Select Subscription...'; 
	// foreach($result as $r) { 
	//     $states[$r['id']] = $r['title']; 
	// } 
	return $result; 
	}

	function getPrice() { 
	$result = $this->db->select('id, title, price')->get('subscription')->result_array(); 
	$states = array(); 
	$states[''] = 'Select Amount...'; 
	foreach($result as $r) { 
		$states[$r['price']] = $r['price']; 
	} 
	return $states; 
	}

	// Send mail to customer for order
	function sendMail($id) {
		$this->db->select('orders.*, subscription.title as subscription_plan_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, subscription.price as subscription_price');
		$this->db->from('orders'); 
		$this->db->join('subscription', 'orders.subscription_plan_id = subscription.id','left'); 
		$this->db->join('users', 'orders.user_id = users.id','left'); 
		$this->db->where(['orders.flag'=>'0', 'orders.id'=>$id]);
		$this->db->order_by("orders.id", "asc");
		$query =  $this->db->get()->result_array();
		
		$orderId                    = $query['0']['order_id'];
		$customerName               = $query['0']['user_name'];
		$customerEmail              = $query['0']['user_email'];
		$subscription_plan_title    = $query['0']['subscription_plan_title'];
		// plan duration
		if($query['0']['plan_status']==1)  {$query['0']['plan_status']="Monthly";}
		if($query['0']['plan_status']==3)  {$query['0']['plan_status']="Quarterly";}
		if($query['0']['plan_status']==6)  {$query['0']['plan_status']="Half Yearly";}
		if($query['0']['plan_status']==12) {$query['0']['plan_status']="Yearly";}

		$plan_status                = $query['0']['plan_status'];
		$grand_total                = $query['0']['grand_total'];
		
		$config = Array(
		// For Server
		    'protocol' => 'mail',
			'smtp_host' => 'mail.muskowl.com',
			'smtp_port' => 587,
			'smtp_user' => 'hemendra@muskowl.com', // change it to yours
			'smtp_pass' => '#hemendra@2021#', // change it to yours

		// For Local
		    // 'protocol' => 'smtp',
    		// 'smtp_host' => 'ssl://smtp.googlemail.com',
			// 'smtp_port' => 465,
			// 'smtp_user' => 'hemendra.muskowl@gmail.com', // change it to yours
			// 'smtp_pass' => 'hss4u@mo', // change it to yours

			'mailtype' => 'html',
			'charset' => 'iso-8859-1',	
			'wordwrap' => TRUE
		);
		$message = 
		'
		<html>
			<head>
				<title>Order Successful !</title>
			</head>
			<body>
			<p> <b> Thanks for subscription our plan </b> </p>
			<p>You have subscribe this :</p>
			<table>
				<tr>
					<td>Subcription ID</td>
					<td>:</td>
					<td><b> '.$orderId.'</b></td>
				</tr>
				<tr>
					<td>Your Name</td>
					<td>:</td>
					<td><b> '.$customerName.' </b></td>
				</tr>
				<tr>
					<td>Subscription Title</td>
					<td>:</td>
					<td><b> '.$subscription_plan_title.' </b></td>
				</tr>
				<tr>
					<td>Subscription Status</td>
					<td>:</td>
					<td><b> '.$plan_status.' </b></td>
				</tr>
				<tr>
					<td>Grand Total</td>
					<td>:</td>
					<td><b> $'.$grand_total.' </b></td>
				</tr>
			</table>
			</body>
		</html>
		';
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('hemendra.muskowl@gmail.com'); // change it to yours
		$this->email->to($customerEmail);// change it to yours
		$this->email->subject('Order Successful');
		$this->email->message($message);
		if($this->email->send()) {
		  return true;
		} else {
		 show_error($this->email->print_debugger());
		}
	}
    
//Add to Cart Methods :---------------------------------------------------------------------------------------------------------------------
        
    // Get List of Cart Orders
    public function cart_list() 
		{
			$this->db->select('add_to_cart.*');
			$this->db->from('add_to_cart');
			
			$this->db->where('add_to_cart.flag','0');
			$this->db->order_by("add_to_cart.id", "asc");

			$query =  $this->db->get()->result_array();
			//print_r($query);exit;
			return $query;
		}

    // Insert cart data in database
    public function cart_insert($data) 
    {
        $this->db->insert('add_to_cart', $data);
        $id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        else { 
            return false;
        }
    }

    //Edit Cart data in database
    function editCartOrders($data, $id)
        {
			$this->db->select('add_to_cart.*');
            $this->db->select('*');
			$this->db->from('add_to_cart');
			$this->db->where('id', $id);

            if($this->db->update('add_to_cart', $data)){
				return true;
			}
		}

    //Delete Cart data in database
    function cartDelete($id)
		{
			$data=array('flag'=>'1');
			$this->db->set('flag','flag',false);
			$this->db->where('id',$id);

			if($this->db->update('add_to_cart', $data)){
				return true;
			}
		}

} // Main Class Closed

?>
