
<?php
session_start();
include_once 'dbconnect.php';
		$admin = $_SESSION['admin'];
		$contributor = $_SESSION['contributor'];
		$result = false;
		$fileUploadFailed = false;
		$fileSubmitted = false;
		$bytesToRead = 20;
		$duplicateError = false;
		$signatureInsertSuccess = false;
		$malwareFound = false;
		$invalidSignature = false;
		$requests = array();



		if (isset($_POST['sign'])) {
			$sign = $_POST['sign'];
			insertSignature($sign, $GLOBALS['con']);
		}

		if($admin == 1){
			getPendingSignatureRequests();
		}


		function getPendingSignatureRequests(){
		

		$q = "SELECT `signature` FROM usermalware";
					
					$result = $GLOBALS['con']->query($q);
				

					if ($result->num_rows > 0) {
				    // output data of each row
				    while($row = $result->fetch_assoc()) {
				        $sign =  $row['signature'];
				        array_push($GLOBALS['requests'], $sign);
				    }
				}
		}


		//takes in signature (20 bytes)
			function insertSignature($contents, $con){
				$validSignature = true;

				$strings = split(" ", $contents);

				foreach ($strings as $testcase) {
				    if (!preg_match("/^[a-zA-Z0-9]+$/",$testcase)) {
					continue;
					}else{
						$invalidSignature = false;
						break;
					}
				}
				if($validSignature){
					if(mysqli_query($con, "insert into malware(`signature`) value ('".$contents."')")){
						$GLOBALS['signatureInsertSuccess'] = true;
					}else{
						//echo mysqli_error($con);
						$GLOBALS['duplicateError'] = true;
					}
				}
				else{
					$GLOBALS['invalidSignature'] = true;
				}
				}


				function insertSignatureContributor($contents, $con){
				$validSignature = true;

				$strings = split(" ", $contents);
				foreach ($strings as $testcase) {
				    if (!preg_match("/^[a-zA-Z0-9]+$/",$testcase)) {
					continue;
					}else{
						$invalidSignature = false;
						break;
					}
				}	
				if($validSignature){
					if(mysqli_query($con, "insert into usermalware(`signature`) value ('".$contents."')")){
						$GLOBALS['signatureInsertSuccess'] = true;
					}else{
						//echo mysqli_error($con);
						$GLOBALS['duplicateError'] = true;
					}
				}
				else{
					$GLOBALS['invalidSignature'] = true;
				}
				}



				//takes in whole file and scans for signature
				function scanFile($fileContents, $con){
					
					//echo $fileContents;

					$q = "SELECT `signature` FROM malware";
					
					$result = $con->query($q);
				

					if ($result->num_rows > 0) {
				    // output data of each row
				    while($row = $result->fetch_assoc()) {
				        $sign =  $row['signature'];
				        if (strpos($fileContents, $sign) !== false) {
					    $GLOBALS['malwareFound'] = true;
					    $GLOBALS['fileSubmitted'] = true;
						}
				    }
				    $GLOBALS['fileSubmitted'] = true;
				     

				} else {
				    $GLOBALS['fileSubmitted'] = true;
				}

					
				}

			if($_FILES){

			        // Checking if file is selected or not
					if($_FILES['file']['name'] != "") {

			        // Checking if the file is plain text or not
						if($_FILES['file']['type'] != 'text/plain') {
							echo "<span>File could not be accepted ! Please upload any '*.txt' file.</span>";
							exit();
						}
						$filename = $_FILES['file']['name'];
						//echo "File uploaded: ".$filename."<p>";

			        	// storing the temporary file name of the uploaded file
						$fileName = $_FILES['file']['tmp_name'];

			        	// error message if the file could not be open
						$file = fopen($fileName,"r") or (exit("Unable to open file!") and $fileUploadFailed = true);

						$fileContents = "";
						if($file){
							        while(!feof($file))
							        {
							            $line = fgets($file);
							            $fileContents .= $line;
							        }
							    }

						//$handle = fopen($filename, "r");
						$contents = fread($file, $bytesToRead);
						//echo $contents;

						if($admin == 1){
							insertSignature(trim($contents), $con);
						}else if($contributor == 1){
							insertSignatureContributor(trim($contents), $con);
						}else{
							scanFile($fileContents, $con);

						}

							   
			        fclose($file); // close file
			        //$conn->close();
			    }
			    else {
			    	if(isset($_FILES) && $_FILES['file']['type'] == '')
			    		echo "<span>Please Choose a file by click on 'Choose File' button.</span>";
			    	}
				}	
				



			?>

