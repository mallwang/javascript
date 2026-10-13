// Dialog erstellen
function showDialogCreateNewCoach() {
	// Variablen für Dialog deklarieren
	var str_title = 'Neuen Trainer erstellen';
	var str_content = '<form class="fg-black">'
			+ '<label>Vorname*</label>'
			+ '<div class="input-control text"><input type="text" id="vorname" placeholder="Vorname" required="required" autofocus="autofocus"></div>'
			+ '<label>Nachname*</label>'
			+ '<div class="input-control text"><input type="text" id="nachname" placeholder="Nachname" required="required"></div>'
			+ '<label>E-Mail Adresse*</label>'
			+ '<div class="input-control text"><input type="email" id="email" placeholder="E-Mail Adresse" required="required"></div>'
			+ '<h6>* Pflichtfeld</h6>'
			+ '<button id="btnSubmit" type="button" class="button primary" onclick="createNewCoach()">Erstellen</button>&nbsp;'
			+ '<button class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
			+ '<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />'
			+ '<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
			+ '</div>' + '</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
	$('#vorname').focus();
}

// Neuen Trainer erstellen
function createNewCoach() {
	// Datenfelder zuweisen
	var vorname = $('#vorname').val();
	var nachname = $('#nachname').val();
	var email = $('#email').val();
	// Wenn alle Felder ausgefüllt sind
	if (vorname != "" && nachname != "" && email != ""){
		$("#alert").hide();
		$("#logWarten").show();
		$("#btnSubmit").hide();
		// per Ajax Daten eintragen
		$.ajax({
			type: 'POST',
			url: 'functions/createCoach.php',
			data: {vorname: vorname, nachname: nachname, email: email},
			cache: false,
			success: function(data) {
				$("#logWarten").hide();
				$("#btnSubmit").show();
				if (data == "ok"){
					// Felder leeren
					$('#vorname').val("");
					$('#nachname').val("");
					$('#email').val("");
					// Vorname fokussieren
					$('#vorname').focus();
					// Meldung anzeigen
					$("#alert").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Der Trainer wurde erstellt.</div></div>");
					$("#alert").show();
				}
				else if (data == "emailSchonVorhanden"){
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Eine identische E-Mail Adresse existiert bereits.</div></div>");
					$("#alert").show();
				}
				else{
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
					$("#alert").show();
				}
			}
		});
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder aus!</div></div>");
		$("#alert").show();
	}
}

// Trainer bearbeiten
function editCoach(int_CoachID) {
	createDialogEditCoach(int_CoachID);
}

// Dialog erstellen
function createDialogEditCoach(int_CoachID){
	// Per Ajax Trainerinfos holen
	$.ajax({
	    url: "../inc/ajaxTest.php?class=db&function=getCoachByID&id=" + int_CoachID,
	    success: function(json_coach) {
	    	var arr_coach = JSON.parse(json_coach);
	    	// Variablen zuweisen
	    	var coachFirstName = arr_coach['coachFirstName'];
	    	var coachSecondName = arr_coach['coachSecondName'];
	    	// Variablen für Dialog deklarieren
	    	var str_title = 'Trainer bearbeiten';
	    	var str_content = 	'<form class="fg-black">' +
	    					'<label>Vorname</label>' +
	    					'<div class="input-control text"><input type="text" id="coachFirstName" placeholder="' + coachFirstName + '"></div>' +
	        				'<label>Nachname</label>'+
	        				'<div class="input-control text"><input type="text" id="coachSecondName" placeholder="' + coachSecondName + '"></div>' +
	        				'<div class="form-actions">' +
	        				'<button id="btnSubmit" type="button" class="button primary" onclick="saveCoachInfos(&quot;' + coachFirstName + '&quot;,&quot;' + coachSecondName + '&quot;,' + int_CoachID + ')">Speichern</button>&nbsp;'+
	        				'<button class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
	        				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
	        				'<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>' + 
	        				'</div>'+
	        				'</form>';
	    	var int_width = 300;
	    	// Dialog erstellen
	    	createDialog(str_title, str_content, int_width);
	    	// Fokus setzen
	    	$('#coachFirstName').focus();
	    }
	});
}

