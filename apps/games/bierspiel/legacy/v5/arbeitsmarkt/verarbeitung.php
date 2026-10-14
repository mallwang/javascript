<?php $title="VERARBEITUNG"; include '../huelle/header.php';
// Alle Gebote abarbeiten
	echo "GEBOTEVERARBEITUNG START<br>";
	$anzahlVerschiedeneAN = anfrage("SELECT COUNT(DISTINCT arbeitnehmer_id) FROM gebote");
	// Wenn Gebote vorhanden
	if ($anzahlVerschiedeneAN[0]>0){
		for($i=1;$i<=$anzahlVerschiedeneAN[0];$i++){
			echo "Durchlauf " . ($i) . "<br>";
			// Ersten Eintrag selektieren
			$id = anfrage("SELECT arbeitnehmer_id FROM gebote WHERE id=(SELECT MIN(id) FROM gebote)");
			// Benoetigte Werte abfragen
			$werte = anfrage("SELECT grundwert FROM arbeitnehmer WHERE id='$id[0]'");
			// Maximales Gebot ermitteln
			$maxGebot = anfrage("SELECT MAX(gebot) FROM gebote WHERE arbeitnehmer_id = '$id[0]'");
			// Hoechstbietenden feststellen
			$arbeitgeber = anfrage("SELECT arbeitgeber_id FROM gebote WHERE gebot = (SELECT MAX(gebot)
					FROM gebote WHERE arbeitnehmer_id = '$id[0]') AND arbeitnehmer_id = '$id[0]'");
			// Veränderung der Zufriedenheit berechnen
			$zufrVeraender = neueZufr($id[0], $maxGebot[0], $werte[0]);
			// Neue Zufriedenheit berechnen
			$zufriedenheitNeu = getNeueZufr($id[0], $zufrVeraender, 71);
			// Neuen Arbeitswert berechnen
			$arbeitswertNeu = berechneArbeitswert($id[0], $zufriedenheitNeu);
			// Arbeitnehmer updaten
			$neuerAG = anfrage("UPDATE arbeitnehmer SET gehalt='$maxGebot[0]', zufriedenheit='$zufriedenheitNeu',
					arbeitswert='$arbeitswertNeu', arbeitgeber='$arbeitgeber[0]' WHERE id = '$id[0]'");
			// Arbeitnehmer aus Tabelle gebote loeschen
			$AnLoeschen = anfrage("DELETE FROM gebote WHERE arbeitnehmer_id='$id[0]'");
			// neuen Arbeitnehmer erstellen
			fuegeANHinzu(2, 1);
		}
		echo "Erfolg! Alle Gebote verarbeitet! <br>";
	}
	else{
		echo "Keine Gebote vorhanden! <br>";
	}
	echo "GEBOTEVERARBEITUNG ENDE <br><br>";
	
// Dauer des Arbeitnehmerverhältnisses feststellen und erhöhen
	echo "ARBEITNEHMERVERARBEITUNG START<br>";
	// Anzahl an Arbeitnehmern feststellen, die beschäftigt sind
	$anzahlBeschaeftigteAN = anfrage("SELECT COUNT(id) FROM arbeitnehmer WHERE NOT arbeitgeber = '-1'");
	echo "Gefundene Arbeitnehmer: " . $anzahlBeschaeftigteAN[0] . "<br>";
	for($i=0; $i<$anzahlBeschaeftigteAN[0]; $i++){
		// Aktuellen Arbeitnehmer abfragen
		$aktAN = anfrage("SELECT id, dauer FROM arbeitnehmer WHERE NOT arbeitgeber = '-1' LIMIT $i, 1");
		echo "Aktueller Arbeitnehmer: " . $aktAN[0] . "<br>";
		// Abfrage Dauer des Dienstverhähltnisses
		echo "Bisherige Besch&auml;ftigungsdauer: " . $aktAN[1] . "<br>";
		// Neue Dauer festlegen
		$dauerNeu = $aktAN[1] + 1;
		echo "Neue Besch&auml;ftigungsdauer: " . $dauerNeu . "<br>";
		// Neue Dauer eintragen
		$dauerEintragen = anfrage("UPDATE arbeitnehmer SET dauer='$dauerNeu' WHERE id='$aktAN[0]'");
		echo "Die Neue Besch&auml;ftigungsdauer f&uuml;r die ID $aktAN[0] wurde von $aktAN[1] auf $dauerNeu gesetzt. <br>";
	}
	echo "ARBEITNEHMERVERARBEITUNG ENDE <br><br>";
	
// Gehaltsabrechnung
	echo "GEHALTSABRECHNUNG START<br>";
	// Anzahl an Arbeitnehmern feststellen, die beschäftigt sind
	$anzahlBeschaeftigteAN = anfrage("SELECT COUNT(id) FROM arbeitnehmer WHERE NOT arbeitgeber = '-1'");
	echo "Gefundene Arbeitnehmer: " . $anzahlBeschaeftigteAN[0] . "<br>";
	for($i=0; $i<$anzahlBeschaeftigteAN[0]; $i++){
		// Aktuellen Arbeitnehmer abfragen
		$aktAN = anfrage("SELECT id, gehalt, arbeitgeber FROM arbeitnehmer WHERE NOT arbeitgeber = '-1' LIMIT $i, 1");
		echo "Aktueller Arbeitnehmer: " . $aktAN[0] . "<br>";
		echo "Aktuelles Gehalt: " . $aktAN[1] . "<br>";
		echo "Arbeitgeber: " . $aktAN[2] . "<br>";
		// Gehalt auf Gehaltskonto buchen (nur ein Wochengehalt)
		$wochengehalt = (float) $aktAN[1]/4;
		echo "Wochengehalt: " . $wochengehalt . "<br>";
		// Aktuelles Gehaltskonto von Unternehmen abfragen
		$gehaltskonto = anfrage("SELECT gehaltskonto, woche, bargeld FROM firmendaten WHERE id='$aktAN[2]'");
		echo "Saldo Gehaltskonto: " . $gehaltskonto[0] . "<br>";
		$gehaltskontoNeu = $gehaltskonto[0] + $wochengehalt;
		echo "Neuer Saldo Gehaltskonto: " . $gehaltskontoNeu . "<br>";
		$setWochengehalt = anfrage("UPDATE firmendaten SET gehaltskonto='$gehaltskontoNeu' WHERE id='$aktAN[2]'");
		echo "Gehaltskonto aktualisiert! <br>";
		// Aktuelle Woche abfragen
		echo "Wochen aufgerechnet: " . $gehaltskonto[1] . "<br>";
		// Wochen erhöhen
		$wocheNeu = $gehaltskonto[1] + 1;
		$wochenNeu = anfrage("UPDATE firmendaten SET woche=$wocheNeu WHERE id='$aktAN[2]'");
		echo "Neue Woche: " . $wocheNeu . "<br>";
		if($wocheNeu == 4){
			echo "Woche 4 erreicht! <br>";
			// Verrechnen des Gehaltskontos
			echo "Aktuelles Bargeld: " . $gehaltskonto[2] . "<br>";
			$bargeldNeu = $gehaltskonto[2] - $gehaltskontoNeu;
			$gehaltVerrechnen = anfrage("UPDATE firmendaten SET bargeld='$bargeldNeu', gehaltskonto='0', woche='0' WHERE id='$aktAN[2]'");
		}
		else{
			echo "Noch keine vierte Woche erreicht! <br>";
		}
	}
	echo "GEHALTSABRECHNUNG ENDE<br>";