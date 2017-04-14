<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	public function tables($orden){
		$q = $this->db->query($orden)->result();
		$result = json_decode( json_encode( $q ), true );	
		return $result;			
	}

}
