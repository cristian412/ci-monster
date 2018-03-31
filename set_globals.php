<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper('file');

function menu($step){

	$s2 = $s3 = $s4 = $s5 = $s6 = ' text-muted ';

	if($step==2) $s2 = ' text-primary ';
	if($step==3) $s2 = $s3 = ' text-primary ';
	if($step==4) $s2 = $s3 = $s4 = ' text-primary ';
	if($step==5) $s2 = $s3 = $s4 = $s5 = ' text-primary ';
	if($step==6) $s2 = $s3 = $s4 = $s5 = $s6 = ' text-primary ';

	if($step==2) $pb = 25;
	if($step==3) $pb = 45;
	if($step==4) $pb = 60;
	if($step==5) $pb = 75;
	if($step==6) $pb = 100;

	return $menu = ' 
			<div class="row">
				<div class="col-md-2"><span class="text-primary">Init</span></div>
				<div class="col-md-2"><span class="'.$s2.'">Globals</span></div>
				<div class="col-md-2"><span class="'.$s3.'">Connection</span></div>
				<div class="col-md-2"><span class="'.$s4.'">Users</span></div>
				<div class="col-md-2"><span class="'.$s5.'">Layout</span></div>
				<div class="col-md-2"><span class="'.$s6.'">Sign In</span></div>
			</div>
			<div class="progress progress-striped active">
			  <div class="progress-bar progress-bar-info" style="width: '.$pb.'%"></div>
			</div>
	';
}

$menu = menu(2);

$form_hidden = '';
$connection_hidden = ' hidden ';
$users_hidden = ' hidden ';
$layout_hidden = ' hidden ';
$signin_hidden = ' hidden ';
$connection_res = '  ';
$re = '';

