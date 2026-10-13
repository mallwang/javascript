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
	    		str_filler = '<button style="width: 100%; height: 34px;" class="button bg-gray fg-white" id="coachID1" type="button" onclick="$.Dialog.close(); showDialogCreateNewCoach()">Neuen Trainer erstellen</button><br><br>';
	    	}
	    	// Ansonsten anzeigen aller möglicher Trainer in einem Auswahlfeld
	    	else{
	    		str_filler = '<div class="input-control select"><select id="coachID">' + str_allCoaches + '</select></div>';
	    	}
	    	// Variablen für Dialog deklarieren
	    	var str_title = 'Neue Mannschaft erstellen';
	    	var str_content = 	'<form class="fg-black">' +
	        				'<label>Typ*</label>' +
	        				'<div class="input-control select"><select autofocus="autofocus" id="teamType1"><option value="1">1. Mannschaft</option><option value="2">2. Mannschaft</option><option value="3">A-Junioren</option><option value="4">B-Junioren</option><option value="5">C-Junioren</option><option value="6">D-Junioren</option><option value="7">E-Junioren</option><option value="8">F-Junioren</option><option value="9">Hobby</option></select></div>' +
	        				'<label>Teamname</label>'+
	        				'<div class="input-control text"><input type="text" id="teamName1" placeholder="Optionaler Teamname"></div>' +
	        				'<label>Trainer*</label>'+ str_filler +
	        				'<h6>* Pflichtfeld</h6>' + 
	        				'<div class="form-actions">' +
	        				'<button id="btnSubmit1" type="button" class="button primary" onclick="createNewTeam()">Erstellen</button>&nbsp;'+
	        				'<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
	        				'<img id="logWarten1" src="../../../../img/loading.gif" style="display: none;" />' +
	        				'<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>' + 
	        				'</div>'+
	        				'</form>';
	    	var int_width = 300;
	    	// Dialog erstellen
	    	createDialog(str_title, str_content, int_width);
	    	$('#teamType1').focus();
	    }
	});
}

// Neue Mannschaft erstellen
function createNewTeam() {
	// Datenfelder zuweisen
	var teamTypeID = $('#teamType1').val();
	var teamName = $('#teamName1').val();
	var coachID = $('#coachID1').val();
	// Wenn alle Felder ausgefüllt sind
	if (teamTypeID != ""){
		$("#alert1").hide();
		$("#logWarten1").show();
		$("#btnSubmit1").hide();
		// Wenn ein Trainer ausgewählt wurde
		if (coachID != ""){
			// per Ajax Daten eintragen
			$.ajax({
				type: 'POST',
				url: 'functions/createTeam.php',
				data: {teamTypeID: teamTypeID, teamName: teamName, coachID: coachID},
				cache: false,
				success: function(data) {
					$("#logWarten1").hide();
					$("#btnSubmit1").show();
					if (data == "ok"){
						// Felder leeren
						$('#teamName1').val("");
						$('#teamType1').val(1);
						// teamType fokussieren
						$('#teamType1').focus();
						// Meldung anzeigen
						$("#alert1").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Die Mannschaft wurde erstellt.</div></div>");
						$("#alert1").show();
					}
					else if(data == "coachSchonVorhanden"){
						// Meldung anzeigen
						$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Der Trainer trainiert bereits eine Mannschaft.</div></div>");
						$("#alert1").show();
					}
					else{
						// Meldung anzeigen
						$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
						$("#alert1").show();
					}
				}
			});
		}
		// Wenn kein Trainer ausgewählt wurde
		else{
			// Ladezeichen verbergen
			$("#logWarten1").hide();
			$("#btnSubmit1").show();
			// Meldung anzeigen
			$('#alert1').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst einen Trainer</div></div>');
			$("#alert1").show();
		}
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder aus!</div></div>");
		$("#alert1").show();
	}
}

