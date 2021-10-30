<?php
class Subscription_api_model extends CI_Model
{

	function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('subscription');

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
			return $query;
	}

	function insert_api($data)
	{
		$this->db->insert('subscription', $data);
        $id = $this->db->insert_id();
        $this->addFeatures($id);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
	}

    public function addFeatures($id){
        $this->db->where('subscription_id', $id);
        $this->db->delete('subscription_details');
        
        $features = $this->input->post('features');

        foreach ($features as $key => $value):
                $this->db->set('subscription_id', $id);
                $this->db->set('features', $value);
                $this->db->insert('subscription_details');
        endforeach;
    }

	function fetch_single_user($user_id)
	{
        $this->db->select('subscription.*');
        $this->db->from('subscription');
        $this->db->where('id', $user_id);
        $this->db->order_by("subscription.id", "asc");

        $query =  $this->db->get()->result_array();

        foreach ($query as $key => $value) {
            $this->db->select('features');
            $this->db->from('subscription_details');
            $this->db->where('subscription_details.subscription_id',$value['id']);
            $features_list=$this->db->get()->result_array();
            $query[$key]['features_list']=$features_list;

        }
        return $query;

	}

    function update_api($id, $data)
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

	function delete_single_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete('subscription');
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

    public function verify_id($id){
    	$condition = "id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('subscription');
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


}

?>