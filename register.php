<?php
session_start();

if(isset($_SESSION['usr_id'])) {
	header("Location: index.php");
}

include_once 'dbconnect.php';

//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['signup'])) {
	$fname = mysqli_real_escape_string($con, $_POST['firstname']);
	$lname = mysqli_real_escape_string($con, $_POST['lastname']);
	$uname = mysqli_real_escape_string($con, $_POST['username']);
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$password = mysqli_real_escape_string($con, $_POST['password']);
	$cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

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
	
	//name can contain only alpha characters and space
	if (!preg_match("/^[a-zA-Z ]+$/",$fname)) {
		$error = true;
		$name_error = "Name must contain only alphabets and space";
	}
	if (!preg_match("/^[a-zA-Z ]+$/",$lname)) {
		$error = true;
		$name_error = "Name must contain only alphabets and space";
	}
	if (!preg_match("/^[a-zA-Z_-]+$/",$uname)) {
		$error = true;
		$name_error = "Invalid Username: can only contain alphabets, numbers, -,or _";
	}
	if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
		$error = true;
		$email_error = "Please Enter Valid Email ID";
	}
	if(strlen($password) < 6) {
		$error = true;
		$password_error = "Password must be minimum of 6 characters";
	}
	if($password != $cpassword) {
		$error = true;
		$cpassword_error = "Password and Confirm Password doesn't match";
	}
	if (!$error) {
		if(mysqli_query($con, "INSERT INTO users(`firstname`, `lastname`, `admin`, `contributor`, `username`, `email`, `password`) VALUES('".$fname."', '".$lname."', '".$admin."', '".$contributor."', '".$uname."', '".$email."', '".md5($password)."')")) {
			$successmsg = "Successfully Registered! <a href='login.php'>Click here to Login</a>";
		} else {
			$errormsg = "Error in registering...Please try again later!";
			echo mysqli_error($con);
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign UP | CS147 Project</title>
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
					<li><a href="login.php">Login</a></li>
					<li class="active"><a href="register.php">Sign Up</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 well">
				<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
					<fieldset>
						<legend>Sign Up</legend>

						<div class="form-group">
							<label for="name">First Name</label>
							<input type="text" name="firstname" placeholder="First Name" required value="<?php if($error) echo $fname; ?>" class="form-control" />
							<span class="text-danger"><?php if (isset($name_error)) echo $name_error; ?></span>
							<label for="name">Last Name</label>
							<input type="text" name="lastname" placeholder="Last Name" required value="<?php if($error) echo $lname; ?>" class="form-control" />
							<span class="text-danger"><?php if (isset($name_error)) echo $name_error; ?></span>
							
						</div>

						<div class="form-group">
							<label for="name">Username</label>
							<input type="text" name="username" placeholder="username" required value="<?php if($error) echo $lname; ?>" class="form-control" />
							<span class="text-danger"><?php if (isset($name_error)) echo $name_error; ?></span>
						</div>
						
						<div class="form-group">
							<label for="name">Email</label>
							<input type="text" name="email" placeholder="Email" required value="<?php if($error) echo $email; ?>" class="form-control" />
							<span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
						</div>

						<div class="form-group">
							<label for="name">Password</label>
							<input type="password" name="password" placeholder="Password" required class="form-control" />
							<span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
						</div>

						<div class="form-group">
							<label for="name">Confirm Password</label>
							<input type="password" name="cpassword" placeholder="Confirm Password" required class="form-control" />
							<span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
						</div>
						<div>
							<label class="checkbox-inline"><input type="checkbox" name="admin" value="admin">Admin</label>
							
							<label class="checkbox-inline"><input type="checkbox" name="contributor" value="contributor">Contributor</label>
						</div>


						<div class="form-group">
							<input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
						</div>
					</fieldset>
				</form>
				<span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
				<span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-md-offset-4 text-center">	
				Already Registered? <a href="login.php">Login Here</a>
			</div>
		</div>
	</div>
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>



