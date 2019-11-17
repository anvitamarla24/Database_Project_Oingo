<?php
	include_once("connection.php");
	session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(!isset($_SESSION['email'])){
			header('Location : sessionerror.html');
		}
		else{
			unset($_SESSION['email']);
			$reply = array('success' => "1");
			echo json_encode($reply);
		}
	}
	else{
		unset($_SESSION['email']);
		header('Location : geterror.html');
	}
	mysqli_close($conn);
?>