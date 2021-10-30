<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">

    <div class="card card-primary card-outline">

        <div class="card-header">
                <h3 class="card-title"> <?= $title ?></h3>
                <div class="pull-right error_msg">
                    <?php echo validation_errors();?>

                    <?php if (isset($message_display)) {
                    echo $message_display;
                    } ?>		
                </div>
        </div>
        <!-- card-header closed -->

        <div class="card-body">
        
                <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Users_panel/add_new_record">
					<div class="form-container">
						<div class="row">
							<div class="form-group col-md-6">
								<input type="text" name="name" class="form-control" placeholder="Enter Name">
							</div>
							<div class="form-group col-md-6">
								<input type="text" name="password" class="form-control" placeholder="Enter Password">
							</div>
							<div class="form-group col-md-6">
                                <input type="text" name="email" class="form-control" placeholder="Enter Email">
							</div>
							<div class="form-group col-md-6">
								<input type="text" name="mobile" class="form-control" placeholder="Mobile">
							</div>
							<div class="form-group col-md-6">
								<input type="text" data-date-formate="dd-mm-yyyy" name="dob" class="form-control date-picker" value="" placeholder="dd-mm-yyyy" autofocus>
							</div>
							<div class="form-group col-md-6">
								<input type="text" name="gender" class="form-control" placeholder="Gender">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<button type="reset" class="btn btn-primary btn-block mb-6">Clear</button>
							</div>    
							<div class="form-group col-md-6">
								<button type="submit" class="btn btn-primary btn-block mb-6">Submit</button>
							</div>
						</div>
					</div>
                </form>
                <!-- /form --> 
        </div>
        <!-- card-body closed -->
    </div>
    <!-- card-outline closed -->
</div>
<!-- container-fluid -->

<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>
