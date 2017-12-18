<?php
$con = mysqli_connect("127.0.0.1", "root", "root1234", "VirusDB");
if (!$con) {
	die('Connect Error: ' . mysqli_connect_error());
}
?>