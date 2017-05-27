<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Tables_model');
	}

	public function index(){
		$data['data']['create_message'] = $this -> Tables_model -> create();
		$data['data']['alter_message']  = $this -> Tables_model -> alter();
		$data['data']['files_message']  = $this -> Tables_model -> files();
		$data['data']['table_name']  = $this -> Tables_model -> table_name();
		$data['data']['show_tables'] = $this -> Tables_model -> show_tables();
		$data['yield_data'] = $this->load->view('mysql/index',$data);
	    $this->load->view('layout/adminlte/index',$data);	
	}

}
