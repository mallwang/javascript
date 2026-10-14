<?php session_start(); include 'funktionen.php';

// Datenbankwerte
$dbhost="DB_HOST";
$dbuser="DB_USER";
$dbpassword="DB_PASSWORD";
$dbdatenbank="DB_NAME";

// Datenbankverbindung �ffnen
if(!($verbindung = mysql_connect("$dbhost", "$dbuser", "$dbpassword"))){
	die("Konnte keine Verbindung zur Datenbank herstellen!");
}

// Datenbank ausw�hlen
if(!(@mysql_select_db("$dbdatenbank", $verbindung))){
	die("Fehler " . mysql_errno() . ": " . mysql_error());
}

// Charakterset "utf8" definieren f�r kommende Datenbankanfragen
mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");

$action = mysql_real_escape_string($_GET["action"]);
$art = mysql_real_escape_string($_GET["art"]);

// Wenn eine Abfrage nach Orten gew�nscht ist
if ($action == 0){
	
	// Variablen Deklarieren
	$tabelle = "staedte";
	$bl_kurz = mysql_real_escape_string($_GET['bundesland']);
	
	// SQL Abfrage
	$sql = "SELECT `name` FROM " . $tabelle . " WHERE bl = '" . $bl_kurz . "' ORDER BY name";
	
	// Anfrage erstellen
	$alleOrte = mysql_query($sql);
	
	// Wenn das Neueintragsformular gew�hlt wurde
	if ($art == 0){
		
		// Wenn es keinen Eintrag gibt
		if (mysql_num_rows($alleOrte) == 0){
			echo "Kein Datensatz vorhanden!";
		}
		
		// Wenn mindestens ein Eintrag vorhanden ist
		else{
			
			// Start des Selectfeldes
			echo "<select id='selectOrtsname' onchange='changeCity(\"0\", this.value, this.selectedIndex);'><option value=''>Bitte w&auml;hlen</option>";
			
			// Array wird in einer Schleife gef�llt
			while($row = mysql_fetch_array($alleOrte)){
				
				// F�r jeden Eintrag ein Optionsfeld erstellen
				echo "<option>" . $row['name'] . "</option>";
			}
			
			// Ende des Selectfeldes
			echo "</select>";
		}
	}
	
	// Wenn das �nderungsformular gew�hlt wurde
	else if ($art == 1){
		
		// Wenn es keinen Eintrag gibt
		if (mysql_num_rows($alleOrte) == 0){
			echo "Kein Datensatz vorhanden!";
		}
		
		// Wenn mindestens ein Eintrag vorhanden ist
		else{
			
			// Start des Selectfeldes
			echo "<select id='selectOrtsnameAenderung' onchange='changeCity(\"1\", this.value, this.selectedIndex);'><option value=''>Bitte w&auml;hlen</option>";
			
			// Array wird in einer Schleife gef�llt
			while($row = mysql_fetch_array($alleOrte)){
				
				// F�r jeden Eintrag ein Optionsfeld erstellen
				echo "<option>" . $row['name'] . "</option>";
			}
			
			// Ende des Selectfeldes
			echo "</select>";
		}
	}
}

// Wenn eine Abfrage nach Sehensw�rdigkeiten, Parks oder Museen gew�nscht ist
else if ($action == 1){
	
	// Variablen holen
	$tabelle = mysql_real_escape_string($_GET['tabelle']);
	$name = mysql_real_escape_string($_GET['name']);
	$bundesland = mysql_real_escape_string($_GET['bundesland']);
	
	// Stadt-Id abfragen
	$idAnfrage = anfrage("SELECT `id` FROM staedte WHERE `name` = '$name' AND `bl` = '$bundesland'");
	$id = $idAnfrage[0];
	
	// SQL Abfrage
	$sql = "SELECT `name`, `beschreibung`, `lng`, `lat` FROM " . $tabelle . " WHERE `id` = '" . $id . "' ORDER BY name";
	
	// Anfrage erstellen
	$alleOrte = mysql_query($sql);
	
	// Wenn kein Datensatz verf�gbar ist
	if (mysql_num_rows($alleOrte) == 0){
		echo "Noch kein Datensatz vorhanden!";
	}
	
	// Wenn mindestens ein Eintrag vorhanden ist
	else{
		
		// Start des Selectfeldes
		echo "<select id='selectNameAlt' onchange='showObjectDetails(this.value)'><option value=''>Bitte w&auml;hlen</option>";
		
		// Array wird in einer Schleife gef�llt
		while($row = mysql_fetch_array($alleOrte)){
			
			// F�r jeden Eintrag ein Optionsfeld erstellen
			echo "<option>" . $row['name'] . " (" . $row['lat'] . ", " . $row['lng'] . ")</option>";
		}
		
		// Ende des Selectfeldes
		echo "</select>";
		
		// Start einer Div f�r alle Beschreibungen
		echo "<div id='alleBeschreibungen' style='display: none;'>";
		
		// Array f�r einen n�chsten Durchlauf zur�cksetzen
		mysql_data_seek($alleOrte, 0);
		
		// Array wird in einer Schleife gef�llt
		while($row1 = mysql_fetch_array($alleOrte)){
			
			// F�r jeden Eintrag wird ein separates div erstellt mit der Beschreibung zu dessen Namen
			echo "<div id='beschr_" . $row1['name'] . "'>" . $row1['beschreibung'] . "</div>";
		}
		
		// Ende der Div
		echo "</div>";
	}
}

