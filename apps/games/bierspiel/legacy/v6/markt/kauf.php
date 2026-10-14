<?php session_start(); include '../connect.php'; include '../funktionen.php';

// firmaId abfragen
$firmaId = $_SESSION['firmaId'];
// Variablen initialisieren
$art = $_POST["art"];
$menge = $_POST["menge"];
$gaststaette = $_POST["gaststaette"];
// Prüfen, ob Menge eine Zahl

// Name der Art feststellen
switch ($art) {
	case "11": $artName = "pils"; break;
	case "12": $artName = "helles"; break;
	case "13": $artName = "lager"; break;
	case "14": $artName = "weissbier"; break;
	case "15": $artName = "schwarzbier"; break;
	case "16": $artName = "altber"; break;
	case "21": $artName = "korn"; break;
	case "22": $artName = "whiskey"; break;
	case "23": $artName = "wodka"; break;
	case "24": $artName = "obstbrand"; break;
	case "25": $artName = "rum"; break;
	case "26": $artName = "likeur"; break;
	case "31": $artName = "almradler"; break;
	case "32": $artName = "radler"; break;
	case "33": $artName = "diesel"; break;
	case "34": $artName = "goass"; break;
	case "35": $artName = "colaweizen"; break;
	case "36": $artName = "russ"; break;
	case "41": $artName = "mineralwasser"; break;
	case "42": $artName = "cola"; break;
	case "43": $artName = "zitronenlimo"; break;
	case "44": $artName = "orangenlimo"; break;
	case "45": $artName = "apfelschorle"; break;
	case "46": $artName = "colamix"; break;
	case "51": $artName = "apfelsaft"; break;
	case "52": $artName = "orangensaft"; break;
	case "53": $artName = "kirschsaft"; break;
	case "54": $artName = "bananensaft"; break;
	case "55": $artName = "johannisbeersaft"; break;
	case "56": $artName = "pfirsichsaft"; break;
	case "61": $artName = "sexonthebeach"; break;
	case "62": $artName = "longislandicedtea"; break;
	case "63": $artName = "zombie"; break;
	case "64": $artName = "hurricane"; break;
	case "65": $artName = "caipirinha"; break;
	case "66": $artName = "mojito"; break;
	case "71": $artName = "weisswein"; break;
	case "72": $artName = "rotwein"; break;
	case "73": $artName = "schaumwein"; break;
	case "74": $artName = "likeurwein"; break;
	case "75": $artName = "rosewein"; break;
	default: echo "falscheArt"; exit;
}
// Prüfen, ob Firma auch der Besitzer des Gebäudes ist
//echo "SELECT COUNT(id) FROM gebaeude WHERE firma_id = '$firmaId' AND id = '$gaststaette'";echo "<br>";
$besitzer = anfrage("SELECT COUNT(id) FROM gebaeude WHERE firma_id = '$firmaId' AND id = '$gaststaette'");
// Wenn aktueller User auch Besitzer des Gebäudes ist
if ($besitzer[0] == 1){
	// Aktuellen Preis abfragen
	//echo "SELECT preis FROM preise WHERE art = '$artName'";echo "<br>";
	$artikelpreis = anfrage("SELECT $artName FROM preise");
	// Kontostand abfragen
	//echo "SELECT konto FROM finanzen WHERE firma_id = '$firmaId'";echo "<br>";
	$kontostand = anfrage("SELECT konto, a_getraenke, a_gesamt FROM finanzen WHERE firma_id = '$firmaId'");
	// Gesamtkosten berechnen
	$gesamtkosten = $artikelpreis[0] * $menge;
	// Wenn Kontostand ausreicht
	if ($kontostand[0] >= $gesamtkosten){
		// Finanzen Käufer berechnen
		$finanzenNeu = $kontostand[0] - $gesamtkosten;
		// Neue Getränkeausgaben berechnen
		$ausgabenGetraenkeNeu = $kontostand[1] + $gesamtkosten;
		// Neue Gesamtausgaben berechnen
		$ausgabenGesamtNeu = $kontostand[2] + $gesamtkosten;
		// Finanzen Käufer updaten
		//echo "UPDATE finanzen SET konto = '$finanzenNeu' WHERE firma_id = '$firmaId'";echo "<br>";
		$finanzupdate = anfrage("UPDATE finanzen SET konto = '$finanzenNeu', a_getraenke = '$ausgabenGetraenkeNeu', a_gesamt = '$ausgabenGesamtNeu' WHERE firma_id = '$firmaId'");
		// Wenn Finanzupdate erfolgreich
		if ($finanzupdate){
			// Lagerbestand abfragen
			//echo "SELECT $artName FROM getraenkelager WHERE firma_id = '$firmaId' AND gebaeude_id = '$gaststaette'";echo "<br>";
			$lagerbestand = anfrage("SELECT $artName FROM getraenkelager WHERE firma_id = '$firmaId' AND gebaeude_id = '$gaststaette'");
			// Neuen Lagerbestand berechnen
			$lagerbestandNeu = $lagerbestand[0] + ($menge * 1);
			// Lagerbestandupdate
			//echo "UPDATE getraenkelager SET $artName = '$lagerbestandNeu' WHERE firma_id = '$firmaId' AND gebaeude_id = '$gaststaette'";echo "<br>";
			$lagerupdate = anfrage("UPDATE getraenkelager SET $artName = '$lagerbestandNeu' WHERE firma_id = '$firmaId' AND gebaeude_id = '$gaststaette'");
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
	// Wenn Finanzen nicht ausreichen
	else{
		echo "finanzenNichtAusreichend";
	}
}
// Wenn aktueller User nicht Besitzer des Gebäudes ist
else{
	echo "nichtBesitzer";
}