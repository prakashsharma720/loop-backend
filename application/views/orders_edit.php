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
                <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>Orders_panel/editOrders/<?php echo $id; ?>">
					<div class="form-container">
						<div class="row">
							<div class="form-group col-md-4">
								<label> Order Date </label>
								<input type="text" name="order_date" data-date-formate="dd-mm-yyyy" class="form-control date-picker" value="<?php echo date('d-m-Y'); ?>" placeholder="dd-mm-yyyy" autofocus autocomplete="off">
							</div>
							<div class="form-group col-md-4">
								<label> Order ID </label>
								<input type="text" name="order_id" class="form-control" placeholder="Order ID">
							</div>
							<div class="form-group col-md-4">
								<label> User ID </label>
								<?php echo form_dropdown('user_id', $users) ?> 
							</div>
						<div class="form-group col-md-4">
							<label name="subscription_plan_id"> Subscription Plan ID </label>
							<select class="form-control " name="subscription_plan_id" id="subscription_plan_id">
								<option value="" disabled selected>Select an option</option>
								<?php  foreach ($subscription as $key => $value) { ?> 
								<option value="<?php echo $value['id']; ?>" price="<?php echo $value['price']?>" ><?php echo $value['title']?></option>
								<?php } ?>
								
							</select>
						</div>	
							<div class="form-group col-md-4">
								<label name="amount"> Amount </label>
								<input type="text" name="amount" class="form-control amount" value="" readonly="readonly" placeholder="Subscription Plan Amount">
							</div>
							<div class="form-group col-md-4">
								<label> Payment Flow </label>
								<select class="form-control payment_flow" name="plan_status" aria-label="Default select example">
									<option value="1">Monthly</option>
									<option value="12" >Yearly</option>
								</select>	
							</div>
							<div class="form-group col-md-4">
								<label> Total </label>
								<input type="text" name="plan_total" class="form-control plan_total" placeholder="Plan Total" readonly="readonly">
							</div>							
							<div class="form-group col-md-4">
								<label> Payment Terms </label>
								<input type="text" name="payment_terms" class="form-control" placeholder="Payment Terms">
							</div>
							<div class="form-group col-md-4">
								<label name="payment_status"> Payment Status </label>
								<select class="form-control form-select" name="payment_status" aria-label="Default select example">
									<option value="1">Pending</option>
									<option value="2" disabled>Success</option>
								</select>	
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-9">
								<h4> If you wish to take addon services then click on this button</h4>
							</div>
							<div class="col-md-3">
								 <button type="button" class="btn btn-success btn_addon_show" ><i class="fa fa-plus"> </i> Addon Services</button>
								  <button type="button" class="btn btn-danger btn_addon_hide hide" ><i class="fa fa-minus"> </i> Remove Addon</button>
							</div>
						</div>
						
						<hr>
						<fieldset class="box_addon ">
						<legend> Addon Services</legend>
							<div class="row col-md-12">
				        		<div class="table-responsive">
					        		<table class="table table-bordered " id="maintable" >
					        			<thead style="background-color: #ca6b24;">
				        					<tr>
					        					<th > Sr.No.</th>
					        					<th > Addon Service </th>
					        					<th > Price</th>
					        					<th > Qty</th>
					        					<th > Total</th>
					        					<th style="width: 12%;"> Action </th>
					        				</tr>
					        			</thead>
					        			<tbody id="mainbody">
			        						<tr class="main_tr1">
												<td>1</td>
												<td> 
													<select class="form-control addon_service_id" name="addon_service_id[]">
														<option value="" disabled selected>Select an option</option>
														<?php  foreach ($addonservices as $key => $value) { ?> 
														<option value="<?php echo $value['id']; ?>" price="<?php echo $value['price']?>" ><?php echo $value['title']?></option>
														<?php } ?>
														
													</select>

												</td>
												<td> 
													<input type="text" name="price[]" class="form-control price" placeholder="Price" style="margin-bottom: 5px;" readonly="readonly"> 
												</td>
												<td> 
													<input type="text" name="qty[]" class="form-control qty" placeholder="Enter Qty" style="margin-bottom: 5px;"> 
												</td>
												<td> 
													<input readonly type="text" name="amount[]" class="form-control total_amt_addon" placeholder="Amount" style="margin-bottom: 5px;"> 
												</td>
												<td >
													<button type="button" class="btn btn-xs btn-primary addrow"  href="#" role='button'><i class="fa fa-plus"></i></button> 
													<button type="button" class="btn btn-xs btn-danger deleterow" href="#" role='button'><i class="fa fa-minus"></i></button>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4" style="text-align: right;"> <b> Grand Total</b> (Subscription + Addon Service) </td>
												<td ><input readonly type="text" name="grand_total" class="form-control grand_total" placeholder="Grand Amount" style="margin-bottom: 5px;"> </td>
												<td></td>
											</tr>
										</tfoot>
					        		</table>
					        	</div>
					        </div>
					    </fieldset>
					    <br>
					    <div class="row ">
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
				<select class="form-control addon_service_id" name="addon_service_id[]">
					<option value="" disabled selected>Select an option</option>
					<?php  foreach ($addonservices as $key => $value) { ?> 
					<option value="<?php echo $value['id']; ?>" price="<?php echo $value['price']?>" ><?php echo $value['title']?></option>
					<?php } ?>
					
				</select>

			</td>
			<td> 
				<input type="text" name="price[]" class="form-control price" placeholder="Price" style="margin-bottom: 5px;" readonly="readonly"> 
			</td>
			<td> 
				<input type="text" name="qty[]" class="form-control qty" placeholder="Enter Qty" style="margin-bottom: 5px;"> 
			</td>
			<td> 
				<input readonly type="text" name="amount[]" class="form-control total_amt_addon" placeholder="Amount" style="margin-bottom: 5px;"> 
			</td>
			<td >
				<button type="button" class="btn btn-xs btn-primary addrow"  href="#" role='button'><i class="fa fa-plus"></i></button> 
				<button type="button" class="btn btn-xs btn-danger deleterow" href="#" role='button'><i class="fa fa-minus"></i></button>
			</td>
		</tr>
