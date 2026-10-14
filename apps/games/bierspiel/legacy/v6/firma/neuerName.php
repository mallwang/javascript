<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Variable initialisieren
$id = $_POST["id"];
$nameNeu = $_POST["nameNeu"];
// Prüfen, ob Firma auch der Besitzer des Gebäudes ist
$besitzer = anfrage("SELECT COUNT(id), name FROM gebaeude WHERE firma_id = '$firmaId' AND id = $id");
// Wenn aktueller User auch Besitzer des Gebäudes ist
if ($besitzer[0] == 1){
	// Wenn ein Wert geändert wurde
	if ($besitzer[1] != $nameNeu){
		// Eintrag in die Datenbank
		$datenGebaeude = anfrage("UPDATE `gebaeude` SET `name`='$nameNeu' WHERE id = '$id'");
		// Wenn Update erfolgreich war
		if ($datenGebaeude){
			echo "ok";
		}
		// Wenn Update nicht erfolgreich war
		else{
			echo "fehlerDateneintrag";
		}
	}
	// Wenn kein Wert geändert wurde
	else{
		echo "keinWertGeaendert";
	}
}
// Wenn aktueller User nicht Besitzer des Gebäudes ist
else{
	echo "nichtBesitzer";
}