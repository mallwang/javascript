<?php
	
	function id ($nickname)
	{
	$ida = mysql_query("SELECT id FROM login WHERE nickname = '$nickname'") or die ("Fehler! functions.php->id");
	$idb = mysql_fetch_array($ida);
	$idc = $idb["id"];
	return $idc;
	}
	
	function dauer ($id)
	{
	$abfrage_eingetragene_dauer = mysql_query("SELECT dauer FROM charakter WHERE login_id = '$id'")or die("Fehler! functions.php->dauer");
	$eingetragene_dauer = mysql_fetch_array($abfrage_eingetragene_dauer);
	$dauer = $eingetragene_dauer['dauer'];
	return $dauer;
	}
	
	function email ($id)
	{
	$abfrage_eingetragene_email = mysql_query("SELECT email FROM login WHERE id = '$id'")or die("Fehler! functions.php->email");
	$eingetragene_email = mysql_fetch_array($abfrage_eingetragene_email);
	$email = $eingetragene_email['email'];
	return $email;
	}
	
	
	function arbeitsende ($id)
	{
	$abfrage_eingetragenes_arbeitsende = mysql_query("SELECT arbeitsende FROM charakter WHERE login_id = '$id'")or die("Fehler! functions.php->arbeitsende");
	$eingetragenes_arbeitsende = mysql_fetch_array($abfrage_eingetragenes_arbeitsende);
	$arbeitsende = $eingetragenes_arbeitsende['arbeitsende'];
	return $arbeitsende;
	}
	
	function skills ($id, $art)
	{
	$abfrage_eingetragener_skill = mysql_query("SELECT $art FROM skills WHERE login_id = '$id'")or die("Fehler! functions.php->skills");
	$eingetragener_skill = mysql_fetch_array($abfrage_eingetragener_skill);
	$skill = $eingetragener_skill[$art];
	return $skill;
	}
	
	function taetigkeit ($id)
	{
	$abfrage_eingetragene_taetigkeit = mysql_query ("SELECT taetigkeit FROM charakter WHERE login_id = '$id'") or die ("Fehler! functions.php->taetigkeit");
	$eingetragene_taetigkeit = mysql_fetch_array($abfrage_eingetragene_taetigkeit);
	$taetigkeit = $eingetragene_taetigkeit['taetigkeit'];
	return $taetigkeit;
	}
	
	function rohstoffe ($id, $art)
	{
	$abfrage_menge = mysql_query ("SELECT $art FROM rohstoffe WHERE login_id = '$id'") or die ("Fehler! functions.php->rohstoffe");
	$arrayeintrag = mysql_fetch_array($abfrage_menge);
	$menge = $arrayeintrag[$art];
	return $menge;
	}
	
	function inventar ($id, $art)
	{
	$abfrage_menge = mysql_query ("SELECT $art FROM inventar WHERE login_id = '$id'") or die ("Fehler! functions.php->inventar");
	$arrayeintrag = mysql_fetch_array($abfrage_menge);
	$menge = $arrayeintrag[$art];
	return $menge;
	}
	
	function arbeiten ($nr, $dauer)
	{
		$nickname = $_SESSION['nickname'];
		$id = id($nickname);
		$taetigkeit = taetigkeit ($id);
		$arbeitsende = arbeitsende ($id);
		$text = "";
		$name = "";
		$tierische_nahrung=rohstoffe($id, 'tierische_nahrung');
		$wasser=rohstoffe($id, 'wasser');
		
		if ($nr == 1) {$text="Du bist nun auf der Jagd";}
		else if ($nr == 2) {$text="Du bist nun Früchte sammeln";}
		else if ($nr == 3) {$text="Du bist nun Fischen";}
		else if ($nr == 4) {$text="Du bist nun Pilze sammeln";}
		else if ($nr == 5) {$text="Du bist nun Holz fällen";}
		else if ($nr == 6) {$text="Du bist nun Steine metzen";}
		else if ($nr == 7) {$text="Du bist nun Wasser suchen";}
		else if ($nr == 8) {$text="Du stellst nun eine Axt her";}
		else if ($nr == 9) {$text="Du stellst nun einen Jagdspeer her";}
		else if ($nr == 10) {$text="Du stellst nun einen Wasserschlauch";}
		else if ($nr == 11) {$text="Du stellst nun einen Korb her";}
		else if ($nr == 12) {$text="Du bist nun einen Lendenschurz her";}
		else if ($nr == 13) {$text="Du bist nun ein paar Schuhe her";}
		
		if ($dauer == 30){$verbr_tier_nahrung=0.4;$verbr_wasser=0.25;}
		else if ($dauer == 60){$verbr_tier_nahrung=0.8;$verbr_wasser=0.5;}
		else if ($dauer == 120){$verbr_tier_nahrung=1.6;$verbr_wasser=1;}
		else if ($dauer == 240){$verbr_tier_nahrung=3.2;$verbr_wasser=2;}	
		
		if ($taetigkeit == 0)
			{
			$date = date('Y-m-d H:i:s');
			$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + $dauer minutes");
			$arbeitsenddatumzeit = date('Y-m-d H:i:s', $timestamp);
			$arbeitsendzeit = date('H:i:s', $timestamp);
			$eintragen_arbeitsende = mysql_query("UPDATE charakter SET arbeitsende ='$arbeitsenddatumzeit' WHERE login_id = '$id'") or die ("Fehler! functions.php->arbeiten/eintragen_arbeitsende");
			$eintragen_dauer = mysql_query("UPDATE charakter SET dauer ='$dauer' WHERE login_id = '$id'") or die ("Fehler! functions.php->arbeiten/eintragen_dauer");
				if ($tierische_nahrung<=0){$tierische_nahrung_neu=0;}
				else {$tierische_nahrung_neu = $tierische_nahrung - $verbr_tier_nahrung;}
			$eintragen_verbr_tier_nahrung = mysql_query("UPDATE rohstoffe SET tierische_nahrung = '$tierische_nahrung_neu' WHERE login_id = '$id'") or die ("Fehler! functions.php->arbeiten/eintragen_verbr_tier_nahrung");
				if ($wasser<=0){$wasser_neu=0;}
				else {$wasser_neu = $wasser - $verbr_wasser;}
			$eintragen_verbr_wasser = mysql_query("UPDATE rohstoffe SET wasser = '$wasser_neu' WHERE login_id = '$id'") or die ("Fehler! functions.php->arbeiten/eintragen_verbr_wasser");
			$eintragen_taetigkeit = mysql_query("UPDATE charakter SET taetigkeit ='$nr' WHERE login_id = '$id'") or die ("Fehler! functions.php->arbeiten/eintragen_taetigkeit");
			return "$text und kehrst um $arbeitsendzeit wieder zurück<br><br><a align='center' href='Hauptseite.php'>Hauptseite</a>";
			}
		else
			{
			$endzeit = date("H:i:s", strtotime($arbeitsende));
			return "Du bist bereits unterwegs!<br><br><a align='center' href='Status.php'>Status</a><br><a align='center' href='Hauptseite.php'>Zurück</a><br><br>";
			}
	}
	
	function ep ($id)
	{
		$abfrage_ep = mysql_query ("SELECT erfahrungspunkte FROM charakter WHERE login_id = '$id'") or die ("Fehler! functions.php->ep");
		$eingetragene_ep = mysql_fetch_array($abfrage_ep);
		$ep = $eingetragene_ep["erfahrungspunkte"]; // Erfahrungspunkte
		return $ep;
	}
?>