</table>

<script src="<?php echo base_url()."assets/"; ?>plugins/jquery/jquery.min.js"></script>
<script>
	$('.box_addon').addClass('hide');

	$('body').on('click','.btn_addon_show',function(){
		$('.box_addon').removeClass('hide');
		$('.btn_addon_show').addClass('hide');
		$('.btn_addon_hide').removeClass('hide');
	});

	$('body').on('click','.btn_addon_hide',function(){
		$('.box_addon').addClass('hide');
		$('.btn_addon_hide').addClass('hide');
		$('.btn_addon_show').removeClass('hide');
	});


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
	$('#subscription_plan_id').on('change', function(){    
        var element = $(this).find('option:selected'); 
        var myTag = element.attr("price"); 
        $('.amount').val(myTag);
        var payment_flow=parseFloat($('.payment_flow').val());
		var amount=parseFloat($('.amount').val());
        var plan_total=amount*payment_flow;
        $('.plan_total').val(plan_total.toFixed(2)); 
    }); 
    	$(document).on('change','.payment_flow',function(){
    		var payment_flow=parseFloat($(this).val());
			var amount=parseFloat($('.amount').val());
	        var plan_total=amount*payment_flow;
	        $('.plan_total').val(plan_total.toFixed(2));

	    });
	    $(document).on('keyup','.qty',function(){
			var table=$(this).closest('table');
			calculate_total(table);

	    });
	    $(document).on('blur','.qty',function(){
			var table=$(this).closest('table');
			calculate_total(table);

	    });

	    $(document).on('change','.addon_service_id',function(){
			var table=$(this).closest('table');
			calculate_total(table);

	    });

	function calculate_total(table)
		{
			var total_amount=0;
			var total_amount_footer=0;
		
			table.find(" tbody tr.main_tr1").each(function()
			{
				//var qty,rate,total=0;
				var price=parseFloat($(this).find("td:nth-child(2) select.addon_service_id option:selected").attr('price'));
				//alert(price);
				$(this).find("td:nth-child(3) input.price").val(price);
				var qty=parseFloat($(this).find("td:nth-child(4) input.qty").val());
				var rate=parseFloat($(this).find("td:nth-child(3) input.price").val());
				//alert();
				if(isNaN(qty))
				{
					qty =0;
				}
				if(isNaN(rate))
				{
					rate =0;
				}
				total_amount=qty*rate;

				total_amount_footer=total_amount_footer+total_amount;

				$(this).find("td:nth-child(5) input.total_amt_addon").val(total_amount.toFixed(2))
				//alert(total_qty);
			});
			var sub_plan_total= parseFloat($('.plan_total').val()); 
			var grand_total=total_amount_footer+sub_plan_total;
			//alert(grand_total);
			table.find("tfoot tr input.grand_total").val(grand_total.toFixed(2));

		}
		

</script>
