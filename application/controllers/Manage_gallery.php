<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 
class Manage_gallery extends CI_Controller { 
     
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

        // Load image model 
        $this->load->model('Gallery_model'); 
         
        $this->load->helper('form'); 
        $this->load->library('form_validation'); 
         
        // Default controller name 
        $this->controller = 'manage_gallery'; 
         
        // File upload path 
        $this->uploadPath = 'uploads/images/'; 
    } 
     
    public function index(){ 
        $data = array(); 
         
        // Get messages from the session 
        if($this->session->userdata('success_msg')){ 
            $data['success_msg'] = $this->session->userdata('success_msg'); 
            $this->session->unset_userdata('success_msg'); 
        } 
        if($this->session->userdata('error_msg')){ 
            $data['error_msg'] = $this->session->userdata('error_msg'); 
            $this->session->unset_userdata('error_msg'); 
        } 
 
        $data['gallery'] = $this->Gallery_model->getRows(); 
        $data['title'] = 'Images Archive'; 
         
        // Load the list page view 
        $this->load->view('templates/header', $data);
        $this->template->load('template','Manage_gallery/index',$data); 
        $this->load->view('templates/footer'); 
    } 
     
    public function view($id){ 
        $data = array(); 
         
        // Check whether id is not empty 
        if(!empty($id)){ 
            $con = array('id' => $id); 
            $data['image'] = $this->Gallery_model->getRows($con); 
            $data['title'] = $data['image']['title']; 
             
            // Load the details page view 
            $this->load->view('templates/header', $data);
            $this->template->load('template','Manage_gallery/view',$data); 
            $this->load->view('templates/footer'); 
        }else{ 
            redirect('Manage_gallery/index','refresh'); 
        } 
    } 
     
    public function add(){ 
        $data = $imgData = array(); 
        $error = ''; 
         
        // If add request is submitted 
        if($this->input->post('imgSubmit')){ 
            // Form field validation rules 
            $this->form_validation->set_rules('title', 'image title', 'required'); 
            $this->form_validation->set_rules('image', 'image file', 'callback_file_check');
            $user_id=$this->input->post('user_id');

            // Prepare gallery data 
            $imgData = array( 
                'title' => $this->input->post('title'),
                'user_id' => $this->input->post('user_id'),                  
            ); 
             
            // Validate submitted form data 
            if($this->form_validation->run() == true){ 
                // Upload image file to the server 
                if(!empty($_FILES['image']['name'])){ 
                    $imageName = $_FILES['image']['name']; 
                     
                    // File upload configuration 
                    $config['upload_path'] = $this->uploadPath; 
                    $config['allowed_types'] = 'jpg|jpeg|png|gif'; 
                     
                    // Load and initialize upload library 
                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 
                     
                    // Upload file to server 
                    if($this->upload->do_upload('image')){ 
                        // Uploaded file data 
                        $fileData = $this->upload->data(); 
                        $imgData['file_name'] = $fileData['file_name'];
                        $user_id=$this->input->post('user_id');

                    }else{ 
                        $error = $this->upload->display_errors();  
                    } 
                } 
                 
                if(empty($error)){ 
                    // Insert image data 
                    $insert = $this->Gallery_model->insert($imgData); 
                     
                    if($insert){ 
                        $this->session->set_userdata('success_msg', 'Image has been uploaded successfully.'); 
                        redirect('Manage_gallery/index','refresh');
                    }else{ 
                        $error = 'Some problems occurred, please try again.'; 
                    } 
                } 
                 
                $data['error_msg'] = $error; 
            } 
        } 
         
        $data['image'] = $imgData; 
        $data['title'] = 'Upload Image'; 
        $data['action'] = 'Upload'; 
        $data['users']=$this->Gallery_model->getUsers();
         
        // Load the add page view 
        $this->load->view('templates/header', $data); 
        $this->template->load('template','Manage_gallery/add-edit',$data); 
        $this->load->view('templates/footer'); 
    } 
     
    public function edit($id){ 
        $data = $imgData = array(); 
         
        // Get image data 
        $con = array('id' => $id); 
        $imgData = $this->Gallery_model->getRows($con); 
        $prevImage = $imgData['file_name'];
        $user_id=$this->input->post('user_id');
         
        // If update request is submitted 
        if($this->input->post('imgSubmit')){ 
            // Form field validation rules 
            $this->form_validation->set_rules('title', 'gallery title', 'required'); 
             
            // Prepare gallery data 
            $imgData = array( 
                'title' => $this->input->post('title'),
                'user_id' => $this->input->post('user_id'),                   
            ); 
             
            // Validate submitted form data 
            if($this->form_validation->run() == true){ 
                // Upload image file to the server 
                if(!empty($_FILES['image']['name'])){ 
                    $imageName = $_FILES['image']['name']; 
                     
                    // File upload configuration 
                    $config['upload_path'] = $this->uploadPath; 
                    $config['allowed_types'] = 'jpg|jpeg|png|gif'; 
                     
                    // Load and initialize upload library 
                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 
                     
                    // Upload file to server 
                    if($this->upload->do_upload('image')){ 
                        // Uploaded file data 
                        $fileData = $this->upload->data(); 
                        $imgData['file_name'] = $fileData['file_name']; 
                         
                        // Remove old file from the server  
                        if(!empty($prevImage)){ 
                            @unlink($this->uploadPath.$prevImage);  
                        } 
                    }else{ 
                        $error = $this->upload->display_errors();  
                    } 
                } 
                 
                if(empty($error)){ 
                    // Update image data 
                    $update = $this->Gallery_model->update($imgData, $id); 
                     
                    if($update){ 
                        $this->session->set_userdata('success_msg', 'Image has been updated successfully.'); 
                        redirect('Manage_gallery/index','refresh'); 
                    }else{ 
                        $error = 'Some problems occurred, please try again.'; 
                    } 
                } 
                 
                $data['error_msg'] = $error; 
            } 
        } 
 
         
        $data['image'] = $imgData; 
        $data['title'] = 'Update Image'; 
        $data['action'] = 'Edit'; 
        $data['users']=$this->Gallery_model->getUsers();

        // Load the edit page view 
        $this->load->view('templates/header', $data); 
        $this->template->load('template','Manage_gallery/add-edit',$data); 
        // $this->load->view($this->controller.'/add-edit', $data); 
        $this->load->view('templates/footer'); 
    } 
     
    public function block($id){ 
        // Check whether id is not empty 
        if($id){ 
            // Update image status 
            $data = array('status' => 0); 
            $update = $this->Gallery_model->update($data, $id); 
             
            if($update){ 
                $this->session->set_userdata('success_msg', 'Image has been blocked successfully.'); 
            }else{ 
                $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.'); 
            } 
        } 
 
        redirect('Manage_gallery/index','refresh'); 
    } 
     
    public function unblock($id){ 
        // Check whether is not empty 
        if($id){ 
            // Update image status 
            $data = array('status' => 1); 
            $update = $this->Gallery_model->update($data, $id); 
             
            if($update){ 
                $this->session->set_userdata('success_msg', 'Image has been activated successfully.'); 
            }else{ 
                $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.'); 
            } 
        } 
 
        redirect('Manage_gallery/index','refresh'); 
    } 
     
    public function delete($id){ 
        // Check whether id is not empty 
        if($id){ 
            $con = array('id' => $id); 
            $imgData = $this->Gallery_model->getRows($con); 
             
            // Delete gallery data 
            $delete = $this->Gallery_model->delete($id); 
             
            if($delete){ 
                // Remove file from the server  
                if(!empty($imgData['file_name'])){ 
                    @unlink($this->uploadPath.$imgData['file_name']);  
                }  
                 
                $this->session->set_userdata('success_msg', 'Image has been removed successfully.'); 
            }else{ 
                $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.'); 
            } 
        } 
 
        redirect('Manage_gallery/index','refresh'); 
    } 
     
    public function file_check($str){ 
        if(empty($_FILES['image']['name'])){ 
            $this->form_validation->set_message('file_check', 'Select an image file to upload.'); 
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    } 
}