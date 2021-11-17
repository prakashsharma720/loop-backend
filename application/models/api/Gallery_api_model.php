<?php
class Gallery_api_model extends CI_Model
{

	function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('images');
        
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
	    $this->db->select('*');
        $this->db->from('images');
		$this->db->order_by('id');
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function insert_api($data = array()) { 
        if(!empty($data)){ 
             
			if(!array_key_exists("created", $data)){ 
                $data['created'] = date("Y-m-d H:i:s"); 
            } 
            if(!array_key_exists("modified", $data)){ 
                $data['modified'] = date("Y-m-d H:i:s"); 
            } 

            // Insert member data 
            $insert = $this->db->insert('images', $data); 
             
            // Return the status 
            return $insert?$this->db->insert_id():false; 
        } 
        return false; 
    }

	function fetch_single($user_id)
	{
		$this->db->select('images.*');
        $this->db->from('images');
        $this->db->where('user_id', $user_id);
        $this->db->order_by("images.user_id", "asc");

        $query =  $this->db->get()->result_array();
        return $query;
	}

	public function verify_user_id($user_id){
    	$condition = "id =" . "'" . $user_id . "'";
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

    public function verify_id($id){
    	$condition = "id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('images');
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


    public function update_api($data, $id) { 
        if(!empty($data) && !empty($id)){ 
            // Add modified date if not included
             
            // Update member data 
            $update = $this->db->update('images', $data, array('id' => $id)); 
             
            // Return the status 
            return $update?true:false; 
        } 
        return false; 
    } 

	function delete_single_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete('images');
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>
