<?php session_start(); include '../huelle/connect.php'; include '../funktionen/funktionen.php';


// aktueller User
$aktuellerUser = $_SESSION['nickname'];
// FirmenID des aktuellen Users abfragen
$firmenId = anfrage("SELECT `id` FROM `firmendaten` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `nickname` = '$aktuellerUser')");

$id = $_POST["id"];

// aktuellen Arbeitgeber feststellen
$aktAG = anfrage("SELECT arbeitgeber FROM arbeitnehmer WHERE id='$id'");
// wenn bereits jemand diesen AG eingestellt hat
if($aktAG[0]=='-1'){
	// Arbeitnehmer updaten
	$neuerAG = anfrage("UPDATE arbeitnehmer SET arbeitgeber='$firmenId[0]' WHERE id='$id'");
	// neuen Arbeitnehmer erstellen
	fuegeANHinzu(1, 1);
	echo 'ok';
}
else{
	echo 'schonEingestellt';
}

?>