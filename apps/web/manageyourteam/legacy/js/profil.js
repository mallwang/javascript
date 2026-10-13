// FUNKTION ZUM BEARBEITEN DES PROFILS
$(document).ready(function () {
	
	// Button "Profil bearbeiten" geklickt
	$("#editProfil").click(function() {
		// Alle Meldungen löschen
		$('#message').html("");
		// Alle Felder aktiv setzen
		$('#vornameEdit').removeAttr('disabled');
		$('#nachnameEdit').removeAttr('disabled');
		$('#emailEdit').removeAttr('disabled');
		// Vorname fokussieren
		$('#vornameEdit').focus();
		// Button disablen und Button "Speichern" anzeigen
		$('#editProfil').hide();
		$('#saveProfil').show();
	});

	// Button "Passwort ändern" geklickt
	$("#changePassword").click(function() {
		// Alle Meldungen löschen
		$('#message').html("");
		// Alle Felder anzeigen
		$('#formNewPW1').show();
		$('#formNewPW2').show();
		$('#formNewPW3').show();
		// Neues Passwort fokussieren
		$('#newPW1').focus();
		// Button disablen und Button "Speichern" anzeigen
		$('#changePassword').hide();
		$('#savePassword').show();
	});
	
	// Button "Profil löschen" geklickt
	$("#deleteProfil").click(function() {
		// Meldung anzeigen
		$('#message').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Achtung!</strong> Bitte füllen Sie alle Felder aus!</div></div>');
		$('#message').html('<div align="left" class="notice marker-on-top bg-darkRed fg-white" style="height: 230px;"><div><strong>Achtung!</strong><br>Wenn Sie Ihren Account löschen möchten, geben Sie bitte hier Ihr aktuelles Passwort ein und bestätigen diese Aktion durch die CheckBox. Im Anschluss wird Ihr Benutzerkonto zur Löschung vorgesehen und nach 3 Tagen gelöscht. Falls Sie sich in dieser Zeitspanne jedoch erneut einloggen, wird das Löschen widerrufen.</div><br><div class=span9" style="display: inline-flex; margin-left: 0px;"><div class="input-control text span3 fg-black" data-role="input-control"><input id="aktPW" type="password" placeholder="Aktuelles Passwort"></div><br><br><div class="span6"><label><input type="checkbox" onclick="btnAktivieren()"><span class="check"></span> Ja, mein Account soll zur Löschung vorgesehen werden!</label></div></div><br><br><br><div class="span6"><button id="deleteProfil2" onclick="deleteProfil()" type="button" disabled="disabled" class="button place-right fg-black bg-white">Profil löschen</button></div><img id="waitDeleteProfil" src="../../../../img/loading.gif" style="display: none;" /><div id="newMessage" style="margin-top: 50px;"></div></div>');
		// Passwort fokussieren
		$('#aktPW').focus();
	});
	
	// Button "Profil speichern" geklickt
	$("#saveProfil").click(function() {
		// Alle Meldungen löschen
		$('#message').html("");
		// Ladezeichen anzeigen und Button verbergen
		$('#saveProfil').hide();
		$('#waitSaveProfil').show();
		// Alle Datenfelder abfragen
		var vorname = $('#vornameEdit').val();
		var nachname = $('#nachnameEdit').val();
		var email = $('#emailEdit').val();
		// Wenn mind. eines befüllt ist
		if (vorname != "" || nachname != "" || email != ""){
			// Wenn Feld leer ist, alten Wert annehmen
			if(vorname == ""){
				vorname = $('#vornameEdit').attr('placeholder');
			}
			if(nachname == ""){
				nachname = $('#nachnameEdit').attr('placeholder');
			}
			if(email == ""){
				email = $('#emailEdit').attr('placeholder');
			}
			// Dateneintrag per Ajax
			$.ajax({
				type: "POST",
				url: '../functions/saveProfil.php',
				cache: false,
				data: {vorname: vorname, nachname: nachname, email: email},
				success: function(data) {
					// Ladezeichen verbergen
					$('#waitSaveProfil').hide();
					$('#saveProfil').show();
					if(data = "ok"){
						// Meldung anzeigen
						$('#message').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Ihre Daten wurden gespeichert!</div></div>');
						// Felder leeren
						$('#vornameEdit').val("");
						$('#nachnameEdit').val("");
						$('#emailEdit').val("");
						// Placeholder ändern
						$('#vornameEdit').attr('placeholder', vorname);
						$('#nachnameEdit').attr('placeholder', nachname);
						$('#emailEdit').attr('placeholder', email);
						// Felder disablen
						$('#vornameEdit').attr('disabled', 'disabled');
						$('#nachnameEdit').attr('disabled', 'disabled');
						$('#emailEdit').attr('disabled', 'disabled');
						// Button disablen und Button "Bearbeiten" anzeigen
						$('#saveProfil').hide();
						$('#editProfil').show();
					}
					else{
						// Meldung anzeigen
						$('#message').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Ihre Daten konnten nicht gespeichert werden!</div></div>');
					}
				}
			});
		}
		// Wenn sich nichts geändert hat
		else{
			// Ladezeichen verbergen
			$('#waitSaveProfil').hide();
			$('#saveProfil').show();
			// Meldung anzeigen
			$('#message').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Ihre Daten wurden gespeichert!</div></div>');
			// Felder disablen
			$('#vornameEdit').attr('disabled', 'disabled');
			$('#nachnameEdit').attr('disabled', 'disabled');
			$('#emailEdit').attr('disabled', 'disabled');
			// Button disablen und Button "Bearbeiten" anzeigen
			$('#saveProfil').hide();
			$('#editProfil').show();
		}
	});
	
	// Button "Passwort speichern" geklickt
	$("#savePassword").click(function() {
		// Alle Meldungen löschen
		$('#message').html("");
		// Ladezeichen anzeigen und Button verbergen
		$('#savePassword').hide();
		$('#waitSavePassword').show();
		// Alle Datenfelder abfragen
		var newPW1 = $('#newPW1').val();
		var newPW2 = $('#newPW2').val();
		var oldPW = $('#oldPW').val();
		// Wenn alle Felder befüllt ist
		if (newPW1 != "" && newPW2 != "" && oldPW != ""){
			// Wenn die neuen Passwörter übereinstimmen
			if (newPW1 == newPW2){
				// Ajax anfrage
				$.ajax({
					type: "POST",
					url: '../functions/savePassword.php',
					cache: false,
					data: {newPW: newPW1, oldPW: oldPW},
					success: function(data) {
						// Ladezeichen verbergen
						$('#waitSavePassword').hide();
						$('#savePassword').show();
						if(data == "ok"){
							// Meldung anzeigen
							$('#message').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Ihr Passwort wurde geändert! Eine Bestätigungsmail wurde an Ihre hinterlegte E-Mail Adresse versandt.</div></div>');
							// Felder leeren
							$('#newPW1').val("");
							$('#newPW2').val("");
							$('#oldPW').val("");
							// Placeholder ändern
							$('#newPW1').attr('placeholder', 'Neues Passwort');
							$('#newPW2').attr('placeholder', 'Passwort wiederholen');
							$('#oldPW').attr('placeholder', 'Aktuelles Passwort');
							// Alle Felder verbergen
							$('#formNewPW1').hide();
							$('#formNewPW2').hide();
							$('#formNewPW3').hide();
							// Button disablen und Button "Bearbeiten" anzeigen
							$('#savePassword').hide();
							$('#changePassword').show();
						}
						else if(data == "passwortFalsch"){
							// Meldung anzeigen
							$('#message').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Das angegebene Passwort ist nicht korrekt! Bitte überprüfen Sie Ihre Eingabe</div></div>');
							// Feld fokussieren
							$('#oldPW').select();
						}
						else{
							// Meldung anzeigen
							$('#message').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
						}
					}
				});
			}
			// Wenn die beiden Passwörter nicht übereinstimmen
			else{
				// Ladezeichen verbergen
				$('#waitSavePassword').hide();
				$('#savePassword').show();
				// Meldung anzeigen
				$('#message').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Beide Passwörter stimmen nicht überein!</div></div>');
				// Neues Passwort fokussieren
				$('#newPW1').select();
			}	
		}
		// Wenn kein Feld ausgefüllt ist
		else{
			// Ladezeichen verbergen
			$('#waitSavePassword').hide();
			$('#savePassword').show();
			// Meldung anzeigen
			$('#message').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder aus!</div></div>');
			// Neues Passwort fokussieren
			$('#newPW1').focus();
		}		
	});
	
});

