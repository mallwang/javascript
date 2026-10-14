<?php include 'connect.php';


/*********************************************************************************
 *********************************************************************************
 ***************************** Datenbank Funktionen ******************************
 *********************************************************************************
 ********************************************************************************/

// DB-Anfrage kommt rein
function anfrage($query){
// 	echo $query;
	// Query wird geprüft, ob Select oder anderes Statement
	$goodQuery = mysql_escape_string($query);
	$result = checkQuery ($query);
	if ($result){
		return $result;
	}
	else{
		return false;
	}
}

// Pruefung der Query ob Select oder anderes Statement
function checkQuery ($goodQuery){
	// echo $query;
	// Wenn ein Select darin zu finden ist
	if (preg_match("/^select/i", $goodQuery)){
// 		echo "inIfCheckQuery";
		// Die Query wird weitergeleitet zum Select
		$result = doSelect ($goodQuery);
		if ($result){
// 			echo "inTrue";
			return $result;
		}
		else{
// 			echo "inFalse";
			return false;
		}
	}
	else{
// 		echo "inElseCheckQuery";
		// Die Query wird weitergeleitet zum Bearbeiten
		$result = doQuery ($goodQuery);
// 		echo $goodQuery;
		if ($result){
			return $result;
		}
		else{
			//echo "Fehler bei der Anfrage! Keine Datensätze geändert";
		}
	}
}

