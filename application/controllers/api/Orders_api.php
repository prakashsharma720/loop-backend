<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }

		$this->load->model('api/Orders_api_model');
		$this->load->model('Orders_model');
		$this->load->library('form_validation');
	}

	function index()
	{
		$data = $this->Orders_api_model->orders_list();

		$array = array(
			'success' => true,
			'Message' => 'Orders Detail !',
			'result' => $data
		);

		echo json_encode($array);
	}

	function insert()
	{
	   //echo"<pre>";print_r($_POST);exit;

		$this->form_validation->set_rules('order_id', 'Order ID', 'required');
		if($this->form_validation->run())
		{
			$order_id = strip_tags($this->input->post('order_id'));
			// Check if the given mobile already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'order_id' => $order_id,
            );
            $userCount = $this->Orders_api_model->getRows($con);
            
            if($userCount > 0){
                // Set the response and exit
				$array = array(
					'success' => false,
					'message' => 'The given Order ID already exists !',
					'result' => $userCount
				);
            }else{
				$plan_price = $this->db->select('price')->get('subscription')->result_array();
				$plan_price = $plan_price['0']['price'];
				$data = array(
					'order_date' => date('Y-m-d',strtotime($this->input->post('order_date'))),
					'order_id' => $this->input->post('order_id'),  
					'user_id' => $this->input->post('user_id'),  
					'subscription_plan_id' => $this->input->post('subscription_plan_id'),
					'plan_price' => $plan_price,
					'amount' => $this->input->post('amount'),
					'grand_total' => $this->input->post('amount'),
					'payment_terms' => $this->input->post('payment_terms'),  
					'payment_status' => $this->input->post('payment_status')
				);
				$this->Orders_api_model->insert_api($data);
				$array = array(
					'success' => true,
					'message' => 'Order Inserted Successfully !',
					'result' => $data
				);
			}
		}
		else
		{
			$array = array(
				'success' => true,
				'message' => 'Please enter order details !'
			);
		}
		echo json_encode($array);
	}
	// insert addon services
	function addon_order_insert() 
	{
		$result = $this->Orders_api_model->addon_insert();
		if($result=="True") {
			$array = array(
				'success' => true,
				'message' => 'Addon Services Added Successfully!'
			);
		} else {
			$array = array(
				'success' => true,
				'message' => 'Addon Services Not Added!'
			);
		}
		echo json_encode($array);
	}
	// insert addon services
	function addon_order_list() 
	{
		$result = $this->Orders_api_model->addon_order_list();
		if(!empty($result)) {
			$array = array(
				'success' => true,
				'message' => 'Addon Order List!',
				'result'  => $result
			);
		} else {
			$array = array(
				'success' => true,
				'message' => 'Addon Services Not Found!'
			);
		}
		echo json_encode($array);
	}
	
	// Get Single Order Details
	function fetch_single()
	{
		$user_id = $this->input->post('user_id');

		$result = $this->Orders_api_model->verify_user_id($user_id);

		if($result > 0)
		{
			$subscription = $this->Orders_api_model->fetch_single_order($this->input->post('user_id'));
			if(!empty($subscription)) {
				$data['subscription'] = $subscription;
			}
			$addons  = $this->Orders_api_model->fetch_single_addon_order($this->input->post('user_id'));
			if(!empty($addons)) {
				$data['addons'] = $addons;
			}

			if(!empty($data)) {
				$array = array(
					'success' => true,
					'message' => 'Order Details !',
					'result' => $data
				);
			} else {
				$array = array(
					'success' => true,
					'message' => 'Orders Not Found !',
					'result' => $data
				);
			}
			echo json_encode($array);
		} else {
			$array = array(
				'success' => false,
				'message' => 'User Not Found !',
			);
			echo json_encode($array);
		}	
	}

	function update()
	{
		
		$this->form_validation->set_rules('id', 'ID', 'required');

		if($this->form_validation->run())
		{	
			$data = array(
				'order_date' => date('Y-m-d',strtotime($this->input->post('order_date'))),
                'order_id' => $this->input->post('order_id'),  
                'user_id' => $this->input->post('user_id'),  
                'subscription_plan_id' => $this->input->post('subscription_plan_id'),
                'amount' => $this->input->post('amount'),
                'other_tax' => $this->input->post('other_tax'),  
                'grand_total' => $this->input->post('amount') + $this->input->post('other_tax'),
                // 'grand_total' => $this->input->post('grand_total'),  
                'payment_terms' => $this->input->post('payment_terms'),  
                'payment_status' => $this->input->post('payment_status')
			);

			$this->Orders_api_model->update_api($data,$id);

			$array = array(
				'success' => true,
				'message' => 'Order Updated Successfully !',
				'result' => $data
			);
		}
		else
		{
			$array = array(
				'success' => true,
				'message' => 'Please enter valid ID !',
			);
		}
		echo json_encode($array);
	}

	function delete()
	{
		if($this->input->post('id'))
		{
			if($this->Orders_api_model->delete_single_order($this->input->post('id')))
			{
				$array = array(

					'success' => true,
					'message' => 'Order Deleted Successfully !',
					'result' => $data
				);
			}
			else
			{
				$array = array(
					'error'		=>	true,
					'message' => 'Order Not Deleted !',

				);
			}
			echo json_encode($array);
		}
	}

