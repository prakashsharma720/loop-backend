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

                <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Addon_services/editRecord/<?php echo $id; ?>">
                    <div class="form-container">
                        <div class="row">
                            <div class="form-group col-md-6">
                               <label> Title </label><span class="required">*</span>
                                <input type="text" name="title" class="form-control" placeholder="Enter Addon Service Name" value="<?= $current['title'] ?>" required="required">
                            </div>
                           <div class="form-group col-md-6">
                             <label for="title">Price :</label><span class="required">*</span>
                                <input type="text" name="price" class="form-control" placeholder="Enter Price" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" required="required" value="<?= $current['price'] ?>">
                            </div>

                            <div class="form-group col-md-12">
                                 <label> Description </label>
                                <textarea name="description" class="form-control" placeholder="Description" rows="2" value="<?= $current['description'] ?>" ><?= $current['description'] ?></textarea>
                            </div>

                          <!--   <div class="form-group col-md-12">
                                <label for="duration">Duration :</label>
                                <input type="text" name="duration" class="form-control" id="duration" value="<?= $current['duration'] ?>" placeholder="Duration">
                            </div> -->
                        </div>

                            <fieldset>
                            <legend> Add Features Details</legend>
                                    <div class="form-group">
                            <div class="row col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered " id="maintable" >
                                        <thead style="background-color: #ca6b24;">
                                            <tr>
                                                <th style="width: 5%;">  Sr.No.</th>
                                                <th style="width: 75%;"> Features </th>
                                                <th style="width: 20%;"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody id="mainbody">
                                           
                                            <?php 
                                            $j=1;
                                            foreach ($current['features_list'] as $key => $value) { ?>
                                            <tr class="main_tr1">
                                                <td><?php echo $j; ?></td>
                                                <td> 
                                                    <input type="text" name="features[]" class="form-control Features" placeholder="Features" style="margin-bottom: 5px;" value="<?= $value['features']?>"> 
                                                </td>
                                                <td >
                                                    <button type="button" class="btn btn-xs btn-primary addrow"  href="#" role='button'><i class="fa fa-plus"></i></button> 
                                                    <button type="button" class="btn btn-xs btn-danger deleterow" href="#" role='button'><i class="fa fa-minus"></i></button>
                                                </td>
                                            </tr>

                                           <?php $j++;} ?>

                                                
                                    </table>
                                </div>
                            </div>
                        </div>
                        </fieldset><br>

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


<table id="sample_table1" style="display: none;">
        <tr class="main_tr1">
            <td>1</td>
            <td> 
                <input type="text" name="features[]" class="form-control Features" placeholder="Features" style="margin-bottom: 5px;"> 
            </td>
            <td >
                <button type="button" class="btn btn-xs btn-primary addrow"  href="#" role='button'><i class="fa fa-plus"></i></button> 
                <button type="button" class="btn btn-xs btn-danger deleterow" href="#" role='button'><i class="fa fa-minus"></i></button>
            </td>
        </tr>
</table>
<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>

<script>
    $('body').on('click','.addrow',function(){

            var table=$(this).closest('table');
            add_row();
            rename_rows();
        });
        function add_row(){ 
            var tr1=$("#sample_table1 tbody tr").clone();

            $("#maintable tbody#mainbody").append(tr1);

        }

        $('body').on('click','.deleterow',function(){
            
        var table=$(this).closest('table');
        var rowCount = $("#maintable tbody tr.main_tr1").length;
        if (rowCount>1){
            if (confirm("Are you sure to remove row ?") == true) {
                $(this).closest("tr").remove();
                rename_rows();
            } 
        }
        }); 
        function rename_rows(){
        var i=0;
        $("#maintable tbody tr.main_tr1").each(function(){ 
            $(this).find("td:nth-child(1)").html(++i);
            
        });
    }
</script>

