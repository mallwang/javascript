<?php session_start ();
include ('inc/base.inc.php');
require_once ('inc/DB.php');

// Template einlesen
$template = get_file_as_string($base['template']);

##################################
### START VARIABLEN ÜBERPRÜFEN ###
##################################

// GET-Variablen initialisieren
$p1 = "";
$p2 = "";
$p3 = "";

// GET-Variablen prüfen und zuweisen
if (isset ( $_GET ['p1'] )) {
	$p1 = rawurldecode ( $_GET ['p1'] );
	if (isset ( $_GET ['p2'] )) {
		$p2 = rawurldecode ( $_GET ['p2'] );
		if (isset ( $_GET ['p3'] ))
			$p3 = rawurldecode ( $_GET ['p3'] );
		else
			$p3 = '';
	} else
		$p2 = '';
} else
	$p1 = '';

// Wenn $p1 gesetzt
if ($p1 != '') {
	// Wenn $p2 gesetzt
	if ($p2 != '') {
		// Wenn $p3 gesetzt
		if ($p3 != '') {
			// Wenn Bestätigungslink aufgerufen (manageyourteam.de/Bestaetigung/EMail/Schlüssel)
			if ($p1 == "Bestaetigung") {
				// DB Verbindung aufbauen für escape_string
				$db = new DB();
				$db->getConnection();
				// Variablen Übernehmen
				$email = mysql_real_escape_string ( $p2 );
				$key = mysql_real_escape_string ( $p3 );
				$base = createBestaetigung($email, $key, $base);
			}
			else{
				// Wenn man bereits eingeloggt ist erscheint Fehlerseite
				if (isset($_SESSION['email']))
					$base = createError404($p1, $base);
				// Wenn man nicht eingeloggt ist, erscheint öffentliche Fehlerseite
				else
					$base = createError404ForGuest($p1, $base);
			}
		}
		// Wenn $p3 nicht gesetzt
		else {
			// Wenn man bereits eingeloggt ist erscheint Fehlerseite
			if (isset($_SESSION['email']))
				$base = createError404($p1, $base);
			// Wenn man nicht eingeloggt ist, erscheint öffentliche Fehlerseite
			else
				$base = createError404ForGuest($p1, $base);
		}
	}
	// Wenn $p2 nicht gesetzt
	else {
		// Unterscheide nur $p1
		if ($p1 == "Home")
			$base = createHome ($p1, $base);
		elseif ($p1 == "Mannschaft")
			$base = createMannschaft ($p1, $base);
		elseif ($p1 == "Trainer")
			$base = createTrainer ($p1, $base);
		elseif ($p1 == "Spieler")
			$base = createSpieler ($p1, $base);
		elseif ($p1 == "Anwesenheit")
			$base = createAnwesenheit ($p1, $base);
		elseif ($p1 == "Aufstellung")
			$base = createAufstellung($p1, $base);
		elseif ($p1 == "Termin_Uebersicht")
			$base = createKalender ($p1, $base, "Uebersicht");
		elseif ($p1 == "Termin_Training")
			$base = createKalender ($p1, $base, "Training");
		elseif ($p1 == "Termin_Spiel")
			$base = createKalender ($p1, $base, "Spiel");
		elseif ($p1 == "Termin_Turnier")
			$base = createKalender ($p1, $base, "Turnier");
		elseif ($p1 == "Spielplan")
			$base = createSpielplan ($p1, $base);
		elseif ($p1 == "Turnierplan")
			$base = createTurnierplan ($p1, $base);
		else if ($p1 == "Verein")
			$base = createVerein ($p1, $base);
		elseif ($p1 == "Profil")
			$base = createProfil ($p1, $base);
		elseif ($p1 == "Logout")
			createLogout();
		else{
			// Wenn man bereits eingeloggt ist erscheint Fehlerseite
			if (isset($_SESSION['email']))
				$base = createError404($p1, $base);
			// Wenn man nicht eingeloggt ist, erscheint öffentliche Vereinsseite
			else
				$base = createVereinsinfo($p1, $base);
		}
	}
}
// Wenn $p1 nicht gesetzt
else {
	// Wenn Session aktiv -> Weiterleitung auf "Home"
	if (isset ( $_SESSION ['email'] ))
		$base = createHome("Home", $base);
	// Wenn keine Session aktiv -> normale Startseite anzeigen
	else
		$base['nav'] = createNavForGuest();
}

#################################
### ENDE VARIABLEN ÜBERPRÜFEN ###
#################################

###################################
### START SEITNINHALT ERSTELLEN ###
###################################

function createBestaetigung($email, $key, $base){
	// Verbindung aufbauen
	$db = new DB();
	$con = $db->getConnection();
	// Wenn User eingeloggt ist
	if(isset($_SESSION ['email'])){
		// Meldung und Abbruch
		$base ['msg'] .= "<div id='message' align='center' style='padding-top: 54px;'><div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Sie haben ihr Benutzerprofil bereits aktiviert!</div></div></div>";
		$base = createHome ( $p1, $base );
	}
	// Wenn User nicht eingeloggt ist
	else{
		// DB Abfrage ob verein schon aktiviert
		$datenUser = mysql_query ( "SELECT `userKey`, `userActive` FROM `usertab` WHERE `userEmail` = '$email'" );
		$datenUserTab = mysql_fetch_array ( $datenUser, MYSQL_NUM );
		// Wenn User schon aktiviert ist
		if ($datenUserTab[1] == 1)
			$base['msg'] .= "<div id='message' align='center' style='padding-top: 54px;'><div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Sie haben ihr Benutzerprofil bereits aktiviert!</div></div></div>";
		else {
			// Wenn Key übereinstimmt, User aktivieren
			if ($key == $datenUserTab[0]) {
				// User aktivieren
				$activation = mysql_query("UPDATE `usertab` SET `userActive` = '1' WHERE `userEmail` = '$email'" );
				// Wenn Account erfolgreich aktiviert wurde
				if ($activation)
					$base['msg'] .= "<div id='message' align='center' style='padding-top: 54px;'><div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Ihr Benutzerprofil wurde erfolgreich aktiviert!<br>Sie können sich nun einloggen.</div></div></div>";
				// Wenn Account nicht aktiviert werden konnte
				else
					$base['msg'] .= "<div id='message' align='center' style='padding-top: 54px;'><div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Ihr Benutzerprofil konnte leider nicht aktiviert werden!<br>Bitte wenden Sie sich diesbezüglich an den Support!</div></div></div>";
			}
			// Wenn Key nicht übereinstimmt
			else
				$base['msg'] .= "<div id='message' align='center' style='padding-top: 54px;'><div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Der angegebene Schlüssel ist fehlerhaft. Bitte fordern Sie diesen erneut an!</div></div></div>";
		}
		// Navigation erstellen
		$base['nav'] = createNavForBest();
	}
	return $base;
}

// Erstellen der Error404-Seite
function createError404($p1, $base){
	// Navigationsleiste erstellen
	$base['nav'] = createNav($_SESSION['rights'], $p1);
	// Seiteninhalt erstellen
	$main = '<div><img src="../../../../img/404.png"><br><h2>Die von Ihnen Angeforderte Seite existiert nicht!</h2></div>';
	// Seitentitel erstellen
	$base['title'] = 'Error 404';
	// Sidebar und Clubinfo leeren
	$base['sidebar'] = '';
	$base['clubinfo'] = '';
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = '<div style="width: 70%;">' . $main . '</div>';
	// Zurückgeben des Arrays
	return $base;
}

// Erstellen der öffentlichen Error404-Seite
function createError404ForGuest($p1, $base){
	// Navigationsleiste erstellen
	$base['nav'] = createNavForGuest();
	// Seiteninhalt erstellen
	$main = '<div><img src="../../../../img/404.png"><br><h2>Die von Ihnen Angeforderte Seite existiert nicht!</h2></div>';
	// Seitentitel erstellen
	$base['title'] = 'Error 404';
	// Sidebar und Clubinfo leeren
	$base['sidebar'] = '';
	$base['clubinfo'] = '';
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = '<div style="width: 70%;">' . $main . '</div>';
	// Zurückgeben des Arrays
	return $base;
}

