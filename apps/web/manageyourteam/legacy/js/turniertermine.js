SOURCE = new Array();
SOURCE[0] = 'functions/turniertermine.php';
NEWSOURCE = new Array();
NEWSOURCE[0] = SOURCE[0];
$(document).ready(function(){loadCalendar();});

// Kalender laden
function loadCalendar(){
	$('#calendar').fullCalendar({
		eventSources: [SOURCE[0]],
		header : {
			left : 'prev,next, today',
			center : 'title',
			right : 'month,agendaWeek,agendaDay'
		},
		titleFormat : {
			month : 'MMMM yyyy', // Mai 2014
			week : "d. [MMM] [yyyy] { '-' d. MMM yyyy}", // 26. Mai - 1. Jun 2014
			day : 'dddd, d. MMM yyyy' // Montag, 26. Mai 2014
		},
		columnFormat : {
			month : 'ddd', // Mo
			week : 'ddd d.M', // Mo 26.5
			day : 'dddd, d.M' // Montag 26.5
		},
		firstDay : 1,
		editable : false,
		eventMouseover : function(event, jsEvent, view) {
			$('.fc-event-inner', this).append(
					'<div id="' + event.id + '" class="hover-end">'
						+ event.info
					+ '</div>');
		},
		eventMouseout : function(event, jsEvent, view) {
			$('#' + event.id).remove();
		},
		eventClick: function(event, jsEvent, view) {
			// Ein Admin und Trainer können bearbeiten, ein Spieler nicht
			if (USER_RIGHTS == 0 || USER_RIGHTS == 1){
				editEvent(event.id);
			}
	    },
		timeFormat : 'H:mm' + '\n'
	});
}

// Kalender neu laden
function reloadCalendar(){
	NEWSOURCE[0] = 'functions/turniertermine.php';
  $('#calendar')
      .fullCalendar('removeEventSource', SOURCE[0])
      .fullCalendar('refetchEvents')
      .fullCalendar('addEventSource', NEWSOURCE[0])
      .fullCalendar('refetchEvents');
  SOURCE[0] = NEWSOURCE[0];
}

// Termin bearbeiten
function editEvent(eventID){
	// Dialog zum Bearbeiten erstellen
	showDialogEditEvent(eventID);
}

