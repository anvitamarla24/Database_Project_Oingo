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

	function intag($nid,$name,$conn){
		$query ="INSERT INTO tag(tagname) VALUES('$name')";
	 	$rs = mysqli_query($conn,$query);
	 	if($rs){
	 		$tagid = $conn->insert_id;
	 		$query1 ="INSERT INTO ntag(nid,tagid) VALUES($nid,$tagid)";
	 		$rs1 = mysqli_query($conn,$query1);
	 	}
	 	else{
	 		$id = gettagid($name,$conn);
	 		$query1 ="INSERT INTO ntag(nid,tagid) VALUES($nid,$id)";
	 		$rs1 = mysqli_query($conn,$query1);
	 	}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){
			$a = $_POST["dataa"] ;
			$b = $_POST["desc"] ;
			$c = $_POST["sdate"] ;
			$d = $_POST["edate"] ;
			$e = $_POST["rad"] ;
			$f = $_POST["comm"] ;
			$tags = $_POST["tag"];
			$tags1 = explode(',', $tags) ;
			$lat = $_POST["lati"];
			$long = $_POST["longi"];
			$userid=getuserid($_SESSION['email'],$conn);

		 	$query =" INSERT INTO notes(notedesc,userid,comments) VALUES('$b','$userid','$f')";
		 	$rs = mysqli_query($conn,$query);
		 	if ($rs) {
		 		$noteid = $conn->insert_id;
		 		$query1 =" INSERT INTO notefilter(noteid,lat,longg,rad) VALUES('$noteid','$lat','$long','$e')";
		 		$rs1 = mysqli_query($conn,$query1);
		 		if ($rs1){
		 			$nid = $conn->insert_id;
		 			$query2 =" INSERT INTO scheduler(nid,fromdate,todate) VALUES('$nid','$c','$d')";
		 			$rs2 = mysqli_query($conn,$query2);
		 			if ($rs2){
		 				$sid = $conn->insert_id;
		 				$query3 =" INSERT INTO days(sid,fromtime,totime,daay) ";
		 				for ( $i = 0 ; $i < sizeof($a); $i++){
		 					$temp = $a[$i];
		 					$e=date('H:i:s',strtotime($temp["stime"]));
							$f=date('H:i:s',strtotime($temp["etime"]));
							$v = $temp["value"];
		 					$query3 = $query3."VALUES('$sid','$e','$f','$v')";
		 				}
		 				$rs3 = mysqli_query($conn,$query3);
		 				if($rs3){
		 					for ($j = 0; $j < sizeof($tags1); $j++){
		 						intag($nid,$tags1[$j],$conn);
		 					}
		 					$reply = array('success' => "1");
		 				}
		 				else{
		 					$reply = array('success' => "0");
		 				}
		 			}
		 			else{
		 				$reply = array('success' => "0");
		 			}
		 		}
		 		else{
		 			$reply = array('success' => "0");
		 		}
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