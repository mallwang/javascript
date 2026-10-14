<?php session_start(); include '../connect.php'; include '../funktionen.php';

// NEUER LAGERBESTAND
if ($_POST["art"] == "lagerbestandUpdate"){
	// Variablen deklarieren
	$alterLB = $_POST["alterLagerbestand"];
	$neuerLB = $_POST["neuerLagerbestand"];
	$ware = $_POST["ware"];
	$firma_id = $_SESSION['firmaId'];
	// Aktuellen Lagerbestand abfragen
	$aktLB = anfrage("SELECT $ware FROM lager WHERE firma_id = $firma_id");
	// Prüfen ob der alte Bestand noch verfügbar ist
	if ($alterLB == $aktLB[0]){
		// Updaten
		$update = anfrage("UPDATE `lager` SET $ware ='$neuerLB' WHERE firma_id = '$firma_id'");
		// Wenn Update erfolgreich war
		if($update){
			echo "ok";
		}
		// Wenn Update nicht erfolgreich war
		else{
			echo "fehlerUpdate";
		}
	}
	// Der alte Bestand ist nicht aktuell
	else{
		echo "bestandNichtAktuell";
	}
}

// MARKT VERKAUF
if ($_POST["art"] == "verkaufMarkt"){
	// Variablen deklarieren
	$alterLB = $_POST["alterLagerbestand"];
	$neuerLB = $_POST["neuerLagerbestand"];
	$menge = $_POST["menge"];
	$ware = $_POST["ware"];
	$einzelpreis = $_POST["einzelpreis"];
	$firma_id = $_SESSION['firmaId'];
	// Aktuellen Lagerbestand abfragen
	$aktLB = anfrage("SELECT $ware FROM lager WHERE firma_id = $firma_id");
	// Prüfen ob der alte Bestand noch verfügbar ist
	if ($alterLB == $aktLB[0]){
		// Prüfen ob schon Angebot existiert
		//echo "SELECT menge FROM $ware WHERE anbieter_id = '$firmenname' AND preis = '$einzelpreis'";
		$mengeVorhanden = anfrage("SELECT id, menge FROM $ware WHERE anbieter_id = '$firma_id' AND preis = '$einzelpreis'");
		// Wenn Menge vorhanden
		if ($mengeVorhanden[1] > 0){
			// Menge erhöhen
			$mengeNeu = $mengeVorhanden[1] + $menge;
			// Updaten
			$eintrag = anfrage("UPDATE $ware SET menge ='$mengeNeu' WHERE id = '$mengeVorhanden[0]'");
		}
		// Wenn noch kein Eintrag vorhanden ist
		else{
			// Eintragen
			$eintrag = anfrage("INSERT INTO $ware (`anbieter_id`, `menge`, `preis`) VALUES ('$firma_id', '$menge', '$einzelpreis')");
		}
		// Updaten
		$update = anfrage("UPDATE `lager` SET $ware ='$neuerLB' WHERE firma_id = '$firma_id'");
		// Wenn Update erfolgreich war
		if($eintrag && $update){
			echo "ok";
		}
		// Wenn Update nicht erfolgreich war
		else{
			echo "fehlerUpdate";
		}
	}
	// Der alte Bestand ist nicht aktuell
	else{
		echo "bestandNichtAktuell";
	}
}