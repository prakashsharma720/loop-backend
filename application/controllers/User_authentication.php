<?php
//session_start(); //we need to start session in order to access it through CI

Class User_authentication extends CI_Controller {

public function __construct() {
	parent::__construct();
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
	$this->load->model('login_database');
	$this->load->model('notifications_model');
}

// Show dashboard page
public function dashboard() {
	//$this->template->load('template', 'login_form');
	$this->login_id=$this->session->userdata['logged_in']['id'];
	$this->department_id=$this->session->userdata['logged_in']['department_id'];
	$this->role_id=$this->session->userdata['logged_in']['role_id'];
	//print_r($this->login_id);exit;
	$data['title'] = 'Dashboard';
	$data['total_notifications'] = $this->notifications_model->totalCount();
	if($this->role_id=='4'){
		$data['allnotifications'] = $this->notifications_model->allnotification();
	}else{
		$data['allnotifications'] = $this->notifications_model->allnotification_emp($this->login_id);
	}
	$this->template->load('template', 'dashboard',$data);
	//$this->load->view('dashboard',$data);
}

public function index() {
	//$this->template->load('template', 'login_form');
	$this->load->view('login_form');
}

public function admin_dashboard() {
	//$this->template->load('template', 'login_form');
	$data['title'] = 'Admin Dashboard';
	
	$this->template->load('template', 'super_dashboard',$data);
	//$this->load->view('dashboard',$data);
}

// Show registration page
public function user_registration_show() {
	$this->load->view('registration_form');
	//$this->template->load('template', 'registration_form');
}

// Validate and registration data in database
public function new_user_registration() {
	// Check validation for user input in SignUp form
	$this->form_validation->set_rules('username', 'Username', 'required');
	$this->form_validation->set_rules('password', 'Password', 'required');
	$new_pass=md5($this->input->post('password'));
	//echo $new_pass;exit;
	if ($this->form_validation->run() == FALSE) {
		$this->load->view('registration_form');
	} else {
		$data = array(
		'username' => $this->input->post('username'),
		'email' => $this->input->post('email_value'),
		'role_id' => 3,
		'flag' => 0,
		'password' => $new_pass
	);
		//print_r($data);exit;
		$result = $this->login_database->registration_insert($data);
		if ($result == TRUE) {
			$data['success'] = 'Registration Successfully !';
			//print_r($data);exit;
			$this->load->view('login_form', $data);
		} else {
			$data['failed'] = 'Username already exist!';
			$this->load->view('registration_form', $data);
		}
	}
}

// Check for user login process
public function user_login_process() {
	$this->form_validation->set_rules('username', 'Username', 'required');
	$this->form_validation->set_rules('password', 'Password', 'required');
	$new_password=md5($this->input->post('password'));
	/*echo $this->input->post('username');
	echo $new_password;exit;*/
	if ($this->form_validation->run() == FALSE) {
	if(isset($this->session->userdata['logged_in'])){
	$this->index();
	//$this->load->view('admin_page');
	} else {
	$this->load->view('login_form');
	}
	} else {
	$data = array(
	'username' => $this->input->post('username'),
	'password' => $new_password
	//$this->input->post('password')
	);
	$result = $this->login_database->login($data);
	if ($result == 'Inactive') {
		$this->session->set_flashdata('failed', 'User is In Active !');
		redirect('User_authentication/index','refresh');
	}
	else if ($result == 'active') {
		$username = $this->input->post('username');
		$result = $this->login_database->read_user_information($username);
		//print_r($result);exit;
		if ($result != false) {
			$session_data = array(
			'id' => $result[0]->id,
			'username' => $result[0]->username,
			'email' => $result[0]->email,
			'name' => $result[0]->name,
			'employee_code' => $result[0]->employee_code,
			'mobile_no' => $result[0]->mobile_no,
			'role_id' => $result[0]->role_id,
			'role' => $result[0]->role,
			'department_id' => $result[0]->department_id,
			'password' => $result[0]->password,
			'photo' => $result[0]->photo,
			);
			// Add user data in session
			$this->session->set_userdata('logged_in', $session_data);
			$role_id=$this->session->userdata['logged_in']['role_id'];
			$this->session->set_flashdata('success', 'Welcome to Loop Newsletter !');
			if($role_id=='1'){
				redirect('/User_authentication/dashboard', 'refresh');
			}else{
				redirect('/User_authentication/dashboard', 'refresh');
			}
		}
	} else {	
		$this->session->set_flashdata('failed', 'Invalid Username or Password !');
		//$this->load->view('login_form', $data);
		redirect('User_authentication/index','refresh');
		}
	}
}
	

// Logout from admin page
public function logout() {
	// Removing session data
	$sess_array = array(
	'username' => '',
	'password' => '',
	'role_id' => '',
	'employee_code' => '',
	);
	$this->session->unset_userdata('logged_in', $sess_array);
	//$data['message_display'] = 'User Successfully Logout';
	$this->session->set_flashdata('success', 'User Successfully Logout !');
	//$this->load->view('login_form', $data);
	$this->session->sess_destroy();
	redirect('User_authentication/index','refresh');
}

public function ForgotPassword() {
	//$this->template->load('template', 'login_form');
	$this->load->view('forgot_password');
}

public function EmailVerify() {
	$this->form_validation->set_rules('email', 'Email', 'required');
	$email=$this->input->post('email');
	$result=$this->login_database->verify_email($email);
	//print_r($result[0]->email);exit;
	if(!empty($result)){
		$emp_email=$result[0]->email;
		$emp_mobile=$result[0]->mobile_no;
		$id=$result[0]->id;
		//$data['success_mesg']='Email is verified.';
		 $config = Array(
                  'protocol' => 'smtp',
                  'smtp_host' => 'ssl://smtp.googlemail.com',
                  'smtp_port' => 465,
                  'smtp_user' => 'hemendra387@gmail.com', // change it to yours
                  'smtp_pass' => '8003836264', // change it to yours
                  'mailtype' => 'html',
                  'charset' => 'iso-8859-1',
                  'wordwrap' => TRUE
                );
		$this->load->helper('string');
		$code= random_string('numeric', 6); //$code='123456';
        $message = 'hello <b>'.$result[0]->name.'</b>,<br><br>
					Your one time password is : '.$code.'<br><br><br>
					With Regards <br>
					<b style="margin:bottom:5px;"> Loop Newsletter<b> <br>
					Udaipur,Rajasthan.';
        $this->load->library('email', $config);
	    $this->email->set_newline("\r\n");
	    $this->email->from('hemendra387@gmail.com'); // change it to yours
	    $this->email->to($emp_email);// change it to yours
	    //$this->email->to('prakash1.muskowl@gmail.com');// change it to yours
	    $this->email->subject('Forgot Password || Loop Newsletter');
	    $this->email->message($message);
	    if($this->email->send()) {
	    	$data = array(
			'forgot_code' => $code
			);
	    	$this->login_database->updateOtp($data,$id);
	    	$data['email']=$emp_email;
	    	$data['id']=$id;
	    	//$this->session->set_flashdata('success', 'OTP is sent to your registered mail id');
	    	$data['success_mesg']=' OTP is sent to your registered mail id .';
	     	$this->load->view('otp_verify',$data);
	    } else {
	    	$data['error_message']=$this->email->print_debugger();
	     	//show_error($this->email->print_debugger());
	    }
	} else {
		$this->session->set_flashdata('failed', 'Email is not registered with us,please try with another registered email');
		redirect('User_authentication/ForgotPassword','refresh');
	}
}

public function otpVerify() {
	$this->form_validation->set_rules('otp', 'OTP', 'required');
	$email=$this->input->post('email');
	$otp=$this->input->post('otp');
	$result=$this->login_database->verify_email($email);
	//print_r($result);exit;
	if($result[0]->forgot_code==$otp){
		$data['email']=$result[0]->email;
		$data['success_mesg']='OTP is verified Successfully';
		$this->load->view('change_password',$data);
	}else{
		$data['email']=$result[0]->email;
		$data['error_message']='OTP does not match ';
		$this->load->view('otp_verify',$data);
	}
}

public function ChangePassword() {
	$password=$this->input->post('password');
	$cpassword=$this->input->post('confirm_password');
	$email=$this->input->post('email');
	$data = array('password' => md5($password));
		$result=$this->login_database->updatePassword($email,$data);
		if($result==true){
			$this->session->set_flashdata('success', 'Password Changed Successfully');
			redirect('User_authentication/index','refresh');
	} else {
			$this->session->set_flashdata('failed', 'Operation Failed');
			redirect('User_authentication/ChangePassword','refresh');
	}
}

public function MyPasswordChangeView() {
	$data['title'] = ' Change Password';
	$data['employees'] = $this->login_database->getEmployees();
	$this->template->load('template', 'mypasswordchange',$data);	
}


public function UserPasswordChange(){
	$password=md5($this->input->post('password'));
	$cpassword=md5($this->input->post('confirm_password'));
	if($password==$cpassword){
		$data = array('password' => $password);
		$emp_id=$this->input->post('emp_id');
		$result=$this->login_database->myPasswordChange($emp_id,$data);
		//print_r($result);exit;
		if($result==true){
			$this->session->set_flashdata('success', 'Password Changed Successfully');
			redirect('User_authentication/MyPasswordChangeView','refresh');
		}
		else{
			$this->session->set_flashdata('failed', 'Operation Failed');
			redirect('User_authentication/MyPasswordChangeView','refresh');
		}	
	}
}

public function sendMail() {
	//$this->template->load('template', 'login_form');
	$data['title'] = 'Welcome to Send Email Page ';
	$data['total_candidate'] = $this->login_database->TotalCandidates();
	$this->template->load('template', 'mailer',$data);
	//$this->load->view('dashboard',$data);
}
	
public function SendEmailtoAllUsers() {
	$result=$this->login_database->FetchallEmails();
	//print_r($result);exit;
	if(!empty($result)){
		foreach ($result as $key => $data) {
		 	$sent_to=$data['email'];
		 	//print_r($sent_to);exit;
			//$data['success_mesg']='Email is verified.';
			$config = Array(
			  'protocol' => 'smtp',
			  'smtp_host' => 'ssl://smtp.googlemail.com',
			  'smtp_port' => 465,
			  //'smtp_user' => 'prakash1.muskowl@gmail.com', // change it to yours
			  //'smtp_user' => 'isnu.admissions@nirmauni.ac.in', // change it to yours
			  'smtp_user' => 'ipnu.admission@nirmauni.ac.in', // change it to yours
			  //'smtp_pass' => 'ymdc@6789', // change it to yours
			  'smtp_pass' => 'Digital@NirmaPharma', // change it to yours
			  'mailtype' => 'html',
			  'charset' => 'iso-8859-1',
			  'wordwrap' => TRUE
			);
			$this->load->helper('string');

			//$code='123456';
			$message = " Hello! 
					<br><br>
					In reference to our previous email, we would like to inform you that 'LAST DATE FOR REGISTRATION IS 27TH JUNE 2019 '. Hurry up and register yourself for becoming a part of one of the best University. Hurry up! limited seats. Follow the registration link below and get yourself registered..<br><br>

					For registering with us, follow the link: http://admissions.nirmauni.ac.in/CampusLynxNU/onindex.html <br>
					NOTE: Ignore if you have already applied.<br><br>

					Feel free to contact us on the numbers and e-mail address mentioned below.<br>
					1.     Phone: (079)30642715,(02717)241900-04 <br>
					2.     Email: admission.ip@nirmauni.ac.in
					<br><br>
					Regards, <br>
					Institute of Pharmacy, Nirma University <br>
					Website: http://www.nirmauni.ac.in/IPNU <br>
					Address: Sarkhej-Gandhinagar Highway, Post Chandlodia, <br>
					Via Gota, Ahmedabad – 382481. Gujarat, India <br><br>
					
					";
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			//$this->email->from('isnu.admissions@nirmauni.ac.in'); // change it to yours
			$this->email->from('ipnu.admission@nirmauni.ac.in'); // change it to yours
			//$this->email->to($emp_email);// change it to yours
			$this->email->to($sent_to);// change it to yours
			//$this->email->to('prakashsharma720@gmail.com');// change it to yours
			$this->email->subject('LAST DATE FOR REGISTRATION IS 27TH JUNE 2019! Institute of Pharmacy, Nirma University');
			$this->email->message($message);
			$this->email->send();
		}
	} else{
			$this->session->set_flashdata('failed', 'Email is not registered with us,please try with another registered email');
			redirect('User_authentication/sendMail','refresh');
		}
}

} // Main Function Closed
?>