// Dialog erstellen
function showDialogCreateNewCoach() {
	// Variablen für Dialog deklarieren
	var str_title = 'Neuen Trainer erstellen';
	var str_content = '<form class="fg-black">'
			+ '<label>Vorname*</label>'
			+ '<div class="input-control text"><input type="text" id="vorname1" placeholder="Vorname" required="required" autofocus="autofocus"></div>'
			+ '<label>Nachname*</label>'
			+ '<div class="input-control text"><input type="text" id="nachname1" placeholder="Nachname" required="required"></div>'
			+ '<label>E-Mail Adresse*</label>'
			+ '<div class="input-control text"><input type="email" id="email1" placeholder="E-Mail Adresse" required="required"></div>'
			+ '<h6>* Pflichtfeld</h6>'
			+ '<button id="btnSubmit1" type="button" class="button primary" onclick="createNewCoach()">Erstellen</button>&nbsp;'
			+ '<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
			+ '<img id="logWarten1" src="../../../../img/loading.gif" style="display: none;" />'
			+ '<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
			+ '</div>' + '</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
	$('#vorname1').focus();
}

// Neuen Trainer erstellen
function createNewCoach() {
	// Datenfelder zuweisen
	var vorname = $('#vorname1').val();
	var nachname = $('#nachname1').val();
	var email = $('#email1').val();
	// Wenn alle Felder ausgefüllt sind
	if (vorname != "" && nachname != "" && email != ""){
		$("#alert1").hide();
		$("#logWarten1").show();
		$("#btnSubmit1").hide();
		// per Ajax Daten eintragen
		$.ajax({
			type: 'POST',
			url: 'functions/createCoach.php',
			data: {vorname: vorname, nachname: nachname, email: email},
			cache: false,
			success: function(data) {
				$("#logWarten1").hide();
				$("#btnSubmit1").show();
				if (data == "ok"){
					// Felder leeren
					$('#vorname1').val("");
					$('#nachname1').val("");
					$('#email1').val("");
					// Vorname fokussieren
					$('#vorname1').focus();
					// Meldung anzeigen
					$("#alert1").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Der Trainer wurde erstellt.</div></div>");
					$("#alert1").show();
				}
				else if (data == "emailSchonVorhanden"){
					$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Eine identische E-Mail Adresse existiert bereits.</div></div>");
					$("#alert1").show();
				}
				else{
					$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
					$("#alert1").show();
				}
			}
		});
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder aus!</div></div>");
		$("#alert1").show();
	}
}

