<?php
	session_start();
	
	if(isset($_SESSION['time']))
	{
	$date = date('Y-m-d H:i:s');
	$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 0 minutes");
	$zeit = date('Y-m-d H:i:s', $timestamp);
	
	$date = date('Y-m-d H:i:s');
	$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 60 minutes");
	$_SESSION['time'] = date('Y-m-d H:i:s', $timestamp);
	$logoutzeit = $_SESSION['time'];
		if($zeit>=$logoutzeit)
		{
			//echo logout.php;
			echo "Deine Loginzeit ist abgelaufen! Bitte erneut <a href='index.php'>einloggen</a>";
			exit;
		}
	}
	
	if(!isset($_SESSION['nickname']))
	{
	echo "Bitte erst <a href='index.php'>einloggen</a>";
	exit;
	}
?>