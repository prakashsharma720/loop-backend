<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_api extends CI_Controller {

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
		$this->load->library('form_validation');
	}
	// Get All Notifications
	function index()
	{
		$this->db->select('notifications.*, users.name as user_name');
		$this->db->from('notifications'); 
		$this->db->join('users', 'notifications.employee_id = users.id','left');
        if(!empty($this->input->post('user_id'))){
            $this->db->where('notifications.employee_id', $this->input->post('user_id'));
        }
		$query = $this->db->get()->result_array();
		if(!empty($query)) {
			foreach($query as $result) {
				$data[] = array(
					'user_id'   => $result['employee_id'],
					'user_name' => $result['user_name'],
					'type'      => $result['type'],
					'subject'   => $result['subject'],
					'message'   => $result['message'],
					'datetime'  => $result['datetime']
				);
			}
			$array = array(
				'success' => true,
				'Message' => 'Notifications!',
				'result' => $data
			);
		} else {
			$array = array(
				'success' => false,
				'Message' => 'No Notifications Found!',
			);
		}
		echo json_encode($array);
	}
}// Main Class Closed
?>
