// Dialog erstellen
function showDialogCreateNewTeam(int_ClubID) {
	$.ajax({
	    url: "../inc/ajaxTest.php?class=db&function=getCoachByClub&id=" + int_ClubID,
	    success: function(json_coaches) {
	    	var arr_coaches = JSON.parse(json_coaches);
	    	var str_allCoaches = "";
	    	// Schleifendurchlauf
	    	for (var i = 0; i < arr_coaches.length; i++) {
				str_allCoaches = str_allCoaches + '<option value="' + arr_coaches[i]["coachID"] + '">' + arr_coaches[i]["coachFirstName"] + ' ' + arr_coaches[i]["coachSecondName"] + '</option>';
			}
	    	var str_filler = "";
	    	// Wenn kein Trainer gefunden wurde, wird ein Button "Neuen Trainer erstellen" angezeigt
	    	if(str_allCoaches == ""){
	    		str_filler = '<button style="width: 100%; height: 34px;" class="button bg-gray fg-white" id="coachID" type="button" onclick="$.Dialog.close(); showDialogCreateNewCoach()">Neuen Trainer erstellen</button><br><br>';
	    	}
	    	// Ansonsten anzeigen aller möglicher Trainer in einem Auswahlfeld
	    	else{
	    		str_filler = '<div class="input-control select"><select id="coachID">' + str_allCoaches + '</select></div>';
	    	}
	    	// Variablen für Dialog deklarieren
	    	var str_title = 'Neue Mannschaft erstellen';
	    	var str_content = 	'<form class="fg-black">' +
	        				'<label>Typ*</label>' +
	        				'<div class="input-control select"><select autofocus="autofocus" id="teamType"><option value="1">1. Mannschaft</option><option value="2">2. Mannschaft</option><option value="3">A-Junioren</option><option value="4">B-Junioren</option><option value="5">C-Junioren</option><option value="6">D-Junioren</option><option value="7">E-Junioren</option><option value="8">F-Junioren</option><option value="9">Hobby</option></select></div>' +
	        				'<label>Teamname</label>'+
	        				'<div class="input-control text"><input type="text" id="teamName" placeholder="Optionaler Teamname"></div>' +
	        				'<label>Trainer*</label>'+ str_filler +
	        				'<h6>* Pflichtfeld</h6>' + 
	        				'<div class="form-actions">' +
	        				'<button id="btnSubmit" type="button" class="button primary" onclick="createNewTeam()">Erstellen</button>&nbsp;'+
	        				'<button class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
	        				'<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />' +
	        				'<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>' + 
	        				'</div>'+
	        				'</form>';
	    	var int_width = 300;
	    	// Dialog erstellen
	    	createDialog(str_title, str_content, int_width);
	    	$('#teamType').focus();
	    }
	});
}

// Neue Mannschaft erstellen
function createNewTeam() {
	// Datenfelder zuweisen
	var teamTypeID = $('#teamType').val();
	var teamName = $('#teamName').val();
	var coachID = $('#coachID').val();
	// Wenn alle Felder ausgefüllt sind
	if (teamTypeID != ""){
		$("#alert").hide();
		$("#logWarten").show();
		$("#btnSubmit").hide();
		// Wenn ein Trainer ausgewählt wurde
		if (coachID != ""){
			// per Ajax Daten eintragen
			$.ajax({
				type: 'POST',
				url: 'functions/createTeam.php',
				data: {teamTypeID: teamTypeID, teamName: teamName, coachID: coachID},
				cache: false,
				success: function(data) {
					$("#logWarten").hide();
					$("#btnSubmit").show();
					if (data == "ok"){
						// Felder leeren
						$('#teamName').val("");
						$('#teamType').val(1);
						// teamType fokussieren
						$('#teamType').focus();
						// Meldung anzeigen
						$("#alert").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Die Mannschaft wurde erstellt.</div></div>");
						$("#alert").show();
					}
					else if(data == "coachSchonVorhanden"){
						// Meldung anzeigen
						$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Der Trainer trainiert bereits eine Mannschaft.</div></div>");
						$("#alert").show();
					}
					else{
						// Meldung anzeigen
						$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
						$("#alert").show();
					}
				}
			});
		}
		// Wenn kein Trainer ausgewählt wurde
		else{
			// Ladezeichen verbergen
			$("#logWarten").hide();
			$("#btnSubmit").show();
			// Meldung anzeigen
			$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst einen Trainer</div></div>');
			$("#alert").show();
		}
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder aus!</div></div>");
		$("#alert").show();
	}
}

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

