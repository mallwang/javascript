<?php $title="Eigene Angebote"; include '../sessionpruefung.php'; include '../geheim.php';?>
<!-- INHALTSBESCHREIBUNG -->
<div class="inhalt" align="center">
	<div align="center">
		<img style="height: 80px;" src="/img/bauarbeiter.png">
		<h2>&Uuml;bersicht aller eigenen Angebote am Markt</h2><br>
	</div>
	<br>
</div>
<?php
// Firmenname abfragen
$firmenname = $_SESSION['firmenname'];
// Abfrage aller Gebeäude des aktuellen Users
//$anzahlAngebote = mysql_query("SELECT kategorie, art, fertigstellung, baukosten, groesse, standort, id FROM gebaeude WHERE firma_id = '$firmaId'");
include '../footer.php';?>