<?php

//session_start(); //we need to start session in order to access it through CI

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//print_r(BASEPATH);exit;

Class Orders_panel extends CI_Controller
{

    public function __construct() 
    {
        parent::__construct();
        if(!$this->session->userdata['logged_in']['id']){
            redirect('User_authentication/index');
        }

        /*require_once APPPATH.'third_party/PHPExcel.php';
        $this->excel = new PHPExcel(); */

        // Load form helper library
        $this->load->helper('form');
        $this->load->helper('url');
        // new security feature
        $this->load->helper('security');

        // Load form validation library
        $this->load->library('form_validation');
		$this->load->library('Pdf');
        $this->load->library('encryption');
        // Load session library
        $this->load->library('session');
        $this->load->library('template');

        // Load database
        $this->load->model('Orders_model');
        //$this->load->library('excel');
    }
    //Orders View
	public function index() 
	{
        $data['title'] = 'Orders List';
		$data['orders'] = $this->Orders_model->orders_list();
		$this->template->load('template','orders_list',$data);
	}
    //Addon Orders View
	public function addon_index() 
	{
        $data['title'] = 'Addon Orders List';
		$data['orders'] = $this->Orders_model->addon_orders_list();
		$this->template->load('template','addon_order_list',$data);
	}
    //Orders PDF View
	public function order_pdf() 
	{
		$id                 = $this->uri->segment('3');
		$data['order_list'] = $this->Orders_model->orders_list($id);
        $html               = $this->load->view('order_details_pdf', $data, true);
        $this->pdf->createPDF($html, 'order_details', false);
	}
    //Orders PDF View
	public function addon_order_pdf() 
	{
		$id                  = $this->uri->segment('3');
		$data['order_list']  = $this->Orders_model->addon_orders_list($id);
        $html                = $this->load->view('addon_order_details_pdf', $data, true);
        $this->pdf->createPDF($html, 'order_details', false);
	}
    //Add Record View
    public function add() 
    {
        $data = array();

        $data['title']='Add New Order';

        $data['login_id']=$this->session->userdata['logged_in']['id'];
        $data['department_id']=$this->session->userdata['logged_in']['department_id'];
        $role_id=$this->session->userdata['logged_in']['role_id'];

        $data['users']=$this->Orders_model->getUsers();
       
        $data['current']=$this->Orders_model->orders_list();
        $data['subscription']=$this->Orders_model->getSubscription();
        $data['addonservices']=$this->Orders_model->getAddonServices();
        $data['price']=$this->Orders_model->getPrice();
       
        $this->template->load('template','orders_add', $data);        
	}
    //Add Record View
    public function add_addon_order() 
    {
        $data = array();
        $data['title']='Add New Addon Order';
        $data['users']=$this->Orders_model->getUsers();
        $data['addonservices']=$this->Orders_model->getAddonServices();
        $data['price']=$this->Orders_model->getPrice();
        $this->template->load('template','add_addon_order', $data);        
	}
    //Add Record to Database
    public function add_addon_service_record() 
    {
		$login_id = $this->session->userdata['logged_in']['id'];
		$data = array(
			'order_date' => date('Y-m-d',strtotime($this->input->post('order_date'))),
			'user_id' => $this->input->post('user_id'),
			'addon_service_id' => $this->input->post('addon_service_id'),
			'price' => $this->input->post('plan_price'),
			'qty' => $this->input->post('qty'),
			'grand_total' => $this->input->post('plan_total'),
			'payment_terms' => $this->input->post('payment_terms'),
			'payment_status' => $this->input->post('payment_status'),
			'created_by' => $login_id
		);
		
		$result = $this->Orders_model->addon_order_insert($data);

		if ($result == TRUE) {
			$this->session->set_flashdata('success', 'Addon Order Added Successfully  !');	
			redirect('Orders_panel/addon_index', 'refresh');
		} else {
			$this->session->set_flashdata('failed', 'Insertion Failed, Addon Order Already exists !');
			redirect('Orders_panel/addon_index', 'refresh');
		}
   }
    //Add Record to Database
    public function add_new_record() 
    {
        $user_id=$this->input->post('user_id');
		$subscription_plan_id=$this->input->post('subscription_plan_id');
        $amount=$this->input->post('amount');

        $this->form_validation->set_rules('order_id', 'Order ID', 'required');
       
       if ($this->form_validation->run() == FALSE) 
       {
           $this->template->load('template','orders_add');   
       } 
       else 
       {	
		   if(empty($this->input->post('grand_total'))) {
				$grand_total = $this->input->post('plan_total');
	   		} else {
				$grand_total = $this->input->post('grand_total');
			}
           $data = array(
            'order_date' => date('Y-m-d',strtotime($this->input->post('order_date'))),
            'order_id' => $this->input->post('order_id'),  
            'user_id' => $this->input->post('user_id'),  
            'subscription_plan_id' => $this->input->post('subscription_plan_id'),
            'plan_price' => $this->input->post('plan_price'),
            'amount' => $this->input->post('plan_total'),
            'grand_total' => $grand_total,
            'payment_terms' => $this->input->post('payment_terms'),  
            'payment_status' => $this->input->post('payment_status'),  
            'plan_status' => $this->input->post('plan_status'),  
            'created_by' => $this->session->userdata['logged_in']['id'],
           );
		   
           $result = $this->Orders_model->orders_insert($data);

           if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Order Added Successfully  !');	
                redirect('Orders_panel/index', 'refresh');
           } else {
                $this->session->set_flashdata('failed', 'Insertion Failed, Order Already exists !');
                redirect('Orders_panel/index', 'refresh');
           }
       }
   }
   //Delete Record to Database
   public function delete_addon_order($id= null){
		$id = $this->uri->segment('3');
		$this->db->where('id', $id);
		$this->db->delete('order_addons');
		$this->session->set_flashdata('success', 'Order deleted Successfully !');
		redirect('/Orders_panel/addon_index', 'refresh');
   }
    //Delete Record to Database
    public function deleteRecord($id= null){

        $ids=$this->input->post('ids');

        if(!empty($ids)) {
            $Datas=explode(',', $ids);
            foreach ($Datas as $key => $id) {
                $this->Orders_model->deleteRecord($id);

            }
            echo $this->session->set_flashdata('success', 'Order deleted Successfully !');
        } else {
            $id = $this->uri->segment('3');
            $this->Orders_model->deleteRecord($id);
            $this->session->set_flashdata('success', 'Order deleted Successfully !');
            redirect('/Orders_panel/index', 'refresh');
        }
    }

    //Edit View
    public function edit_addon_order($id) { 
		$id                    = $this->uri->segment('3');
        $data['title']         = 'Edit Addon Order';
		$query                 = $this->db->get_where("order_addons",array("id"=>$id));
        $data['current']       = $query->row();
        $data['id']            = $id;
        $data['users']         = $this->Orders_model->getUsers();
        $data['addonservices'] = $this->Orders_model->getAddonServices();
        $data['price']         = $this->Orders_model->getPrice();
        $this->template->load('template','edit_addon_order',$data);
	}
    //Edit View
    public function edit($id) { 
		$id                    = $this->uri->segment('3');
        $data['title']         = 'Edit Order';
		$data['current']       = $this->Orders_model->orders_list_for_edit($id);
        $data['id']            = $id;
        $data['users']         = $this->Orders_model->getUsers();           // For Select User for Place Order
        $data['subscription']  = $this->Orders_model->getSubscription();    // For Subscription Drop-down
        $data['price']         = $this->Orders_model->getPrice();           
        $data['addonservices'] = $this->Orders_model->getAddonServices();   // For Addon Services Drop-down
		//echo"<pre>";print_r($data);exit;
        $this->template->load('template','orders_edit',$data);
	}

    //Edit Record to Database
    public function editOrders($id)
    {   
        $data['id'] = $id;
		$this->form_validation->set_rules('order_id', 'Order ID', 'required');
        $user_id=$this->input->post('user_id');
		$login_id=$this->session->userdata['logged_in']['id'];
		if(empty($this->input->post('grand_total'))) {
			$grand_total = $this->input->post('plan_total');
		   } else {
			$grand_total = $this->input->post('grand_total');
		}
		$data = array();
		if(!empty($this->input->post('order_date'))) {
			$data['order_date'] = date('Y-m-d',strtotime($this->input->post('order_date')));
		}
		if(!empty($this->input->post('order_id'))) {
			$data['order_id'] = $this->input->post('order_id');
		}
		if(!empty($this->input->post('user_id'))) {
			$data['user_id'] = $this->input->post('user_id');
		}
		if(!empty($this->input->post('plan_price'))) {
			$data['plan_price'] = $this->input->post('plan_price');
		}
		if(!empty($this->input->post('plan_total'))) {
			$data['amount'] = $this->input->post('plan_total');
		}
		if(!empty($grand_total)) {
			$data['grand_total'] = $this->input->post('grand_total');
		}
		if(!empty($this->input->post('payment_terms'))) {
			$data['payment_terms'] = $this->input->post('payment_terms');
		}
		if(!empty($this->input->post('payment_status'))) {
			$data['payment_status'] = $this->input->post('payment_status');
		}
		if(!empty($this->input->post('plan_status'))) {
			$data['plan_status'] = $this->input->post('plan_status');
		}
		$data['edited_by'] = $this->session->userdata['logged_in']['id'];

		$result = $this->Orders_model->editRecord($data,$id);
		
		if ($result == TRUE) {
			$this->session->set_flashdata('success', 'Order Updated Successfully !');
			redirect('Orders_panel/index','refresh');
		}else {
			$this->session->set_flashdata('failed', 'Order Updating Failed !');
			redirect('Orders_panel/index','refresh');
		}
	}

    //Edit Record to Database
    public function update_addon_service_record($id)
    {
		$data = array();
		if(!empty($this->input->post('order_date'))) {
			$data['order_date'] = date('Y-m-d',strtotime($this->input->post('order_date')));
		}
		if(!empty($this->input->post('user_id'))) {
			$data['user_id'] = $this->input->post('user_id');
		}
		if(!empty($this->input->post('addon_service_id'))) {
			$data['addon_service_id'] = $this->input->post('addon_service_id');
		}
		if(!empty($this->input->post('plan_price'))) {
			$data['price'] = $this->input->post('plan_price');
		}
		if(!empty($this->input->post('qty'))) {
			$data['qty'] = $this->input->post('qty');
		}
		if(!empty($this->input->post('plan_total'))) {
			$data['grand_total'] = $this->input->post('plan_total');
		}
		if(!empty($this->input->post('payment_terms'))) {
			$data['payment_terms'] = $this->input->post('payment_terms');
		}
		if(!empty($this->input->post('payment_status'))) {
			$data['payment_status'] = $this->input->post('payment_status');
		}
		$data['edited_by'] = $this->session->userdata['logged_in']['id'];

		$result = $this->Orders_model->edit_addon_record($data,$id);
		
		if ($result == TRUE) {
			$this->session->set_flashdata('success', 'Order Updated Successfully !');
			redirect('Orders_panel/addon_index','refresh');
		}else {
			$this->session->set_flashdata('failed', 'Order Updating Failed !');
			redirect('Orders_panel/addon_index','refresh');
		}
	}


    function getPrice($id) { 
        $result = $this->db->select('price')->from('subscription')->where(['flag'=>'0','id'=>$id])->get()->row_array(); 
        return $result; 
    }
    
