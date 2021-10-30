<div class="container">
    <h1><?php echo $title; ?></h1>
    <hr>
    
    <!-- Display status message -->
    <?php if(!empty($error_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-md-6">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-control"> User ID <?php echo form_dropdown('user_id', $users) ?> </label>
                </div>
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter title" value="<?php echo !empty($image['title'])?$image['title']:''; ?>" >
                    <?php echo form_error('title','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <label>Images:</label>
                    <input type="file" name="image" class="form-control" multiple>
                    <?php echo form_error('image','<p class="help-block text-danger">','</p>'); ?>
                    <?php if(!empty($image['file_name'])){ ?>
                        <div class="img-box" style="margin:15px;">
                            <img src="<?php echo base_url('uploads/images/'.$image['file_name']); ?>">
                        </div>
                    <?php } ?>
                </div>
                
                <a href="<?php echo base_url(); ?>Manage_gallery/index" class="btn btn-secondary">Back</a>
                <input type="hidden" name="id" value="<?php echo !empty($image['id'])?$image['id']:''; ?>">
                <input type="submit" name="imgSubmit" class="btn btn-success" value="SUBMIT">
            </form>
        </div>
    </div>
</div>