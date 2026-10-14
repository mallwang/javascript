<?php session_start(); include 'funktionen.php';

// Datenbankwerte
$dbhost="DB_HOST";
$dbuser="DB_USER";
$dbpassword="DB_PASSWORD";
$dbdatenbank="DB_NAME";

// Datenbankverbindung oeffnen
if(!($verbindung = mysql_connect("$dbhost", "$dbuser", "$dbpassword"))){
	die("Konnte keine Verbindung zur Datenbank herstellen!");
}

// Datenbank auswaehlen
if(!(@mysql_select_db("$dbdatenbank", $verbindung))){
	die("Fehler " . mysql_errno() . ": " . mysql_error());
}

mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");

// Alle Variabeln abfragen
$bundesland = mysql_real_escape_string($_POST["bundesland"]);
$ort = mysql_real_escape_string($_POST["ort"]);
$art = mysql_real_escape_string($_POST["art"]);
$name = mysql_real_escape_string($_POST["name"]);
$beschreibung = mysql_real_escape_string($_POST["beschreibung"]);
$breitengrad = mysql_real_escape_string($_POST["breitengrad"]);
$laengengrad = mysql_real_escape_string($_POST["laengengrad"]);
$userName = mysql_real_escape_string($_POST["userName"]);
$userEmail = mysql_real_escape_string($_POST["userEmail"]);

