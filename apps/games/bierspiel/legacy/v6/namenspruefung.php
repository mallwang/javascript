<?php session_start(); include 'connect.php'; include 'funktionen.php';

/* > session_start() benötigt?
 * > else benötigt?
 */

// Benutzer
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
// E-Mail
elseif (isset($_POST["email"])){
	$email = $_POST["email"];
	// Abfrage, ob E-Mail schon vorhanden
	$user = anfrage("SELECT COUNT(id) as anzahl FROM user WHERE email = '$email'");
	if($user[0] == 0){
		echo 'yes';
	}
	else{
		echo 'no';
	}
}
// Firmenname
elseif (isset($_POST["firmenname"])){
	$firmenname = $_POST["firmenname"];
	// Abfrage, ob Firmenname schon vorhanden
	$user = anfrage("SELECT COUNT(id) as anzahl FROM firmendaten WHERE firmenname = '$firmenname'");
	if($user[0] == 0){
		echo 'yes';
	}
	else{
		echo 'no';
	}
}

else{
	
}