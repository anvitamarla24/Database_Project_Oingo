<?php
 
include_once("connection.php"); 
session_start();

	function getuserid($email,$conn) {
    	$query ="select userid from user where uemail = '$email'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row["userid"];
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){
			$fuserid=$_POST['reqid'];
			$userid = getuserid($_SESSION['email'],$conn);

			$query = "DELETE FROM friends WHERE userid='$userid' and fuserid = '$fuserid'";
			$rs = mysqli_query($conn,$query);

			if($rs){
	 			header('Location: removefriend.html');
			}
			else
			{
				unset($_SESSION['email']);
				header('Location : databaseerror.html');
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