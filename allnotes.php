<?php
	include_once("connection.php");
	session_start();

	function getd($latitude1, $longitude1, $latitude2, $longitude2) {
    
	    $earth_radius = 6371;
	 
	    $dLat = deg2rad($latitude2 - $latitude1);
	    $dLon = deg2rad($longitude2 - $longitude1);
	 
	    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
	    $c = 2 * asin(sqrt($a));
	    $d = $earth_radius * $c;
	 
	    return $d;
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_SESSION['email'])){

			$search=$_POST['on'];
			$word= $_POST['word'];
			$lat= $_POST['latitude'];
			$long= $_POST['longitude'];

			if ($lat != ""){
				if ($search == "tag"){
					$query ="select DISTINCT n.noteid,n.notedesc,nf.lat,nf.longg from tag t, ntag nt, notes n, notefilter nf where n.noteid = nf.noteid and nf.nid = nt.nid and t.tagid = nt.tagid and t.tagname LIKE '%$word%'";
				}
				elseif ($search == "name"){
					$query ="select DISTINCT n.noteid,n.notedesc,nf.lat,nf.longg from notes n, notefilter nf where n.noteid = nf.noteid and n.notedesc LIKE '%$word%'";	
				}
			}
			else{
				if ($search == "tag"){
					$query ="select DISTINCT n.noteid,n.notedesc from tag t, ntag nt, notes n, notefilter nf where n.noteid = nf.noteid and nf.nid = nt.nid and t.tagid = nt.tagid and t.tagname LIKE '%$word%'";
				}
				elseif ($search == "name"){
					$query ="select DISTINCT n.noteid,n.notedesc from notes n where n.notedesc LIKE '%$word%'";	
				}
			}

		 	$rs = mysqli_query($conn,$query);

		 	if($rs){
		 		$val = mysqli_num_rows($rs);
		 		$count = 0;
				$temp = [];
				while( $count < (int)$val ){
					if($row = mysqli_fetch_assoc($rs)){
						if ($lat != ""){
							$dis = getd($lat,$long,$row["lat"],$row["longg"]);
							if ($dis <= 100){
								$temp[] = array("id" => $row["noteid"], "desc" => $row["notedesc"]);
							}
						}
						else{
							$temp[] = array("id" => $row["noteid"], "desc" => $row["notedesc"]);
						}
					}
					$count = $count + 1;
				}
		 		$reply = array('success' => 1, 'data' => $temp);
			}
			else{
				$reply = array('success' => 1, 'data' => mysqli_error($conn));
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