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

	# Tables_model
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/Tables_model.php");
	$path   = FCPATH.'application/models/Tables_model.php';
	if( ! write_file($path, $data) )  $re.= 'ERROR WRITING models/Tables_model.php<br>';

	# showhtml_helper
	$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/showhtml_helper.php");
	$path   = FCPATH.'application/helpers/showhtml_helper.php';
	if( ! write_file($path, $data) ) $re.='ERROR WRITING helpers/showhtml_helper.php<br>';


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
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #f07746; color: #fff; }
	::-moz-selection { background-color: #f07746; color: #fff; }

	body {
		background-color: #fff;
		margin: 40px auto;
		max-width: 1024px;
		font: 16px/24px normal "Helvetica Neue",Helvetica,Arial,sans-serif;
		color: #808080;
	}

	a {
		color: #dd4814;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
	   color: #97310e;
	}

	h1 {
		color: #fff;
		background-color: #dd4814;
		border-bottom: 1px solid #d0d0d0;
		font-size: 22px;
		font-weight: bold;
		margin: 0 0 14px 0;
		padding: 5px 10px;
		line-height: 40px;
	}

	h1 img {
		display: block;
	}

	h2 {
		color:#404040;
		margin:0;
		padding:0 0 10px 0;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 13px;
		background-color: #f5f5f5;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		 margin: 0 0 10px;
		 padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 12px;
		border-top: 1px solid #d0d0d0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
		background:#8ba8af;
		color:#fff;
	}

	#container {
		margin: 10px;
		border: 1px solid #d0d0d0;
		box-shadow: 0 0 8px #d0d0d0;
		border-radius: 4px;
	}
	</style>
</head>
<body>
	<div id="container">
		<h1>
			CI-MONSTER
		</h1>

		<div id="body">
			<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

			<p>If you would like to edit this page you'll find it located at:</p>
			<code>application/views/welcome_message.php</code>

			<p>The corresponding controller for this page is found at:</p>
			<code>application/controllers/Welcome.php</code>

			<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
		</div>

		<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
	</div>

<form method="post" style="font-family: courier;">
	BASEURL &nbsp;<input type="text" name="url" id=	"urlname" style="width:400px"><br><br>
	APPNAME &nbsp;<input type="text" name="appname" required="required"><br><br>
	DB-NAME &nbsp;<input type="text" name="database" required="required"><br><br>
	USERNAME&nbsp;<input type="text" name="user" required="required"><br><br>
	PASSWORD&nbsp;<input type="text" name="mbarete"><br><br>
	BEGINNOW&nbsp;<input type="submit" name="beginnow"><br><br>
</form>
<script>
	var currentLocation = window.location;
	document.getElementById("urlname").value = currentLocation;

</script>

</body>
</html>
