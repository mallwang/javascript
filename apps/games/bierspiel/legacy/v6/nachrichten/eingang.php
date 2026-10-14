<?php $title="Posteingang"; include '../sessionpruefung.php'; include '../connect.php'; include '../geheim.php';?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-success"><b>Diese Seite ist komplett &uuml;berarbeitet. <a style="font-size: 11px;" href="#">Fehler melden</a></b></div>
<?php
// Aktueller User
$aktuellerUser = $_SESSION["user"];
// Aktuelle User Id
$userId = $_SESSION["userId"];
// Alle nachrichten abfragen
$alle_nachrichten_abfrage = mysql_query("SELECT id, DATE_FORMAT(versendet,'%d.%m.%Y - %H:%i') AS versendet, betreff, text, von_id, von_user FROM pns WHERE zu_id = '$userId' AND empfaenger_geloescht = 0");
// Anzahl an Nachrichten für aktuellen User zählen
$anzahl_nachrichten = mysql_num_rows($alle_nachrichten_abfrage);
// Wenn aktueller User keine Nachrichten im Posteingang hat
if ($anzahl_nachrichten == 0){
    // Mitteilung an aktuellen User
    echo"<br><br><div align='center'><h3>In Ihrem Posteingang befinden sich keine Nachrichten.</h3></div>"; 
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
	echo"<br><br><div id='anzNachrichten' align='center'><h3>In Ihrem Posteingang $befindet sich $anzahl_nachrichten $gesamtnachrichteninfo.</h3></div>";
	?>
	<!-- Formular -->
	<br><br>
	<div class="support">
		<form id="nachrichtNeu" style="display: none;" class="form-horizontal">
			<div class="control-group">
		      	<input maxlength="50" class="span3" type="text" id="nachrichtBetreff" onkeyup="pruefeStatus()" placeholder="Betreff">
		      	<input style="margin-left: 25px;" maxlength="50" class="span3" type="text" id="nachrichtEmpfaenger" onkeyup="pruefeStatus()" placeholder="Firmenname des Empf&auml;ngers">
		  	</div>
		  	<div class="control-group">
		    	<textarea onkeyup="pruefeEingabe()" maxlength="1000" class="span6" id="nachrichtText" rows="8" placeholder="Ihre Nachricht"></textarea>
		  	</div>
			<div id="alertFehler" style="display: none;" class="alert alert-error"></div>
			<div id="alertErfolg" style="display: none;" class="alert alert-success"></div>
			<div align="center" style="font-size: 12px;">
				<div style="display: inline-block;" id="nachrichtZeichen">1000</div><div style="display: inline-block;">&nbsp; Zeichen verbleiben</div>
			</div>
			<br>
			<div class="control-group">
				<label class="checkbox">
					<input id="checkbox" type="checkbox"> Nachricht in Postausgang kopieren!
				</label>
			</div>
		  	<div class="control-group">
		   		<div class="controls">
		   			<button onclick="sende()" disabled="disabled" style="width: 200px;" id="nachrichtBtn" type="button" class="btn btn-info">Absenden</button>
		      		<img style="display: none;" id="wartenNachricht" src="/img/loading.gif"/>
		      	</div>
		  	</div>
		</form>
	</div>
	<br>
	<?php 
	// Tabellenbeginn
	echo "	<div class='nachrichten'>
			<div id='alertFehler2' style='display: none;' class='alert alert-error'></div>
			<div id='alertErfolg2' style='display: none;' class='alert alert-success'></div>
			<table class='table' id='tabelle'>";
	// Tabellenkopf
	echo "	<thead>
				<tr>
					<th style='width: 20px;'>#</th>
					<th style='width: 500px;'>Betreff</th>
					<th style='width: 200px;'>Von</th>
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
		$von_user = $row->von_user;
		$versendet = $row->versendet;
		$text = html_entity_decode($row->text);
		// Ausgeben
		echo "<tr id='nachricht$id'>
				<td>$anzahl</td>
				<td><ausklapp><a class='pointer'>$betreff</a></ausklapp><div style='display: none;'><br>$text</div></td>
				<td>$von_user</td>
				<td>$versendet</td>
				<td>
					<div class='input-append'>
						<button onclick='antworten(\"$betreff\", \"$von_user\")' id='nachricht$anzahl' title='Antworten' class='btn' type='button'><i class='icon-envelope'></i></button>
						<button disabled='disabled' id='vertrag$anzahl' title='Vertragsangebot machen' class='btn' type='button'><i class='icon-list-alt'></i></button>
						<button onclick='loeschen(\"$id\")' id='loeschen' title='L&ouml;schen' class='btn' type='button'><i class='icon-trash'></i></button>
						<img style='display: none;' id='warten$id' src='/img/loading.gif'/>
					</div>
				</td>
			</tr>";
	}
	// Tabellenschluss
	echo "</tbody></table>";
}?>
<script type="text/javascript">
//Globale Variablen
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
	// Antworten
	// Bei klick auf Antworten fährt sich ein Nachricht formular aus (wie bei neuer nachricht)
	function antworten(betreff, empfaenger){
		// Betreff
		var betreffNeu = "Re: " + betreff;
		$('#nachrichtBetreff').attr('value', betreffNeu);
		$('#nachrichtEmpfaenger').attr('value', empfaenger);
		// Formular erscheint
		$('#nachrichtNeu').slideDown();
		// Fokus auf Inhalt setzen
		document.getElementById('nachrichtText').focus();
	}

	// Nachricht löschen
	function loeschen(id){
		// Meldung erscheinen
		var loeschen = confirm(unescape("M%F6chtest du die ausgew%E4hlte Nachricht wirklich l%F6schen%3F"));
		// Meldungen verstecken
		$("#alertErfolg2").hide();
		$("#alertFehler2").hide();
		// Wenn ok gedrückt
		if (loeschen){
			// Wartezeichen anzeigen
			var wartezeichen = "warten" + id;
			document.getElementById(wartezeichen).removeAttribute("style");
			var status = "empfaenger";
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
									$("#anzNachrichten").html("<h3>In Ihrem Posteingang befinden sich " + anzahlNachrichten + " Nachrichten.</h3>");
								}
								else if (anzahlNachrichten == 1){
									$("#anzNachrichten").html("<h3>In Ihrem Posteingang befindet sich " + anzahlNachrichten + " Nachricht.</h3>");
								}
								else if (anzahlNachrichten == 0){
									// ganze Tabelle ausblenden
									$("#tabelle").hide();
									$("#anzNachrichten").html("<h3>In Ihrem Posteingang befinden sich keine Nachrichten.</h3>");
								}
								//$("#anzahlMailsAusgang").html("" + anzahlNachrichten);
								// Erfolgsmeldung
								$("#alertErfolg2").html("<b>Erfolg!</b> Die Nachricht wurde gel&ouml;scht");
		               	 		$("#alertErfolg2").show();
							}
							else{
								// Fehlermeldung
		               	 		$("#alertFehler2").html("<b>Fehler!</b> Es ist folgender Fehler aufgetreten<br><br>" + data);
		               	 		$("#alertFehler2").show();
							}
						}
			});
			return false;
		}
	}
	
	function pruefeEingabe(){
		// Anzahl der Zeichen auslesen
		var anzahlZeichen = $('#nachrichtText').val().length;
		// Wenn Anzahl > 1000: letztes Zeichen wird gelöscht
		if (anzahlZeichen > 1000){
			// Text auslesen
			var input = $('#nachrichtText').val();
			// Kürzen
			var input = input.substring(0, 1000);
			// Nachricht in feld schreiben
			$('#nachrichtText').val() = input;
		}
		// Verbleibende Zeichen
		var verbleibendeZeichen = 1000 - anzahlZeichen;
		// Anzeige aktualisieren
		$('#nachrichtZeichen').html(verbleibendeZeichen);
		pruefeStatus();
	}
	function pruefeStatus(){
		// Meldungen ausblenden
		$("#alertFehler").hide();
		$("#alertErfolg").hide();
		// Hintergrund EmpfängerName zurücksetzen
		$("#nachrichtEmpfaenger").css("border-color","rgb(204,204,204)");
		$("#nachrichtEmpfaenger").css("background-color","white");
		// Prüfen, ob alle Felder ausgefüllt sind
		var betreff = $('#nachrichtBetreff').val();
		var empfaenger = $('#nachrichtEmpfaenger').val();
		var text = $('#nachrichtText').val();
		if (betreff.length > 0 && empfaenger.length > 0 && text.length > 0){
			// Button wird aktiv
			$('#nachrichtBtn').removeAttr('disabled');
		}
		// Wenn nicht alle Felder ausgefüllt sind
		else{
			// Button wird deaktiv
			$('#nachrichtBtn').attr('disabled', 'disabled');
		}
	}
	function sende(){
		// Meldungen ausblenden
		$("#alertFehler").hide();
		$("#alertErfolg").hide();
		// Alle Werte initalisieren
		var betreff = $('#nachrichtBetreff').val();
		var empfaenger = $('#nachrichtEmpfaenger').val();
		var text = $('#nachrichtText').val();
		// Wenn Checkbox geklickt
		if(document.getElementById("checkbox").checked) {
			var checked = 0;
		}
		else{
			var checked = 1;
		}
		// Prüfen ob alle Ausgefüllt
		if (betreff.length > 0 && empfaenger.length > 0 && text.length > 0){
			// Wenn Empfänger ungleich Absender
			if (empfaenger.toLowerCase() != "<?php echo strtolower($aktuellerUser);?>"){
				// Wartezeichen zeigen
				$('#wartenNachricht').show();
				// Ajax-Aufruf
				$.ajax({
					type: "POST",
					url: "/nachrichten/nachrichtSenden.php",
					cache: false,
					data: { betreff: betreff, empfaenger: empfaenger, text: text, checked: checked},
							success: function(data){
								// Wartezeichen verstecken
								$('#wartenNachricht').hide();
								if(data=='ok'){
									// Button deaktivieren
									$('#nachrichtBtn').attr('disabled', 'disabled');
									// Erfolgsmitteilung
									$("#alertErfolg").html("<strong>Erfolg!</strong> Die Nachricht wurde versandt!");
									$("#alertErfolg").show();
								}
								else if(data == 'postausgangVoll'){
									$("#alertFehler").html("<strong>Fehler!</strong> Ihr Postausgang ist voll!<br>Bitte l&ouml;schen Sie vorher mindestens eine Nachricht.");
									$("#alertFehler").show();
									// Button deaktivieren
									$('#nachrichtBtn').attr('disabled', 'disabled');
								}
								else if(data == 'posteingangVoll'){
									$("#alertFehler").html("<strong>Fehler!</strong> Der Posteingang des Empf&auml;ngers ist voll!");
									$("#alertFehler").show();
									// Button deaktivieren
									$('#nachrichtBtn').attr('disabled', 'disabled');
								}
								else if(data == 'empfaengerNichtExistent'){
									// Empfängerfeld markieren
									$("#nachrichtEmpfaenger").css("border-color","red");
									$("#nachrichtEmpfaenger").css("background-color","salmon");
									// Nachricht
									$("#alertFehler").html("<strong>Fehler!</strong> Die angegebene Empf&auml;ngerfirma existiert leider nicht!");
									$("#alertFehler").show();
									// Button deaktivieren
									$('#nachrichtBtn').attr('disabled', 'disabled');
									// Empfängerfeld selektieren
									document.getElementById("nachrichtEmpfaenger").select();
								}
								else if(data == 'fehlerDateneintrag'){
			               	 		$("#alertFehler").html("<strong>Fehler!</strong> Die Nachricht konnte nicht versandt werden<br>" +
					               	 		"Bitte versuchen Sie es nochmal und/oder wenden Sie sich an den Support");
			               	 		$("#alertFehler").show();
								}
								else{
			               	 		$("#alertFehler").html("<strong>Fehler!</strong> Es ist folgender Fehler aufgetreten<br><br>" + data);
			               	 		$("#alertFehler").show();
								}
							}
				});
				return false;
			}
			// Wenn Empfänger gleich Absender
			else{
				// Hinweis erscheint
				$('#alertFehler').html("<strong>Fehler!</strong> Du kannst an dich selbst keine Nachricht senden!<br>" +
						"Bitte benutze hierf&uuml;r die Notizfunktion.");
				$('#alertFehler').show();
				// Button deaktivieren
				$('#nachrichtBtn').attr('disabled', 'disabled');
				// Empfängerfeld rot hinterlegen
				$("#nachrichtEmpfaenger").css("border-color","red");
				$("#nachrichtEmpfaenger").css("background-color","salmon");
				// Empfängerfeld selektieren
				document.getElementById("nachrichtEmpfaenger").select();
			}
		}
		// Wenn nicht alle ausgefüllt
		else{
			// Hinweis erscheint
			$('#alertFehler').html("<strong>Fehler!</strong> Bitte alle Felder ausf&uuml;llen!");
			$('#alertFehler').show();
		}
	}
</script>
<?php include '../footer.php';?>