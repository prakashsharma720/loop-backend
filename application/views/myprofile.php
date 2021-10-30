<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style type="text/css">
	.select2{
		height:45px !important;
		width: 100% !important;
	}
  .btnEdit{
    width: 25%;
    border-radius: 5px;
    margin: 1px;
    padding: 1px;
  }

</style>
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><?= $title ?></h3>
       		<div class="button-group float-right">
		        <?php if($this->session->flashdata('success')): ?>
		          <span class="successs_mesg"><?php echo $this->session->flashdata('success'); ?></span>
		        <?php endif; ?>
		        <?php if($this->session->flashdata('failed')): ?>
		            <span class="error_mesg"><?php echo $this->session->flashdata('failed'); ?></span>
		        <?php endif; ?>
	      </div>
	    </div> 
		<!-- /.card-body -->
      	<div class="card-body">
      		<div class="row">
      			<div class="col-md-3">
      				<span>
      					<img src="<?php echo base_url()."uploads/".$result['photo']; ?>" class="img-circle " alt="User Image" style="width: 100%;height: 100%;">
      				</span>
      			</div>
	      		<div class="col-md-9 table-responsive">
				        <table class="table">
				        	<tr>
								<th> Full Name </th>
								<td> <?= $result['name'] ?></td>
								<th> Employee Code </th>
								<td> <?= $result['employee_code'] ?></td>

							</tr>	
							<tr>
								<th> Designation </th>
								<td> <?= $result['role'] ?></td>
								<th> Department </th>
								<td> <?= $result['department_name'] ?></td>
							</tr>	
							<tr>
								<th> Email </th>
								<td> <?= $result['email'] ?></td>
								<th> Mobile </th>
								<td> <?= $result['mobile_no'] ?></td>
							</tr>	
							<tr>
								<th> Username </th>
								<td> <?= $result['username'] ?></td>
								<th> Blood Group </th>
								<td> <?= $result['blood_group'] ?></td>
							</tr>
							<tr>
								<th> Aadhaar No </th>
								<td> <?= $result['aadhaar_no'] ?></td>
								<th> Date of Birth </th>
								<td> <?php  echo date('d-M-Y',strtotime($result['dob'])); ?></td>
							</tr>
							<tr>
								<th> Address </th>
								<td colspan="4"> <?= $result['address'] ?></td>
								
							</tr>					        					        	
				        </table>
				</div>
			</div>
		</div>
	</div>
</div>

