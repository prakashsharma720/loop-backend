<?php

Class Subscription_model extends CI_Model {

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
    
    // Insert subscription data in database
    public function subscription_insert($data) 
    {
        $this->db->insert('subscription', $data);
        $id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            $this->addFeatures($id);
            return true;
        }
        else { 
            return false;
        }
    }
    public function addFeatures($id){
        $this->db->where('subscription_id', $id);
        $this->db->delete('subscription_details');
        if(!empty($this->input->post('features')[0])){
             foreach ($this->input->post('features') as $key => $value) :
                $this->db->set('subscription_id', $id);
                $this->db->set('features', $value);
                $this->db->insert('subscription_details');
            endforeach;
        }
    }

    // view subscription data from database
    public function subscription_list() 
		{
			$this->db->select('subscription.*');
			$this->db->from('subscription');
			$this->db->where('subscription.flag','0');
			$this->db->order_by("subscription.id", "asc");

			$query =  $this->db->get()->result_array();

            foreach ($query as $key => $value) {
                $this->db->select('features');
                $this->db->from('subscription_details');
                $this->db->where('subscription_details.subscription_id',$value['id']);
                $features_list=$this->db->get()->result_array();
                $query[$key]['features_list']=$features_list;
               
            }
			// echo "<pre>";
            //print_r($query);
             //echo "</pre>";exit;
           
			return $query;
		}

    function deleteRecord($id)
        {
            //$data=array('flag'=>'1');
            // $this->db->set('flag','flag',false);
            $this->db->where('id',$id);
            if($this->db->delete('subscription')){
                $this->db->where('subscription_id',$id);
                $this->db->delete('subscription_details');
                return true;
            }
        }
         public function getById($id) 
        {
            $this->db->select('subscription.*');
            $this->db->from('subscription');
            $this->db->where('subscription.id',$id);
            $this->db->order_by("subscription.id", "asc");

            $query =  $this->db->get()->row_array();

            foreach ($query as $key => $value) {
                $this->db->select('features');
                $this->db->from('subscription_details');
                $this->db->where('subscription_details.subscription_id',$id);
                $features_list=$this->db->get()->result_array();
                $query['features_list']=$features_list;
               
            }
            // echo "<pre>";
            // print_r($query);
            //  echo "</pre>";exit;
           
            return $query;
        }
    function editRecord($data, $id)
        {
			$this->db->where('id', $id);
            $this->db->update('subscription', $data);
            $this->addFeatures($id);
			if ($this->db->affected_rows() > 0) {
                return true;
            }
             else { 
                return false;
            }
		}

}

?>