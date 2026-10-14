<?php include 'funktionen.php';

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

// Daten abfragen
$schluessel = $_GET["schluessel"];
$entry = $_GET["entry"];
$art = $_GET["art"];
// Daten zur Kontrolle ausgeben
//echo "<b>Folgende Daten wurden &uuml;bergeben:</b><br>Schl&uuml;ssel: $schluessel<br>Entry: $entry<br>Art: $art";
// Wenn alle Daten ausgef�llt sind
if ($schluessel != "" && $entry != "" && $art != ""){
	// Fallunterscheidung
	$tabelle_alt;
	switch ($art) {
		case "S": $tabelle_alt = "sehensw_neu"; break;
		case "P": $tabelle_alt = "parks_neu"; break;
		case "M": $tabelle_alt = "museen_neu"; break;
		default: $tabelle_alt = false; break;
	}
	if(!$tabelle_alt){
		echo "keineArt"; exit;
	}
	// Vorhandenen Eintrag abfragen
	//echo "<br><br>Datenbankquery: SELECT COUNT(`name`) AS anzahl, `name`, `ort`, `bundesland`, `beschreibung`, `long`, `lat` FROM $tabelle_alt WHERE `key` = '$schluessel'";
	$eintragAnfrage = anfrage("SELECT COUNT(`name`) AS anzahl, `name`, `ort`, `bundesland`, `beschreibung`, `lng`, `lat` FROM $tabelle_alt WHERE `key` = '$schluessel'");
	// Wenn es einen Datensatz gibt
	if ($eintragAnfrage[0] == 1){
		// Wenn entry = 1 --> Datensatz in richtige Tabelle eintragen
		if ($entry == 1){
			// Variabeln zuweisen
			$name = $eintragAnfrage[1];
			$ort = $eintragAnfrage[2];
			$bundesland = $eintragAnfrage[3];
			$beschreibung = $eintragAnfrage[4];
			$long = $eintragAnfrage[5];
			$lat = $eintragAnfrage[6];
			// Fallunterscheidung
			$tabelle_neu;
			switch ($art) {
				case "S": $tabelle_neu = "sehensw"; break;
				case "P": $tabelle_neu = "parks"; break;
				case "M": $tabelle_neu = "museen"; break;
				default: $tabelle_neu = false; break;
			}
			if(!$tabelle_neu){
				echo "keineArt"; exit;
			}
			// Daten zur Kontrolle ausgeben
			echo "<br><b>Folgende Daten wurden in der Datenbank gefunden:</b><br>Name: $name<br>Ort: $ort<br>Bundesland: $bundesland<br>
			Beschreibung: $beschreibung<br>L&auml;ngengrad: $long<br>Breitengrad: $lat";
			// Id der Stadt abfragen
			//echo "<br>Datenbankquery: SELECT `id` FROM staedte WHERE `name` = '$ort' AND `bl` = '$bundesland' AND `kreis` = '$kreis'";
			$idAnfrage = anfrage("SELECT `id` FROM staedte WHERE `name` = '$ort' AND `bl` = '$bundesland'");
			$id = $idAnfrage[0];
			// Neuen Datensatz eintragen
			//echo "<br>Datenbankquery: INSERT INTO $tabelle_neu (`id`, `name`, `beschreibung`, `lng`, `lat`)
				//VALUES ('$id', '$name', '$beschreibung', '$long', '$lat')";
			$dateneintrag = anfrage("INSERT INTO $tabelle_neu (`id`, `name`, `beschreibung`, `lng`, `lat`)
				VALUES ('$id', '$name', '$beschreibung', '$long', '$lat')");
			// Wenn der Dateneintrag ok ist
			if ($dateneintrag){
				// Vorhandenen Eintrag l�schen
				//echo "<br>Datenbankquery: DELETE FROM $tabelle_alt WHERE `key` = '$schluessel'";
				$loeschen = anfrage("DELETE FROM $tabelle_alt WHERE `key` = '$schluessel'");
				// Wenn der alte Datensatz gel�scht wurde
				if ($loeschen){
					echo "<br><br><b style='color: green;'>Die Daten wurden erfolgreich in $tabelle_neu eingetragen und aus $tabelle_alt gel&ouml;scht!</b>";
				}
				// Wenn der alte Datensatz nicht gel�scht wurde
				else{
					echo "<br>Die Daten wurden in $tabelle_neu eingetragen aber <b>nicht</b> aus $tabelle_alt gel&ouml;scht!";
				}
			}
			// Wenn es einen Fehler beim Dateneintrag gibt
			else{
				echo "<br>Fehler beim Dateneintrag"; exit;
			}
		}
		// Wenn entry = 0 --> Datensatz l�schen
		else if ($entry == 0){
			// Vorhandenen Eintrag l�schen
			//echo "<br>Datenbankquery: DELETE FROM $tabelle_alt WHERE `key` = '$schluessel'";
			$eintragAnfrage = anfrage("DELETE FROM $tabelle_alt WHERE `key` = '$schluessel'");
			echo "<br><b style='color: green;'>Der Datensatz wurde aus $tabelle_alt gel&ouml;scht!</b>";
		}
	}
	// Wenn es keine Stadt gibt
	else if ($eintragAnfrage[0] == 0){
		echo "<br><b style='color: red;'>Stadt existiert nicht!</b>"; exit;
	}
	// Wenn es mehr als eine Stadt gibt
	else{
		echo "<br><b style='color: red;'>Stadt ist mehrmals vorhanden!</b>"; exit;
	}
}
// Wenn nicht alle Daten ausgef�llt sind
else{
	echo "<br>Fehlerhafte Daten"; exit;
}
?>