// Dialog erstellen
function showDialogCreateNewAppointment(){
	if (USER_RIGHTS == 0){
		// Admin kann für alle Mannschaften Termine erstellen
		$.ajax({
		    url: "../inc/ajaxTest.php?class=db&function=getTeamByClub&id=0",
		    success: function(json_teams) {
		    	var arr_teams = JSON.parse(json_teams);
		    	var str_allTeams = "";
		    	// Schleifendurchlauf
		    	for (var i = 0; i < arr_teams.length; i++) {
		    		var clubID = arr_teams[i]["clubID"];
		    		// TeamTypeID zu TeamType umwandeln
		    		var teamType = convertTeamTypeID(arr_teams[i]["teamTypeID"]);
		    		var teamBez = "";
		    		// Wenn ein TeamName existiert
		    		if (arr_teams[i]["teamName"] != -1){
		    			teamBez = teamType + " (" + arr_teams[i]["teamName"] + ")";
		    		}
		    		else{
		    			teamBez = teamType;
		    		}
		    		str_allTeams = str_allTeams + '<option value="' + arr_teams[i]["teamID"] + '">' + teamBez + '</option>';
				}
		    	var str_filler = "";
		    	// Wenn keine Mannschaft gefunden wurde, wird ein Button "Neue Mannschaft erstellen" angezeigt
		    	if(str_allTeams == ""){
		    		str_filler = '<button id="teamID1" style="width: 100%; height: 34px;" class="button bg-gray fg-white" type="button" onclick="$.Dialog.close(); showDialogCreateNewTeam(' + clubID + ')">Neue Mannschaft erstellen</button><br><br>';
		    	}
		    	// Ansonsten anzeigen aller möglicher Trainer in einem Auswahlfeld
		    	else{
		    		str_filler = '<div class="input-control select"><select id="teamID1"><option value="0">Mannschaft wählen</option>' + str_allTeams + '</select></div>';
		    	}
				// Variablen für Dialog deklarieren
				var str_title = 'Neuen Termin erstellen';
				var str_content = '<form class="fg-black">'
						+ '<label>Terminart*</label>'
						+ '<div class="input-control select"><select id="appointmentType" onchange="changeType()"><option value="1">Training</option><option value="2" selected="selected">Spiel</option><option value="3">Turnier</option></select></div>'
						+ '<div id="contentBezeichnung" style="display: none;">'
						+ 	'<label>Bezeichnung</label>'
						+ 	'<div class="input-control text"><input type="text" id="bezeichnung" placeholder="Bezeichnung"></div>'
						+ '</div>'
						+ '<label>Mannschaft*</label>' + str_filler
						+ '<div id="contentGegner" style="display: none;">'
						+ 	'<label>Gegner</label>'
						+ 	'<div class="input-control text"><input type="text" id="gegner" placeholder="Gegner"></div>'
						+	'<label>Spielort</label>'
						+	'<div class="input-control radio default-style" data-role="input-control">'
						+		'<label>'
						+			'<input id="radioHeim" type="radio" name="r" value="H" checked="">'
						+			'<span class="check"></span>'
						+			'Heim'
						+		'</label>'
						+	'</div>'
						+	'<div class="input-control radio default-style" style="margin-left: 25px;" data-role="input-control">'
						+		'<label>'
						+			'<input id="radioAuswaerts" type="radio" name="r" value="A">'
						+			'<span class="check"></span>'
						+			'Auswärts'
						+		'</label>'
						+	'</div>'
						+ '</div>'
						+ '<label>Startdatum*</label>'
						+ '<div class="input-control text"><input type="date" id="startdatum" placeholder="tt.mm.jjjj"></div>'
						+ '<label>Startuhrzeit*</label>'
						+ '<div class="input-control text"><input type="time" id="startuhrzeit" placeholder="hh:mm"></div>'
						+ '<label>Enddatum*</label>'
						+ '<div class="input-control text"><input type="date" id="enddatum" placeholder="tt.mm.jjjj"></div>'
						+ '<label>Enduhrzeit*</label>'
						+ '<div class="input-control text"><input type="time" id="enduhrzeit" placeholder="hh:mm"></div>'
						+ '<h6>* Pflichtfeld</h6>'
						+ '<button id="btnSubmit1" type="button" class="button primary" onclick="createNewAppointment()">Erstellen</button>&nbsp;'
						+ '<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
						+ '<img id="wait1" src="../../../../img/loading.gif" style="display: none;" />'
						+ '<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
						+ '</div>' + '</form>';
				var int_width = 300;
				// Dialog erstellen
				createDialog(str_title, str_content, int_width);
				changeType();
				$('#gegner').focus();
		    }
		});
	}
	// Trainer kann nur für Seine Mannschaft Termine erstellen
	else if(USER_RIGHTS == 1){
		$.ajax({
		    url: "../inc/ajaxTest.php?class=db&function=getTeamByID&id=0",
		    success: function(json_team) {
		    	var arr_team = JSON.parse(json_team);
		    	var str_team = "";
	    		// TeamTypeID zu TeamType umwandeln
	    		var teamType = convertTeamTypeID(arr_team["teamTypeID"]);
	    		var teamBez = "";
	    		// Wenn ein TeamName existiert
	    		if (arr_team["teamName"] != -1){
	    			teamBez = teamType + " (" + arr_team["teamName"] + ")";
	    		}
	    		else{
	    			teamBez = teamType;
	    		}
	    		str_team = '<option value="' + arr_team["teamID"] + '">' + teamBez + '</option>';
		    	var str_filler = "";
		    	// Wenn keine Mannschaft gefunden wurde, wird ein Hinweis angezeigt
		    	if(str_team == ""){
		    		str_filler = 'Sie trainieren keine Mannschaft.<br><br>';
		    	}
		    	// Ansonsten anzeigen der Mannschaft in einem Auswahlfeld
		    	else{
		    		str_filler = '<div class="input-control select"><select id="teamID1">' + str_team + '</select></div>';
		    	}
				// Variablen für Dialog deklarieren
				var str_title = 'Neuen Termin erstellen';
				var str_content = '<form class="fg-black">'
						+ '<label>Terminart*</label>'
						+ '<div class="input-control select"><select id="appointmentType" onchange="changeType()"><option value="1">Training</option><option value="2" selected="selected">Spiel</option><option value="3">Turnier</option></select></div>'
						+ '<div id="contentBezeichnung" style="display: none;">'
						+ 	'<label>Bezeichnung</label>'
						+ 	'<div class="input-control text"><input type="text" id="bezeichnung" placeholder="Bezeichnung"></div>'
						+ '</div>'
						+ '<label>Mannschaft*</label>' + str_filler
						+ '<div id="contentGegner" style="display: none;">'
						+ 	'<label>Gegner</label>'
						+ 	'<div class="input-control text"><input type="text" id="gegner" placeholder="Gegner"></div>'
						+	'<label>Spielort</label>'
						+	'<div class="input-control radio default-style" data-role="input-control">'
						+		'<label>'
						+			'<input id="radioHeim" type="radio" name="r" value="H" checked="">'
						+			'<span class="check"></span>'
						+			'Heim'
						+		'</label>'
						+	'</div>'
						+	'<div class="input-control radio default-style" style="margin-left: 25px;" data-role="input-control">'
						+		'<label>'
						+			'<input id="radioAuswaerts" type="radio" name="r" value="A">'
						+			'<span class="check"></span>'
						+			'Auswärts'
						+		'</label>'
						+	'</div>'
						+ '</div>'
						+ '<label>Startdatum*</label>'
						+ '<div class="input-control text"><input type="date" id="startdatum" placeholder="tt.mm.jjjj"></div>'
						+ '<label>Startuhrzeit*</label>'
						+ '<div class="input-control text"><input type="time" id="startuhrzeit" placeholder="hh:mm"></div>'
						+ '<label>Enddatum*</label>'
						+ '<div class="input-control text"><input type="date" id="enddatum" placeholder="tt.mm.jjjj"></div>'
						+ '<label>Enduhrzeit*</label>'
						+ '<div class="input-control text"><input type="time" id="enduhrzeit" placeholder="hh:mm"></div>'
						+ '<h6>* Pflichtfeld</h6>'
						+ '<button id="btnSubmit1" type="button" class="button primary" onclick="createNewAppointment()">Erstellen</button>&nbsp;'
						+ '<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
						+ '<img id="wait1" src="../../../../img/loading.gif" style="display: none;" />'
						+ '<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
						+ '</div>' + '</form>';
				var int_width = 300;
				// Dialog erstellen
				createDialog(str_title, str_content, int_width);
				changeType();
				$('#gegner').focus();
				$('#teamID1').attr("disabled", "disabled");
		    }
		});
	}
}

