<?php session_start(); include 'connect.php'; include 'funktionen.php';

if (isset($_POST["nickname"])){
	$nickname = $_POST["nickname"];
	// Abfrage, ob Benutzer schon vorhanden
	$user = anfrage("SELECT COUNT(id) as anzahl FROM user WHERE nickname = '$nickname'");
	if($user[0] == 0){
		echo 'yes';
	}
	else{
		echo 'no';
	}
}

elseif (isset($_POST["email"])){
	$email = $_POST["email"];
	// Abfrage, ob Benutzer schon vorhanden
	$user = anfrage("SELECT COUNT(id) as anzahl FROM user WHERE email = '$email'");
	if($user[0] == 0){
		echo 'yes';
	}
	else{
		echo 'no';
	}
}

else{
	
}