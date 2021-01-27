<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use RestServer\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class RestServer extends REST_Controller {

	public function test_get(){
		$array = array('nombre' => 'Miguel Angel', 'apellido'=>'Bañolas');
		$this->response($array);
	}

	public function datos_emails_get(){
		$this->load->database();
		$this->response($this->db->query("SELECT * FROM pe_datos_email_tracking")->result_array());
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
}
