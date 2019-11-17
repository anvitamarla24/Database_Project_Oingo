<?php
 
include_once("connection.php"); 
session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){
			$noteid=$_POST['reqid'];

			$query = "DELETE FROM notes WHERE noteid='$noteid'";
			$rs = mysqli_query($conn,$query);

			if($rs){
	 			header('Location: removenote.html');
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