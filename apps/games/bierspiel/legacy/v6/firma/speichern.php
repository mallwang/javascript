<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Variable initialisieren
$id = $_POST["id"];
$stilIndex = $_POST["stilIndex"];
$pzgIndex = $_POST["pzgIndex"];
$szgIndex = $_POST["szgIndex"];
$spIndex = $_POST["spIndex"];
$tmIndex = $_POST["tmIndex"];
$twIndex = $_POST["twIndex"];
// Prüfen, ob Firma auch der Besitzer des Gebäudes ist
$besitzer = anfrage("SELECT COUNT(id), stil, pzg, szg, sp, tm, tw FROM gebaeude WHERE firma_id = '$firmaId' AND id = $id");
// Wenn aktueller User auch Besitzer des Gebäudes ist
if ($besitzer[0] == 1){
	// Wenn Index für Stil, Sitzplätze, TM oder TW ausgewählt wurde
	if (($stilIndex != "0") && ($tmIndex != "0") && ($twIndex != "0") && ($spIndex != "0")){
		// Stil
		switch ($stilIndex){
			case "1":
				$stilName = "Modern";
				break;
			case "2":
				$stilName = "Rustikal";
				break;
			case "3":
				$stilName = "Edel";
				break;
			case "4":
				$stilName = "keinen";
				break;
			case "5":
				$stilName = "keinen";
				break;
			case "6":
				$stilName = "keinen";
				break;
		}
		// Primäre Zielgruppe
		switch ($pzgIndex){
			case "0":
				$pzgName = "keine";
				break;
			case "1":
				$pzgName = "Familie";
				break;
			case "2":
				$pzgName = "Teenager";
				break;
			case "3":
				$pzgName = "Junge Erwachsene";
				break;
			case "4":
				$pzgName = "Erwachsene";
				break;
			case "5":
				$pzgName = "Rentner";
				break;
		}
		// Sekundäre Zielgruppe
		switch ($szgIndex){
			case "0":
				$szgName = "keine";
				break;
			case "1":
				$szgName = "Familie";
				break;
			case "2":
				$szgName = "Teenager";
				break;
			case "3":
				$szgName = "Junge Erwachsene";
				break;
			case "4":
				$szgName = "Erwachsene";
				break;
			case "5":
				$szgName = "Rentner";
				break;
		}
		// Sitzplätze
		switch ($spIndex){
			case "1":
				$spAnzahl = "24";
				break;
			case "2":
				$spAnzahl = "28";
				break;
			case "3":
				$spAnzahl = "32";
				break;
			case "4":
				$spAnzahl = "36";
				break;
			case "5":
				$spAnzahl = "40";
				break;
			case "6":
				$spAnzahl = "44";
				break;
			case "7":
				$spAnzahl = "48";
				break;
			case "8":
				$spAnzahl = "52";
				break;
			case "9":
				$spAnzahl = "56";
				break;
			case "10":
				$spAnzahl = "60";
				break;
			case "11":
				$spAnzahl = "64";
				break;
			case "12":
				$spAnzahl = "68";
				break;
			case "13":
				$spAnzahl = "72";
				break;
			case "14":
				$spAnzahl = "76";
				break;
			case "15":
				$spAnzahl = "80";
				break;
			case "16":
				$spAnzahl = "84";
				break;
			case "17":
				$spAnzahl = "88";
				break;
			case "18":
				$spAnzahl = "92";
				break;
			case "19":
				$spAnzahl = "96";
				break;
			case "20":
				$spAnzahl = "100";
				break;
			case "21":
				$spAnzahl = "104";
				break;
			case "22":
				$spAnzahl = "108";
				break;
			case "23":
				$spAnzahl = "112";
				break;
			case "24":
				$spAnzahl = "116";
				break;
			case "25":
				$spAnzahl = "120";
				break;
		}
		// Wenn ein Wert geändert wurde
		if (($besitzer[1] != $stilName) || ($besitzer[2] != $pzgName) || ($besitzer[3] != $szgName) || ($besitzer[4] != $spAnzahl) || ($besitzer[5] != $tmIndex) || ($besitzer[6] != $twIndex)){
			// Umbaukosten für den Stil berechnen
			if ($besitzer[1] != $stilName){
				$umbaukosten += 10000;
			}
			if ($besitzer[4] != $spAnzahl){
				$aenderungSP = $spAnzahl - $besitzer[4];
				if ($aenderungSP > 0){
					$umbaukosten += $aenderungSP * 50;
				}
				else{
					$umbaukosten += $aenderungSP * 20;
				}
			}
			if ($besitzer[5] != $tmIndex){
				$aenderungTM = $tmIndex - $besitzer[5];
				if ($aenderungTM > 0){
					$umbaukosten += $aenderungTM * 500;
				}
				else{
					$umbaukosten += $aenderungTM * -200;
				}
			}
			if ($besitzer[6] != $twIndex){
				$aenderungTW = $twIndex - $besitzer[6];
				if ($aenderungTW > 0){
					$umbaukosten += $aenderungTW * 500;
				}
				else{
					$umbaukosten += $aenderungTW * -200;
				}
			}
			// Kontostand abfragen
			$kontostand = anfrage("SELECT konto, a_gebaeudeumbau, a_gesamt, e_gebaeudeumbau, e_gesamt FROM finanzen WHERE firma_id = '$firmaId'");
			// Wenn Kontostand ausreicht
			if ($kontostand[0] >= $umbaukosten){
				// Wenn Umbaukosten anfallen
				if ($umbaukosten > 0){
					// Gesamte Umbaukosten errechnen
					$umbaukostenGesamtNeu = ($kontostand[1] + $umbaukosten);
					// Gesamtausgaben errechnen
					$ausgabenNeu = ($kontostand[2] + $umbaukosten);
					// FinanzenNeu berechnen
					$finanzenNeu = $kontostand[0] - $umbaukosten;
					// Finanzen updaten
					$finanzupdate = anfrage("UPDATE finanzen SET `konto` = '$finanzenNeu', `a_gebaeudeumbau` = '$umbaukostenGesamtNeu', `a_gesamt` = '$ausgabenNeu' WHERE firma_id = '$firmaId'");
				}
				// Wenn Umbaukosten kleiner null
				else if($umbaukosten < 0){
					// Gesamte Umbauerträge errechnen
					$umbauertraegeGesamtNeu = ($kontostand[3] - $umbaukosten);
					// Gesamterträge errechnen
					$einnahmenNeu = ($kontostand[4] - $umbaukosten);
					// FinanzenNeu berechnen
					$finanzenNeu = $kontostand[0] - $umbaukosten;
					// Finanzen updaten
					$finanzupdate = anfrage("UPDATE finanzen SET `konto` = '$finanzenNeu', `e_gebaeudeumbau` = '$umbauertraegeGesamtNeu', `e_gesamt` = '$einnahmenNeu' WHERE firma_id = '$firmaId'");
				}
				else{
					$finanzupdate = true;
				}
				// Wenn Finanzupdate erfolgreich
				if ($finanzupdate){
					// Eintrag in die Datenbank
					$datenGebaeude = anfrage("UPDATE `gebaeude` SET `stil`='$stilName',`pzg`='$pzgName',`szg`='$szgName',`sp`='$spAnzahl',`tm`='$tmIndex',`tw`='$twIndex' WHERE id = '$id'");
					// Wenn Update erfolgreich war
					if ($datenGebaeude){
						echo "ok";
					}
					// Wenn Update nicht erfolgreich war
					else{
						echo "fehlerDateneintrag";
					}
				}
				// Wenn Finanzupdate nicht erfolgreich
				else{
					echo "finanzUpdateKaufFehlgeschlagen";
				}
			}
			// Wenn Finanzen nicht ausreichen
			else{
				echo "finanzenNichtAusreichend";
			}
		}
		// Wenn kein Wert geändert wurde
		else{
			echo "keinWertGeaendert";
		}
	}
	// Wenn Index für Stil, Sitzplätze, TM oder TW nicht ausgewählt wurde
	else{
		echo "keinenIndex";
	}
}
// Wenn aktueller User nicht Besitzer des Gebäudes ist
else{
	echo "nichtBesitzer";
}