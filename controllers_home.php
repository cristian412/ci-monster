<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
		$data['tables'] = $this -> Tables_model -> show_tables();

		$data['yield_data'] = $this->load->view('home/index',$data,true);

		$this->load->view('layout/adminlte/index',$data);
	}
}
