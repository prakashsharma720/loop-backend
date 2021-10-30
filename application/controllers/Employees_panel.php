<?php

//session_start(); //we need to start session in order to access it through CI

Class Employees_panel extends CI_Controller {

public function __construct() {
parent::__construct();
if(!$this->session->userdata['logged_in']['id']){
    redirect('User_authentication/index');
}
/*require_once APPPATH.'third_party/PHPExcel.php';
$this->excel = new PHPExcel(); */

// Load form helper library
$this->load->helper('form');
$this->load->helper('url');
// new security feature
$this->load->helper('security');
// Load form validation library
$this->load->library('form_validation');
// Load session library
$this->load->library('session');
$this->load->library('template');
// Load database
$this->load->model('Employees_model');
}

// Show list page
	public function index() {
			$data['title'] = 'Employees List';
			$data['employees'] = $this->Employees_model->employeesList();
			$data['roles'] = $this->Employees_model->getRoles();
			$data['departments'] = $this->Employees_model->getDepartments();	
			$this->template->load('template','employees_list',$data);
	}

	
	

	public function add() 
	{
			$data = array();
			$data['title'] = 'Add New Employees';
			//$data['employees'] = $this->Employees_model->employeesList();
			$data['roles'] = $this->Employees_model->getRoles();
			$data['emp_code'] = $this->Employees_model->getEmployeeCode();
			$voucher_no= $data['emp_code'];
            if($voucher_no<10){
            $employee_id_code='EC000'.$voucher_no;
            }
            else if(($voucher_no>=10) && ($voucher_no<=99)){
              $employee_id_code='EC00'.$voucher_no;
            }
            else if(($voucher_no>=100) && ($voucher_no<=999)){
              $employee_id_code='EC0'.$voucher_no;
            }
            else{
              $employee_id_code='EC'.$voucher_no;
            }
            //print_r($employee_id_code);exit;
            $data['employee_code']=$employee_id_code;
			$data['departments'] = $this->Employees_model->getDepartments();
			//print_r($data['departments']);exit;
			$this->template->load('template','employees_add',$data);
	}

	public function edit($id = NULL) 
	{
			$data = array();
			$result = $this->Employees_model->getById($id);

			if (isset($result['id']) && $result['id']) :
	            $data['id'] = $result['id'];
	        else:
	            $data['id'] = '';
	        endif;

			if (isset($result['name']) && $result['name']) :
	            $data['name'] = $result['name'];
	       else:
	            $data['name'] = '';
	        endif;	
	        if (isset($result['blood_group']) && $result['blood_group']) :
	            $data['blood_group'] = $result['blood_group'];
	       else:
	            $data['blood_group'] = '';
	        endif;

	        if (isset($result['employee_code']) && $result['employee_code']) :
	            $data['emp_code'] = $result['employee_code'];
	            $voucher_no= $data['emp_code'];
	            if($voucher_no<10){
	            $employee_id_code='EC000'.$voucher_no;
	            }
	            else if(($voucher_no>=10) && ($voucher_no<=99)){
	              $employee_id_code='EC00'.$voucher_no;
	            }
	            else if(($voucher_no>=100) && ($voucher_no<=999)){
	              $employee_id_code='EC0'.$voucher_no;
	            }
	            else{
	              $employee_id_code='EC'.$voucher_no;
	            }
	            //print_r($employee_id_code);exit;
	            $data['employee_code']=$employee_id_code;
	        else:
	            $data['employee_code'] = '';
	        endif;

	        if (isset($result['email']) && $result['email']) :
	            $data['email'] = $result['email'];
	       else:
	            $data['email'] = '';
	        endif;
	         
	        if (isset($result['mobile_no']) && $result['mobile_no']) :
	            $data['mobile_no'] = $result['mobile_no'];
	       else:
	        $data['mobile_no'] = '';
	        endif;

	        if (isset($result['designation']) && $result['designation']) :
	            $data['designation'] = $result['designation'];
	       else:
	            $data['designation'] = '';
	        endif;

	        if (isset($result['username']) && $result['username']) :
	            $data['username'] = $result['username'];
	       else:
	            $data['username'] = '';
	        endif;

 			if (isset($result['password']) && $result['password']) :
	            $data['password'] = $result['password'];
	       	else:
	            $data['password'] = '';
	        endif;

	        if (isset($result['role_id']) && $result['role_id']) :
	            $data['role_id'] = $result['role_id'];
	       else:
	            $data['role_id'] = '';
	        endif;

	        if (isset($result['department_id']) && $result['department_id']) :
	            $data['department_id'] = $result['department_id'];
	       else:
	            $data['department_id'] = '';
	        endif; 
	        if (isset($result['pan_no']) && $result['pan_no']) :
	            $data['pan_no'] = $result['pan_no'];
	       else:
	            $data['pan_no'] = '';
	        endif; 
	        if (isset($result['aadhaar_no']) && $result['aadhaar_no']) :
	            $data['aadhaar_no'] = $result['aadhaar_no'];
	       else:
	            $data['aadhaar_no'] = '';
	        endif; 
	        if (isset($result['gender']) && $result['gender']) :
	            $data['gender'] = $result['gender'];
	       else:
	            $data['gender'] = '';
	        endif; 
	        if (isset($result['address']) && $result['address']) :
	            $data['address'] = $result['address'];
	       else:
	            $data['address'] = '';
	        endif;
	        if (isset($result['dob']) && $result['dob']) :
	            $data['dob'] = $result['dob'];
	       else:
	            $data['dob'] = '';
	        endif;

	        if (isset($result['photo']) && $result['photo']) :
	            $data['photo'] = $result['photo'];
	       else:
	            $data['photo'] = '';
	        endif; 
	        if (isset($result['medical_status'])) :
	            $data['medical_status'] = $result['medical_status'];
	       else:
	            $data['medical_status'] = '';
	        endif; 

	        if (isset($result['report_no'])) :
	            $data['report_no'] = $result['report_no'];
	       else:
	            $data['report_no'] = '';
	        endif;

	        if (isset($result['id']) && $result['id']) :
				$data['title'] = 'Edit Employees';
			else:
				$data['title'] = 'Add New Employees';
			 endif;	
			//$data['employees'] = $this->Employees_model->employeesList();
			$data['roles'] = $this->Employees_model->getRoles();
			
			$data['departments'] = $this->Employees_model->getDepartments();
			//print_r($data['departments']);exit;
			$this->template->load('template','employees_edit',$data);
	}
	
	public function add_new_employee() {

		$this->form_validation->set_rules('mobile_no', 'Mobile Number ', 'required');
		$this->form_validation->set_rules('role_id', 'Role ', 'required');
		$this->form_validation->set_rules('department_id', 'Department', 'required');		
		
		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in'])){
			redirect('/Employees_panel/add');
			}else{
			$this->load->view('login_form');
			}
		}
		else 
		{
			     	
	               
	        $loginId=$this->session->userdata['logged_in']['id'];
	  		$config['upload_path']          = './uploads/employees';
	        $config['allowed_types']        = 'jpg|png';
	        $config['max_size']             = 100;
	        $config['max_width']            = 1024;
	        $config['max_height']           = 768;
	        $this->load->library('upload');
	        $this->upload->do_upload('photo');

			$data = array(
			'name' => $this->input->post('name'),
			'employee_code' => $this->input->post('employee_code'),
			'blood_group' => $this->input->post('blood_group'),
			'email' => $this->input->post('email'),
			'photo' => $this->upload->data()['file_name'],
			'gender' => $this->input->post('gender'),
			'medical_status' => $this->input->post('medical_status'),
			'report_no' => $this->input->post('report_no'),
			'mobile_no' => $this->input->post('mobile_no'),
			'pan_no' => $this->input->post('pan_no'),
			'aadhaar_no' => $this->input->post('aadhaar_no'),
			'dob' => date('Y-m-d',strtotime($this->input->post('dob'))),
			'address' => $this->input->post('address'),
			'role_id' => $this->input->post('role_id'),
			'department_id' => $this->input->post('department_id'),
			'username' => $this->input->post('username'),
			'created_by' => $loginId,
			'password' => md5($this->input->post('password')),
			);

			$result = $this->Employees_model->employee_insert($data);
			if ($result == TRUE) {
				
			$this->session->set_flashdata('success', 'Employees Added Successfully !');
			redirect('/Employees_panel/index', 'refresh');
			//$this->fetchSuppliers();
			} else {
			$this->session->set_flashdata('failed', 'Employees insertion failed!');
			redirect('/Employees_panel/index', 'refresh');
			}
		} 
	}

	public function editemployee($id) {
		$this->form_validation->set_rules('username', 'Username ', 'required');
		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in'])){
			$this->add();
			}else{
			$this->load->view('login_form');
			}
		}
		else 
		{
			$loginId=$this->session->userdata['logged_in']['id'];

			if($this->input->post('flag')==1){
				$status='1';
			}else{
				$status='0';
			}
			//print_r($this->input->post('old_image'));exit;
			

			$config['upload_path']          = './uploads/employees/';
	       	$config['allowed_types'] 		= 'gif|jpg|jpeg|png';
	        $config['overwrite'] 			= TRUE;
	        $config['max_size']             = 2048000;
	        $config['max_width']            = 1024;
	        $config['max_height']           = 768;
	        $this->load->library('upload');
	        $photo=$this->upload->data('file_name');
	        //print_r($photo);exit;
	       
			//$result=$this->upload->do_upload('photo');
	        $this->upload->do_upload('photo');
	       

	      	//print_r($this->upload->data()['file_name']);exit;
	      	if(!empty($this->upload->data()['file_name'])){
	      		$file_name=$this->upload->data()['file_name'];
	      		
	      	}else{
	      		$file_name=$this->input->post('old_image');
	      	}
			$data = array(
			'name' => $this->input->post('name'),
			'employee_code' => $this->input->post('employee_code'),
			'email' => $this->input->post('email'),
			//'photo' => $this->upload->data('file_name'),
			'photo' => $file_name,
			'blood_group' => $this->input->post('blood_group'),
			'gender' => $this->input->post('gender'),
			'medical_status' => $this->input->post('medical_status'),
			'report_no' => $this->input->post('report_no'),
			'mobile_no' => $this->input->post('mobile_no'),
			'pan_no' => $this->input->post('pan_no'),
			'aadhaar_no' => $this->input->post('aadhaar_no'),
			'dob' => date('Y-m-d',strtotime($this->input->post('dob'))),
			'address' => $this->input->post('address'),
			'role_id' => $this->input->post('role_id'),
			'department_id' => $this->input->post('department_id'),
			'username' => $this->input->post('username'),
			'status' => $status,
			'flag'=>$this->input->post('flag'),
			'edited_by'=>$loginId,
			);

			

			$result = $this->Employees_model->employee_update($data,$id);
			if ($result == TRUE) {
				if(!empty($this->upload->data()['file_name'])){
					$old_image=$this->input->post('old_image');
					unlink("uploads/employees/".$old_image);
				}
			$this->session->set_flashdata('success', 'Employees Updated Successfully !');
			//$this->fetchSuppliers();
			redirect('/Employees_panel/index', 'refresh');
			} else {
			$this->session->set_flashdata('failed', 'No Changes in Employees details !');
			redirect('/Employees_panel/index', 'refresh');
			}
		} 
	}
	public function deleteEmployee($id= null){
			$ids=$this->input->post('ids');
			if(!empty($ids)) {
				$Datas=explode(',', $ids);
	  	 		foreach ($Datas as $key => $id) {
	  	 			$this->Employees_model->deleteemployee($id);
	  	 			$result=$this->Employees_model->getById($id);
		  	 		if (isset($result['photo']) && $result['photo']) :
			            $user_image = $result['photo'];
			            unlink("uploads/employees/".$user_image);
			        endif;
	  	 		}
  	 			echo $this->session->set_flashdata('success', 'Employees deleted Successfully !');
			}else{
  	 		$id = $this->uri->segment('3');
  	 		$this->Employees_model->deleteemployee($id);
  	 		$result=$this->Employees_model->getById($id);
  	 		if (isset($result['photo']) && $result['photo']) :
	            $user_image = $result['photo'];
	            unlink("uploads/employees/".$user_image);
	        endif;

  	 		$this->session->set_flashdata('success', 'Employees deleted Successfully !');
  	 		redirect('/Employees_panel/index', 'refresh');
  	 		//$this->fetchSuppliers(); //render the refreshed list.
  	 		}

		}
		
		public function MyProfile($id= null){
		$data['title']='My Profile Details';
		$data['result']=$this->Employees_model->getById($id);
		$this->template->load('template','myprofile',$data);
		}


	}

?>