<!DOCTYPE html>
<html>
<head>
	<title>Scanner | CS147 Project</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" >
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />

</head>
<body>

	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Anti-Virus File Scanner</a>
			</div>
			<div class="collapse navbar-collapse" id="navbar1">
				<ul class="nav navbar-nav navbar-right">
					<?php if (isset($_SESSION['usr_id'])) { ?>
					<li><p class="navbar-text">Welcome <?php echo $_SESSION['usr_name']; ?></p></li>
					<li><a href="logout.php">Log Out</a></li>
					<?php } else { ?>
					<li><a href="login.php">Login</a></li>
					<li><a href="register.php">Sign Up</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</nav>


	<div class="container">
		<div class="jumbotron">
			<h1>Scannner!!</h1> 
			<form action="" method="post" enctype="multipart/form-data">
				<?php if($admin == 1 || $contributor == 1){ ?>
				<p class="navbar-text">Hi <?php echo $_SESSION['usr_name']; ?>, Please upload an infected file!</p>
				<?php } else { ?>	
				<p class="navbar-text">Hi <?php echo $_SESSION['usr_name']; ?>, Please select a file to scan for malware or virus!</p>
				<?php } ?>
				<b>Select file to upload (*.txt): </b>
				<input type="file" name="file" size="70" /><input type="submit" value="Submit" />
			</form>

			<?php if($result){ ?>
			<div class="alert alert-success" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">...</a>
			</div>
			<?php } 
			if($fileUploadFailed) { ?>
			<div class="alert alert-danger" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">File upload failed! Please try again later.</a>
			</div>
			<?php } ?>

			<!-- <?php if(!$fileUploadFailed && $fileSubmitted) { ?>
			<div class="alert alert-success" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">File Upload Successful.</a>
			</div>
			<?php } ?> -->

			<?php if($signatureInsertSuccess) { ?>
			<div class="alert alert-success" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">Malware Signature successfully added to database!</a>
			</div>
			<?php } ?>


			<?php if($duplicateError) { ?>
			<div class="alert alert-danger" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">Signature already added to Database!</a>
			</div>
			<?php } ?>


			<?php if($malwareFound && $fileSubmitted){ ?>
			<div class="alert alert-danger" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">File infected with malware!</a>
			</div>
			<?php } ?>


			<?php if($invalidSignature){ ?>
			<div class="alert alert-danger" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">Signature contains invalid characters! Cannot add to database.</a>
			</div>
			<?php } ?>

			<?php if(!$malwareFound && $fileSubmitted){ ?>
			<div class="alert alert-success" role="alert" style="margin-top: 19px;">
  			<a href="#" class="alert-link">No Malware Found in the file!</a>
			</div>
			<?php } ?>

		</div>

		<!--Table displayign all putative signatures from contibutors-->
		<?php if(!empty($requests)) {
			echo "Pending Signature Approval";
		 	echo "<table class='table table-striped table-dark'>
		  <thead>
		    <tr>
		      <th scope='col'>#</th>
		      <th scope='col'>Signature</th>
		    </tr>
		  </thead>
		  <tbody>";
		  $count = 1;
		  foreach ($requests as $value) {
		    echo "<tr>";
		      echo "<th scope='row'>".$count."</th>";
		      echo "<td>".$value;
		      echo "<div style='float: right;'>";
		      echo "<button type='submit' class='approve-btn btn alert-success' style='margin-right: 10px;' name='approve' id='".$value."' value='".$value."'>Approve</button>";
		  	  echo "</div>";
		     echo "</td>";
		    echo "</tr>";
		    $count += 1;
		} 
    
		echo "</tbody>";
		echo "</table>";
	}
	?>

	</div>
	

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
    $('.approve-btn').click(function(){
        var clickBtnValue = $(this).val();
        console.log(clickBtnValue);
        var ajaxurl = 'loginController.php',
        data =  {'sign': clickBtnValue};
        $.post(ajaxurl, data, function (response) { 
        	//$('#'+clickBtnValue).prop('disabled', true);
        	$("button[type=submit]").attr("disabled", "disabled");
            alert("signature approved successfully!");
        });
    });

});
</script>
</body>
</html>