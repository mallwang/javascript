<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Variable initialisieren
$id = $_POST["id"];
// Prüfen, ob Firma auch der Besitzer des Gebäudes ist
$besitzer = anfrage("SELECT COUNT(id), baukosten FROM gebaeude WHERE firma_id = '$firmaId' AND id = $id");
// Wenn aktueller User auch Besitzer des Gebäudes ist
if ($besitzer[0] == 1){
	// Hälfte der Baukosten berechnen
	$haelfte = $besitzer[1] / 2;
	// Gebäude aus Tabelle löschen
	$dateneintrag = anfrage("DELETE FROM `gebaeude` WHERE id = $id");
	// Wenn Dateneintrag ok
	if ($dateneintrag){
		// Aktuellen Kontostand abfragen
		$finanzen = anfrage("SELECT konto FROM finanzen WHERE firma_id = '$firmaId'");
		// Neuen Kontostand berechnen
		$kontostandNeu = $finanzen[0] + $haelfte;
		//echo "UPDATE `finanzen` SET `konto`='$kontostandNeu' WHERE firma_id = '$firmaId'";
		// Datenbankeintrag Kontostand
		$datenUser = anfrage("UPDATE `finanzen` SET `konto`='$kontostandNeu' WHERE firma_id = '$firmaId'");
		// Wenn Dateneintrag ok
		if ($datenUser){
			echo "ok";
		}
		// Wenn Dateneintrag nicht ok
		else{
			echo "fehlerKontostandeintrag";
		}
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