<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankverbindung herstellen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$eventID = mysql_real_escape_string($_POST['eventID']);
$eventType = mysql_real_escape_string($_POST['eventType']);
// Aus eventtab löschen
$deleteEvent = mysql_query("DELETE FROM `eventtab` WHERE `eventID` = '$eventID'");
// Wenn es ein Spiel ist, auch aus teammatchtab löschen
if ($eventType == 2)
	// Aus teammatchtab löschen
	$deleteMatch = mysql_query("DELETE FROM `teammatchtab` WHERE `eventID` = '$eventID'");
// Wenn es ein Turnier ist, auch aus teamtournamenttab löschen
else if ($eventType == 3)
	$deleteMatch = mysql_query("DELETE FROM `teamtournamenttab` WHERE `eventID` = '$eventID'");
else
	$deleteMatch = true;
// Wenn Löschen geklappt hat
if ($deleteEvent && $deleteMatch)
	echo "ok";
else
	echo "Löschen fehlgeschlagen";
