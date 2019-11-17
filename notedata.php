<?php
	include_once("connection.php");
	session_start();

	function getuserid($id,$conn) {
    	$query ="select n.userid as userid,n.comments as allow, u.uemail as email, u.firstname as first, u.lastname as last from notes n, user u where n.noteid = '$id' and u.userid = n.userid";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row;
	}

	function getloca($id,$conn) {
    	$query ="select nf.lat,nf.longg from notes n, notefilter nf where n.noteid = '$id' and nf.noteid = n.noteid";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row;
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){
			$a = $_POST["id"];
			$b = getuserid($a,$conn);
			$d = getloca($a,$conn);
			$c = $b["userid"];

			if ( $b["allow"] == "allowed" ){
			 	$query ="select c.cid, u.uemail as email, c.commentdesc as descc, c.timestampts as ts from comm c, user u where noteid = '$a' and c.userid = u.userid order by c.timestampts ASC";
			 	$rs = mysqli_query($conn,$query);

			 	if($rs){
			 		$val = mysqli_num_rows($rs);
			 		$count = 0;
					$temp = [];
					while( $count < (int)$val ){
						if($row = mysqli_fetch_assoc($rs)){
							$temp[] = array("cid" => $row["cid"],"email" => $row["email"],"ts" => $row["ts"], "desc" => $row["descc"]);
						}
						$count = $count + 1;
					}
			 		$reply = array('success' => 1, "allow" => 1, "first" => $b["first"], "last" => $b["last"], "uemail" => $b["email"], "lat" => $d["lat"], "long" => $d["longg"], 'data' => $temp);
			 		echo json_encode($reply);
				}
				else{
					$reply = array('success' => 0);
					echo json_encode($reply);
				}
			}
			else{
				$reply = array('success' => 1, "allow" => 0, "first" => $b["first"], "last" => $b["last"], "uemail" => $b["email"], "lat" => $d["lat"], "long" => $d["longg"]);
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