// Erstellen der Home-Seite
function createHome($p1, $base) {
	// Navigationsleiste erstellen
	$base['nav'] = createNav($_SESSION['rights'], $p1);
	// Kalender erstellen
	$calendar = file_get_contents("calendar.php");
	// Admin und Trainer sieht alle Termine
	if ($_SESSION['rights'] == 0 || $_SESSION['rights'] == 1)
		// Seiteninhalt erstellen
		$main = '<div style="display: inline;"><strong>MONATSÜBERSICHT</strong></div><br><br><div>' . $calendar . '</div>';
	// Spieler sieht nur Termine seiner Mannschaft
	if ($_SESSION['rights'] == 2){
		// DB-Verbindung erstellen
		$db = new DB();
		$con = $db->getConnection();
		// Aktuelle Mannschaft abfragen
		$allTeams = $db->getTeamByID($_SESSION['teamID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false)
			// Seiteninhalt erstellen
			$main =	'<div style="display: inline;"><strong>MONATSÜBERSICHT</strong></div><br><br><div>' . $calendar . '</div>';
		// Wenn Spieler keiner Mannschaft zugeordnet ist
		else
			$main = 'Sie sind keiner Mannschaft zugeordnet und sehen deshalb auch keine Monatsübersicht.';
	}
	// Seitentitel erstellen
	$base['title'] = 'Home';
	// Zusätzliches JavaScript
	$base['js2'] = '<script type="text/javascript">var USER_RIGHTS = ' . $_SESSION['rights'] . '</script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/fullcalendar.js"></script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/alleTermine.js"></script>';
	// Sidebar und Clubinfo leeren
	$base['sidebar'] = '';
	$base['clubinfo'] = '';
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = '<div style="width: 70%;">' . $main . '</div>';
	// Zurückgeben des Arrays
	return $base;
}

// Erstellen der Mannschaft-Seite
function createMannschaft($p1, $base) {
	// Navigationsleiste erstellen
	$base['nav'] = createNav($_SESSION['rights'], $p1);
	// Seiteninhalt erstellen anhand von Rechten
	$main = createContentTeam($_SESSION['rights']);
	// Seitentitel erstellen
	$base['title'] = 'Mannschaft';
	// Zusätzliches JavaScript (Dokumentende)
	$base['js2'] = '<script type="text/javascript" src="../../../../js/mannschaftsuebersicht.js"></script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/datatables.js"></script>';
	$base['css'] .= '<link rel="stylesheet" href="../../../../css/datatables.min.css">';
	// Sidebar und Clubinfo leeren
	$base['sidebar'] = '';
	$base['clubinfo'] = '';
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = '<div style="width: 100%;"><br>' . $main . '</div>';
	// Zurückgeben des Arrays
	return $base;
}

// Mannschaft erstellen
function createContentTeam($rights) {
	$return = "";
	// Verbindung aufbauen
	$db = new DB ();
	// Unterscheidung anhand der Rechte
	if ($rights == 0){
		// Administrator sieht alle Mannschaften und kann auch eine neue erstellen
		$return = 	'<!-- BUTTON ZUM ERSTELLEN EINER NEUEN MANNSCHAFT -->
						<form id="newTeam">
							<button onclick="showDialogCreateNewTeam(' . $_SESSION['clubID'] . ')" id="btnNewTeam" type="button" class="button place-right bg-white">Neue Mannschaft erstellen</button>
						</form>
						<br>';
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByClub($_SESSION['clubID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			// Anfang der Tabelle erstellen
			$return .= 	'<div class="alleTeams"><table id="myTable" class="display" cellspacing="0" width="100%"><thead><tr><th>Team</th><th>Trainer</th><th>Aktionen</th></tr></thead><tfoot><tr><th>Team</th><th>Trainer</th><th>Aktionen</th></tr></tfoot><tbody>';
			// Tabelleninhalt erstellen
			$i = 0;
			while ($i < count($allTeams)){
				$teamType = convertTeamTypeID($allTeams[$i]['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($allTeams[$i]['teamName'] != -1)
					$teamBez = $teamType . " (" . $allTeams[$i]['teamName'] . ")";
				else
					$teamBez = $teamType;
				// Trainer der Mannschaft abfragen
				$coach = $db->getCoachByTeam($allTeams[$i]['teamID']);
				// Für jeden Eintrag ein Optionsfeld erstellen
				$return .= 	'<tr>
								<td>' . $teamBez . '</td>
								<td class="right">' . $coach[0]['coachFirstName'] . ' ' . $coach[0]['coachSecondName'] . '</td>
								<td class="right">
									<button onclick="editTeam(' . $allTeams[$i]['teamID'] . ')" style="background-color: transparent;"><i title="Mannschaft bearbeiten" class="icon-pencil"></i></button>
									<button onclick="deleteTeam(' . $allTeams[$i]['teamID'] . ')" style="background-color: transparent;"><i title="Mannschaft löschen" class="icon-remove"></i></button>
								</td>
							</tr>';
				$i++;
			}
			// Ende der Tabelle erstellen
			$return .= '</tbody></table></div>';
		}
		// Wenn keine Mannschaft existiert
		else
			$return .= "Es existiert bisher noch keine Mannschaft";
	}
	elseif ($rights == 1){
		$allCoaches = $db->getCoachByID($_SESSION['coachID']);
		// Wenn der Trainer existiert
		if ($allCoaches != false){
			// Wenn ein Team zugewiesen ist
			if(isset($allCoaches['team'][0]	)){
				// Mannschaft zuweisen
				$teamID = $allCoaches['team'][0];
				// Mannschaft abfragen
				$allTeams = $db->getTeamByID($teamID);
				// Wenn Mannschaften existieren
				if ($allTeams != false){
					// Alle Spieler abfragen
					$allPlayer = $db->getPlayerByTeam($teamID);
					// Wenn Spieler existieren
					if ($allPlayer != false)
						$numberOfPlayers = count($allPlayer);
					// Wenn keine Spieler in der Mannschaft existieren
					else
						$numberOfPlayers = 0;
					// Anfang der Tabelle erstellen
					$return .=	'<div class="alleTeams">
									<table class="table striped hovered">
										<thead>
											<tr>
												<th class="text-left">Team</th>
												<th class="text-left">Anzahl Spieler</th>
											</tr>
										</thead>
									<tbody>';
					$teamType = convertTeamTypeID($allTeams['teamTypeID']);
					// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
					if ($allTeams['teamName'] != -1)
						$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
					else
						$teamBez = $teamType;
					// Für jeden Eintrag ein Optionsfeld erstellen
					$return .= 	'<tr><td>' . $teamType . '</td><td class="right">' . $numberOfPlayers . '</td></tr>';
					// Ende der Tabelle erstellen
					$return .= '</tbody></table></div>';
				}
			}
			// Wenn keine Mannschaft existiert
			else
				$return = "Sie trainieren aktuell keine Mannschaft.";
		}
		// Wenn kein Trainer existiert
		else
			$return = "Es existiert noch kein Trainer";
	}
	elseif ($rights == 2){
		// Spieler sieht nur seine Mannschaft
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByID($_SESSION['teamID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			// Alle Spieler abfragen
			$allPlayer = $db->getPlayerByTeam($_SESSION['teamID']);
			// Wenn Spieler existieren
			if ($allPlayer != false)
				$numberOfPlayers = count($allPlayer);
			// Wenn keine Spieler in der Mannschaft existieren
			else
				$numberOfPlayers = 0;
			// Anfang der Tabelle erstellen
			$return .= 	'<div class="alleTeams"><table class="table striped hovered"><thead><tr><th class="text-left">Team</th><th class="text-left">Trainer</th><th class="text-left">Anzahl Spieler</th></tr></thead><tbody>';
			// Tabelleninhalt erstellen
			$teamType = convertTeamTypeID($allTeams['teamTypeID']);
			// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
			if ($allTeams['teamName'] != -1)
				$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
			else
				$teamBez = $teamType;
			// Trainer der Mannschaft abfragen
			$coach = $db->getCoachByTeam($allTeams['teamID']);
			// Für jeden Eintrag ein Optionsfeld erstellen
			$return .= 	'<tr><td>' . $teamBez . '</td><td class="right">' . $coach[0]['coachFirstName'] . ' ' . $coach[0]['coachSecondName'] . '</td><td class="right">' . $numberOfPlayers . '</td></tr>';
			// Ende der Tabelle erstellen
			$return .= '</tbody><tfoot></tfoot></table></div>';
		}
		// Wenn keine Mannschaft existiert
		else
			$return = "Sie sind keiner Mannschaft zugeordnet.";
	}
	return $return;
}

// Erstellen der Trainer-Seite (nur von Admin sichtbar)
function createTrainer($p1, $base) {
	// Wenn keine Administratorenrechte vorliegen, Fehlerseite erstellen
	if ($_SESSION['rights'] != 0)
		$base = createError404($p1, $base);
	else{
		// Navigationsleiste erstellen
		$base['nav'] = createNav($_SESSION['rights'], $p1);
		// Seiteninhalt erstellen anhand von Rechten
		$main = createContentCoach();
		// Seitentitel erstellen
		$base['title'] = 'Trainer';
		// Zusätzliches JavaScript (Dokumentende)
		$base['js2'] = '<script type="text/javascript" src="../../../../js/traineruebersicht.js"></script>';
		$base['js2'] .= '<script type="text/javascript" src="../../../../js/datatables.js"></script>';
		$base['css'] .= '<link rel="stylesheet" href="../../../../css/datatables.min.css">';
		// Sidebar und Clubinfo leeren
		$base['sidebar'] = '';
		$base['clubinfo'] = '';
		// Footer löschen
		$base['footer'] = '';
		// Seiteninhalt zuweisen
		$base['main'] = '<div style="width: 100%;"><br>' . $main . '</div>';
	}
	// Zurückgeben des Arrays
	return $base;
}

// Trainer erstellen
function createContentCoach() {
	$return = "";
	// Verbindung aufbauen
	$db = new DB();
	$allCoaches = $db->getCoachByClub($_SESSION['clubID']);
	// Administrator sieht alle Trainer und kann auch einen neuen erstellen
	$return = '<form id="newCoach"><button onclick="showDialogCreateNewCoach()" id="btnNewCoach" type="button" class="button place-right bg-white">Neuen Trainer erstellen</button></form><br>';
	// Wenn mindestens ein Trainer existiert
	if ($allCoaches != false){
		// Anfang der Tabelle erstellen
		$return .= 	'<div class="alleTrainer"><table id="myTable" class="display" cellspacing="0" width="100%"><thead><tr><th>Name</th><th>Team</th><th>Aktionen</th></tr></thead><tfoot><tr><th>Name</th><th>Team</th><th>Aktionen</th></tr></tfoot><tbody>';
		// Tabelleninhalt erstellen
		$i = 0;
		while ($i < count($allCoaches)){
			if (isset($allCoaches[$i]['team'][0])){
				$team = $db->getTeamByID($allCoaches[$i]['team'][0]);
				$teamType = convertTeamTypeID($team['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($team['teamName'] != -1)
					$teamBez = $teamType . " (" . $team['teamName'] . ")";
				else
					$teamBez = $teamType;
			}
			else
				$teamBez = "Keine Mannschaft";
			// Für jeden Eintrag ein Optionsfeld erstellen
			$return .=	'<tr>
							<td>' . $allCoaches[$i]['coachFirstName'] . ' ' . $allCoaches[$i]['coachSecondName'] . '</td>
							<td class="right">' . $teamBez . '</td>
							<td class="right">
								<button onclick="editCoach(' . $allCoaches[$i]['coachID'] . ')" style="background-color: transparent;">
									<i title="Trainer bearbeiten" class="icon-pencil"></i>
								</button>
								<button onclick="deleteCoach(' . $allCoaches[$i]['coachID'] . ')" style="background-color: transparent;">
									<i title="Trainer löschen" class="icon-remove"></i>
								</button>
							</td>
						</tr>';
			$i ++;
		}
		// Ende der Tabelle erstellen
		$return .= '</tbody></table></div>';
	}
	// Wenn kein Trainer existiert
	else
		$return .= "Es existiert bisher noch kein Trainer";
	return $return;
}

// Erstellen der Spieler-Seite
function createSpieler($p1, $base) {
	// Navigationsleiste erstellen
	$base['nav'] = createNav($_SESSION['rights'], $p1);
	// Seiteninhalt erstellen anhand von Rechten
	$main = createContentPlayer($_SESSION['rights']);
	// Seitentitel erstellen
	$base['title'] = 'Spieler';
	// Zusätzliches JavaScript (Dokumentende)
	$base['js2'] = '<script type="text/javascript">var USER_RIGHTS = ' . $_SESSION['rights'] . '</script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/spieleruebersicht.js"></script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/datatables.js"></script>';
	$base['css'] .= '<link rel="stylesheet" href="../../../../css/datatables.min.css">';
	// Sidebar und Clubinfo leeren
	$base['sidebar'] = '';
	$base['clubinfo'] = '';
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = '<div style="width: 100%;"><br>' . $main . '</div>';
	// Zurückgeben des Arrays
	return $base;
}

// Spieler erstellen
function createContentPlayer($rights){
	$return = "";
	// Verbindung aufbauen
	$db = new DB ();
	// Unterscheidung anhand der Rechte
	if ($rights == 0){
		// Administrator sieht alle Spieler des Vereins und kann diese auch bearbeiten / neu erstellen
		$return = createBtnPlayer();
		// Alle Spieler abfragen
		$allPlayer = $db->getPlayerByClub($_SESSION['clubID']);
		// Wenn Spieler existieren
		if ($allPlayer != false){
			// Anfang der Tabelle erstellen
			$return .= 	'<div class="alleSpieler"><table id="myTable" class="display" cellspacing="0" width="100%"><thead><tr><th>Name</th><th>Mannschaft</th><th>Aktionen</th></tr></thead><tfoot><tr><th>Name</th><th>Mannschaft</th><th>Aktionen</th></tr></tfoot><tbody>';
			// Tabelleninhalt erstellen
			$i = 0;
			while ($i < count($allPlayer)){
				$team = $db->getTeamByID($allPlayer[$i]['teamID']);
				$teamType = convertTeamTypeID($team['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($team['teamName'] != -1)
					$teamBez = $teamType . " (" . $team['teamName'] . ")";
				else
					$teamBez = $teamType;
				// Für jeden Eintrag ein Optionsfeld erstellen
				$return .= 	'<tr>
									<td>' . $allPlayer[$i]['playerFirstName'] . ' ' . $allPlayer[$i]['playerSecondName'] . '</td>
									<td class="right">' . $teamBez . '</td>
									<td class="right">
										<button onclick="editPlayer(' . $allPlayer[$i]['playerID'] . ')" style="background-color: transparent;"><i title="Spieler bearbeiten" class="icon-pencil"></i></button>
										<button onclick="deletePlayer(' . $allPlayer[$i]['playerID'] . ')" style="background-color: transparent;"><i title="Spieler löschen" class="icon-remove"></i></button>
									</td>
								</tr>';
				$i++;
			}
			// Ende der Tabelle erstellen
			$return .= '</tbody></table></div>';
		}
		// Wenn kein Spieler existiert
		else
			$return .= "Es existiert bisher noch kein Spieler";
	}
	// User == Trainer
	elseif ($rights == 1){
		// Trainer sieht alle Spieler seiner Mannschaften und kann diese auch bearbeiten / neu erstellen
		$return = createBtnPlayer();
		// Alle Trainer abfragen
		$allCoaches = $db->getCoachByID($_SESSION['coachID']);
		// Wenn ein Team zugewiesen ist
		if(isset($allCoaches['team'])){
			// Wenn ein Team zugewiesen ist
			if(isset($allCoaches['team'][0]	)){
				// Mannschaft zuweisen
				$teamID = $allCoaches['team'][0];
				$allPlayer = $db->getPlayerByTeam($teamID);
				// Wenn Spieler existieren
				if ($allPlayer != false){
					// Anfang der Tabelle erstellen
					$return .= 	'<div class="alleSpieler"><table id="myTable" class="display" cellspacing="0" width="100%"><thead><tr><th>Name</th><th>Mannschaft</th><th>Aktionen</th></tr></thead><tfoot><tr><th>Name</th><th>Mannschaft</th><th>Aktionen</th></tr></tfoot><tbody>';
					// Tabelleninhalt erstellen
					$i = 0;
					while ($i < count($allPlayer)){
						$team = $db->getTeamByID($allPlayer[$i]['teamID']);
						$teamType = convertTeamTypeID($team['teamTypeID']);
						// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
						if ($team['teamName'] != -1)
							$teamBez = $teamType . " (" . $team['teamName'] . ")";
						else
							$teamBez = $teamType;
						// Für jeden Eintrag ein Optionsfeld erstellen
						$return .= 	'<tr>
										<td>' . $allPlayer[$i]['playerFirstName'] . ' ' . $allPlayer[$i]['playerSecondName'] . '</td>
										<td class="right">' . $teamBez . '</td>
										<td class="right">
											<button onclick="editPlayer(' . $allPlayer[$i]['playerID'] . ')" style="background-color: transparent;"><i title="Spieler bearbeiten" class="icon-pencil"></i></button>
											<button onclick="deletePlayer(' . $allPlayer[$i]['playerID'] . ')" style="background-color: transparent;"><i title="Spieler löschen" class="icon-remove"></i></button>
										</td>
									</tr>';
						$i++;
					}
					// Ende der Tabelle erstellen
					$return .= '</tbody><tfoot></tfoot></table></div>';
				}
				// Wenn kein Spieler existiert
				else
					$return .= "Es existiert bisher noch kein Spieler";
			}
			// Wenn Trainer kein Team hat
			else
				$return = "Sie trainieren aktuell keine Mannschaft.";
		}
	}
	// User == Spieler
	elseif ($rights == 2){
		// Aktuelle Mannschaft abfragen
		$allTeams = $db->getTeamByID($_SESSION['teamID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			// Spieler sieht nur seine Mitspieler
			$allPlayer = $db->getPlayerByTeam($_SESSION['teamID']);
			// Wenn Spieler existieren
			if ($allPlayer != false){
				// Anfang der Tabelle erstellen
				$return .= 	'<div class="alleSpieler"><table id="myTable" class="display" cellspacing="0" width="100%"><thead><tr><th>Name</th><th>Mannschaft</th><th>Geburtsdatum</th></tr></thead><tfoot><tr><th>Name</th><th>Mannschaft</th><th>Geburtsdatum</th></tr></tfoot><tbody>';
				// Tabelleninhalt erstellen
				$i = 0;
				while ($i < count($allPlayer)){
					$team = $db->getTeamByID($allPlayer[$i]['teamID']);
					$teamType = convertTeamTypeID($team['teamTypeID']);
					// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
					if ($team['teamName'] != -1)
						$teamBez = $teamType . " (" . $team['teamName'] . ")";
					else
						$teamBez = $teamType;
					// Für jeden Eintrag ein Optionsfeld erstellen
					$return .= 	'<tr>
										<td>' . $allPlayer[$i]['playerFirstName'] . ' ' . $allPlayer[$i]['playerSecondName'] . '</td>
										<td class="right">' . $teamBez . '</td>
										<td class="right">' . convertDate($allPlayer[$i]['playerBirthdate']) . '</td>
									</tr>';
					$i++;
				}
				// Ende der Tabelle erstellen
				$return .= '</tbody></table></div>';
			}
			// Wenn kein Spieler existiert
			else
				$return = "Es existiert bisher noch kein Spieler";
		}
		// Wenn der Spieler keiner Mannschaft zugeordnet ist
		else
			$return = "Sie sind keiner Mannschaft zugeordnet und besitzen deshalb keine Mitspieler.";
	}
	return $return;
}

// Erstellen der Anwesenheit-Seite
function createAnwesenheit($p1, $base) {
	// Wenn keine Administratoren- oder Trainerrechte vorliegen, Fehlerseite erstellen
	if ($_SESSION['rights'] != 0 && $_SESSION['rights'] != 1)
		$base = createError404($p1, $base);
	else{
		// Navigationsleiste erstellen
		$base['nav'] = createNav($_SESSION['rights'], $p1);
		// Seiteninhalt erstellen anhand von Rechten
		$appID=array("1","2");
		$main = createContentAnwesenheit($_SESSION['rights']);
		// Seitentitel erstellen
		$base['title'] = 'Anwesenheit';
		// Zusätzliches JavaScript (Dokumentende)
		$base['js2'] = '<script type="text/javascript">var USER_RIGHTS = ' . $_SESSION['rights'] . '</script>';
		$base['js2'] .= '<script type="text/javascript" src="../../../../js/anwesenheit.js"></script>';
		$base['js2'] .= '<script type="text/javascript" src="../../../../js/datatables.js"></script>';
		$base['css'] .= '<link rel="stylesheet" href="../../../../css/datatables.min.css">';
		// Sidebar und Clubinfo leeren
		$base['sidebar'] = '';
		$base['clubinfo'] = '';
		// Footer löschen
		$base['footer'] = '';
		// Seiteninhalt zuweisen
		$base['main'] = '<div style="width: 90%;"><br>' . $main . '</div>';
	}
	// Zurückgeben des Arrays
	return $base;
}

// Anwesenheit erstellen
function createContentAnwesenheit($rights){
	$return="";
	// Verbindung aufbauen
	$db = new DB();
	$con = $db->getConnection();
	// Admin sieht alle Mannschaften
	if ($rights == 0){
		$allTeams = $db->getTeamByClub($_SESSION['clubID']);
		$i = 0;
		$str_option = "";
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			$str_filler = "";
			// Selectfeld erstellen
			$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
			$i = 0;
			while ($i < count($allTeams)){
				$teamType = convertTeamTypeID($allTeams[$i]['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($allTeams[$i]['teamName'] != -1)
					$teamBez = $teamType . " (" . $allTeams[$i]['teamName'] . ")";
				else
					$teamBez = $teamType;
				$str_filler .= '<option value="' . $allTeams[$i]['teamID'] . '">' . $teamBez . '</option>';
				$i++;
			}
			$str_filler .= '</select></div>';
		}
		// Wenn keine Mannschaft existiert
		else
			$str_filler = 	'<button id="teamID" style="width: 100%; height: 31px; margin-top: 1px;" onclick="showDialogCreateNewTeam(' . $_SESSION['clubID'] . ')" id="btnNewTeam" type="button" class="button bg-gray fg-white">Neue Mannschaft erstellen</button>';
		// Letzte Woche und Heute berechnen
		$today = date("Y-m-d");
		$lastWeek = date("Y-m-d", strtotime("-7 days"));
		$return = '<form class="fg-black">
					<div class="span2">
						<label>Von</label>
						<div class="input-control text" data-role="datepicker"
		    				data-date="' . $lastWeek . '"
		    				data-format="dd.mm.yyyy"
		   					data-position="bottom"
		    				data-effect="slide">
		    				<input id="dateFrom" type="text">
						</div>
					</div>
					<div class="span2">
						<label>Bis</label>
						<div class="input-control text" data-role="datepicker"
		    				data-date="' . $today . '"
		    				data-format="dd.mm.yyyy"
		   					data-position="bottom"
		    				data-effect="slide">
		    				<input id="dateTo" type="text">
						</div>
					</div>
					<div class="span3">
						<label>Mannschaft</label>'
		    						. $str_filler . '
					</div>
					<div class="span2">
						<label>Terminart</label>
						<div class="input-control select">
							<select id="appointmentType" size="1">
								<option value="1">Training</option>
								<option value="2">Spiel</option>
								<option value="3">Turnier</option>
							</select>
						</div>
					</div>
					<div class="span2">
						<div class="form-actions">
							<br>
							<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent('.$rights.')">Anzeigen</button>&nbsp;
							<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
							<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
						</div>
					</div>
					<div class="span2">
						<!-- BUTTON ZUM ERSTELLEN EINES NEUEN TERMINS -->
						<form id="newAppointment">
							<button style="width: 100%; margin-top: 21px;" onclick="showDialogCreateNewAppointment()" id="btnNewAppointment" type="button" class="button place-right bg-white">Neuen Termin erstellen</button>
						</form>
					</div>
				</form><br><br><br><br><br><div id="result"></div>';
	}
	// Trainer sieht nur seine Mannschaft
	else if ($rights == 1){
		//TRAINER
		$allCoaches = $db->getCoachByID($_SESSION['coachID']);
		// Wenn der Trainer existiert
		if ($allCoaches != false){
			// Wenn ein Team zugewiesen ist
			if(isset($allCoaches['team'][0]	)){
				// Mannschaft zuweisen
				$teamID = $allCoaches['team'][0];
				// Mannschaft abfragen
				$allTeams = $db->getTeamByID($teamID);
				// Wenn Mannschaften existieren
				if ($allTeams != false){
					$str_filler = "";
					// Selectfeld erstellen
					$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
					$teamType = convertTeamTypeID($allTeams['teamTypeID']);
					// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
					if ($allTeams['teamName'] != -1)
						$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
					else
						$teamBez = $teamType;
					$str_filler .= '<option value="' . $allTeams['teamID'] . '">' . $teamBez . '</option>';
					$str_filler .= '</select></div>';
				}
				// Letzte Woche und Heute berechnen
				$today = date("Y-m-d");
				$lastWeek = date("Y-m-d", strtotime("-7 days"));
				$return = '<form class="fg-black">
					<div class="span2">
						<label>Von</label>
						<div class="input-control text" data-role="datepicker"
		    				data-date="' . $lastWeek . '"
		    				data-format="dd.mm.yyyy"
		   					data-position="bottom"
		    				data-effect="slide">
		    				<input id="dateFrom" type="text">
						</div>
					</div>
					<div class="span2">
						<label>Bis</label>
						<div class="input-control text" data-role="datepicker"
		    				data-date="' . $today . '"
		    				data-format="dd.mm.yyyy"
		   					data-position="bottom"
		    				data-effect="slide">
		    				<input id="dateTo" type="text">
						</div>
					</div>
					<div class="span3">
						<label>Mannschaft</label>'
		    						. $str_filler . '
					</div>
					<div class="span2">
						<label>Terminart</label>
						<div class="input-control select">
							<select id="appointmentType" size="1">
								<option value="1">Training</option>
								<option value="2">Spiel</option>
								<option value="3">Turnier</option>
							</select>
						</div>
					</div>
					<div class="span2">
						<div class="form-actions">
							<br>
							<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent('.$rights.')">Anzeigen</button>&nbsp;
							<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
							<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
						</div>
					</div>
					<div class="span2">
						<!-- BUTTON ZUM ERSTELLEN EINES NEUEN TERMINS -->
						<form id="newAppointment">
							<button style="width: 100%; margin-top: 21px;" onclick="showDialogCreateNewAppointment()" id="btnNewAppointment" type="button" class="button place-right bg-white">Neuen Termin erstellen</button>
						</form>
					</div>
				</form><br><br><br><br><br><div id="result"></div>';
			}
			// Wenn Trainer kein Team zugewiesen wurde
			else
				$return = "Sie trainieren aktuell keine Mannschaft.";
		}
	}
	return $return;
}

// Erstellen der Statistik-Seite
function createAufstellung($p1, $base) {
	// Inhalt erstellen
	$nav = createNav ( $_SESSION['rights'], $p1 );
	// 	$main = "Statistik";
	$main = createContentAufstellungen();
	// Zuweisen des Arrays
	$base['title'] = 'Aufstellung';
	$base['css'] .= '<link rel="stylesheet" href="../../../../css/aufstellung.min.css">';
	$base['css'] .= '<link rel="stylesheet" href="../../../../css/jquery-ui.min.css">';
	$base['nav'] = $nav;
	$base['sidebar'] = '';
	// Alle Mannschaften abfragen
	$db = new DB();
	$db->getConnection();
	$strHidden1 = "";
	$strHidden2 = "";
	// Clubdaten anhand der Rechte anzeigen
	if ($_SESSION['rights'] == 0){
		$allTeams = $db->getTeamByClub($_SESSION['clubID']);
		$i = 0;
		$str_option = "";
		// Wenn mindestens ein Team existiert
		if ($allTeams != false){
			$str_option .= '<select id="setTeam" size="1"><option value="0">Bitte Mannschaft auswählen</option>';
			while ($i < count($allTeams)){
				$teamType = convertTeamTypeID($allTeams[$i]['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($allTeams[$i]['teamName'] != -1)
					$teamBez = $teamType . " (" . $allTeams[$i]['teamName'] . ")";
				else
					$teamBez = $teamType;
				$str_option .= "<option value='" . $allTeams[$i]['teamID'] . "'>" . $teamBez . "</option>";
				$i++;
			}
			$str_option .= '</select>';
		}
		// Wenn kein Team existiert
		else
			$str_option .= '<button id="btnNewTeam" type="button" onclick="showDialogCreateNewTeam(' . $_SESSION['clubID'] . ')" class="button bg-darkGreen fg-white">Neue Mannschaft erstellen</button>';
		$strHidden1 = '<li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Taktik</li><li><select id="setTaktik" size="1"><option value="1">4-3-3</option><option value="2">4-4-2</option><option value="3">3-4-3</option><option value="4">4-5-1</option><option value="5">5-3-2</option><option value="6">5-4-1</option><option value="7">3-5-2</option><option value="8">5-3-2</option><option value="9">5-4-1</option></select></li>';
		$strHidden2 = '<li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Speichern</li><li><button id="save" type="button" class="button bg-darkGreen fg-white">Aufstellung speichern</button><img id="waitSave" src="../../../../img/loading.gif" style="display: none;" /><div id="alertSave" style="display: none; font-size: 14px; margin-top: 8px;"></div></li><li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Drucken</li><li><button id="print" type="button" onclick="printdiv(&quot;aufstellung&quot;, &quot;teamReserve&quot;)" class="button bg-darkGreen fg-white">Aufstellung drucken</button></li>';
	}
	// Trainer sieht nur seine Mannschaft
	else if ($_SESSION ['rights'] == 1){
		$allCoaches = $db->getCoachByID($_SESSION['coachID']);
		// Wenn der Trainer existiert
		if ($allCoaches != false){
			// Wenn ein Team zugewiesen ist
			if(isset($allCoaches['team'][0]	)){
				// Mannschaft zuweisen
				$teamID = $allCoaches['team'][0];
				// Mannschaft abfragen
				$allTeams = $db->getTeamByID($teamID);
				// Wenn Mannschaften existieren
				if ($allTeams != false){
					$str_option = '<select id="setTeam" size="1"><option value="0">Bitte Mannschaft auswählen</option>';
					$teamType = convertTeamTypeID($allTeams['teamTypeID']);
					// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
					if ($allTeams['teamName'] != -1)
						$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
					else
						$teamBez = $teamType;
					$str_option .= "<option value='" . $allTeams['teamID'] . "'>" . $teamBez . "</option></select>";
				}
				$strHidden1 = '<li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Taktik</li><li><select id="setTaktik" size="1"><option value="1">4-3-3</option><option value="2">4-4-2</option><option value="3">3-4-3</option><option value="4">4-5-1</option><option value="5">5-3-2</option><option value="6">5-4-1</option><option value="7">3-5-2</option><option value="8">5-3-2</option><option value="9">5-4-1</option></select></li>';
				$strHidden2 ='<li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Speichern</li><li><button id="save" type="button" class="button bg-darkGreen fg-white">Aufstellung speichern</button><img id="waitSave" src="../../../../img/loading.gif" style="display: none;" /><div id="alertSave" style="display: none; font-size: 14px; margin-top: 8px;"></div></li><li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Drucken</li>								<li><button id="print" type="button" onclick="printdiv(&quot;aufstellung&quot;, &quot;teamReserve&quot;)" class="button bg-darkGreen fg-white">Aufstellung drucken</button></li>';
			}
			// Wenn Trainer kein Team zugewiesen ist
			else
				$str_option = "Sie trainieren aktuell keine Mannschaft.";
		}
	}
	// Spieler sieht nur seine Mannschaft
	else if ($_SESSION ['rights'] == 2){
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByID($_SESSION['teamID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			$str_option = '<select id="setTeam" size="1"><option value="-1">Bitte Mannschaft auswählen</option>';
			$teamType = convertTeamTypeID($allTeams['teamTypeID']);
			// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
			if ($allTeams['teamName'] != -1)
				$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
			else
				$teamBez = $teamType;
			$str_option .= "<option value='" . $allTeams['teamID'] . "'>" . $teamBez . "</option></select>";
		}
		// Wenn Spieler in keiner Mannschaft
		else
			$str_option = "Sie sind keiner Mannschaft zugeordnet.";
	}

	// Clubdaten anzeigen
	$base['clubinfo'] =	'<div class="span4">
								<nav class="sidebar bg-white">
									<ul>
										<li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Mannschaft</li>
										<li>' . $str_option . '</li>
										' . $strHidden1 . '
										<li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Spielerliste</li>
										<li>
											<img id="waitLoad" src="../../../../img/loading.gif" style="display: none;" />
											<div class="bg-darkGreen fg-white team ui-droppable" id="team" style="height: 75px;"></div>
										</li>
										<li class="title fg-darkGreen" style="font-weight: bold; font-size: 20px;">Reserve</li>
										<li><div class="bg-darkGreen fg-white teamReserve" id="teamReserve"></div></li>
										' . $strHidden2 . '
										<br><br>
									</ul>
								</nav>
							</div>';

	$main = '<div class="aufstellung" id="aufstellung" style="margin-top: -100px; margin-left: -100px;"></div>';
	$base['main'] = '<div class="span10 squadContent">' . $main . '</div>';
	$base['footer'] = '';
	// Zusätzliches JavaScript
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/aufstellung.js"></script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/jquery-ui-1.10.4.min.js"></script>';
	$base['css'] .= '<style>.squadContainer{width:887px;margin:0 auto;margin-top:8px;padding-left:150px;}.squadContent{background:url(/img/spielfeld.png) no-repeat transparent;height:570px;}</style>';
	// Zurückgeben des Arrays
	return $base;
}

// Aufstellung erstellen
function createContentAufstellungen(){
	$return = "";
	$return = '<form action="" method="post" style="display:inline">
      					<input type="hidden" name="saved" value="1">
                 			<div class="pos-abs" style="top:15px;left:-170px;">
		
          						<div class="teamList">
            						<div class="playerlist pos-rel" id="playerlist"></div>
              					</div>
		
								<div class="playerlistReserve" id="playerlistReserve"></div>
		
          						<div class="main" id="main">
            						<div class="save pos-rel" style="top:1px;">
              							<a href="#" id="save" name="save" style="text-decoration:none;">
                							<input type="button" value="Speichern" class="pos-rel buttonGreen" style="cursor:pointer;padding:2px 10px;">
              							</a>
            						</div>
          						</div>
              					<div class="divTeamstaerke">
									<p id="anzAktSpieler" class="marydale pos-rel ie9Fix" style="margin-top: -245px; left: 212px; color: rgb(6, 75, 144);">11 Spieler</p>
								</div>
          						<!--Ende Team Configuration-->
          						<div class="aufstellung" id="aufstellung"></div>
          					</div>
          					<div class="background">
								<p class="textCenter">Aufstellung</p>
          					</div>
        				</div>
      				</form>';
	return $return;
}

// Funktion zum Erstellen der "Kalender"-Seite
function createKalender($p1, $base, $type) {
	// Unterscheidung der Art (Übersicht, Training, Spiel, Turnier)
	$art = "";
	$ueberschrift = "";
	switch ($type) {
		case "Uebersicht" :
			$art = 0;
			$ueberschrift = "ALLE TERMINE";
			$base['js2'] .= '<script type="text/javascript" src="../../../../js/alleTermine.js"></script>';
			break;
		case "Training" :
			$art = 1;
			$ueberschrift = "ALLE TRAININGSTERMINE";
			$base['js2'] .= '<script type="text/javascript" src="../../../../js/trainingstermine.js"></script>';
			break;
		case "Spiel" :
			$art = 2;
			$ueberschrift = "ALLE SPIELTERMINE";
			$base['js2'] .= '<script type="text/javascript" src="../../../../js/spieltermine.js"></script>';
			break;
		case "Turnier" :
			$art = 3;
			$ueberschrift = "ALLE TURNIERTERMINE";
			$base['js2'] .= '<script type="text/javascript" src="../../../../js/turniertermine.js"></script>';
			break;
	}
	// Navigationsleiste erstellen
	$base['nav'] = createNav($_SESSION['rights'], $p1);
	// Kalender erstellen
	$calendar = file_get_contents("calendar.php");
	// Admin und Trainer dürfen einen neuen Termin erstellen
	if ($_SESSION['rights'] == 0 || $_SESSION['rights'] == 1){
		// Seiteninhalt erstellen
		$main =	'<div style="display: inline;"><strong>' . $ueberschrift . '</strong></div>
				<!-- BUTTON ZUM ERSTELLEN EINES NEUEN TERMINS -->
				<form id="newAppointment">
					<button onclick="showDialogCreateNewAppointment(' . $art . ')" id="btnNewAppointment" type="button" class="button place-right bg-white">Neuen Termin erstellen</button>
				</form>
				<br>
				<div>' . $calendar . '</div>';
	}
	// Spieler dürfen keine Termine erstellen
	else if ($_SESSION['rights'] == 2){
		$db = new DB();
		$con = $db->getConnection();
		// Aktuelle Mannschaft abfragen
		$allTeams = $db->getTeamByID($_SESSION['teamID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			// Seiteninhalt erstellen
			$main = '
				<div style="display: inline;"><strong>' . $ueberschrift . '</strong></div>
				<br><br>
				<div>' . $calendar . '</div>';
		}
		// Wenn Spieler keiner Mannschaft zugeordnet
		else
			$main = 'Sie sind keiner Mannschaft zugeordnet und sehen deshalb keine Termine.';
	}
	// Seitentitel erstellen
	$base['title'] = 'Kalender - ' . $type;
	// Zusätzliches JavaScript
	$base['js2'] .= '<script type="text/javascript">var USER_RIGHTS = ' . $_SESSION['rights'] . '</script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/fullcalendar.js"></script>';
	// Sidebar und Clubinfo leeren
	$base['sidebar'] = '';
	$base['clubinfo'] = '';
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = '<div style="width: 70%;"><br>' . $main . '</div>';
	// Zurückgeben des Arrays
	return $base;
}

// Funktion zum Erstellen der "Statistik"-Seite
function createSpielplan($p1, $base) {
	// Navigation erstellen
	$nav = createNav ($_SESSION['rights'], $p1 );
	// Inhalt erstellen
	$main = createContentSpielplan($_SESSION['rights']);
	// Zuweisen des Arrays
	$base['title'] = 'Spielplan';
	// Zusätzliches JavaScript (Dokumentende)
	$base['js2'] = '<script type="text/javascript">var USER_RIGHTS = ' . $_SESSION['rights'] . '</script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/spielplan.js"></script>';
	$base['nav'] = $nav;
	$base['sidebar'] = '';
	$base['main'] = '<div style="width: 80%;">' . $main . '</div>';
	$base['clubinfo'] = '';
	$base['footer'] = '';
	// Zurückgeben des Arrays
	return $base;
}

// Spielplan erstellen
function createContentSpielplan($rights){
	$return = "";
	// Verbindung aufbauen
	$db = new DB ();
	$con = $db->getConnection();
	// Admin kann einen Spielplan für alle Mannschaften anlegen
	if ($rights == 0){
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByClub($_SESSION['clubID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			$str_filler = "";
			// Selectfeld erstellen
			$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
			$i = 0;
			while ($i < count($allTeams)){
				$teamType = convertTeamTypeID($allTeams[$i]['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($allTeams[$i]['teamName'] != -1)
					$teamBez = $teamType . " (" . $allTeams[$i]['teamName'] . ")";
				else
					$teamBez = $teamType;
				$str_filler .= '<option value="' . $allTeams[$i]['teamID'] . '">' . $teamBez . '</option>';
				$i++;
			}
			$str_filler .= '</select></div>';
		}
		// Wenn keine Mannschaft existiert
		else
			$str_filler = 	'<button id="teamID" style="width: 100%; height: 31px; margin-top: 1px;" onclick="showDialogCreateNewTeam(' . $_SESSION['clubID'] . ')" id="btnNewTeam" type="button" class="button bg-gray fg-white">Neue Mannschaft erstellen</button>';
		$return .= '<div align="center">
						<div class="span3">
							<label>Mannschaft</label>'
				. $str_filler . '
						</div>
						<div class="span2">
							<br>
							<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent('.$rights.')">Anzeigen</button>&nbsp;
							<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
							<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
						</div>
						<div class="span2">
							<!-- BUTTON ZUM ERSTELLEN EINES NEUEN TERMINS -->
							<form id="newAppointment">
								<button style="width: 100%; margin-top: 21px;" onclick="showDialogCreateNewAppointment()" id="btnNewAppointment" type="button" class="button place-right bg-white">Neuen Termin erstellen</button>
							</form>
						</div>
					</div>
					<br><br><br><br><br><div id="result"></div>';
	}
	// Trainer kann einen Spielplan nur für seine Mannschaft anlegen
	else if ($rights == 1){
		$allCoaches = $db->getCoachByID($_SESSION['coachID']);
		// Wenn der Trainer existiert
		if ($allCoaches != false){
			// Wenn ein Team zugewiesen ist
			if(isset($allCoaches['team'][0])){
				// Mannschaft zuweisen
				$teamID = $allCoaches['team'][0];
				// Mannschaft abfragen
				$allTeams = $db->getTeamByID($teamID);
				// Wenn Mannschaften existieren
				if ($allTeams != false){
					$str_filler = "";
					// Selectfeld erstellen
					$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
					$teamType = convertTeamTypeID($allTeams['teamTypeID']);
					// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
					if ($allTeams['teamName'] != -1)
						$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
					else
						$teamBez = $teamType;
					$str_filler .= '<option value="' . $allTeams['teamID'] . '">' . $teamBez . '</option></select></div>';
				}
				$return .= '<div align="center">
						<div class="span3">
							<label>Mannschaft</label>'
						. $str_filler . '
						</div>
						<div class="span2">
							<br>
							<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent('.$rights.')">Anzeigen</button>&nbsp;
							<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
							<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
						</div>
						<div class="span2">
							<!-- BUTTON ZUM ERSTELLEN EINES NEUEN TERMINS -->
							<form id="newAppointment">
								<button style="width: 100%; margin-top: 21px;" onclick="showDialogCreateNewAppointment()" id="btnNewAppointment" type="button" class="button place-right bg-white">Neuen Termin erstellen</button>
							</form>
						</div>
					</div>
					<br><br><br><br><br><div id="result"></div>';
			}
			// Wenn Trainer kein Team trainiert
			else
				$return = "Sie trainieren aktuell keine Mannschaft.";
		}
		// Wenn der Trainer nicht existiert
		else
			$return = "Sie trainieren aktuell keine Mannschaft.";
	}
	// Spieler kann nur Spielplan seiner Mannschaft einsehen
	else if ($rights == 2){
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByID($_SESSION['teamID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			$str_filler = "";
			// Selectfeld erstellen
			$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
			$teamType = convertTeamTypeID($allTeams['teamTypeID']);
			// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
			if ($allTeams['teamName'] != -1)
				$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
			else
				$teamBez = $teamType;
			$str_filler .= '<option value="' . $allTeams['teamID'] . '">' . $teamBez . '</option></select></div>';
			$return .= '<div align="center">
					<div class="span3">
						<label>Mannschaft</label>'
					. $str_filler . '
					</div>
					<div class="span2">
						<br>
						<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent('.$rights.')">Anzeigen</button>&nbsp;
						<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
						<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
					</div>
				</div>
				<br><br><br><br><br><div id="result"></div>';
		}
		// Wenn Spieler keinem Team angehört
		else
			$return = "Sie gehören aktuell keiner Mannschaft an.";
	}
	return $return;
}

// Funktion zum Erstellen der "Statistik"-Seite
function createTurnierplan($p1, $base) {
	// Navigation erstellen
	$nav = createNav ($_SESSION['rights'], $p1 );
	// Inhalt erstellen
	$main = createContentTurnierplan($_SESSION['rights']);
	// Zuweisen des Arrays
	$base['title'] = 'Turnierplan';
	// Zusätzliches JavaScript (Dokumentende)
	$base['js2'] = '<script type="text/javascript">var USER_RIGHTS = ' . $_SESSION['rights'] . '</script>';
	$base['js2'] .= '<script type="text/javascript" src="../../../../js/turnierplan.js"></script>';
	$base['nav'] = $nav;
	$base['sidebar'] = '';
	$base['main'] = '<div style="width: 80%;">' . $main . '</div>';
	$base['clubinfo'] = '';
	$base['footer'] = '';
	// Zurückgeben des Arrays
	return $base;
}

// Turnierplan erstellen
function createContentTurnierplan($rights){
	$return = "";
	// Verbindung aufbauen
	$db = new DB ();
	$con = $db->getConnection();
	// Admin kann den Terminplan für alle Mannschaften einsehen
	if ($rights == 0){
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByClub($_SESSION['clubID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			$str_filler = "";
			// Selectfeld erstellen
			$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
			$i = 0;
			while ($i < count($allTeams)){
				$teamType = convertTeamTypeID($allTeams[$i]['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($allTeams[$i]['teamName'] != -1)
					$teamBez = $teamType . " (" . $allTeams[$i]['teamName'] . ")";
				else
					$teamBez = $teamType;
				$str_filler .= '<option value="' . $allTeams[$i]['teamID'] . '">' . $teamBez . '</option>';
				$i++;
			}
			$str_filler .= '</select></div>';
		}
		// Wenn keine Mannschaft existiert
		else
			$str_filler = 	'<button id="teamID" style="width: 100%; height: 31px; margin-top: 1px;" onclick="showDialogCreateNewTeam(' . $_SESSION['clubID'] . ')" id="btnNewTeam" type="button" class="button bg-gray fg-white">Neue Mannschaft erstellen</button>';
		$return .= '<div align="center">
						<div class="span3">
							<label>Mannschaft</label>'
				. $str_filler . '
						</div>
						<div class="span2">
							<br>
							<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent()">Anzeigen</button>&nbsp;
							<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
							<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
						</div>
						<div class="span2">
							<!-- BUTTON ZUM ERSTELLEN EINES NEUEN TERMINS -->
							<form id="newAppointment">
								<button style="width: 100%; margin-top: 21px;" onclick="showDialogCreateNewAppointment()" id="btnNewAppointment" type="button" class="button place-right bg-white">Neuen Termin erstellen</button>
							</form>
						</div>
					</div>
					<br><br><br><br><br><div id="result"></div>';
	}
	// Trainer kann einen Spielplan nur für seine Mannschaft anlegen
	else if ($rights == 1){
		$allCoaches = $db->getCoachByID($_SESSION['coachID']);
		// Wenn der Trainer existiert
		if ($allCoaches != false){
			// Wenn ein Team zugewiesen ist
			if(isset($allCoaches['team'][0])){
				// Mannschaft zuweisen
				$teamID = $allCoaches['team'][0];
				// Mannschaft abfragen
				$allTeams = $db->getTeamByID($teamID);
				// Wenn Mannschaften existieren
				if ($allTeams != false){
					$str_filler = "";
					// Selectfeld erstellen
					$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
					$teamType = convertTeamTypeID($allTeams['teamTypeID']);
					// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
					if ($allTeams['teamName'] != -1)
						$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
					else
						$teamBez = $teamType;
					$str_filler .= '<option value="' . $allTeams['teamID'] . '">' . $teamBez . '</option></select></div>';
				}
				$return .= '<div align="center">
						<div class="span3">
							<label>Mannschaft</label>'
						. $str_filler . '
						</div>
						<div class="span2">
							<br>
							<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent()">Anzeigen</button>&nbsp;
							<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
							<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
						</div>
						<div class="span2">
							<!-- BUTTON ZUM ERSTELLEN EINES NEUEN TERMINS -->
							<form id="newAppointment">
								<button style="width: 100%; margin-top: 21px;" onclick="showDialogCreateNewAppointment()" id="btnNewAppointment" type="button" class="button place-right bg-white">Neuen Termin erstellen</button>
							</form>
						</div>
					</div>
					<br><br><br><br><br><div id="result"></div>';
			}
			// Wenn Trainer kein Team trainiert
			else
				$return = "Sie trainieren aktuell keine Mannschaft.";
		}
		// Wenn der Trainer nicht existiert
		else
			$return = "Sie trainieren aktuell keine Mannschaft.";
	}
	// Spieler kann nur Spielplan seiner Mannschaft einsehen
	else if ($rights == 2){
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByID($_SESSION['teamID']);
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			$str_filler = "";
			// Selectfeld erstellen
			$str_filler = '<div class="input-control select"><select id="teamID" size="1"><option value="0">Mannschaft auswählen</option>1';
			$teamType = convertTeamTypeID($allTeams['teamTypeID']);
			// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
			if ($allTeams['teamName'] != -1)
				$teamBez = $teamType . " (" . $allTeams['teamName'] . ")";
			else
				$teamBez = $teamType;
			$str_filler .= '<option value="' . $allTeams['teamID'] . '">' . $teamBez . '</option></select></div>';
			$return .= '<div align="center">
						<div class="span3">
							<label>Mannschaft</label>'
					. $str_filler . '
						</div>
						<div class="span2">
							<br>
							<button id="btnSubmit" style="width: 100%; height: 31px; margin-top: 11px;" type="button" class="button primary" onclick="buildContent()">Anzeigen</button>&nbsp;
							<img id="wait" src="../../../../img/loading.gif" style="display: none; margin-top: 14px;" />
							<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>
						</div>
					</div>
					<br><br><br><br><br><div id="result"></div>';
		}
		// Wenn Spieler keinem Team angehört
		else
			$return = "Sie gehören aktuell keiner Mannschaft an.";
	}
	return $return;
}

// Funktion zum Erstellen der "Verein"-Seite
function createVerein($p1, $base) {
	// Navigationsleiste erstellen
	$base['nav'] = createNav($_SESSION['rights'], $p1);
	// Seiteninhalt erstellen
	$main = "Verein";
	$main = createContentVerein($_SESSION['rights']);
	// Seitentitel erstellen
	$base['title'] = 'Verein';
	// Zusaetzliches JS laden
	$base['js2'] = '<script type="text/javascript" src="../../../../js/vereinProfil.js"></script>';
	// Sidebar und Clubinfo leeren
	$base['sidebar'] = '';
	$base['clubinfo'] = '';
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = '<div style="width: 80%;">' . $main . '</div>';
	// Zurückgeben des Arrays
	return $base;
}

// Verein erstellen
function createContentVerein($rights){
	$return = "";
	// Datenbankverbindung herstellen
	$db = new DB();
	$con = $db->getConnection();
	// DB Abfrage nach der eventID im gewählten Zeitraum
	$allClubData = mysql_query("SELECT `clubShort`, `clubLong`, `clubHistory` FROM `clubtab` WHERE `clubID` = '" . $_SESSION['clubID'] . "'");
	$clubData = mysql_fetch_array($allClubData, MYSQL_NUM);
	$clubShort = $clubData[0];
	$clubLong = $clubData[1];
	$clubHistory = $clubData[2];
	$editSaveButtons = '';
	// Admin kann Daten bearbeiten, Trainer und Spieler sehen nur die Daten
	if($_SESSION['rights'] == 0){
		$editSaveButtons = '<div class=span6">
							<div class=span2">
								<label class="span3">
									<br>
									<!-- Vereinsprofil bearbeiten BUTTON -->
									<button id="editVereinProfil" type="button" class="bg-darkGreen fg-white button place-right">Vereinsdaten bearbeiten</button>
									<img id="waitEditVereinProfil" src="../../../../img/loading.gif" style="display: none;" />
									<!-- Vereinsprofil speichern BUTTON -->
									<button id="saveVereinProfil" type="button" class="bg-darkGreen fg-white button place-right" style="display: none;" >Vereinsdaten speichern</button>
									<img id="waitSaveVereinProfil" src="../../../../img/loading.gif" style="display: none;" />
								</label>
							</div>
						</div>';
	}
	// Wenn Daten existieren, keinen
	// Vereinsdaten erstellen
	$return = '	<div class="vereinsdaten">
				<div>
					<form id="register">
						<fieldset>
							<div class=span3">
								<br>
								<label class="span2" style="margin-top: 38px;">Beschreibung:</label>
								<div class="input-control textarea span7">
									<textarea maxlength="500" style="margin: 0px; height: 100px; width: 598px;" id="VereinKurzbeschreibung" name="text" cols="35" rows="4" disabled="disabled" placeholder="Kurzbeschreibung des Vereins">' . $clubShort . '</textarea>
								</div>
								<br><br><br>
								<label class="span2" style="margin-top: 21px;">Lange Beschreibung:</label>
								<div class="input-control textarea span7">
									<textarea style="margin: 0px; height: 100px; width: 598px;" id="VereinLangbeschreibung" name="text" cols="35" rows="4" disabled="disabled" placeholder="Lange Beschreibung des Vereins">' . $clubLong . '</textarea>
								</div>
								<br><br><br>
								<label class="span2" style="margin-top: 38px;">Historie:</label>
								<div class="input-control textarea span7">
									<textarea maxlength="1000" style="margin: 0px; height: 100px; width: 598px;" id="VereinHistorie" name="text" cols="35" rows="4" disabled="disabled" placeholder="Historie des Vereins">' . $clubHistory . '</textarea>
								</div>
							</div>
							' . $editSaveButtons . '
						</fieldset>
					</form>
				</div>
				<div id="message" align="center"></div>
			</div>';
	return $return;
}

// Funktion zum Erstellen der "Profil"-Seite
function createProfil($p1, $base) {
	// Inhalt erstellen
	$nav = createNav ( $_SESSION['rights'], $p1 );
	$main = createContentProfil();
	// Zuweisen des Arrays
	$base['title'] = 'Profil';
	// Zusätzliches JavaScript (Dokumentende)
	$base['js2'] = '<script type="text/javascript" src="../../../../js/profil.js"></script>';
	$base['nav'] = $nav;
	$base['sidebar'] = '';
	$base['main'] = '<div style="width: 80%;">' . $main . '</div>';
	$base['clubinfo'] = '';
	$base['footer'] = '';
	// Zurückgeben des Arrays
	return $base;
}

// Profil erstellen
function createContentProfil(){
	$return = "";
	$return = '	<div class="alleDaten">
				<div>
					<form id="register">
						<fieldset>
							<div class=span3">
								<br>
								<label class="span2">Vorname</label>
								<div class="input-control text span3" data-role="input-control">
									<input id="vornameEdit" type="text" disabled="disabled" placeholder="' . $_SESSION["vorname"] . '" autofocus="autofocus" required="required">
								</div>
								<br><br><br>
								<label class="span2">Nachname</label>
								<div class="input-control text span3" data-role="input-control">
									<input id="nachnameEdit" type="text" disabled="disabled" placeholder="' . $_SESSION["nachname"] . '" required="required">
								</div>
								<br><br><br>
								<label class="span2">E-Mail Adresse</label>
								<div class="input-control text span3" data-role="input-control">
									<input id="emailEdit" type="email" disabled="disabled" placeholder="' . $_SESSION["email"] . '" required="required">
								</div>
							</div>
							<div class=span3">
								<br>
								<div id="formNewPW1" style="display: none;">
									<label class="span2">Neues Passwort</label>
									<div class="input-control text span3" data-role="input-control">
										<input id="newPW1" type="password" placeholder="Neues Passwort">
									</div>
								</div>
								<br><br><br>
								<div id="formNewPW2" style="display: none;">
									<label class="span2">Neues Passwort wiederholen</label>
									<div class="input-control text span3" data-role="input-control">
										<input id="newPW2" type="password" placeholder="Passwort wiederholen">
									</div>
								</div>
								<br><br><br>
								<div id="formNewPW3" style="display: none;">
									<label class="span2">Aktuelles Passwort</label>
									<div class="input-control text span3" data-role="input-control">
										<input id="oldPW" type="password" placeholder="Aktuelles Passwort">
									</div>
								</div>
								<br><br><br>
							</div>
							<br><br><br>
							<div class=span6">
								<div class=span2">
									<!-- Profil bearbeiten BUTTON -->
									<label class="span2">
										<button id="editProfil" type="button" class="bg-darkGreen fg-white button place-right">Profil bearbeiten
											<img id="waitEditProfil" src="../../../../img/loading.gif" style="display: none;" />
										</button>
									</label>
									<!-- Profil speichern BUTTON -->
									<label class="span2">
										<button id="saveProfil" type="button" class="bg-darkGreen fg-white button place-right" style="display: none;" >Profil speichern</button>
										<img id="waitSaveProfil" src="../../../../img/loading.gif" style="display: none;" />
									</label>
								</div>
								<div class=span2">
									<!-- Passwort ändern BUTTON -->
									<label class="span2">
										<button id="changePassword" type="button" class="bg-darkGreen fg-white button place-right">Passwort ändern
											<img id="waitChangePassword" src="../../../../img/loading.gif" style="display: none;" />
										</button>
									</label>
									<!-- Passwort speichern BUTTON -->
									<label class="span2">
										<button id="savePassword" type="button" class="bg-darkGreen fg-white button place-right" style="display: none;" >Passwort speichern</button>
										<img id="waitSavePassword" src="../../../../img/loading.gif" style="display: none;" />
									</label>
								</div>
								<div class=span2">
									<!-- Profil löschen BUTTON -->
									<label class="span2">
										<button id="deleteProfil" type="button" class="bg-darkGreen fg-white button place-right">Profil löschen</button>
									</label>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
					<div id="message" align="center"></div>
			</div>';
	return $return;
}

// Funktion zum Erstellen der "Logout"-Seite
function createLogout() {
	// Session zerstören
	session_destroy ();
	// Startseite anzeigen
	header ( 'Location: /' );
}

// Erstellen der öffentlichen "Vereinsinfo"-Seite
function createVereinsinfo($p1, $base) {
	require_once 'inc/DB.php';
	$db = new DB();
	$db->getConnection();
	// SQL Abfrage
	$sql = "SELECT * FROM `clubtab` WHERE `clubName` = '" . $p1 . "'";
	// Anfrage erstellen
	$clubExists = mysql_query($sql);
	// Navigationsleiste erstellen
	$base['nav'] = createNavForGuest();
	// Wenn Vereinsname existiert -> Kalender anzeigen mit Terminen für verschiedene Mannschaften und Terminarten
	if (!mysql_num_rows($clubExists ) == 0){
		$row = mysql_fetch_array($clubExists);
		// Sidebar entfernen
		$base["sidebar"] = 	'';
		// Clubdaten anzeigen
		$base["clubinfo"] = '<div class="span4">
								<nav class="sidebar bg-darkGreen">
									<ul>
										<li class="title" style="font-weight: bold; font-size: 20px;">' . $row['clubName'] . '</li>
										<li>
											<div align="center" class="span4">
												<img src="../../../../img/spieler.png" class="margin10 nlm ntm size2">
											</div>
											<div align="left" class="fg-white margin10">
												<strong>Beschreibung:</strong><br>
												' . $row['clubShort'] . '
												<br><br>
												<strong>Geschichte:</strong><br>
												' . $row['clubHistory'] . '
												<br>
											</div>
										</li>
									</ul>
								</nav>
							</div>';
		// Alle Mannschaften abfragen
		$allTeams = $db->getTeamByClub($row['clubID']);
		$str_filler_type = "";
		// Wenn Mannschaften existieren
		if ($allTeams != false){
			$str_filler = "";
			$str_filler_type = '<div class="input-control select span3"><select class="" id="appointmentType" onchange="reloadCalendar(' . $row['clubID'] . ')"><option value="0">Alle</option><option value="1" class="bg-green">Training</option><option value="2" class="bg-red">Spiel</option><option value="3" class="bg-orange">Turnier</option></select></div>';
			// Selectfeld erstellen
			$str_filler = '<div class="input-control select span3"><select id="teamID" onchange="reloadCalendar(' . $row['clubID'] . ')"><option value="0">Alle Mannschaften</option>';
			$i = 0;
			while ($i < count($allTeams)){
				$teamType = convertTeamTypeID($allTeams[$i]['teamTypeID']);
				// Falls das Team eine Bezeichung trägt, diese in Klammern anfügen
				if ($allTeams[$i]['teamName'] != -1)
					$teamBez = $teamType . " (" . $allTeams[$i]['teamName'] . ")";
				else
					$teamBez = $teamType;
				$str_filler .= '<option value="' . $allTeams[$i]['teamID'] . '">' . $teamBez . '</option>';
				$i++;
			}
			$str_filler .= '</select></div>';
		}
		// Wenn keine Mannschaft existiert
		else{
			$str_filler = '<label>Keine Mannschaft vorhanden.</label>';
			$str_filler_type = '<label>Keine Mannschaft vorhanden.</label>';
		}
		$main =	'<div class="span10">
					<div class="span7" align="right">
						<label>Mannschaft:</label>
					</div>
					<div class="span3">' . $str_filler . '</div>
					<div class="span7" style="margin-left: 0px;" align="right">
						<label>Typ:</label>
					</div>
					<div class="span3">' . $str_filler_type . '</div>
					<br><br><br><br><br>
					<div align="left" class="panel-content fg-dark nlp nrp">
						' . file_get_contents ( "calendar.php" ) . '
					</div>
				</div>';
		// Zusätzliches JavaScript
		$base['js2'] .= '<script type="text/javascript">var CLUB_ID = ' . $row['clubID'] . ';var USER_RIGHTS = 2;</script>';
		$base['js2'] .= '<script type="text/javascript" src="../../../../js/fullcalendar.js"></script>';
		$base['js2'] .= '<script type="text/javascript" src="../../../../js/vereinsinfo.js"></script>';
		$base['js2'] .= '<script type="text/javascript">var str_club = "' . $row['clubName'] . '";</script><script type="text/javascript" src="../../../../js/alleTermineClub.js"></script>';
	}
	// Wenn Vereinsname nicht existiert -> Startseite anzeigen mit Inhalt dass Verein nicht existiert
	else{
		$base["sidebar"] = 	'<div class="span4">'.
								'<form>'.
									'<fieldset>'.
										'<legend>Suchen Sie hier nach einem Verein</legend>'.
										'<div class="input-control text" data-role="input-control">'.
											'<input id="vereinLiveSearch" type="text" placeholder="Vereinsname" onkeyup="liveSearch(this.value)">'.
										'</div>'.
										'<img id="waitLiveSearch" src="../../../../img/loading.gif" style="display: none;" />'.
										'<br>'.
										'<div id="placeholderLiveSearch"></div>'.
									'</fieldset>'.
								'</form>'.
							'</div>';
		// Seiteninhalt erstellen
		$main =	'<div class="span6">
					<div align="center" class="panel-content fg-dark nlp nrp">
						<strong>Der Angegebene Vereinsname existiert nicht!</strong>
					</div>
				</div>';
		$base["clubinfo"] = '<div class="span4">
								<!-- Anzeige eines Hinweises zur Registrierung -->
								<div>
									<form id="register">
										<fieldset>
											<legend>Registrieren Sie sich noch heute!</legend>
											<div class="input-control text" data-role="input-control">
												<input id="vornameRegister" type="text" placeholder="Vorname*" autofocus="autofocus" required="required">
											</div>
											<div class="input-control text" data-role="input-control">
												<input id="nachnameRegister" type="text" placeholder="Nachname*" required="required">
											</div>
											<div class="input-control text" data-role="input-control">
												<input id="vereinRegister" type="text" placeholder="Vereinsname*" required="required">
											</div>
											<div class="input-control text" data-role="input-control">
												<input id="emailRegister" type="email" placeholder="E-Mail Adresse*" required="required">
											</div>
											<div align="left" class="input-control password" data-role="input-control">
												<input id="passwortRegister" type="password" placeholder="Passwort*" required="required">'. //pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}">
													'<span style="font-size: 12px;">
													*Pflichtfeld
												</span>
											</div>
											<br>
											<br>
											<input id="btnRegister" class="inverse" type="submit" value="Registrieren">
												<img id="waitRegister" src="../../../../img/loading.gif" style="display: none;" />
											<br>
											<div id="alertRegister" style="display: none; font-size: 14px; margin-top: 8px;"></div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>';
	}
	// Seitentitel erstellen
	$base['title'] = $p1;
	// Footer löschen
	$base['footer'] = '';
	// Seiteninhalt zuweisen
	$base['main'] = $main;
	// Zurückgeben des Arrays
	return $base;
}

##################################
### ENDE SEITNINHALT ERSTELLEN ###
##################################

########################################
### START ZUSATZFUNKTIONEN ERSTELLEN ###
########################################

// Vereinsinformationen (rechte Sidebar) erstellen
function createClubinfo() {
	$return = "";
	$return = '<nav class="sidebar">
					<ul>
						<li class="title" style="font-weight: bold; font-size: 20px;">Vereinsname</li>
								<li>
									<div align="center" class="span3">
										<img src="../../../../img/spieler.png" class="margin10 nlm ntm size2">
									</div>
									<div align="center" class="fg-white margin10">
										<strong>Vorstand:</strong>
										<br>
										Vorname Nachname
										<br>
										<br>
										<strong>Anschrift:</strong>
										<br>
										Straße Nr.
										<br>
										12345 StadtOrt
										<br>
										<br>
										<strong>Kontakt:</strong>
										<br>
										Tel.: 069/12312312
										<br>
										<a class="fg-blue" style="cursor: pointer;" href="mailto:info@vereinsname.de" data-hint="info@vereinsname.de|">E-Mail senden</a>
									</div>
								</li>
							</ul>
						</nav>';
	return $return;
}

// Navigationsleiste erstellen
function createNav($int_rights, $p1) {
	$return = "";
	$str_Startseite = "";
	$str_Team = "";
	$str_Verwaltung = "";
	$str_Kalender = "";
	$str_Spielplan = "";
	$str_Turnierplan = "";
	$str_Verein = "";
	$str_Profil = "";
	// Wenn "Home" aufgerufen wird -> "Startseite" rot hinterlegen
	if ($p1 == "Home")
		$str_Startseite = "bg-darkGreen";
	// Wenn irgendwas im Kalender aufgerufen wird -> "Kalender" dunkelgrün hinterlegen
	elseif ($p1 == "Termin_Uebersicht" || $p1 == "Termin_Training" || $p1 == "Termin_Spiel" || $p1 == "Termin_Turnier")
	$str_Kalender = "bg-darkGreen";
	elseif ($p1 == "Mannschaft" || $p1 == "Trainer" || $p1 == "Spieler")
	$str_Team = "bg-darkGreen";
	elseif ($p1 == "Trainingsgruppen" || $p1 == "Anwesenheit" || $p1 == "Aufstellung")
	$str_Verwaltung = "bg-darkGreen";
	elseif ($p1 == "Spielplan")
	$str_Spielplan = "bg-darkGreen";
	elseif ($p1 == "Turnierplan")
	$str_Turnierplan = "bg-darkGreen";
	elseif ($p1 == "Verein")
	$str_Verein = "bg-darkGreen";
	elseif ($p1 == "Profil")
	$str_Profil = "bg-darkGreen";
	// Erstellen der Navigation für den Administrator
	if ($int_rights == 0) {
		$return = '<nav class="navigation-bar green">
						    <nav class="navigation-bar-content green">
								<span class="element" style="z-index: 10;"><img width="100px" height="100px" src="../../../../img/spieler.png" style=" margin-top: -5px;"></span>
						        <a href="/Home"><span class="element brand fg-white"><div style="font-weight: bold;">' . strtoupper($_SESSION['vereinsname']) . '</div><div style="margin-top: 2px;">' . strtoupper($_SESSION['vorname'] . ' ' . $_SESSION['nachname']) . '</div></span></a>
						        <span class="element-divider place-right"></span>
						        <a class="element place-right" style="padding-top: 23px;" href="/Logout">AUSLOGGEN</a>
						    </nav>
						</nav>
						<nav class="navigation-bar green">
    						<nav class="navigation-bar-content green">
								<span class="element" style="width: 130px;"></span>
						        <a class="element brand ' . $str_Startseite . '" href="/Home"><span class="icon-home margin-top8"></span></a>
						        <div class="element ' . $str_Team . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                TEAM
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Mannschaft">MANNSCHAFT</a></li>
						                <li><a href="/Trainer">TRAINER</a></li>
						                <li><a href="/Spieler">SPIELER</a></li>
						            </ul>
						        </div>
						        <div class="element ' . $str_Verwaltung . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                VERWALTUNG
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Anwesenheit">ANWESENHEIT</a></li>
						                <li><a href="/Aufstellung">AUFSTELLUNG</a></li>
						            </ul>
						        </div>
								<div class="element ' . $str_Kalender . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                KALENDER
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Termin_Uebersicht">ÜBERSICHT</a></li>
						                <li><a href="/Termin_Training">TRAINING</a></li>
						                <li><a href="/Termin_Spiel">SPIELE</a></li>
						                <li><a href="/Termin_Turnier">TURNIERE</a></li>
						            </ul>
						        </div>
								<a class="element brand ' . $str_Spielplan . '" style="padding-top: 23px;" href="/Spielplan">SPIELPLAN</a>
								<a class="element brand ' . $str_Turnierplan . '" style="padding-top: 23px;" href="/Turnierplan">TURNIERPLAN</a>
								<a class="element brand ' . $str_Verein . '" style="padding-top: 23px;" href="/Verein">VEREIN</a>
								<a class="element brand ' . $str_Profil . '" style="padding-top: 23px;" href="/Profil">PROFIL</a>
							</nav>
						</nav>';
	} 	// Erstellen der Navigation für den Trainer
	elseif ($int_rights == 1) {
		$return = '<nav class="navigation-bar green">
						    <nav class="navigation-bar-content green">
								<span class="element" style="z-index: 10;"><img width="100px" height="100px" src="../../../../img/spieler.png" style=" margin-top: -5px;"></span>
						        <span class="element brand"><div style="font-weight: bold;">' . strtoupper($_SESSION['vereinsname']) . '</div><div style="margin-top: 2px;">' . strtoupper($_SESSION['vorname'] . ' ' . $_SESSION['nachname']) . '</div></span>
						        <span class="element-divider place-right"></span>
						        <a class="element place-right" style="padding-top: 23px;" href="/Logout">AUSLOGGEN</a>
						    </nav>
						</nav>
						<nav class="navigation-bar green">
    						<nav class="navigation-bar-content green">
								<span class="element" style="width: 130px;"></span>
						        <a class="element brand ' . $str_Startseite . '" href="/Home"><span class="icon-home margin-top8"></span></a>
								<div class="element ' . $str_Team . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                TEAM
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Mannschaft">MANNSCHAFT</a></li>
						                <li><a href="/Spieler">SPIELER</a></li>
						            </ul>
						        </div>
								<div class="element ' . $str_Verwaltung . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                VERWALTUNG
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Anwesenheit">ANWESENHEIT</a></li>
										<li><a href="/Aufstellung">AUFSTELLUNG</a></li>
						            </ul>
						        </div>
								<div class="element ' . $str_Kalender . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                KALENDER
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Termin_Uebersicht">ÜBERSICHT</a></li>
						                <li><a href="/Termin_Training">TRAINING</a></li>
						                <li><a href="/Termin_Spiel">SPIELE</a></li>
						                <li><a href="/Termin_Turnier">TURNIERE</a></li>
						            </ul>
						        </div>
								<a class="element brand ' . $str_Spielplan . '" style="padding-top: 23px;" href="/Spielplan">SPIELPLAN</a>
								<a class="element brand ' . $str_Turnierplan . '" style="padding-top: 23px;" href="/Turnierplan">TURNIERPLAN</a>
								<a class="element brand ' . $str_Verein . '" style="padding-top: 23px;" href="/Verein">VEREIN</a>
								<a class="element brand ' . $str_Profil . '" style="padding-top: 23px;" href="/Profil">PROFIL</a>
							</nav>
						</nav>';
	} 	// Erstellen der Navigation für den Spieler
	elseif ($int_rights == 2) {
		// Wenn Session TeamBez vorhanden
		if(isset($_SESSION['teamBez']))
			$str_bez = " - " . $_SESSION['teamBez'];
		else
			$str_bez = "";
		$return = '<nav class="navigation-bar green">
						    <nav class="navigation-bar-content green">
								<span class="element" style="z-index: 10;"><img width="100px" height="100px" src="../../../../img/spieler.png" style=" margin-top: -5px;"></span>
						        <span class="element brand"><div style="font-weight: bold;">' . strtoupper($_SESSION['vereinsname']) . strtoupper($str_bez) . '</div><div style="margin-top: 2px;">' . strtoupper($_SESSION['vorname'] . ' ' . $_SESSION['nachname']) . '</div></span>
						        <span class="element-divider place-right"></span>
						        <a class="element place-right" style="padding-top: 23px;" href="/Logout">AUSLOGGEN</a>
						    </nav>
						</nav>
						<nav class="navigation-bar green">
    						<nav class="navigation-bar-content green">
								<span class="element" style="width: 130px;"></span>
						        <a class="element brand ' . $str_Startseite . '" href="/Home"><span class="icon-home margin-top8"></span></a>
								<div class="element ' . $str_Team . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                TEAM
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Mannschaft">MANNSCHAFT</a></li>
						                <li><a href="/Spieler">SPIELER</a></li>
						            </ul>
						        </div>
								<a class="element brand ' . $str_Verwaltung . '" style="padding-top: 23px;" href="/Aufstellung">AUFSTELLUNG</a>
								<div class="element ' . $str_Kalender . '" style="padding-top: 23px; padding-right: 28px;">
						            <a class="dropdown-toggle" href="#">
						                KALENDER
						            </a>
						            <ul class="dropdown-menu" data-role="dropdown">
						                <li><a href="/Termin_Uebersicht">ÜBERSICHT</a></li>
						                <li><a href="/Termin_Training">TRAINING</a></li>
						                <li><a href="/Termin_Spiel">SPIELE</a></li>
						                <li><a href="/Termin_Turnier">TURNIERE</a></li>
						            </ul>
						        </div>
								<a class="element brand ' . $str_Spielplan . '" style="padding-top: 23px;" href="/Spielplan">SPIELPLAN</a>
								<a class="element brand ' . $str_Turnierplan . '" style="padding-top: 23px;" href="/Turnierplan">TURNIERPLAN</a>
								<a class="element brand ' . $str_Verein . '" style="padding-top: 23px;" href="/Verein">VEREIN</a>
								<a class="element brand ' . $str_Profil . '" style="padding-top: 23px;" href="/Profil">PROFIL</a>
							</nav>
						</nav>';
	}
	return $return;
}

// Öffentliche Navigationsleiste erstellen
function createNavForGuest(){
	$return =	'<nav class="navigation-bar green">
					<nav class="navigation-bar-content green">
						<span class="element" style="z-index: 10;"><img width="100px" height="100px" src="../../../../img/spieler.png" style=" margin-top: -5px;"></span>
						<a class="element brand" href="/"><div style="font-weight: bold;">MANAGE YOUR TEAM</div></a>
						<!-- LOGIN ELEMENTE -->
						<div class="element input-element place-right">
							<form id="login">
								<div class="input-control text span3">
									<input id="email" type="text" placeholder="E-Mail" required="required" style="font-size: 14px;">
								</div>
								<div class="input-control text span2">
									<input id="passwort" type="password" placeholder="Passwort" required="required" style="font-size: 14px;">
								</div>
								<div class="input-control text span1" style="padding-bottom: 0px; margin-bottom: 13px; height: 0px;">
									<!-- LOGIN BUTTON -->
									<button id="btnSubmit" type="submit" class="place-right" style="margin-top: -3px;"> Login
										<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />
									</button>
								</div>
								<div id="alert" style="display: none; padding-top: 10px;"></div>
							</form>
						</div>
					</nav>
				</nav>
				<nav class="navigation-bar green">
   					<nav class="navigation-bar-content green">
						<span class="element"></span>
					</nav>
				</nav>';
	return $return;
}

// Öffentliche Navigationsleiste für Bestätigungsseite erstellen
function createNavForBest(){
	$return =	'<nav class="navigation-bar green">
				    <nav class="navigation-bar-content green">
						<span class="element" style="z-index: 10;"><img width="100px" height="100px" src="../../../../img/spieler.png" style=" margin-top: -5px;"></span>
						<a class="element brand" href="/"><div style="font-weight: bold;">MANAGE YOUR TEAM</div></a>
						<!-- LOGIN ELEMENTE -->
						<div class="element input-element place-right">
							<form id="loginBest">
								<div class="input-control text span3">
									<input id="email" type="text" placeholder="E-Mail" required="required" style="font-size: 14px;">
								</div>
								<div class="input-control text span2">
									<input id="passwort" type="password" placeholder="Passwort" required="required" style="font-size: 14px;">
								</div>
								<div class="input-control text span1" style="padding-bottom: 0px; margin-bottom: 13px; height: 0px;">
									<!-- LOGIN BUTTON -->
									<button id="btnSubmit" type="submit" class="place-right" style="margin-top: -3px;"> Login
										<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />
									</button>
								</div>
								<div id="alert" style="display: none; padding-top: 10px;"></div>
							</form>
						</div>
				    </nav>
				</nav>
				<nav class="navigation-bar green">
    				<nav class="navigation-bar-content green">
						<span class="element"></span>
					</nav>
				</nav>';
	return $return;
}

// Funktion zur Erstellung eines "schönen" Datums
function convertDate($oldDate) {
	$newDate = explode("-", $oldDate);
	return sprintf("%02d.%02d.%04d", $newDate[2], $newDate[1], $newDate[0]);
}

// Name des Typs
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

// Button "Neuen Spieler erstellen" erstellen (nur für Admin und Trainer)
function createBtnPlayer() {
	$return = "";
	$return = '<!-- BUTTON ZUM ERSTELLEN EINES NEUEN SPIELERS -->
				<form id="newPlayer">
					<button onclick="showDialogCreateNewPlayer(' . $_SESSION['clubID'] . ')" id="btnNewPlayer" type="button" class="button place-right bg-white">Neuen Spieler erstellen</button>
				</form>
				<br>';
	return $return;
}

#######################################
### ENDE ZUSATZFUNKTIONEN ERSTELLEN ###
#######################################

// Ersetzen der Platzhalter
$template = str_replace( $base['tag_start'] . 'title' . $base['tag_end'], $base['title'], $template);
$template = str_replace( $base['tag_start'] . 'meta' . $base['tag_end'], $base['meta'], $template);
$template = str_replace( $base['tag_start'] . 'link' . $base['tag_end'], $base['link'], $template);
$template = str_replace( $base['tag_start'] . 'css' . $base['tag_end'], $base['css'], $template);
$template = str_replace( $base['tag_start'] . 'js' . $base['tag_end'], $base['js'], $template);
$template = str_replace( $base['tag_start'] . 'nav' . $base['tag_end'], $base['nav'], $template);
$template = str_replace( $base['tag_start'] . 'msg' . $base['tag_end'], $base['msg'], $template);
$template = str_replace( $base['tag_start'] . 'sidebar' . $base['tag_end'], $base['sidebar'], $template);
$template = str_replace( $base['tag_start'] . 'main' . $base['tag_end'], $base['main'], $template);
$template = str_replace( $base['tag_start'] . 'clubinfo' . $base['tag_end'], $base['clubinfo'], $template);
$template = str_replace( $base['tag_start'] . 'js2' . $base['tag_end'], $base['js2'], $template);
$template = str_replace( $base['tag_start'] . 'footer' . $base['tag_end'], $base['footer'], $template);

// Zurückgeben des Templates
echo stripslashes($template);

?>