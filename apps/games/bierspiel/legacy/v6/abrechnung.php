<?php include 'sessionpruefung.php';

// Datenbankverbindung oeffnen
if(!($verbindung = mysql_connect("DB_HOST", "DB_USER", "DB_PASSWORD"))){
	die("Konnte keine Verbindung zur Datenbank herstellen!");
}

// Datenbank auswaehlen
if(!(@mysql_select_db("DB_NAME", $verbindung))){
	die("Fehler " . mysql_errno() . ": " . mysql_error());
}

mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");

/*
 * ZINSBERECHNUNG
 */

// Alle Finanzdaten der einzelnen User abfragen
$finanzdaten = mysql_query("SELECT firma_id, konto, kredit, a_zinsen, a_gesamt, e_zinsen, e_gesamt FROM finanzen");
// Schleifendurchlauf durch jede einzelne Firma
while($row = mysql_fetch_row($finanzdaten)){
	if ($row[0] != "1"){
		// Zinserträge berechen
		$zinsertraege = round(($row[1] * 0.01), 2);
		// Zinsaufwendungen berechnen
		$zinsaufwendungen = round(($row[2] * 0.1), 2);
		// Neue Zinserträge und Zinsaufwendungen Gesamt berechnen
		$zinsertraegeNeu = ($row[5] + $zinsertraege);
		$zinsaufwendungenNeu = ($row[3] + $zinsaufwendungen);
		// Neue Gesamterträge und Gesamtaufwendungen berechnen
		$einnahmenGesamtNeu = ($row[6] + $zinsertraege);
		$ausgabenGesamtNeu = ($row[4] + $zinsaufwendungen);
		// Neuen Kontostand berechnen
		$kontostandNeu = round(($row[1] + ($zinsertraege - $zinsaufwendungen)), 2);
		// Finanzen updaten
		echo "UPDATE finanzen SET `konto` = '$kontostandNeu', `a_zinsen` = '$zinsaufwendungenNeu',
			`a_gesamt` = '$ausgabenGesamtNeu', `e_zinsen` = '$zinsertraegeNeu', `e_gesamt` = '$einnahmenGesamtNeu' WHERE firma_id = '$row[0]'<br>";
		//mysql_query("UPDATE finanzen SET `konto` = '$kontostandNeu', `a_zinsen` = '$zinsaufwendungenNeu',
			//`a_gesamt` = '$ausgabenGesamtNeu', `e_zinsen` = '$zinsertraegeNeu', `e_gesamt` = '$einnahmenGesamtNeu' WHERE firma_id = '$row[0]'");
	}
}
	

// Alle Gebäude abfragen
$gebaeude = mysql_query("SELECT id, firma_id, beliebtheit, fertigstellung, viertel, stil, pzg, szg, sp, tm, tw FROM gebaeude");

// Beginn Tabellenausgabe
echo "<table class='table'><thead><tr><th>FirmaId</th><th>Geb&auml;udeId</th><th>Viertel</th><th>Bel</th><th>T</th><th>JE</th><th>F</th><th>R</th><th>E</th>
	<th>Gesamte<br>Besucherzahl</th><th>Ausstattung</th><th>Erweiterungen</th><th>Besucher durch<br>Pzg und Szg</th><th style='width: 100px;'>Biere</th><th style='width: 100px;'>Biermisch</th><th style='width: 100px;'>Alkoholfrei</th><th style='width: 100px;'>S&auml;fte</th><th style='width: 100px;'>Spirituosen</th><th style='width: 100px;'>Cocktails</th><th style='width: 100px;'>Weine</th><th style='width: 100px;'>Gesamtumsatz</th></tr></thead><tbody>";

