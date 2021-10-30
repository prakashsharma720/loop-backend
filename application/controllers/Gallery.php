<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 
class Gallery extends CI_Controller { 
     
    function __construct() { 
        parent::__construct(); 

        if(!$this->session->userdata['logged_in']['id']){
            redirect('User_authentication/index');
        }
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
        $this->load->helper('directory');

        
        // Load image model 
        $this->load->model('Gallery_model'); 
         
        // Default controller name 
        $this->controller = 'gallery'; 
    } 
     
    public function index(){ 
        $data = array(); 
         
        $con = array( 
            'where'=> array( 
                'status' => 1 
            ) 
        ); 
        $data['gallery'] = $this->Gallery_model->getRows($con); 
        $data['title'] = 'Images Gallery'; 
         
        // Load the list page view 
        $this->load->view('templates/header', $data);
        $this->template->load('template','gallery/index',$data); 
        $this->load->view('templates/footer'); 
    } 
}