// Neuen Termin erstellen
function createNewAppointment() {
	// Wartezeichen anzeigen und Button verstecken und Alert leeren
	$('#btnSubmit1').hide();
	$('#wait1').show();
	$('#alert1').html("");
	// Datenfelder zuweisen
	var appointmentType = $('#appointmentType').val();
	var teamID = $('#teamID1').val();
	var startdatum = $('#startdatum').val();
	var startuhrzeit = $('#startuhrzeit').val();
	var enddatum = $('#enddatum').val();
	var enduhrzeit = $('#enduhrzeit').val();
	var location = "H";
	// Ort abfragen
	if ($('#radioAuswaerts').is(':checked')){
		location = "A";
	}
	// Wenn eine Mannschaft existiert
	if (teamID != ""){
		// Wenn eine Mannschaft ausgewählt wurde
		if (teamID != 0){
			// Wenn alle Felder ausgefüllt sind
			if(appointmentType != "" && startdatum != "" && startuhrzeit != "" && enddatum != "" && enduhrzeit != ""){
				if (appointmentType == 1){
					var gegner = "";
					var bezeichnung = "";
				}
				// Wenn es ein Spiel ist, dann Gegner abfragen
				else if (appointmentType == 2){
					var gegner = $('#gegner').val();
					var bezeichnung = "";
				}
				// Wenn es ein Turnier ist, dann Bezeichnung abfragen
				else if (appointmentType == 3){
					var gegner = "";
					var bezeichnung = $('#bezeichnung').val();
				}
				var info = gegner + "" + bezeichnung;
				$("#alert1").hide();
				$("#logWarten1").show();
				$("#btnSubmit1").attr("disabled");
				// Ajax-Aufruf
				$.ajax({
					type : "POST",
					url : "../functions/createAppointment.php",
					cache : false,
					data : {teamID : teamID, appointmentType: appointmentType, info: info, location: location, startdatum: startdatum, enddatum: enddatum, startuhrzeit: startuhrzeit, enduhrzeit: enduhrzeit},
					success : function(data) {
						// Wartezeichen verbergen und Button anzeigen
						$('#btnSubmit1').show();
						$('#wait1').hide();
						if (data == "ok"){
							// Felder leeren
							$('#bezeichnung').val("");
							$('#gegner').val("");
							$('#startdatum').val("");
							$('#enddatum').val("");
							$('#startuhrzeit').val("");
							$('#enduhrzeit').val("");
							$('#radioHeim').attr("checked", "checked");
							// appointmentType fokussieren
							$('#gegner').focus();
							// Meldung anzeigen
							$("#alert1").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Der Termin wurde erstellt.</div></div>");
							$("#alert1").show();
							// Seite neu laden (Button neu klicken)
							$('#btnSubmit').click();
						}
						else{
							// Meldung anzeigen
							$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
							$("#alert1").show();
						}
					}
				});
			}
			// Wenn nicht alle Felder ausgefüllt sind
			else {
				// Wartezeichen verbergen und Button anzeigen
				$('#btnSubmit1').show();
				$('#wait1').hide();
				// Meldung anzeigen
				$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder aus.</div></div>");
				$("#alert1").show();
			}
		}
		// Wenn keine Mannschaft ausgewählt wurde
		else{
			// Wartezeichen verbergen und Button anzeigen
			$('#btnSubmit1').show();
			$('#wait1').hide();
			// Meldung anzeigen
			$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte wählen Sie eine Mannschaft aus.</div></div>");
			$("#alert1").show();
		}
	}
	// Wenn keine Mannschaft existiert
	else{
		// Wartezeichen verbergen und Button anzeigen
		$('#btnSubmit1').show();
		$('#wait1').hide();
		// Meldung anzeigen
		$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst eine Mannschaft.</div></div>");
		$("#alert1").show();
	}
}

