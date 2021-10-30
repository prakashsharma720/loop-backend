<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo $grid_number;exit;
//$this->load->model('notifications_model');
?>


  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><?= $title?></h3>
        <div class="pull-right error_msg">
			<?php echo validation_errors();?>

			<?php if (isset($message_display)) {
			echo $message_display;
			} ?>		
		</div>

      </div> <!-- /.card-body -->
      <div class="card-body">
					<form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Rm_code/add_new_rmcode">
		        <div class="form-group">
		        	<div class="row col-md-12">
		        		<div class="col-md-4 col-sm-4 ">
			            	<label class="control-label">Type Of Notification <span class="required">*</span></label>
			            	<input type="text"  placeholder="Enter Type" name="type" class="form-control"  required autofocus>
			            </div>
			            <div class="col-md-4 col-sm-4 ">
			            	<label  class="control-label"> Subject <span class="required">*</span></label>	
			            	<input type="text"  placeholder="Enter subject" name="subject" class="form-control"  required autofocus>
			            </div>
			            <div class="col-md-4 col-sm-4 ">
			            	<label  class="control-label"> Message <span class="required">*</span></label>
			            	<textarea type="textarea"  placeholder="Enter message" name="message" class="form-control"  required autofocus></textarea>
			            </div>
		        	</div>
		        </div>
		         <div class="form-group">
		        	<div class="row col-md-12">			   
			            	<label  class="control-label" style="visibility: hidden;"> Grade</label>
			                <button type="submit" class="btn btn-primary btn-block"> Save</button>
		        	</div>
		        </div>
		        
		        
		    </form> <!-- /form -->
		</div>
	</div>
</div>
<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		var base_url='<?php echo base_url() ;?>';
		//alert(base_url);
		$(document).on('change','.category',function(){
				var category_id = $('.category').find('option:selected').val();
				//var aa= base_url+"Meenus/rolewisedata/"+role_id;
				//alert(category_id);
				$.ajax({
	                type: "POST",
	                url:"<?php echo base_url('Suppliers/getSupplierByCategory/') ?>"+category_id,
	                //data: {id:role_id},
	                dataType: 'html',
	                success: function (response) {
	                	//alert(response);
	                    $(".suppliers").html(response);
	                    $('.select2').select2();
	                }
            	});
			}); 
	});
</script> 