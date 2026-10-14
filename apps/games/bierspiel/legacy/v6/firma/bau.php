<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Prüfen, wieviele Gebäude User hat (Maximal: Normal(20), Premium(30))
$anzahlGebaeude = anfrage("SELECT COUNT(id) FROM gebaeude WHERE firma_id = '$firmaId'");
// Wenn Maximale Grenze nich erreicht
if ($anzahlGebaeude[0] <= 20){
	// Alle Variablen initialisieren
	$gebaeude = $_POST["gebaeude"];
	$kategorie = $_POST["kategorie"];
	$dauer = ($_POST["dauer"] * 60);
	$kosten = $_POST["kosten"];
	$standort = $_POST["standort"];
	$groesse = $_POST["groesse"];
	$viertel = $_POST["viertel"];
	// Aktuellen Kontostand abfragen
	$finanzen = anfrage("SELECT konto, a_gebaeudebau, a_gesamt FROM finanzen WHERE firma_id = '$firmaId'");
	// Wenn Finanzen größer als die Kosten
	if ($finanzen[0] >= $kosten){
		// Fertigstellungszeitpunkt berechnen
		$fertigstellung = date('Y-m-d H:i:s', strtotime("+$dauer seconds"));
		// Datenbankeintrag Gebäude
		$dateneintrag = anfrage("INSERT INTO `gebaeude`(`firma_id`, `kategorie`, `art`, `fertigstellung`, `baukosten`, `groesse`, `standort`, `viertel`)
				VALUES ('$firmaId', '$kategorie', '$gebaeude', '$fertigstellung', '$kosten', '$groesse', '$standort', '$viertel')");
		// Wenn Dateneintrag ok
		if ($dateneintrag){
			// GebäudeId abfragen
			$gebaeudeId = mysql_insert_id();
			// Datenbankeintrag Getränkeangebot
			$dateneintragGetraenke = anfrage("INSERT INTO `getraenkeangebote`(`firma_id`, `gebaeude_id`) VALUES ('$firmaId', '$gebaeudeId')");
			// Wenn Dateneintrag ok
			if ($dateneintragGetraenke){
				// Datenbankeintrag Gebäudeerweiterungen
				$dateneintragGebaeudeerweiterungen = anfrage("INSERT INTO `gebaeudeerweiterungen`(`firma_id`, `gebaeude_id`) VALUES ('$firmaId', '$gebaeudeId')");
				// Wenn Dateneintrag ok
				if ($dateneintragGebaeudeerweiterungen){
					// Dateneintrag Getränkelager
					$dateneintragGetraenkelager = anfrage("INSERT INTO `getraenkelager`(`firma_id`, `gebaeude_id`) VALUES ('$firmaId', '$gebaeudeId')");
					// Wenn Dateneintrag ok
					if ($dateneintragGetraenkelager){
						// Neuen Kontostand berechnen
						$kontostandNeu = $finanzen[0] - $kosten;
						// Neue Gesamtausgaben für Gebäudebau berechnen
						$gebaeudebauNeu = $finanzen[1] + $kosten;
						// Neue Gesamtausgaben berechnen
						$gesamtausgabenNeu = $finanzen[2] + $kosten;
						// Datenbankeintrag Kontostand
						$datenUser = anfrage("UPDATE `finanzen` SET `konto`='$kontostandNeu', `a_gebaeudebau`='$gebaeudebauNeu', `a_gesamt`='$gesamtausgabenNeu' WHERE firma_id = '$firmaId'");
						// Wenn Dateneintrag ok
						if ($datenUser){
							echo "ok";
						}
						// Wenn Dateneintrag nicht ok
						else{
							echo "fehlerKontostandeintrag";
						}
					}
					// Wenn Dateineintrag nicht ok
					else{
						echo "fehlerGetraenkelagereintrag";
					}
				}
				// Wenn Dateineintrag nicht ok
				else{
					echo "fehlerGebaeudeerweiterungeneintrag";
				}
			}
			// Wenn Dateineintrag nicht ok
			else{
				echo "fehlerGetraenkeeintrag";
			}
		}
		// Wenn Dateneintrag nicht ok
		else{
			echo "fehlerGebaeudeeintrag";
		}
	}
	// Wenn Finanzen kleiner als die Kosten
	else{
		echo "kontostandZuKlein";
	}
}
// Wenn Maximale Gebäudeanzahl erreicht
else{
	echo "maxGebaeude";
}