<?php

//session_start(); //we need to start session in order to access it through CI

Class Notifications extends CI_Controller {

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

//$this->load->library('encryption');

// Load session library
$this->load->library('session');


$this->load->library('template');

// Load database
//$this->load->model('notifications_model');
$this->load->model('notifications_model');
//$this->load->library('excel');
}

// Show login page
public function add($id = NULL) {
	$data = array();
	
	$data['title']='Create New Notification';
	//$data['suppliers']=$this->notifications_model->getSuppliers();
	$data['grids']=$this->notifications_model->getGrids();
	$data['raw_materials']=$this->notifications_model->getCategories();
	$data['categories']=$this->notifications_model->getSupplierCategories();
	//$data['states']=$this->notifications_model->getStates();
	$this->template->load('template','notification_master',$data);

	//$this->load->view('footer');
	
	}
public function edit($id = NULL) {
	$data = array();
	$result = $this->notifications_model->getById($id);
	//print_r($result);exit;
	if (isset($result['id']) && $result['id']) :
        $data['id'] = $result['id'];
    else:
        $data['id'] = '';
    endif;

    if (isset($result['supplier_id']) && $result['supplier_id']) :
        $data['supplier_id'] = $result['supplier_id'];
    else:
        $data['supplier_id'] = '';
    endif; 
     if (isset($result['categories_id']) && $result['categories_id']) :
        $data['categories_id'] = $result['categories_id'];
    else:
        $data['categories_id'] = '';
    endif;

    if (isset($result['grid_number']) && $result['grid_number']) :
        $data['grid_number'] = $result['grid_number'];
    else:
        $data['grid_number'] = '';
    endif;

    if (isset($result['rm_name']) && $result['rm_name']) :
        $data['rm_name'] = $result['rm_name'];
    else:
        $data['rm_name'] = '';
    endif;
    if (isset($result['grade']) && $result['grade']) :
        $data['grade'] = $result['grade'];
    else:
        $data['grade'] = '';
    endif;

    if (isset($result['rm_code']) && $result['rm_code']) :
        $data['rm_code'] = $result['rm_code'];
    else:
        $data['rm_code'] = '';
    endif;

	$data['title']='Edit RM Code';
	$data['grids']=$this->notifications_model->getGrids();
	$data['suppliers']=$this->notifications_model->getSuppliers($data['categories_id']);
	$data['raw_materials']=$this->notifications_model->getCategories();
	$data['categories']=$this->notifications_model->getSupplierCategories();
	//$data['states']=$this->notifications_model->getStates();
	$this->template->load('template','rm_code_edit',$data);

	//$this->load->view('footer');
	
	}
	
	public function index(){
			$data['title']=' Notification Master';
			$data['rmcodes']=$this->notifications_model->allnotification();
			$this->template->load('template','notification_master',$data);
		}

	public function report() 
	{
		$data['title'] = 'Suppliers Report';
		$data['suppliers'] = $this->notifications_model->supplier_list();
		//echo var_dump($data['students']);
		$this->template->load('template','supplier_report',$data);
	}

	public function add_new_rmcode() {
		$this->form_validation->set_rules('grid_number', 'Supplier Name', 'required');
		$this->form_validation->set_rules('supplier_id', 'Supplier Name', 'required');
		$this->form_validation->set_rules('rm_name', 'Raw Material', 'required');
		

		
		if ($this->form_validation->run() == FALSE) 
		{
			
			if(isset($this->session->userdata['logged_in'])){
				$this->add();
			//$this->load->view('admin_page');
			}else{
			$this->load->view('login_form');
			}
			//$this->template->load('template','supplier_add');
		} 
		else 
		{
			$login_id=$this->session->userdata['logged_in']['id'];
			$data = array(
			'grid_number' => $this->input->post('grid_number'),
			'supplier_id' => $this->input->post('supplier_id'),
			'rm_name' => $this->input->post('rm_name'),
			'grade' => $this->input->post('grade'),
			'rm_code' => $this->input->post('rm_code'),
			'categories_id' => $this->input->post('categories_id'),
			'created_by' => $login_id
			);
			//print_r($data);exit;
			$result = $this->notifications_model->rm_insert($data);
			if ($result == TRUE) {
			$this->session->set_flashdata('success', 'Data inserted Successfully !');
			redirect('/Notifications/index', 'refresh');
			//$this->fetchSuppliers();
			} else {
			$this->session->set_flashdata('success', 'Insertion Failed ! Data already exists');
			redirect('/Notifications/index', 'refresh');
			}
		}
	}

	public function edit_rmcode($id){
		$this->form_validation->set_rules('grid_number', 'Supplier Name', 'required');
		$this->form_validation->set_rules('supplier_id', 'Supplier Name', 'required');
		$this->form_validation->set_rules('rm_name', 'Raw Material', 'required');
	

		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in'])){
					$this->add($id);
			//$this->load->view('admin_page');
			}else{
			$this->load->view('login_form');
			}
			//$this->template->load('template','supplier_edit');
		} 
		else 
		{
			$login_id=$this->session->userdata['logged_in']['id'];
			$data = array(
			'grid_number' => $this->input->post('grid_number'),
			'supplier_id' => $this->input->post('supplier_id'),
			'rm_name' => $this->input->post('rm_name'),
			'grade' => $this->input->post('grade'),
			'rm_code' => $this->input->post('rm_code'),
			'categories_id' => $this->input->post('categories_id'),
			'edited_by' => $login_id
			);
			$old_id = $this->input->post('rm_id_old'); 
			//print_r($data);exit;
			$result = $this->notifications_model->editRMcode($data,$old_id);
			if ($result == TRUE) {

			$this->session->set_flashdata('success', 'Data Updated Successfully !');
			redirect('/Notifications/index', 'refresh');
			//$this->template->load('template','supplier_view');
			} else {
			$this->session->set_flashdata('failed', 'No changes in RM Code details!');
			redirect('/Notifications/index', 'refresh');
			//$this->template->load('template','supplier_view');
			}
		}
	}

	public function deleteNotification($id= null)
	{
		$ids=$this->input->post('ids');
		if(!empty($ids)) 
		{
			$Datas=explode(',', $ids);
  	 		foreach ($Datas as $key => $id) {
  	 			$this->notifications_model->delete_notification($id);
  	 		}
	 			echo $this->session->set_flashdata('success', 'All selected Notification has been cleared Successfully !');
		}
		else
		{
  	 		$id = $this->uri->segment('3');
  	 		$this->notifications_model->delete_notification($id);
  	 		$this->session->set_flashdata('success', 'This Notification has been cleared !');
  	 		redirect('/User_authentication/dashboard', 'refresh');
	 			//$this->fetchSuppliers(); //render the refreshed list.
	 		}
  	 }
  	 
}

?>