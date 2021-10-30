<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }

		$this->load->model('api/Subscription_api_model');
		$this->load->library('form_validation');
	}

	function index()
	{
		$data['subscriptions'] = $this->Subscription_api_model->fetch_all();

		$array = array(
			'success' => true,
			'Message' => 'Subscription Detail !',
			'result' => $data
		);

		echo json_encode($array);
	}

	function addon_services()
	{
		$this->load->model('Addon_model');
		$data['addon_services'] = $this->Addon_model->addon_services_list();

		$array = array(
			'success' => true,
			'Message' => 'Addon Services List !',
			'result' => $data
		);

		echo json_encode($array);
	}


	function insert()
	{
		$title = $this->input->post('title');

		$this->form_validation->set_rules('title', 'title', 'required');
       
       if ($this->form_validation->run() == FALSE) 
       {
			$array = array(
				'success' => false,
				'message' => 'Please enter subscription details !',
				'result' => $title
			);
			echo json_encode($array);   
       } 
       else 
       {
    	    $data = array(
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),  
				'price' => $this->input->post('price'),  
				'duration' => $this->input->post('duration'),
			);
			
           $result = $this->Subscription_api_model->insert_api($data);

		   if ($result == TRUE) {
				$array = array(
					'success' => true,
					'message' => 'Subscription Inserted Successfully !',
					'result' => $data
				);
				echo json_encode($array);
           } else
			{
				$array = array(
					'success' => false,
					'message' => 'Subscription Not Inserted !',
				);
				echo json_encode($array);
			}
       }
	}
	
	function fetch_single()
	{
		$id = $this->input->post('id');

		$result = $this->Subscription_api_model->verify_id($id);

		if($result > 0)
		{
			$data['subscription'] = $this->Subscription_api_model->fetch_single_user($this->input->post('id'));
			$array = array(
				'success' => true,
				'message' => 'Subscription Details !',
				'result' => $data
			);
			echo json_encode($array);
		}else{
			$array = array(
				'success' => false,
				'message' => 'Subscription Not Found !',
			);
			echo json_encode($array);
		}
	}

	function update()
	{
		$this->form_validation->set_rules('id', 'ID', 'required');
		if($this->form_validation->run())
		{	
			$data = array(
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'price' => $this->input->post('price'),
				'duration' => $this->input->post('duration')
			);

			$id =$this->input->post('id');
			$this->Subscription_api_model->update_api($id, $data);

			$array = array(
				'success' => true,
				'message' => 'Subscription Updated Successfully !',
				'result' => $data
			);
		}
		else
		{
			$array = array(
				'success' => false,
				'message' => 'Please Enter ID for Update Details !'
			);
		}
		echo json_encode($array);
	}

	function delete()
	{
		if($this->input->post('id'))
		{
			if($this->Subscription_api_model->delete_single_user($this->input->post('id')))
			{
				$array = array(
					'success' => true,
					'message' => 'Subscription Deleted Successfully !',
					'result' => $data
				);
			}
			else
			{
				$array = array(
					'success' => false,
					'message' => 'Subscription Not Deleted !',
				);
			}
			echo json_encode($array);
		}
	}

}


?>