//Dialog erstellen
function showDialogEditEvent(eventID){
	// Per Ajax Eventinfos holen
	$.ajax({
		type: "POST",
		url: "../functions/getEventInfos.php",
		cache: false,
		data: {eventID: eventID},
	    success: function(json_event) {
	    	var arr_event = JSON.parse(json_event);
	    	// Variablen zuweisen
	    	var teamID = arr_event['teamID'];
	    	var clubID = arr_event['clubID'];
	    	var eventType = arr_event['eventType'];
	    	var eventInfo = arr_event['eventInfo'];
	    	var location = arr_event['location'];
	    	var eventStartDate = arr_event['eventStartDate'];
	    	var eventEndDate = arr_event['eventEndDate'];
	    	var eventStartTime = arr_event['eventStartTime'];
	    	var eventEndTime = arr_event['eventEndTime'];
	    	// Admin kann Termine zu allen Mannschaften ändern
	    	if (USER_RIGHTS == 0){
		    	$.ajax({
		    	    url: "../inc/ajaxTest.php?class=db&function=getTeamByClub&id=" + clubID,
		    	    success: function(json_teams) {
		    	    	var arr_teams = JSON.parse(json_teams);
		    	    	var str_allTeams = "";
		    	    	// Schleifendurchlauf
		    	    	for (var i = 0; i < arr_teams.length; i++) {
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
		    	    		str_filler = '<button style="width: 100%; height: 34px;" class="button bg-gray fg-white" id="teamID" type="button" onclick="$.Dialog.close(); showDialogCreateNewTeam(' + clubID + ')">Neue Mannschaft erstellen</button><br><br>';
		    	    	}
		    	    	// Ansonsten anzeigen aller möglicher Trainer in einem Auswahlfeld
		    	    	else{
		    	    		str_filler = '<div class="input-control select"><select id="teamID">' + str_allTeams + '</select></div>';
		    	    	}
				    	// Variablen für Dialog deklarieren
				    	var str_title = 'Termin bearbeiten';
				    	var str_content = '<form class="fg-black">'
							+ '<label>Terminart*</label>'
							+ '<div class="input-control select"><select autofocus="autofocus" id="appointmentType" onchange="changeType()"><option value="1">Training</option><option value="2">Spiel</option><option value="3">Turnier</option></select></div>'
							+ '<div id="contentBezeichnung" style="display: none;">'
							+ 	'<label>Bezeichnung</label>'
							+ 	'<div class="input-control text"><input type="text" id="bezeichnung" placeholder="Bezeichnung"></div>'
							+ '</div>'
							+ '<label>Mannschaft*</label>' + str_filler
							+ '<div id="contentGegner" style="display: none;">'
							+ 	'<label>Gegner</label>'
							+ 	'<div class="input-control text"><input type="text" id="gegner" placeholder="Gegner"></div>'
							+	'<label>Spielort*</label>'
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
							+ '<button id="btnSubmit" type="button" class="button primary" onclick="saveEvent(' + eventID + ')">Speichern</button>&nbsp;'
							+ '<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
							+ '<div style="width: 100%; margin-top: 6px;>'
							+ 	'<button id="btnDelete" type="button" class="button danger" onclick="$.Dialog.close(); deleteEvent(' + eventID + ', ' + eventType + ')">Termin löschen</button>&nbsp;'
							+ '</div>'
							+ '<img id="wait" src="../../../../img/loading.gif" style="display: none;" />'
							+ '<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
							+ '</div>' + '</form>';
				    	var int_width = 300;
				    	// Dialog erstellen
				    	createDialog(str_title, str_content, int_width);
				    	// Wenn es ein Training ist
				    	if (eventType == 1){
				    		// Platzhalter Gegner und Bezeichnung leeren
				    		$('#gegner').attr("placeholder", "Gegner");
				    		$('#bezeichnung').attr("placeholder", "Bezeichnung");
				    	}
				    	// Wenn es ein Spiel ist
				    	else if (eventType == 2){
				    		// Platzhalter Bezeichnung leeren
				    		$('#bezeichnung').attr("placeholder", "Bezeichnung");
				    		// Platzhalter Gegner setzen
				    		if(eventInfo != ""){
				    			$('#gegner').attr("placeholder", eventInfo);
				    		}
				    		else{
				    			$('#gegner').attr("placeholder", "Gegner");
				    		}
				    		// Ort setzen
				    		if (location == "A"){
				    			$('#radioAuswaerts').attr("checked", "checked");
				    		}
				    	}
				    	// Wenn es ein Turnier ist
				    	else if (eventType == 3){
				    		// Platzhalter Gegner leeren
				    		$('#gegner').attr("placeholder", "Gegner");
				    		// Platzhalter Bezeichnung setzen
				    		if (eventInfo != ""){
				    			$('#bezeichnung').attr("placeholder", eventInfo);
				    		}
				    		else{
				    			$('#bezeichnung').attr("placeholder", "Bezeichnung");
				    		}
				    	}
				    	// Variablen setzen
				    	$('#teamID').val(teamID);
				    	$('#appointmentType').val(eventType);
				    	$('#startdatum').val(eventStartDate);
				    	$('#enddatum').val(eventEndDate);
				    	$('#startuhrzeit').val(eventStartTime);
				    	$('#enduhrzeit').val(eventEndTime);
				    	// Fokus setzen
				    	$('#appointmentType').focus();
				    	changeType();
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
	    		    		str_filler = 'Sie trainieren aktuell keine Mannschaft.<br><br>';
	    		    	}
	    		    	// Ansonsten anzeigen der Mannschaft in einem Auswahlfeld
	    		    	else{
	    		    		str_filler = '<div class="input-control select"><select id="teamID" disabled="disabled">' + str_team + '</select></div>';
	    		    	}
	    		    	// Variablen für Dialog deklarieren
				    	var str_title = 'Termin bearbeiten';
				    	var str_content = '<form class="fg-black">'
							+ '<label>Terminart*</label>'
							+ '<div class="input-control select"><select autofocus="autofocus" id="appointmentType" onchange="changeType()"><option value="1">Training</option><option value="2">Spiel</option><option value="3">Turnier</option></select></div>'
							+ '<div id="contentBezeichnung" style="display: none;">'
							+ 	'<label>Bezeichnung</label>'
							+ 	'<div class="input-control text"><input type="text" id="bezeichnung" placeholder="Bezeichnung"></div>'
							+ '</div>'
							+ '<label>Mannschaft*</label>' + str_filler
							+ '<div id="contentGegner" style="display: none;">'
							+ 	'<label>Gegner</label>'
							+ 	'<div class="input-control text"><input type="text" id="gegner" placeholder="Gegner"></div>'
							+	'<label>Spielort*</label>'
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
							+ '<button id="btnSubmit" type="button" class="button primary" onclick="saveEvent(' + eventID + ')">Speichern</button>&nbsp;'
							+ '<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
							+ '<div style="width: 100%; margin-top: 6px;>'
							+ 	'<button id="btnDelete" type="button" class="button danger" onclick="$.Dialog.close(); deleteEvent(' + eventID + ', ' + eventType + ')">Termin löschen</button>&nbsp;'
							+ '</div>'
							+ '<img id="wait" src="../../../../img/loading.gif" style="display: none;" />'
							+ '<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
							+ '</div>' + '</form>';
				    	var int_width = 300;
				    	// Dialog erstellen
				    	createDialog(str_title, str_content, int_width);
				    	// Wenn es ein Training ist
				    	if (eventType == 1){
				    		// Platzhalter Gegner und Bezeichnung leeren
				    		$('#gegner').attr("placeholder", "Gegner");
				    		$('#bezeichnung').attr("placeholder", "Bezeichnung");
				    	}
				    	// Wenn es ein Spiel ist
				    	else if (eventType == 2){
				    		// Platzhalter Bezeichnung leeren
				    		$('#bezeichnung').attr("placeholder", "Bezeichnung");
				    		// Platzhalter Gegner setzen
				    		if(eventInfo != ""){
				    			$('#gegner').attr("placeholder", eventInfo);
				    		}
				    		else{
				    			$('#gegner').attr("placeholder", "Gegner");
				    		}
				    		// Ort setzen
				    		if (location == "A"){
				    			$('#radioAuswaerts').attr("checked", "checked");
				    		}
				    	}
				    	// Wenn es ein Turnier ist
				    	else if (eventType == 3){
				    		// Platzhalter Gegner leeren
				    		$('#gegner').attr("placeholder", "Gegner");
				    		// Platzhalter Bezeichnung setzen
				    		if (eventInfo != ""){
				    			$('#bezeichnung').attr("placeholder", eventInfo);
				    		}
				    		else{
				    			$('#bezeichnung').attr("placeholder", "Bezeichnung");
				    		}
				    	}
				    	// Variablen setzen
				    	$('#teamID').val(teamID);
				    	$('#appointmentType').val(eventType);
				    	$('#startdatum').val(eventStartDate);
				    	$('#enddatum').val(eventEndDate);
				    	$('#startuhrzeit').val(eventStartTime);
				    	$('#enduhrzeit').val(eventEndTime);
				    	// Fokus setzen
				    	$('#appointmentType').focus();
				    	changeType();
	    		    }
	    		});
	    	}
	    }
	});
}

