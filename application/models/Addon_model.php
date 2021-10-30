<?php

Class Addon_model extends CI_Model {

// Insert registration data in database


   
    
    // Insert addon_services data in database
    public function addon_services_insert($data) 
    {
        $this->db->insert('addon_services', $data);
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
        $this->db->where('addon_service_id', $id);
        $this->db->delete('addon_features');
        foreach ($this->input->post('features') as $key => $value) :
                $this->db->set('addon_service_id', $id);
                $this->db->set('features', $value);
                $this->db->insert('addon_features');
        endforeach;
    }

    // view addon_services data from database
    public function addon_services_list() 
		{
			$this->db->select('addon_services.*');
			$this->db->from('addon_services');
			// $this->db->where('addon_services.flag','0');
			$this->db->order_by("addon_services.id", "asc");

			$query =  $this->db->get()->result_array();

            foreach ($query as $key => $value) {
                $this->db->select('features');
                $this->db->from('addon_features');
                $this->db->where('addon_features.addon_service_id',$value['id']);
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
			$data=array('flag'=>'1');
			// $this->db->set('flag','flag',false);
			$this->db->where('id',$id);
			if($this->db->delete('addon_services')){
                $this->db->where('addon_service_id',$id);
                $this->db->delete('addon_features');
				return true;
			}
		}
        
         public function getById($id) 
        {
            $this->db->select('addon_services.*');
            $this->db->from('addon_services');
            $this->db->where('addon_services.id',$id);
            $this->db->order_by("addon_services.id", "asc");

            $query =  $this->db->get()->row_array();

            foreach ($query as $key => $value) {
                $this->db->select('features');
                $this->db->from('addon_features');
                $this->db->where('addon_features.addon_service_id',$id);
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
            $this->db->update('addon_services', $data);
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