// Inhalt erstellen
function buildContent(rights){
	// Inhalt löschen
	$('#result').html("");
	// Wartezeichen anzeigen und Button verstecken
	$('#btnSubmit').hide();
	$('#wait').show();
	// Datenübernahme
	var teamID = $('#teamID').val();
	// Wenn keine Mannschaft existiert
	if (teamID == ""){
		// Wartezeichen verbergen und Button anzeigen
		$('#btnSubmit').show();
		$('#wait').hide();
		// Meldung anzeigen
		$("#result").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst eine Mannschaft.</div></div>");
		$("#result").show();
	}
	// Wenn eine Mannschaft existiert
	else{
		// Wenn eine Mannschaft gewählt wurde
		if (teamID != 0){
			// Ajax Datenaufruf
			$.ajax({
				type: 'POST',
				url: 'functions/getSpielplan.php',
				data: {teamID: teamID},
				cache: false,
				success: function(data) {
					// Wartezeichen verbergen und Button anzeigen
					$('#btnSubmit').show();
					$('#wait').hide();
					if(data == "keineTermine"){
						// Meldung anzeigen
						$("#result").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Für den gewählten Zeitraum existieren keine Termine.</div></div>");
						$("#result").show();
					}
					else{
						// INHALTE GEFUNDEN							
						$("#result").html(data);
						$("#result").show();
					}
				}
			});
		}
		// Wenn keine Mannschaft gewählt wurde
		else{
			// Wartezeichen verbergen und Button anzeigen
			$('#btnSubmit').show();
			$('#wait').hide();
			// Meldung anzeigen
			$("#result").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte wählen Sie zuerst eine Mannschaft aus.</div></div>");
			$("#result").show();
		}
	}
}

// Ergebnis eintragen
function setResult(eventID, startDate, opponent){
	// Dialog zum Bearbeiten erstellen
	showDialogSetResult(eventID, startDate, opponent);
}

