<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h5 style="margin: 20px; margin-left: 55px"><?php echo !empty($image['title'])?$image['title']:''; ?></h5>
            <?php if(!empty($image['file_name'])){ ?>
                <div class="img-box">
                    <img src="<?php echo base_url('uploads/images/'.$image['file_name']); ?>">
                </div>
            <?php } ?>
            
            <div style="margin: 20px; margin-left: 55px">
                <a href="<?php echo base_url(); ?>Manage_gallery/index" class="btn btn-primary">Back to List</a>
            </div>

        </div>
    </div>
</div>