// Wenn eine Abfrage nach Sehensw�rdigkeiten, Parks oder Museen gew�nscht ist (f�r die Anzeige auf der Karte)
else if ($action == 2){

	// Variablen holen
	$tabelle = mysql_real_escape_string($_GET['tabelle']);
	$name = mysql_real_escape_string($_GET['name']);
	$bundesland = mysql_real_escape_string($_GET['bundesland']);

	// Stadt-Id abfragen
	$idAnfrage = anfrage("SELECT `id` FROM staedte WHERE `name` = '$name' AND `bl` = '$bundesland'");
	$id = $idAnfrage[0];

	// SQL Abfrage
	$sql = "SELECT `name`, `lng`, `lat` FROM " . $tabelle . " WHERE `id` = '" . $id . "' ORDER BY name";

	// Anfrage erstellen
	$alleOrte = mysql_query($sql);

	// Wenn kein Datensatz verf�gbar ist
	if (mysql_num_rows($alleOrte) == 0){
		echo "Nichts";
	}

	// Wenn mindestens ein Eintrag vorhanden ist
	else{

		// Array wird in einer Schleife gef�llt
		while($row = mysql_fetch_array($alleOrte)){
				
			// F�r jeden Eintrag ein Optionsfeld erstellen
			echo $row['name'] . "|" . $row['lat'] . "|" . $row['lng'] . "||";
		}
	}
}

// Wenn eine Abfrage nach Sehensw�rdigkeiten, Parks oder Museen gew�nscht ist (f�r die Anzeige auf der Karte)
else if ($action == 3){

	// Variablen holen
	$tabelle = mysql_real_escape_string($_GET['tabelle']);
	$bundesland = mysql_real_escape_string($_GET['bundesland']);

	// Stadt-Id abfragen
	$idAnfrage = mysql_query("SELECT `id` FROM staedte WHERE `bl` = '$bundesland'");
	
	// F�r alle Ids
	while($row1 = mysql_fetch_array($idAnfrage)){
		
		// SQL Abfrage
		$sql = "SELECT `name`, `lng`, `lat` FROM " . $tabelle . " WHERE `id` = '" . $row1['id'] . "' ORDER BY name";
		
		$alleOrte = mysql_query($sql);
		
		// Array wird in einer Schleife gef�llt
		while($row = mysql_fetch_array($alleOrte)){

			// F�r jeden Eintrag ein Optionsfeld erstellen
			echo $row['name'] . "|" . $row['lat'] . "|" . $row['lng'] . "||";
		}
	}
}

// Wenn eine Abfrage nach Sehensw�rdigkeiten, Parks oder Museen gew�nscht ist (f�r die Anzeige auf der Karte)
else if ($action == 4){

	// Variablen holen
	$tabelle = mysql_real_escape_string($_GET['tabelle']);

	// SQL Abfrage
	$sql = "SELECT `name`, `lng`, `lat` FROM " . $tabelle . " ORDER BY name";

	$alleOrte = mysql_query($sql);

	// Array wird in einer Schleife gef�llt
	while($row = mysql_fetch_array($alleOrte)){

		// F�r jeden Eintrag ein Optionsfeld erstellen
		echo $row['name'] . "|" . $row['lat'] . "|" . $row['lng'] . "||";
	}
}