// Dialog erstellen
function showDialogSetResult(eventID, startDate, opponent){
	// Per Ajax Eventinfos holen
	$.ajax({
		type: "POST",
		url: "../functions/getMatchInfos.php",
		cache: false,
		data: {eventID: eventID},
	    success: function(json_event) {
	    	var arr_event = JSON.parse(json_event);
	    	// Variablen zuweisen
	    	var teamGoals = arr_event['teamGoals'];
	    	var opponentGoals = arr_event['opponentGoals'];
	    	// Variablen für Dialog deklarieren
	    	var str_title = 'Ergebnis eintragen';
	    	var str_content = '<form class="fg-black">'
	    		+ '<label>Datum</label>'
				+ '<div class="input-control text"><input type="text" disabled="disabled" id="startdatum" placeholder="' + startDate + '"></div>'
				+ '<label>Gegner</label>'
				+ '<div class="input-control text"><input type="text" disabled="disabled" id="gegner" placeholder="' + opponent + '"></div>'
				+ '<label>Ergebnis</label>'
				+ '<div class="input-control text span1-2"><input type="text" id="teamGoals" placeholder="Team"></div>:'
				+ '<div class="input-control text span1-2"><input type="text" id="opponentGoals" placeholder="Gegner"></div><br>'
				+ '<button id="btnSubmit1" type="button" class="button primary" onclick="saveMatch(' + eventID + ')">Speichern</button>&nbsp;'
				+ '<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
				+ '<img id="wait1" src="../../../../img/loading.gif" style="display: none;" />'
				+ '<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
				+ '</div>' + '</form>';
	    	var int_width = 300;
	    	// Dialog erstellen
	    	createDialog(str_title, str_content, int_width);
	    	// Wenn Tore schon eingetragen, dann Variablen setzen
	    	if (teamGoals != -1 && opponentGoals != -1){
	    		$('#teamGoals').attr("placeholder", teamGoals);
		    	$('#opponentGoals').attr("placeholder", opponentGoals);
	    	}
	    	// Fokus setzen
	    	$('#teamGoals').focus();
	    }
	});
}

// Spiel bearbeiten
function editMatch(eventID, startDate, opponent){
	// Dialog zum Bearbeiten erstellen
	showDialogEditMatch(eventID, startDate, opponent);
}

// Dialog erstellen
function showDialogEditMatch(eventID, startDate, opponent){
	// Per Ajax Eventinfos holen
	$.ajax({
		type: "POST",
		url: "../functions/getMatchInfos.php",
		cache: false,
		data: {eventID: eventID},
	    success: function(json_event) {
	    	var arr_event = JSON.parse(json_event);
	    	// Variablen zuweisen
	    	var teamGoals = arr_event['teamGoals'];
	    	var opponentGoals = arr_event['opponentGoals'];
	    	// Variablen für Dialog deklarieren
	    	var str_title = 'Spiel bearbeiten';
	    	var str_content = '<form class="fg-black">'
	    		+ '<label>Datum</label>'
				+ '<div class="input-control text"><input type="text" disabled="disabled" id="startdatum" placeholder="' + startDate + '"></div>'
				+ '<label>Gegner</label>'
				+ '<div class="input-control text"><input type="text" disabled="disabled" id="gegner" placeholder="' + opponent + '"></div>'
				+ '<label>Ergebnis</label>'
				+ '<div class="input-control text span1-2"><input type="text" id="teamGoals" placeholder="Team"></div>:'
				+ '<div class="input-control text span1-2"><input type="text" id="opponentGoals" placeholder="Gegner"></div><br>'
				+ '<button id="btnSubmit1" type="button" class="button primary" onclick="saveMatch(' + eventID + ')">Speichern</button>&nbsp;'
				+ '<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
				+ '<img id="wait1" src="../../../../img/loading.gif" style="display: none;" />'
				+ '<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
				+ '</div>' + '</form>';
	    	var int_width = 300;
	    	// Dialog erstellen
	    	createDialog(str_title, str_content, int_width);
	    	// Wenn Tore schon eingetragen, dann Variablen setzen
	    	if (teamGoals != -1 && opponentGoals != -1){
	    		$('#teamGoals').attr("placeholder", teamGoals);
		    	$('#opponentGoals').attr("placeholder", opponentGoals);
	    	}
	    	// Fokus setzen
	    	$('#teamGoals').focus();
	    }
	});
}

// Spiel speichern
function saveMatch(eventID){
	// Wartezeichen anzeigen und Button verstecken und Alert leeren
	$('#btnSubmit1').hide();
	$('#wait1').show();
	$('#alert1').html("");
	// Datenfelder zuweisen
	var teamGoals = $('#teamGoals').val();
	var opponentGoals = $('#opponentGoals').val();
	// Wenn beide Datenfelder ausgefüllt sind
	if (isNormalInteger(teamGoals) && isNormalInteger(opponentGoals)){
		// Ajax-Aufruf
		$.ajax({
			type : "POST",
			url : "../functions/saveMatch.php",
			cache : false,
			data : {eventID: eventID, teamGoals : teamGoals, opponentGoals: opponentGoals},
			success : function(data) {
				// Wartezeichen verbergen und Button anzeigen
				$('#btnSubmit1').show();
				$('#wait1').hide();
				if (data == "ok"){
					// appointmentType fokussieren
					$('#teamGoals').focus();
					// Meldung anzeigen
					$("#alert1").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Die Spielinformationen wurden aktualisiert.</div></div>");
					$("#alert1").show();
					// Seite neu laden (Button neu klicken)
					$('#btnSubmit').click();
				}
				else{
					// Meldung anzeigen
					$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
					$("#alert1").show();
				}
			}
		});
	}
	// Wenn eingetragene Daten keine Integer
	else{
		// Wartezeichen verbergen und Button anzeigen
		$('#btnSubmit1').show();
		$('#wait1').hide();
		// Meldung anzeigen
		$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder korrekt aus.</div></div>");
		$("#alert1").show();
	}
}