if(!empty($_POST)){

	
	# SETEAR VARIABLES GLOBALES
	foreach ($_POST as $key => $value) $$key = $value;

	# LAYOUT
	if(isset($layout)):
		$menu = menu(6);
		$form_hidden = ' hidden ';
		$signin_hidden = '';

		######## CREATE FOLDERS #########

		// create folder layout
		$path   = FCPATH.'application/views/layout';
		if(!is_dir($path)){ 
			mkdir($path, 0777, true);
			chmod($path, 0777);
		}
		// create folder layout/adminlte
		$path   = FCPATH.'application/views/layout/adminlte';
		if(!is_dir($path)){ 
			mkdir($path, 0777, true);
			chmod($path, 0777);
		}
		// create folder HOME
		$path   = FCPATH.'application/views/home';
		if(!is_dir($path)){ 
			mkdir($path, 0777, true);
			chmod($path, 0777);
		}
		// create folder MYSQL
		$path   = FCPATH.'application/controllers/mysql';
		if(!is_dir($path)){ 
			mkdir($path, 0777, true);
			chmod($path, 0777);
		}
		// create folder MYSQL
		$path   = FCPATH.'application/views/mysql';
		if(!is_dir($path)){ 
			mkdir($path, 0777, true);
			chmod($path, 0777);
		}

		######## LOAD FILES #########
		// HOME CONTROLLER
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/controllers_home.php");
		$path   = FCPATH.'application/controllers/Home.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);

		// HOME VIEW
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/views_home_index.php");
		$path   = FCPATH.'application/views/home/index.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);

		// Layout Admin LTE
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/layout_adminlte.php");
		$path   = FCPATH.'application/views/layout/adminlte/index.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING'.$path.'<br>';
		chmod($path, 0777);

		# .htaccess
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/.htaccess");
		$path   = FCPATH.'.htaccess';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING htaccess'.$path.'<br>';
		chmod($path, 0777);
	
		// CONFIG GOOGLE PLUS
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/config_googleplus.php");
		$path   = FCPATH.'application/config/googleplus.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);
	
		// LIBRARIES GOOGLE PLUS
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/library_googleplus.php");
		$path   = FCPATH.'application/libraries/Googleplus.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);

		// THIRD_PARTY GOOGLE PLUS
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/google-login-api.zip");
		$path   = FCPATH.'application/third_party/google-login-api.zip';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);
	
		// HOME CONTROLLER
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/controllers_users.php");
		$path   = FCPATH.'application/controllers/Users.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);

		# views/users/sign_in.php
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/views_users_sign_in.php");
		$path   = FCPATH.'application/views/layout/adminlte/sign_in.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING htaccess'.$path.'<br>';
		chmod($path, 0777);
		# views/users/sign_up.php
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/views_users_sign_up.php");
		$path   = FCPATH.'application/views/layout/adminlte/sign_up.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING htaccess'.$path.'<br>';
		chmod($path, 0777);
		# views/users/sign_out.php
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/views_users_sign_out.php");
		$path   = FCPATH.'application/views/layout/adminlte/sign_out.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING htaccess'.$path.'<br>';
		chmod($path, 0777);
		# views_mysql
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/views_mysql_index.php");
		$path   = FCPATH.'application/views/mysql/index.php';
		if( ! write_file($path, $data) )  $re.= 'ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);
		# views_mysql
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/controllers_mysql_tables.php");
		$path   = FCPATH.'application/controllers/mysql/Tables.php';
		if( ! write_file($path, $data) )  $re.= 'ERROR WRITING '.$path.'<br>';
		chmod($path, 0777);

		# Finally change ROUTES
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/config_routes.php");
		$path   = FCPATH.'application/config/routes.php';
		if( ! write_file($path, $data) ) $re.='ERROR WRITING'.$path.'<br>';

	endif;
	# USERS
	if(isset($users)):
		$menu = menu(4);

		$form_hidden = ' hidden ';
		$connection_hidden = ' hidden ';

		$r = $this-> Request_model -> peticion("
			CREATE TABLE IF NOT EXISTS users 
			( id_users INT(10) PRIMARY KEY AUTO_INCREMENT, 
			username VARCHAR(255), 
			password VARCHAR(255),
			email VARCHAR(255)) ENGINE = InnoDB") ;

		$q = "INSERT INTO users(`id_users`,`username`,`password`,`email`)
			VALUES('1','admin','admin','cristianamarillacloss@gmail.com')";
        $r = $this-> Request_model -> peticion($q);

		if($r!=false){
			$menu = menu(5);
			$connection_res = "<h1>Successfully Created</h1>";
			$connection_hidden = ' hidden ';
			$users_hidden = ' hidden ';
			$layout_hidden = ' ';


		}else{
			$menu = menu(3);
			$connection_res = "<h1>Connection Error</h1>";
		}
	endif;

	# CONNECTIONS
	if(isset($connection)):
		$menu = menu(3);

		$form_hidden = ' hidden ';
		$connection_hidden = '';

		$r = $this-> Request_model -> peticion("SELECT count(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."'");
		if($r!=false){
			$menu = menu(4);
			$connection_res = "<h1>Connection Successfull</h1>";
			$connection_hidden = ' hidden ';
			$users_hidden = ' ';


		}else{
			$form_hidden = '';
			$connection_hidden = ' hidden ';

			$menu = menu(2);
			$connection_res = "<h1>Connection Error</h1>";
		}
	endif;

	# URL
	if(isset($url)):
		$menu = menu(3);

		$form_hidden = ' hidden ';
		$connection_hidden = '';

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
		$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/config_autoload.php");
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


		if($re!='')	$re.='<div class="alert alert-dismissible alert-danger">'.$re.'</div>';

	endif; // end if beginnow

} // end IF !EMPTHY POST

//echo substr(sprintf('%o', fileperms($application)), -4);


$file = FCPATH."application/views/tables/users/list.php";
if(file_exists($file))


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CI MONSTER</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cerulean/bootstrap.min.css">
	<style>
		.hidden {
			display: none;
		}
	</style>
</head>
<body>
	<div class="container">
		<br>
		<div class="row">
			<?=$re?>
			<?=$menu?>
		</div>

		<br>
		<div class="row">
			<div class="col-lg-6 <?=$signin_hidden?> " >
				<?php echo "<pre>"; print_r($_POST); echo "</pre>"; ?>
				<?=$re?>
				<a href="<?=URL?>" class="btn btn-info"> Sign In</a>
			</div>
			<div class="col-lg-6 <?=$layout_hidden?>" >
				<?php echo "<pre>"; print_r($_POST); echo "</pre>"; ?>

				<form method="post">
					<button type="submit" class="btn btn-success" name="layout" value="true">Create Layout</button>
				</form>
			</div>
			<div class="col-lg-6 <?=$connection_hidden?> " >
				<?php echo "<pre>"; print_r($_POST); echo "</pre>"; ?>

				<form method="post">
					<button type="submit" class="btn btn-success" name="connection" value="true">Try connection</button>
				</form>
			</div>
			<div class="col-lg-6 <?=$users_hidden?> " >
				<?=$connection_res?>
				<form method="post">
					<button type="submit" class="btn btn-success" name="users" value="true">Create Users Table</button>
				</form>
			</div>
			<div class="col-lg-6 <?=$form_hidden?>" >
				<?=$connection_res?>
				<form method="post" class="form-horizontal">
				  <fieldset>
					    <legend>Set Globals Data</legend>

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
