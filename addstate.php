<?php
	include_once("connection.php");
	session_start();
	
	function getuserid($email,$conn) {
    	$query ="select userid from user where uemail = '$email'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row["userid"];
	}

	function gettagid($name,$conn){
		$query ="select tagid from tag where tagname = '$name'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row["tagid"];
	}

	function intag($nid,$state,$name,$conn){
		$query ="INSERT INTO tag(tagname) VALUES('$name')";
	 	$rs = mysqli_query($conn,$query);
	 	if($rs){
	 		$tagid = $conn->insert_id;
	 		$query1 ="INSERT INTO utag(userid,tagid,state) VALUES ($nid,$tagid,'$state')";
	 		$rs1 = mysqli_query($conn,$query1);
	 		echo mysqli_error($conn);
	 	}
	 	else{
	 		$id = gettagid($name,$conn);
	 		$query1 ="INSERT INTO utag(userid,tagid,state) VALUES ($nid,$id,'$state')";
	 		$rs1 = mysqli_query($conn,$query1);
	 		echo mysqli_error($conn);
	 	}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$reply = array('success' => 0);
		if(isset($_SESSION['email'])){
			$a = $_POST["state"] ;
			$c = $_POST["stime"] ;
			$d = $_POST["etime"] ;
			$g = $_POST["rad"] ;
			$tags = $_POST["tag"];
			$tags1 = explode(',', $tags) ;
			$userid=getuserid($_SESSION['email'],$conn);

			$e=date('H:i:s',strtotime($c));
			$f=date('H:i:s',strtotime($d));

		 	$query =" INSERT INTO userpref(userid,state,stime,etime,radius) VALUES('$userid','$a','$c','$d','$g')";
		 	$rs = mysqli_query($conn,$query);
		 	$reply = array('success' => mysqli_error($conn));
		 	if ($rs) {
		 		for ($j = 0; $j < sizeof($tags1); $j++){
		 			intag($userid,$a,$tags1[$j],$conn);
		 		}
		 		$reply = array('success' => "1");
	 		} 
	 		else {
	 			$reply = array('success' => "0");
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
	echo json_encode($reply);
?>