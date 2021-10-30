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
        $this->db->insert('orders', $data);
        $id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        else { 
            return false;
        }
    }

    // view orders data from database
    public function orders_list() 
		{
			$this->db->select('orders.*');
			$this->db->from('orders');

			// $this->db->join('orders_details', 'orders.id = orders_details.orders_id','left'); 
			
			$this->db->where('orders.flag','0');
			$this->db->order_by("orders.id", "asc");

			$query =  $this->db->get()->result_array();
			//print_r($query);exit;
			return $query;
		}
    
    

        function deleteRecord($id)
		{
			$data=array('flag'=>'1');
			$this->db->set('flag','flag',false);
			$this->db->where('id',$id);

			if($this->db->update('orders', $data)){
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