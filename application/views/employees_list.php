<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//print_r($employees);exit;
?>

<style type="text/css">
  .btnEdit{
    width: 25%;
    border-radius: 5px;
    margin: 1px;
    padding: 1px;
  }
  .col-sm-6 ,.col-md-6{
      float: left;
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
      <span class="card-title"><?php  echo $title; ?>
      </span>
       <div class="button-group float-right">

         <a href="<?php echo base_url(); ?>Employees_panel/add" class="btn btn-success" data-toggle="tooltip" title="New Employee"><i class="fa fa-plus"></i></a>

         <button class="btn btn-default" data-toggle="tooltip" title="Refresh" onclick="location.reload();"><i class="fa fa-refresh"></i></button>

          <button class="btn btn-danger delete_all" data-toggle="tooltip" title="Bulk Delete" ><i class="fa fa-trash"></i></button>
        
      </div>
    </div> <!-- /.card-body -->
    <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th><input type="checkbox" id="master"></th>
              <th >Sr.No.</th>
              <th> Name </th>
              <!-- <th style="white-space: nowrap;"> Email </th> -->
              <!-- <th style="white-space: nowrap;">Mobile</th> -->
              <th style="white-space: nowrap;"> Role</th>
              <th style="white-space: nowrap;"> Department</th>
              <th style="white-space: nowrap;"> Photo</th>
              <th style="white-space: nowrap;width: 20%;"> Action Button</th>
            </tr>
          </thead>
          <tbody>
           <?php
          $i=1;foreach($employees as $obj){ ?>
              <tr>
                <td><input type="checkbox" class="sub_chk" value="<?php echo $obj['id']; ?>" /></td>
                <td><?php echo $i;?></td>
                <td><?php  
               $voucher_no= $obj['employee_code']; 
                    if($voucher_no<10){
                    $employee_id_code='EC000'.$voucher_no;
                    }
                    else if(($voucher_no>=10) && ($voucher_no<=99)){
                      $employee_id_code='EC00'.$voucher_no;
                    }
                    else if(($voucher_no>=100) && ($voucher_no<=999)){
                      $employee_id_code='EC0'.$voucher_no;
                    }
                    else{
                      $employee_id_code='EC'.$voucher_no;
                    }
                    echo $obj['name'].'('.$employee_id_code.')';

                ?></td>
                <!-- <td><?php echo $obj['email']; ?></td>
                <td><?php echo $obj['mobile_no']; ?></td> -->
                <td><?php echo $obj['role']; ?></td>
                <td><?php echo $obj['department_name']; ?></td>
                <td>
                  <?php if(!empty($obj['photo'])) { ?>
                    <div style="height: 10%;width: 100%;">
                      <img src="<?php echo base_url().'/uploads/employees/'.$obj['photo']; ?>"  width="100%;"/>
                    </div>
                    <?php } ?>
                  </td>
                <td >
                   <a class="btn btn-xs btn-info btnEdit" data-toggle="modal" data-target="#view<?php echo $obj['id'];?>"><i style="color:#fff;"class="fa fa-eye"></i></a>
                  <a class="btn btn-xs btn-primary btnEdit" href="<?php echo base_url(); ?>Employees_panel/edit/<?php echo $obj['id'];?>"><i class="fa fa-edit"></i></a>                  
                  <a class="btn btn-xs btn-danger btnEdit" data-toggle="modal" data-target="#delete<?php echo $obj['id'];?>"><i style="color:#fff;"class="fa fa-trash"></i></a>
                <!--   <a href="<?php //echo base_url(); ?>welcome/deleteSupplier/<?php echo $obj['id'];?>"
                   onclick="return confirm(\'Confirm Deletion.\')">Delete</a> -->
                </td>
                  <!-- Model for details -->
                  <div class="modal fade" id="view<?php echo $obj['id'];?>" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Employees Details </h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-md-4">
                                      <label class="control-label"> Name : </label>
                                      <span><?php echo $obj['name']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Employees Code : </label>
                                      <span><?php echo $obj['employee_code']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Email : </label>
                                      <span><?php echo $obj['email']; ?></span>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                      <label class="control-label"> Mobile Number : </label>
                                      <span><?php echo $obj['mobile_no']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Role ID : </label>
                                      <span><?php echo $obj['role_id']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Department ID : </label>
                                      <span><?php echo $obj['department_id']; ?></span>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                      <label class="control-label"> User Name : </label>
                                      <span><?php echo $obj['username']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Pan Number : </label>
                                      <span><?php echo $obj['pan_no']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Blood Group : </label>
                                      <span><?php echo $obj['blood_group']; ?></span>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                      <label class="control-label"> Gender : </label>
                                      <span><?php echo $obj['gender']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Aadhaar Number : </label>
                                      <span><?php echo $obj['aadhaar_no']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Medical Status : </label>
                                      <span><?php echo $obj['medical_status']; ?></span>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                      <label class="control-label"> Report Number : </label>
                                      <span><?php echo $obj['report_no']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Date of Birth : </label>
                                      <span><?php echo $obj['dob']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Photo : </label>
                                      <?php if(!empty($obj['photo'])) { ?>
                                        <div style="height: 10%;width: 100%;">
                                          <img src="<?php echo base_url().'/uploads/employees/'.$obj['photo']; ?>"  width="100%;"/>
                                        </div>
                                      <?php } ?>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                      <label class="control-label"> Address : </label>
                                      <span><?php echo $obj['address']; ?></span>
                                  </div>
                                  <div class="col-md-6">
                                      <label class="control-label"> Forgot Code : </label>
                                      <span><?php echo $obj['forgot_code']; ?></span>
                                  </div>
                              </div>
                            </div><!-- Modal Body Closed-->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal"> Close </button>
                            </div>
                        </div><!-- Modal content Closed-->
                      </div><!-- Model Dialog Closed -->
                  </div> <!-- Model Closed -->

                  <!-- Delete Modal -->
                  <div class="modal fade" id="delete<?php echo $obj['id'];?>" role="dialog">
                    <div class="modal-dialog">
                      <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Employees_panel/deleteEmployee/<?php echo $obj['id'];?>">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                             <h4 class="modal-title">Confirm Header </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                           
                          </div>
                          <div class="modal-body">
                            <p>Are you sure, you want to delete employees <b><?php echo $obj['name'];?> </b>? </p>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary delete_submit"> Yes </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> No </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                    
              </tr>
            <?php  $i++;} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
  $( document ).ready(function() {
     
    jQuery('#master').on('click', function(e) {
    if($(this).is(':checked',true))  
    {
      $(".sub_chk").prop('checked', true);  
    }  
    else  
    {  
      $(".sub_chk").prop('checked',false);  
    }  
  });
    jQuery('.delete_all').on('click', function(e) { 
    var allVals = [];  
    $(".sub_chk:checked").each(function() {  
      allVals.push($(this).val());
    });  
    //alert(allVals.length); return false;  
    if(allVals.length <=0)  
    {  
      alert("Please select row.");  
    }  
    else {  
      WRN_PROFILE_DELETE = "Are you sure you want to delete all selected records?";  
      var check = confirm(WRN_PROFILE_DELETE);  
      if(check == true){  
        var join_selected_values = allVals.join(","); 
        $.ajax({   
          type: "POST",  
          url: "<?php echo base_url(); ?>Employees_panel/deleteEmployee",  
          cache:false,  
          data: 'ids='+join_selected_values,  
          success: function(response)  
          {   
            $(".successs_mesg").html(response);
            location.reload();
          }   
        });
           
      }  
    }  
  });

  });

</script>