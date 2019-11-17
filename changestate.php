<?php
 
include_once("connection.php"); 
session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){
			$state=$_POST['state'];
			$userid = $_SESSION['email'];

			$query = "UPDATE user SET userstate = '$state' WHERE uemail = '$userid'";
			$rs = mysqli_query($conn,$query);

			if($rs){
	 			header('Location: changestate.html');
			}
			else
			{
				unset($_SESSION['email']);
				header('Location: databaseerror.html');
			}
		}
		else{
			header('Location : sessionerror.html');
		}
	}
	else{
		unset($_SESSION['email']);
		header('Location : geterror.html');
	}
?>