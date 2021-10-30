<!DOCTYPE html>
	<html>
	<head>
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<meta charset="utf-8">
		<title>Order Details</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
		<style>
			
		</style>
	</head>
	<body>
	<h1 align="center">Order Details</h1>
	<hr>
		<table class="table">
			<tbody>				
				<tr width="50%">
					<td width="50%"><h3>Customer Details</h3></td>
				</tr>
				<tr width="50%">
					<td width="25%"><b>Name</b></td>
					<td width="25%"> : <?php echo $order_list[0]['user_id']; ?></td>
				</tr>
				<tr width="50%">
					<td width="25%"><b>Mobile</b></td>
					<td width="25%"> : <?php echo $order_list[0]['user_mobile']; ?></td>
				</tr>
				<tr width="50%">
					<td width="25%"><b>Email</b></td>
					<td width="25%"> : <?php echo $order_list[0]['user_email']; ?></td>
				</tr>
			</tbody>
		</table>
		<br>
		<hr>
		<table width="100%">
			<thead >
				<tr width="100%" >
					<th width="60%" valign="left" align="left">Subscription</th>
					<th width="20%" valign="left" align="left">Price</th>
					<th width="20%" valign="left" align="left">Plan Status</th>
					<th width="20%" valign="left" align="left">Amount</th>
				</tr>
			</thead>
			<tbody>
				<td width="60%" valign="left" align="left"><?php echo $order_list[0]['subscription_plan_id']; ?></td>
				<?php 
					if($order_list[0]['plan_status']=='1'){$order_list[0]['plan_status']="Monthly";} 
					if($order_list[0]['plan_status']=='3'){$order_list[0]['plan_status']="Quarterly";} 
					if($order_list[0]['plan_status']=='6'){$order_list[0]['plan_status']="Half Yearly";} 
					if($order_list[0]['plan_status']=='12'){$order_list[0]['plan_status']="Yearly";} 
				?>
				<td width="20%" valign="left" align="left"><?php echo $order_list[0]['subscription_price']; ?></td>
				<td width="20%" valign="left" align="left"><?php echo $order_list[0]['plan_status']; ?></td>
				<td width="20%" valign="left" align="left"><?php echo $order_list[0]['amount']; ?></td>
			</tbody>
		</table>

	</body>
</html>