//------------------------------------------------------------ Add To Cart API : -------------------------------------------------------------------------


// 
function userCart()
{
	$data = $this->Orders_api_model->orders_list();
	if(!empty($data)) {
		$array = array(
			'success' => true,
			'Message' => 'Cart Orders Detail !',
			'result' => $data
		);
	} else {		
		$array = array(
			'success' => true,
			'Message' => 'No Cart Orders Detail Found!',
		);
	}

	echo json_encode($array);
}
//Delete Record to Database
function delete_cart_item(){
	$id = $this->input->post('cart_id');
	$this->Orders_model->cartDelete($id);
	$array = array(
		'success' => true,
		'Message' => 'Cart Orders Deleted!',
	);
	echo json_encode($array);
}
//Add Functionality for Add To Cart
	function add_to_cart()
	{
		$this->form_validation->set_rules('user_id', 'User ID', 'required');
		if($this->form_validation->run())
		{
			$cart_id = strip_tags($this->input->post('cart_id'));

			// Check if the given mobile already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'cart_id' => $cart_id,
            );

            $userCount = $this->Orders_api_model->getRowsCart($con);
            
            if($userCount > 0){
                // Set the response and exit
				$array = array(
					'success' => false,
					'message' => 'The Given Cart ID Already Exists !',
					'result' => $userCount
				);
            }else{

				$data = array(
					'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					'cart_id' => $this->input->post('cart_id'),  
					'user_id' => $this->input->post('user_id'),  
					'subscription_plan_id' => $this->input->post('subscription_plan_id'),
					'amount' => $this->input->post('amount'),
					'other_tax' => $this->input->post('other_tax'),  
					'grand_total' => $this->input->post('amount') + $this->input->post('other_tax'),
					// 'grand_total' => $this->input->post('grand_total'), 
					'created_by' => $this->input->post('user_id'),
				);

				$this->Orders_api_model->insert_cart($data);

				$array = array(
					'success' => true,
					'message' => 'Order Added to Cart Successfully !',
					'result' => $data
				);
			}
		}
		else
		{
			$array = array(
				'success' => true,
				'message' => 'Please enter order details !'
			);
		}
		echo json_encode($array);
	}

	//Update Functionality for Add To Cart
	function cart_update()
	{
		$this->form_validation->set_rules('id', 'ID', 'required');
		if($this->form_validation->run())
		{	
			$data = array(
				'date' => date('Y-m-d',strtotime($this->input->post('date'))),
                'cart_id' => $this->input->post('cart_id'),  
                'user_id' => $this->input->post('user_id'),  
                'subscription_plan_id' => $this->input->post('subscription_plan_id'),
                'amount' => $this->input->post('amount'),
                'other_tax' => $this->input->post('other_tax'),  
                'grand_total' => $this->input->post('amount') + $this->input->post('other_tax'),
                // 'grand_total' => $this->input->post('grand_total'),
				'edited_by' => $this->input->post('user_id'),
			);

			$this->Orders_api_model->update_cart($this->input->post('id'), $data);

			$array = array(
				'success' => true,
				'message' => 'Order Updated Successfully !',
				'result' => $data
			);
		}
		else
		{
			$array = array(
				'success' => false,
				'message' => 'Please enter valid ID !'
			);
		}
		echo json_encode($array);
	}
	