// Das Statement wird verarbeitet
function doQuery ($goodQuery){
	//echo $query;
	if ($result = mysql_query ($goodQuery)){
		$aff_rows = mysql_affected_rows();
		if ($aff_rows){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		die (mysql_error());
	}
}

// Das Select wird verarbeitet
function doSelect($goodQuery){
// 	echo $goodQuery;
	$result = mysql_query($goodQuery);
	// echo $result;
	$a = mysql_fetch_array($result, MYSQL_NUM);
	if($result){
		return $a;
	}
	else{
		return false;
	}
}



/********************************************************************************
 ********************************************************************************
 *************************** Arbeitnehmer Funktionen ****************************
 ********************************************************************************
 *******************************************************************************/

// zufaelligen Namen fuer AN erstellen
function erstelleName(){
	// Dateien fuer Namensbildung laden
	$vornamen = file ('vornamen.txt');
	$nachname = file ('nachnamen.txt');
	// Zufallszahlen anhand Dateiinhalt definieren
	$randVornamen=rand(0, 353);
	$randNachnamen=rand(0, 4804);
	// Array "name" erstellen und zurueckgeben
	$name = array();
	$name[] = $vornamen[$randVornamen];
	$name[] = $nachname[$randNachnamen];
	return $name;
}

// Auszubildenden erstellen (Vorname, Nachname, Alter, Arbeitswert, Talent, Gehalt)
function erstelleAuszubildenen(){
	$azubi = erstelleName();
	// zufaelliges Alter berechnen
	$alter = rand(16, 22);
	// alter dem Array "azubi" hinzufuegen
	$azubi[] = $alter;
	// Array "arbeitswert" erstellen und mit Werten fuellen
	$arbeitswert = file ('arbeitswertAzubi.txt');
	// zufaelligen Arbeitswert erstellen
	$randArbeitswert = rand(0, 29);
	// arbeitswert dem Array "azubi" hinzufuegen
	$azubi[] = $arbeitswert[$randArbeitswert];
	// Array "talent" erstellen und mit Werten fuellen 
	$talent = file ('talent.txt');
	// Zufallszahl erstellen
	$randTalent = rand(0, 19);
	// "talent" dem Array "azubi" hinzufuegen
	$azubi[] = $talent[$randTalent];
	// Gehalt festlegen (800 im Ersten Jahr)
	$azubi[] = 800.00;
	// Arbeitsgebiet festlegen
	$azubi[] = 1;
	// Array "azubi" zurueckgeben
	return $azubi;
}

// ungelernten Arbeitnehmer erstellen (Vorname, Nachname, Alter, Arbeitswert, Talent, Gehalt)
function erstelleUngelernteArbeitskraft(){
	$ungelernterAN = erstelleName();
	// zufaelliges Alter berechnen
	$alter = rand(23, 35);
	// alter dem Array "azubi" hinzufuegen
	$ungelernterAN[] = $alter;
	// Array "arbeitswert" erstellen und mit Werten fuellen
	$arbeitswert = file ('arbeitswertUngelernteAN.txt');
	// zufaelligen Arbeitswert erstellen
	$randArbeitswert = rand(0, 79);
	// arbeitswert dem Array "ungelernterAN" hinzufuegen
	$ungelernterAN[] = $arbeitswert[$randArbeitswert];
	// Array "talent" erstellen und mit Werten fuellen
	$talent = file ('talent.txt');
	// zufaelliges Talent erstellen
	$randTalent = rand(0, 19);
	// "talent" dem Array "ungelernterAN" hinzufuegen
	$ungelernterAN[] = $talent[$randTalent];
	// Gehalt festlegen (1300 Grundlohn + arbeitswertabhaengiger Bonus)
	$gehalt = 1300 + ($arbeitswert[$randArbeitswert] - 34) * 20;
	// Gehalt hinzufuegen
	$ungelernterAN[] = $gehalt;
	// Arbeitsgebiet festlegen
	$arbeitsgebiet = rand(2, 4);
	$ungelernterAN[] = $arbeitsgebiet;
	// Array "ungelernterAN" zurueckgeben
	return $ungelernterAN;	
}

// angelernten Arbeitnehmer erstellen (Vorname, Nachname, Alter, Arbeitswert, Talent, Gehalt)
function erstelleAngelernteArbeitskraft(){
	$angelernterAN = erstelleName();
	// zufaelliges Alter berechnen
	$alter = file ('alterAngelernte.txt');
	// zufaelliges Alter erstellen
	$randAlter = rand(0, 152);
	// Alter dem Array "angelernterAN" hinzufuegen
	$angelernterAN[] = $alter[$randAlter];
	// Array "arbeitswert" erstellen und mit Werten fuellen
	$arbeitswert = file ('arbeitswertAngelernteAN.txt');
	// zufaelligen Arbeitswert erstellen
	$randArbeitswert = rand(0, 179);
	// arbeitswert dem Array "ungelernterAN" hinzufuegen
	$angelernterAN[] = $arbeitswert[$randArbeitswert];
	// Array "talent" erstellen und mit Werten fuellen
	$talent = file ('talent.txt');
	// zufaelliges Talent erstellen
	$randTalent = rand(0, 19);
	// "talent" dem Array "ungelernterAN" hinzufuegen
	$angelernterAN[] = $talent[$randTalent];
	// Gehalt festlegen (1700 Grundlohn + arbeitswertabhaengiger Bonus)
	$gehalt = 1700 + ($arbeitswert[$randArbeitswert] - 44) * 45;
	// Gehalt dem Array "angelernterAN" hinzufuegen
	$angelernterAN[] = $gehalt;
	// Arbeitsgebiet festlegen
	$arbeitsgebiet = rand(2, 4);
	$angelernterAN[] = $arbeitsgebiet;
	// Array "ungelernterAN" zurueckgeben
	return $angelernterAN;
}

// neuen Arbeitnehmer (aller Art) hinzufuegen
// Art: 0=Azubi, 1=ungelernt, 2=angelernt/Berufsausbildung, 3=Studium/Meister
function fuegeANHinzu($art, $menge){
	for ($i=0; $i<$menge; $i++){
		switch ($art){
			case 0: $name=erstelleAuszubildenen(); break;
			case 1: $name=erstelleUngelernteArbeitskraft(); break;
			case 2: $name=erstelleAngelernteArbeitskraft(); break;
		}
		$query = "INSERT INTO `bierspiel`.`arbeitnehmer` (`id`, `art`, `arbeitsgebiet`, `vorname`, `nachname`, `alter`, `arbeitswert`, `grundwert`, `talent`, `gehalt`, `arbeitgeber`)
		VALUES (NULL, '$art', $name[6], '$name[0]', '$name[1]', '$name[2]', '$name[3]', '$name[3]', '$name[4]', '$name[5]', '-1')";
		//echo $query;
		anfrage($query);
	}
}


function decimalUmwandeln($wert){
	$wert = substr($wert, 0, (strlen($wert)-3)) . "," . substr($wert, (strlen($wert)-2), 2);
	$nachkommalaenge = strlen($wert)-6;
	if($nachkommalaenge > 0){
		if($nachkommalaenge%3 != 0){
			$anzahlEinfuegungen = intval(($nachkommalaenge/3)+1);
		}
		else{
			(int) $anzahlEinfuegungen = intval($nachkommalaenge/3);
		}
	}
	else{
		$anzahlEinfuegungen = 0;
	}
// 	echo "AnzahlEinfuegungen :" . $anzahlEinfuegungen;
	// Tausenderpunkte einfügen
	for($i=0; $i<$anzahlEinfuegungen; $i++){
// 		echo "Durchlauf: " . ($i+1)  . "  ";
		$suffix = substr($wert, (-6 - ($i*4)));
// 		echo $suffix;
		$praefix = substr($wert, 0, (-6 - ($i*4)));
// 		echo $praefix;
		$wert = $praefix . "." . $suffix;
	}
	return $wert;
}


function berechneArbeitswert($id, $zufriedenheit){
	// Grundwert + Art des Arbeitnehmers abfragen
	$werteArbeitnehmer = anfrage("SELECT grundwert, art FROM arbeitnehmer WHERE id = '$id'");
	// Neuen Arbeitswert setzen
	$arbeitswert = $werteArbeitnehmer[0];
	if(($zufriedenheit>=91) AND ($zufriedenheit<=100)){
		$arbeitswert = (int) ($werteArbeitnehmer[0]  * 1.1);
	}
	elseif(($zufriedenheit>=81) AND ($zufriedenheit<=90)){
		$arbeitswert = (int) ($werteArbeitnehmer[0] * 1.05);
	}
	elseif(($zufriedenheit>=71) AND ($zufriedenheit<=80)){
		$arbeitswert = (int) $werteArbeitnehmer[0];
	}
	elseif(($zufriedenheit>=51) AND ($zufriedenheit<=70)){
		$arbeitswert = (int) ($werteArbeitnehmer[0] * 0.95);
	}
	elseif(($zufriedenheit>=31) AND ($zufriedenheit<=50)){
		$arbeitswert = (int) ($werteArbeitnehmer[0] * 0.75);
	}
	elseif(($zufriedenheit>=1) AND ($zufriedenheit<=30)){
		$arbeitswert = (int) ($werteArbeitnehmer[0] * 0.5);
	}
	elseif($zufriedenheit==0){
		$arbeitswert = 0;
	}
	// je nach Art Maxima
	if($werteArbeitnehmer[1]==0){
		if($arbeitswert > 30){
			$arbeitswert = 30;
		}
	}
	elseif($werteArbeitnehmer[1]==1){
		if($arbeitswert > 50){
			$arbeitswert = 50;
		}
	}
	elseif($werteArbeitnehmer[1]==2){
		if($arbeitswert > 75){
			$arbeitswert = 75;
		}
	}
	elseif($werteArbeitnehmer[1]==3){
		if($arbeitswert > 100){
			$arbeitswert = 100;
		}
	}
	
	return $arbeitswert;
}


function grundgehaltBerechnen($id, $grundwert){
	// Normales Gehalt berechnen auf Grundlage der Art
	$art = anfrage("SELECT art FROM arbeitnehmer WHERE id='$id'");
	switch ($art[0]) {
		case 0: $grundgehalt=800;	$zuschlag=0;	$startwert=$grundwert;	break;
		case 1: $grundgehalt=1300;	$zuschlag=20;	$startwert=34;				break;
		case 2: $grundgehalt=1700;	$zuschlag=45;	$startwert=44;				break;
		case 3: $grundgehalt=3200;	$zuschlag=100;	$startwert=69;				break;
	}
	$gehalt = $grundgehalt + ($grundwert - $startwert) * $zuschlag;
	return $gehalt;
}


function neueZufr($id, $gehaltNeu, $grundwert){
	// Grundgehalt
	$grundgehalt = grundgehaltBerechnen($id, $grundwert);
	echo "Grundgehalt : " . $grundgehalt;
	// Faktor berechnen
	$faktor = (int) (($gehaltNeu/$grundgehalt)*100-100);
	if ($faktor>=0){
		$zufrVeraender = (int) ($faktor/2);
	}
	else{
		if($faktor<(-50)){
			$zufrVeraender = 71;
		}
		else{
			$zufrVeraender = (int) ($faktor/0.71);
		}
	}
	return $zufrVeraender;
}


function getNeueZufr($id, $zufrVeraender, $aktZufr){
	// Grundzufriedenheit = 71

	$zufriedenheitNeu = 71+$zufrVeraender;
	
	if($zufriedenheitNeu<=0){
		$zufriedenheitNeu = 0;
	}
	elseif($zufriedenheitNeu>100){
		$zufriedenheitNeu = 100;
	}
	
	
	
/*
	if($aktZufr>90){
		$zufriedenheitNeu = $aktZufr;
	}
	else{
		if(($aktZufr+$zufrVeraender)>90){
			$zufriedenheitNeu = 90;
		}
		elseif(($aktZufr+$zufrVeraender)<=0){
			$zufriedenheitNeu = 0;
		}
		else{
			$zufriedenheitNeu = 71+$zufrVeraender;
		}
	}
*/
// 	echo "Neue Zufriedenheit: " . $zufriedenheitNeu;
	return $zufriedenheitNeu;
}
?>