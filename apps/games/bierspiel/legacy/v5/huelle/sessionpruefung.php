<?php
	session_start();
	
	//if(isset($_SESSION['time']))
	//{
	//$date = date('Y-m-d H:i:s');
	//$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 0 minutes");
	//$zeit = date('Y-m-d H:i:s', $timestamp);
	//
	//$date = date('Y-m-d H:i:s');
	//$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 60 minutes");
	//$_SESSION['time'] = date('Y-m-d H:i:s', $timestamp);
	//$logoutzeit = $_SESSION['time'];
	//	if($zeit>=$logoutzeit)
	//	{
	//		//echo logout.php;
	//		echo "Deine Loginzeit ist abgelaufen! Bitte erneut <a href='index.php'>einloggen</a>";
	//		exit;
	//	}
	//}
	
	if(!isset($_SESSION['nickname'])){
		echo "<div align='center' class='bg-color-red'><h2 class='fg-color-orange'><br><b>Sie sind nicht eingeloggt!</b></h2><br></div>";
		include("../index.php");
		exit;
	}
?>