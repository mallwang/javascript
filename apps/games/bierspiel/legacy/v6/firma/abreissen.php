<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Variable initialisieren
$id = $_POST["id"];
// Prüfen, ob Firma auch der Besitzer des Gebäudes ist
$besitzer = anfrage("SELECT COUNT(id) FROM gebaeude WHERE firma_id = '$firmaId' AND id = $id");
// Wenn aktueller User auch Besitzer des Gebäudes ist
if ($besitzer[0] == 1){
	// Gebäude aus Tabelle löschen
	$dateneintrag = anfrage("DELETE FROM `gebaeude` WHERE id = $id");
	// Wenn Dateneintrag ok
	if ($dateneintrag){
			echo "ok";
	}
	// Wenn Dateneintrag nicht ok
	else{
		echo "fehlerDateneintrag";
	}
}
// Wenn aktueller User nicht Besitzer des Gebäudes ist
else{
	echo "nichtBesitzer";
}