// Pr�fen, ob alle Daten ausgef�llt sind
if ($bundesland != "" && $ort != "" && $art != "" && $name != "" && strlen($beschreibung) > 10 && $breitengrad != "" && $laengengrad != ""){
	// Stadtname
	$stadtname = $ort;
	// Fallunterscheidung des Bundeslandes
	$bundeslandname;
	switch ($bundesland) {
		case "BW": $bundeslandname = "Baden-W&uuml;rttemberg"; break;
		case "BY": $bundeslandname = "Bayern"; break;
		case "BE": $bundeslandname = "Berlin"; break;
		case "BB": $bundeslandname = "Brandenburg"; break;
		case "HB": $bundeslandname = "Bremen"; break;
		case "HH": $bundeslandname = "Hamburg"; break;
		case "HE": $bundeslandname = "Hessen"; break;
		case "MV": $bundeslandname = "Mecklenburg-Vorpommern"; break;
		case "NI": $bundeslandname = "Niedersachsen"; break;
		case "NW": $bundeslandname = "Nordrhein-Westfalen"; break;
		case "RP": $bundeslandname = "Rheinland-Pfalz"; break;
		case "SL": $bundeslandname = "Saarland"; break;
		case "SN": $bundeslandname = "Sachsen"; break;
		case "ST": $bundeslandname = "Sachsen-Anhalt"; break;
		case "SH": $bundeslandname = "Schleswig-Holstein"; break;
		case "TH": $bundeslandname = "Th&uuml;ringen"; break;
		default: $bundeslandname = false; break;
	}
	if(!$bundeslandname){
		echo "keinBundesland"; exit;
	}
	// Fallunterscheidung der Art
	$artname;
	$tabelle;
	switch ($art) {
		case "S": $artname = "Sehensw&uuml;rdigkeit"; $tabelle = "sehensw_neu"; break;
		case "P": $artname = "Park"; $tabelle = "parks_neu"; break;
		case "M": $artname = "Museum"; $tabelle = "museen_neu"; break;
		default: $artname = false; break;
	}
	if(!$artname){
		echo "keineArt"; exit;
	}
	// Pr�fen, ob ein User ausgef�llt ist
	if (!isset($_SESSION['nickname'])){
		if($userName == ""){
			$userName = "Unbekannt";
		}
		if($userEmail == ""){
			$userEmail = "Unbekannt";
		}
	}
	else{
		$userName = $_SESSION["username"];
		$userEmail = $_SESSION["nickname"];
	}
	// Schl�ssel erstellen
	$zufallskey = substr(md5(microtime()), mt_rand(0, 24), 8);
	$schluessel = md5($zufallskey);
	// Datensatz in bestimmte Tabelle eintragen
	$dateneintrag = anfrage("INSERT INTO $tabelle (`name`, `ort`, `bundesland`, `beschreibung`, `lng`, `lat`, `key`)
			VALUES ('$name', '$stadtname', '$bundesland', '$beschreibung', '$laengengrad', '$breitengrad', '$schluessel')");
	// Wenn der Datensatz erfolgreich eingetragen wurde
	if ($dateneintrag){
		// E-Mail versenden
		$empfaengerWebmaster = "MAIL_WEBMASTER_ADDRESS";
		$absender = "i-use-my-brain.com <MAIL_FROM_ADDRESS>";
		$betreffWebmaster = "Neuer Dateneintrag von " . $userName . ": " . $name . ", " . $stadtname;
		$headerWebmaster  = "MIME-Version: 1.0\r\n";
		$headerWebmaster .= "Content-type: text/html; charset=utf-8\r\n";
		$headerWebmaster .= "From: $absender\r\n";
		$headerWebmaster .= "Reply-To: $absender\r\n";
		// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
		$headerWebmaster .= "X-Mailer: PHP ". phpversion();
		$textWebmaster = "
			<html>
				<body>
					<div>
						Soeben wurde ein neuer Datensatz eingetragen!
						<br><br>
						Bundesland: $bundeslandname<br>
						Ort: $stadtname<br>
						Kreis: $kreisname<br>
						Art: $artname<br>
						Name: $name<br>
						Beschreibung: $beschreibung<br>
						Breitengrad: $breitengrad<br>
						L&auml;ngengrad: $laengengrad<br><br>
						&Uuml;bermittelnder Benutzer:<br>
						Name: $userName<br>
						E-Mail: $userEmail<br>
						<br>
						Datenbankquery: INSERT INTO $tabelle (`name`, `ort`, `bundesland`, `beschreibung`, `lng`, `lat`, `key`) VALUES
						('$name', '$stadtname', '$bundesland', '$beschreibung', '$laengengrad', '$breitengrad', '$schluessel')<br><br>
						Link zur Pr&uuml;fung: <a href='https://maps.google.de/maps?q=$breitengrad,$laengengrad'>Mithilfe Google Pr&uuml;fen</a><br><br>
						<img border='0' src='http://maps.googleapis.com/maps/api/staticmap?center=$breitengrad,$laengengrad&markers=$breitengrad,$laengengrad&zoom=7&size=800x450&sensor=false' alt='Kartenausschnitt Zoom 8'>
						<img border='0' src='http://maps.googleapis.com/maps/api/staticmap?center=$breitengrad,$laengengrad&markers=$breitengrad,$laengengrad&zoom=10&size=800x450&sensor=false' alt='Kartenausschnitt Zoom 11'><br><br>
						<img border='0' src='http://maps.googleapis.com/maps/api/staticmap?center=$breitengrad,$laengengrad&markers=$breitengrad,$laengengrad&zoom=13&size=800x450&sensor=false' alt='Kartenausschnitt Zoom 14'>
						<img border='0' src='http://maps.googleapis.com/maps/api/staticmap?center=$breitengrad,$laengengrad&markers=$breitengrad,$laengengrad&zoom=16&size=800x450&sensor=false' alt='Kartenausschnitt Zoom 17'><br><br>
						Link zum Kartenausschnitt: <a href='http://maps.googleapis.com/maps/api/staticmap?center=$breitengrad,$laengengrad&markers=$breitengrad,$laengengrad&zoom=15&size=800x450&sensor=false'>http://maps.googleapis.com/maps/api/staticmap?center=$breitengrad,$laengengrad&markers=$breitengrad,$laengengrad&zoom=12&size=800x450&sensor=false</a><br><br>
						<a href='http://www.i-use-my-brain.com/eintragen.php?schluessel=$schluessel&entry=0&art=$art'>Verwerfen</a> <a href='http://www.i-use-my-brain.com/eintragen.php?schluessel=$schluessel&entry=1&art=$art'>In Datenbank eintragen</a>
					</div>
				</body>
			</html>";
		// Mail versenden
		mail($empfaengerWebmaster, $betreffWebmaster, $textWebmaster, $headerWebmaster);
		echo "ok"; exit;
	}
	// Wenn der Datensatz nicht erfolgreich eingetragen wurde
	else{
		echo "fehlerEintrag"; exit;
	}
}
else{
	echo "nichtAusgefuellt"; exit;
}