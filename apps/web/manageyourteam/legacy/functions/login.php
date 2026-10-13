<?php session_start();
require_once '../inc/DB.php';

// Datenbankinstanz erstellen und Verbindung aufbauen
$db = new DB();
$con = $db->getConnection();

// Variablen abfragen
$email = strtolower(mysql_real_escape_string($_POST["email"]));
$passwort = mysql_real_escape_string($_POST["passwort"]);
// DB Abfrage userPasswort
$datenUser = mysql_query("SELECT `userID`, `userPassword`, `userActive`, `userRights` FROM `usertab` WHERE `userEmail` = '$email'");
$datenUserTab = mysql_fetch_array($datenUser, MYSQL_NUM);
// Prüfen, ob Passwort mit E-Mail übereinstimmt
if(crypt($passwort, $datenUserTab[1]) == $datenUserTab[1]){
	// Prüfen, ob Account schon aktiviert
	if($datenUserTab[2] == 1){
		// Flag (userDelete) auf 0 setzen
		$updateUser = mysql_query("UPDATE `usertab` SET `userDelete` = 0 WHERE `userEmail` = '$email'");
		// Wenn sich Admin einloggt
		if ($datenUserTab[3] == 0){
			$datenClub = mysql_query("SELECT `clubName`, `clubAdminFirstName`, `clubAdminSecondName`, `clubID` FROM `clubtab` WHERE `userID` = '$datenUserTab[0]'");
			$datenClubTab = mysql_fetch_array($datenClub, MYSQL_NUM);
			$userID = $datenUserTab[0];
			$clubName = $datenClubTab[0];
			$vorname = $datenClubTab[1];
			$nachname = $datenClubTab[2];
			$clubID = $datenClubTab[3];
		}
		// Wenn sich Trainer einloggt
		if ($datenUserTab[3] == 1){
			// Trainerdaten abfragen
			$datenCoach = mysql_query("SELECT `coachID`, `clubID`, `coachFirstName`, `coachSecondName` FROM `coachtab` WHERE `userID` = '$datenUserTab[0]'");
			$datenCoachTab = mysql_fetch_array($datenCoach, MYSQL_NUM);
			// Vereinsname abfragen
			$datenClub = mysql_query("SELECT `clubName` FROM `clubtab` WHERE `clubID` = '$datenCoachTab[1]'");
			$datenClubTab = mysql_fetch_array($datenClub, MYSQL_NUM);
			$userID = $datenUserTab[0];
			$clubName = $datenClubTab[0];
			$clubID = $datenCoachTab[1];
			$vorname = $datenCoachTab[2];
			$nachname = $datenCoachTab[3];
			$_SESSION['coachID'] = $datenCoachTab[0];
		}
		// Wenn sich Spieler einloggt
		if ($datenUserTab[3] == 2){
			// Spielerdaten abfragen
			$datenPlayer = mysql_query("SELECT `playerID`, `teamID`, `clubID`, `playerFirstName`, `playerSecondName` FROM `playertab` WHERE `userID` = '$datenUserTab[0]'");
			$datenPlayerTab = mysql_fetch_array($datenPlayer, MYSQL_NUM);
			// Vereinsname abfragen
			$datenClub = mysql_query("SELECT `clubName` FROM `clubtab` WHERE `clubID` = '$datenPlayerTab[2]'");
			$datenClubTab = mysql_fetch_array($datenClub, MYSQL_NUM);
			// Teamname abfragen
			$datenTeam = mysql_query("SELECT `teamName`, `teamTypeID` FROM `teamtab` WHERE `teamID` = '$datenPlayerTab[1]'");
			$datenTeamTab = mysql_fetch_array($datenTeam, MYSQL_NUM);
			$teamType = convertTeamTypeID($datenTeamTab[1]);
			// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
			if ($datenTeamTab[0] != -1)
				$teamBez = $teamType . " (" . $datenTeamTab[0] . ")";
			else 
				$teamBez = $teamType;
			$userID = $datenUserTab[0];
			$clubName = $datenClubTab[0];
			$clubID = $datenPlayerTab[2];
			$vorname = $datenPlayerTab[3];
			$nachname = $datenPlayerTab[4];
			$_SESSION['playerID'] = $datenPlayerTab[0];
			$_SESSION['teamID'] = $datenPlayerTab[1];
			$_SESSION['teamBez'] = $teamBez;
		}
		// Sessions anhand von dessen Rechten setzen
		// Alle: clubName, vorname, nachname, clubID, email, userID
		// Trainer: coachID
		// Spieler: playerID, teamID
		$_SESSION['email'] = $email;
		$_SESSION['userID'] = $userID;
		$_SESSION['vereinsname'] = $clubName;
		$_SESSION['vorname'] = $vorname;
		$_SESSION['nachname'] = $nachname;
		$_SESSION['clubID'] = $clubID;
		$_SESSION['rights'] = $datenUserTab[3];
		echo 'ok';
	}
	else
		echo 'nichtAktiviert';
}
else
	echo 'pwFalsch';

function convertTeamTypeID($teamTypeID){
	$teamType = "";
	// TeamID durch Namen ersetzen
	switch ($teamTypeID) {
		case 1:
			$teamType = "1. Mannschaft";
			break;
		case 2:
			$teamType = "2. Mannschaft";
			break;
		case 3:
			$teamType = "A-Junioren";
			break;
		case 4:
			$teamType = "B-Junioren";
			break;
		case 5:
			$teamType = "C-Junioren";
			break;
		case 6:
			$teamType = "D-Junioren";
			break;
		case 7:
			$teamType = "E-Junioren";
			break;
		case 8:
			$teamType = "F-Junioren";
			break;
		case 9:
			$teamType = "Hobby";
			break;
		default:
			$teamType = "Fehler";
			break;
	}
	return $teamType;
}
?>