<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$coachID = mysql_real_escape_string($_POST['coachID']);
// DB Abfrage userID
$datenUser = mysql_query("SELECT `userID` FROM `coachtab` WHERE `coachID` = '$coachID'");
$datenUserTab = mysql_fetch_array($datenUser, MYSQL_NUM);
$userID = $datenUserTab[0];
// User löschen
$deleteUser = mysql_query("DELETE FROM `usertab` WHERE `userID` = $userID");
// Player löschen
$deleteCoach = mysql_query("DELETE FROM `coachtab` WHERE `coachID` = $coachID");
// Wenn Löschen geklappt hat
if ($deleteUser && $deleteCoach)
	echo "ok";
else
	echo "Löschen fehlgeschlagen";