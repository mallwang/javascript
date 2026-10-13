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

// Dialog erstellen
function showDialogCreateNewPlayer(int_ClubID) {
	if (USER_RIGHTS == 0){
		// Admin kann für alle Mannschaften Spieler erstellen
		$.ajax({
		    url: "../inc/ajaxTest.php?class=db&function=getTeamByClub&id=" + int_ClubID,
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
		    		str_filler = '<button style="width: 100%; height: 34px;" class="button bg-gray fg-white" id="teamID" type="button" onclick="$.Dialog.close(); showDialogCreateNewTeam(' + int_ClubID + ')">Neue Mannschaft erstellen</button><br><br>';
		    	}
		    	// Ansonsten anzeigen aller möglicher Trainer in einem Auswahlfeld
		    	else{
		    		str_filler = '<div class="input-control select"><select id="teamID"><option value="0">Bitte Mannschaft wählen</option>' + str_allTeams + '</select></div>';
		    	}
				// Variablen für Dialog deklarieren
				var str_title = 'Neuen Spieler erstellen';
				var str_content = '<form class="fg-black">'
						+ '<label>Mannschaft*</label>' + str_filler
						+ '<label>Vorname*</label>'
						+ '<div class="input-control text"><input type="text" id="vorname" placeholder="Vorname" required="required"></div>'
						+ '<label>Nachname*</label>'
						+ '<div class="input-control text"><input type="text" id="nachname" placeholder="Nachname" required="required"></div>'
						+ '<label>E-Mail Adresse</label>'
						+ '<div class="input-control text"><input type="email" id="email" placeholder="E-Mail Adresse"></div>'
						+ '<label>Geburtstatum*</label>'
						+ '<div class="input-control text"><input type="date" id="gebdatum" placeholder="tt.mm.jjjj" required="required"></div>'
						+ '<h6>* Pflichtfeld</h6>'
						+ '<button id="btnSubmit" type="button" class="button primary" onclick="createNewPlayer()">Erstellen</button>'
						+ '<button class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
						+ '<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />'
						+ '<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
						+ '</div>'
						+ '</form>';
				var int_width = 300;
				// Dialog erstellen
				createDialog(str_title, str_content, int_width);
				$('#teamID').focus();
		    }
		});
	}
	// Trainer kann nur für seine Mannschaft Spieler erstellen
	else if(USER_RIGHTS == 1){
		$.ajax({
		    url: "../inc/ajaxTest.php?class=db&function=getTeamByID&id=0",
		    success: function(json_team) {
		    	var arr_team = JSON.parse(json_team);
		    	var str_title = "";
				var str_content = "";
		    	// Wenn Coach keine Mannschaft trainiert
		    	if (arr_team == false){
		    		// Variablen für Dialog deklarieren
					str_title = 'Neuen Termin erstellen';
					str_content = '<div align="center" class="fg-black">Sie trainieren aktuell keine Mannschaft!</div>';
		    	}
		    	else{
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
			    		str_filler = '<div class="input-control select"><select id="teamID">' + str_team + '</select></div>';
			    	}
			    	// Variablen für Dialog deklarieren
					var str_title = 'Neuen Spieler erstellen';
					var str_content = '<form class="fg-black">'
							+ '<label>Mannschaft*</label>' + str_filler
							+ '<label>Vorname*</label>'
							+ '<div class="input-control text"><input type="text" id="vorname" placeholder="Vorname" required="required"></div>'
							+ '<label>Nachname*</label>'
							+ '<div class="input-control text"><input type="text" id="nachname" placeholder="Nachname" required="required"></div>'
							+ '<label>E-Mail Adresse</label>'
							+ '<div class="input-control text"><input type="email" id="email" placeholder="E-Mail Adresse"></div>'
							+ '<label>Geburtstatum*</label>'
							+ '<div class="input-control text"><input type="date" id="gebdatum" placeholder="tt.mm.jjjj" required="required"></div>'
							+ '<h6>* Pflichtfeld</h6>'
							+ '<button id="btnSubmit" type="button" class="button primary" onclick="createNewPlayer()">Erstellen</button>'
							+ '<button class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
							+ '<img id="logWarten" src="../../../../img/loading.gif" style="display: none;" />'
							+ '<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
							+ '</div>'
							+ '</form>';
					var int_width = 300;
					// Dialog erstellen
					createDialog(str_title, str_content, int_width);
					$('#teamID').attr("disabled", "disabled");
					$('#vorname').focus();
		    	}
		    }
		});
	}
}

