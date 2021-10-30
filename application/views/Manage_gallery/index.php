<div class="container">
    <h2>Gallery Images Management</h2>
	<title><?php echo $title; ?></title>

    <!-- Display status message -->
    <?php if(!empty($success_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
    <?php }elseif(!empty($error_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
    <?php } ?>
	
    <div class="row">
        <div class="col-md-12 head">
            <div class="float-right" style="margin-bottom: 18px; margin-top: -45px; margin-left: 18px;">
                <button class="btn btn-secondary" href="">Take Picture</button>
            </div>
            <div class="float-right" style="margin-bottom: 18px; margin-top: -45px">
                <a href="<?php echo base_url(); ?>Manage_gallery/add" class="btn btn-primary"><i class="plus"></i> Upload Image</a>
            </div>
        </div>
        <div>
        <!-- Data list table --> 
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th width="2%">#</th>
                        <th width="25%">Image</th>
                        <th width="5%">Title</th>
                        <th width="15%">Created</th>
                        <th width="3%">Status</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($gallery)){ $i=0;  
                        foreach($gallery as $row){ $i++; 
                            $image = !empty($row['file_name'])?'<img src="'.base_url().'uploads/images/'.$row['file_name'].'" alt="" style="width:100%" />':''; 
                            $statusLink = ($row['status'] == 1)?site_url('/Manage_gallery/block/'.$row['id']):site_url('/Manage_gallery/unblock/'.$row['id']);  
                            $statusTooltip = ($row['status'] == 1)?'Click to Inactive':'Click to Active';  
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $image; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['created']; ?></td>
                        <td><a href="<?php echo $statusLink; ?>" title="<?php echo $statusTooltip; ?>"><span class="badge <?php echo ($row['status'] == 1)?'badge-success':'badge-danger'; ?>"><?php echo ($row['status'] == 1)?'Active':'Inactive'; ?></span></a></td>
                        <td>
                            <a href="<?php echo base_url(); ?>Manage_gallery/view/<?php echo $row['id']; ?>" class="btn btn-primary">view</a>
                            <a href="<?php echo base_url(); ?>Manage_gallery/edit/<?php echo $row['id']; ?>" class="btn btn-warning">edit</a>
                            <a href="<?php echo base_url(); ?>Manage_gallery/delete/<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete data?')?true:false;">delete</a>
                        </td>
                    </tr>
                    <?php } }else{ ?>
                    <tr><td colspan="6">No image(s) found...</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>