<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$playerID = mysql_real_escape_string($_POST['playerID']);
// DB Abfrage userID
$datenUser = mysql_query("SELECT `userID` FROM `playertab` WHERE `playerID` = '$playerID'");
$datenUserTab = mysql_fetch_array($datenUser, MYSQL_NUM);
$userID = $datenUserTab[0];
// User löschen
$deleteUser = mysql_query("DELETE FROM `usertab` WHERE `userID` = '$userID'");
// Player löschen
$deletePlayer = mysql_query("DELETE FROM `playertab` WHERE `playerID` = '$playerID'");
// Player aus attendancetab löschen
$deletePlayer = mysql_query("DELETE FROM `attendancetab` WHERE `playerID` = '$playerID'");
// Wenn Löschen geklappt hat
if ($deleteUser && $deletePlayer)
	echo "ok";
else
	echo "Löschen fehlgeschlagen";