// Mannschaft bearbeiten
function editTeam(int_TeamID) {
	createDialogEditTeam(int_TeamID);
}

// Dialog erstellen
function createDialogEditTeam(int_TeamID){
	// Per Ajax Trainerinfos holen
	$.ajax({
	    url: "../inc/ajaxTest.php?class=db&function=getTeamByID&id=" + int_TeamID,
	    success: function(json_team) {
	    	var arr_team = JSON.parse(json_team);
	    	var str_allCoaches = "";
	    	// Variablen zuweisen
	    	var teamID = arr_team['teamID'];
	    	var teamName = arr_team['teamName'];
	    	var teamTypeID = arr_team['teamTypeID'];
	    	// Dazugehörigen Trainer abfragen
	    	$.ajax({
	    	    url: "../inc/ajaxTest.php?class=db&function=getCoachByClub&id=0",
	    	    success: function(json_coaches) {
	    	    	var arr_coaches = JSON.parse(json_coaches);
	    	    	var str_allCoaches = "";
	    	    	// Schleifendurchlauf
	    	    	for (var i = 0; i < arr_coaches.length; i++) {
	    				str_allCoaches = str_allCoaches + '<option value="' + arr_coaches[i]["coachID"] + '">' + arr_coaches[i]["coachFirstName"] + ' ' + arr_coaches[i]["coachSecondName"] + '</option>';
	    			}
	    	    	var str_filler = "";
	    	    	// Wenn kein Trainer gefunden wurde, wird ein Button "Neuen Trainer erstellen" angezeigt
	    	    	if(str_allCoaches == ""){
	    	    		str_filler = '<button style="width: 100%; height: 34px;" class="button bg-gray fg-white" id="coachID" type="button" onclick="$.Dialog.close(); showDialogCreateNewCoach()">Neuen Trainer erstellen</button><br><br>';
	    	    	}
	    	    	// Ansonsten anzeigen aller möglicher Trainer in einem Auswahlfeld
	    	    	else{
	    	    		str_filler = '<div class="input-control select"><select id="coachID">' + str_allCoaches + '</select></div>';
	    	    	}
	    	    	// Trainer abfragen
	    	    	$.ajax({
	    	    	    url: "../inc/ajaxTest.php?class=db&function=getCoachByTeam&id="+teamID,
	    	    	    success: function(json_coach) {
	    	    	    	var arr_coach = JSON.parse(json_coach);
	    	    	    	if (teamName == -1){
					    		teamName = "Optionaler Teamname";
					    	}
	    	    	    	// Variablen zuweisen
	    	    	    	var coachID = arr_coach['coachID'];
					    	// Variablen für Dialog deklarieren
					    	var str_title = 'Mannschaft bearbeiten';
					    	var str_content = 	'<form class="fg-black">' +
					    					'<label>Typ*</label>' +
					    					'<div class="input-control select"><select autofocus="autofocus" id="teamType"><option value="1">1. Mannschaft</option><option value="2">2. Mannschaft</option><option value="3">A-Junioren</option><option value="4">B-Junioren</option><option value="5">C-Junioren</option><option value="6">D-Junioren</option><option value="7">E-Junioren</option><option value="8">F-Junioren</option><option value="9">Hobby</option></select></div>' +
					        				'<label>Teamname</label>'+
					        				'<div class="input-control text"><input type="text" id="teamName"></div>' +
					        				'<label>Trainer*</label>'+ str_filler +
					        				'<h6>* Pflichtfeld</h6>' + 
					        				'<div class="form-actions">' +
					        				'<button id="btnSubmit" type="button" class="button primary" onclick="saveTeamInfos(' + teamTypeID + ',&quot;' + teamName + '&quot;,' + coachID + ',' + int_TeamID + ')">Speichern</button>&nbsp;'+
					        				'<button class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
					        				'<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />' +
					        				'<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>' + 
					        				'</div>'+
					        				'</form>';
					    	var int_width = 300;
					    	// Dialog erstellen
					    	createDialog(str_title, str_content, int_width);
					    	// Fokus setzen
					    	$('#teamType').val(teamTypeID);
					    	$('#coachID').val(coachID);
					    	$('#teamName').attr("placeholder", ""+teamName);
					    	$('#teamType').focus();
	    	    	    }
	    	    	});
	    	    }
	    	});
	    }
	});
}

