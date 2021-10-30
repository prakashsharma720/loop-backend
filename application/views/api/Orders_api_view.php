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
                        <h3 class="panel-title">Order's Details</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <span id="success_message"></span>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;"> ID </th>
                            <th style="white-space: nowrap;"> Order Date </th>
                            <th style="white-space: nowrap;"> Order ID </th>
                            <th style="white-space: nowrap;"> User ID </th>
                            <th> Subscription Plan ID </th>
                            <th style="white-space: nowrap;"> Amount </th>
                            <th style="white-space: nowrap;"> Other Tax </th>
                            <th style="white-space: nowrap;"> Payment Terms </th>
                            <th style="white-space: nowrap;"> Payment Status </th>
                            <th style="white-space: nowrap;"> Payment Status </th>
                            <th style="white-space: nowrap;"> Action Button </th>
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

<script type="text/javascript" language="javascript" >
    $(document).ready(function(){
        function fetch_data()
        {
            $.ajax({
                url:"<?php echo base_url(); ?>api/Orders_test_api/action",
                method:"POST",
                data:{data_action:'fetch_all'},
                success:function(data)
                {
                    $('tbody').html(data);
                }
            });
        }

        fetch_data();
    });
</script>