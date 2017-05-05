<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Request_model');
		$this->load->library('session');
	}


	public function index(){
		$this->load->view('users/sign_in');
	}
	public function sign_in(){
		$data['message'] = '';
		$this->load->view('users/sign_in',$data);
	}
	public function sign_up(){
		$this->load->view('users/sign_up');
	}
	public function sign_out(){
		$this->session->sess_destroy();
		redirect( URL );
	}

	public function check(){
		$usuario = $this->input->post('user');
		$pass = $this->input->post('pass');
		$q = "SELECT * from users where username='$usuario' and password='$pass'";
		$r = $this->Request_model->peticion($q);
		if( count($r)>0 ){
			foreach ($r[0] as $key => $value) {
				$this->session->set_userdata($key, $value);
			}
			redirect( URL );
		}else{
			$data['message'] = 'ERROR USER OR PASSWORD WRON';
			$this->load->view('users/sign_in',$data);
		}
	}
	public function check_pru(){
		$usuario = $this->input->post('user');
		$pass = $this->input->post('pass');
		$q = "SELECT * from users where username='$usuario' and password='$pass'";
		echo $q;
	}
}
