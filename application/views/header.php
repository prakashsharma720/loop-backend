<?php 
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
     <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>
     <ul class="navbar-nav pull-right" style="padding-left: 92%;">
          &nbsp;
      <li class="nav-item" >
        <a href="<?php echo base_url();?>User_authentication/logout" class="btn btn-info" style="background-color: #dc7629 !important;border-color:#dc7629;"><i class="fa fa-power-off" ></i></a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #dc7629 !important;">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="<?php echo base_url(); ?>uploads/logo1.png" alt="LOOP" class="brand-image img-square elevation-3" style="background-color:black; margin-right: 25px">
      <span class="brand-text font-weight-light" style="font-size: 18px !important;"> <b> LOOP Newsletter </b> </span>
    </a>