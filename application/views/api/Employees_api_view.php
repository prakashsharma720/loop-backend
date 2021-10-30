<html>
<head>
    <title>Loop Newsletter</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
</head>
<body>
    <div class="container">
        <br />
        <h3 align="center"> Loop Newsletter </h3>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="panel-title">User's Details</h3>
                    </div>
                    <div class="col-md-6" align="right">
                        <button type="button" id="add_button" class="btn btn-info btn-xs">Add</button>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <span id="success_message"></span>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;"> ID </th>
                            <th style="white-space: nowrap;"> Name </th>
                            <th style="white-space: nowrap;"> Employee Code </th>
                            <th style="white-space: nowrap;"> Email </th>
                            <th style="white-space: nowrap;"> Mobile No </th>
                            <th style="white-space: nowrap;"> Role ID </th>
                            <th style="white-space: nowrap;"> Department ID </th>
                            <th style="white-space: nowrap;"> Username </th>
                            <th style="white-space: nowrap;"> Password </th>
                            <th style="white-space: nowrap;"> Pan No </th>
                            <th style="white-space: nowrap;"> Blood Group </th>
                            <th style="white-space: nowrap;"> Gender </th>
                            <th style="white-space: nowrap;"> Aadhar No </th>
                            <th style="white-space: nowrap;"> Medical Status </th>
                            <th style="white-space: nowrap;"> Report No </th>
                            <th style="white-space: nowrap;"> DoB </th>
                            <th style="white-space: nowrap;"> Photo </th>
                            <th style="white-space: nowrap;"> Address </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add User</h4>
                </div>
                <div class="modal-body">
                    <label>Name</label>
                    <input type="text" name="name" id="name" class="form-control" />
                    <span id="name_error" class="text-danger"></span>
                    <br />
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control" />
                    <span id="username_error" class="text-danger"></span>
                    <br />
                    <label>Password</label>
                    <input type="text" name="password" id="password" class="form-control" />
                    <span id="password_error" class="text-danger"></span>
                    <br />
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" />
                    <input type="hidden" name="data_action" id="data_action" value="Insert" />
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" language="javascript" >

// alert('hello');

$(document).ready(function(){
    function fetch_data()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>api/test_api/action",
            method:"POST",
            data:{data_action:'fetch_all'},
            success:function(data)
            {
                $('tbody').html(data);
            }
        });
    }

    fetch_data();

    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Add Subscription");
        $('#action').val('Add');
        $('#data_action').val("Insert");
        $('#userModal').modal('show');
});

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url() . 'test_api/action' ?>",
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            success:function(data)
            {
                if(data.success)
                {
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    fetch_data();
                    if($('#data_action').val() == "Insert")
                    {
                        $('#success_message').html('<div class="alert alert-success">Data Inserted</div>');
                    }
                }

                if(data.error)
                {
                    $('#title_error').html(data.title_error);
                    $('#description_error').html(data.description_error);
                    $('#price_error').html(data.price_error);
                    $('#duration_error').html(data.duration_error);
                }
            }
        })
    });

    $(document).on('click', '.edit', function(){
        var user_id = $(this).attr('id');
        $.ajax({
            url:"<?php echo base_url() . 'test_api/action' ?>",
            method:"POST",
            data:{user_id:user_id, data_action:'fetch_single'},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#title').val(data.title);
                $('#description').val(data.description);
                $('#price').val(data.price);
                $('#duration').val(data.duration);
                $('.modal-title').text('Edit User');
                $('#user_id').val(user_id);
                $('#action').val('Edit');
                $('#data_action').val('Edit');
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var user_id = $(this).attr('id');
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"<?php echo base_url() . 'test_api/action' ?>",
                method:"POST",
                data:{user_id:user_id, data_action:'Delete'},
                dataType:"JSON",
                success:function(data)
                {
                    if(data.success)
                    {
                        $('#success_message').html('<div class="alert alert-success">Data Deleted</div>');
                        fetch_data();
                    }
                }
            })
        }
    });
    
});
</script>