// Mannschaft speichern
function saveTeamInfos(teamTypeID_old, teamName_old, coachID_old, teamID) {
	$("#alert").hide();
	$("#logWarten").show();
	$("#btnSubmit").hide();
	// Datenfelder zuweisen
	var teamTypeID = $('#teamType').val();
	var teamName = $('#teamName').val();
	if ($('#teamName').attr("placeholder") != "Optionaler Teamname" && teamName == ""){
		teamName = $('#teamName').attr("placeholder");
	}
	var coachID = $('#coachID').val();
	// Wenn Trainer ausgewählt
	if (coachID != ""){
		// Wenn Datenfelder sich nicht geändert haben
		if(teamTypeID_old == teamTypeID && teamName_old == teamName && coachID_old == coachID){
			$("#logWarten").hide();
			$("#btnSubmit").show();
			// Meldung anzeigen
			$("#alert").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Die Änderungen wurden gespeichert!</div></div>");
			$("#alert").show();
		}
		else{
			// Dateneintrag per Ajax
			$.ajax({
				type: "POST",
				url: '../functions/saveTeam.php',
				cache: false,
				data: {teamID: teamID, teamTypeID: teamTypeID, teamName: teamName, coachID: coachID},
				success: function(data) {
					// Ladezeichen verbergen
					$('#logWarten').hide();
					$('#btnSubmit').show();
					if(data == "ok"){
						// Meldung anzeigen
						$('#alert').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Die Änderungen wurden gespeichert!</div></div>');
						$("#alert").show();
						// Felder leeren
						$('#teamName').val("");
						// Placeholder ändern
						if (teamName == ""){
							teamName = "Optionaler Teamname";
						}
						$('#teamName').attr('placeholder', teamName);
					}
					else if(data == "coachSchonVorhanden"){
						// Meldung anzeigen
						$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Der Trainer trainiert bereits eine Mannschaft.</div></div>");
						$("#alert").show();
					}
					else{
						// Meldung anzeigen
						$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
						$("#alert").show();
					}
				}
			});
		}
	}
	// Wenn kein Trainer ausgewählt wurde
	else{
		// Ladezeichen verbergen
		$("#wait").hide();
		$("#btnSubmit").show();
		// Meldung anzeigen
		$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst einen Trainer</div></div>');
		$("#alert").show();
	}
}

// Mannschaft löschen
function deleteTeam(int_TeamID) {
	createDialogDeleteTeam(int_TeamID);
}

// Dialog erstellen
function createDialogDeleteTeam(int_TeamID){
	// Variablen für Dialog deklarieren
	var str_title = 'Mannschaft löschen';
	var str_content = 	'<form class="fg-black">' +
					'<div id="alert" style="display: none; font-size: 14px;"></div>' + 
					'<div id="alertOld" style="font-size: 14px;" class="notice marker-on-top bg-darkRed fg-white"><div><strong>Achtung!</strong><br>Wenn Sie die komplette Mannschaft löschen, existieren die betroffenen Spieler sowie der Trainer weiterhin in Ihrem Verein.</div></div>' +
    				'<br>' +
    				'<div class="form-actions">' +
    				'<button id="btnSubmit" type="button" class="button primary" onclick="deleteTeamInfos(' + int_TeamID + ')">Löschen</button>&nbsp;'+
    				'<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
    				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
    				'</div>'+
    				'</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
}

// Mannschaft löschen
function deleteTeamInfos(int_TeamID){
	$('#alertOld').hide();
	// Ladezeichen anzeigen
	$("#wait").show();
	$("#btnSubmit").hide();
	// Per Ajax Spieler löschen
	$.ajax({
		type: "POST",
		url: '../functions/deleteTeam.php',
		cache: false,
		data: {teamID: int_TeamID},
		success: function(data) {
			// Ladezeichen verbergen
			$("#wait").hide();
			$("#btnSubmit").show();
			if(data == "ok"){
				// Meldung anzeigen
				$('#alert').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Die Mannschaft wurde gelöscht!</div></div>');
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