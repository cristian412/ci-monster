<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Request_model');
		$this->load->model('Tables_model');
		$this->load->library('session');
	}


	public function index(){

		$data['login_url'] = $this->googleplus->loginURL();


			
		//$this->load->view('welcome_message',$data);

		$this->load->view('layout/adminlte/sign_in',$data);
	}
	public function sign_in(){
		//$data['login_url'] = $this->googleplus->loginURL();
		$data['login_url'] = '';
		$data['message'] = '';
		$this->load->view('layout/adminlte/sign_in',$data);

	}
	public function sign_up(){
		$this->load->view('layout/adminlte/sign_up');
	}
	public function sign_out(){
		$this->session->sess_destroy();
		//$this->googleplus->revokeToken();
		redirect( URL.'users/sign_in' );
	}

	public function check(){

		if( $this->input->post('user') ){
			$usuario = $this->input->post('user');
			$pass = $this->input->post('pass');
			$q = "SELECT * from users where username='$usuario' and password='$pass'";
			$r = $this->Request_model->peticion($q);
			if( count($r)>0 ){
				foreach ($r[0] as $key => $value)
					$this->session->set_userdata($key, $value);
				
				$tables = $this -> Tables_model -> table_name();
				$this->session->set_userdata('tables', $tables);

				$this->session->set_userdata('login',true);
				
				redirect( URL );
			}else{
				$data['message'] = 'ERROR USER OR PASSWORD WRON';
				$this->load->view('layout/adminlte/sign_in',$data);
			}
		}

		if (isset($_GET['code'])) {
			$this->googleplus->getAuthenticate();
			$this->session->set_userdata('user_profile',$this->googleplus->getUserInfo());
			$email = $_SESSION['user_profile']['email'];

			$q = "SELECT * from users where email='{$email}'";
			$r = $this->Request_model->peticion($q);
			if( count($r)>0 ){
				foreach ($r[0] as $key => $value)
					$this->session->set_userdata($key, $value);

				$this->session->set_userdata('login',true);
				$tables = $this -> Tables_model -> table_name();
				$this->session->set_userdata('tables', $tables);
				redirect( URL );
			}else{
				$data['message'] = 'ERROR EMAIL WRON';
				$this->load->view('layout/adminlte/sign_in',$data);
			}


		}	

	}
	public function check_pru(){
		$usuario = $this->input->post('user');
		$pass = $this->input->post('pass');
		$q = "SELECT * from users where username='$usuario' and password='$pass'";
		echo $q;
	}

	// INICIA GOOGLE PLUS

	public function profile(){
		
		if($this->session->userdata('login') != true){
			redirect('');
		}
		
		$contents['user_profile'] = $this->session->userdata('user_profile');
		$this->load->view('profile',$contents);
		
	}
	
}
