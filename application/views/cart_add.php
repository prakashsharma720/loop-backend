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
                <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Orders_panel/add_to_cart_insert">
					<div class="form-container">
						<div class="row">
							<div class="form-group col-md-6">
								<input type="text" name="date" data-date-formate="dd-mm-yyyy" class="form-control date-picker" value="<?php echo date('d-m-Y'); ?>" placeholder="dd-mm-yyyy" autofocus autocomplete="off">
							</div>
							<div class="form-group col-md-6">
								<input type="text" name="cart_id" class="form-control" placeholder="Cart ID">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label class="form-control"> User ID <?php echo form_dropdown('user_id', $users) ?> </label>
							</div>
							<div class="form-group col-md-6">
								<label  class="form-control" name="subscription_plan_id"> Subscription Plan ID
									<select name="subscription_plan_id" id="options">
										<option value="" disabled selected>Select an option</option>
										<option value="1">STARTER</option>
										<option value="2">FAIRY GODMOTHER</option>
										<option value="3">EDUSALES</option>
									</select>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label class="form-control" name="amount"> Amount
									<select  onblur="findTotal()" class="amount" name="amount" id="choices">
										<option value=""  disabled selected>Please select an option</option>
									</select>
								</label>
							</div>
							<div class="form-group col-md-6">
								<input type="text" class="form-control amount" onblur="findTotal()" class="" name="other_tax" placeholder="Other Tax">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<input type="text" id="totalordercost" name="grand_total" class="form-control" placeholder="Grand Total">
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
<script>
// Map your choices to your option value
var lookup = {
   '1': ['99'],
   '2': ['250'],
   '3': ['500'],
};

// When an option is changed, search the above for matching choices
$('#options').on('change', function() {
   // Set selected option as variable
   var selectValue = $(this).val();

   // Empty the target field
   $('#choices').empty();
   
   // For each chocie in the selected option
   for (i = 0; i < lookup[selectValue].length; i++) {
      // Output choice in the target field
      $('#choices').append("<option value='" + lookup[selectValue][i] + "'>" + lookup[selectValue][i] + "</option>");
   }
});
</script>

<script>
function findTotal(){
    var arr = document.getElementsByClassName('amount');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
            tot += parseFloat(arr[i].value);
    }
    document.getElementById('totalordercost').value = tot;
}
</script>