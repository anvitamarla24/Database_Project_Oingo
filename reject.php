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
			$id=$_POST['reqid'];
			$a = getuserid($_SESSION['email'],$conn);

			if($a < $id){
				$query = "UPDATE friends SET status = 'declined' WHERE userid = '$a' and fuserid = '$id' ";
			}
			else{
				$query = "UPDATE friends SET status = 'declined' WHERE fuserid = '$a' and userid = '$id' ";
			}
			$rs = mysqli_query($conn,$query);

			if($rs){
	 			header('Location: request.html');
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