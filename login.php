<?php
	include_once("connection.php");
	session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$a = $_POST["email"] ;
		$b = $_POST["password"] ;

	 	$query ="select * from user where uemail = '$a' and password = '$b'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	$num = mysqli_num_rows($rs);

	 	if($num){
	 		$_SESSION["email"] = $a;
	 		$reply = array('success' => "1");
		}
		else
		{
			$reply = array('success' => "0", "error" => "Enter valid login credentials");
		}
	}
	else{
		header('Location : geterror.html');
	}
	mysqli_close($conn);
	echo json_encode($reply);
?>