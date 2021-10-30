<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .box .overlay > .fa, .overlay-wrapper .overlay > .fa {
    top: 79%;
    left: 49%;
}
</style>
<style type="text/css">
#bread{
    width: 50%;
    margin-left: 8%;
}
#bread ul.treeview-menu{
    margin-right: 5px;
}
#bread ul{
    margin-bottom: 5px;
    margin-top: 5px;
}
#bread ul li label a.toggle{
    transition: background .3s ease;
    color: #b7f099 !important;
}
#bread ul li label {
    width: 100%;
    display: block;
    background: rgb(77, 55, 75);
    color: #fefefe;
    padding: 0.75em;
    margin-bottom: 0px;
    border-radius: 0.15em;
    transition: background 0.3s ease;
}
#bread ul li.treeview{
    border: 1px solid #383838;
    margin-top: 5px;
    border-radius: 4px;
}
</style>
<?php if($this->session->flashdata('success')): ?>
         <div class="alert alert-success alert-dismissible" >
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fa fa-check"></i> Success!</h5>
                 <?php echo $this->session->flashdata('success'); ?>
               </div>
          <!-- <span class="successs_mesg"><?php echo $this->session->flashdata('success'); ?></span> -->
      <?php endif; ?>

      <?php if($this->session->flashdata('failed')): ?>
         <div class="alert alert-error alert-dismissible " >
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fa fa-check"></i> Alert!</h5>
                 <?php echo $this->session->flashdata('failed'); ?>
               </div>
      <?php endif; ?>
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <span class="card-title"><?= $title ?></span>
        <div class="pull-right ">
			
		</div>
	      </div> <!-- /.card-body -->
	      	<div class="card-body">
	      		 <ul class="nav nav-pills ml-auto p-2">
                  <li class="nav-item"><a class="nav-link active show" href="#rolewise" data-toggle="tab" > Role Wise </a></li>
                  <li class="nav-item"><a class="nav-link" href="#empwise" data-toggle="tab"> Employee Wise</a></li>

                </ul>               
		            <div class="tab-content">
	                  	<div class="tab-pane  active show" id="rolewise">
	                  		<form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Meenus/addRoleRights">
			                  	<div class="row col-md-12">
			                  		<label> Select Role</label>
			                  		<div class="col-md-6 rolewise">
			                  			 <?php  
					            			echo form_dropdown('role_id', $roles)
					            		?>
			                  		</div>
			                  	</div>
			                  
				                  	<div id="rolewisedatashow">
											
				                  	</div>
			                </form>
	                  	</div>
	                  <div class="tab-pane  show" id="empwise">
	                  	<form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Meenus/addEmployeeRights">
		                  	<div class="row col-md-12">
			                  	<label> Select Employee</label>
			                  		<div class="col-md-6 empwise">
			                  			 <?php  
					            			echo form_dropdown('employee_id', $employees)
					            		?>
			                  		</div>
			                  </div>
		                  	<div id="empwisedatashow">
										
			                </div>

		                  </form>
		                </div>
					</div>
			</div>
		</div>		
<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		var base_url='<?php echo base_url() ;?>';
		//alert(base_url);
		$(document).on('change','.rolewise',function(){
				var role_id = $('.rolewise').find('option:selected').val();
				//var aa= base_url+"Meenus/rolewisedata/"+role_id;
				//alert(role_id);
				$.ajax({
	                type: "POST",
	                url:"<?php echo base_url('Meenus/rolewisedata/') ?>"+role_id,
	                //data: {id:role_id},
	                dataType: 'html',
	                success: function (response) {
	                	//alert(response);
	                    $("#rolewisedatashow").html(response);
	                }
            	});
			}); 

			$(document).on('change','.empwise',function(){
					var employee_id = $('.empwise').find('option:selected').val();
					//alert(employee_id);
					$.ajax({
	                type: "POST",
	                url:"<?php echo base_url('Meenus/employeewisedata/') ?>"+employee_id,
	                //data: {id:role_id},
	                dataType: 'html',
	                success: function (response) {
	                	//alert(response);
	                    $("#empwisedatashow").html(response);
	                }
            	});
			}); 

			$(document).on('change','.menu_check',function(){
		        var now= $(this);
		        $(this).closest('li').find(' input[type=checkbox]').prop('checked', $(this).is(':checked'));
		        var sibs = false;
		        $(this).closest('ul').children('li').each(function () {
		            if($(this).find('input[type=checkbox]').is(':checked')) sibs=true;
		        });
		        $(this).parents('ul').prev().find('input[type=checkbox]').prop('checked', sibs);
		    });

			$(document).on('click','.toggle',function(e){
	        var now = $(this);
	        if (now.parent().next().hasClass('show')) {
	            now.parent().next().slideUp(350);
	            now.parent().next().removeClass('show');
	        } else {
	            now.parent().parent().parent().find('.inner').removeClass('show');
	            now.parent().parent().find('.inner').slideUp(350);
	            now.parent().next().toggleClass('show');
	            now.parent().next().slideToggle(350);
	        }
	   		});
		
	});
</script> 