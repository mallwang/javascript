<?php include 'connect.php'; include 'funktionen.php';

// Vorgemerkte User aus Tabelle 'loeschen' auslesen


// Finanzen löschen
$finanzen = anfrage("DELETE FROM `finanzen` WHERE firma_id = '$firmaId'");
// Wenn finanzen gelöscht
if ($finanzen){
	// Firmendaten löschen
	$firmendaten = anfrage("DELETE FROM `firmendaten` WHERE id = '$firmaId'");
	// Wenn firmendaten gelöscht
	if ($firmendaten){
		// Nachrichten löschen
		$nachrichten = anfrage("DELETE FROM `pns` WHERE von_id = '$firmaId' AND zu_id = '$firmaId'");
		// Wenn nachrichten gelöscht
		if ($nachrichten){
			// Gebäude löschen
			$gebaeude = anfrage("DELETE FROM `gebaeude` WHERE firma_id = '$firmaId'");
			// Wenn gebäude gelöscht
			if ($gebaeude){
				// Lager löschen
				$lager = anfrage("DELETE FROM `lager` WHERE firma_id = '$firmaId'");
				// Wenn lager gelöscht
				if ($lager){
					// Quellwasser löschen
					$quellwasser = anfrage("DELETE FROM `quellwasser` WHERE anbieter_id = '$firmaId'");
					// Wenn quellwasser gelöscht
					if ($quellwasser){
						// User löschen
						$user = anfrage("DELETE FROM `user` WHERE id = '$userId'");
						// Wenn user gelöscht
						if ($user){
							
						}
						// Wenn user nicht gelöscht
						else{
							
						}
					}
					// Wenn quellwasser nicht gelöscht
					else{
						
					}
				}
				// Wenn lager nicht gelöscht
				else{
					
				}
			}
			// Wenn gebäude nicht gelöscht
			else{
					
			}
		}
		// Wenn nachrichten nicht gelöscht
		else{
						
		}
	}
	// Wenn firmendaten nicht gelöscht
	else{
		
	}
}
// Wenn finanzen nicht gelöscht
else{
	
}