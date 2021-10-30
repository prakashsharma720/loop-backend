<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//print_r($menuList);exit;
?>
<?php //echo current_url(); ?>
<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><?= $title ?></h3>
            <div class="pull-right ">

                <?php echo validation_errors();?>
                
                <?php if($this->session->flashdata('success')): ?>
                    <span class="successs_mesg"><?php echo $this->session->flashdata('success'); ?></span>
                <?php endif; ?>

                <?php if($this->session->flashdata('failed')): ?>
                    <span class="error_mesg"><?php echo $this->session->flashdata('failed'); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row col-md-12">
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><?= $total_candidate ?></h3>

                    <p> Total Candidates</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                 
                </div>
              </div>
              <a href="<?php echo base_url(); ?>User_authentication/SendEmailtoAllUsers" >
                <div class="col-lg-3 col-6">
                  <!-- small box -->
                  <div class="small-box bg-success">
                    <div class="inner">
                      <h4>  Send Mail </a></h4>
                     
                    </div>
                    <div class="icon">
                      <i class="ion ion-envelope"></i>
                    </div>
                  </div>
                </a>
                </div>

              <!-- ./col -->
              
              <!-- ./col -->
              
              <!-- ./col -->
             
              <!-- ./col -->
            </div>
        </div>
    </div>
</div>