//------------------------------------------------------------ Addon Cart API : -------------------------------------------------------------------------

// 
function addon_userCart()
{
	$data = $this->Orders_api_model->addon_orders_list();
	if(!empty($data)) {
		$array = array(
			'success' => true,
			'Message' => 'Addon Cart Orders Detail !',
			'result' => $data
		);
	} else {		
		$array = array(
			'success' => true,
			'Message' => 'No Addon Cart Orders Detail Found!',
		);
	}

	echo json_encode($array);
}
//Delete Record to Database
function addon_delete_cart_item(){
	$id = $this->input->post('addon_card_id');
	$this->Orders_api_model->addoncartDelete($id);
	$array = array(
		'success' => true,
		'Message' => 'Addon Cart Orders Deleted!',
	);
	echo json_encode($array);
}

//Addon Add To Cart
	function addon_add_to_cart()
	{
		$this->form_validation->set_rules('user_id', 'User ID', 'required');
		if($this->form_validation->run())
		{
			$addon_card_id = strip_tags($this->input->post('addon_card_id'));

			// Check if the given mobile already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'addon_card_id' => $addon_card_id,
            );

            $userCount = $this->Orders_api_model->addon_getRowsCart($con);
            
            if($userCount > 0){
                // Set the response and exit
				$array = array(
					'success' => false,
					'message' => 'The Given Cart ID Already Exists !',
					'result' => $userCount
				);
            }else{

				$data = array(
					'date'                  => date('Y-m-d',strtotime($this->input->post('date'))),
					'addon_card_id'         => $this->input->post('addon_card_id'),  
					'user_id'               => $this->input->post('user_id'),  
					'addon_service_id'      => $this->input->post('addon_service_id'),
					'price'                 => $this->input->post('price'),
					'qty'                   => $this->input->post('qty'),  
					'grand_total'           => $this->input->post('grand_total'),
					'created_by' => $this->input->post('user_id'),
				);

				$this->Orders_api_model->addon_insert_cart($data);

				$array = array(
					'success' => true,
					'message' => 'Order Added to Cart Successfully !',
					'result' => $data
				);
			}
		}
		else
		{
			$array = array(
				'success' => true,
				'message' => 'Please enter order details !'
			);
		}
		echo json_encode($array);
	}

	//Update Functionality for Add To Cart
	function addon_cart_update()
	{
		$this->form_validation->set_rules('id', 'ID', 'required');
		if($this->form_validation->run())
		{	
			$data = array(
				'date'                  => date('Y-m-d',strtotime($this->input->post('date'))),
				'addon_card_id'         => $this->input->post('addon_card_id'),  
				'user_id'               => $this->input->post('user_id'),  
				'addon_service_id'      => $this->input->post('addon_service_id'),
				'price'                 => $this->input->post('price'),
				'qty'                   => $this->input->post('qty'),  
				'grand_total'           => $this->input->post('grand_total'),
				'edited_by' => $this->input->post('user_id'),
			);

			$this->Orders_api_model->addon_update_cart($this->input->post('id'), $data);

			$array = array(
				'success' => true,
				'message' => 'Order Updated Successfully !',
				'result' => $data
			);
		}
		else
		{
			$array = array(
				'success' => false,
				'message' => 'Please enter valid ID !'
			);
		}
		echo json_encode($array);
	}

} //Main Class Closed Here...


?>
