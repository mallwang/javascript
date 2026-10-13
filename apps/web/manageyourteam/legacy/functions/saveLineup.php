<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$aktiveSpieler = mysql_real_escape_string($_POST['aktiveSpieler']);
$teamID = mysql_real_escape_string($_POST['teamID']);
$taktik = mysql_real_escape_string($_POST['taktik']);
// Erst ganzes Team auf 0 setzen
$datenNull = mysql_query("UPDATE `playertab` SET `positionID` = '0' WHERE `teamID` = '$teamID'");
// String zerlegen
$daten1 = explode("||", $aktiveSpieler); // [0]="26->9" [1]="25->10" usw.
// Schleifendurchlauf
for ($i = 0; $i < sizeof($daten1); $i++) {
	$daten2 = explode("->", $daten1[$i]); // [0]="26" [1]="9" usw.
	$playerID = $daten2[0];
	$positionID = $daten2[1];
	// Spieler eintragen
	$datenTeam = mysql_query("UPDATE `playertab` SET `positionID` = '$positionID' WHERE `playerID` = '$playerID'");
}
// Taktik eintragen
$datenTaktik = mysql_query("UPDATE `teamtab` SET `lineupID` = '$taktik' WHERE `teamID` = '$teamID'");
// Wenn Eintragungen erfolgreich waren
if ($datenTaktik && $datenTeam)
	echo "ok";
else
	echo "Fehler beim Dateneintrag";