<?php

Class Users_model extends CI_Model {

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
    
    // Insert users data in database
    public function users_insert($data) 
    {
        $this->db->insert('users', $data);
        // $id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        else { 
            return false;
        }
    }
    
    // view users data from database
    public function user_list() 
		{
			$this->db->select('users.*');
			$this->db->from('users');
			
			$this->db->where('users.flag','0');
			$this->db->order_by("users.id", "asc");

			$query =  $this->db->get()->result_array();
			//print_r($query);exit;
			return $query;
		}

    function deleteRecord($id)
		{
			$data=array('flag'=>'1');
			$this->db->set('flag','flag',false);
			$this->db->where('id',$id);

			if($this->db->update('users', $data)){
				return true;
			}
		}

    function editRecord($data, $id)
        {
			$this->db->select('users.*');
            $this->db->select('*');
			$this->db->from('users');
			$this->db->where('id', $id);
			
            if($this->db->update('users', $data)){
				return true;
			}
		}

    function getUsers() { 
        $result = $this->db->select('id, name')->from('users')->where('flag','0')->get()->result_array(); 
        return $result; 
    }

    function myPasswordChange($users_id,$data){
        //echo $users_id;exit;
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $users_id);
        //$this->db->limit(1);
        //$this->db->get();
        if($this->db->update('users', $data)){
            return true;
        }else{
            return false;
        }
    }

    function updatePassword($email,$data){
        $this->db->select('*');
        $this->db->from('employees');
        $this->db->where('email', $email);
        /*$this->db->limit(1);
        $this->db->get();*/
        if($this->db->update('employees', $data)){
            return true;
        }else{
            return false;
        }
    }

}

?>