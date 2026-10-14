<?php $title="Userstatistik"; include '../sessionpruefung.php'; include '../geheim.php';?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-success"><b>Diese Seite ist komplett &uuml;berarbeitet. <a style="font-size: 11px;" href="#">Fehler melden</a></b></div>
<!-- INHALTSBESCHREIBUNG -->
<div class="inhalt" align="center">
	<div align="center">
		<img style="height: 80px;" src="/img/bauarbeiter.png">
		<h2>Anzeige einiger Statistiken</h2><br>
	</div>
  	<b>Folgender Inhalt:</b><br>
  	> Statistiken (z.B. Gesamtanzahl User, Gesamtanzahl Geb&auml;ude, ...)<br>
  	<br><br>
</div>
<?php
// Gesamte Userzahl abfragen
$gesamtanzahlUser = anfrage("SELECT count(id) FROM user");
// Überschrift
echo "<div align='center'><h3>Allgemeine Userstatistiken</h3></div><br>";
// Ausgabe der Werte
echo "<div align='center'>Gesamte Userzahl: $gesamtanzahlUser[0]</div>";
include '../footer.php';?>