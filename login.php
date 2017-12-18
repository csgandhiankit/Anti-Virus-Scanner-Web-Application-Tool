<?php
session_start();

if(isset($_SESSION['usr_id'])!="") {
	header("Location: loginController.php");
}

include_once 'dbconnect.php';

//check if form is submitted
if (isset($_POST['login'])) {

	$uname = mysqli_real_escape_string($con, $_POST['username']);
	$password = mysqli_real_escape_string($con, $_POST['password']);


	$contributor = 0;
	if(isset($_POST['contributor'])){
	$contributor = mysqli_real_escape_string($con, $_POST['contributor']);
	}
	if($contributor === 'contributor'){
		$contributor = 1;
	}else{
		$contributor = 0;
	}


	$admin = 0;
	if(isset($_POST['admin'])){
	$admin = mysqli_real_escape_string($con, $_POST['admin']);
	}
	if($admin === 'admin'){
		$admin = 1;
	}else{
		$admin = 0;
	}
	$result = mysqli_query($con, "SELECT * FROM users WHERE username = '" . $uname. "' and password = '" . md5($password) . "' and admin=".$admin." and contributor=".$contributor);

	if ($row = mysqli_fetch_array($result)) {
		$_SESSION['usr_id'] = $row['id'];
		$_SESSION['usr_name'] = $row['firstname'];
		$_SESSION['admin'] = $row['admin'];
		$_SESSION['contributor'] = $row['contributor'];
		header("Location: loginController.php");
	} else {
		$errormsg = "Incorrect Email or Password!!!";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login | CS147 Project</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" >
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
</head>
<body>

<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<!-- add header -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">Anti-Virus Scanner</a>
		</div>
		<!-- menu items -->
		<div class="collapse navbar-collapse" id="navbar1">
			<ul class="nav navbar-nav navbar-right">
				<li class="active"><a href="login.php">Login</a></li>
				<li><a href="register.php">Sign Up</a></li>
			</ul>
		</div>
	</div>
</nav>

<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 well">
			<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
				<fieldset>
					<legend>Login</legend>
					
					<div class="form-group">
						<label for="name">Username</label>
						<input type="text" name="username" placeholder="Username" required class="form-control" />
					</div>

					<div class="form-group">
						<label for="name">Password</label>
						<input type="password" name="password" placeholder="Your Password" required class="form-control" />
					</div>
					<div>
					<label class="checkbox-inline"><input type="checkbox" name="admin" value="admin">Admin</label>
					<label class="checkbox-inline"><input type="checkbox" name="contributor" value="contributor">Contributor</label>
					</div>

					<div class="form-group">
						<input type="submit" name="login" value="Login" class="btn btn-primary" />
					</div>
				</fieldset>
			</form>
			<span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-md-offset-4 text-center">	
		New User? <a href="register.php">Sign Up Here</a>
		</div>
	</div>
</div>

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
