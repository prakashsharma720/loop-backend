<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_api extends CI_Controller {

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

		$this->load->model('api/Gallery_api_model');
		$this->load->library('form_validation');
	}

	function index()
	{
		$data = $this->Gallery_api_model->fetch_all();
		
        $result = array();
        foreach ($data as $object) :
            $result[] = array(
                'id' => $object['id'],
                'user_id' => $object['user_id'],
                'title' => $object['title'],
                'file_name' => base_url('/uploads/images/'.$object['file_name']),
                'created' => $object['created'],
                'modified' => $object['modified'],
                'status' => $object['status']
            );
        endforeach;
		$array = array(
			'success' => true,
			'Message' => 'Subscription Details !',
			'result' => $result
		);
       
		echo json_encode($array);
	}

	function insert()
	{	
		$this->uploadPath = 'uploads/images/';

		$data = $imgData = array(); 
        $error = '';
		
		// Form field validation rules 
		$this->form_validation->set_rules('title', 'Title', 'required'); 
		if (empty($_FILES['image']['name']))
		{
			$this->form_validation->set_rules('image', 'Document', 'required');
		}

		$user_id=$this->input->post('user_id');
		
		$result = $this->Gallery_api_model->verify_user_id($user_id);

		if($result == 0)
		{
			// Set the response and exit
			$array = array(
				'success' => false,
				'message' => 'User Not Match, Please enter register user id !',
				'result' => $result
			);
			echo json_encode($array);

		}else{
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
					$config['upload_path'] = 'uploads/images/'; 
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
					$insert = $this->Gallery_api_model->insert_api($imgData); 
					
					if($insert){ 
						$array = array(
							'success' => true,
							'message' => 'Images Inserted Successfully !',
							'result' => $imgData,
						);

						echo json_encode($array);
					}else{ 
						$array = array(
							'success' => true,
							'message' => 'Images Not Inserted !',
							'result' => $insert,
						);

						echo json_encode($array);
					} 
				} 			
			}
		}
	}
	
    
	function fetch_single()
	{
		$user_id = $this->input->post('user_id');

		$result = $this->Gallery_api_model->verify_user_id($user_id);

		if($result > 0)
		{
			$data['images'] = $this->Gallery_api_model->fetch_single($this->input->post('user_id'));
			$array = array(
				'success' => true,
				'message' => 'Images Found Successfully !',
				'result' => $data,
			);

			echo json_encode($array);
		}else{
			$array = array(
				'success' => false,
				'message' => 'Images Not Found for Given User ID !',
			);
			echo json_encode($array);
		}	
	}

	function update()
	{
		$id=$this->input->post('id');
		$result = $this->Gallery_api_model->verify_id($id);

		if($result == 0)
		{
			// Set the response and exit
			$array = array(
				'success' => false,
				'message' => 'ID Not Match, Please enter Valid id !',
				'result' => $result
			);
			echo json_encode($array);

		}else{

			$this->uploadPath = 'uploads/images/';

			$data = $imgData = array(); 
			
			// Get image data 
			$con = array('id' => $id); 
			$imgData = $this->Gallery_api_model->getRows($con); 
			$prevImage = $imgData['file_name'];
			$user_id=$this->input->post('user_id');
        
            // Form field validation rules 
            $this->form_validation->set_rules('title', 'gallery title', 'required'); 
             
            // Prepare gallery data 
            $imgData = array( 
                'title' => $this->input->post('title'),
                'user_id' => $this->input->post('user_id'),                   
            ); 
             
            // Validate submitted form data 
            if($this->form_validation->run() == true){
				//print_r('hello');exit;
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
					// Insert image data 
					$insert = $this->Gallery_api_model->update_api($imgData, $id); 
					
					if($insert){ 
						$array = array(
							'success' => true,
							'message' => 'Images Updated Successfully !',
							'result' => $imgData,
						);

						echo json_encode($array);
					}else{ 
						$array = array(
							'success' => true,
							'message' => 'Images Not Updated !',
							'result' => $insert,
						);

						echo json_encode($array);
					} 
				}
            }
		}
	}

	function delete()
	{
		if($this->input->post('user_id'))
		{
			if($this->Gallery_api_model->delete_single_user($this->input->post('user_id')))
			{
				$array = array(

					'success' => true,
                    'message' => 'Data Deleted Successfully !',
                    'result'  => $data
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