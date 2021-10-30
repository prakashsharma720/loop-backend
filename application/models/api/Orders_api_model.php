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

	function insert_api($data)
	{
		$this->db->insert('orders', $data);
		$order_id = $this->input->post('order_id');
		$this->addOrderDetails($order_id);
	}

	public function addOrderDetails($order_id){
        $this->db->where('order_id', $order_id);
        $this->db->delete('order_details');
        
        //  foreach ($this->input->post('addon_service')  as $key => $value) {
        //     $this->db->set('order_id', $order_id);
        //     $this->db->set('addon_service_id', $value['addon_service_id']);
        //     $this->db->set('qty', $value['qty']);
        //     $this->db->set('price', $value['price']);
        //     $this->db->set('total', $value['total']);
        //     $this->db->insert('order_details');
        // }
        
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