// Termin speichern
function saveEvent(eventID){
	// Wartezeichen anzeigen und Button verstecken und Alert leeren
	$('#btnSubmit').hide();
	$('#wait').show();
	$('#alert').html("");
	// Datenfelder zuweisen
	var teamID = $('#teamID').val();
	var appointmentType = $('#appointmentType').val();
	var startdatum = $('#startdatum').val();
	var enddatum = $('#enddatum').val();
	var startuhrzeit = $('#startuhrzeit').val();
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
			if(startdatum != "" && enddatum != "" && startuhrzeit != "" && enduhrzeit != "" && appointmentType != ""){
				if (appointmentType == 1){
					var gegner = "";
					var bezeichnung = "";
				}
				// Wenn es ein Spiel ist, dann Gegner abfragen
				else if (appointmentType == 2){
					var gegner = $('#gegner').val();
					if (gegner == ""){
						gegner = $('#gegner').attr("placeholder");
					}
					var bezeichnung = "";
				}
				// Wenn es ein Turnier ist, dann Bezeichnung abfragen
				else if (appointmentType == 3){
					var gegner = "";
					var bezeichnung = $('#bezeichnung').val();
					if (bezeichnung == ""){
						bezeichnung = $('#bezeichnung').attr("placeholder");
					}
				}
				var info = gegner + "" + bezeichnung;
				$("#alert").hide();
				$("#logWarten").show();
				$("#btnSubmit").attr("disabled");
				// Ajax-Aufruf
				$.ajax({
					type : "POST",
					url : "../functions/saveAppointment.php",
					cache : false,
					data : {eventID: eventID, teamID : teamID, appointmentType: appointmentType, info: info, location: location, startdatum: startdatum, enddatum: enddatum, startuhrzeit: startuhrzeit, enduhrzeit: enduhrzeit},
					success : function(data) {
						// Wartezeichen verbergen und Button anzeigen
						$('#btnSubmit').show();
						$('#wait').hide();
						if (data == "ok"){
							// appointmentType fokussieren
							$('#appointmentType').focus();
							// Meldung anzeigen
							$("#alert").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Der Termin wurde aktualisiert.</div></div>");
							$("#alert").show();
							// Kalender neu laden
							reloadCalendar();
						}
						else{
							// Meldung anzeigen
							$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
							$("#alert").show();
						}
					}
				});
			}
			// Wenn nicht alle Felder ausgefüllt sind
			else {
				// Wartezeichen verbergen und Button anzeigen
				$('#btnSubmit').show();
				$('#wait').hide();
				// Meldung anzeigen
				$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Felder korrekt aus.</div></div>");
				$("#alert").show();
			}
		}
		// Wenn keine Mannschaft ausgewählt wurde
		else{
			// Wartezeichen verbergen und Button anzeigen
			$('#btnSubmit').show();
			$('#wait').hide();
			// Meldung anzeigen
			$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte wählen Sie eine Mannschaft aus.</div></div>");
			$("#alert").show();
		}
	}
	// Wenn keine Mannschaft existiert
	else{
		// Wartezeichen verbergen und Button anzeigen
		$('#btnSubmit').show();
		$('#wait').hide();
		// Meldung anzeigen
		$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst eine Mannschaft.</div></div>");
		$("#alert").show();
	}
}

