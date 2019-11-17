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

			$a = getuserid($_SESSION['email'],$conn);

		 	$query ="select n.noteid,n.notedesc from notes n where n.userid = '$a'";
		 	$rs = mysqli_query($conn,$query);

		 	if($rs){
		 		$val = mysqli_num_rows($rs);
		 		$count = 0;
				$temp = [];
				while( $count < (int)$val ){
					if($row = mysqli_fetch_assoc($rs)){
						$temp[] = array("id" => $row["noteid"], "desc" => $row["notedesc"]);
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