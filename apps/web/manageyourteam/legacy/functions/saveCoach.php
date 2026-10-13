<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$coachFirstName = mysql_real_escape_string($_POST['coachFirstName']);
$coachSecondName = mysql_real_escape_string($_POST['coachSecondName']);
$coachID = mysql_real_escape_string($_POST['coachID']);
// Trainer updaten
$updateCoach = mysql_query("UPDATE `coachtab` SET `coachFirstName` = '$coachFirstName', `coachSecondName` = '$coachSecondName' WHERE `coachID` = '$coachID'");
// Wenn Dateneintrag erfolgreich war
if ($updateCoach)
	echo "ok";
else
	echo "fehlerDateneintrag";