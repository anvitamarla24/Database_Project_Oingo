<?php
	include_once("connection.php");
	session_start();

	function getuserid($email,$conn) {
    	$query ="select userid from user where uemail = '$email'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row["userid"];
	}

	function getf($userid,$fuserid,$conn) {
    	$query ="select status from friends where (userid = '$userid' and fuserid = '$fuserid') or (userid = '$fuserid' and fuserid = '$userid')";
	 	$rs = mysqli_query($conn,$query);
	 	if ($row = mysqli_fetch_assoc($rs)){
	 		return "Yes";
	 	}
	 	else{
	 		return "No";
	 	}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){

			$uid = getuserid($_SESSION['email'],$conn);
			$word= $_POST['word'];

			$query ="select userid,uemail,firstname,lastname from user where firstname LIKE '%$word%'";
		 	$rs = mysqli_query($conn,$query);

		 	if($rs){
		 		$val = mysqli_num_rows($rs);
		 		$count = 0;
				$temp = [];
				while( $count < (int)$val ){
					if($row = mysqli_fetch_assoc($rs)){
						$x = getf($uid,$row["userid"],$conn);
						if ( $x == "No" and $uid != $row["userid"]){
							$temp[] = array("id" => $row["userid"], "email" => $row["uemail"], "first" => $row["firstname"], "last" => $row["lastname"]);
						}
					}
					$count = $count + 1;
				}
		 		$reply = array('success' => 1, 'data' => $temp);
			}
			else{
				$reply = array('success' => 0);
			}
			echo json_encode($reply);
		}
		else{
			header('Location : sessionerror.html');
		}
	}
	else{
		header('Location : geterror.html');
	}
	mysqli_close($conn);
?>