// Profil zur Löschung eintragen
function deleteProfil(){
	// Alle Meldungen löschen
	$('#newMessage').html("");
	// Ladezeichen anzeigen und Button verbergen
	$('#deleteProfil2').hide();
	$('#waitDeleteProfil').show();
	// Passwort abfragen
	var passwort = $('#aktPW').val();
	// Wenn Passwort ausgefüllt
	if (passwort != ""){
		// Dateneintrag per Ajax
		$.ajax({
			type: "POST",
			url: '../functions/deleteProfil.php',
			cache: false,
			data: {passwort: passwort},
			success: function(data) {
				// Ladezeichen verbergen
				$('#deleteProfil2').show();
				$('#waitDeleteProfil').hide();
				if(data == "ok"){
					// Alert
                    alert(unescape("Ihr Account wurde erfolgreich zum L%F6schen vorgesehen%21%0AEine Best%E4tigung wurde an Ihre E-Mail Adresse versandt.%0A%0ASie werden nun ausgeloggt."));
                    // Ausloggen
                    document.location='/Logout';
				}
				else if (data == "passwortFalsch"){
						// Meldung anzeigen
						$('#newMessage').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Das angegebene Passwort ist nicht korrekt! Bitte überprüfen Sie Ihre Eingabe</div></div>');
						// Feld fokussieren
						$('#aktPW').select();
				}
				else{
					// Meldung anzeigen
					$('#newMessage').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
				}
			}
		});
	}
	// Wenn das Passwort nicht ausgefüllt ist
	else{
		// Ladezeichen verbergen
		$('#deleteProfil2').show();
		$('#waitDeleteProfil').hide();
		// Meldung anzeigen
		$('#newMessage').html('<div align="left" class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Bitte bestätigen Sie Ihre Löschung mit Ihrem aktuellen Passwort.</div></div>');
	}
}

// Button aktivieren
function btnAktivieren(){
	// "Account löschen"-Button aktivieren wenn disabled
	if (document.getElementById('deleteProfil2').getAttribute('disabled') == "disabled"){
		$('#deleteProfil2').removeAttr('disabled');
	}
	else{
		$('#deleteProfil2').attr('disabled', 'disabled');
	}
}