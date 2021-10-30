<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Loop Newsletter Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
   <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>plugins/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/adminlte.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>plugins/iCheck/square/blue.css">
  <link rel='stylesheet' id='divi-fonts-css'  href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&#038;subset=latin,latin-ext&#038;display=swap' type='text/css' media='all' />
  <link rel='stylesheet' id='et-builder-googlefonts-cached-css'  href='https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic|Roboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic&#038;subset=latin,latin-ext&#038;display=swap' type='text/css' media='all' />
  <!-- Open Sans Font -->
  <link rel='stylesheet' id='divi-fonts-css' href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&#038;subset=latin,latin-ext&#038;display=swap' type='text/css' media='all' />
  <link rel='stylesheet' id='et-builder-googlefonts-cached-css'  href='https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic|Roboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic&#038;subset=latin,latin-ext&#038;display=swap' type='text/css' media='all' />
  <!-- Google Font: Source Sans Pro -->
  <style type="text/css">
    .login-page{
      color: #747474 !important;
    }
    body {
      top: 0;
      left: 0;
      z-index: -1;
      width: 100%;
      height: 100%;
      content: '';
      background-color:#eae4e4;
      font-family: Open Sans,Arial,sans-serif !important;
    }
  </style>
</head>
<body>
<div class="login-box">
  <div class="login-logo">
    <img src="<?php echo base_url() ;?>uploads/logo1.png" style="width:60%;">
  </div>
  <!-- /.login-logo -->
  <div class="card" style="background-color: #f8f9fa;">
    <div class="card-body login-card-body">
      <p class="login-box-msg" style="font-weight: 600;">Sign in to start your session</p>
      <div class="" >
        <?php echo validation_errors();?>
        <?php if($this->session->flashdata('failed')): ?>
          <div style="background-color: #ea7878;color: #fff; width: 100%;padding: 10px;margin-bottom: 10px; padding-left: 20px;" class="error_mesg"><?php echo $this->session->flashdata('failed'); ?>
           <i class="fa fa-close" id="close" style="padding-left: -10px;"></i>
        </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success')): ?>
          <div style="background-color: #1295AB;color: #fff; width: 100%;padding: 10px;margin-bottom: 10px;    padding-left: 40px;" class="error_mesg"><?php echo $this->session->flashdata('success'); ?>
               <i class="fa fa-close" id="close" style="padding-left: 80px;"></i>
            </div>
      <?php endif; ?>
    </div>
      <form action="<?php echo base_url() ;?>User_authentication/user_login_process" method="post">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off">
        </div>

         <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off">
        </div>
        
        <div class="row mb-3">
          <div class="col-12">
            <button type="submit" class="btn  btn-block btn-flat" style="background-color: #dc7629;color:#fff;">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
         <div class="row">
            <div class="col-6">
                <p class="mb-1">
                  <?php echo anchor('User_authentication/ForgotPassword', 'Forgot Password', 'title="Forgot Password" style="color:#924f1b;font-weight:600;" '); ?>
                </p>
            </div>
            <!-- <div class="col-6">
              <?php echo anchor('User_authentication/user_registration_show', 'User Registration', 'title="Registration" style="color:#924f1b;font-weight:600;" '); ?>
            </div> -->
          </div>
      </form>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()."assets/"; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url()."assets/"; ?>plugins/iCheck/icheck.min.js"></script>

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass   : 'iradio_square-blue',
      increaseArea : '20%' // optional
    })
  })

  $('#close').on('click', function(e) { 
   $(this).parent('.error_mesg').remove(); 
});
/*
    $(function() {
    setTimeout(function() {
        $(".error_mesg").hide('blind', {}, 500)
    }, 3000);
  });*/
</script>
</body>
</html>
