<!-- Add jQuery library -->
<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script> -->

<!-- Fancybox CSS library -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fancybox/jquery.fancybox.css'); ?>">
<!-- Fancybox JS library -->
<script src="<?php echo base_url('assets/fancybox/jquery.fancybox.js'); ?>"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/lib/jquery.mousewheel.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fancybox/source/jquery.fancybox.css?v=2.1.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.7"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
  

    <div class="head" style="margin: 15px;">
        <nav class="navbar navbar-expand-lg navbar-light bg-gray">
            <a class="navbar-brand"><h2>Gallery</h2></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                       
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <a href="<?php echo base_url(); ?>Manage_gallery/index" class="btn btn-primary" role="button"> <p style="color:white; margin-top: 10px;"><strong> Manage Gallery </strong></p> </a>
                </form>
            </div>
        </nav>
    </div>    

<div class="container">
    <div class="row ol-md-12">
            <?php if(!empty($gallery)){ ?> 
            
            <?php foreach($gallery as $row)
            { 
                $uploadDir = base_url().'uploads/images/'; 
                $imageURL = $uploadDir.$row["file_name"]; 
            ?>
            
            <div class="col-lg-3">
                <a class="fancybox" href="<?php echo $imageURL; ?>" data-caption="<?php echo $row["title"]; ?>" >
                    <img src="<?php echo $imageURL; ?>" alt="" style="width: 80%;"/>
                    <p><?php echo $row["title"]; ?></p>
                </a>
            </div>
            <?php } ?> 
            </div>
            <?php }else{ ?>
                <div class="col-xs-12">
                    <div class="alert alert-danger">No image(s) found...</div>
                </div>
            <?php } ?>
    </div>
</div>

<!-- Initialize fancybox -->
<script>
    $(".fancybox").fancybox({
      helpers: { 
        buttons: {
          position: 'top'
        },
        title: {
          type: 'float'
        }
      }
    });
</script>