// Trainer speichern
function saveCoachInfos(firstName, secondName, coachID) {
	$("#alert").hide();
	$("#wait").show();
	$("#btnSubmit").hide();
	// Datenfelder zuweisen
	var coachFirstName = $('#coachFirstName').val();
	if (coachFirstName == "" && $('#coachFirstName').attr('placeholder') == firstName){
		coachFirstName = $('#coachFirstName').attr('placeholder');
	}
	var coachSecondName = $('#coachSecondName').val();
	if (coachSecondName == "" && $('#coachSecondName').attr('placeholder') == secondName){
		coachSecondName = $('#coachSecondName').attr('placeholder');
	}
	// Wenn sich nichts geändert hat
	if (coachFirstName == firstName && coachSecondName == secondName){
		$("#wait").hide();
		$("#btnSubmit").show();
		// Meldung anzeigen
		$("#alert").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Die Daten wurden gespeichert!</div></div>");
		$("#alert").show();
		// Fokus setzen
    	$('#coachFirstName').focus();
	}
	// Wenn sich was geändert hat
	else{
		// Dateneintrag per Ajax
		$.ajax({
			type: "POST",
			url: '../functions/saveCoach.php',
			cache: false,
			data: {coachFirstName: coachFirstName, coachSecondName: coachSecondName, coachID: coachID},
			success: function(data) {
				// Ladezeichen verbergen
				$('#logWarten').hide();
				$('#btnSubmit').show();
				if(data == "ok"){
					// Meldung anzeigen
					$('#alert').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Die Änderungen wurden gespeichert!</div></div>');
					$("#alert").show();
					// Felder leeren
					$('#coachFirstName').val("");
					$('#coachSecondName').val("");
					// Platzhalter ändern
					$('#coachFirstName').attr('placeholder', coachFirstName);
					$('#coachSecondName').attr('placeholder', coachSecondName);
				}
				else{
					// Meldung anzeigen
					$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
					$("#alert").show();
				}
			}
		});
		$("#wait").hide();
		$("#btnSubmit").show();
	}
}

// Trainer löschen
function deleteCoach(int_CoachID) {
	createDialogDeleteCoach(int_CoachID);
}

// Dialog erstellen
function createDialogDeleteCoach(int_CoachID){
	// Variablen für Dialog deklarieren
	var str_title = 'Spieler löschen';
	var str_content = 	'<form class="fg-black">' +
					'<div id="alert" style="display: none; font-size: 14px;"></div>' + 
					'<div id="alertOld" style="font-size: 14px;" class="notice marker-on-top bg-darkRed fg-white"><div><strong>Achtung!</strong><br>Wenn Sie den Trainer löschen, wird das betreffende Benutzerprofil umgehend gelöscht.</div></div>' +
    				'<br>' +
    				'<div class="form-actions">' +
    				'<button id="btnSubmit" type="button" class="button primary" onclick="deleteCoachInfos(' + int_CoachID + ')">Löschen</button>&nbsp;'+
    				'<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
    				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
    				'</div>'+
    				'</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
}

// Trainer löschen
function deleteCoachInfos(int_CoachID){
	$('#alertOld').hide();
	// Ladezeichen anzeigen
	$("#wait").show();
	$("#btnSubmit").hide();
	// Per Ajax Spieler löschen
	$.ajax({
		type: "POST",
		url: '../functions/deleteCoach.php',
		cache: false,
		data: {coachID: int_CoachID},
		success: function(data) {
			// Ladezeichen verbergen
			$("#wait").hide();
			$("#btnSubmit").show();
			if(data == "ok"){
				// Meldung anzeigen
				$('#alert').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Der Trainer wurde gelöscht!</div></div>');
				$("#alert").show();
				// Button verbergen
				$("#btnSubmit").hide();
				$("#btnAbort").attr("class", "button place-right success");
				$("#btnAbort").html("Fertig");
			}
			else{
				// Meldung anzeigen
				$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
				$("#alert").show();
			}
		}
	});
}

// Dialog erstellen
function createDialog(str_title, str_content, int_width) {
	// Dialog erstellen
	$.Dialog({
		shadow : true,
		overlay : false,
		draggable : true,
		title : str_title,
		width : int_width,
		padding : 20,
		onShow : function() {
			$.Dialog.content(str_content);
		}
	});
}