<?php
	include_once("connection.php");

	function getuserid($email,$conn) {
    	$query ="select userid from user where uemail = '$email'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row["userid"];
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$a = $_POST["email"] ;
		$c = $_POST["first"] ;
		$d = $_POST["last"] ;
		$b = $_POST["password"] ;
		$h = $_POST["state"] ;
		$e = $_POST["stime"] ;
		$f = $_POST["etime"] ;
		$g = $_POST["radius"] ;

		$e=date('H:i:s',strtotime($e));
		$f=date('H:i:s',strtotime($f));

	 	$query =" INSERT INTO user(firstname,lastname,uemail,password, userstate) VALUES('$c','$d','$a','$b','$h')";
	 	$result = mysqli_query($conn,$query);
	 	if ($result) {
	 		$userid=getuserid($a,$conn);
	 		$query1 =" INSERT INTO userpref(userid,state,stime,etime,radius) VALUES('$userid','$h','$e','$f','$g')";
	 		$result1 = mysqli_query($conn,$query1);
	 		if ($result1){
     			$reply = array('success' => 1);
     		}
     		else{
     			$query2 =" DELETE FROM user where userid = '$userid'";
	 			$result2 = mysqli_query($conn,$query1);
	 			$reply = array('success' => 0, 'error' => "Something went wrong, Please try registering again.");
     		}
 		} 
 		else {
			header('Location : databaseerror.html');
 		}
 		echo json_encode($reply);
	}
	else{
		header('Location : geterror.html');
	}
	mysqli_close($conn);
?>