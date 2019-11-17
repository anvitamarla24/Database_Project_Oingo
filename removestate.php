<?php
 
include_once("connection.php"); 
session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){
			$userid=$_POST['reqid'];
			$state= $_POST['reqid1'];

			$query = "DELETE FROM userpref WHERE userid='$userid' and state = '$state'";
			$rs = mysqli_query($conn,$query);

			if($rs){
	 			header('Location: removestate.html');
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