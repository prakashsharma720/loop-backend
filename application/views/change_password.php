<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //echo base_url(); exit;?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Change Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/adminlte.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>plugins/iCheck/square/blue.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Open Sans Font -->
  <link rel='stylesheet' id='divi-fonts-css' href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&#038;subset=latin,latin-ext&#038;display=swap' type='text/css' media='all' />
  <link rel='stylesheet' id='et-builder-googlefonts-cached-css'  href='https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic|Roboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic&#038;subset=latin,latin-ext&#038;display=swap' type='text/css' media='all' />
  
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
    <img src="<?php echo base_url() ;?>uploads/logo1.png" style="width:50%;">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <h3 class="login-box-msg">Change Password</h3>
      <div class="">                    
        <?php if(@$success_mesg): ?>
            <div style="background-color: green;color: #f8f9fa; width: 100%;padding: 10px;margin-bottom: 10px;    padding-left: 40px;" class="error_mesg"><?php echo @$success_mesg; ?>
               <i class="fa fa-close" id="close" style="padding-left: 45px;"></i>
            </div>
        <?php endif; ?>
    </div>
      <form action="<?php echo base_url() ;?>User_authentication/ChangePassword" method="post">
        <input type="hidden" name="email" value="<?= $email ?>">
         <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="password" class="form-control password" placeholder="Enter New Password" name="password" autocomplete="off" required>
        </div>

         <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="password" class="form-control cpassword" placeholder="Confirm Password" name="confirm_password" required autocomplete="off">
        </div>
         <div class="row mb-3">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn  btn-block btn-flat" style="background-color: #dc7629;color:#fff;"> Submit</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
     
      <!-- /.social-auth-links -->
		 <div class="row">
		 	<div class="col-12">
		      <p class="mb-1">
		      	<?php echo anchor('User_authentication/ForgotPassword', 'Forgot My Password', 'title=" forgot my password" style="color:#924f1b;font-weight:600;"'); ?>
		        <!-- <a href="#">I forgot my password</a> -->
		      </p>
		    </div>
		 </div>
    <!-- /.login-card-body -->
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
    $(document).on('blur','.cpassword', function (){
        var cpassword = $('.cpassword').val();
        var password = $('.password').val();
        //var confirmPassword = document.getElementById("txtConfirmPassword").value;
        if(password!='')
        {
          if (password != cpassword) {
              alert("Passwords do not match.");
              //$(this).val();
              $('.cpassword').val('');
          }
        }
    });

     $(document).on('blur','.password', function (){
        var cpassword = $('.cpassword').val();
        var password = $('.password').val();
        //var confirmPassword = document.getElementById("txtConfirmPassword").value;
        if(cpassword!='')
        {
          if (password != cpassword) {
              alert("Passwords do not match.");
              //$(this).val();
              $('.password').val('');
          }
        }
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