// Spiel löschen
function deleteMatch(int_eventID) {
	createDialogDeleteMatch(int_eventID);
}

// Dialog erstellen
function createDialogDeleteMatch(int_eventID){
	// Variablen für Dialog deklarieren
	var str_title = 'Spieltermin löschen';
	var str_content = 	'<form class="fg-black">' +
					'<div id="alert1" style="display: none; font-size: 14px;"></div>' + 
					'<div id="alertOld" style="font-size: 14px;" class="notice marker-on-top bg-darkRed fg-white"><div><strong>Achtung!</strong><br>Möchten Sie den ausgewählten Termin wirklich löschen?<br>Dieser wird ebenfalls aus dem Kalender gelöscht.</div></div>' +
    				'<br>' +
    				'<div class="form-actions">' +
    				'<button id="btnSubmit1" type="button" class="button primary" onclick="deleteMatchInfos(' + int_eventID + ')">Löschen</button>&nbsp;'+
    				'<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
    				'<img id="wait1" src="../../../../img/loading.gif" style="display: none;" />' +
    				'</div>'+
    				'</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
}

// Spiel löschen
function deleteMatchInfos(int_eventID){
	$('#alertOld').hide();
	// Ladezeichen anzeigen
	$("#wait1").show();
	$("#btnSubmit1").hide();
	// Per Ajax Spieler löschen
	$.ajax({
		type: "POST",
		url: '../functions/deleteMatch.php',
		cache: false,
		data: {eventID: int_eventID},
		success: function(data) {
			// Ladezeichen verbergen
			$("#wait1").hide();
			$("#btnSubmit1").show();
			if(data == "ok"){
				// Meldung anzeigen
				$('#alert1').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Der Spieltermin wurde gelöscht!</div></div>');
				$("#alert1").show();
				// Button verbergen
				$("#btnSubmit1").hide();
				$("#btnAbort1").attr("class", "button place-right success");
				$("#btnAbort1").html("Fertig");
				// Seite neu laden (Button neu klicken)
				$('#btnSubmit').click();
			}
			else{
				// Meldung anzeigen
				$('#alert1').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
				$("#alert1").show();
			}
		}
	});
}

//Dialog erstellen
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

// Wechsel der Terminart 
function changeType(){
	// Aktuellen Type abfragen
	var appointmentType = $('#appointmentType').val();
	// Wenn es ein Training ist
	if(appointmentType == 1){
		$('#contentBezeichnung').hide();
		$('#contentGegner').hide();
	}
	// Wenn es ein Spiel ist
	else if(appointmentType == 2){
		$('#contentBezeichnung').hide();
		$('#contentGegner').show();
	}
	// Wenn es ein Turnier ist
	else if(appointmentType == 3){
		$('#contentBezeichnung').show();
		$('#contentGegner').hide();
	}
}

// Name des Typs
function convertTeamTypeID(int_teamTypeID){
	var teamType = "";
	switch (int_teamTypeID) {
		case '1':
			teamType = "1. Mannschaft";
			break;
		case '2':
			teamType = "2. Mannschaft";
			break;
		case '3':
			teamType = "A-Junioren";
			break;
		case '4':
			teamType = "B-Junioren";
			break;
		case '5':
			teamType = "C-Junioren";
			break;
		case '6':
			teamType = "D-Junioren";
			break;
		case '7':
			teamType = "E-Junioren";
			break;
		case '8':
			teamType = "F-Junioren";
			break;
		case '9':
			teamType = "Hobby";
			break;
		default:
			teamType = "Fehler";
			break;
	}
	return teamType;
}

// Prüfen, ob String ein positiver Integer
function isNormalInteger(str) {
    var n = ~~Number(str);
    return String(n) === str && n >= 0;
}