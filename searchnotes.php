<?php
	include_once("connection.php");
	session_start();

	function getuserid($email,$conn) {
    	$query ="select userid,userstate from user where uemail = '$email'";
	 	$rs = mysqli_query($conn,$query);
	 	$row = mysqli_fetch_assoc($rs);
	 	return $row;
	}

	function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
    
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
			$lat = $_POST["lat"] ;
			$long = $_POST["long"] ;
			$search=$_POST['on'];
			$word= $_POST['word'];
			$row = getuserid($_SESSION['email'],$conn);
			$a = $row["userid"];
			$b = $row["userstate"];
			$jd = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
   			$curday = strtolower(jddayofweek($jd,1));
			
			if ($search == "tag"){
				$query ="select DISTINCT n.noteid,n.notedesc,nf.lat,nf.longg,uf.radius, nf.rad
					from user u, notes n, userpref uf, notefilter nf, scheduler s, days d, tag t1, tag t2, utag ut, ntag nt
					where u.userid = '$a' and uf.state = '$b' and u.userid = uf.userid and n.noteid = nf.noteid and s.sid = d.sid and s.nid = nf.nid and t1.tagid = ut.tagid and ut.userid = uf.userid and uf.state = ut.state and nt.tagid = t2.tagid and nf.nid = nt.nid and t1.tagid = t2.tagid and (s.fromdate <= CURRENT_DATE  and CURRENT_DATE <= s.todate) and (d.daay = 'everyday' or d.daay = '$curday') and (d.fromtime <= CURRENT_TIME and CURRENT_TIME <= d.totime) and (uf.stime <= CURRENT_TIME and CURRENT_DATE <= uf.etime) and t2.tagname LIKE '%$word%'";
			}
			elseif ($search == "name"){
				$query ="select DISTINCT n.noteid,n.notedesc,nf.lat,nf.longg,uf.radius, nf.rad
					from user u, notes n, userpref uf, notefilter nf, scheduler s, days d, tag t1, tag t2, utag ut, ntag nt
					where u.userid = '$a' and uf.state = '$b' and u.userid = uf.userid and n.noteid = nf.noteid and s.sid = d.sid and s.nid = nf.nid and t1.tagid = ut.tagid and ut.userid = uf.userid and uf.state = ut.state and nt.tagid = t2.tagid and nf.nid = nt.nid and t1.tagid = t2.tagid and (s.fromdate <= CURRENT_DATE  and CURRENT_DATE <= s.todate) and (d.daay = 'everyday' or d.daay = '$curday') and (d.fromtime <= CURRENT_TIME and CURRENT_TIME <= d.totime) and (uf.stime <= CURRENT_TIME and CURRENT_DATE <= uf.etime) and n.notedesc LIKE '%$word%'";
			}

		 	$rs = mysqli_query($conn,$query);

		 	if($rs){
		 		$val = mysqli_num_rows($rs);
		 		$count = 0;
				$temp = [];
				while( $count < (int)$val ){
					if($row = mysqli_fetch_assoc($rs)){
						if ($be <= $row["rad"] && $be <= $row["radius"]){
							$temp[] = array("id" => $row["noteid"], "desc" => $row["notedesc"]);
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