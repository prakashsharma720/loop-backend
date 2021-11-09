<?php

//session_start(); //we need to start session in order to access it through CI

Class Notifications extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata['logged_in']['id']){
			redirect('User_authentication/index');
		}
		// Load form helper library
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('security');

		// Load session library
		$this->load->library('session');
		$this->load->library('template');
		$this->load->library('form_validation');
		//$this->load->library('encryption');
		//$this->load->library('excel');

		// Load database
		$this->load->model('notifications_model');
		$this->load->model('Notifications_Model');
	}
	// Get Order for near expiry date
	public function near_expiry_orders_notification() {
		$order_details = $this->Notifications_Model->near_expiry_orders();
		if(!empty($order_details)) {
			foreach($order_details as $orders) {
				$user_id  = $orders['user_id'];
				$order_id = $orders['order_id'];
				// Data for Notification
				$notifData['employee_id'] = $user_id;
				$notifData['type']        = 'Order Expiry';
				$notifData['subject']     = 'Order Expiry';
				$notifData['message']     =  'Your order will expire in 7 day, Order ID : '.$order_id;
				$notifData['datetime']    = date('Y-m-d H:i:s');
				$notifData['status']      = '0';
				$this->db->insert('notifications', $notifData);
			}
		}
		$this->session->set_flashdata('success', 'Notification Send Successfully!');
		redirect('User_authentication/dashboard','refresh');
	}	
	// Send mail to customer for order expiry
	function near_expiry_orders_mail() {		
		$order_details = $this->Notifications_Model->near_expiry_orders();
		if(!empty($order_details)) {
			foreach($order_details as $ordersList) {
				$order_row_id = $ordersList['id'];
				$order_id     = $ordersList['order_id'];
				$this->db->select('orders.*, subscription.title as subscription_plan_title, users.name as user_name, users.id as user_id, users.email as user_email, users.mobile as user_mobile, subscription.price as subscription_price');
				$this->db->from('orders'); 
				$this->db->join('subscription', 'orders.subscription_plan_id = subscription.id','left'); 
				$this->db->join('users', 'orders.user_id = users.id','left'); 
				$this->db->where(['orders.flag'=>'0', 'orders.id'=>$order_row_id]);
				$query =  $this->db->get()->result_array();
				
				$orderId                    = $query['0']['order_id'];
				$customerName               = $query['0']['user_name'];
				$customerEmail              = $query['0']['user_email'];
				$subscription_plan_title    = $query['0']['subscription_plan_title'];
				// plan duration
				if($query['0']['plan_status']==1)  {$query['0']['plan_status']="Monthly";}
				if($query['0']['plan_status']==3)  {$query['0']['plan_status']="Quarterly";}
				if($query['0']['plan_status']==6)  {$query['0']['plan_status']="Half Yearly";}
				if($query['0']['plan_status']==12) {$query['0']['plan_status']="Yearly";}
		
				$plan_status                = $query['0']['plan_status'];
				$grand_total                = $query['0']['grand_total'];
		
				$config = Array(
				// For Server
					// 'protocol' => 'mail',
					// 'smtp_host' => 'mail.muskowl.com',
					// 'smtp_port' => 587,
					// 'smtp_user' => 'hemendra@muskowl.com', // change it to yours
					// 'smtp_pass' => '#hemendra@2021#', // change it to yours
				// For Local
					'protocol'  => 'smtp',
					'smtp_host' => 'ssl://smtp.googlemail.com',
					'smtp_port' => 465,
					'smtp_user' => 'hemendra.muskowl@gmail.com', // change it to yours
					'smtp_pass' => 'hss4u@mo', // change it to yours
		
					'mailtype' => 'html',
					'charset' => 'iso-8859-1',	
					'wordwrap' => TRUE
				);
				
				$message = 
				'
				<html>
					<head>
						<title>Order Expiry !</title>
					</head>
					<body>
					<p> <b> Your order will expire in 7 day</b> </p>
					<p>You have subscribe this :</p>
					<table>
						<tr>
							<td>Subcription ID</td>
							<td>:</td>
							<td><b> '.$order_id.'</b></td>
						</tr>
						<tr>
							<td>Subscription Title</td>
							<td>:</td>
							<td><b> '.$subscription_plan_title.' </b></td>
						</tr>
						<tr>
							<td>Subscription Status</td>
							<td>:</td>
							<td><b> '.$plan_status.' </b></td>
						</tr>
						<tr>
							<td>Grand Total</td>
							<td>:</td>
							<td><b> $'.$grand_total.' </b></td>
						</tr>
					</table>
					</body>
				</html>
				';
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				$this->email->from('hemendra.muskowl@gmail.com'); // change it to yours
				$this->email->to($customerEmail);// change it to yours
				$this->email->subject('Order Expiry');
				$this->email->message($message);
				$this->email->send();
			}
		}
		$this->session->set_flashdata('success', 'Mail Send Successfully!');
		redirect('User_authentication/dashboard','refresh');
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
} // Main Class Closed
?>
