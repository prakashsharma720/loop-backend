<?php

//session_start(); //we need to start session in order to access it through CI

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//print_r(BASEPATH);exit;

Class Addon_services extends CI_Controller
{

    public function __construct() 
    {
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

        $this->load->library('encryption');

        // Load session library
        $this->load->library('session');

        $this->load->library('template');

        // Load database
        $this->load->model('Addon_model');
        //$this->load->library('excel');
    }

    //Subscription View
	public function index() 
	{
        $data['title'] = 'Addon Services List';
		
		$data['subscriptions'] = $this->Addon_model->addon_services_list();

		$this->template->load('template','addon_list',$data);       
	}

    //Add Record View
    public function add() 
    {
        $data = array();

        $data['title']='Create New Addon Service';

        $data['login_id']=$this->session->userdata['logged_in']['id'];
        $data['department_id']=$this->session->userdata['logged_in']['department_id'];
        $role_id=$this->session->userdata['logged_in']['role_id'];

        $this->load->model('Addon_model');
        $this->template->load('template','addon_create', $data);        
	}

    //Add Record to Database
    public function add_new_record() 
    {
        $this->form_validation->set_rules('title', 'title', 'required');
       
       if ($this->form_validation->run() == FALSE) 
       {
           $this->template->load('template','addon_create',$data);   
       } 
       else 
       {
           $data = array(
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),  
            'price' => $this->input->post('price'),  
            // 'duration' => $this->input->post('duration'),  
           );
        //    echo "<pre>";
        //    print_r($_POST);
        //    echo "</pre>";exit;
           $result = $this->Addon_model->addon_services_insert($data);

           if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Service Added Successfully  !');	
                redirect('Addon_services/index', 'refresh');
           } else {
                $this->session->set_flashdata('failed', 'Insertion Failed, Service Already exists !');
                redirect('Addon_services/index', 'refresh');
           }
       }
   }

    //Delete Record to Database
    public function deleteRecord($id= null){

        $ids=$this->input->post('ids');

        if(!empty($ids)) {
            $Datas=explode(',', $ids);
            foreach ($Datas as $key => $id) {
                $this->Addon_model->deleteRecord($id);

            }
            echo $this->session->set_flashdata('success', 'Services deleted Successfully !');
        }else{
            $id = $this->uri->segment('3');
            $this->Addon_model->deleteRecord($id);
            $this->session->set_flashdata('success', 'Service deleted Successfully !');
            redirect('/Addon_services/index', 'refresh');
        }
    }

    //Edit View
    public function edit($id) { 

		$id = $this->uri->segment('3');

		$data['title']='Edit Addon Service';
	
        $data['current'] = $this->Addon_model->getById($id);
       	//print_r($data['current']);exit;
	
        $data['id'] = $id;
		        
        $this->template->load('template','addon_edit',$data);
	}


    //Edit Record to Database
    public function editRecord($id)
    {
		$this->form_validation->set_rules('title', 'Title', 'required');
        
		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in'])){
			$this->edit($id);
			//$this->load->view('admin_page');
			}else{
			$this->load->view('login_form');
			}
		} 
		else 
		{
			$login_id=$this->session->userdata['logged_in']['id'];

			$data = array(
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'price' => $this->input->post('price'),
				// 'duration' => $this->input->post('duration'),
			);
			// $old_id = $this->input->post('id'); 
			//print_r($data);exit;

			$result = $this->Addon_model->editRecord($data,$id);
			//echo $result;exit;

			if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Subscription Updated Successfully !');
                redirect('Addon_services/index','refresh');
			}else {
                $this->session->set_flashdata('failed', 'Subscription Updating Failed !');
                redirect('Addon_services/index','refresh');
			}
		}
	}
    
}

?>