<?php
	include_once("connection.php");
	session_start();

	function getuserid($email,$conn) {
    	$query ="select userid from user where uemail = '$email'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row["userid"];
	}
	function getemail($userid,$conn) {
    	$query ="select uemail,firstname,lastname from user where userid = '$userid'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row;
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){

			$a = getuserid($_SESSION['email'],$conn);

		 	$query ="select userid,fuserid,status from friends where (userid = '$a' or fuserid = '$a') and fromid != '$a'";
		 	$rs = mysqli_query($conn,$query);

		 	if($rs){
		 		$val = mysqli_num_rows($rs);
		 		$count = 0;
				$temp = [];
				while( $count < (int)$val ){
					if($row = mysqli_fetch_assoc($rs)){
						if($row["status"] == "pending"){
							if ($row["userid"] == $a){
								$x = getemail($row["fuserid"],$conn);
								$temp[] = array("id" => $row["fuserid"], "email" => $x["uemail"], "first" => $x["firstname"], "last" => $x["lastname"]);
							}
							else if ($row["fuserid"] == $a){
								$x = getemail($row["userid"],$conn);
								$temp[] = array("id" => $row["userid"], "email" => $x["uemail"], "first" => $x["firstname"], "last" => $x["lastname"] );
							}
						}
					}
					$count = $count + 1;
				}
		 		$reply = array('success' => 1, 'data' => $temp);
		 		echo json_encode($reply);
			}
			else{
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