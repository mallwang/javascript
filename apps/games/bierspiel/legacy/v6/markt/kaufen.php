<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Variablen initialisieren
$id = $_POST["id"];
$menge = $_POST["menge"];
$ware = $_POST["ware"];
// Artikel abfragen
$artikel = anfrage("SELECT menge, preis, anbieter_id FROM $ware WHERE id = '$id'");
// Wenn Anbieter != Käufer
if ($artikel[2] != $firmaId){
	// Kontostand abfragen
	$kontostand = anfrage("SELECT konto FROM finanzen WHERE firma_id = '$firmaId'");
	// Abfragen ob die angefragte Menge noch existiert
	// Wenn die Menge noch existiert
	if ($artikel[0] >= $menge){
		// Gesamtkosten berechnen
		$gesamtkosten = $artikel[1] * $menge;
		// Wenn Finanzen ausreichen
		if ($kontostand[0] >= $gesamtkosten){
			// Neue Menge berechnen
			$mengeNeu = $artikel[0] - $menge;
			// Wenn Neue Menge == 0
			if($mengeNeu == 0){
				// Angebot löschen
				$angebot = anfrage("DELETE FROM $ware WHERE id = '$id'");
			}
			// Wenn noch Ware vorhanden nach Kauf
			else{
				// Update Angebot
				$angebot = anfrage("UPDATE $ware SET menge = '$mengeNeu' WHERE id = '$id'");
			}
			// Wenn Angeboteintrag erfolgreich
			if ($angebot){
				// Finanzen des Empfängers abfragen
				$kontostandEmpf = anfrage("SELECT konto FROM finanzen WHERE firma_id = '$artikel[2]'");
				// Finanzen Empfänger berechnen
				$finanzenEmpfNeu = $kontostandEmpf[0] + $gesamtkosten;
				// Finanzen Empfänger updaten
				$finanzupdateEmpf = anfrage("UPDATE finanzen SET konto = '$finanzenEmpfNeu' WHERE firma_id = '$artikel[2]'");
				// Wenn Finanzupdate Empfänger erfolgreich
				if ($finanzupdateEmpf){
					// Finanzen Käufer berechnen
					$finanzenNeu = $kontostand[0] - $gesamtkosten;
					// Finanzen Käufer updaten
					$finanzupdate = anfrage("UPDATE finanzen SET konto = '$finanzenNeu' WHERE firma_id = '$firmaId'");
					// Wenn Finanzupdate erfolgreich
					if ($finanzupdate){
						// Lagerbestand abfragen
						$lagerbestand = anfrage("SELECT $ware FROM lager WHERE firma_id = '$firmaId'");
						// Neuen Lagerbestand berechnen
						$lagerbestandNeu = $lagerbestand[0] + $menge;
						// Lagerbestandupdate
						$lagerupdate = anfrage("UPDATE lager SET $ware = '$lagerbestandNeu' WHERE firma_id = '$firmaId'");
						// Wenn Lagerupdate erfolgreich
						if ($lagerupdate){
							echo "ok";
						}
						// Wenn Lagerupdate nicht erfolgreich
						else{
							echo "lagerbestandUpdateFehlgeschlagen";
						}
					}
					// Wenn Finanzupdate nicht erfolgreich
					else{
						echo "finanzUpdateKaufFehlgeschlagen";
					}
				}
				// Wenn Finanzupdate Empfänger nicht erfolgreich
				else{
					echo "finanzUpdateEmpfFehlgeschlagen";
				}
			}
			// Wenn Angebotseintrag nicht erfolgreich
			else{
				echo "angebotsUpdateFehlgeschlagen";
			}
		}
		// Wenn Finanzen nicht ausreichen
		else{
			echo "finanzenNichtAusreichend";
		}
	}
	// Wenn die Menge nicht mehr existiert
	else{
		echo "mengeNichtVerfuegbar";
	}
}
// Wenn Anbieter == Käufer
else{
	echo "anbieterIstEmpfaenger";
}