// Schleifendurchlauf durch jedes einzelne Gebäude
while($row = mysql_fetch_row($gebaeude)){
	// Wenn Gebäude fertiggestellt ist
	if(strtotime($row[3]) <= strtotime(date('Y-m-d H:i:s'))){
		
		// Aktuelle Auslastung berechnen (hier: besucher = beliebtheit = $row[2])
		$besucher = $row[2];
		
		// Abfrage der Zielgruppenverteilung des Viertels
		$zielgruppenanfrage = mysql_query("SELECT * FROM stadtviertel WHERE viertel = '$row[4]'");
		$zielgruppen = mysql_fetch_array($zielgruppenanfrage, MYSQL_NUM);
		
		// TODO: Anstatt die absoluten Zahlen zu verändern, ist es auch möglich die relativen Zahlen zunächst anzupassen
		// und im Anschluss diese mit der Beliebtheit (Besuchern) zu multiplizieren
		
		$anteilTeenager = $zielgruppen[1];
		$anteilJungeErw = $zielgruppen[2];
		$anteilFamilien = $zielgruppen[3];
		$anteilRentner = $zielgruppen[4];
		$anteilErw = $zielgruppen[5];
		
		// Besucher jeder Zielgruppe berechnen
// 		$teenager = ceil($zielgruppen[1] * $besucher);
// 		$jungeErw = ceil($zielgruppen[2] * $besucher);
// 		$familien = ceil($zielgruppen[3] * $besucher);
// 		$rentner = ceil($zielgruppen[4] * $besucher);
// 		$erwachsene = ceil($zielgruppen[5] * $besucher);
		
		// Variabeln initialisieren
		$pzgNeu = 0;
		$szgNeu = 0;
		
		// Einfluss der primären und sekundären Zielgruppe beachten
		if ($row[6] != "keine" || $row[7] != "keine"){
			// Wenn nur sekundäre Zielgruppe gewählt --> primäre Zielgruppe
			if ($row[6] == "keine" && $row[7] != "keine"){
				$pzg = $row[7];
				$szg = "keine";
			}
			else{
				$pzg = $row[6];
				$szg = $row[7];
			}
			// Anpassen der Besucher der primären Zielgruppe
			if($pzg == "Teenager"){
// 				$pzgNeu = $teenager * 0.3;
				$anteilTeenager = $anteilTeenager * 1.3;
				if($szg != "keine"){
					if($szg == "Teenager"){
						//$szgNeu = $teenager * 0.2;
						$anteilTeenager = $anteilTeenager * 1.2;
					}
					else if($szg == "Junge Erwachsene"){
						//$szgNeu = $jungeErw * 0.2;
						$anteilJungeErw = $anteilJungeErw * 1.2;
					}
					else if($szg == "Erwachsene"){
						//$szgNeu = $erwachsene * 0.2;
						$anteilErw = $anteilErw * 1.2;
					}
					else if($szg == "Rentner"){
// 						$szgNeu = $rentner * 0.2;
						$anteilRentner = $anteilRentner * 1.2;
					}
					else if($szg == "Familie"){
// 						$szgNeu = $familien * 0.2;
						$anteilFamilien = $anteilFamilien * 1.2;
					}
				}
			}
			else if($pzg == "Junge Erwachsene"){
// 				$pzgNeu = $jungeErw * 0.3;
				$anteilJungeErw = $anteilJungeErw * 1.3;
				if($szg != "keine"){
					if($szg == "Teenager"){
// 						$szgNeu = $teenager * 0.2;
						$anteilTeenager = $anteilTeenager * 1.2;
					}
					else if($szg == "Junge Erwachsene"){
// 						$szgNeu = $jungeErw * 0.2;
						$anteilJungeErw = $anteilJungeErw * 1.2;
					}
					else if($szg == "Erwachsene"){
// 						$szgNeu = $erwachsene * 0.2;
						$anteilErw = $anteilErw * 1.2;
					}
					else if($szg == "Rentner"){
// 						$szgNeu = $rentner * 0.2;
						$anteilRentner = $anteilRentner * 1.2;
					}
					else if($szg == "Familie"){
// 						$szgNeu = $familien * 0.2;
						$anteilFamilien = $anteilFamilien * 1.2;
					}
				}
			}
			else if($pzg == "Erwachsene"){
// 				$pzgNeu = $erwachsene * 0.3;
				$anteilErw = $anteilErw * 1.3;
				if($szg != "keine"){
					if($szg == "Teenager"){
// 						$szgNeu = $teenager * 0.2;
						$anteilTeenager = $anteilTeenager * 1.2;
					}
					else if($szg == "Junge Erwachsene"){
// 						$szgNeu = $jungeErw * 0.2;
						$anteilJungeErw = $anteilJungeErw * 1.2;
					}
					else if($szg == "Erwachsene"){
// 						$szgNeu = $erwachsene * 0.2;
						$anteilErw = $anteilErw * 1.2;
					}
					else if($szg == "Rentner"){
// 						$szgNeu = $rentner * 0.2;
						$anteilRentner = $anteilRentner * 1.2;
					}
					else if($szg == "Familie"){
// 						$szgNeu = $familien * 0.2;
						$anteilFamilien = $anteilFamilien * 1.2;
					}
				}
			}
			else if($pzg == "Rentner"){
// 				$pzgNeu = $rentner * 0.3;
				$anteilRentner = $anteilRentner * 1.3;
				if($szg != "keine"){
					if($szg == "Teenager"){
// 						$szgNeu = $teenager * 0.2;
						$anteilTeenager = $anteilTeenager * 1.2;
					}
					else if($szg == "Junge Erwachsene"){
// 						$szgNeu = $jungeErw * 0.2;
						$anteilJungeErw = $anteilJungeErw * 1.2;
					}
					else if($szg == "Erwachsene"){
// 						$szgNeu = $erwachsene * 0.2;
						$anteilErw = $anteilErw * 1.2;
					}
					else if($szg == "Rentner"){
// 						$szgNeu = $rentner * 0.2;
						$anteilRentner = $anteilRentner * 1.2;
					}
					else if($szg == "Familie"){
// 						$szgNeu = $familien * 0.2;
						$anteilFamilien = $anteilFamilien * 1.2;
					}
				}
			}
			else if($pzg == "Familie"){
// 				$pzgNeu = $familien * 0.3;
				$anteilFamilien = $anteilFamilien * 1.3;
				if($szg != "keine"){
					if($szg == "Teenager"){
// 						$szgNeu = $teenager * 0.2;
						$anteilTeenager = $anteilTeenager * 1.2;
					}
					else if($szg == "Junge Erwachsene"){
// 						$szgNeu = $jungeErw * 0.2;
						$anteilJungeErw = $anteilJungeErw * 1.2;
					}
					else if($szg == "Erwachsene"){
// 						$szgNeu = $erwachsene * 0.2;
						$anteilErw = $anteilErw * 1.2;
					}
					else if($szg == "Rentner"){
// 						$szgNeu = $rentner * 0.2;
						$anteilRentner = $anteilRentner * 1.2;
					}
					else if($szg == "Familie"){
// 						$szgNeu = $familien * 0.2;
						$anteilFamilien = $anteilFamilien * 1.2;
					}
				}
			}
		}
		
		// Einfluss des Stils beachten
		if($row[5] == "keinen"){
			$anteilTeenager = $anteilTeenager * 0.5;
			$anteilJungeErw = $anteilJungeErw * 0.5;
			$anteilErw = $anteilErw * 0.5;
			$anteilRentner = $anteilRentner * 0.5;
			$anteilFamilien = $anteilFamilien * 0.5;
		}
		else if($row[5] == "Modern"){
			$anteilTeenager = $anteilTeenager * 1.1;
			$anteilJungeErw = $anteilJungeErw * 1.1;
		}
		else if($row[5] == "Rustikal"){
			$anteilErw = $anteilErw * 1.066;
			$anteilRentner = $anteilRentner * 1.066;
			$anteilFamilien = $anteilFamilien * 1.066;
		}
		else if($row[5] == "Edel"){
			$anteilTeenager = $anteilTeenager * 1.05;
			$anteilJungeErw = $anteilJungeErw * 1.05;
			$anteilErw = $anteilErw * 1.05;
			$anteilRentner = $anteilRentner * 1.05;
			$anteilFamilien = $anteilFamilien * 1.05;
		}
		
		// Besucherzahlen berechnen
		$teenager = ceil($anteilTeenager * $besucher);
		$jungeErw = ceil($anteilJungeErw * $besucher);
		$familien = ceil($anteilFamilien * $besucher);
		$rentner = ceil($anteilRentner * $besucher);
		$erwachsene = ceil($anteilErw * $besucher);
		
		// Einfluss der Toilette beachten
		if($row[9] == "1"){
			if($row[10] == "1"){
			}
			else if($row[10] == "2"){
			}
			else if($row[10] == "3"){
			}
			else if($row[10] == "4"){
			}
			else if($row[10] == "5"){
			}
			else if($row[10] == "6"){
			}
		}
		else if($row[9] == "2"){
			if($row[10] == "1"){
			}
			else if($row[10] == "2"){
			}
			else if($row[10] == "3"){
			}
			else if($row[10] == "4"){
			}
			else if($row[10] == "5"){
			}
			else if($row[10] == "6"){
			}
		}
		else if($row[9] == "3"){
			if($row[10] == "1"){
			}
			else if($row[10] == "2"){
			}
			else if($row[10] == "3"){
			}
			else if($row[10] == "4"){
			}
			else if($row[10] == "5"){
			}
			else if($row[10] == "6"){
			}
		}
		else if($row[9] == "4"){
			if($row[10] == "1"){
			}
			else if($row[10] == "2"){
			}
			else if($row[10] == "3"){
			}
			else if($row[10] == "4"){
			}
			else if($row[10] == "5"){
			}
			else if($row[10] == "6"){
			}
		}
		else if($row[9] == "5"){
			if($row[10] == "1"){
			}
			else if($row[10] == "2"){
			}
			else if($row[10] == "3"){
			}
			else if($row[10] == "4"){
			}
			else if($row[10] == "5"){
			}
			else if($row[10] == "6"){
			}
		}
		else if($row[9] == "6"){
			if($row[10] == "1"){
			}
			else if($row[10] == "2"){
			}
			else if($row[10] == "3"){
			}
			else if($row[10] == "4"){
			}
			else if($row[10] == "5"){
			}
			else if($row[10] == "6"){
			}
		}
		
		// TODO: Entweder so belassen, dass zusätzliche Besucher da sind ohne das andere Zielgruppen
		// dafür weichen müssen oder alle anderen Zielgruppen um die um die zusätzliche Menge der PZG und SZG mindern (Tendenz: eher mindern)		
		
		// Getränkeverteilung abfragen
		$getraenkeverteilunganfrage = mysql_query("SELECT * FROM nachfrage");
		
		// Zählvariable anlegen
		$i = 0;
		
		// Getränke initialisieen
		$biere = 0;
		$biermisch = 0;
		$alkfrei = 0;
		$saefte = 0;
		$spirituosen = 0;
		$cocktails = 0;
		$weine = 0;
		
		// Schleifendurchlauf durch jede einzelne Zielgruppe
		while($getr = mysql_fetch_row($getraenkeverteilunganfrage)){
			// 1. durchlauf = teenager, 2. durchlauf = jungeErwachsene usw.
			if ($i == 0){
				$biere = ($biere + ($getr[1] * $teenager));
				$biermisch = ($biermisch + ($getr[2] * $teenager));
				$alkfrei = ($alkfrei + ($getr[3] * $teenager));
				$saefte = ($saefte + ($getr[4] * $teenager));
				$spirituosen = ($spirituosen + ($getr[5] * $teenager));
				$cocktails = ($cocktails + ($getr[6] * $teenager));
				$weine = ($weine + ($getr[7] * $teenager));
			}
			else if ($i == 1){
				$biere = ($biere + ($getr[1] * $jungeErw));
				$biermisch = ($biermisch + ($getr[2] * $jungeErw));
				$alkfrei = ($alkfrei + ($getr[3] * $jungeErw));
				$saefte = ($saefte + ($getr[4] * $jungeErw));
				$spirituosen = ($spirituosen + ($getr[5] * $jungeErw));
				$cocktails = ($cocktails + ($getr[6] * $jungeErw));
				$weine = ($weine + ($getr[7] * $jungeErw));
			}
			else if ($i == 2){
				$biere = ($biere + ($getr[1] * $familien));
				$biermisch = ($biermisch + ($getr[2] * $familien));
				$alkfrei = ($alkfrei + ($getr[3] * $familien));
				$saefte = ($saefte + ($getr[4] * $familien));
				$spirituosen = ($spirituosen + ($getr[5] * $familien));
				$cocktails = ($cocktails + ($getr[6] * $familien));
				$weine = ($weine + ($getr[7] * $familien));
			}
			else if ($i == 3){
				$biere = ($biere + ($getr[1] * $rentner));
				$biermisch = ($biermisch + ($getr[2] * $rentner));
				$alkfrei = ($alkfrei + ($getr[3] * $rentner));
				$saefte = ($saefte + ($getr[4] * $rentner));
				$spirituosen = ($spirituosen + ($getr[5] * $rentner));
				$cocktails = ($cocktails + ($getr[6] * $rentner));
				$weine = ($weine + ($getr[7] * $rentner));
			}
			else if ($i == 4){
				$biere = ($biere + ($getr[1] * $erwachsene));
				$biermisch = ($biermisch + ($getr[2] * $erwachsene));
				$alkfrei = ($alkfrei + ($getr[3] * $erwachsene));
				$saefte = ($saefte + ($getr[4] * $erwachsene));
				$spirituosen = ($spirituosen + ($getr[5] * $erwachsene));
				$cocktails = ($cocktails + ($getr[6] * $erwachsene));
				$weine = ($weine + ($getr[7] * $erwachsene));
			}
			// Zählvariable erhöhen
			$i++;
		}
		
		// Getränkeangebot abfragen
		$getraenkeangebotanfrage = mysql_query("SELECT * FROM getraenkeangebote WHERE gebaeude_id = '$row[0]'");
		$getraenkeangebot = mysql_fetch_array($getraenkeangebotanfrage, MYSQL_NUM);
		// Angebotene Sorten Bier zählen
		$bierangebot = $getraenkeangebot[2] + $getraenkeangebot[4] + $getraenkeangebot[6] + $getraenkeangebot[8] + $getraenkeangebot[10] + $getraenkeangebot[12];
		// Angebotene Sorten Biermischgetränke zählen
		$biermischangebot = $getraenkeangebot[26] + $getraenkeangebot[28] + $getraenkeangebot[30] + $getraenkeangebot[32] + $getraenkeangebot[34] + $getraenkeangebot[36];
		// Angebotene Sorten alkoholfreie Getränke zählen
		$alkoholfreiangebot = $getraenkeangebot[50] + $getraenkeangebot[52] + $getraenkeangebot[54] + $getraenkeangebot[56] + $getraenkeangebot[58] + $getraenkeangebot[60];
		// Angebotene Sorten Säfte zählen
		$saftangebot = $getraenkeangebot[72] + $getraenkeangebot[74] + $getraenkeangebot[76] + $getraenkeangebot[78] + $getraenkeangebot[80] + $getraenkeangebot[82];
		// Angebotene Sorten Spirituosen zählen
		$spirituosenangebot = $getraenkeangebot[14] + $getraenkeangebot[16] + $getraenkeangebot[18] + $getraenkeangebot[20] + $getraenkeangebot[22] + $getraenkeangebot[24];
		// Angebotene Sorten Cocktails zählen
		$cocktailangebot = $getraenkeangebot[38] + $getraenkeangebot[40] + $getraenkeangebot[42] + $getraenkeangebot[44] + $getraenkeangebot[46] + $getraenkeangebot[48];
		// Angebotene Sorten Weine zählen
		$weinangebot = $getraenkeangebot[62] + $getraenkeangebot[64] + $getraenkeangebot[66] + $getraenkeangebot[68] + $getraenkeangebot[70];
		
		// Berechnung der Kauffaktoren aufgrund des Preisfaktors (Angebotspreis / Gleichgewichtspreis)
		$kauffaktorPils = preisFaktor($getraenkeangebot[3] / 2.3);
		$kauffaktorHelles = preisFaktor($getraenkeangebot[5] / 2.3);
		$kauffaktorLager = preisFaktor($getraenkeangebot[7] / 2.3);
		$kauffaktorWeissbier = preisFaktor($getraenkeangebot[9] / 2.3);
		$kauffaktorSchwarzbier = preisFaktor($getraenkeangebot[11] / 2.3);
		$kauffaktorAltbier = preisFaktor($getraenkeangebot[13] / 2.3);
		$kauffaktorAlmradler = preisFaktor($getraenkeangebot[27] / 2.1);
		$kauffaktorRadler = preisFaktor($getraenkeangebot[29] / 2.1);
		$kauffaktorDiesel = preisFaktor($getraenkeangebot[31] / 2.1);
		$kauffaktorGoass = preisFaktor($getraenkeangebot[33] / 2.1);
		$kauffaktorColaweizen = preisFaktor($getraenkeangebot[35] / 2.1);
		$kauffaktorRuss = preisFaktor($getraenkeangebot[37] / 2.1);
		$kauffaktorMineralwasser = preisFaktor($getraenkeangebot[51] / 1.8);
		$kauffaktorCola = preisFaktor($getraenkeangebot[53] / 1.8);
		$kauffaktorZlimo = preisFaktor($getraenkeangebot[55] / 1.8);
		$kauffaktorOlimo = preisFaktor($getraenkeangebot[57] / 1.8);
		$kauffaktorApfelschorle = preisFaktor($getraenkeangebot[59] / 1.8);
		$kauffaktorColamix = preisFaktor($getraenkeangebot[61] / 1.8);
		$kauffaktorAsaft = preisFaktor($getraenkeangebot[73] / 1.5);
		$kauffaktorOsaft = preisFaktor($getraenkeangebot[75] / 1.5);
		$kauffaktorKsaft = preisFaktor($getraenkeangebot[77] / 1.5);
		$kauffaktorBsaft = preisFaktor($getraenkeangebot[79] / 1.5);
		$kauffaktorJsaft = preisFaktor($getraenkeangebot[81] / 1.5);
		$kauffaktorPsaft = preisFaktor($getraenkeangebot[83] / 1.5);
		$kauffaktorKorn = preisFaktor($getraenkeangebot[15] / 1.1);
		$kauffaktorWhiskey = preisFaktor($getraenkeangebot[17] / 1.1);
		$kauffaktorWodka = preisFaktor($getraenkeangebot[19] / 1.1);
		$kauffaktorObstbrand = preisFaktor($getraenkeangebot[21] / 1.1);
		$kauffaktorRum = preisFaktor($getraenkeangebot[23] / 1.1);
		$kauffaktorLikeur = preisFaktor($getraenkeangebot[25] / 1.1);
		$kauffaktorSotb = preisFaktor($getraenkeangebot[39] / 5.2);
		$kauffaktorLiit = preisFaktor($getraenkeangebot[41] / 5.2);
		$kauffaktorZombie = preisFaktor($getraenkeangebot[43] / 5.2);
		$kauffaktorHurricane = preisFaktor($getraenkeangebot[45] / 5.2);
		$kauffaktorCaipirinha = preisFaktor($getraenkeangebot[47] / 5.2);
		$kauffaktorMojito = preisFaktor($getraenkeangebot[49] / 5.2);
		$kauffaktorWeisswein = preisFaktor($getraenkeangebot[63] / 1.6);
		$kauffaktorRotwein = preisFaktor($getraenkeangebot[65] / 1.6);
		$kauffaktorSchaumwein = preisFaktor($getraenkeangebot[67] / 1.6);
		$kauffaktorLikeurwein = preisFaktor($getraenkeangebot[69] / 1.6);
		$kauffaktorRosewein = preisFaktor($getraenkeangebot[71] / 1.6);
		
		// Funktion zum Berechnen des Absatzes
		function berechneAbsatz($getraenkeangebot, $gesamtverkauf, $verteilung, $kauffaktor, $einheit, $verkaufte){
			$rueckgabe = array('0', '0');
			if ($getraenkeangebot == 1){
				$rueckgabe[0] = ((ceil(((($gesamtverkauf * 15) * $verteilung) * $kauffaktor) / $einheit)) * $einheit);
				$rueckgabe[1] = ($verkaufte + 1);
			}
			else{
				$rueckgabe[0] = 0;
				$rueckgabe[1] = $verkaufte;
			}
			return $rueckgabe;
		}
		
		
		// Verbrauch pro einelner Sorte berechnen (Verbrauch wird mit einer Konstanten (hier: 15) und dem jeweiligen Kauffaktor multipliziert)
		// Bier
		$bierverteilung = verteilung(6, $bierangebot);
		$verkaufteBiere = 0;
		$pilsArray = berechneAbsatz($getraenkeangebot[2], $biere, $bierverteilung[$verkaufteBiere], $kauffaktorPils, '0.5', $verkaufteBiere);
		$hellesArray = berechneAbsatz($getraenkeangebot[4], $biere, $bierverteilung[$pilsArray[1]], $kauffaktorHelles, '0.5', $pilsArray[1]);
		$lagerArray = berechneAbsatz($getraenkeangebot[6], $biere, $bierverteilung[$hellesArray[1]], $kauffaktorLager, '0.5', $hellesArray[1]);
		$weissbierArray = berechneAbsatz($getraenkeangebot[8], $biere, $bierverteilung[$lagerArray[1]], $kauffaktorWeissbier, '0.5', $lagerArray[1]);
		$schwarzbierArray = berechneAbsatz($getraenkeangebot[10], $biere, $bierverteilung[$weissbierArray[1]], $kauffaktorSchwarzbier, '0.5', $weissbierArray[1]);
		$altbierArray = berechneAbsatz($getraenkeangebot[12], $biere, $bierverteilung[$schwarzbierArray[1]], $kauffaktorAltbier, '0.5', $schwarzbierArray[1]);
		$pils = $pilsArray[0];
		$helles = $hellesArray[0];
		$lager = $lagerArray[0];
		$weissbier = $weissbierArray[0];
		$schwarzbier = $schwarzbierArray[0];
		$altbier = $altbierArray[0];
		$verkaufteBiere = $altbierArray[1];
		
		// Biermisch
		$biermischverteilung = verteilung(6, $biermischangebot);
		$verkaufteBieremisch = 0;
		$almradlerArray = berechneAbsatz($getraenkeangebot[26], $biermisch, $biermischverteilung[$verkaufteBieremisch], $kauffaktorAlmradler, '0.5', $verkaufteBieremisch);
		$radlerArray = berechneAbsatz($getraenkeangebot[28], $biermisch, $biermischverteilung[$almradlerArray[1]], $kauffaktorRadler, '0.5', $almradlerArray[1]);
		$dieselArray = berechneAbsatz($getraenkeangebot[30], $biermisch, $biermischverteilung[$radlerArray[1]], $kauffaktorDiesel, '0.5', $radlerArray[1]);
		$goassArray = berechneAbsatz($getraenkeangebot[32], $biermisch, $biermischverteilung[$dieselArray[1]], $kauffaktorGoass, '0.5', $dieselArray[1]);
		$colaweizenArray = berechneAbsatz($getraenkeangebot[34], $biermisch, $biermischverteilung[$goassArray[1]], $kauffaktorColaweizen, '0.5', $goassArray[1]);
		$russArray = berechneAbsatz($getraenkeangebot[36], $biermisch, $biermischverteilung[$colaweizenArray[1]], $kauffaktorRuss, '0.5', $colaweizenArray[1]);
		$almradler = $almradlerArray[0];
		$radler = $radlerArray[0];
		$diesel = $dieselArray[0];
		$goass = $almradlerArray[0];
		$colaweizen = $almradlerArray[0];
		$russ = $almradlerArray[0];
		$verkaufteBieremisch = $russArray[1];
		
		// Alkoholfrei
		$alkoholfreiverteilung = verteilung(6, $alkoholfreiangebot);
		$verkaufteAlkoholfreie = 0;
		$mineralwasserArray = berechneAbsatz($getraenkeangebot[50], $alkfrei, $alkoholfreiverteilung[$verkaufteAlkoholfreie], $kauffaktorMineralwasser, '0.5', $verkaufteAlkoholfreie);
		$colaArray = berechneAbsatz($getraenkeangebot[52], $alkfrei, $alkoholfreiverteilung[$mineralwasserArray[1]], $kauffaktorCola, '0.5', $mineralwasserArray[1]);
		$zlimoArray = berechneAbsatz($getraenkeangebot[54], $alkfrei, $alkoholfreiverteilung[$$colaArray[1]], $kauffaktorZlimo, '0.5', $colaArray[1]);
		$olimoArray = berechneAbsatz($getraenkeangebot[56], $alkfrei, $alkoholfreiverteilung[$zlimoArray[1]], $kauffaktorOlimo, '0.5', $zlimoArray[1]);
		$apfelschorleArray = berechneAbsatz($getraenkeangebot[58], $alkfrei, $alkoholfreiverteilung[$olimoArray[1]], $kauffaktorApfelschorle, '0.5', $olimoArray[1]);
		$colamixArray = berechneAbsatz($getraenkeangebot[60], $alkfrei, $alkoholfreiverteilung[$apfelschorleArray[1]], $kauffaktorColamix, '0.5', $apfelschorleArray[1]);
		$mineralwasser = $mineralwasserArray[0];
		$cola = $colaArray[0];
		$zlimo = $zlimoArray[0];
		$olimo = $olimoArray[0];
		$apfelschorle = $apfelschorleArray[0];
		$colamix = $colamixArray[0];
		$verkaufteAlkoholfreie = $colamixArray[1];
		
		// Säfte
		$saftverteilung = verteilung(6, $saftangebot);
		$verkaufteSaefte = 0;
		$asaftArray = berechneAbsatz($getraenkeangebot[72], $alkfrei, $saftverteilung[$verkaufteSaefte], $kauffaktorAsaft, '0.5', $verkaufteSaefte);
		$osaftArray = berechneAbsatz($getraenkeangebot[74], $alkfrei, $saftverteilung[$asaftArray[1]], $kauffaktorOsaft, '0.5', $asaftArray[1]);
		$ksaftArray = berechneAbsatz($getraenkeangebot[76], $alkfrei, $saftverteilung[$osaftArray[1]], $kauffaktorKsaft, '0.5', $osaftArray[1]);
		$bsaftArray = berechneAbsatz($getraenkeangebot[78], $alkfrei, $saftverteilung[$ksaftArray[1]], $kauffaktorBsaft, '0.5', $ksaftArray[1]);
		$jsaftArray = berechneAbsatz($getraenkeangebot[80], $alkfrei, $saftverteilung[$bsaftArray[1]], $kauffaktorJsaft, '0.5', $bsaftArray[1]);
		$psaftArray = berechneAbsatz($getraenkeangebot[82], $alkfrei, $saftverteilung[$jsaftArray[1]], $kauffaktorPsaft, '0.5', $jsaftArray[1]);
		$asaft = $asaftArray[0];
		$osaft = $osaftArray[0];
		$ksaft = $ksaftArray[0];
		$bsaft = $bsaftArray[0];
		$jsaft = $jsaftArray[0];
		$psaft = $psaftArray[0];
		$verkaufteSaefte = $psaftArray[1];
		
		// Spirituosen
		$spirituosenverteilung = verteilung(6, $spirituosenangebot);
		$verkaufteSpirituosen = 0;
		$kornArray = berechneAbsatz($getraenkeangebot[14], $spirituosen, $spirituosenverteilung[$verkaufteSpirituosen], $kauffaktorKorn, '0.02', $verkaufteSpirituosen);
		$whiskeyArray = berechneAbsatz($getraenkeangebot[16], $spirituosen, $spirituosenverteilung[$kornArray[1]], $kauffaktorWhiskey, '0.02', $kornArray[1]);
		$wodkaArray = berechneAbsatz($getraenkeangebot[18], $spirituosen, $spirituosenverteilung[$whiskeyArray[1]], $kauffaktorWodka, '0.02', $whiskeyArray[1]);
		$obstbrandArray = berechneAbsatz($getraenkeangebot[20], $spirituosen, $spirituosenverteilung[$wodkaArray[1]], $kauffaktorObstbrand, '0.02', $wodkaArray[1]);
		$rumArray = berechneAbsatz($getraenkeangebot[22], $spirituosen, $spirituosenverteilung[$obstbrandArray[1]], $kauffaktorRum, '0.02', $obstbrandArray[1]);
		$likeurArray = berechneAbsatz($getraenkeangebot[24], $spirituosen, $spirituosenverteilung[$rumArray[1]], $kauffaktorLikeur, '0.02', $rumArray[1]);
		$korn = $kornArray[0];
		$whiskey = $whiskeyArray[0];
		$wodka = $wodkaArray[0];
		$obstbrand = $obstbrandArray[0];
		$rum = $rumArray[0];
		$likeur = $likeurArray[0];
		
		// Cocktails
		$cocktailverteilung = verteilung(6, $cocktailangebot);
		$verkaufteCocktails = 0;
		$sotbArray = berechneAbsatz($getraenkeangebot[38], $cocktails, $cocktailverteilung[$verkaufteCocktails], $kauffaktorSotb, '0.4', $verkaufteCocktails);
		$liitArray = berechneAbsatz($getraenkeangebot[40], $cocktails, $cocktailverteilung[$sotbArray[1]], $kauffaktorLiit, '0.4', $sotbArray[1]);
		$zombieArray = berechneAbsatz($getraenkeangebot[42], $cocktails, $cocktailverteilung[$liitArray[1]], $kauffaktorZombie, '0.4', $liitArray[1]);
		$hurricaneArray = berechneAbsatz($getraenkeangebot[44], $cocktails, $cocktailverteilung[$zombieArray[1]], $kauffaktorHurricane, '0.4', $zombieArray[1]);
		$caipirinhaArray = berechneAbsatz($getraenkeangebot[46], $cocktails, $cocktailverteilung[$hurricaneArray[1]], $kauffaktorCaipirinha, '0.4', $hurricaneArray[1]);
		$mojitoArray = berechneAbsatz($getraenkeangebot[48], $cocktails, $cocktailverteilung[$caipirinhaArray[1]], $kauffaktorMojito, '0.4', $caipirinhaArray[1]);
		$sotb = $sotbArray[0];
		$liit = $liitArray[0];
		$zombie = $zombieArray[0];
		$hurricane = $hurricaneArray[0];
		$caipirinha = $caipirinhaArray[0];
		$mojito = $mojitoArray[0];
		$verkaufteCocktails = $mojitoArray[1];
		
		// Weine
		$weinverteilung = verteilung(6, $weinangebot);
		$verkaufteWeine = 0;
		$weissweinArray = berechneAbsatz($getraenkeangebot[62], $cocktails, $weinverteilung[$verkaufteWeine], $kauffaktorWeisswein, '0.1', $verkaufteWeine);
		$rotweinArray = berechneAbsatz($getraenkeangebot[64], $cocktails, $weinverteilung[$weissweinArray[1]], $kauffaktorRotwein, '0.1', $weissweinArray[1]);
		$schaumweinArray = berechneAbsatz($getraenkeangebot[66], $cocktails, $weinverteilung[$rotweinArray[1]], $kauffaktorSchaumwein, '0.1', $rotweinArray[1]);
		$likeurweinArray = berechneAbsatz($getraenkeangebot[68], $cocktails, $weinverteilung[$schaumweinArray[1]], $kauffaktorLikeurwein, '0.1', $schaumweinArray[1]);
		$roseweinArray = berechneAbsatz($getraenkeangebot[70], $cocktails, $weinverteilung[$likeurweinArray[1]], $kauffaktorRosewein, '0.1', $likeurweinArray[1]);
		$weisswein = $weissweinArray[0];
		$rotwein = $rotweinArray[0];
		$schaumwein = $schaumweinArray[0];
		$likeurwein = $likeurweinArray[0];
		$rosewein = $roseweinArray[0];
		$verkaufteWeine = $roseweinArray[1];
		
		// Berücksichtigen der Erweiterungen
		$gebaeudeerweiterungenanfrage = mysql_query("SELECT * FROM gebaeudeerweiterungen WHERE gebaeude_id = '$row[0]'");
		$gebaeudeerweiterungen = mysql_fetch_array($gebaeudeerweiterungenanfrage, MYSQL_NUM);
		// Multiplikatorvariablen
		$erweiterungsfaktor = 0;
		// Wenn Zapfanlage vorhanden
		if ($gebaeudeerweiterungen[2] != 0){
			$erweiterungsfaktor += ($gebaeudeerweiterungen[2] * 0.05);
		}
		// Wenn Stammtisch vorhanden
		if ($gebaeudeerweiterungen[3] == 1){
			$erweiterungsfaktor += 0.1;
		}
		// Wenn Bar vorhanden
		if ($gebaeudeerweiterungen[5] == 1){
			$erweiterungsfaktor += 0.15;
		}
		// Wenn Kaminofen vorhanden
		if ($gebaeudeerweiterungen[6] == 1){
			$erweiterungsfaktor += 0.1;
		}
		// Wenn Biergarten vorhanden und schönes Wetter (Wetterfaktor Winter immer 0, Sommer immer >= 60, Frühling und Herbst > 0 und <= 60)
		$wetterfaktor = 0;
		if ($gebaeudeerweiterungen[9] == 1 && $wetterfaktor != 0){
			$erweiterungsfaktor += ($wetterfaktor * 0.4);
		}
		// Wenn Küche vorhanden
		if ($gebaeudeerweiterungen[10] == 1){
			$erweiterungsfaktor += 0.2;
			// Wenn Speisekarte vorhanden
			if ($gebaeudeerweiterungen[11] == 1){
				$erweiterungsfaktor += 0.1;
			}
			// Wenn Tageskarte vorhanden
			if ($gebaeudeerweiterungen[12] == 1){
				$erweiterungsfaktor += 0.05;
			}
			// Wenn Servierwagen vorhanden
			if ($gebaeudeerweiterungen[13] == 1){
				$erweiterungsfaktor += 0.05;
			}
			// Wenn Menage vorhanden
			if ($gebaeudeerweiterungen[6] == 1){
				$erweiterungsfaktor += 0.02;
			}
		}
		// Dauer der Happy Hour berechnen
		/*
		if ($gebaeudeerweiterungen[7] != "00:00:00" || $gebaeudeerweiterungen[8] != "00:00:00" || 1){
			$startTimeTrenn = explode(":", $gebaeudeerweiterungen[7]);
			$startH = $startTimeTrenn[0] . ":" . $startTimeTrenn[1];
			//schlusszeit am : trennen
			$closedMoDoTimeTrenn = explode(":", $gebaeudeerweiterungen[8]);
			$endeH = $closedMoDoTimeTrenn[0] . ":" . $closedMoDoTimeTrenn[1];
			//startzeit mit mktime in timestamp wandeln
			$beginnTime = mktime($startTimeTrenn[0], $startTimeTrenn[1], 0, 0, 0, 0);
			//schluss mit mktime in timestamp wandeln
			$closedTimeMoDo = mktime($closedMoDoTimeTrenn[0], $closedMoDoTimeTrenn[1], 0, 0, 0, 0);
			//berechnen der differenz aus schluss und startzeit
			$timeDiff = $closedTimeMoDo - $beginnTime;
			//ergebnis der differenz ausgeben
			//echo gmdate("H:i", $timeDiff);
		}
		
		TODO: Erweiterungsfaktor schon auf Beliebtheit rechnen....spart diese nachträglichen Operationen
		*/
		
		// Multiplikation mit dem Erweiterungsfaktor
		$pils = $pils * (1 + $erweiterungsfaktor);
		$helles = $helles * (1 + $erweiterungsfaktor);
		$lager = $lager * (1 + $erweiterungsfaktor);
		$weissbier = $weissbier * (1 + $erweiterungsfaktor);
		$schwarzbier = $schwarzbier * (1 + $erweiterungsfaktor);
		$altbier = $altbier * (1 + $erweiterungsfaktor);
		$almradler = $almradler * (1 + $erweiterungsfaktor);
		$radler = $radler * (1 + $erweiterungsfaktor);
		$diesel = $diesel * (1 + $erweiterungsfaktor);
		$goass = $goass * (1 + $erweiterungsfaktor);
		$colaweizen = $colaweizen * (1 + $erweiterungsfaktor);
		$russ = $russ * (1 + $erweiterungsfaktor);
		$mineralwasser = $mineralwasser * (1 + $erweiterungsfaktor);
		$cola = $cola * (1 + $erweiterungsfaktor);
		$zlimo = $zlimo * (1 + $erweiterungsfaktor);
		$olimo = $olimo * (1 + $erweiterungsfaktor);
		$apfelschorle = $apfelschorle * (1 + $erweiterungsfaktor);
		$colamix = $colamix * (1 + $erweiterungsfaktor);
		$asaft = $asaft * (1 + $erweiterungsfaktor);
		$osaft = $osaft * (1 + $erweiterungsfaktor);
		$ksaft = $ksaft * (1 + $erweiterungsfaktor);
		$bsaft = $bsaft * (1 + $erweiterungsfaktor);
		$jsaft = $jsaft * (1 + $erweiterungsfaktor);
		$psaft = $psaft * (1 + $erweiterungsfaktor);
		$korn = $korn * (1 + $erweiterungsfaktor);
		$whiskey = $whiskey * (1 + $erweiterungsfaktor);
		$wodka = $wodka * (1 + $erweiterungsfaktor);
		$obstbrand = $obstbrand * (1 + $erweiterungsfaktor);
		$rum = $rum * (1 + $erweiterungsfaktor);
		$likeur = $likeur * (1 + $erweiterungsfaktor);
		$sotb = $sotb * (1 + $erweiterungsfaktor);
		$liit = $liit * (1 + $erweiterungsfaktor);
		$zombie = $zombie * (1 + $erweiterungsfaktor);
		$hurricane = $hurricane * (1 + $erweiterungsfaktor);
		$caipirinha = $caipirinha * (1 + $erweiterungsfaktor);
		$mojito = $mojito * (1 + $erweiterungsfaktor);
		$weisswein = $weisswein * (1 + $erweiterungsfaktor);
		$rotwein = $rotwein * (1 + $erweiterungsfaktor);
		$schaumwein = $schaumwein * (1 + $erweiterungsfaktor);
		$likeurwein = $likeurwein * (1 + $erweiterungsfaktor);
		$rosewein = $rosewein * (1 + $erweiterungsfaktor);
		
		/********************************
		 /* Lagerbestandsprüfung start **
		/*******************************/
		
		// Lagerbestände abfragen (wenn Angebot an Sorte geringer als die Nachfrage wählt ein Teil der Kundschaft ein anderes Getränk der selben Gattung)
		$lagerbestandsanfrage = mysql_query("SELECT * FROM getraenkelager WHERE gebaeude_id = '$row[0]'");
		$lagerbestand = mysql_fetch_array($lagerbestandsanfrage, MYSQL_NUM);
		// Zählvariable für die Berechnung von restlichen im Angebot befindlichen Sorten
		$bierangebotVerbraucht = 0;
		// Wenn Lagerbestand nicht ausreicht
		if ($lagerbestand[2] < $pils){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussPils = $pils - $lagerbestand[2];
			// Nachgefragte Menge = Lagerbestand
			$pils = $lagerbestand[2];
			// Wenn weiteres Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($bierangebot - $bierangebotVerbraucht) > 1){
				$ueberschussTeilPils = ($nachfrageueberschussPils / ($bierangebot - 1)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[4] == 1)
					$helles += $ueberschussTeilPils;
				if ($getraenkeangebot[6] == 1)
					$lager += $ueberschussTeilPils;
				if ($getraenkeangebot[8] == 1)
					$weissbier += $ueberschussTeilPils;
				if ($getraenkeangebot[10] == 1)
					$schwarzbier += $ueberschussTeilPils;
				if ($getraenkeangebot[12] == 1)
					$altbier += $ueberschussTeilPils;
				// Zählvariable erhöhen
				$bierangebotVerbraucht++;
			}
		}
		if ($lagerbestand[3] < $helles){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussHelles = $helles - $lagerbestand[3];
			// Nachgefragte Menge = Lagerbestand
			$helles = $lagerbestand[3];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($bierangebot - $bierangebotVerbraucht) > 1){
				$ueberschussTeilHelles = ($nachfrageueberschussHelles / ($bierangebot - $bierangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[6] == 1)
					$lager += $ueberschussTeilHelles;
				if ($getraenkeangebot[8] == 1)
					$weissbier += $ueberschussTeilHelles;
				if ($getraenkeangebot[10] == 1)
					$schwarzbier += $ueberschussTeilHelles;
				if ($getraenkeangebot[12] == 1)
					$altbier += $ueberschussTeilHelles;
				// Zählvariable erhöhen
				$bierangebotVerbraucht++;
			}
		}
		if ($lagerbestand[4] < $lager){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussLager = $lager - $lagerbestand[4];
			// Nachgefragte Menge = Lagerbestand
			$lager = $lagerbestand[4];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($bierangebot - $bierangebotVerbraucht) > 1){
				$ueberschussTeilLager = ($nachfrageueberschussLager / ($bierangebot - $bierangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[8] == 1)
					$weissbier += $ueberschussTeilLager;
				if ($getraenkeangebot[10] == 1)
					$schwarzbier += $ueberschussTeilLager;
				if ($getraenkeangebot[12] == 1)
					$altbier += $ueberschussTeilLager;
				// Zählvariable erhöhen
				$bierangebotVerbraucht++;
			}
		}
		if ($lagerbestand[5] < $weissbier){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussWeissbier = $weissbier - $lagerbestand[5];
			// Nachgefragte Menge = Lagerbestand
			$weissbier = $lagerbestand[5];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($bierangebot - $bierangebotVerbraucht) > 1){
				$ueberschussTeilWeissbier = ($nachfrageueberschussWeissbier / ($bierangebot - $bierangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[10] == 1)
					$schwarzbier += $ueberschussTeilWeissbier;
				if ($getraenkeangebot[12] == 1)
					$altbier += $ueberschussTeilWeissbier;
				// Zählvariable erhöhen
				$bierangebotVerbraucht++;
			}
		}
		if ($lagerbestand[6] < $schwarzbier){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussSchwarzbier = $schwarzbier - $lagerbestand[6];
			// Nachgefragte Menge = Lagerbestand
			$schwarzbier = $lagerbestand[6];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($bierangebot - $bierangebotVerbraucht) > 1){
				$ueberschussTeilSchwarzbier = ($nachfrageueberschussSchwarzbier / ($bierangebot - $bierangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[12] == 1)
					$altbier += $ueberschussTeilSchwarzbier;
				// Zählvariable erhöhen
				$bierangebotVerbraucht++;
			}
		}
		if ($lagerbestand[7] < $altbier){
			// Nachgefragte Menge = Lagerbestand
			$altbier = $lagerbestand[7];
		}
		/*******************************
		/* Bierüberschussprüfung ende **
		/******************************/
		
		$spirituosenangebotVerbraucht = 0;
		if ($lagerbestand[8] < $korn){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussKorn = $korn - $lagerbestand[8];
			// Nachgefragte Menge = Lagerbestand
			$korn = $lagerbestand[8];
			// Wenn weiteres Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($spirituosenangebot - $spirituosenangebotVerbraucht) > 1){
				$ueberschussTeilKorn = ($nachfrageueberschussKorn / ($spirituosenangebot - $spirituosenangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[16] == 1)
					$whiskey += $ueberschussTeilKorn;
				if ($getraenkeangebot[18] == 1)
					$wodka += $ueberschussTeilKorn;
				if ($getraenkeangebot[20] == 1)
					$obstbrand += $ueberschussTeilKorn;
				if ($getraenkeangebot[22] == 1)
					$rum += $ueberschussTeilKorn;
				if ($getraenkeangebot[24] == 1)
					$likeur += $ueberschussTeilKorn;
				// Zählvariable erhöhen
				$spirituosenangebotVerbraucht++;
			}
		}
		if ($lagerbestand[9] < $whiskey){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussWhiskey = $whiskey - $lagerbestand[9];
			// Nachgefragte Menge = Lagerbestand
			$whiskey = $lagerbestand[9];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($spirituosenangebot - $spirituosenangebotVerbraucht) > 1){
				$ueberschussTeilWhiskey = ($nachfrageueberschussWhiskey / ($spirituosenangebot - $spirituosenangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[18] == 1)
					$wodka += $ueberschussTeilWhiskey;
				if ($getraenkeangebot[20] == 1)
					$obstbrand += $ueberschussTeilWhiskey;
				if ($getraenkeangebot[22] == 1)
					$rum += $ueberschussTeilWhiskey;
				if ($getraenkeangebot[24] == 1)
					$likeur += $ueberschussTeilWhiskey;
				// Zählvariable erhöhen
				$spirituosenangebotVerbraucht++;
			}
		}
		if ($lagerbestand[10] < $wodka){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussWodka = $wodka - $lagerbestand[10];
			// Nachgefragte Menge = Lagerbestand
			$wodka = $lagerbestand[10];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($spirituosenangebot - $spirituosenangebotVerbraucht) > 1){
				$ueberschussTeilWodka = ($nachfrageueberschussWodka / ($spirituosenangebot - $spirituosenangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[20] == 1)
					$obstbrand += $ueberschussTeilWodka;
				if ($getraenkeangebot[22] == 1)
					$rum += $ueberschussTeilWodka;
				if ($getraenkeangebot[24] == 1)
					$likeur += $ueberschussTeilWodka;
				// Zählvariable erhöhen
				$spirituosenangebotVerbraucht++;
			}
		}
		if ($lagerbestand[11] < $obstbrand){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussObstbrand = $obstbrand - $lagerbestand[11];
			// Nachgefragte Menge = Lagerbestand
			$obstbrand = $lagerbestand[11];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($spirituosenangebot - $spirituosenangebotVerbraucht) > 1){
				$ueberschussTeilObstbrand = ($nachfrageueberschussObstbrand / ($spirituosenangebot - $spirituosenangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[22] == 1)
					$rum += $ueberschussTeilObstbrand;
				if ($getraenkeangebot[24] == 1)
					$likeur += $ueberschussTeilObstbrand;
				// Zählvariable erhöhen
				$spirituosenangebotVerbraucht++;
			}
		}
		if ($lagerbestand[12] < $rum){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussRum = $rum - $lagerbestand[12];
			// Nachgefragte Menge = Lagerbestand
			$rum = $lagerbestand[12];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($spirituosenangebot - $spirituosenangebotVerbraucht) > 1){
				$ueberschussTeilRum = ($nachfrageueberschussRum / ($spirituosenangebot - $spirituosenangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[24] == 1)
					$likeur += $ueberschussTeilRum;
				// Zählvariable erhöhen
				$spirituosenangebotVerbraucht++;
			}
		}
		if ($lagerbestand[13] < $likeur){
			// Nachgefragte Menge = Lagerbestand
			$likeur = $lagerbestand[13];
		}
		/***************************************
		 /* Spirituosenüberschussprüfung ende **
		/**************************************/
		
		$biermischangebotVerbraucht = 0;
		if ($lagerbestand[14] < $almradler){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussAlmradler = $almradler - $lagerbestand[14];
			// Nachgefragte Menge = Lagerbestand
			$almradler = $lagerbestand[14];
			// Wenn weiteres Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($biermischangebot - $biermischangebotVerbraucht) > 1){
				$ueberschussTeilAlmradler = ($nachfrageueberschussAlmradler / ($biermischangebot - $biermischangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[28] == 1)
					$radler += $ueberschussTeilAlmradler;
				if ($getraenkeangebot[30] == 1)
					$diesel += $ueberschussTeilAlmradler;
				if ($getraenkeangebot[32] == 1)
					$goass += $ueberschussTeilAlmradler;
				if ($getraenkeangebot[34] == 1)
					$colaweizen += $ueberschussTeilAlmradler;
				if ($getraenkeangebot[36] == 1)
					$russ += $ueberschussTeilAlmradler;
				// Zählvariable erhöhen
				$biermischangebotVerbraucht++;
			}
		}
		if ($lagerbestand[15] < $radler){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussRadler = $radler - $lagerbestand[15];
			// Nachgefragte Menge = Lagerbestand
			$radler = $lagerbestand[15];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($biermischangebot - $biermischangebotVerbraucht) > 1){
				$ueberschussTeilRadler = ($nachfrageueberschussRadler / ($biermischangebot - $biermischangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[30] == 1)
					$diesel += $ueberschussTeilRadler;
				if ($getraenkeangebot[32] == 1)
					$goass += $ueberschussTeilRadler;
				if ($getraenkeangebot[34] == 1)
					$colaweizen += $ueberschussTeilRadler;
				if ($getraenkeangebot[36] == 1)
					$russ += $ueberschussTeilRadler;
				// Zählvariable erhöhen
				$biermischangebotVerbraucht++;
			}
		}
		if ($lagerbestand[16] < $diesel){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussDiesel = $diesel - $lagerbestand[16];
			// Nachgefragte Menge = Lagerbestand
			$diesel = $lagerbestand[16];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($biermischangebot - $biermischangebotVerbraucht) > 1){
				$ueberschussTeilDiesel = ($nachfrageueberschussDiesel / ($biermischangebot - $biermischangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[32] == 1)
					$goass += $ueberschussTeilDiesel;
				if ($getraenkeangebot[34] == 1)
					$colaweizen += $ueberschussTeilDiesel;
				if ($getraenkeangebot[36] == 1)
					$russ += $ueberschussTeilDiesel;
				// Zählvariable erhöhen
				$biermischangebotVerbraucht++;
			}
		}
		if ($lagerbestand[17] < $goass){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussGoass = $goass - $lagerbestand[17];
			// Nachgefragte Menge = Lagerbestand
			$goass = $lagerbestand[17];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($biermischangebot - $biermischangebotVerbraucht) > 1){
				$ueberschussTeilGoass = ($nachfrageueberschussGoass / ($biermischangebot - $biermischangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[34] == 1)
					$colaweizen += $ueberschussTeilGoass;
				if ($getraenkeangebot[36] == 1)
					$russ += $ueberschussTeilGoass;
				// Zählvariable erhöhen
				$biermischangebotVerbraucht++;
			}
		}
		if ($lagerbestand[18] < $colaweizen){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussColaweizen = $colaweizen - $lagerbestand[18];
			// Nachgefragte Menge = Lagerbestand
			$colaweizen = $lagerbestand[18];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($biermischangebot - $biermischangebotVerbraucht) > 1){
				$ueberschussTeilColaweizen = ($nachfrageueberschussColaweizen / ($biermischangebot - $biermischangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[36] == 1)
					$russ += $ueberschussTeilColaweizen;
				// Zählvariable erhöhen
				$biermischangebotVerbraucht++;
			}
		}
		if ($lagerbestand[19] < $russ){
			// Nachgefragte Menge = Lagerbestand
			$russ = $lagerbestand[19];
		}
		/************************************
		/* Biermischüberschussprüfung ende **
		/***********************************/

		$cocktailangebotVerbraucht = 0;
		if ($lagerbestand[20] < $sotb){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussSotb = $sotb - $lagerbestand[20];
			// Nachgefragte Menge = Lagerbestand
			$sotb = $lagerbestand[20];
			// Wenn weiteres Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($cocktailangebot - $cocktailangebotVerbraucht) > 1){
				$ueberschussTeilSotb = ($nachfrageueberschussSotb / ($cocktailangebot - $cocktailangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[40] == 1)
					$liit += $ueberschussTeilSotb;
				if ($getraenkeangebot[42] == 1)
					$zombie += $ueberschussTeilSotb;
				if ($getraenkeangebot[44] == 1)
					$hurricane += $ueberschussTeilSotb;
				if ($getraenkeangebot[46] == 1)
					$caipirinha += $ueberschussTeilSotb;
				if ($getraenkeangebot[48] == 1)
					$mojito += $ueberschussTeilSotb;
				// Zählvariable erhöhen
				$cocktailangebotVerbraucht++;
			}
		}
		if ($lagerbestand[21] < $liit){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussLiit = $liit - $lagerbestand[21];
			// Nachgefragte Menge = Lagerbestand
			$liit = $lagerbestand[21];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($cocktailangebot - $cocktailangebotVerbraucht) > 1){
				$ueberschussTeilLiit = ($nachfrageueberschussLiit / ($cocktailangebot - $cocktailangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[42] == 1)
					$zombie += $ueberschussTeilLiit;
				if ($getraenkeangebot[44] == 1)
					$hurricane += $ueberschussTeilLiit;
				if ($getraenkeangebot[46] == 1)
					$caipirinha += $ueberschussTeilLiit;
				if ($getraenkeangebot[48] == 1)
					$mojito += $ueberschussTeilLiit;
				// Zählvariable erhöhen
				$cocktailangebotVerbraucht++;
			}
		}
		if ($lagerbestand[22] < $zombie){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussZombie = $zombie - $lagerbestand[22];
			// Nachgefragte Menge = Lagerbestand
			$zombie = $lagerbestand[22];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($cocktailangebot - $cocktailangebotVerbraucht) > 1){
				$ueberschussTeilZombie = ($nachfrageueberschussZombie / ($cocktailangebot - $cocktailangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[44] == 1)
					$hurricane += $ueberschussTeilZombie;
				if ($getraenkeangebot[46] == 1)
					$caipirinha += $ueberschussTeilZombie;
				if ($getraenkeangebot[48] == 1)
					$mojito += $ueberschussTeilZombie;
				// Zählvariable erhöhen
				$cocktailangebotVerbraucht++;
			}
		}
		if ($lagerbestand[23] < $hurricane){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussHurricane = $hurricane - $lagerbestand[23];
			// Nachgefragte Menge = Lagerbestand
			$hurricane = $lagerbestand[23];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($cocktailangebot - $cocktailangebotVerbraucht) > 1){
				$ueberschussTeilHurricane = ($nachfrageueberschussHurricane / ($cocktailangebot - $cocktailangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[46] == 1)
					$caipirinha += $ueberschussTeilHurricane;
				if ($getraenkeangebot[48] == 1)
					$mojito += $ueberschussTeilHurricane;
				// Zählvariable erhöhen
				$cocktailangebotVerbraucht++;
			}
		}
		if ($lagerbestand[24] < $caipirinha){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussCaipirinha = $caipirinha - $lagerbestand[24];
			// Nachgefragte Menge = Lagerbestand
			$caipirinha = $lagerbestand[24];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($cocktailangebot - $cocktailangebotVerbraucht) > 1){
				$ueberschussTeilCaipirinha = ($nachfrageueberschussCaipirinha / ($cocktailangebot - $cocktailangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[48] == 1)
					$mojito += $ueberschussTeilCaipirinha;
				// Zählvariable erhöhen
				$cocktailangebotVerbraucht++;
			}
		}
		if ($lagerbestand[25] < $mojito){
			// Nachgefragte Menge = Lagerbestand
			$mojito = $lagerbestand[25];
		}
		/************************************
		 /* Cocktailüberschussprüfung ende **
		/***********************************/

		$alkoholfreiangebotVerbraucht = 0;
		if ($lagerbestand[26] < $mineralwasser){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussMineralwasser = $mineralwasser - $lagerbestand[26];
			// Nachgefragte Menge = Lagerbestand
			$mineralwasser = $lagerbestand[26];
			// Wenn weiteres Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($alkoholfreiangebot - $alkoholfreiangebotVerbraucht) > 1){
				$ueberschussTeilMineralwasser = ($nachfrageueberschussMineralwasser / ($alkoholfreiangebot - $alkoholfreiangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[40] == 1)
					$cola += $ueberschussTeilMineralwasser;
				if ($getraenkeangebot[42] == 1)
					$zlimo += $ueberschussTeilMineralwasser;
				if ($getraenkeangebot[44] == 1)
					$olimo += $ueberschussTeilMineralwasser;
				if ($getraenkeangebot[46] == 1)
					$apfelschorle += $ueberschussTeilMineralwasser;
				if ($getraenkeangebot[48] == 1)
					$colamix += $ueberschussTeilMineralwasser;
				// Zählvariable erhöhen
				$alkoholfreiangebotVerbraucht++;
			}
		}
		if ($lagerbestand[27] < $cola){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussCola = $cola - $lagerbestand[27];
			// Nachgefragte Menge = Lagerbestand
			$cola = $lagerbestand[27];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($alkoholfreiangebot - $alkoholfreiangebotVerbraucht) > 1){
				$ueberschussTeilCola = ($nachfrageueberschussCola / ($alkoholfreiangebot - $alkoholfreiangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[42] == 1)
					$zlimo += $ueberschussTeilCola;
				if ($getraenkeangebot[44] == 1)
					$olimo += $ueberschussTeilCola;
				if ($getraenkeangebot[46] == 1)
					$apfelschorle += $ueberschussTeilCola;
				if ($getraenkeangebot[48] == 1)
					$colamix += $ueberschussTeilCola;
				// Zählvariable erhöhen
				$alkoholfreiangebotVerbraucht++;
			}
		}
		if ($lagerbestand[28] < $zlimo){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussZlimo = $zlimo - $lagerbestand[28];
			// Nachgefragte Menge = Lagerbestand
			$zlimo = $lagerbestand[28];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($alkoholfreiangebot - $alkoholfreiangebotVerbraucht) > 1){
				$ueberschussTeilZlimo = ($nachfrageueberschussZlimo / ($alkoholfreiangebot - $alkoholfreiangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[44] == 1)
					$olimo += $ueberschussTeilZlimo;
				if ($getraenkeangebot[46] == 1)
					$apfelschorle += $ueberschussTeilZlimo;
				if ($getraenkeangebot[48] == 1)
					$colamix += $ueberschussTeilZlimo;
				// Zählvariable erhöhen
				$alkoholfreiangebotVerbraucht++;
			}
		}
		if ($lagerbestand[29] < $olimo){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussOlimo = $olimo - $lagerbestand[29];
			// Nachgefragte Menge = Lagerbestand
			$olimo = $lagerbestand[29];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($alkoholfreiangebot - $alkoholfreiangebotVerbraucht) > 1){
				$ueberschussTeilOlimo = ($nachfrageueberschussOlimo / ($alkoholfreiangebot - $alkoholfreiangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[46] == 1)
					$apfelschorle += $ueberschussTeilOlimo;
				if ($getraenkeangebot[48] == 1)
					$colamix += $ueberschussTeilOlimo;
				// Zählvariable erhöhen
				$alkoholfreiangebotVerbraucht++;
			}
		}
		if ($lagerbestand[30] < $apfelschorle){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussApfelschorle = $apfelschorle - $lagerbestand[30];
			// Nachgefragte Menge = Lagerbestand
			$apfelschorle = $lagerbestand[30];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($alkoholfreiangebot - $alkoholfreiangebotVerbraucht) > 1){
				$ueberschussTeilApfelschorle = ($nachfrageueberschussApfelschorle / ($alkoholfreiangebot - $alkoholfreiangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[48] == 1)
					$colamix += $ueberschussTeilApfelschorle;
				// Zählvariable erhöhen
				$alkoholfreiangebotVerbraucht++;
			}
		}
		if ($lagerbestand[31] < $colamix){
			// Nachgefragte Menge = Lagerbestand
			$colamix = $lagerbestand[31];
		}
		/***************************************
		 /* Alkoholfreiüberschussprüfung ende **
		/**************************************/

		$weinangebotVerbraucht = 0;
		if ($lagerbestand[32] < $weisswein){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussWeisswein = $weisswein - $lagerbestand[32];
			// Nachgefragte Menge = Lagerbestand
			$weisswein = $lagerbestand[32];
			// Wenn weiteres Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($weinangebot - $weinangebotVerbraucht) > 1){
				$ueberschussTeilWeisswein = ($nachfrageueberschussWeisswein / ($weinangebot - $weinangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[64] == 1)
					$rotwein += $ueberschussTeilWeisswein;
				if ($getraenkeangebot[66] == 1)
					$schaumwein += $ueberschussTeilWeisswein;
				if ($getraenkeangebot[68] == 1)
					$likeurwein += $ueberschussTeilWeisswein;
				if ($getraenkeangebot[70] == 1)
					$rosewein += $ueberschussTeilWeisswein;
				// Zählvariable erhöhen
				$weinangebotVerbraucht++;
			}
		}
		if ($lagerbestand[33] < $rotwein){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussRotwein = $rotwein - $lagerbestand[33];
			// Nachgefragte Menge = Lagerbestand
			$rotwein = $lagerbestand[33];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($weinangebot - $weinangebotVerbraucht) > 1){
				$ueberschussTeilRotwein = ($nachfrageueberschussRotwein / ($weinangebot - $weinangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[66] == 1)
					$schaumwein += $ueberschussTeilRotwein;
				if ($getraenkeangebot[68] == 1)
					$likeurwein += $ueberschussTeilRotwein;
				if ($getraenkeangebot[70] == 1)
					$rosewein += $ueberschussTeilRotwein;
				// Zählvariable erhöhen
				$weinangebotVerbraucht++;
			}
		}
		if ($lagerbestand[34] < $schaumwein){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussSchaumwein = $schaumwein - $lagerbestand[34];
			// Nachgefragte Menge = Lagerbestand
			$schaumwein = $lagerbestand[34];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($weinangebot - $weinangebotVerbraucht) > 1){
				$ueberschussTeilSchaumwein = ($nachfrageueberschussSchaumwein / ($weinangebot - $weinangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[68] == 1)
					$likeurwein += $ueberschussTeilSchaumwein;
				if ($getraenkeangebot[70] == 1)
					$rosewein += $ueberschussTeilSchaumwein;
				// Zählvariable erhöhen
				$weinangebotVerbraucht++;
			}
		}
		if ($lagerbestand[35] < $likeurwein){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussLikeurwein = $likeurwein - $lagerbestand[35];
			// Nachgefragte Menge = Lagerbestand
			$likeurwein = $lagerbestand[35];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($weinangebot - $weinangebotVerbraucht) > 1){
				$ueberschussTeilLikeurwein = ($nachfrageueberschussLikeurwein / ($weinangebot - $weinangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[70] == 1)
					$rosewein += $ueberschussTeilLikeurwein;
				// Zählvariable erhöhen
				$weinangebotVerbraucht++;
			}
		}
		if ($lagerbestand[36] < $rosewein){
			// Nachgefragte Menge = Lagerbestand
			$rosewein = $lagerbestand[36];
		}
		/********************************
		 /* Weinüberschussprüfung ende **
		/*******************************/
		
		$saftangebotVerbraucht = 0;
		if ($lagerbestand[37] < $asaft){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussAsaft = $asaft - $lagerbestand[37];
			// Nachgefragte Menge = Lagerbestand
			$asaft = $lagerbestand[37];
			// Wenn weiteres Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($saftangebot - $saftangebotVerbraucht) > 1){
				$ueberschussTeilAsaft = ($nachfrageueberschussAsaft / ($saftangebot - $saftangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[74] == 1)
					$osaft += $ueberschussTeilAsaft;
				if ($getraenkeangebot[76] == 1)
					$ksaft += $ueberschussTeilAsaft;
				if ($getraenkeangebot[78] == 1)
					$bsaft += $ueberschussTeilAsaft;
				if ($getraenkeangebot[80] == 1)
					$jsaft += $ueberschussTeilAsaft;
				if ($getraenkeangebot[82] == 1)
					$psaft += $ueberschussTeilAsaft;
				// Zählvariable erhöhen
				$saftangebotVerbraucht++;
			}
		}
		if ($lagerbestand[38] < $osaft){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussOsaft = $osaft - $lagerbestand[38];
			// Nachgefragte Menge = Lagerbestand
			$osaft = $lagerbestand[38];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($saftangebot - $saftangebotVerbraucht) > 1){
				$ueberschussTeilOsaft = ($nachfrageueberschussOsaft / ($saftangebot - $saftangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[76] == 1)
					$ksaft += $ueberschussTeilOsaft;
				if ($getraenkeangebot[78] == 1)
					$bsaft += $ueberschussTeilOsaft;
				if ($getraenkeangebot[80] == 1)
					$jsaft += $ueberschussTeilOsaft;
				if ($getraenkeangebot[82] == 1)
					$psaft += $ueberschussTeilOsaft;
				// Zählvariable erhöhen
				$saftangebotVerbraucht++;
			}
		}
		if ($lagerbestand[39] < $ksaft){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussKsaft = $ksaft - $lagerbestand[39];
			// Nachgefragte Menge = Lagerbestand
			$ksaft = $lagerbestand[39];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($saftangebot - $saftangebotVerbraucht) > 1){
				$ueberschussTeilKsaft = ($nachfrageueberschussKsaft / ($saftangebot - $saftangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[78] == 1)
					$bsaft += $ueberschussTeilKsaft;
				if ($getraenkeangebot[80] == 1)
					$jsaft += $ueberschussTeilKsaft;
				if ($getraenkeangebot[82] == 1)
					$psaft += $ueberschussTeilKsaft;
				// Zählvariable erhöhen
				$saftangebotVerbraucht++;
			}
		}
		if ($lagerbestand[40] < $bsaft){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussBsaft = $bsaft - $lagerbestand[40];
			// Nachgefragte Menge = Lagerbestand
			$bsaft = $lagerbestand[40];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($saftangebot - $saftangebotVerbraucht) > 1){
				$ueberschussTeilBsaft = ($nachfrageueberschussBsaft / ($saftangebot - $saftangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[80] == 1)
					$jsaft += $ueberschussTeilBsaft;
				if ($getraenkeangebot[82] == 1)
					$psaft += $ueberschussTeilBsaft;
				// Zählvariable erhöhen
				$saftangebotVerbraucht++;
			}
		}
		if ($lagerbestand[41] < $jsaft){
			// Nachfrageüberschuss berechnen
			$nachfrageueberschussJsaft = $jsaft - $lagerbestand[41];
			// Nachgefragte Menge = Lagerbestand
			$jsaft = $lagerbestand[41];
			// Restliches Bierangebot berechnen
			// Wenn weitere Angebote gleicher Gattung vorhanden --> einen Teil des Überschusses aufteilen
			if (($saftangebot - $saftangebotVerbraucht) > 1){
				$ueberschussTeilJsaft = ($nachfrageueberschussJsaft / ($saftangebot - $saftangebotVerbraucht)) * 0.6;
				// Zu jeder verbleibenden Sorte den Überschuss addieren
				if ($getraenkeangebot[82] == 1)
					$psaft += $ueberschussTeilJsaft;
				// Zählvariable erhöhen
				$saftangebotVerbraucht++;
			}
		}
		if ($lagerbestand[42] < $psaft){
			// Nachgefragte Menge = Lagerbestand
			$psaft = $lagerbestand[42];
		}
		/********************************
		 /* Saftüberschussprüfung ende **
		/*******************************/
		
		// Umsatz berechnen
		$umsatzPils = round(($pils * $getraenkeangebot[3]), 2);
		$umsatzHelles = round(($helles * $getraenkeangebot[5]), 2);
		$umsatzLager = round(($lager * $getraenkeangebot[7]), 2);
		$umsatzWeissbier = round(($weissbier * $getraenkeangebot[9]), 2);
		$umsatzSchwarzbier = round(($schwarzbier * $getraenkeangebot[11]), 2);
		$umsatzAltbier = round(($altbier * $getraenkeangebot[13]), 2);
		$umsatzAlmradler = round(($almradler * $getraenkeangebot[27]), 2);
		$umsatzRadler = round(($radler * $getraenkeangebot[29]), 2);
		$umsatzDiesel = round(($diesel * $getraenkeangebot[31]), 2);
		$umsatzGoass = round(($goass * $getraenkeangebot[33]), 2);
		$umsatzColaweizen = round(($colaweizen * $getraenkeangebot[35]), 2);
		$umsatzRuss = round(($russ * $getraenkeangebot[37]), 2);
		$umsatzMineralwasser = round(($mineralwasser * $getraenkeangebot[51]), 2);
		$umsatzCola = round(($cola * $getraenkeangebot[53]), 2);
		$umsatzZlimo = round(($zlimo * $getraenkeangebot[55]), 2);
		$umsatzOlimo = round(($olimo * $getraenkeangebot[57]), 2);
		$umsatzApfelschorle = round(($apfelschorle * $getraenkeangebot[59]), 2);
		$umsatzColamix = round(($colamix * $getraenkeangebot[61]), 2);
		$umsatzAsaft = round(($asaft * $getraenkeangebot[73]), 2);
		$umsatzOsaft = round(($osaft * $getraenkeangebot[75]), 2);
		$umsatzKsaft = round(($ksaft * $getraenkeangebot[77]), 2);
		$umsatzBsaft = round(($bsaft * $getraenkeangebot[79]), 2);
		$umsatzJsaft = round(($jsaft * $getraenkeangebot[81]), 2);
		$umsatzPsaft = round(($psaft * $getraenkeangebot[83]), 2);
		$umsatzKorn = round(($korn * $getraenkeangebot[15]), 2);
		$umsatzWhiskey = round(($whiskey * $getraenkeangebot[17]), 2);
		$umsatzWodka = round(($wodka * $getraenkeangebot[19]), 2);
		$umsatzObstbrand = round(($obstbrand * $getraenkeangebot[21]), 2);
		$umsatzRum = round(($rum * $getraenkeangebot[23]), 2);
		$umsatzLikeur = round(($likeur * $getraenkeangebot[25]), 2);
		$umsatzSotb = round(($sotb * $getraenkeangebot[39]), 2);
		$umsatzLiit = round(($liit * $getraenkeangebot[41]), 2);
		$umsatzZombie = round(($zombie * $getraenkeangebot[43]), 2);
		$umsatzHurricane = round(($hurricane * $getraenkeangebot[45]), 2);
		$umsatzCaipirinha = round(($caipirinha * $getraenkeangebot[47]), 2);
		$umsatzMojito = round(($mojito * $getraenkeangebot[49]), 2);
		$umsatzWeisswein = round(($weisswein * $getraenkeangebot[63]), 2);
		$umsatzRotwein = round(($rotwein * $getraenkeangebot[65]), 2);
		$umsatzSchaumwein = round(($schaumwein * $getraenkeangebot[67]), 2);
		$umsatzLikeurwein = round(($likeurwein * $getraenkeangebot[69]), 2);
		$umsatzRosewein = round(($rosewein * $getraenkeangebot[71]), 2);
		// Gesamtumsatz berechnen
		$gesamtumsatz = ($umsatzPils + $umsatzHelles + $umsatzLager + $umsatzWeissbier + $umsatzSchwarzbier + $umsatzAltbier +
		$umsatzAlmradler + $umsatzRadler + $umsatzDiesel + $umsatzGoass + $umsatzColaweizen + $umsatzRuss + $umsatzMineralwasser +
		$umsatzCola + $umsatzZlimo + $umsatzOlimo + $umsatzApfelschorle + $umsatzColamix + $umsatzAsaft + $umsatzOsaft + $umsatzKsaft +
		$umsatzBsaft + $umsatzJsaft + $umsatzPsaft + $umsatzKorn + $umsatzWhiskey + $umsatzWodka + $umsatzObstbrand + $umsatzRum +
		$umsatzLikeur + $umsatzSotb + $umsatzLiit + $umsatzZombie + $umsatzHurricane + $umsatzCaipirinha + $umsatzMojito + 
		$umsatzWeisswein + $umsatzRotwein + $umsatzSchaumwein + $umsatzLikeurwein + $umsatzRosewein);
		// Finanzen abfragen
		$finanzanfrage = mysql_query("SELECT konto, e_umsatz, e_gesamt FROM finanzen WHERE firma_id = '$lagerbestand[0]'");
		$finanzen = mysql_fetch_array($finanzanfrage, MYSQL_NUM);
		// Neuen Kontostand berechnen
		$kontostandNeu = ($finanzen[0] + $gesamtumsatz);
		// Neuen Gesamtumsatz berechnen
		$gesamtumsatzNeu = ($finanzen[1] + $gesamtumsatz);
		// Neue Gesamteinnahmen berechnen
		$gesamteinnahmenNeu = ($finanzen[2] + $gesamtumsatz);
		//echo "KontostandAlt: " . $finanzen[0] . " / Gesamtumsatz: " . $gesamtumsatz . " / KontostandNeu: " . $kontostandNeu . "<br>";
		// Finanzen updaten
		//echo "UPDATE finanzen SET konto = '$kontostandNeu', e_umsatz = '$gesamtumsatzNeu', e_gesamt = '$gesamteinnahmenNeu' WHERE firma_id = '$lagerbestand[0]'";
		//echo "<br>";
		
		// Absatz aller Getränke runden
		$pils = (ceil($pils / 0.5) * 0.5);
		$helles = (ceil($helles / 0.5) * 0.5);
		$lager = (ceil($lager / 0.5) * 0.5);
		$weissbier = (ceil($weissbier / 0.5) * 0.5);
		$schwarzbier = (ceil($schwarzbier / 0.5) * 0.5);
		$altbier = (ceil($altbier / 0.5) * 0.5);
		$almradler = (ceil($almradler / 0.5) * 0.5);
		$radler = (ceil($radler / 0.5) * 0.5);
		$diesel = (ceil($diesel / 0.5) * 0.5);
		$goass = (ceil($goass / 0.5) * 0.5);
		$colaweizen = (ceil($colaweizen / 0.5) * 0.5);
		$russ = (ceil($russ / 0.5) * 0.5);
		$mineralwasser = (ceil($mineralwasser / 0.5) * 0.5);
		$cola = (ceil($cola / 0.5) * 0.5);
		$zlimo = (ceil($zlimo / 0.5) * 0.5);
		$olimo = (ceil($olimo / 0.5) * 0.5);
		$apfelschorle = (ceil($apfelschorle / 0.5) * 0.5);
		$colamix = (ceil($colamix / 0.5) * 0.5);
		$asaft = (ceil($asaft / 0.5) * 0.5);
		$osaft = (ceil($osaft / 0.5) * 0.5);
		$ksaft = (ceil($ksaft / 0.5) * 0.5);
		$bsaft = (ceil($bsaft / 0.5) * 0.5);
		$jsaft = (ceil($jsaft / 0.5) * 0.5);
		$psaft = (ceil($psaft / 0.5) * 0.5);
		$korn = (ceil($korn / 0.02) * 0.02);
		$whiskey = (ceil($whiskey / 0.02) * 0.02);
		$wodka = (ceil($wodka / 0.02) * 0.02);
		$obstbrand = (ceil($obstbrand / 0.02) * 0.02);
		$likeur = (ceil($likeur / 0.02) * 0.02);
		$rum = (ceil($rum / 0.02) * 0.02);
		$sotb = (ceil($sotb / 0.4) * 0.4);
		$liit = (ceil($liit / 0.4) * 0.4);
		$zombie = (ceil($zombie / 0.4) * 0.4);
		$hurricane = (ceil($hurricane / 0.4) * 0.4);
		$caipirinha = (ceil($caipirinha / 0.4) * 0.4);
		$mojito = (ceil($mojito / 0.4) * 0.4);
		$weisswein = (ceil($weisswein / 0.7) * 0.7);
		$rotwein = (ceil($rotwein / 0.7) * 0.7);
		$schaumwein = (ceil($schaumwein / 0.7) * 0.7);
		$likeurwein = (ceil($likeurwein / 0.7) * 0.7);
		$rosewein = (ceil($rosewein / 0.7) * 0.7);
		
		// Neuen Lagerbestand berechnen
		$lagerbestandNeuPils = $lagerbestand[2] - $pils;
		$lagerbestandNeuHelles = $lagerbestand[3] - $helles;
		$lagerbestandNeuLager = $lagerbestand[4] - $lager;
		$lagerbestandNeuWeissbier = $lagerbestand[5] - $weissbier;
		$lagerbestandNeuSchwarzbier = $lagerbestand[6] - $schwarzbier;
		$lagerbestandNeuAltbier = $lagerbestand[7] - $altbier;
		$lagerbestandNeuAlmradler = $lagerbestand[14] - $almradler;
		$lagerbestandNeuRadler = $lagerbestand[15] - $radler;
		$lagerbestandNeuDiesel = $lagerbestand[16] - $diesel;
		$lagerbestandNeuGoass = $lagerbestand[17] - $goass;
		$lagerbestandNeuColaweizen = $lagerbestand[18] - $colaweizen;
		$lagerbestandNeuRuss = $lagerbestand[19] - $russ;
		$lagerbestandNeuMineralwasser = $lagerbestand[26] - $mineralwasser;
		$lagerbestandNeuCola = $lagerbestand[27] - $cola;
		$lagerbestandNeuZlimo = $lagerbestand[28] - $zlimo;
		$lagerbestandNeuOlimo = $lagerbestand[29] - $olimo;
		$lagerbestandNeuApfelschorle = $lagerbestand[30] - $apfelschorle;
		$lagerbestandNeuColamix = $lagerbestand[31] - $colamix;
		$lagerbestandNeuAsaft = $lagerbestand[37] - $asaft;
		$lagerbestandNeuOsaft = $lagerbestand[38] - $osaft;
		$lagerbestandNeuKsaft = $lagerbestand[39] - $ksaft;
		$lagerbestandNeuBsaft = $lagerbestand[40] - $bsaft;
		$lagerbestandNeuJsaft = $lagerbestand[41] - $jsaft;
		$lagerbestandNeuPsaft = $lagerbestand[42] - $psaft;
		$lagerbestandNeuKorn = $lagerbestand[8] - $korn;
		$lagerbestandNeuWhiskey = $lagerbestand[9] - $whiskey;
		$lagerbestandNeuWodka = $lagerbestand[10] - $wodka;
		$lagerbestandNeuObstbrand = $lagerbestand[11] - $obstbrand;
		$lagerbestandNeuRum = $lagerbestand[12] - $rum;
		$lagerbestandNeuLikeur = $lagerbestand[13] - $likeur;
		$lagerbestandNeuSotb = $lagerbestand[20] - $sotb;
		$lagerbestandNeuLiit = $lagerbestand[21] - $liit;
		$lagerbestandNeuZombie = $lagerbestand[22] - $zombie;
		$lagerbestandNeuHurricane = $lagerbestand[23] - $hurricane;
		$lagerbestandNeuCaipirinha = $lagerbestand[24] - $caipirinha;
		$lagerbestandNeuMojito = $lagerbestand[25] - $mojito;
		$lagerbestandNeuWeisswein = $lagerbestand[32] - $weisswein;
		$lagerbestandNeuRotwein = $lagerbestand[33] - $rotwein;
		$lagerbestandNeuSchaumwein = $lagerbestand[34] - $schaumwein;
		$lagerbestandNeuLikeurwein = $lagerbestand[35] - $likeurwein;
		$lagerbestandNeuRosewein = $lagerbestand[36] - $rosewein;
		
		// Lagerbestand updaten
		/*
		echo "UPDATE `getraenkelager` SET
		`pils`='$lagerbestandNeuPils', `helles`='$lagerbestandNeuHelles', `lager`='$lagerbestandNeuLager',
		`weissbier`='$lagerbestandNeuWeissbier',`schwarzbier`='$lagerbestandNeuSchwarzbier',`altbier`='$lagerbestandNeuAltbier',
		`korn`='$lagerbestandNeuKorn',`whiskey`='$lagerbestandNeuWhiskey',`wodka`='$lagerbestandNeuWodka',
		`obstbrand`='$lagerbestandNeuObstbrand',`rum`='$lagerbestandNeuRum',`likeur`='$lagerbestandNeuLikeur',
		`almradler`='$lagerbestandNeuAlmradler',`radler`='$lagerbestandNeuRadler',`diesel`='$lagerbestandNeuDiesel',
		`goass`='$lagerbestandNeuGoass',`colaweizen`='$lagerbestandNeuColaweizen',`russ`='$lagerbestandNeuRuss',
		`sexonthebeach`='$lagerbestandNeuSotb',`longislandicedtea`='$lagerbestandNeuLiit',`zombie`='$lagerbestandNeuZombie',
		`hurricane`='$lagerbestandNeuHurricane',`caipirinha`='$lagerbestandNeuCaipirinha',`mojito`='$lagerbestandNeuMojito',
		`mineralwasser`='$lagerbestandNeuMineralwasser',`cola`='$lagerbestandNeuCola',`zitronenlimo`='$lagerbestandNeuZlimo',
		`orangenlimo`='$lagerbestandNeuOlimo',`apfelschorle`='$lagerbestandNeuApfelschorle',`colamix`='$lagerbestandNeuColamix',
		`weisswein`='$lagerbestandNeuWeisswein',`rotwein`='$lagerbestandNeuRotwein',`schaumwein`='$lagerbestandNeuSchaumwein',
		`likeurwein`='$lagerbestandNeuLikeurwein',`rosewein`='$lagerbestandNeuRosewein',
		`apfelsaft`='$lagerbestandNeuAsaft',`orangensaft`='$lagerbestandNeuOsaft',`kirschsaft`='$lagerbestandNeuKsaft',
		`bananensaft`='$lagerbestandNeuBsaft',`johannisbeersaft`='$lagerbestandNeuJsaft',`pfirsichsaft`='$lagerbestandNeuPsaft'
		WHERE `gebaeude_id`='$row[0]'";
		echo "<br><br>";
		*/	
		// Ausgabe der Spalten
		echo "	<tr>
					<td>$row[1]</td>
					<td>$row[0]</td>
					<td>$row[4]</td>
					<td>$row[2]</td>
					<td>$teenager</td>
					<td>$jungeErw</td>
					<td>$familien</td>
					<td>$rentner</td>
					<td>$erwachsene</td>
					<td>" . ($teenager + $jungeErw + $familien + $rentner + $erwachsene) . "</td>
					<td>
						<div style='font-size: 12px;'>
							Stil: $row[5]<br>
							PZG: $row[6]<br>
							SZG: $row[7]<br>
							Sitzpl&auml;tze: $row[8]<br>
							Toiletten M: $row[9]<br>
							Toiletten W: $row[10]<br>
						</div>
					</td>
					<td>
						<div style='font-size: 12px;'>
						Zapfanlage: $gebaeudeerweiterungen[2]<br>
						Stammtisch: $gebaeudeerweiterungen[3]<br>
						Festzelt: $gebaeudeerweiterungen[4]<br>
						Bar: $gebaeudeerweiterungen[5]<br>
						Kaminofen: $gebaeudeerweiterungen[6]<br>"
						/*Happy Hour:<br>"
						. $startH . " - " . $endeH . "<br>
						Zeitdiff: " . $timeDiff . "<br>*/ . "
						Biergarten: $gebaeudeerweiterungen[9]<br>
						K&uuml;che: $gebaeudeerweiterungen[10]<br>
						Speisekarte: $gebaeudeerweiterungen[11]<br>
						Tageskarte: $gebaeudeerweiterungen[12]<br>
						Servierwagen: $gebaeudeerweiterungen[13]<br>
						Menage: $gebaeudeerweiterungen[14]<br>
						</div>
					</td>
					<td>
						$row[6]: $pzgNeu<br>
						$row[7]: $szgNeu<br>
					</td>
					<td>
						<div style='font-size: 12px;'>"
						/*Verteilung: " . print_r(verteilung(6, $bierangebot))*/ . "<br>
						Pils: " . $pils . " Liter<br>
						Helles: " . $helles . " Liter<br>
						Lager: " . $lager . " Liter<br>
						Wei&szlig;bier: " . $weissbier . " Liter<br>
						Schwarzbier: " . $schwarzbier . " Liter<br>
						Altbier: " . $altbier . " Liter<br>
						Gesamt:<b> " . ($pils + $helles + $lager + $weissbier + $schwarzbier + $altbier) . " Liter</b><br>
						Umsatz:<b> " . ($umsatzPils + $umsatzHelles + $umsatzLager + $umsatzWeissbier + $umsatzSchwarzbier + $umsatzAltbier) . " &euro;</b><br>
						</div>
					</td>
					<td>
						<div style='font-size: 12px;'>"
						/*Verteilung: " . print_r(verteilung(6, $biermischangebot))*/ . "<br>
						Almradler: " . $almradler . " Liter<br>
						Radler: " . $radler . " Liter<br>
						Diesel: " . $diesel . " Liter<br>
						Goass: " . $goass . " Liter<br>
						Colaweizen: " . $colaweizen . " Liter<br>
						Russ: " . $russ . " Liter<br>
						Gesamt:<b> " . ($almradler + $radler + $diesel + $goass + $colaweizen + $russ) . " Liter</b><br>
						Umsatz:<b> " . ($umsatzAlmradler + $umsatzRadler + $umsatzDiesel + $umsatzGoass + $umsatzColaweizen + $umsatzRuss) . " &euro;</b><br>
						</div>
					</td>
					<td>
						<div style='font-size: 12px;'>"
						/*Verteilung: " . print_r(verteilung(6, $alkoholfreiangebot))*/ . "<br>
						Mineralwasser: " . $mineralwasser . " Liter<br>
						Cola: " . $cola . " Liter<br>
						Zitronenlimo: " . $zlimo . " Liter<br>
						Orangenlimo: " . $olimo . " Liter<br>
						Apfelschorle: " . $apfelschorle . " Liter<br>
						Cola-Mix: " . $colamix . " Liter<br>
						Gesamt:<b> " . ($mineralwasser + $cola + $zlimo + $olimo + $apfelschorle + $colamix) . " Liter</b><br>
						Umsatz:<b> " . ($umsatzMineralwasser + $umsatzCola + $umsatzZlimo + $umsatzOlimo + $umsatzApfelschorle + $umsatzColamix) . " &euro;</b><br>
						</div>
					</td>
					<td>
						<div style='font-size: 12px;'>"
						/*Verteilung S&auml;fte: " . print_r(verteilung(6, $saftangebot))*/ . "<br>
						Apfelsaft: " . $asaft . " Liter<br>
						Orangensaft: " . $osaft . " Liter<br>
						Kirschsaft: " . $ksaft . " Liter<br>
						Bananensaft: " . $bsaft . " Liter<br>
						Johannisbeersaft: " . $jsaft . " Liter<br>
						Pfirsichsaft: " . $psaft . " Liter<br>
						Gesamt:<b> " . ($asaft + $osaft + $ksaft + $bsaft + $jsaft + $psaft) . " Liter</b><br>
						Umsatz:<b> " . ($umsatzAsaft + $umsatzOsaft + $umsatzKsaft + $umsatzBsaft + $umsatzJsaft + $umsatzPsaft) . " &euro;</b><br>
						</div>
					</td>
					<td>
						<div style='font-size: 12px;'>"
						/*Verteilung Spirituosen: " . print_r(verteilung(6, $spirituosenangebot))*/ . "<br>
						Korn: " . $korn . " Liter<br>
						Whiskey: " . $whiskey . " Liter<br>
						Wodka: " . $wodka . " Liter<br>
						Obstbrand: " . $obstbrand . " Liter<br>
						Rum: " . $rum . " Liter<br>
						Lik&ouml;r: " . $likeur . " Liter<br>
						Gesamt:<b> " . ($korn + $whiskey + $wodka + $obstbrand + $rum + $likeur) . " Liter</b><br>
						Umsatz:<b> " . ($umsatzKorn + $umsatzWhiskey + $umsatzWodka + $umsatzObstbrand + $umsatzRum + $umsatzLikeur) . " &euro;</b><br>
						</div>
					</td>
					<td>
						<div style='font-size: 12px;'>"
						/*Verteilung Cocktails: " . print_r(verteilung(6, $cocktailangebot))*/ ."<br>
						Sex on the Beach: " . $sotb . " Liter<br>
						Long Island Iced Tea: " . $liit . " Liter<br>
						Zombie: " . $zombie . " Liter<br>
						Hurricane: " . $hurricane . " Liter<br>
						Caipirinha: " . $caipirinha . " Liter<br>
						Mojito: " . $mojito . " Liter<br>
						Gesamt:<b> " . ($sotb + $liit + $zombie + $hurricane + $caipirinha + $mojito) . " Liter</b><br>
						Umsatz:<b> " . ($umsatzSotb + $umsatzLiit + $umsatzZombie + $umsatzHurricane + $umsatzCaipirinha + $umsatzMojito) . " &euro;</b><br>
						</div>	
					</td>
					<td>
						<div style='font-size: 12px;'>"
						/*Verteilung Weine: " . print_r(verteilung(5, $weinangebot))*/ . "<br>
						Weisswein: " . $weisswein . " Liter<br>
						Rotwein: " . $rotwein . " Liter<br>
						Schaumwein: " . $schaumwein . " Liter<br>
						Lik&ouml;rwein: " . $likeurwein . " Liter<br>
						Rosewein: " . $rosewein . " Liter<br>
						Gesamt:<b> " . ($weisswein + $rotwein + $schaumwein + $likeurwein + $rosewein) . " Liter</b><br>
						Umsatz:<b> " . ($umsatzWeisswein + $umsatzRotwein + $umsatzSchaumwein + $umsatzLikeurwein + $umsatzRosewein) . " &euro;</b><br>
						</div>	
					</td>
					<td>
						$gesamtumsatz &euro;
					</td>
				</tr>";
		

		/*
		echo "<b>Preise</b><br>";
		echo "<div style='font-size: 13px;'>";
		echo "Pils: " . $getraenkeangebot[3] . " &euro; (Verkaufsfaktor: " . $kauffaktorPils . ")<br>";
		echo "Helles: " . $getraenkeangebot[5] . " &euro; (Verkaufsfaktor: " . $kauffaktorHelles . ")<br>";
		echo "Lager: " . $getraenkeangebot[7] . " &euro; (Verkaufsfaktor: " . $kauffaktorLager . ")<br>";
		echo "Weissbier: " . $getraenkeangebot[9] . " &euro; (Verkaufsfaktor: " . $kauffaktorWeissbier . ")<br>";
		echo "Schwarzbier: " . $getraenkeangebot[11] . " &euro; (Verkaufsfaktor: " . $kauffaktorSchwarzbier . ")<br>";
		echo "Altbier: " . $getraenkeangebot[13] . " &euro; (Verkaufsfaktor: " . $kauffaktorAltbier . ")<br>";
		echo "Almradler: " . $getraenkeangebot[27] . " &euro; (Verkaufsfaktor: " . $kauffaktorAlmradler . ")<br>";
		echo "Radler: " . $getraenkeangebot[29] . " &euro; (Verkaufsfaktor: " . $kauffaktorRadler . ")<br>";
		echo "Diesel: " . $getraenkeangebot[31] . " &euro; (Verkaufsfaktor: " . $kauffaktorDiesel . ")<br>";
		echo "Goass: " . $getraenkeangebot[33] . " &euro; (Verkaufsfaktor: " . $kauffaktorGoass . ")<br>";
		echo "Colaweizen: " . $getraenkeangebot[35] . " &euro; (Verkaufsfaktor: " . $kauffaktorColaweizen . ")<br>";
		echo "Russ: " . $getraenkeangebot[37] . " &euro; (Verkaufsfaktor: " . $kauffaktorRuss . ")<br>";
		echo "Mineralwasser: " . $getraenkeangebot[51] . " &euro; (Verkaufsfaktor: " . $kauffaktorMineralwasser . ")<br>";
		echo "Cola: " . $getraenkeangebot[53] . " &euro; (Verkaufsfaktor: " . $kauffaktorCola . ")<br>";
		echo "Zitronenlimo: " . $getraenkeangebot[55] . " &euro; (Verkaufsfaktor: " . $kauffaktorZlimo . ")<br>";
		echo "Orangenlimo: " . $getraenkeangebot[57] . " &euro; (Verkaufsfaktor: " . $kauffaktorOlimo . ")<br>";
		echo "Apfelschorle: " . $getraenkeangebot[59] . " &euro; (Verkaufsfaktor: " . $kauffaktorApfelschorle . ")<br>";
		echo "Cola-Mix: " . $getraenkeangebot[61] . " &euro; (Verkaufsfaktor: " . $kauffaktorColamix . ")<br>";
		echo "Apfelsaft: " . $getraenkeangebot[73] . " &euro; (Verkaufsfaktor: " . $kauffaktorAsaft . ")<br>";
		echo "Orangensaft: " . $getraenkeangebot[75] . " &euro; (Verkaufsfaktor: " . $kauffaktorOsaft . ")<br>";
		echo "Kirschsaft: " . $getraenkeangebot[77] . " &euro; (Verkaufsfaktor: " . $kauffaktorKsaft . ")<br>";
		echo "Bananensaft: " . $getraenkeangebot[79] . " &euro; (Verkaufsfaktor: " . $kauffaktorBsaft . ")<br>";
		echo "Johannisbeersaft: " . $getraenkeangebot[81] . " &euro; (Verkaufsfaktor: " . $kauffaktorJsaft . ")<br>";
		echo "Pfirsichsaft: " . $getraenkeangebot[83] . " &euro; (Verkaufsfaktor: " . $kauffaktorPsaft . ")<br>";
		echo "Korn: " . $getraenkeangebot[15] . " &euro; (Verkaufsfaktor: " . $kauffaktorKorn . ")<br>";
		echo "Whiskey: " . $getraenkeangebot[17] . " &euro; (Verkaufsfaktor: " . $kauffaktorWhiskey . ")<br>";
		echo "Wodka: " . $getraenkeangebot[19] . " &euro; (Verkaufsfaktor: " . $kauffaktorWodka . ")<br>";
		echo "Obstbrand: " . $getraenkeangebot[21] . " &euro; (Verkaufsfaktor: " . $kauffaktorObstbrand . ")<br>";
		echo "Rum: " . $getraenkeangebot[23] . " &euro; (Verkaufsfaktor: " . $kauffaktorRum . ")<br>";
		echo "Likeur: " . $getraenkeangebot[25] . " &euro; (Verkaufsfaktor: " . $kauffaktorLikeur . ")<br>";
		echo "Sex on the Beach: " . $getraenkeangebot[39] . " &euro; (Verkaufsfaktor: " . $kauffaktorSotb . ")<br>";
		echo "Long Island Iced Tea: " . $getraenkeangebot[41] . " &euro; (Verkaufsfaktor: " . $kauffaktorLiit . ")<br>";
		echo "Zombie: " . $getraenkeangebot[43] . " &euro; (Verkaufsfaktor: " . $kauffaktorZombie . ")<br>";
		echo "Hurricane: " . $getraenkeangebot[45] . " &euro; (Verkaufsfaktor: " . $kauffaktorHurricane . ")<br>";
		echo "Caipirinha: " . $getraenkeangebot[47] . " &euro; (Verkaufsfaktor: " . $kauffaktorCaipirinha . ")<br>";
		echo "Mojito: " . $getraenkeangebot[49] . " &euro; (Verkaufsfaktor: " . $kauffaktorMojito . ")<br>";
		echo "Wei&szlig;wein: " . $getraenkeangebot[63] . " &euro; (Verkaufsfaktor: " . $kauffaktorWeisswein . ")<br>";
		echo "Rotwein: " . $getraenkeangebot[65] . " &euro; (Verkaufsfaktor: " . $kauffaktorRotwein . ")<br>";
		echo "Schaumwein: " . $getraenkeangebot[67] . " &euro; (Verkaufsfaktor: " . $kauffaktorSchaumwein . ")<br>";
		echo "Lik&ouml;rwein: " . $getraenkeangebot[69] . " &euro; (Verkaufsfaktor: " . $kauffaktorLikeurwein . ")<br>";
		echo "Rosewein: " . $getraenkeangebot[71] . " &euro; (Verkaufsfaktor: " . $kauffaktorRosewein . ")<br>";
		echo "</div>";
		
		//print_r(verteilung(6, 6));
		echo "<br><br>";
		*/
	}
}
// Ende der Tabelle
echo "</tbody></table>";

