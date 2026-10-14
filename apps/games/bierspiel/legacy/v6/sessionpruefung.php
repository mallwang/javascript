<?php session_start(); header("Content-type:text/html; charset=utf-8");
	
	if(!isset($_SESSION['nickname'])){
		//echo "kein User angemeldet";
		include 'startHeader.php';
	}
	else{
		//echo "user: " . $_SESSION['nickname'];
		include 'geheimHeader.php';
	}
	
?>