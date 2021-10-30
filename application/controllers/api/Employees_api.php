<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_api extends CI_Controller {

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

		$this->load->model('api/Employees_api_model');
		$this->load->library('form_validation');
	}

	function index()
	{
		$data = $this->Employees_api_model->fetch_all();

		$array = array(
			'success' => true,
			'Message' => 'Employees Details !',
			'result' => $data->result_array()
		);

		echo json_encode($array);
	}

	function insert()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		if($this->form_validation->run())
		{
			$employee_code = strip_tags($this->input->post('employee_code'));
       		$username = strip_tags($this->input->post('username'));
			// Check if the given employees already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'employee_code' => $employee_code,
                'username' => $username
            );
            $userCount = $this->Employees_api_model->getRows($con);
            
            if($userCount > 0){
                // Set the response and exit
               $array = array(
					'success' => false,
					'Message' => 'Employees already exists !',
					'result' => $userCount
				);
				echo json_encode($array);
            }else{
				$data = array(
					'name' => $this->input->post('name'),
					'employee_code' => $this->input->post('employee_code'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'role_id' => $this->input->post('role_id'),
					'department_id' => $this->input->post('department_id'),
					'username' => $this->input->post('username'),
					'password' => md5($this->input->post('password')),
					'pan_no' => $this->input->post('pan_no'),
					'blood_group' => $this->input->post('blood_group'),
					'gender' => $this->input->post('gender'),
					'aadhaar_no' => $this->input->post('aadhaar_no'),
					'medical_status' => $this->input->post('medical_status'),
					'report_no' => $this->input->post('report_no'),
					'dob' => $this->input->post('dob'),
					'photo' => $this->input->post('photo'),
					'address' => $this->input->post('address')
				);

				$this->Employees_api_model->insert_api($data);

				$array = array(
					'success' => true,
					'Message' => 'Employees data inserted successfully !',
					'result' => $data
				);
				echo json_encode($array);
			}
		}else{
			$array = array(
				'success' => false,
				'Message' => 'Please enter employees details !',
			);
			echo json_encode($array);
		}
	}
	
	function fetch_single()
	{
		if($this->input->post('id'))
		{
			$data = $this->Employees_api_model->fetch_single_user($this->input->post('id'));

			foreach($data as $row)
			{
				$output['name'] = $row['name'];
				$output['username'] = $row['username'];
				$output['password'] = $row['password'];				
			}
			echo json_encode($output);
		}
	}

	function update()
	{
		$this->form_validation->set_rules('id', 'ID', 'required');
		$id = $this->input->post('id');

		$con['returnType'] = 'count';
            $con['conditions'] = array(
                'id' => $id
            );

        $userCount = $this->Employees_api_model->getRows($con);
		
		if($userCount > 0)
		{	
			$data = array(
				'id' => $this->input->post('id'),
				'name' => $this->input->post('name'),
				'employee_code' => $this->input->post('employee_code'),
				'email' => $this->input->post('email'),
				'mobile_no' => $this->input->post('mobile_no'),
				'role_id' => $this->input->post('role_id'),
				'department_id' => $this->input->post('department_id'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'pan_no' => $this->input->post('pan_no'),
				'blood_group' => $this->input->post('blood_group'),
				'gender' => $this->input->post('gender'),
				'aadhaar_no' => $this->input->post('aadhaar_no'),
				'medical_status' => $this->input->post('medical_status'),
				'report_no' => $this->input->post('report_no'),
				'dob' => $this->input->post('dob'),
				'photo' => $this->input->post('photo'),
				'address' => $this->input->post('address')
			);

			$this->Employees_api_model->update_api($this->input->post('id'), $data);

			$array = array(
				'success' => true,
				'Message' => 'Employees data updated successfully !',
				'result' => $data
			);
		}
		else
		{
			$array = array(
				'success' => false,
				'Message' => 'Employees not found, please enter registered id !',
			);
		}
		echo json_encode($array);
	}

	function delete()
	{
		if($this->input->post('id'))
		{
			if($this->Employees_api_model->delete_single_user($this->input->post('id')))
			{
				$array = array(

					'success' => true,
					'Message' => 'Employees data deleted successfully !',
					'result' => $data
				);
			}
			else
			{
				$array = array(
					'error'		=>	true
				);
			}
			echo json_encode($array);
		}
	}

}


?>