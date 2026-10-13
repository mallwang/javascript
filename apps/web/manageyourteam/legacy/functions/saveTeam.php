<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$teamID = mysql_real_escape_string($_POST['teamID']);
$teamTypeID = mysql_real_escape_string($_POST['teamTypeID']);
$teamName = mysql_real_escape_string($_POST['teamName']);
$coachID = mysql_real_escape_string($_POST['coachID']);
// Prüfen, ob Trainer schon eine Mannschaft trainiert
$datenCoach = mysql_query("SELECT `teamCoachID`, `teamID` FROM `teamcoachtab` WHERE `coachID` = '$coachID'");
$datenCoachTab = mysql_fetch_array($datenCoach, MYSQL_NUM);
$anzahlGleicherCoach = $datenCoachTab[0];
if ($anzahlGleicherCoach != 0 && $datenCoachTab[1] != $teamID){
	echo "coachSchonVorhanden";
	exit;
}
// Mannschaft updaten
$updateTeam = mysql_query("UPDATE `teamtab` SET `teamName` = '$teamName', `teamTypeID` = '$teamTypeID' WHERE `teamID` = '$teamID'");
// teamcoachtab updaten
$updateTeamCoach = mysql_query("UPDATE `teamcoachtab` SET `coachID` = '$coachID' WHERE `teamID` = '$teamID'");
// Wenn Dateneintrag erfolgreich war
if ($updateTeam && $updateTeamCoach)
	echo "ok";
else
	echo "fehlerDateneintrag";