// Aktuelle Bierpreise abfragen
$preisanfrage = mysql_query("SELECT * FROM preise");
$preise = mysql_fetch_array($preisanfrage, MYSQL_NUM);
// Ober- und Untergrenzen festlegen
$bierMin = 40; $bierMax = 50;
$schnapsMin = 12; $schnapsMax = 15;
$biermischMin = 8; $biermischMax = 12;
$alkoholfreiMin = 5; $alkoholfreiMax = 7;
$saftMin = 6; $saftMax = 8;
$weinMin = 3; $weinMax = 5;
$cocktailsMin = 40; $cocktailsMax = 50;
// Zufallsschwankung berechnen (zwischen -5% und 5%)
$schwankung = array(0, 0, 1, -1, 1, -1, 1, -1, 1, -1, 1, -1, 2, -2, 2, -2, 2, -2, 2, -2, 3, -3, 3, -3, 3, -3, 4, -4, 4, -4, 5, -5);
// Neue Bierpreise
$preisNeuPils = neuerPreis($bierMin, $bierMax, $preise[0], $schwankung[rand(0, 31)]);
$preisNeuHelles = neuerPreis($bierMin, $bierMax, $preise[1], $schwankung[rand(0, 31)]);
$preisNeuLager = neuerPreis($bierMin, $bierMax, $preise[2], $schwankung[rand(0, 31)]);
$preisNeuWeissbier = neuerPreis($bierMin, $bierMax, $preise[3], $schwankung[rand(0, 31)]);
$preisNeuSchwarzbier = neuerPreis($bierMin, $bierMax, $preise[4], $schwankung[rand(0, 31)]);
$preisNeuAltbier = neuerPreis($bierMin, $bierMax, $preise[5], $schwankung[rand(0, 31)]);
// Neue Schnapspreise
$preisNeuKorn = neuerPreis($schnapsMin, $schnapsMax, $preise[6], $schwankung[rand(0, 31)]);
$preisNeuWhiskey = neuerPreis($schnapsMin, $schnapsMax, $preise[7], $schwankung[rand(0, 31)]);
$preisNeuWodka = neuerPreis($schnapsMin, $schnapsMax, $preise[8], $schwankung[rand(0, 31)]);
$preisNeuObstbrand = neuerPreis($schnapsMin, $schnapsMax, $preise[9], $schwankung[rand(0, 31)]);
$preisNeuRum = neuerPreis($schnapsMin, $schnapsMax, $preise[10], $schwankung[rand(0, 31)]);
$preisNeuLikeur = neuerPreis($schnapsMin, $schnapsMax, $preise[11], $schwankung[rand(0, 31)]);
// Neue Biermischpreise
$preisNeuAlmradler = neuerPreis($biermischMin, $biermischMax, $preise[12], $schwankung[rand(0, 31)]);
$preisNeuRadler = neuerPreis($biermischMin, $biermischMax, $preise[13], $schwankung[rand(0, 31)]);
$preisNeuDiesel = neuerPreis($biermischMin, $biermischMax, $preise[14], $schwankung[rand(0, 31)]);
$preisNeuGoass = neuerPreis($biermischMin, $biermischMax, $preise[15], $schwankung[rand(0, 31)]);
$preisNeuColaweizen = neuerPreis($biermischMin, $biermischMax, $preise[16], $schwankung[rand(0, 31)]);
$preisNeuRuss = neuerPreis($biermischMin, $biermischMax, $preise[17], $schwankung[rand(0, 31)]);
// Neue Alkoholfreie Preise
$preisNeuMineralwasser = neuerPreis($alkoholfreiMin, $alkoholfreiMax, $preise[18], $schwankung[rand(0, 31)]);
$preisNeuCola = neuerPreis($alkoholfreiMin, $alkoholfreiMax, $preise[19], $schwankung[rand(0, 31)]);
$preisNeuZlimo = neuerPreis($alkoholfreiMin, $alkoholfreiMax, $preise[20], $schwankung[rand(0, 31)]);
$preisNeuOlimo = neuerPreis($alkoholfreiMin, $alkoholfreiMax, $preise[21], $schwankung[rand(0, 31)]);
$preisNeuApfelschorle = neuerPreis($alkoholfreiMin, $alkoholfreiMax, $preise[22], $schwankung[rand(0, 31)]);
$preisNeuColamix = neuerPreis($alkoholfreiMin, $alkoholfreiMax, $preise[23], $schwankung[rand(0, 31)]);
// Neue Saftpreise
$preisNeuAsaft = neuerPreis($saftMin, $saftMax, $preise[24], $schwankung[rand(0, 31)]);
$preisNeuOsaft = neuerPreis($saftMin, $saftMax, $preise[25], $schwankung[rand(0, 31)]);
$preisNeuKsaft = neuerPreis($saftMin, $saftMax, $preise[26], $schwankung[rand(0, 31)]);
$preisNeuBsaft = neuerPreis($saftMin, $saftMax, $preise[27], $schwankung[rand(0, 31)]);
$preisNeuJsaft = neuerPreis($saftMin, $saftMax, $preise[28], $schwankung[rand(0, 31)]);
$preisNeuPsaft = neuerPreis($saftMin, $saftMax, $preise[29], $schwankung[rand(0, 31)]);
// Neue Cocktailpreise
$preisNeuSotb = neuerPreis($cocktailsMin, $cocktailsMax, $preise[30], $schwankung[rand(0, 31)]);
$preisNeuLiit = neuerPreis($cocktailsMin, $cocktailsMax, $preise[31], $schwankung[rand(0, 31)]);
$preisNeuZombie = neuerPreis($cocktailsMin, $cocktailsMax, $preise[32], $schwankung[rand(0, 31)]);
$preisNeuHurricane = neuerPreis($cocktailsMin, $cocktailsMax, $preise[33], $schwankung[rand(0, 31)]);
$preisNeuCaipirinha = neuerPreis($cocktailsMin, $cocktailsMax, $preise[34], $schwankung[rand(0, 31)]);
$preisNeuMojito = neuerPreis($cocktailsMin, $cocktailsMax, $preise[35], $schwankung[rand(0, 31)]);
// Neue Weinpreise
$preisNeuWeisswein = neuerPreis($weinMin, $weinMax, $preise[36], $schwankung[rand(0, 31)]);
$preisNeuRotwein = neuerPreis($weinMin, $weinMax, $preise[37], $schwankung[rand(0, 31)]);
$preisNeuSchaumwein = neuerPreis($weinMin, $weinMax, $preise[38], $schwankung[rand(0, 31)]);
$preisNeuLikeurwein = neuerPreis($weinMin, $weinMax, $preise[39], $schwankung[rand(0, 31)]);
$preisNeuRosewein = neuerPreis($weinMin, $weinMax, $preise[40], $schwankung[rand(0, 31)]);
// Ausgabe der neuen Preise
echo "<b>Neue Preise</b><br>";
echo "Pils: " . $preisNeuPils . "(" . $preise[0] . ")<br>";
echo "Helles: " . $preisNeuHelles . "(" . $preise[1] . ")<br>";
echo "Lager: " . $preisNeuLager . "(" . $preise[2] . ")<br>";
echo "Wei&szlig;bier: " . $preisNeuWeissbier . "(" . $preise[3] . ")<br>";
echo "Schwarzbier: " . $preisNeuSchwarzbier . "(" . $preise[4] . ")<br>";
echo "Altbier: " . $preisNeuAltbier . "(" . $preise[5] . ")<br>";
echo "Korn: " . $preisNeuKorn . "(" . $preise[6] . ")<br>";
echo "Whiskey: " . $preisNeuWhiskey . "(" . $preise[7] . ")<br>";
echo "Wodka: " . $preisNeuWodka . "(" . $preise[8] . ")<br>";
echo "Obstbrand: " . $preisNeuObstbrand . "(" . $preise[9] . ")<br>";
echo "Rum: " . $preisNeuRum . "(" . $preise[10] . ")<br>";
echo "Lik&ouml;r: " . $preisNeuLikeur . "(" . $preise[11] . ")<br>";
echo "Almradler: " . $preisNeuAlmradler . "(" . $preise[12] . ")<br>";
echo "Radler: " . $preisNeuRadler . "(" . $preise[13] . ")<br>";
echo "Diesel: " . $preisNeuDiesel . "(" . $preise[14] . ")<br>";
echo "Goass: " . $preisNeuGoass . "(" . $preise[15] . ")<br>";
echo "Colaweizen: " . $preisNeuColaweizen . "(" . $preise[16] . ")<br>";
echo "Russ: " . $preisNeuRuss . "(" . $preise[17] . ")<br>";
echo "Mineralwasser: " . $preisNeuMineralwasser . "(" . $preise[18] . ")<br>";
echo "Cola: " . $preisNeuCola . "(" . $preise[19] . ")<br>";
echo "Zlimo: " . $preisNeuZlimo . "(" . $preise[20] . ")<br>";
echo "Olimo: " . $preisNeuOlimo . "(" . $preise[21] . ")<br>";
echo "Apfelschorle: " . $preisNeuApfelschorle . "(" . $preise[22] . ")<br>";
echo "Colamix: " . $preisNeuColamix . "(" . $preise[23] . ")<br>";
echo "Apfelsaft: " . $preisNeuAsaft . "(" . $preise[24] . ")<br>";
echo "Orangensaft: " . $preisNeuOsaft . "(" . $preise[25] . ")<br>";
echo "Kirschsaft: " . $preisNeuKsaft . "(" . $preise[26] . ")<br>";
echo "Bananensaft: " . $preisNeuBsaft . "(" . $preise[27] . ")<br>";
echo "Johannisbeersaft: " . $preisNeuJsaft . "(" . $preise[28] . ")<br>";
echo "Pfirsichsaft: " . $preisNeuPsaft . "(" . $preise[29] . ")<br>";
echo "Sex on the Beach: " . $preisNeuSotb . "(" . $preise[30] . ")<br>";
echo "Long Island Iced Tea: " . $preisNeuLiit . "(" . $preise[31] . ")<br>";
echo "Zombie: " . $preisNeuZombie . "(" . $preise[32] . ")<br>";
echo "Hurricane: " . $preisNeuHurricane . "(" . $preise[33] . ")<br>";
echo "Caipirinha: " . $preisNeuCaipirinha . "(" . $preise[34] . ")<br>";
echo "Mojito: " . $preisNeuMojito . "(" . $preise[35] . ")<br>";
echo "Wei&szlig;wein: " . $preisNeuWeisswein . "(" . $preise[36] . ")<br>";
echo "Rotwein: " . $preisNeuRotwein . "(" . $preise[37] . ")<br>";
echo "Schaumwein: " . $preisNeuSchaumwein . "(" . $preise[38] . ")<br>";
echo "Lik&ouml;rwein: " . $preisNeuLikeurwein . "(" . $preise[39] . ")<br>";
echo "Rosewein: " . $preisNeuRosewein . "(" . $preise[40] . ")<br>";
// Funktion zum Berechnen neuer Preise
function neuerPreis($min, $max, $preisAlt, $schwankung){
	$neuerPreis = round(($preisAlt * (1 + ($schwankung / 100))), 2);
	if($neuerPreis < $min)
		$neuerPreis = $min;
	else if($neuerPreis > $max)
		$neuerPreis = $max;
	return $neuerPreis;
}

