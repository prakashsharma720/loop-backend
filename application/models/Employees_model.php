<?php

Class Employees_model extends CI_Model {

	// Insert registration data in database
	public function employee_insert($data) 
	{

		// Query to check whether username already exist or not
		$condition = "employee_code =" . "'" . $data['employee_code'] . "'";

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
	public function employee_update($data,$id) 
	{
		/*print_r($id);
		print_r($data);
		exit;
		*/
		$this->db->where('id', $id);
		$this->db->update('employees', $data);
		if ($this->db->affected_rows() > 0) {
		return true;
		}
		 else { 
		return false;
		}
	}
	function getEmployeeCode(){
    $count=0;
    $this->db->select_max('employee_code');
    $this->db->from('employees');
    $query=$this->db->get()->row_array();
    //print_r($query['employee_code']);exit;
    $count=$query['employee_code']+1;
    return $count;
   
	}

	public function employeesList() 
	{
		
		$this->db->select('employees.*,roles.role,departments.department_name');
		$this->db->from('employees');
		$this->db->join('roles', 'employees.role_id = roles.id', 'left'); 
		$this->db->join('departments', 'employees.department_id = departments.id', 'left'); 
		$this->db->where('employees.flag','0');
		$this->db->order_by("employees.id", "asc");
		$query = $this->db->get();
		//print_r($query);exit;
		return $query->result_array();

	}
	public function getById($id) {
       $this->db->select('employees.*,roles.role,departments.department_name');
		$this->db->from('employees');
		$this->db->join('roles', 'employees.role_id = roles.id', 'left'); 
		$this->db->join('departments', 'employees.department_id = departments.id', 'left'); 
        $this->db->where('employees.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    function getRoles() { 
        $result = $this->db->select('id, role')->from('roles')->where('flag','0')->get()->result_array(); 
        $roles = array(); 
        $roles[''] = 'Select Role...'; 
        foreach($result as $r) { 
            $roles[$r['id']] = $r['role']; 
        } 
        
        return $roles; 
    } 
      function getDepartments() { 
        $result = $this->db->select('id, department_name,department_code')->from('departments')->where('flag','0')->get()->result_array(); 
        //$result= $result->result_array();
        $departments = array(); 
        $departments[' '] = 'Select department...'; 
        foreach($result as $r) { 
            $departments[$r['id']] = $r['department_name'].' ('.$r['department_code'].')'; 
        } 
        
        return $departments; 
    } 
    function deleteemployee($id)
		{
			//if($this->db->delete('suppliers', "id = ".$id)) return true;
			$data=array('flag'=>'1','status'=>'1');
			$this->db->set('flag','flag',false);
			$this->db->where('id',$id);
			if($this->db->update('employees', $data)){
				return true;
			}
		}



}
?>