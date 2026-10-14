<?php

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

// Variablen abfragen
$tabelle = $_GET['tabelle'];
$stadt = $_GET['stadt'];

// ID der Stadt abfragen
$idAnfrage = mysql_query("SELECT `id` FROM staedte WHERE `name` = '$stadt'");

$anzahlDaten = mysql_num_rows($idAnfrage);

// Wenn es genau einen Eintrag gibt
if ($anzahlDaten == 1){
	$id = mysql_fetch_array($idAnfrage);
	$id = $id['id'];
	
	$sql = "SELECT `name`, `beschreibung`, `lng`, `lat` FROM `" . $tabelle . "` WHERE id = $id";
	
	$allePlaetze = mysql_query($sql);		
	
	// Wenn es mindestens einen Eintrag gibt
	if (mysql_num_rows($allePlaetze) > 0){
		
		// Start des Selectfeldes
		$rueckgabe = "<?xml version='1.0' encoding='UTF-8'?><datensaetze>";
		
		// Array wird in einer Schleife gef�llt
		while($row = mysql_fetch_array($allePlaetze)){
			// F�r jeden Eintrag ein Optionsfeld erstellen
			$rueckgabe .= "<objekt><name>" . $row['name'] . "</name><beschreibung>" . $row['beschreibung'] . "</beschreibung><lat>" . $row['lat'] . "</lat><long>" . $row['lng'] . "</long></objekt>";
		}
		
		// Ende des Selectfeldes
		$rueckgabe .= "</datensaetze>";
		
		echo $rueckgabe;
	}
	
	// Wenn es keinen Eintrag gibt
	else{
		echo "Kein Objekt gefunden";
	}
}

// Wenn es keinen Eintrag gibt
else if ($anzahlDaten == 0){
	echo "Keine Stadt gefunden";
}

// Wenn es mehrere Eintrag gibt
else if ($anzahlDaten > 1){
	echo "Mehrere Staedte gefunden";
}

?>