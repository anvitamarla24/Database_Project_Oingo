<?php

	$db_name = "proj1" ;
	$mysql_user = "root" ;
	$mysql_pass = "qwerty" ;
	$server_name = "localhost" ;

	$conn = mysqli_connect($server_name , $mysql_user ,$mysql_pass ,$db_name);

	if (!$conn) {
    	header('Location : databaseerror.html');
	}
?>