// Add To Cart Methods Below ----------------------------------------------------------------------------------------------------------- 

    //Cart Add View
    public function cart_add() 
    {
        $data = array();

        $data['title']='Add to Cart';

        $data['login_id']=$this->session->userdata['logged_in']['id'];
        $data['department_id']=$this->session->userdata['logged_in']['department_id'];
        $role_id=$this->session->userdata['logged_in']['role_id'];

        $data['users']=$this->Orders_model->getUsers();
       
        $data['current']=$this->Orders_model->cart_list();
        $data['subscription']=$this->Orders_model->getSubscription();
        $data['price']=$this->Orders_model->getPrice();
       
        $this->template->load('template','cart_add', $data);        
	}

    //Cart View
	public function cart_list() 
	{
        $data['title'] = 'Cart List';
		
		$data['cart'] = $this->Orders_model->cart_list();

		$this->template->load('template','cart_list',$data);       
	}

    //Cart Edit View
    public function cart_edit($id) { 

		$id = $this->uri->segment('3');

        $data['title']='Edit Cart Order';

		$query = $this->db->get_where("add_to_cart",array("id"=>$id));
        $data['current'] = $query->row();
       	//print_r($data['current'][0]->vendor_code);exit;
        
        $data['id'] = $id;

        $data['users']=$this->Orders_model->getUsers();
        $data['subscription']=$this->Orders_model->getSubscription();
        $data['price']=$this->Orders_model->getPrice();

        $this->template->load('template','cart_edit',$data);
	}
    
    //Add Record to Database
    public function add_to_cart_insert() 
    {
        $user_id=$this->input->post('user_id');
		$subscription_plan_id=$this->input->post('subscription_plan_id');
        $amount=$this->input->post('amount');

        $this->form_validation->set_rules('cart_id', 'Cart ID', 'required');
       
       if ($this->form_validation->run() == FALSE) 
       {
           $this->template->load('template','cart_add');   
       } 
       else 
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
                'created_by' => $this->session->userdata['logged_in']['id'],
                // 'edited_by' => $this->session->userdata['logged_in']['id'],
           );
           
           $result = $this->Orders_model->cart_insert($data);

           if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Order Added to Cart Successfully !');	
                redirect('Orders_panel/cart_list', 'refresh');
           } else {
                $this->session->set_flashdata('failed', 'Insertion Failed, Order Already Exists in Cart !');
                redirect('Orders_panel/cart_list', 'refresh');
           }
       }
   }

   //Edit Record to Database
   public function editCartOrders($id)
   {   
       $data['id'] = $id;

       $this->form_validation->set_rules('cart_id', 'Cart ID', 'required');
       
       $user_id=$this->input->post('user_id');
       $subscription_plan_id=$this->input->post('subscription_plan_id');
       $amount=$this->input->post('amount');

       if ($this->form_validation->run() == FALSE) 
       {
           if(isset($this->session->userdata['logged_in'])){
               $this->edit($id);
           }else{
               $this->load->view('login_form');
           }
       } 
       else 
       {
           $login_id=$this->session->userdata['logged_in']['id'];

           $data = array(
               'date' => date('Y-m-d',strtotime($this->input->post('date'))),
               'cart_id' => $this->input->post('cart_id'),  
               'user_id' => $this->input->post('user_id'),  
               'subscription_plan_id' => $this->input->post('subscription_plan_id'),
               'amount' => $this->input->post('amount'),
               'other_tax' => $this->input->post('other_tax'),  
               'grand_total' => $this->input->post('amount') + $this->input->post('other_tax'),
               // 'grand_total' => $this->input->post('grand_total'),  
               'edited_by' => $this->session->userdata['logged_in']['id']
           );

           $result = $this->Orders_model->editCartOrders($data,$id);
           //echo $result;exit;
           if ($result == TRUE) {
               $this->session->set_flashdata('success', 'Cart Order Updated Successfully !');
               redirect('Orders_panel/cart_list','refresh');
           }else {
               $this->session->set_flashdata('failed', 'Cart Order Updating Failed !');
               redirect('Orders_panel/cart_list','refresh');
           }
       }
   }

    //Delete Record to Database
    public function cartDelete($id= null){

        $ids=$this->input->post('ids');

        if(!empty($ids)) {
            $Datas=explode(',', $ids);
            foreach ($Datas as $key => $id) {
                $this->Orders_model->cartDelete($id);
            }
            echo $this->session->set_flashdata('success', 'Cart Order Deleted Successfully !');
        }else{
            $id = $this->uri->segment('3');
            $this->Orders_model->cartDelete($id);
            $this->session->set_flashdata('success', 'Cart Order Deleted Successfully !');
            redirect('/Orders_panel/cart_list', 'refresh');
        }
    }

}

?>
