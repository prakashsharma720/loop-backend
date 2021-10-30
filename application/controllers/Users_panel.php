<?php

//session_start(); //we need to start session in order to access it through CI

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//print_r(BASEPATH);exit;

Class Users_panel extends CI_Controller
{

    public function __construct() 
    {
        parent::__construct();
        if(!$this->session->userdata['logged_in']['id']){
            redirect('Users_panel/index');
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
        $this->load->model('Users_model');
        //$this->load->library('excel');
    }

    //users View
	public function index() 
	{
        $data['title'] = 'Users List';
		
		$data['users'] = $this->Users_model->user_list();

		$this->template->load('template','users_list',$data);       
	}

    //Add Record View
    public function add() 
    {
        $data = array();

        $data['title']='Add New User';

        $data['login_id']=$this->session->userdata['logged_in']['id'];
        $data['department_id']=$this->session->userdata['logged_in']['department_id'];
        $role_id=$this->session->userdata['logged_in']['role_id'];

        $this->load->model('Users_model');
        $this->template->load('template','users_add', $data);        
	}

    //Add Record to Database
    public function add_new_record() 
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        // $this->form_validation->set_rules('password', 'Password', 'required|matches[confirm_password]'); 
        // $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
       
       if ($this->form_validation->run() == FALSE) 
       {
           $this->template->load('template','users_add',$data);   
       } 
       else 
       {
           $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),  
            'mobile' => $this->input->post('mobile'),  
            'dob' => date('Y-m-d',strtotime($this->input->post('dob'))),  
            'gender' => $this->input->post('gender'),  
            'password' => md5($this->input->post('password')),
           );
        //    echo "<pre>";
        //    print_r($_POST);
        //    echo "</pre>";exit;
           $result = $this->Users_model->users_insert($data);

           if ($result == TRUE) {
                $this->session->set_flashdata('success', 'User Added Successfully  !');	
                redirect('Users_panel/index', 'refresh');
           } else {
                $this->session->set_flashdata('failed', 'Insertion Failed, User Already exists !');
                redirect('Users_panel/index', 'refresh');
           }
       }
   }

    //Delete Record to Database
    public function deleteRecord($id= null){

        $ids=$this->input->post('ids');

        if(!empty($ids)) {
            $Datas=explode(',', $ids);
            foreach ($Datas as $key => $id) {
                $this->Users_model->deleteRecord($id);

            }
            echo $this->session->set_flashdata('success', 'Users deleted Successfully !');
        }else{
            $id = $this->uri->segment('3');
            $this->Users_model->deleteRecord($id);
            $this->session->set_flashdata('success', 'Users deleted Successfully !');
            redirect('/Users_panel/index', 'refresh');
        }
    }

    //Edit View
    public function edit($id) { 

		$id = $this->uri->segment('3');

		$data['title']='Edit Users';
		//echo $id;exit;
		
		$query = $this->db->get_where("users",array("id"=>$id));
        $data['current'] = $query->row();
       	//print_r($data['current'][0]->vendor_code);exit;
	
        $data['id'] = $id;
		        
        $this->template->load('template','users_edit',$data);
	}


    //Edit Record to Database
    public function editusers($id)
    {
		$this->form_validation->set_rules('name', 'Name', 'required');
        
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
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'mobile' => $this->input->post('mobile'),
                'dob' => date('Y-m-d',strtotime($this->input->post('dob'))),  
                'gender' => $this->input->post('gender'),
				// 'password' => md5($this->input->post('password')),
			);
			// $old_id = $this->input->post('id'); 
			//print_r($data);exit;

			$result = $this->Users_model->editRecord($data,$id);
			//echo $result;exit;

			if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Users Updated Successfully !');
                redirect('Users_panel/index','refresh');
			}else {
                $this->session->set_flashdata('failed', 'Users Updating Failed !');
                redirect('Users_panel/index','refresh');
			}
		}
	}

    public function MyPasswordChangeView() {
		$data['title'] = ' Change Password';
		$data['users'] = $this->Users_model->getUsers();
		$this->template->load('template', 'change_user_password',$data);	
	}

    public function UserPasswordChange(){
        $password=md5($this->input->post('password'));
		$cpassword=md5($this->input->post('confirm_password'));
        $users_id=$this->input->post('users_id');

		// print_r($password);echo"<br>";
		// print_r($cpassword);
		// exit;

		if($password==$cpassword){
			$data = array('password' => $password);
			$users_id=$this->input->post('users_id');
			$result=$this->Users_model->myPasswordChange($users_id,$data);
			//print_r($result);exit;
			if($result==true){
				$this->session->set_flashdata('success', 'Password Changed Successfully');
				redirect('Users_panel/MyPasswordChangeView','refresh');	
			}
			else{
				$this->session->set_flashdata('failed', 'Operation Failed');
				redirect('Users_panel/MyPasswordChangeView','refresh');
			}	
        }
    }  
        
		
}

?>