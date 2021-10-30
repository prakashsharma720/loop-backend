<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
  if (isset($this->session->userdata['logged_in'])) 
  {
    $user_id = $this->session->userdata['logged_in']['id'];
    $username = $this->session->userdata['logged_in']['username'];
    $email = $this->session->userdata['logged_in']['email'];
    $name = $this->session->userdata['logged_in']['name'];
    $role_id = $this->session->userdata['logged_in']['role_id'];
    $role = $this->session->userdata['logged_in']['role'];
    $photo = $this->session->userdata['logged_in']['photo'];
  }
  else 
  {
    header("location: login");
  } 
?>

<div class="sidebar" style="background-color: #291f20 !important; overflow: auto;">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class=" " style="width: 35%;">
          <img src="<?php echo base_url()."uploads/".$photo; ?>" class="img-circle " alt="User Image" style="width: 90%">
          <div class="info" >
            <a href="#" class="d-block"> Name : <?php echo $name ;?></a>
            <a href="#" class="d-block"> Role : <?php echo $role ;?></a>
          </div>
        </div>
        <div class="info" >
          <a href="<?php echo base_url();?>User_authentication/MyPasswordChangeView" class="c-block"> Change Password</a>
        </div>
        <div class="info" >
          <a href="<?php echo base_url();?>Employees_panel/MyProfile/<?= $user_id ?>" class="d-block"> Profile</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <?php
          echo $this->dynamic_menu->build_menu();
        ?>
      </nav>
      <!-- /.sidebar-menu -->
</div>
</aside>
 