// FUNKTION ZUM BEARBEITEN DER VEREINSDATEN
$(document).ready(function () {

	// Button "VereinProfil bearbeiten" geklickt
	$("#editVereinProfil").click(function() {
		// Alle Meldungen löschen
		$('#message').html("");
		// Alle Felder aktiv setzen
		$('#VereinKurzbeschreibung').removeAttr('disabled');
		$('#VereinLangbeschreibung').removeAttr('disabled');
		$('#VereinHistorie').removeAttr('disabled');
		// Vorname fokussieren
		$('#VereinKurzbeschreibung').focus();
		// Button disablen und Button "Speichern" anzeigen
		$('#editVereinProfil').hide();
		$('#saveVereinProfil').show();
	});

	// Button "VereinProfil speichern" geklickt
	$("#saveVereinProfil").click(function() {
		// Wartezeichen anzeigen
		$('#waitSaveVereinProfil').show();
		// Alle Meldungen löschen
		$('#message').html("");
		// Alle Datenfelder abfragen
		var str_kurzBeschr = $('#VereinKurzbeschreibung').val();
		var str_langBeschr = $('#VereinLangbeschreibung').val();
		var str_Historie = $('#VereinHistorie').val();
		// Dateneintrag per Ajax
		$.ajax({
			type: "POST",
			url: '../functions/saveVereinsprofil.php',
			cache: false,
			data: {vereinKurzbeschreibung: str_kurzBeschr, vereinLangbeschreibung: str_langBeschr, vereinHistorie: str_Historie},
			success: function(data) {
				// Wartezeichen verbergen
				$('#waitSaveVereinProfil').hide();
				if(data == "ok"){
					// Meldung anzeigen
					$('#message').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Die Vereinsdaten wurden gespeichert!</div></div>');
					// Felder disablen
					$('#VereinKurzbeschreibung').attr('disabled', 'disabled');
					$('#VereinLangbeschreibung').attr('disabled', 'disabled');
					$('#VereinHistorie').attr('disabled', 'disabled');
					// Button disablen und Button "Bearbeiten" anzeigen
					$('#saveVereinProfil').hide();
					$('#editVereinProfil').show();
				}
				else{
					// Wartezeichen verbergen
					$('#waitSaveVereinProfil').hide();
					// Meldung anzeigen
					$('#message').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Die Vereinsdaten konnten nicht gespeichert werden!</div></div>');
				}
			}
		});
	});
});