// Funktion zum Berechnen des Absatzes
function berechneAbsatz($getraenkeangebot, $gesamtverkauf, $verteilung, $kauffaktor, $einheit, $verkaufte){
	$rueckgabe = array('0', '0');
	if ($getraenkeangebot == 1){
		$rueckgabe[0] = ((ceil(((($gesamtverkauf * 15) * $verteilung) * $kauffaktor) / $einheit)) * $einheit);
		$rueckgabe[1] = ($verkaufte + 1);
	}
	else{
		$rueckgabe[0] = 0;
		$rueckgabe[1] = $verkaufte;
	}
	return $rueckgabe;
}

// Funktion zum Berechnen des Kauffaktors
function preisFaktor($preisFaktor){
	if ($preisFaktor == 1)
		$kauffaktor = 1;
	else if ($preisFaktor > 1 && $preisFaktor <= 1.1)
		$kauffaktor = 0.95;
	else if ($preisFaktor > 1.1 && $preisFaktor <= 1.15)
		$kauffaktor = 0.90;
	else if ($preisFaktor > 1.15 && $preisFaktor <= 1.2)
		$kauffaktor = 0.80;
	else if ($preisFaktor > 1.2 && $preisFaktor <= 1.25)
		$kauffaktor = 0.70;
	else if ($preisFaktor > 1.25 && $preisFaktor <= 1.3)
		$kauffaktor = 0.60;
	else if ($preisFaktor > 1.3 && $preisFaktor <= 1.35)
		$kauffaktor = 0.50;
	else if ($preisFaktor > 1.35 && $preisFaktor <= 1.5)
		$kauffaktor = 0.35;
	else if ($preisFaktor > 1.5 && $preisFaktor <= 1.75)
		$kauffaktor = 0.25;
	else if ($preisFaktor > 1.75 && $preisFaktor <= 2)
		$kauffaktor = 0.15;
	else if ($preisFaktor > 2)
		$kauffaktor = 0;
	else if ($preisFaktor < 1 && $preisFaktor >= 0.95)
		$kauffaktor = 1.1;
	else if ($preisFaktor < 0.95 && $preisFaktor >= 0.90)
		$kauffaktor = 1.2;
	else if ($preisFaktor < 0.90 && $preisFaktor >= 0.80)
		$kauffaktor = 1.3;
	else if ($preisFaktor < 0.80 && $preisFaktor >= 0.70)
		$kauffaktor = 1.35;
	else if ($preisFaktor < 0.70 && $preisFaktor >= 0.60)
		$kauffaktor = 1.5;
	else if ($preisFaktor < 0.60 && $preisFaktor >= 0.50)
		$kauffaktor = 1.7;
	else if ($preisFaktor < 0.50 && $preisFaktor >= 0.35)
		$kauffaktor = 2;
	else if ($preisFaktor < 0.35 && $preisFaktor >= 0.25)
		$kauffaktor = 2.6;
	else if ($preisFaktor < 0.25 && $preisFaktor >= 0.15)
		$kauffaktor = 3.5;
	else if ($preisFaktor < 0.15 && $preisFaktor >= 0.10)
		$kauffaktor = 4.5;
	else if ($preisFaktor < 0.10 && $preisFaktor >= 0.05)
		$kauffaktor = 7;
	else if ($preisFaktor < 0.05)
		$kauffaktor = 10;	
	return $kauffaktor;
}

