<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){

		$data['yield_data'] = $this->load->view('home/index','',true);

		$this->load->view('layout/adminlte/index',$data);
	}
}
