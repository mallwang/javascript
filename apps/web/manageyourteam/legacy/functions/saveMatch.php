<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$eventID = mysql_real_escape_string($_POST['eventID']);
$teamGoals = mysql_real_escape_string($_POST['teamGoals']);
$opponentGoals = mysql_real_escape_string($_POST['opponentGoals']);
// Spieldaten eintragen
$datenupdateMatch = mysql_query("UPDATE `teammatchtab` SET `teamGoals` = '$teamGoals', `opponentGoals` = '$opponentGoals' WHERE `eventID` = '$eventID'");
// Wenn Eintragungen erfolgreich waren
if ($datenupdateMatch)
	echo "ok";
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';