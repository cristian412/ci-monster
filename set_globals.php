<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper('file');


if(!empty($_POST)){
	$re = '';

	# SETEAR VARIABLES GLOBALES
	echo "<pre>"; print_r($_POST); echo "</pre>";
	foreach ($_POST as $key => $value) $$key = $value;

	# CONFIG.PHP
	$var  = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/config_config.php");
	$a = array("URL_VAR", "APPNAME_VAR", "DATABASE_VAR","USER_VAR","MBARETE_VAR");
	$path = FCPATH."application/config/config.php";
	$data = file_get_contents($path);
	$b = array($url, $appname, $database, $user, $mbarete );
	$var = str_replace($a, $b, $var);
	$data = $data.$var;
	if( ! write_file($path, $data) ) $re.='ERROR WRITING config/config.php<br>'; 

	# DATABASE.PHP
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/config_database.php");
	$path   = FCPATH.'application/config/database.php';
	if( ! write_file($path, $data) ) $re.='ERROR WRITING config/database.php<br>'; 


	# AUTOLOAD.PHP
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/autoload.php");
	$path   = FCPATH.'application/config/autoload.php';
	if( ! write_file($path, $data) ) $re.='ERROR WRITING config/autoload.php<br>'; 

	# Peticiones_model
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/Request_model.php");
	$path   = FCPATH.'application/models/Request_model.php';
	if ( ! write_file($path, $data) ) $re.= 'ERROR WRITING models/Peticiones_model.php<br>';
	chmod($path, 0777);


	# Tables_model
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/Tables_model.php");
	$path   = FCPATH.'application/models/Tables_model.php';
	if( ! write_file($path, $data) )  $re.= 'ERROR WRITING models/Tables_model.php<br>';
	chmod($path, 0777);


	# showhtml_helper
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/showhtml_helper.php");
	$path   = FCPATH.'application/helpers/showhtml_helper.php';
	if( ! write_file($path, $data) ) $re.='ERROR WRITING helpers/showhtml_helper.php<br>';
	chmod($path, 0777);


	# HACEMOS UNA PETICION
	//$r = $this-> Request_model -> peticion("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."'");
	//echo "<pre>"; print_r($r); 		echo "</pre>";	


	# CARGAMOS EL PRIMER ARCHIVO EN LA VISTA, DESDE GITHUB
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/holamundo.html");
	$path   = FCPATH.'application/views/holamundo.html';
	write_file($path, $data);
	chmod($path, 0777);

	echo "<br>Respuesta: ".$re;

} // end IF !EMPTHY POST

//echo substr(sprintf('%o', fileperms($application)), -4);


$file = FCPATH."application/views/tables/users/list.php";
if(file_exists($file))


?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CI MONSTER</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cerulean/bootstrap.min.css">
	
</head>
<body>
	<div class="container">
		<h1>			CI-MONSTER 		</h1>
		<div class="row">
			<div class="col-lg-6 ">
				<form method="post" class="form-horizontal">
				  <fieldset>
					    <legend>Change Globals Data</legend>

					    <div class="form-group">
					      <label for="url" class="col-md-3">BASE URL</label>
					      <div class="col-md-9">
						      <input type="text" class="form-control" name="url" id="urlname" aria-describedby="urlname_help">
						      <small id="urlname_help" class="form-text text-muted">Ussually not need modify that.</small>
					      </div>
					    </div>

					    <div class="form-group">
					      <label for="appname"  class="col-md-3">APP NAME</label>
					      <div class="col-md-9">
						      <input type="text" class="form-control" name="appname"  aria-describedby="appname_help">
						      <small id="appname_help" class="form-text text-muted">The principal name used for the app.</small>
						  </div>
					    </div>

					    <div class="form-group">
					      <label for="database" class="col-md-3">DB NAME</label>
   					      <div class="col-md-9">
						      <input type="text" class="form-control" name="database"  aria-describedby="dbname_help">
						      <small id="dbname_help" class="form-text text-muted">Please include de pre-name like user_database.</small>
						    </div>
					    </div>

					    <div class="form-group">
					      <label for="user" class="col-md-3">USER NAME</label>
					      <div class="col-md-9">
						      <input type="text" class="form-control" name="user"  aria-describedby="user_help">
						      <small id="user_help" class="form-text text-muted">The username of the database.</small>
						    </div>
					    </div>

					    <div class="form-group">
					      <label for="mbarete" class="col-md-3">PASSWORD</label>
					      <div class="col-md-9">
						      <input type="password" class="form-control" name="mbarete"  aria-describedby="mbarete_help">
						      <small id="mbarete_help" class="form-text text-muted">The user's password.</small>
						    </div>
					    </div>

					    <div class="form-group">
					      <label class="col-md-3" >REPASSWORD</label>
					      <div class="col-md-9">
						      <input type="password" class="form-control"   aria-describedby="re_help">
						      <small id="re_help" class="form-text text-muted">Please re password.</small>
						    </div>
					    </div>

					    <button type="submit" class="btn btn-primary" name="beginnow">Submit</button>
				    </fieldset>
				</form>
			</div>
		</div>


	</div>

<script>
	var currentLocation = window.location;
	document.getElementById("urlname").value = currentLocation;

</script>

</body>
</html>
