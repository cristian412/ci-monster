<?php

?>

<!DOCTYPE html>
<html lang="en">
<head> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cerulean/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>

	<title>Admin</title>
	<style type="text/css">
		body{
			background-image: url(http://jesuitasaru.org/wp-content/uploads/2017/06/medioambiente.jpg);
			background-size: cover;			
		}
	</style>

</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4"></div>
		</div>

		<div class="row center">
			<div class="col-md-6 col-sm-10">
				<div class="panel-heading">
					<div class="panel-title text-center">
						<h1 style="color: green"><?=APPNAME?></h1>
						<hr />
					</div>
				</div> 
				<div class="row">
					<?=$message?>
				</div>
				<div class="row">
					<a href="<?=$login_url;?>" class="btn btn-block btn-social btn-google"><span class="fa fa-google"></span> Iniciar Sesion con Google</a>
				</div>
				<div class="row">
					<br>
					<button  class="btn btn-block btn-social btn-reddit" id="user-access">
						<span class="fa fa-user"></span> Iniciar Sesion con Contraseña
					</button>
					<script type="text/javascript">
						$("#user-access").on('click', function(){
					    	$("#main-login").toggleClass('hidden'); 
						});
					</script>
				</div>
				<div class="main-login main-center hidden" id="main-login">
					<form class="form-horizontal" method="post" action="<?=URL?>users/check">
						<div class="form-group">
							<label for="email" class="cols-sm-2 control-label">Username</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="user" id="email"  placeholder="Enter your Username"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="cols-sm-2 control-label">Password</label>
							<div class="cols-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
								<input type="password" class="form-control" name="pass" id="password"  placeholder="Enter your Password"/>
							</div>
							</div>
						</div>
						<div class="form-group ">
							<button type="submit"  class="btn btn-primary btn-lg btn-block login-button">Sign In</button>
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
</body>
</html>
