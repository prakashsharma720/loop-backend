<?php

Class Menus extends CI_Model {

	// Insert registration data in database
	public function menu_insert($data) 
	{

		// Query to check whether username already exist or not
		//$condition = "menu_name =" . "'" . $data['menu_name'] . "'";
/*
		$this->db->select('*');
		$this->db->from('menus');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();*/
		//print_r($data);exit;
		/*if ($query->num_rows() == 0) {*/
		// Query to insert data in database
		$this->db->insert('menus', $data);
		if ($this->db->affected_rows() > 0) {
		return true;
		} else { 
		return false;
		}
	}
	public function menu_update($data,$id) 
	{
		/*print_r($id);
		print_r($data);
		exit;
		*/
		$this->db->where('id', $id);
		$this->db->update('menus', $data);
		if ($this->db->affected_rows() > 0) {
		return true;
		}
		else { 
		return false;
		}
	}
	public function menusList() 
	{
		
		$this->db->select('c1.*,c2.menu_name as parent');
		$this->db->from('menus as c1');
		//$this->db->join('menus', 'menus.parent_id = menus.id', 'left');
        $this->db->join('menus c2', 'c1.parent_id = c2.id', 'left');
		$this->db->order_by("c1.id", "ASC");
		$query = $this->db->get();
        //print_r($query->result_array());exit;
		return $query->result_array();

	}
	public function getById($id) {
       	$this->db->select('*');
		$this->db->from('menus');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
   	public function delete($id) {
       	$this->db->delete('menus', array('id' => $id));
        if ($this->db->affected_rows() > 0) {
		return true;
		}
		else { 
		return false;
		}
    }
      function getParents() { 
        $result = $this->db->select('id, menu_name')->from('menus')->get()->result_array(); 
        //$result= $result->result_array();
        $parentMenus = array(); 
        $parentMenus[''] = 'Select Parent...'; 
        foreach($result as $r) { 
            $parentMenus[$r['id']] = $r['menu_name']; 
        } 
        
        return $parentMenus; 
    } 
     function getRoles() { 
        $result = $this->db->select('id, role')->from('roles')->where('flag','0')->get()->result_array(); 
        //$result= $result->result_array();
        $parentMenus = array(); 
        $parentMenus[''] = 'Select Role...'; 
        foreach($result as $r) { 
            $parentMenus[$r['id']] = $r['role']; 
        } 
        
        return $parentMenus; 
    }

    function getEmployees() { 
        $result1 = $this->db->select('id, name')->from('employees')->where('flag','0')->get()->result_array(); 
        //$result= $result->result_array();
        $parentMenus1 = array(); 
        $parentMenus1[''] = 'Select Employee...'; 
        foreach($result1 as $r) { 
            $parentMenus1[$r['id']] = $r['name']; 
        } 
        
        return $parentMenus1; 
    }
    public function get_menus(){

        $this->db->select('*');
        $this->db->from('menus');
        $this->db->where('parent_id', 0);
        //Add here role condition
        $parent = $this->db->get();

        $categories = $parent->result();
        $i=0;
        foreach($categories as $p_cat){

            $categories[$i]->sub = $this->sub_menus($p_cat->id);
            $i++;
        }
        return $categories;
    }
    public function sub_menus($id){

        $this->db->select('*');
        $this->db->from('menus');
        $this->db->where('parent_id', $id);
         //add here role condition
        $child = $this->db->get();
        $categories = $child->result();
        $i=0;
        foreach($categories as $p_cat){

            $categories[$i]->sub = $this->sub_menus($p_cat->id);
            $i++;
        }
        return $categories;       
    }

     public function FindOldUserRightsRoles($id)
    {
        $condition = "role_id =" . "'" .$id. "'";
        $this->db->select('*');
        $this->db->from('user_rights');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

     public function FindOldUserRightsEmployees($id)
    {
        $condition = "employee_id =" . "'" .$id. "'";
        $this->db->select('*');
        $this->db->from('user_rights');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function addUserRightsRole($data){
        $condition = "role_id =" . "'" . $data['role_id'] . "'";
        $this->db->select('*');
        $this->db->from('user_rights');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        //print_r($data);exit;
        if ($query->num_rows() == 0) {
        // Query to insert data in database
        $this->db->insert('user_rights', $data);
        if ($this->db->affected_rows() > 0) {
        return true;
        } else { 
        return false;
        }
    }  else{
            $this->db->where('role_id', $data['role_id']);
            $this->db->update('user_rights', $data);
            if ($this->db->affected_rows() > 0) {
            return true;
            }
            else { 
            return false;
            }

    }

    }
        public function addUserRightsEmployee($data)
        {
            $condition = "employee_id =" . "'" . $data['employee_id'] . "'";
            $this->db->select('*');
            $this->db->from('user_rights');
            $this->db->where($condition);
            $this->db->limit(1);
            $query = $this->db->get();
            //print_r($data);exit;
            if ($query->num_rows() == 0) {
            // Query to insert data in database
            $this->db->insert('user_rights', $data);
            if ($this->db->affected_rows() > 0) {
            return true;
            } else { 
            return false;
            }
            }  
            else{
                $this->db->where('employee_id', $data['employee_id']);
                $this->db->update('user_rights', $data);
                if ($this->db->affected_rows() > 0) {
                return true;
                }
                else { 
                return false;
                }

        }
    }

}
?>