// Termin löschen
function deleteEvent(int_eventID, int_eventType) {
	createDialogDeleteEvent(int_eventID, int_eventType);
}

// Dialog erstellen
function createDialogDeleteEvent(int_eventID, int_eventType){
	// Wenn es ein Spiel ist, dann Warnung, dass auch aus dem Spielplan gelöscht wird
	var str_fillerText = "";
	if (int_eventType == 2){
		str_fillerText = "Wenn Sie diesen Spieltermin löschen, erscheint dessen Daten auch nicht mehr im Spielplan.<br>";
	}
	// Variablen für Dialog deklarieren
	var str_title = 'Termin löschen';
	var str_content = 	'<form class="fg-black">' +
					'<div id="alert" style="display: none; font-size: 14px;"></div>' + 
					'<div id="alertOld" style="font-size: 14px;" class="notice marker-on-top bg-darkRed fg-white"><div><strong>Achtung!</strong><br>' + str_fillerText + 'Möchten Sie diesen Termin wirklich löschen?</div></div>' +
    				'<br>' +
    				'<div class="form-actions">' +
    				'<button id="btnSubmit" type="button" class="button primary" onclick="deleteEventInfos(' + int_eventID + ', ' + int_eventType + ')">Löschen</button>&nbsp;'+
    				'<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
    				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
    				'</div>'+
    				'</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
}

// Termin löschen
function deleteEventInfos(int_eventID, int_eventType){
	$('#alertOld').hide();
	// Ladezeichen anzeigen
	$("#wait").show();
	$("#btnSubmit").hide();
	// Per Ajax Spieler löschen
	$.ajax({
		type: "POST",
		url: '../functions/deleteEvent.php',
		cache: false,
		data: {eventID: int_eventID, eventType: int_eventType},
		success: function(data) {
			// Ladezeichen verbergen
			$("#wait").hide();
			$("#btnSubmit").show();
			if(data == "ok"){
				// Meldung anzeigen
				$('#alert').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Der Termin wurde gelöscht!</div></div>');
				$("#alert").show();
				// Button verbergen
				$("#btnSubmit").hide();
				$("#btnAbort").attr("class", "button place-right success");
				$("#btnAbort").html("Fertig");
				// Kalender neu laden
				reloadCalendar();
			}
			else{
				// Meldung anzeigen
				$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
				$("#alert").show();
			}
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