// Funktion zum Berechnen der Verteilung
function verteilung ($stellen, $anzahl){
	if ($anzahl == 0){
		$array = array();
		for ($i = 0; $i < $stellen; $i++){
			$array[] = 0;
		}
	}
	else{
		$array = array();
		for ($i = 0; $i < $anzahl; $i++){
			$array[] = 0;
		}
		$abzug = 0;
		while ($anzahl != 1){
			$wert1 = (100 - $abzug)/$anzahl;
			//echo "Ursprungswert: " . ($wert1 * 0.01) . "<br>";
			$zahl = rand(($wert1 - 8), ($wert1 + 8));
			//echo "Zufallszahl: " . ($zahl * 0.01) . "<br>";
			$array[($anzahl - 1)] = ($zahl * 0.01);
			$abzug = $abzug + $zahl;
			$anzahl--;
		}
		$array[($anzahl - 1)] = ((100 - $abzug) * 0.01);
		//echo "Zufallszahl: " . ((100 - $abzug) * 0.01) . "<br>";
		//echo "Summe: " . (($abzug + (100 - $abzug)) * 0.01) . "<br>";
		//print_r($array);
		//echo "<br>";
		for ($i = 0; $i < (rand(5, 10)); $i++){
			shuffle($array);
		}
	}
	return $array;
}

include 'footer.php';