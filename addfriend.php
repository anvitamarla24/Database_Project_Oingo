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
			$userid=getuserid($_SESSION['email'],$conn);
			$id=$_POST['reqid'];

			if ($userid < $id){
		 		$query =" INSERT INTO friends(userid,fuserid,status,fromid) VALUES('$userid','$id','pending','$userid')";
		 	}
		 	else{
		 		$query =" INSERT INTO friends(userid,fuserid,status,fromid) VALUES('$id','$userid','pending','$userid')";
		 	}
		 	$rs = mysqli_query($conn,$query);
		 	if ($rs) {
		 		header('Location: addfriend.html');
	 		} 
	 		else {
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
	mysqli_close($conn);
?>