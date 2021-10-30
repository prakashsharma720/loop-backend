<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
	if (isset($this->session->userdata['logged_in'])) {
		$username = ($this->session->userdata['logged_in']['username']);
		$email = ($this->session->userdata['logged_in']['email']);
		$role_id = $this->session->userdata['logged_in']['role_id'];
		$employee_id = $this->session->userdata['logged_in']['id'];
	//echo $username;exit;
	} 
	else {
		header("location: login");
	}

	$curr_url=current_url();

	$result=$this->user_rights_url->getUrlList($role_id,$employee_id);
	$current_page=current_url();
	//$current_page='http://localhost/codeIgniter_project/loop_nl/Employees_panel/MyProfile/1';
	 // echo "<pre>";
	 // print_r($result);
	// $last=[];
	$base = base_url();
	// http://localhost/codeIgniter_project/loop_nl/User_authentication/dashboard
	//$current_page='Meenus/UserRights';
	$data=explode($base, $current_page);
	//print_r($data);
	$auth_page_old = $data[1];
	//echo $auth_page;exit;
	$edit_data=explode('/', $data[1]);
	//print_r($edit_data);exit;
	if(!empty($edit_data[0])){
	$auth_page=$edit_data[0].'/'.$edit_data[1];
	}else{
	$auth_page=$auth_page_old;
	}
	//echo $auth_page;exit;
	if(in_array($auth_page, $result))
	{
?>
	<!DOCTYPE html>
	<html>
	<?php $this->load->view('css'); ?>
	<body class="hold-transition sidebar-mini">
		<div class="wrapper">
			<!-- include your header view here -->
			<?php $this->load->view('header'); ?>
			<?php $this->load->view('menu'); ?>
			<div class="content-wrapper">
				<section class="content" style="padding-top: 10px;">
						<?= $contents ?>
				</section>
			</div>
			<?php $this->load->view('footer'); ?>
		</div>
	</body>
	<?php $this->load->view('js'); ?>
	</html>
	<?php   }  else { 
		redirect('User_authentication/dashboard');
		} ?>