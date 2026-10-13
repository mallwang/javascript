<?php session_start();
require_once '../inc/DB.php';
include 'lib/funktionen.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();
// Daten übernehmen
$action = mysql_real_escape_string($_POST['action']);
$teamID = mysql_real_escape_string($_POST['teamID']);
$email = $_SESSION['email'];
$userID = $_SESSION['userID'];
// DB Abfrage taktik
if ($action == "getTaktik"){
	$datenTeam = mysql_query("SELECT `lineupID` FROM `teamtab` WHERE `teamID` = '$teamID'");
	$datenTeamTab = mysql_fetch_array($datenTeam, MYSQL_NUM);
	$return = $datenTeamTab[0];
}
else if($action == "getLineup"){
	// Alle Spieler abfragen und per json übergeben
	$datenTeam = mysql_query("SELECT `playerID`, `playerFirstName`, `playerSecondName`, `playerBirthdate`, `positionID` FROM `playertab` WHERE `teamID` = '$teamID'");
	$teamPlayer = array();
	$playerCount = 0;
	while($playerRow=mysql_fetch_array($datenTeam)){
		$playerID =	$playerRow['playerID'];
		$playerFirstName =	$playerRow['playerFirstName'];
		$playerSecondName =	$playerRow['playerSecondName'];
		$playerBirthdate =	$playerRow['playerBirthdate'];
		$positionID =	$playerRow['positionID'];
		$player = array("playerID" => $playerID, "playerFirstName" => $playerFirstName, "playerSecondName" => $playerSecondName, "playerBirthdate" => $playerBirthdate, "positionID" => $positionID);
		$teamPlayer[$playerCount]=$player;
		$playerCount++;
	}
	$return = json_encode($teamPlayer);
}
echo $return;