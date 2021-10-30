<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$current_page=current_url();
$data=explode('?', $current_page);
//print_r($data[0]);exit;
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
      <span class="card-title"><?php  echo $title; ?></span>
       <div class="pull-right error_msg">
          <a href="<?php echo base_url(); ?>Users_panel/add" class="btn btn-success" data-toggle="tooltip" title="New complaint"><i class="fa fa-plus"></i></a>
          <button class="btn btn-default" data-toggle="tooltip" title="Refresh" onclick="location.reload();"><i class="fa fa-refresh"></i></button>
          <button class="btn btn-danger delete_all" data-toggle="tooltip" title="Bulk Delete" ><i class="fa fa-trash"></i></button>  
      </div>
    </div> <!-- /.card-body -->
    
    <div class="card-body"> 
    <hr>

      <div class="table-responsive">

        <table id="example1" class="table table-bordered table-striped">

          <thead>
            <tr>
              <th><input type="checkbox" id="master"></th>
              <th >Sr.No.</th>
              <th style="white-space: nowrap;"> Name </th>
              <!-- <th style="white-space: nowrap;"> Email </th> -->
              <th style="white-space: nowrap;"> Mobile </th>
              <!-- <th style="white-space: nowrap;"> Date Of Birth </th>
              <th style="white-space: nowrap;"> Gender </th> -->
              <th style="white-space: nowrap;width: 20%;"> Action Button</th>
            </tr>

          </thead>

          <tbody>

           <?php
          $i=1;foreach($users as $obj){ ?>

            <tr>
                <td><input type="checkbox" class="sub_chk" value="<?php echo $obj['id']; ?>" /></td>
                <td><?php echo $i;?></td>
                <td><?php echo $obj['name']; ?></td>
                <!-- <td><?php echo $obj['email']; ?></td> -->
                <td><?php echo $obj['mobile']; ?></td>
                <!-- <td><?php echo $obj['dob']; ?></td>
                <td><?php echo $obj['gender']; ?></td> -->
                <td>
                  <a class="btn btn-xs btn-info btnEdit" data-toggle="modal" data-target="#view<?php echo $obj['id'];?>"><i style="color:#fff;"class="fa fa-eye"></i></a>
                  <a class="btn btn-xs btn-primary btnEdit" href="<?php echo base_url(); ?>Users_panel/edit/<?php echo $obj['id'];?>"><i class="fa fa-edit"></i></a>
                  <a class="btn btn-xs btn-danger btnEdit" data-toggle="modal" data-target="#delete<?php echo $obj['id'];?>"><i style="color:#fff;"class="fa fa-trash"></i></a>
                </td>
                  <!-- Modal for View Details -->
                  <div class="modal fade" id="view<?php echo $obj['id'];?>" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                             <h4 class="modal-title">User Details </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                            <div class="modal-body">
                              <div class="row">
                                  <div class="col-md-4">
                                      <label class="control-label"> Name : </label>
                                      <span><?php echo $obj['name']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Email : </label>
                                      <span><?php echo $obj['email']; ?></span>
                                  </div>
                                  <div class="col-md-4">
                                      <label class="control-label"> Mobile : </label>
                                      <span><?php echo $obj['mobile']; ?></span>
                                  </div>
                              </div>
                              <br>
                              <div class="row">
                                  <div class="col-md-6">
                                      <label class="control-label"> Date of Birth : </label>
                                      <span><?php echo $obj['dob']; ?></span>
                                  </div>
                                  <div class="col-md-6">
                                      <label class="control-label"> Gender : </label>
                                      <span><?php echo $obj['gender']; ?></span>
                                  </div>
                              </div>
                            </div> <!-- Modal Body Closed-->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal"> Close </button>
                            </div><!-- Modal footer-->
                        </div><!-- Modal content-->
                      </div><!-- Modal dialog-->
                    </div> <!-- Model Closed -->

                    <!-- Model for Delete -->
                    <div class="modal fade" id="delete<?php echo $obj['id'];?>" role="dialog">
                      <div class="modal-dialog">
                        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Users_panel/deleteRecord/<?php echo $obj['id'];?>">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                             <h4 class="modal-title">Confirm Header </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <p>Are you sure, you want to delete user <b><?php echo $obj['name'];?> </b>? </p>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary delete_submit"> Yes </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> No </button>
                          </div>
                        </div>
                        </form>
                      </div>
                    </div> <!-- Model Closed -->
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
      WRN_PROFILE_DELETE = "Are you sure you want to delete all selected issue?";  
      var check = confirm(WRN_PROFILE_DELETE);  
      if(check == true){  
        var join_selected_values = allVals.join(","); 
        $.ajax({   
          type: "POST",  
          url: "<?php echo base_url(); ?>Users_panel/deleteissue",  
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