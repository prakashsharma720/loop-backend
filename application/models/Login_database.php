<?php

Class Login_Database extends CI_Model {

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
public function login($data) {
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
public function read_user_information($username) {

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

		function updateOtp($data,$id){
			$this->db->select('*');
			$this->db->from('employees');
			$this->db->where('id', $id);
			if($this->db->update('employees', $data)){
				return true;
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
		function myPasswordChange($emp_id,$data){
			//echo $emp_id;exit;
			$this->db->select('*');
			$this->db->from('employees');
			$this->db->where('id', $emp_id);
			//$this->db->limit(1);
			//$this->db->get();
			if($this->db->update('employees', $data)){
				return true;
			}else{
				return false;
			}
		}

		public function TotalCandidates()
		{
			$this->db->select('*');
			$this->db->from('send_emails');
			$query = $this->db->get();
			return $query->num_rows();
		}
		public function FetchallEmails()
		{
			$this->db->select('email');
			$this->db->from('send_emails');
			$query =  $this->db->get()->result_array();
			return $query;
		}
		
    function get_menu_tree($parent_id) 
	{
		global $con;
		$menu = "";
		$sqlquery = " SELECT * FROM menus where flag='0' and parent_id='" .$parent_id . "' ";
		$res=mysqli_query($con,$sqlquery);
	    while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)) 
		{
	           $menu .="<li class='nav-item has-treeview menu-open'><a class='nav-link active' href='".$row['link']."'>".$row['menu_name']."</a>";
			   
			   $menu .= "<ul>".get_menu_tree($row['menu_id'])."</ul>"; //call  recursively
			   
	 		   $menu .= "</li>";
	 
	    }
	    
	    return $menu;
	} 
	

    public function verify_email($email){
    	$condition = "email =" . "'" . $email . "'";
		$this->db->select('*');
		$this->db->from('employees');
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
	
	function getEmployees() { 
        $result = $this->db->select('id, name')->from('employees')->where('flag','0')->get()->result_array(); 
        return $result; 
    }

}

?>