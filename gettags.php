<?php
	include_once("connection.php");
	session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){
			$a = $_POST["id"];

				$query = "select t.tagname as tagname, t.tagid as tagid from notes n, notefilter nf, tag t, ntag nt where n.noteid = '$a' and n.noteid = nf.noteid and nf.nid = nt.nid and nt.tagid = t.tagid";
			 	$rs = mysqli_query($conn,$query);

			 	if($rs){
			 		$val = mysqli_num_rows($rs);
			 		$count = 0;
					$temp = [];
					while( $count < (int)$val ){
						if($row = mysqli_fetch_assoc($rs)){
							$temp[] = array("id" => $row["tagid"],"name" => $row["tagname"]);
						}
						$count = $count + 1;
					}
			 		$reply = array('success' => 1, 'data' => $temp);
			 		echo json_encode($reply);
				}
				else{
					$reply = array('success' => 0);
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