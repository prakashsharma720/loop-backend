<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

Class Authentication_api extends Restserver\Libraries\REST_Controller  {
// class Authentication_api extends REST_Controller {

    public function __construct() { 
        parent::__construct();

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        // Load the user model
        $this->load->model('api/user');
    }

    function _remap($method, $param1 = null, $param2 = null) {
        if($method == "login_post"){
            return $this->$method();
        }
        if($method == "registration_post"){
            return $this->$method();
        }
        if($method == "user_post"){
            return $this->$method($param1);
        }
        if($method == "update_post"){
            return $this->$method($param1);
        }
        if($method == "changePassword_post"){
            return $this->$method();
        }
        if($method == "sent_post"){
            return $this->$method();
        }
        if($method == "otpVerify_post"){
            return $this->$method();
        }
    }
    
    // array holding allowed Origin domains
    function cors() {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        
            exit(0);
        }
        
        echo "You have CORS!";
    }
    
    // User : LogIn API
    public function login_post() {

        //print_r('hello form Login Method');exit;

        // Get the post data
        $mobile = $this->input->post('mobile');
        $password = $this->input->post('password');
        $data = array(
            'mobile' => $mobile,
            'password' => $password
        );
        // Validate the post data
        if(!empty($mobile) && !empty($password))
        {
            
            // Check if any user exists with the given credentials
            $con['returnType'] = 'single';
            $con['conditions'] = array(
                'mobile' => $mobile,
                'password' => md5($password),
                'status' => 0
            );
            
            $user = $this->user->getRows($con); 
            
            if($user > 0){
                $this->data['status'] = 'True';
                $this->data['message'] = 'Login successful !';
                $this->data['result'] = $user;
                $this->response($this->data);
            }else{
                $this->data['status'] = 'False';
                $this->data['message'] = 'User Not Found, Please enter registered mobile number and password !';
                $this->data['result'] = $data;
                $this->response($this->data);
            }
            
        }else{
            $this->data['status'] = 'False';
            $this->data['message'] = 'Please enter registered mobile and password !';
            $this->data['result'] = $data;
            $this->response($this->data);    
        }
    }
    
    // User : Registration API
    public function registration_post() 
    {
        //print_r('hello form Registration Method');exit;
        
        // Get the post data
        // $id = strip_tags($this->post('id'));
        $name = strip_tags($this->post('name'));
        $email = strip_tags($this->post('email'));
        $mobile = strip_tags($this->post('mobile'));
        $password = md5($this->post('password'));
        $status = '0';

        // Validate the post data
        if(!empty($mobile) && !empty($password)){
            
            // Check if the given mobile already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'mobile' => $mobile,
            );
            
            $userCount = $this->user->getRows($con); //print_r($userCount);exit;
            
            if($userCount > 0){
                $this->data['status'] = 'False';
                $this->data['message'] = 'The given user information exists !';
                $this->response($this->data);
            }else{
                // Insert user data
                $userData = array(
                    // 'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'password' => $password,
                    'status' => $status,
                );

                $insert = $this->user->insert($userData);

                $result = $this->user->getRows($con);
            
                if($result != 0){
                    $this->data['status'] = 'True';
                    $this->data['message'] = 'Registration successful.';
                    $this->data['result'] = $userData;
                    $this->response($this->data);
                }else{
                    $this->data['status'] = 'False';
                    $this->data['message'] = 'Some problems occurred, please try again.';
                    $this->data['result'] = $userData;
                    $this->response($this->data);
                }
               
            }
        }else{
            // Set the response and exit
            $this->data['status'] = 'False';
            $this->data['message'] = 'Provide complete user info to add !';
            $this->response($this->data);
        }
    }
    
    // User : Get User by ID API
    public function user_post() 
    {
        $id = strip_tags($this->post('id'));

        $result = $this->user->verify_id($id);
        //print_r($result);exit;

        // Check if the user data exists
        if(!empty($result)){
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response([
                'status' => TRUE,
                'message' => 'User Details Found Successfully !',
                'result' => $result
            ]);
        }else{
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'User Details Not Found.'
            ]);
        }
    }
    
    // User : Update Data API
    function update_post()
	{   
        //print_r('hello form Update Method');exit;
        $id = strip_tags($this->post('id'));
        $result = $this->user->verify_id($id); //print_r($result);exit;

        if($result)
        {
            $name = strip_tags($this->input->post('name'));
            $email = strip_tags($this->input->post('email'));
            $mobile = strip_tags($this->input->post('mobile'));
            $dob = strip_tags($this->input->post('dob'));
            $gender = strip_tags($this->input->post('gender'));
                    // Validate the post data
            if(!empty($id))
            {
                // Update user's account data
                $userData = array();

                if(!empty($name)){
                    $userData['name'] = $name;
                }
                if(!empty($email)){
                    $userData['email'] = $email;
                }
                if(!empty($mobile)){
                    $userData['mobile'] = $mobile;
                }
                if(!empty($dob)){
                    $userData['dob'] = $dob;
                }
                if(!empty($gender)){
                    $userData['gender'] = $gender;
                }

                    $this->user->update($id, $userData);

                    $this->response([
                        'success' => true,
                        'Message' => 'User Data Updated Successfully !',
                        'result' => $userData
                    ]);
                }
                else
                {
                    $this->response([
                        'success' => false,
                        'Message' => 'User Data Updating Failed !'
                    ]);
                }
        }else{
            $this->response([
                'success' => false,
                'Message' => 'User ID Not Match !'
            ]);
        }
	}

    // User : Change Password API
    public function changePassword_post()
    {   
        // Get the post data
        $id = strip_tags($this->post('id'));
        $password = md5($this->post('password'));
        $confirm_password = md5($this->post('confirm_password'));
        $status = '0';

        $result = $this->user->verify_id($id); 

        // Validate the post data
        if(!empty($result))
        {
            if($password === $confirm_password)
            {   // Insert user data
                    $userData = array(
                        'password' => $password
                    );

                    $data = array('password' => $password);
                    $id=$this->input->post('id');

                    $insert = $this->user->myPasswordChange($id, $userData);
                
                    if($userData != 0){
                        $this->data['status'] = 'True';
                        $this->data['message'] = 'Password Change Successful !';
                        $this->data['result'] = $userData;
                        $this->response($this->data);
                    }else{
                        $this->data['status'] = 'False';
                        $this->data['message'] = 'Some problems occurred, please try again.';
                        $this->data['result'] = $userData;
                        $this->response($this->data);
                    }
            }else{
                $this->data['status'] = 'False';
                $this->data['message'] = 'Confirm Password Not Match !';
                $this->response($this->data);
            }
        }else{
            // Set the response and exit
            $this->data['status'] = 'False';
            $this->data['message'] = 'User ID is not registered with us, please try with another registered User ID !';
            $this->data['result'] = $result;
            $this->response($this->data);
        }
    }

    public function sent_post()
    {
        $mobile = strip_tags($this->post('mobile'));

        $result = $this->user->verify_mobile($mobile);
        //print_r($result);exit;

        if($result)
        {
            // Account details
            $apiKey = urlencode('Your apiKey');

            // Message details
            $numbers = array($mobile);
            $sender = urlencode('TXTLCL');
            $otp = rand(1000,9999);
            $message = rawurlencode($otp);
            $numbers = implode(',', $numbers);
            
            // Prepare data for POST request
            $data = array('apikey' => $apiKey, 'numbers' => $numbers, 'sender' => $sender, 'message' => $message);
            // Send the POST request with cURL
            $ch = curl_init('https://api.textlocal.in/send/');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            // Process your response here
            //echo $response;
            $data = array(
                'otp' => $otp,
                'mobile' => $mobile
            );

            $this->user->updateOtp($data, $mobile);

            $this->response([
                'status' => True,
                'message' => 'OTP Sended to Registered Mobile !',
                'result' => $data
            ]);

        }else{
            $data = array(
                'mobile' => $mobile
            );
            $this->response([
                'status' => False,
                'message' => 'Please Enter Registered Mobile !',
                'result' => $data
            ]);
        }
    }

    public function otpVerify_post() 
    {
		$otp = $this->input->post('otp');
        if(!empty($otp))
        {
            $mobile = $this->input->post('mobile');
            $password = md5($this->post('password'));

            $result = $this->user->verify_otp($otp, $mobile);
            //print_r($result);exit;

            if($result == true)
            {
                if(!empty($this->post('password'))){
                    
                    $userData = array('password' => $password);
                    $mobile=$this->input->post('mobile');
                    $this->user->changePassword($mobile, $userData);

                    $data['success']='OTP is verified, Password Change Successfully !';
                    $data['mobile']=$mobile;
                    $data['otp']=$otp;
                    $this->response($data);

                }else{
                    $data['success']='OTP is verified, Please enter password !';
                    $data['mobile']=$mobile;
                    $data['otp']=$otp;
                    $this->response($data);
                }

            }else{
                $data['error_message']='OTP does not match ';
                $data['mobile']=$mobile;
                $data['otp']=$otp;
                $this->response($data);
            }
        }else{
            $data['error_message']='Please Enter Valid OTP ';
            $data['otp']=$otp;
            $this->response($data);
        }
	}

} //Main Class Closed