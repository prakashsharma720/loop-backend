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
            <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Orders_panel/editCartOrders/<?php echo $id; ?>">
					<div class="form-container">
						<div class="row">
							<div class="form-group col-md-6">
								<input type="text" name="date" data-date-formate="dd-mm-yyyy" class="form-control date-picker"  value="<?php echo date('d-m-Y'); ?>" placeholder="dd-mm-yyyy" autofocus autocomplete="off">
							</div>
							<div class="form-group col-md-6">
								<input type="text" name="cart_id" value="<?= $current->cart_id ?>" class="form-control" placeholder="Cart ID">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label  class="form-control"> User ID <?php echo form_dropdown('user_id', $users, $current->user_id) ?> </label>
							</div>
							<div class="form-group col-md-6">
								<label  class="form-control"> Subscription Plan ID <?php echo form_dropdown('subscription_plan_id', $subscription, $current->subscription_plan_id) ?> </label>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label  class="form-control"> Amount <?php echo form_dropdown('amount', $price, $current->amount) ?> </label>
							</div>
							<div class="form-group col-md-6">
								<input type="text" name="other_tax" value="<?= $current->other_tax ?>" class="form-control" placeholder="Other Tax">
							</div>
						</div>
						<div class="row"> 
							<div class="form-group col-md-12">
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
