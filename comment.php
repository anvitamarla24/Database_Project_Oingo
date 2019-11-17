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
			$com=$_POST['comment'];
			$id=$_POST['reqid'];

		 	$query =" INSERT INTO comm(noteid,userid,commentdesc,timestampts) VALUES('$id','$userid','$com',now())";
		 	$rs = mysqli_query($conn,$query);
		 	if (!$rs) {
		 		unset($_SESSION['email']);
		 		header('Location: Databaseerror.html');
	 		} 
	 		else{
	 			$reply = array("success" => 1);
	 			echo json_encode($reply);
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