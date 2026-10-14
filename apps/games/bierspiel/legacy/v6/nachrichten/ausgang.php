<?php $title="Postausgang"; include '../sessionpruefung.php'; include '../connect.php'; include '../geheim.php';?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-success"><b>Diese Seite ist komplett &uuml;berarbeitet. <a style="font-size: 11px;" href="#">Fehler melden</a></b></div>
<?php
// Aktuelle User Id
$userId = $_SESSION["userId"];
// Alle nachrichten abfragen
$alle_nachrichten_abfrage = mysql_query("SELECT id, DATE_FORMAT(versendet,'%d.%m.%Y - %H:%i') AS versendet, betreff, text, zu_id, zu_user FROM pns WHERE von_id = '$userId' AND sender_geloescht = 0");
// Anzahl an Nachrichten für aktuellen User zählen
$anzahl_nachrichten = mysql_num_rows($alle_nachrichten_abfrage);
// Wenn aktueller User keine Nachrichten im Posteingang hat
if ($anzahl_nachrichten == 0){
    // Mitteilung an aktuellen User
    echo"<br><br><div align='center'><h3>In Ihrem Postausgang befinden sich keine Nachrichten.</h3></div>"; 
}
// Wenn aktueller User Nachrichten im Posteingang hat
else{
	// Einzahl oder Mehrzahl für die Ausgabe festlegen
	if($anzahl_nachrichten == 1){
		$befindet = "befindet";
		$gesamtnachrichteninfo = "Nachricht";
	}
	elseif($anzahl_nachrichten > 1){
		$befindet = "befinden";
		$gesamtnachrichteninfo = "Nachrichten";
	}
	// Mitteilung an aktuellen User
	echo"<br><br><div id='anzNachrichten' align='center'><h3>In Ihrem Postausgang $befindet sich $anzahl_nachrichten $gesamtnachrichteninfo.</h3></div>";
	// Tabellenbeginn
	echo "	<br><br><br><div class='nachrichten'>
			<div id='alertFehler' style='display: none;' class='alert alert-error'></div>
			<div id='alertErfolg' style='display: none;' class='alert alert-success'></div>
			<table class='table' id='tabelle'>";
	// Tabellenkopf
	echo "	<thead>
				<tr>
					<th style='width: 20px;'>#</th>
					<th style='width: 500px;'>Betreff</th>
					<th style='width: 200px;'>An</th>
					<th style='width: 200px;'>Datum</th>
					<th style='width: 80px;'>Aktion</th>
				</tr>
			</thead></div>";
	// Tabellenbody
	echo "<tbody>";
	// Zählvariable
	$anzahl = 0;
	// Schleifendurchlauf für jede Nachricht
	while($row = mysql_fetch_object($alle_nachrichten_abfrage)){
		$anzahl++;
		$id = $row->id;
		$betreff = $row->betreff;
		$zu_user = $row->zu_user;
		$versendet = $row->versendet;
		$text = $row->text;
		// Ausgeben
		echo "<tr id='nachricht$id'>
				<td>$anzahl</td>
				<td><ausklapp><a class='pointer'>$betreff</a></ausklapp><div style='display: none;'><br>$text</div></td>
				<td>$zu_user</td>
				<td>$versendet</td>
				<td>
					<div class='input-append'>
						<button disabled='disabled' id='vertrag$anzahl' title='Vertragsangebot machen' class='btn' type='button'><i class='icon-list-alt'></i></button>
						<button onclick='loeschen(\"$id\")' id='loeschen$anzahl' title='L&ouml;schen' class='btn' type='button'><i class='icon-trash'></i></button>
						<img style='display: none;' id='warten$id' src='/img/loading.gif'/>
					</div>
				</td></tr>";
	}
	// Tabellenschluss
	echo "</tbody></table>";
}
?>
<script type="text/javascript">
// Globale Variablen
var anzahlNachrichten = parseInt("<?php echo $anzahl_nachrichten;?>");

$(document).ready(function() {
	// Ausklapp funktion
	$('ausklapp').click(function(){
		if($(this).next("div").is(':visible')!=true){
            event.preventDefault();
            $(this).next("div").slideDown(300);
       	}
       	else{
            event.preventDefault();
            $(this).next("div").slideUp(300);
       	} 
	});
});

//Nachricht löschen
function loeschen(id){
	// Meldung erscheinen
	var loeschen = confirm(unescape("M%F6chtest du die ausgew%E4hlte Nachricht wirklich l%F6schen%3F"));
	// Meldungen verstecken
	$("#alertErfolg").hide();
	$("#alertFehler").hide();
	// Wenn ok gedrückt
	if (loeschen){
		// Wartezeichen anzeigen
		var wartezeichen = "warten" + id;
		document.getElementById(wartezeichen).removeAttribute("style");
		var status = "sender";
		// Ajax-Aufruf
		$.ajax({
			type: "POST",
			url: "/nachrichten/nachrichtLoeschen.php",
			cache: false,
			data: { id: id, status: status},
					success: function(data){
						// Wartezeichen verstecken
						document.getElementById(wartezeichen).setAttribute("style", "display: none;");
						if(data=='ok'){
							// Zeile löschen
							var nachrichtId = "nachricht" + id;
							document.getElementById(nachrichtId).setAttribute("style", "display: none;");
							// Neue Nachrichtenanzahl berechnen
							anzahlNachrichten--;
							// Nachrichtenanzeigen updaten
							if (anzahlNachrichten > 1){
								$("#anzNachrichten").html("<h3>In Ihrem Postausgang befinden sich " + anzahlNachrichten + " Nachrichten.</h3>");
							}
							else if (anzahlNachrichten == 1){
								$("#anzNachrichten").html("<h3>In Ihrem Postausgang befindet sich " + anzahlNachrichten + " Nachricht.</h3>");
							}
							else if (anzahlNachrichten == 0){
								// ganze Tabelle ausblenden
								$("#tabelle").hide();
								$("#anzNachrichten").html("<h3>In Ihrem Postausgang befinden sich keine Nachrichten.</h3>");
							}
							//$("#anzahlMailsAusgang").html("" + anzahlNachrichten);
							// Erfolgsmeldung
							$("#alertErfolg").html("<b>Erfolg!</b> Die Nachricht wurde gel&ouml;scht");
	               	 		$("#alertErfolg").show();
						}
						else{
							// Fehlermeldung
	               	 		$("#alertFehler").html("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
	               	 		$("#alertFehler").show();
						}
					}
		});
		return false;
	}
}
</script>
<?php include '../footer.php';?>