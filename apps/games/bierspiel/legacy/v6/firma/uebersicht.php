<?php $title="Geb&auml;ude&uuml;bersicht"; include '../sessionpruefung.php'; include '../geheim.php';
// FirmenId abfragen
$firmaId = $_SESSION['firmaId'];
// Abfrage aller Gebe‰ude des aktuellen Users
$anzahlGebaeude = mysql_query("SELECT kategorie, art, fertigstellung, baukosten, groesse, standort, id FROM gebaeude WHERE firma_id = '$firmaId'");
// Abfrage aller Geb‰ude
$alleGebaeude = anfrage("SELECT COUNT(id) FROM gebaeude WHERE firma_id = '$firmaId'");
?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-success"><b>Diese Seite ist komplett &uuml;berarbeitet. <a style="font-size: 11px;" href="#">Fehler melden</a></b></div>
<script type="text/javascript">
//Globale variablen deklarieren
var finanzen = "<?php echo $finanzen[0];?>";
var anzahlGebaeude = "<?php echo $alleGebaeude[0];?>";
// Geb‰ude abreissen
function abreissen(elementId){
	// Meldungen lˆschen
	$('#alertFehler').hide();
	$('#alertErfolg').hide();
	// Id herausschneiden
	var id = elementId.substring(6); // z.B. abriss1 -> 1
	// Sicherheitsfrage
	var loeschen = confirm(unescape("M%F6chtest du das ausgew%E4hlte Geb%E4ude wirklich abrei%DFen%3F%0ADu bekommst keine Kostenerstattung%21"));
	// Wenn best‰tigt wurde
	if (loeschen){
		// Wartezeichen anzeigen
		document.getElementById("wait" + id).removeAttribute('style');
		// Ajax aufruf
		$.ajax({
			type: "POST",
			url: "/firma/abreissen.php",
			cache: false,
			data: { id: id},
				success: function(data){
					// Wartezeichen verstecken
					document.getElementById("wait" + id).setAttribute('style', 'display: none;');
					if(data=='ok'){
						// Geb‰ude ausblenden
						document.getElementById(id).setAttribute('style', 'display: none;');
						// Hinweis
						$('#alertErfolg').html("<b>Erfolg!</b> Das Geb&auml;ude wurde abgerissen!");
						$('#alertErfolg').show();
					}
					else if(data=='nichtBesitzer'){
						$("#alertFehler").html("<b>Fehler!</b> Sie sind nicht der rechtm&auml;&szlig;ige Besitzer dieses Geb&auml;udes!");
						$("#alertFehler").show();
					}
					else if(data=='fehlerDateneintrag'){
						$("#alertFehler").html("<b>Fehler!</b> Das Geb&auml;ude konnte nicht abgerissen werden!");
						$("#alertFehler").show();
					}
					else{
               	 		$("#alertFehler").html("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
               	 		$("#alertFehler").show();
					}
				}
		});
		return false;
	}
}

// Geb‰ude verkaufen
function verkaufen(elementId, baukosten){
	// Meldungen lˆschen
	$('#alertFehler').hide();
	$('#alertErfolg').hide();
	// Id herausschneiden
	var id = elementId.substring(7); // z.B. verkauf1 -> 1
	// H‰lfte der Baukosten berechnen
	var haelfte = baukosten/2;
	// Sicherheitsfrage
	var verkaufen = confirm(unescape("M%F6chtest du das ausgew%E4hlte Geb%E4ude wirklich varksufen%3F%0ADu bekommst die H%E4lfte der Baukosten erstattet%21%0AErstattung: " + haelfte + " %u20AC"));
	// Wenn best‰tigt wurde
	if (verkaufen){
		// Wartezeichen anzeigen
		document.getElementById("wait" + id).removeAttribute('style');
		// Ajax aufruf
		$.ajax({
			type: "POST",
			url: "/firma/verkaufen.php",
			cache: false,
			data: { id: id},
				success: function(data){
					// Wartezeichen verstecken
					document.getElementById("wait" + id).setAttribute('style', 'display: none;');
					if(data=='ok'){
						// Geb‰ude ausblenden
						document.getElementById(id).setAttribute('style', 'display: none;');
						// Neuen Kontostand berechnen
						var kontostandNeu = finanzen*1 + haelfte*1;
						finanzen = kontostandNeu;
						// Hinweis
						$('#alertErfolg').html("<b>Erfolg!</b> Das Geb&auml;ude wurde verkauft!<br>" +
								"Erhaltene R&uuml;ckerstattung: " + haelfte + " &euro;<br>" + 
								"Neuer Kontostand: " + kontostandNeu + " &euro;");
						$('#alertErfolg').show();
						// Finanzen updaten
						$("#kontostand").html("Kontostand: <b>" + finanzen + " &euro;</b>");
						// Geb‰udeanzahl verringern
						anzahlGebaeude--;
						// Anzahl Geb‰ude updaten
						$("#anzahlGebaeude").html("Deine Geb&auml;udeanzahl: " + anzahlGebaeude);
					}
					else if(data=='nichtBesitzer'){
						$("#alertFehler").html("<b>Fehler!</b> Sie sind nicht der rechtm&auml;&szlig;ige Besitzer dieses Geb&auml;udes!");
						$("#alertFehler").show();
					}
					else if(data=='fehlerDateneintrag'){
						$("#alertFehler").html("<b>Fehler!</b> Das Geb&auml;ude konnte nicht abgerissen werden!");
						$("#alertFehler").show();
					}
					else{
               	 		$("#alertFehler").html("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
               	 		$("#alertFehler").show();
					}
				}
		});
		return false;
	}
}
</script>
<!--
<div align="center" class="inhalt">
	<div align="center">
		<img style="width: 50px;" src="/img/bauarbeiter.png">
		<h2>&Uuml;bersicht aller Geb&auml;ude nach Kategorie</h2><br>
	</div>
	<b>Folgender Inhalt:</b><br>
	> User sieht eine zusammenstellung aller Geb&auml;ude (mit links zu den jeweiligen Kategorien)<br>
	> Aktuelle Auftr&auml;ge und Stati der Geb&auml;uden (Standort, Gr&ouml;&szlig;e, Arbeitnehmer)<br>
	> sp&auml;ter evtl. grafische Benutzeroberfl&auml;che
	<br><br>
</div>
-->
<?php
// Anzahl Geb‰ude
$i = 0;
$v = 0;
$p = 0;
$f = 0;
$l = 0;
$t = 0;
$s = 0;
$tabelle = "<table class='table table-condensed'><thead><tr><th style='width: 150px;'>Art</th><th style='width: 100px;'>Gr&ouml;&szlig;e</th><th style='width: 200px;'>Standort</th><th style='width: 200px;'>Status</th></tr></thead><tbody>";
// Alle Geb‰udekategorien initialisieren
$verkaufsgebaeude = $tabelle;
$produktionsgebaeude = $tabelle;
$forschungsgebaeude = $tabelle;
$landwirtschaftsgebaeude = $tabelle;
$transportgebaeude = $tabelle;
$sonstigegebaeude = $tabelle;
// Schleifendurchlauf um Geb‰ude der richtigen Kategorie zuzuordnen
while($row = mysql_fetch_row($anzahlGebaeude)){
	// Pr¸fen, ob schon gebaut
	if (strtotime($row[2]) < strtotime(date('Y-m-d H:i:s'))){
		$status = "	<a href='/Verkauf'><button id='gebaeude$row[6]' title='Zu den Verkaufsgeb&auml;uden' class='btn' type='button'><i class='icon-share-alt'></i></button></a>
					<button id='verkauf$row[6]' onclick='verkaufen(this.id, $row[3])' title='Geb&auml;ude verkaufen' style='margin-left: 40px;' class='btn' type='button'><i class=' icon-briefcase'></i></button>
					<button id='abriss$row[6]' onclick='abreissen(this.id)' title='Geb&auml;ude abrei&szlig;en' class='btn' type='button'><i class='icon-remove-circle'></i></button>
					<img style='display: none;' id='wait$row[6]' src='/img/loading.gif'/>";
	}
	else{
		$fertigstellung = date('d.m.Y - H:i:s', mktime((int)substr($row[2],11,2), (int)substr($row[2],14,2), (int)substr($row[2],17,2), (int)substr($row[2],5,2), (int)substr($row[2],8,2), (int)substr($row[2],0,4)));
		$status = "<button disabled='disabled' title=\"Im Bau / Fertigstellung: " . $fertigstellung . " \" class='btn' type='button'><i class='icon-wrench'></i></button>";
	}
	// Aktuelle Geb‰udewerte
	$aktuellesGebaeude = "<tr id='$row[6]'><td>" . $row[1] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $status . "</td></tr>";
	// Fallunterscheidung anhand der Kategorie
	switch ($row[0]) {
		case 'v': $verkaufsgebaeude = $verkaufsgebaeude . $aktuellesGebaeude; $v++; break;
		case 'p': $produktionsgebaeude = $produktionsgebaeude . $aktuellesGebaeude; $p++; break;
		case 'f': $forschungsgebaeude = $forschungsgebaeude . $aktuellesGebaeude; $f++; break;
		case 'l': $landwirtschaftsgebaeude = $landwirtschaftsgebaeude . $aktuellesGebaeude; $l++; break;
		case 't': $transportgebaeude = $transportgebaeude . $aktuellesGebaeude; $t++; break;
		case 's': $sonstigegebaeude = $sonstigegebaeude . $aktuellesGebaeude; $s++; break;
	}
	// Z‰hlvariable erhˆhen
	$i++;
}
// Wenn Verkaufsgeb‰ude vorhanden sind
if ($v > 0){
	// Tabelle schlieﬂen
	$verkaufsgebaeude = $verkaufsgebaeude . "</tbody></table>";
}
else{
	// Hinweis anzeigen
	$verkaufsgebaeude = "Sie haben noch kein Geb&auml;ude dieser Kategorie gebaut!";
}
// Wenn Produktionsgeb‰ude vorhanden sind
if ($p > 0){
	// Tabelle schlieﬂen
	$produktionsgebaeude = $produktionsgebaeude . "</tbody></table>";
}
else{
	// Hinweis anzeigen
	$produktionsgebaeude = "Sie haben noch kein Geb&auml;ude dieser Kategorie gebaut!";
}
// Wenn Forschungsgeb‰ude vorhanden sind
if ($f > 0){
	// Tabelle schlieﬂen
	$forschungsgebaeude = $forschungsgebaeude . "</tbody></table>";
}
else{
	// Hinweis anzeigen
	$forschungsgebaeude = "Sie haben noch kein Geb&auml;ude dieser Kategorie gebaut!";
}
// Wenn Landwirtschaftsgeb‰ude vorhanden sind
if ($l > 0){
	// Tabelle schlieﬂen
	$landwirtschaftsgebaeude = $landwirtschaftsgebaeude . "</tbody></table>";
}
else{
	// Hinweis anzeigen
	$landwirtschaftsgebaeude = "Sie haben noch kein Geb&auml;ude dieser Kategorie gebaut!";
}
// Wenn Transportgeb‰ude vorhanden sind
if ($t > 0){
	// Tabelle schlieﬂen
	$transportgebaeude = $transportgebaeude . "</tbody></table>";
}
else{
	// Hinweis anzeigen
	$transportgebaeude = "Sie haben noch kein Geb&auml;ude dieser Kategorie gebaut!";
}
// Wenn Sonstige Geb‰ude vorhanden sind
if ($s > 0){
	// Tabelle schlieﬂen
	$sonstigegebaeude = $sonstigegebaeude . "</tbody></table>";
}
else{
	// Hinweis anzeigen
	$sonstigegebaeude = "Sie haben noch kein Geb&auml;ude dieser Kategorie gebaut!";
}
// Kurzer Hinweis
echo "<div align='center'><h2 id='anzahlGebaeude'>Deine Geb&auml;udeanzahl: $i</h2></div>";
// Alle Geb‰udedaten
?>
<br>
<!-- Hinweise -->
<div class="support">
	<div id="alertFehler" style="display: none;" class="alert alert-error"></div>
	<div id="alertErfolg" style="display: none;" class="alert alert-success"></div>
</div>
<br>
<!-- Aufklappbares Men¸ f¸r alle Geb‰ude -->
<div style='display: none;'>$text</div>
<br>
<div class="inhalt tabbable" style="margin-bottom: 18px;">
	<ul class="nav nav-tabs" style="border: none;">
		<li class="active"><a href="#tab1" data-toggle="tab">Verkauf</a></li>
		<!--
		<li class=""><a href="#tab2" data-toggle="tab">Produktion</a></li>
		<li class=""><a href="#tab3" data-toggle="tab">Forschung</a></li>
		<li class=""><a href="#tab4" data-toggle="tab">Landwirtschaft</a></li>
		<li class=""><a href="#tab5" data-toggle="tab">Transport</a></li>
		<li class=""><a href="#tab6" data-toggle="tab">Sonstige</a></li>
		-->
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			<p>
				<?php echo $verkaufsgebaeude;?>
			</p>
		</div>
		<!-- 
		<div class="tab-pane" id="tab2">
			<p>
				<?php //echo $produktionsgebaeude;?>
			</p>
		</div>
		<div class="tab-pane" id="tab3">
			<p>
				<?php //echo $forschungsgebaeude;?>
			</p>
		</div>
		<div class="tab-pane" id="tab4">
			<p>
				<?php //echo $landwirtschaftsgebaeude;?>
			</p>
		</div>
		<div class="tab-pane" id="tab5">
			<p>
				<?php //echo $transportgebaeude;?>
			</p>
		</div>
		<div class="tab-pane" id="tab6">
			<p>
				<?php //echo $sonstigegebaeude;?>
			</p>
		</div>
		-->
	</div>
</div>

<?php include '../footer.php';?>