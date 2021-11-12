<?php
class Orders_api_model extends CI_Model
{

	function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('orders');
        
        //fetch data by conditions
        if(array_key_exists("conditions",$params)){
            foreach($params['conditions'] as $key => $value){
                $this->db->where($key,$value);
            }
        }
        
        if(array_key_exists("id",$params)){
            $this->db->where('id',$params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            //set start and limit
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();    
            }elseif(array_key_exists("returnType",$params) && $params['returnType'] == 'single'){
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->row_array():false;
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():false;
            }
        }
        
        //print_r($result);exit;
        //return fetched data
        return $result;
    }

	function fetch_all()
	{
		$this->db->order_by('id');
		return $this->db->get('orders');
	}
    // view orders data from database
    function orders_list() 
	{
		$this->db->select('orders.*, subscription.title as subscription_plan_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, subscription.price as subscription_price');
		$this->db->from('orders'); 
		$this->db->join('subscription', 'orders.subscription_plan_id = subscription.id','left'); 
		$this->db->join('users', 'orders.user_id = users.id','left');		
        if(!empty($this->input->post('user_id'))){
			$this->db->where('orders.user_id',$this->input->post('user_id'));
	    }
		if(!empty($this->input->post('order_id'))){
			$this->db->where('orders.order_id',$this->input->post('order_id'));
	    }
		if(!empty($this->input->post('id'))){
			$this->db->where('orders.id',$this->input->post('id'));
	    }
		if(!empty($this->input->post('from_date'))){
			$this->db->where('orders.order_date >=',$this->input->post('from_date'));
	    }
		if(!empty($this->input->post('upto_date'))){
			$this->db->where('orders.order_date <=',$this->input->post('upto_date'));
	    }
		$this->db->where('orders.flag','0');
		$this->db->order_by("orders.id", "asc");
		$query =  $this->db->get()->result_array();
		$i=0;
		foreach ($query as $i => $value) {
			$this->db->select('order_details.*, addon_services.title as addon_service_title');
			$this->db->join('addon_services', 'order_details.addon_service_id = addon_services.id','left');
			$this->db->from('order_details');
			$this->db->where('order_details.order_id', $value['id']);
			$details=$this->db->get()->result_array();
			$query[$i]['order_details']=$details;
		}
		return $query;
	}
	function insert_api($data)
	{
		$this->db->insert('orders', $data);
		if ($this->db->affected_rows() > 0) 
		{
			$order_id = $this->db->insert_id();
			$this->addOrderDetails($order_id);
			$this->insertNotif($order_id);
			$this->sendMail($order_id);
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
		$query    = $this->db->get()->result_array();

		$user_id  = $query['0']['user_id'];
		$order_id = $query['0']['order_id'];
		// Data for Notification
		$notifData['employee_id'] = $user_id;
		$notifData['type']        = 'Order Create';
		$notifData['subject']     = 'Place Order';
		$notifData['message']     =  'Order Successful : Order ID : '.$order_id;
		$notifData['datetime']    = date('Y-m-d H:i:s');
		$notifData['status']      = '0';
		$this->db->insert('notifications', $notifData);
    }

	public function addOrderDetails($order_id){
        $this->db->where('order_id', $order_id);
        $this->db->delete('order_details');

        $addon=explode(',',$this->input->post('addon_service_id'));
        $qty=explode(',',$this->input->post('qty'));
        $price=explode(',',$this->input->post('price'));
        $total=explode(',',$this->input->post('total'));
        
        if(!empty($addon)){
             foreach ($addon as $key => $value) :
                $this->db->set('order_id', $order_id);
                $this->db->set('addon_service_id', $value);
                $this->db->set('qty', $qty[$key]);
                $this->db->set('price', $price[$key]);
                $this->db->set('total', $total[$key]);
                $this->db->insert('order_details');
            endforeach;
        }
    }
	public function addon_insert(){
		if(!empty($this->input->post('order_date'))) {
			$data['order_date'] = date('Y-m-d',strtotime($this->input->post('order_date')));
		}
		if(!empty($this->input->post('user_id'))) {
			$data['user_id'] = $this->input->post('user_id');
		}
		if(!empty($this->input->post('addon_service_id'))) {
			$data['addon_service_id'] = $this->input->post('addon_service_id');
		}
		if(!empty($this->input->post('price'))) {
			$data['price'] = $this->input->post('price');
		}
		if(!empty($this->input->post('qty'))) {
			$data['qty'] = $this->input->post('qty');
		}
		if(!empty($this->input->post('grand_total'))) {
			$data['grand_total'] = $this->input->post('grand_total');
		}
		if(!empty($this->input->post('payment_terms'))) {
			$data['payment_terms'] = $this->input->post('payment_terms');
		}
		if(!empty($this->input->post('payment_status'))) {
			$data['payment_status'] = $this->input->post('payment_status');
		}
		if(!empty($this->input->post('user_id'))) {
			$data['created_by'] = $this->input->post('user_id');
		}

        $this->db->insert('order_addons', $data);
        $order_id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
			$this->insertNotif_addon_add($order_id);
			$this->sendMail_addon_add($order_id);
            return True;
        }
        else { 
            return False;
        }
	}
    // Insert Notification in Database
    public function insertNotif_addon_add($order_id) {
		// Get Details
		$this->db->select('order_addons.*, addon_services.title as addon_service_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, addon_services.price as addon_service_price');
		$this->db->from('order_addons'); 
		$this->db->join('addon_services', 'order_addons.addon_service_id = addon_services.id','left'); 
		$this->db->join('users', 'order_addons.user_id = users.id','left');
		$this->db->where(['order_addons.flag'=>'0', 'order_addons.id'=>$order_id]);
		$query               = $this->db->get()->result_array();
	
		$user_id             = $query['0']['user_id'];
		$addon_service_title = $query['0']['addon_service_title'];
		// Data for Notification
		$notifData['employee_id'] = $user_id;
		$notifData['type']        = 'Addon Order Create';
		$notifData['subject']     = 'Place Order';
		$notifData['message']     =  'Addon Order Successful : Addon Title : '.$addon_service_title;
		$notifData['datetime']    = date('Y-m-d H:i:s');
		$notifData['status']      = '0';
		$this->db->insert('notifications', $notifData);
    }
	// Send mail to customer for order
	function sendMail_addon_add($order_id) {
		$this->db->select('order_addons.*, addon_services.title as addon_service_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, addon_services.price as addon_service_price');
		$this->db->from('order_addons'); 
		$this->db->join('addon_services', 'order_addons.addon_service_id = addon_services.id','left'); 
		$this->db->join('users', 'order_addons.user_id = users.id','left');
		$this->db->where(['order_addons.flag'=>'0', 'order_addons.id'=>$order_id]);
		$query =  $this->db->get()->result_array();
		
		$customerName               = $query['0']['user_name'];
		$customerEmail              = $query['0']['user_email'];
		$subscription_plan_title    = $query['0']['addon_service_title'];

		$plan_price                 = $query['0']['price'];
		$plan_qty                   = $query['0']['qty'];
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
			<p> <b> Thanks for addon our plan </b> </p>
			<p>You have addon this :</p>
			<table>
				<tr>
					<td>Your Name</td>
					<td>:</td>
					<td><b> '.$customerName.' </b></td>
				</tr>
				<tr>
					<td>Addon Service Title</td>
					<td>:</td>
					<td><b> '.$subscription_plan_title.' </b></td>
				</tr>
				<tr>
					<td>Addon Plan Price</td>
					<td>:</td>
					<td><b> '.$plan_price.' </b></td>
				</tr>
				<tr>
					<td>Addon Plan Qty</td>
					<td>:</td>
					<td><b> '.$plan_qty.' </b></td>
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
	// Get Addon Order's List
	function addon_order_list() {
		$this->db->select('order_addons.*, addon_services.title as addon_service_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, addon_services.price as addon_service_price');
		$this->db->from('order_addons'); 
		$this->db->join('addon_services', 'order_addons.addon_service_id = addon_services.id','left'); 
		$this->db->join('users', 'order_addons.user_id = users.id','left');		
        if(!empty($this->input->post('user_id'))){
			$this->db->where('order_addons.user_id',$this->input->post('user_id'));
	    }
		if(!empty($this->input->post('id'))){
			$this->db->where('order_addons.id',$this->input->post('id'));
	    }
		if(!empty($this->input->post('from_date'))){
			$this->db->where('order_addons.order_date >=',$this->input->post('from_date'));
	    }
		if(!empty($this->input->post('upto_date'))){
			$this->db->where('order_addons.order_date <=',$this->input->post('upto_date'));
	    }
		$this->db->where('order_addons.flag','0');
		$this->db->order_by("order_addons.id", "desc");
		$query =  $this->db->get()->result_array();
		return $query;
	}

	function fetch_single_order($user_id)
	{
		$this->db->select('orders.*');
        $this->db->from('orders');
		$multipleWhere = ['user_id' => $user_id, 'plan_status' => 'running'];
        $this->db->where($multipleWhere);
        $this->db->order_by("orders.user_id", "asc");

        $query =  $this->db->get()->result_array();
        return $query;
	}

	function update_api($user_id, $data)
	{
		$this->db->where('id', $user_id);
		$this->db->update('orders', $data);
	}

	function delete_single_order($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete('orders');
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function verify_user_id($user_id){
    	$condition = "user_id =" . "'" . $user_id . "'";
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
		return $query->result();
		} 
		else {
		return false;
		}
    }
	// Send mail to customer for order
	function sendMail($order_id) {
		$this->db->select('orders.*, subscription.title as subscription_plan_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, subscription.price as subscription_price');
		$this->db->from('orders'); 
		$this->db->join('subscription', 'orders.subscription_plan_id = subscription.id','left'); 
		$this->db->join('users', 'orders.user_id = users.id','left'); 
		$this->db->where(['orders.flag'=>'0', 'orders.id'=>$order_id]);
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

//-------------------------------------------------Add To Cart Methods------------------------------------------------------------------------


    function getRowsCart($params = array()){
        $this->db->select('*');
        $this->db->from('add_to_cart');
        
        //fetch data by conditions
        if(array_key_exists("conditions",$params)){
            foreach($params['conditions'] as $key => $value){
                $this->db->where($key,$value);
            }
        }
        
        if(array_key_exists("id",$params)){
            $this->db->where('id',$params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            //set start and limit
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();    
            }elseif(array_key_exists("returnType",$params) && $params['returnType'] == 'single'){
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->row_array():false;
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():false;
            }
        }
        return $result;
    }

    //Add To Cart : Insert Method
    function insert_cart($data)
	{
		if(!array_key_exists("created_on", $data)){ 
			$data['created_on'] = date("Y-m-d H:i:s"); 
		} 

		$this->db->insert('add_to_cart', $data);
	}

    //Add To Cart : Update Method
    function update_cart($user_id, $data)
	{
		if(!array_key_exists("edited_on", $data)){ 
			$data['edited_on'] = date("Y-m-d H:i:s"); 
		} 
		$this->db->where('id', $user_id);
		$this->db->update('add_to_cart', $data);
	}
    
}

?>
