<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        // Load the database library
        $this->load->database();
        
        $this->userTbl = 'users';
    }

    /*
     * Get rows from the employees table
     */
    function getRows($params = array()){
        $this->db->select('*');
        $this->db->from($this->userTbl);
        
        //fetch data by conditions
        // $params = array();
        
        if(array_key_exists("conditions", $params)){
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
    
    /*
     * Insert user data
     */
    public function insert($data){
        //add created and modified date if not exists
        // if(!array_key_exists("created_on", $data)){
        // $data['created_on'] = date("Y-m-d H:i:s");
        // }
        // if(!array_key_exists("edited_on", $data)){
        // $data['edited_on'] = date("Y-m-d H:i:s");
        // }
        
        //insert user data to employees table
        $insert = $this->db->insert($this->userTbl, $data);
        
        //return the status
        return $insert = $this->db->insert_id($insert);
    }

    function myPasswordChange($id,$data){
        //echo $users_id;exit;
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        //$this->db->limit(1);
        //$this->db->get();
        if($this->db->update('users', $data)){
            return true;
        }else{
            return false;
        }
    }
    
    function changePassword($mobile, $data){
        //echo $users_id;exit;
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('mobile', $mobile);
        //$this->db->limit(1);
        //$this->db->get();
        if($this->db->update('users', $data)){
            return true;
        }else{
            return false;
        }
    }
    
    /*
     * Update user data
     */
    public function update($id, $userData) {
         //echo $users_id;exit;
         $this->db->select('*');
         $this->db->from('users');
         $this->db->where('id', $id);
         //$this->db->limit(1);
         //$this->db->get();
         if($this->db->update('users', $userData)){
             return true;
         }else{
             return false;
         } 
    }

    /*
     * Delete user data
     */
    public function delete($id){
        //update user from employees table
        $delete = $this->db->delete('users',array('id'=>$id));
        //return the status
        return $delete?true:false;
    }

    public function verify_id($id){
    	$condition = "id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('users');
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

    public function verify_mobile($mobile){
    	$condition = "mobile =" . "'" . $mobile . "'";
		$this->db->select('*');
		$this->db->from('users');
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

    public function verify_password($password){
    	$condition = "password =" . "'" . $password . "'";
		$this->db->select('*');
		$this->db->from('users');
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

    function updateOtp($data, $mobile){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('mobile', $mobile);
        if($this->db->update('users', $data)){
            return true;
        }
    }

    public function verify_otp($otp, $mobile){
		$this->db->select('*');
		$this->db->from('users');
        $array = array('otp' => $otp, 'mobile' => $mobile);
		$this->db->where($array);
		$this->db->limit(1);

		$query = $this->db->get();
        
		if ($query->num_rows() == 1) { 
		    return true;
		} 
		else {
		    return false;
		}
    }

} // Main Method Closed 