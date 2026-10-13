<?php
		$id = id ($_SESSION['nickname']);
		$arbeitsende = arbeitsende($id);
		$abfrage_eingetragene_taetigkeit = mysql_query ("SELECT taetigkeit FROM charakter WHERE login_id = '$id'") or die ("Fehler! status_header.php/abfrage_taetigkeit");
		$eingetragene_taetigkeit = mysql_fetch_array($abfrage_eingetragene_taetigkeit);
		$nr = $eingetragene_taetigkeit['taetigkeit'];
		$endzeit = date("H:i:s", strtotime($arbeitsende));
		$anzeige_zeit=true;
		if ($nr == 0) {
			$name="Du hast nichts zu tun";
			$anzeige_zeit=false;}
		else if ($nr == 1) {$name="Jagd";}
		else if ($nr == 2) {$name="Früchte sammeln";}
		else if ($nr == 3) {$name="Fischen";}
		else if ($nr == 4) {$name="Pilze sammeln";}
		else if ($nr == 5) {$name="Holz fällen";}
		else if ($nr == 6) {$name="Steine metzen";}
		else if ($nr == 7) {$name="Wasser suchen";}
		else if ($nr == 8) {$name="Axt herstellen";}
		else if ($nr == 9) {$name="Jagdspeer herstellen";}
		else if ($nr == 10) {$name="Wasserschlauch herstellen";}
		else if ($nr == 11) {$name="Korb herstellen";}
		else if ($nr == 12) {$name="Lendenschurz herstellen";}
		else if ($nr == 13) {$name="Schuhe herstellen";}
		else {$name="Fehler! status_header.php/namenszuweisung";}
		
		echo "Tätigkeit: $name<br>";
		if ($anzeige_zeit){echo "Arbeitsende: $endzeit<br>";}
		else {echo "<br>";}
?>