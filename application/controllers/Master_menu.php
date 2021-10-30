<?php

//session_start(); //we need to start session in order to access it through CI

Class Master_menu extends CI_Controller {

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
$this->load->model('menu_master');
}
	
	public function index($id = NULL) 
	{
		$data = array();
			$result = $this->menu_master->getById($id);

			if (isset($result['id']) && $result['id']) :
	            $data['id'] = $result['id'];
	        else:
	            $data['id'] = '';
	        endif;

			if (isset($result['menu_name']) && $result['menu_name']) :
	            $data['menu_name'] = $result['menu_name'];
	       else:
	            $data['menu_name'] = '';
	        endif;

			$data['title'] = 'Menu Master';
			$data['menus'] = $this->menu_master->menusList();
			//echo var_dump($data['students']);
			//print_r($data['menu_name']);exit;
			$this->template->load('template','menu_master',$data);
	}
	public function add_new_menu() {
		
		$this->form_validation->set_rules('menu_name', 'menu Name', 'required');
		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in'])){
			$this->index();
			//$this->template->load('template','menu_master');
			//$this->load->view('admin_page');
			}else{
			$this->load->view('login_form');
			}
			//$this->template->load('template','supplier_add');
		}
		else 
		{
			
			$data = array(
			'menu_name' => $this->input->post('menu_name')
			);
			$result = $this->menu_master->menu_insert($data);
			if ($result == TRUE) {
			//$data['message_display'] = 'menu Added Successfully !';
			$this->session->set_flashdata('success', 'Menu Added Successfully !');
			redirect('/menu/index', 'refresh');
			//$this->fetchSuppliers();
			} else {
			$this->session->set_flashdata('failed', 'Already exists, menu Could not added !');
			redirect('/menu/index', 'refresh');
			}
		} 
	}

	public function editmenu($id) {
		$this->form_validation->set_rules('menu_name', 'menu Name', 'required');
		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in'])){
				$this->index();
			//$this->template->load('template','menu_master');
			//$this->load->view('admin_page');
			}else{
			$this->load->view('login_form');
			}
			//$this->template->load('template','supplier_add');
		}
		else 
		{
			$data = array(
			//'id' => $id,
			'menu_name' => $this->input->post('menu_name'),
			'flag' => $this->input->post('flag')
			);
			$result = $this->menu_master->menu_update($data,$id);
			//echo $result;exit;
			if ($result == TRUE) {
			$this->session->set_flashdata('success', 'menu Updated Successfully !');
			redirect('/menu/index', 'refresh');
			//$this->fetchSuppliers();
			}
			else {
			$this->session->set_flashdata('failed', 'No Changes in menu!');
			redirect('/menu/index', 'refresh');
			}
		} 
	}

}

?>