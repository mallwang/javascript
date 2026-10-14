<?php session_start(); include '../huelle/connect.php'; include '../funktionen/funktionen.php';

// aktueller User
$aktuellerUser = $_SESSION['nickname'];
// FirmenID des aktuellen Users abfragen
$firmenId = anfrage("SELECT `id` FROM `firmendaten` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `nickname` = '$aktuellerUser')");
// Bargeld check
$bargeld = anfrage("SELECT bargeld FROM firmendaten WHERE user_id=(SELECT id FROM user WHERE nickname='$aktuellerUser')");

$id = $_POST["id"];
$arbeitswert = $_POST["arbeitswert"];
$abfindung = $_POST["abfindung"];

// Wenn genug bargeld vorhanden ist
if ($abfindung<$bargeld[0]){
	// Kontostand berechnen
	$kontostandNeu = ($bargeld[0] - $abfindung);
	// Abfindung bezahlen
	$zahleAbfindung = anfrage("UPDATE firmendaten SET bargeld='$kontostandNeu' WHERE id='$firmenId[0]'");
	// Normales Gehalt berechnen auf Grundlage der Art
	$gehalt = grundgehaltBerechnen($id, $arbeitswert);
// 	echo $gehalt;
	// Arbeitnehmer updaten
	$keinAG = anfrage("UPDATE arbeitnehmer SET arbeitgeber='-1', gehalt='$gehalt' WHERE id='$id'");
	
	if($keinAG){
		echo 'ok';
	}
	else{
		echo 'nichtOk';
	}
}
// Wenn nich genug Bargeld vorhanden ist
else{
	echo 'noBargeld';
}

?>