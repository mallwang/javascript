<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamTypeID = mysql_real_escape_string($_POST['teamTypeID']);
$teamName = mysql_real_escape_string($_POST['teamName']);
$coachID = mysql_real_escape_string($_POST['coachID']);
$clubID = $_SESSION['clubID'];
// Prüfen, ob Trainer schon eine Mannschaft trainiert
$datenCoach = mysql_query("SELECT `teamCoachID` FROM `teamcoachtab` WHERE `coachID` = '$coachID'");
$datenCoachTab = mysql_fetch_array($datenCoach, MYSQL_NUM);
$anzahlGleicherCoach = $datenCoachTab[0];
if ($anzahlGleicherCoach != 0){
	echo "coachSchonVorhanden";
	exit;
}
// Neue Mannschaft eintragen
$dateneintragTeam = mysql_query("INSERT INTO `teamtab`(`clubID`, `teamName`, `teamTypeID`) VALUES ('$clubID', '$teamName', '$teamTypeID')");
// ID der Mannschafts-Tabelle
$teamTabID = mysql_insert_id();
// Neuen Eintrag in teamCoachTab
$dateneintragTeamCoach = mysql_query("INSERT INTO `teamcoachtab`(`coachID`, `teamID`) VALUES ('$coachID', '$teamTabID')");
// Wenn Eintragungen erfolgreich waren
if ($dateneintragTeam && $dateneintragTeamCoach)
	echo 'ok';
// Wenn der Dateneintrag fehlschlug
else
	echo 'Fehler beim Dateneintrag';