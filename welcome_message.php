<?php
$data = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/set_globals.php");
$path   = FCPATH.'application/views/welcome_message.php';
$re = '';
if( ! write_file($path, $data) ) 
	$re.=   '<div class="alert alert-dismissible alert-danger">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>ERROR WRITING </strong> <code> config/database.php </code> 
			  <p> type in your terminal: <code> sudo chmod -R 0777 ./application </code>  in your project folder.</p>
			</div><br>'; 
?>
<!DOCTYPE html>
<html>
<head>
	<title>CI MONSTER</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<br>
		<?=$re?>
		<div class="jumbotron">
		 <h1>CI MONSTER</h1>
		 	<p>powerfull backend, based in codeigniter framework </p>
		 	<p><a class="btn btn-primary btn-lg" href="<?=URL?>">Go!</a></p>
		</div>
	</div>
</body>
</html>
