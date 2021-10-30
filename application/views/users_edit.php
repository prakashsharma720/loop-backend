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

                <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Users_panel/editusers/<?php echo $id; ?>">

                            <div class="form-group col-md-12">
								<input type="text" name="name" value="<?= $current->name ?>" class="form-control" placeholder="Enter Name">
							</div>

							<div class="form-group col-md-12">
                                <input type="text" name="email" value="<?= $current->email ?>" class="form-control" placeholder="Enter Email">
							</div>

							<div class="form-group col-md-12">
								<input type="text" name="mobile" value="<?= $current->mobile ?>" class="form-control" placeholder="mobile">
							</div>

							<!-- <div class="form-group col-md-12">
								<input type="text" name="password" value="<?= $current->password ?>" class="form-control" placeholder="password">
							</div> -->

                            <div class="form-group col-md-6">
								<input type="text" data-date-formate="dd-mm-yyyy" name="dob" class="form-control date-picker" value="<?php echo date('d-m-Y',strtotime($current->dob)); ?>" placeholder="dd-mm-yyyy" autofocus>
							</div>

							<div class="form-group col-md-6">
								<input type="text" name="gender" value="<?= $current->gender ?>" class="form-control" placeholder="Gender">
							</div>
                    
                    <button type="submit" class="btn btn-primary btn-block"> Save</button>
                
                </form>
                <!-- /form -->
                
        </div>
        <!-- card-body closed -->


    </div>
    <!-- card-outline closed -->

</div>
<!-- container-fluid -->


<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>
