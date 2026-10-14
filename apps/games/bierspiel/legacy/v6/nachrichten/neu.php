<?php $title="Neue Nachricht"; include '../sessionpruefung.php'; include '../geheim.php';
$aktuellerUser = $_SESSION['user'];?>
<!-- Hinweis -->
<div align="center" id="alertHinweis" class="alert alert-success"><b>Diese Seite ist komplett &uuml;berarbeitet. <a style="font-size: 11px;" href="#">Fehler melden</a></b></div>
<script type="text/javascript">
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
		// Alle Werte initalisieren
		var betreff = $('#nachrichtBetreff').val();
		var empfaenger = $('#nachrichtEmpfaenger').val().toLowerCase();
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
			if (empfaenger != "<?php echo strtolower($aktuellerUser);?>"){
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
									$("#alertFehler").html("<strong>Fehler!</strong> Der angegebene User existiert leider nicht!");
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
<!-- Formular -->
<br><br>
<div class="support">
	<form id="nachrichtNeu" class="form-horizontal">
		<div class="control-group">
	      	<input maxlength="50" class="span3" type="text" id="nachrichtBetreff" onkeyup="pruefeStatus()" placeholder="Betreff">
	      	<input style="margin-left: 25px;" maxlength="50" class="span3" type="text" id="nachrichtEmpfaenger" onkeyup="pruefeStatus()" placeholder="Nickname des Empf&auml;ngers">
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
<script type="text/javascript">
	document.getElementById('betreff').focus();
</script>
<?php include '../footer.php';?>