// Neuen Spieler erstellen
function createNewPlayer() {
	// Datenfelder zuweisen
	var teamID = $('#teamID').val();
	var vorname = $('#vorname').val();
	var nachname = $('#nachname').val();
	var email = $('#email').val();
	var gebdatum = $('#gebdatum').val();
	// Wenn eine Mannschaft existiert
	if (teamID != ""){
		// Wenn eine Mannschaft ausgewählt wurde
		if (teamID != 0){
			// Wenn Pflichtfelder ausgefüllt sind
			if (vorname != "" && nachname != "" && gebdatum != ""){
				$("#alert").hide();
				$("#logWarten").show();
				$("#btnSubmit").hide();
				// Wenn eine Mannschaft ausgewählt
				if (teamID != ""){
					// per Ajax Daten eintragen
					$.ajax({
						type: 'POST',
						url: 'functions/createPlayer.php',
						data: {teamID: teamID, vorname: vorname, nachname: nachname, email: email, gebdatum: gebdatum},
						cache: false,
						success: function(data) {
							$("#logWarten").hide();
							$("#btnSubmit").show();
							if (data == "ok"){
								// Felder leeren
								$('#vorname').val("");
								$('#nachname').val("");
								$('#email').val("");
								$('#gebdatum').val("");
								// Vorname fokussieren
								$('#vorname').focus();
								// Meldung anzeigen
								$("#alert").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Der Spieler wurde erstellt.</div></div>");
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
				// Wenn keine Mannschaft ausgewählt
				else{
					// Ladezeichen verbergen
					$("#logWarten").hide();
					$("#btnSubmit").show();
					// Meldung anzeigen
					$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst eine Mannschaft</div></div>');
					$("#alert").show();
				}
			}
			// Wenn nicht alle Felder ausgefüllt sind
			else{
				$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte füllen Sie alle Pflichtfelder aus!</div></div>");
				$("#alert").show();
			}
		}
		// Wenn keine Mannschaft ausgewählt wurde
		else{
			// Wartezeichen verbergen und Button anzeigen
			$('#btnSubmit').show();
			$('#logWarten').hide();
			// Meldung anzeigen
			$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte wählen Sie eine Mannschaft aus.</div></div>");
			$("#alert").show();
		}
	}
	// Wenn keine Mannschaft existiert
	else{
		// Wartezeichen verbergen und Button anzeigen
		$('#btnSubmit').show();
		$('#logWarten').hide();
		// Meldung anzeigen
		$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst eine Mannschaft.</div></div>");
		$("#alert").show();
	}
}

// Spieler bearbeiten
function editPlayer(int_PlayerID) {
	createDialogEditPlayer(int_PlayerID);
}

// Dialog erstellen
function createDialogEditPlayer(int_PlayerID){
	// Per Ajax Spielerinfos holen
	$.ajax({
	    url: "../inc/ajaxTest.php?class=db&function=getPlayerByID&id=" + int_PlayerID,
	    success: function(json_player) {
	    	var arr_player = JSON.parse(json_player);
	    	// Variablen zuweisen
	    	var playerFirstName = arr_player['playerFirstName'];
	    	var playerSecondName = arr_player['playerSecondName'];
	    	var playerBirthdate = arr_player['playerBirthdate'];
	    	var userID = arr_player['userID'];
	    	var teamID = arr_player['teamID'];
	    	var clubID = arr_player['clubID'];
	    	if (USER_RIGHTS == 0){
	    		// Admin kann für alle Mannschaften Termine erstellen
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
		    	    	// Userdaten abfragen
		    	    	$.ajax({
		    	    	    url: "../inc/ajaxTest.php?class=db&function=getUserByID&id=" + userID,
		    	    	    success: function(json_user) {
		    	    	    	var arr_user = JSON.parse(json_user);
		    	    	    	var email = arr_user['userEmail'];
						    	// Variablen für Dialog deklarieren
						    	var str_title = 'Spieler bearbeiten';
						    	var str_content = 	'<form class="fg-black">' +
						    					'<label>Mannschaft*</label>' + str_filler +
						    					'<label>Vorname</label>' +
						    					'<div class="input-control text"><input type="text" id="playerFirstName" placeholder="' + playerFirstName + '"></div>' +
						        				'<label>Nachname</label>'+
						        				'<div class="input-control text"><input type="text" id="playerSecondName" placeholder="' + playerSecondName + '"></div>' +
						        				'<label>E-Mail Adresse</label>' +
						        				'<div class="input-control text"><input type="email" id="email" placeholder="E-Mail Adresse"></div>' +
						        				'<label>Geburtstatum</label>' +
						        				'<div class="input-control text"><input type="date" id="playerBirthdate" placeholder="' + playerBirthdate + '"></div>' +
						        				'<div class="form-actions">' +
						        				'<button id="btnSubmit" type="button" class="button primary" onclick="savePlayerInfos(' + teamID + ',&quot;' + playerFirstName + '&quot;,&quot;' + playerSecondName + '&quot;,&quot;' + email + '&quot;,&quot;' + playerBirthdate + '&quot;,' + int_PlayerID + ', ' + userID + ')">Speichern</button>&nbsp;'+
						        				'<button class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
						        				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
						        				'<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>' + 
						        				'</div>'+
						        				'</form>';
						    	var int_width = 300;
						    	// Dialog erstellen
						    	createDialog(str_title, str_content, int_width);
						    	$('#playerBirthdate').val(playerBirthdate);
						    	// Placeholder setzen
						    	if (email != ""){
						    		$('#email').attr("placeholder", email);
						    		$('#email').attr("disabled", "disabled");
						    	}
						    	// Fokus setzen
						    	$('#playerFirstName').focus();
						    	$('#teamID').val(teamID);
		    	    	    }
		    	    	});
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
	    		    	// Userdaten abfragen
		    	    	$.ajax({
		    	    	    url: "../inc/ajaxTest.php?class=db&function=getUserByID&id=" + userID,
		    	    	    success: function(json_user) {
		    	    	    	var arr_user = JSON.parse(json_user);
		    	    	    	var email = arr_user['userEmail'];
						    	// Variablen für Dialog deklarieren
						    	var str_title = 'Spieler bearbeiten';
						    	var str_content = 	'<form class="fg-black">' +
						    					'<label>Mannschaft*</label>' + str_filler +
						    					'<label>Vorname</label>' +
						    					'<div class="input-control text"><input type="text" id="playerFirstName" placeholder="' + playerFirstName + '"></div>' +
						        				'<label>Nachname</label>'+
						        				'<div class="input-control text"><input type="text" id="playerSecondName" placeholder="' + playerSecondName + '"></div>' +
						        				'<label>E-Mail Adresse</label>' +
						        				'<div class="input-control text"><input type="email" id="email" placeholder="E-Mail Adresse"></div>' +
						        				'<label>Geburtstatum</label>' +
						        				'<div class="input-control text"><input type="date" id="playerBirthdate" placeholder="' + playerBirthdate + '"></div>' +
						        				'<div class="form-actions">' +
						        				'<button id="btnSubmit" type="button" class="button primary" onclick="savePlayerInfos(' + teamID + ',&quot;' + playerFirstName + '&quot;,&quot;' + playerSecondName + '&quot;,&quot;' + email + '&quot;,&quot;' + playerBirthdate + '&quot;,' + int_PlayerID + ', ' + userID + ')">Speichern</button>&nbsp;'+
						        				'<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
						        				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
						        				'<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>' + 
						        				'</div>'+
						        				'</form>';
						    	var int_width = 300;
						    	// Dialog erstellen
						    	createDialog(str_title, str_content, int_width);
						    	$('#playerBirthdate').val(playerBirthdate);
						    	// Placeholder setzen
						    	if (email != ""){
						    		$('#email').attr("placeholder", email);
						    	}
						    	// Fokus setzen
						    	$('#playerFirstName').focus();
						    	$('#teamID').val(teamID);
		    	    	    }
		    	    	});
	    		    }
	    		});
	    	}
	    }
	});
}

// Spieler speichern
function savePlayerInfos(teamID, firstName, secondName, email, birthDate, playerID, userID) {
	$("#alert").hide();
	$("#wait").show();
	$("#btnSubmit").hide();
	// Datenfelder zuweisen
	var playerTeamID = $('#teamID').val();
	// Wenn Mannschaft ausgewählt
	if (playerTeamID != 0){
		var playerFirstName = $('#playerFirstName').val();
		if (playerFirstName == "" && $('#playerFirstName').attr('placeholder') == firstName){
			playerFirstName = $('#playerFirstName').attr('placeholder');
		}
		var playerSecondName = $('#playerSecondName').val();
		if (playerSecondName == "" && $('#playerSecondName').attr('placeholder') == secondName){
			playerSecondName = $('#playerSecondName').attr('placeholder');
		}
		var playerEmail = $('#email').val();
		if (playerEmail == "" && $('#email').attr('placeholder') == email){
			playerEmail = $('#email').attr('placeholder');
		}
		var playerBirthdate = $('#playerBirthdate').val();
		if (playerBirthdate == "" && $('#playerBirthdate').attr('placeholder') == birthDate){
			playerBirthdate = $('#playerBirthdate').attr('placeholder');
		}
		// Wenn sich nichts geändert hat
		if (playerTeamID == teamID && playerFirstName == firstName && playerSecondName == secondName && playerEmail == email && playerBirthdate == birthDate){
			$("#wait").hide();
			$("#btnSubmit").show();
			// Meldung anzeigen
			$("#alert").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Die Daten wurden gespeichert!</div></div>");
			$("#alert").show();
			// Fokus setzen
	    	$('#playerFirstName').focus();
		}
		else{
			// Dateneintrag per Ajax
			$.ajax({
				type: "POST",
				url: '../functions/savePlayer.php',
				cache: false,
				data: {teamID: playerTeamID, vorname: playerFirstName, nachname: playerSecondName, email: playerEmail, gebdatum: playerBirthdate, userID: userID},
				success: function(data) {
					// Ladezeichen verbergen
					$("#wait").hide();
					$("#btnSubmit").show();
					if(data == "ok"){
						// Meldung anzeigen
						$('#alert').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Die Änderungen wurden gespeichert!</div></div>');
						$("#alert").show();
						// Felder leeren
						$('#playerFirstName').val("");
						$('#playerSecondName').val("");
						$('#email').val("");
						// Placeholder ändern
						$('#playerFirstName').attr('placeholder', playerFirstName);
						$('#playerSecondName').attr('placeholder', playerSecondName);
						if (playerEmail == ""){
							$('#email').attr('placeholder', "E-Mail Adresse");
						}
						else{
							$('#email').attr('placeholder', playerEmail);
							$('#email').attr("disabled", "disabled");
						}
						$('#playerBirthdate').val(playerBirthdate);
						// Fokus setzen
						$('#playerFirstName').focus();
					}
					else if(data == "emailSchonVorhanden"){
						// Meldung anzeigen
						$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Eine identische E-Mail Adresse existiert bereits.</div></div>");
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
	// Wenn keine Mannschaft ausgewählt wurde
	else{
		// Ladezeichen verbergen
		$("#wait").hide();
		$("#btnSubmit").show();
		// Meldung anzeigen
		$('#alert').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Bitte erstellen Sie zuerst eine Mannschaft</div></div>');
		$("#alert").show();
	}
}

// Spieler löschen
function deletePlayer(int_PlayerID) {
	createDialogDeletePlayer(int_PlayerID);
}

// Dialog erstellen
function createDialogDeletePlayer(int_PlayerID){
	// Variablen für Dialog deklarieren
	var str_title = 'Spieler löschen';
	var str_content = 	'<form class="fg-black">' +
					'<div id="alert" style="display: none; font-size: 14px;"></div>' + 
					'<div id="alertOld" style="font-size: 14px;" class="notice marker-on-top bg-darkRed fg-white"><div><strong>Achtung!</strong><br>Wenn Sie den Spieler löschen, wird das betreffende Benutzerprofil umgehend gelöscht.<br>Von der Löschung ist darüberhinaus sowohl die Mannschaft, die Anwesenheistliste als auch die Aufstellung betroffen.</div></div>' +
    				'<br>' +
    				'<div class="form-actions">' +
    				'<button id="btnSubmit" type="button" class="button primary" onclick="deletePlayerInfos(' + int_PlayerID + ')">Löschen</button>&nbsp;'+
    				'<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
    				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
    				'</div>'+
    				'</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
}

// Spieler löschen
function deletePlayerInfos(int_PlayerID){
	$('#alertOld').hide();
	// Ladezeichen anzeigen
	$("#wait").show();
	$("#btnSubmit").hide();
	// Per Ajax Spieler löschen
	$.ajax({
		type: "POST",
		url: '../functions/deletePlayer.php',
		cache: false,
		data: {playerID: int_PlayerID},
		success: function(data) {
			// Ladezeichen verbergen
			$("#wait").hide();
			$("#btnSubmit").show();
			if(data == "ok"){
				// Meldung anzeigen
				$('#alert').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Der Spieler wurde gelöscht!</div></div>');
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

// Name des Typs
function convertTeamTypeID(int_teamTypeID){
	var teamType = "";
	// TeamID durch Namen ersetzen
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