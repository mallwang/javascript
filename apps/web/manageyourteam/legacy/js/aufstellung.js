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
	    	if(str_allCoaches == "")
	    		str_filler = '<button style="width: 100%; height: 34px;" class="button bg-gray fg-white" id="coachID" type="button" onclick="$.Dialog.close(); showDialogCreateNewCoach()">Neuen Trainer erstellen</button><br><br>';
	    	// Ansonsten anzeigen aller möglicher Trainer in einem Auswahlfeld
	    	else
	    		str_filler = '<div class="input-control select"><select id="coachID">' + str_allCoaches + '</select></div>';
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
	        				'<button id="btnAbort" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'+
	        				'<img id="wait" src="../../../../img/loading.gif" style="display: none;" />' +
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
		$("#wait").show();
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
					$("#wait").hide();
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
			$("#wait").hide();
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
//	alert("teamType: " + teamType + "\nteamName: " + teamName + "\ncoachID: " + coachID);
}

// Dialog erstellen
function showDialogCreateNewCoach() {
	// Variablen für Dialog deklarieren
	var str_title = 'Neuen Trainer erstellen';
	var str_content = '<form class="fg-black">'
			+ '<div id="alert" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
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
//	alert("vorname: " + vorname + "\nnachname: " + nachname + "\nemail: " + email);
}

// Drucken der Seite
function printdiv(printpage, printpage2){
	var int_teamID = $("#setTeam").val();
	if (int_teamID != 0){
		var mannschaft = $("#setTeam :selected").text();
		var headstr = "<html><head><title></title></head><body>";
		var footstr = "</body>";
		var newstr = document.all.item(printpage).innerHTML;
		var newstr2 = document.all.item(printpage2).innerHTML;
		var oldstr = document.body.innerHTML;
		document.body.innerHTML = headstr + "<div align='center'><h3>Aufstellung " + mannschaft + "</h3></div><div style='border: 5px;'>" + newstr+"</div><br><br><br>"+newstr2+footstr;
		window.print();
		document.body.innerHTML = oldstr;
	}
}

var spielerArray = new Array();
var spielerNamen = new Array();
var spielerAlter = new Array();
var taktik;
var int_teamID;
var taktik_positionen = new Array();
var player = 0;

// Festlegen der Positionen der jeweiligen Taktik
//'4-3-3'
taktik_positionen[1] = new Array(0,1,0,1,1,1,1,0,1,0,1,1,1,1,1,0,1,1,1,1,1);
//'4-4-2'
taktik_positionen[2] = new Array(0,1,0,1,1,1,1,0,1,1,1,1,1,0,1,0,1,1,1,1,1);
//'3-4-3'
taktik_positionen[3] = new Array(0,1,1,0,1,1,0,0,1,1,1,1,1,1,1,0,1,1,1,1,1);
//'4-5-1'
taktik_positionen[4] = new Array(0,1,0,1,1,1,1,1,1,1,1,1,0,1,0,0,1,1,1,1,1);
//'5-3-2'
taktik_positionen[5] = new Array(0,1,1,1,1,1,1,0,1,0,1,1,1,0,1,0,1,1,1,1,1);
//'5-4-1'
taktik_positionen[6] = new Array(0,1,1,1,1,1,1,0,1,1,1,1,0,1,0,0,1,1,1,1,1);
//'3-5-2'
taktik_positionen[7] = new Array(0,1,1,0,1,1,0,1,1,1,1,1,1,0,1,0,1,1,1,1,1);
//'5-3-2'
taktik_positionen[8] = new Array(0,1,0,1,1,1,1,1,1,0,1,1,1,0,1,0,1,1,1,1,1);
//'5-4-1'
taktik_positionen[9] = new Array(0,1,0,1,1,1,1,1,1,1,1,1,0,1,0,0,1,1,1,1,1);

//'1',  '2', '3',  '4',  '5', '6', '7', '8', '9','10','11','12','13','14'
//'TW','LIB','LV','LMD','RMD','RV','VS','LM','DM','ZM','RM','LS','MS','RS'
						// '0', '1',  '2', '3',  '4',  '5', '6', '7', '8', '9','10','11','12','13','14',     '15',     '16',     '17',     '18',     '19',     '20'
var positions = new Array ("-","TW","LIB","LV","LMD","RMD","RV","VS","LM","DM","ZM","RM","LS","MS","RS","Reserve","Reserve","Reserve","Reserve","Reserve","Reserve");

var fieldPositionNumbers = new Array (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
var reservesPositionNumbers = new Array (16,17,18,19,20);

$(document).ready(function () {
	
	// Wenn Betrachter ein Spieler ist, dann das Ziehen disablen
	if ($("#setTeam").val() == -1){
		player = 1;
	}
	
	// Bei Änderung des Mannschaft-Auswahlfeldes wird das Spielfeld neu gerendert
	$("#setTeam").change(function(){
		// Wartezeichen anzeigen
		$('#waitLoad').show();
		int_teamID = $("#setTeam").val();
		// Ajax Aufruf für die Taktik	
		$.ajax({
			type: 'POST',
			url: 'functions/getAufstellung.php',
			cache: false,
			data: {action: "getTaktik", teamID: int_teamID},
			success: function(data) {
				if (data == ""){
					taktik = 1;
					clearPositionDivs();
					definePlayerList();
					// Wartezeichen verbergen
					$('#waitLoad').hide();
				}
				else{
					taktik = data;
					$("head").append('<link rel="stylesheet" href="../css/taktik_'+taktik+'.css" type="text/css">');
					$("#setTaktik").val(taktik);
	//				clearPositionDivs();
					buildContent(int_teamID);
				}
			}
		});
		$('#team').html("");
	});
	
	// Bei Änderung der Taktik über das Auswahlfeld wird das Spielfeld neu gerendert
	$("#setTaktik").change(function(){
		// Wartezeichen anzeigen
		$('#waitLoad').show();
		taktik = $("#setTaktik").val();
		$('#team').html("");
		clearPositionDivs();
		buildContent(int_teamID);
		// stylesheets austauschen bei selektieren der taktik (selectbox)
		$('head > link').filter(':last').attr('href', '../css/taktik_'+taktik+'.css');
	});
	
	$("#save").click(function() {
		int_teamID = $("#setTeam").val();
		saveLineup(int_teamID);
	});

		// Funktion zur Erstellung der Divs für die Aufstellung und die Reserve
		function createPositionDivs(){
		// Bestehende Divs entfernen
		clearPositionDivs();

		var count = 0;
		for(var x=0; x < taktik_positionen[taktik].length; x++){
			// Wenn die aktuelle Aufstellung die Position enthält
			if(taktik_positionen[taktik][x] == 1){
				// Erstellen der Ersatzbank (leere Felder mit "Reserve")
				if(x == 16 || x == 17 || x == 18 || x == 19 || x == 20){
					$("#teamReserve").append("<div class='posDiv"+count+"' id='Pos"+x+"'></div>");
					$("#Pos"+x).append(
							"<div class='droppedPlayer'>" +
								"<div style='margin-left: 2px; margin-top: 0px;'>" +
									"<div style='position:absolute; margin-top: -1px'>" +
										"<span style='color:#A8A789; font-size:11px; font-weight:normal;'>"+(positions[x])+"</span>" +
									"</div>" +
								"</div>" +
							"</div>");
					$("#Pos"+x).children().css("height", 12);
					$("#Pos"+x).children().css("width", 233);
				}
				// Erstellen der Aufstellung (leere Felder mit Bezeichnung der Position)
				else{
					$("#aufstellung").append("<div class='posDiv"+count+"' id='Pos"+x+"'></div>");
					$("#Pos"+x).append(
							"<div class='droppedPlayer'>" +
								"<div style='margin-left: 4px; margin-top: 2px;'>" +
									"<div style='position:absolute; margin-left:35px; margin-top:19px'>" +
										"<span style='color:#00316; font-size:11px; font-weight:normal;'>"+(positions[x])+"</span>" +
									"</div>" +
								"</div>" +
							"</div>");
				}
				count++;
			}
		}
	}
		
	// Alle Positionen entfernen
	function clearPositionDivs(){
		for(var x=0; x < taktik_positionen[taktik].length; x++){
			$("#Pos"+x).remove();
		}
	}

	// Spielernamen beschneiden wenn zu lang
	function cutPlayerNames(name, letters){
		if(name.length > (letters + 1))
			name = name.substring(0,letters)+"..";
		else
			name = name;
		return name;
	}

	// Laden der aktuellen Aufstellung
	function aktAufstellungLaden(){
		var counter=0;
		for(var i in spielerArray){
			// Wenn der Spieler aufgestellt ist
			if(spielerArray[i][3] > 0){
				// Hintergrund wechseln für Spieler auf dem Spielfeld
				if(spielerArray[i][3] != 16 && spielerArray[i][3] != 17 && spielerArray[i][3] != 18 && spielerArray[i][3] != 19 && spielerArray[i][3] != 20){
					// Wenn Torwart
					if(spielerArray[i][3] == 1)
						$("#Pos"+spielerArray[i][3]).css("background-image","url(../img/container_tw.png)");
					// Wenn Feldspieler
					else if(spielerArray[i][3] != 1)
						$("#Pos"+spielerArray[i][3]).css("background-image","url(../img/container.png)");
				}
				// Container leeren
				$("#Pos"+spielerArray[i][3]).html("");
				// Spielername abfragen
				var playerName = spielerArray[i][1];
				playerName = cutPlayerNames(playerName, 18);
				// Alle aufgestellte Spieler auf Spielfeld anzeigen
				if(spielerArray[i][3] != 16 && spielerArray[i][3] != 17 && spielerArray[i][3] != 18 && spielerArray[i][3] != 19 && spielerArray[i][3] != 20){
					// Wenn es der Torwart ist
					if(spielerArray[i][3] == 1){
						// Vorheriges erstelltes Feld durch gefülltes Feld ersetzen
						$("#Pos"+spielerArray[i][3]).append(
								"<div class='droppedPlayer'>" +
									"<div style='margin-left: 6px; margin-top: -2px;'>" +
										"<div class='trikot'>" +
											"<img src='../img/trikot_tw.png'>" +
										"</div>" +
										"<div class='container_name_pos' title='"+spielerArray[i][1]+"'>" +
											"<span style='font-size:9px;'>"+playerName+" ("+spielerArray[i][2]+")</span>" +
										"</div>" +
									"</div>");
					}
					// Wenn es ein Feldspieler ist
					else if(spielerArray[i][3] != 1){
							// Vorheriges erstelltes Feld durch gefülltes Feld ersetzen
							$("#Pos"+spielerArray[i][3]).append(
									"<div class='droppedPlayer'>" +
										"<div style='margin-left: 6px; margin-top: -2px;'>" +
											"<div class='trikot'>" +
												"<img src='../img/trikot_feld.png'>" +
											"</div>" +
											"<div class='container_name_pos' title='"+spielerArray[i][1]+"'>" +
												"<span style='font-size:9px;'>"+playerName+" ("+spielerArray[i][2]+")</span>" +
											"</div>" +
										"</div>");
					}
				}
				// Spieler auf der Reservebank anzeigen
				else{
					if(spielerArray[i][3] != 1){
						// Vorheriges erstelltes Feld durch gefülltes Feld ersetzen
						$("#Pos"+spielerArray[i][3]).append(
								"<div class='droppedPlayer'>" +
									"<div style='position: absolute; height: 15px; font-style: verdana; font-size: 11px;'>" +
										"<div> "+spielerArray[i][1]+" (" + spielerArray[i][2] + ")</div>" +
									"</div>");
						$("#Pos"+spielerArray[i][3]).children().css("height", 12);
						$("#Pos"+spielerArray[i][3]).children().css("width", 233);
					}
				}
				// Allen besetzten Positionen eine ID zuweisen mit der ID des Spielers
				$("#Pos"+spielerArray[i][3]).children().attr("id","posDivposition_"+spielerArray[i][0]);
				// Wenn es die Pos des Spielers in Aufstellung gibt und er auch aufgestellt ist,
				// wird er aus dem Repository ausgeblendet
				if(taktik_positionen[taktik][spielerArray[i][3]] == 1 && spielerArray[i][3] > 0){
						$("#playerID_"+spielerArray[i][0]).hide();
						$("#playerID_"+spielerArray[i][0]).next('br').replaceWith("");
				}
				counter++
			}
		}
		// Wartezeichen verbergen
		$('#waitLoad').hide();
		// Größe des Repositorys neu laden
		definePlayerList();
		// Anzeige der aktuellen Spielerzahl im Team (Feld + Reserve)
		$("#anzAktSpieler").text("");
		$("#anzAktSpieler").html(counter+" Spieler");
	}
	
	// Aufstellung speichern
	function saveLineup(int_teamID){
		if (int_teamID != 0 && int_teamID != undefined){
			// Meldung verbergen
			$("#alertSave").hide();
			// Button verbergen
			$("#save").hide();
			// Wartezeichen anzeigen
			$("#waitSave").show();
			// Nur die Spieler übergeben, die auch aufgestellt bzw. in der Reserve sind 
			var aktiveSpielerString = "";
			for(var i=1; i<=20; i++){
				var aufgestellt = $("#Pos"+i+"").children().attr("id");
				if(aufgestellt){
					var aktivePlayerId = aufgestellt.replace(/posDivposition_/, "");
					aktiveSpielerString = aktiveSpielerString.concat(aktivePlayerId+"->"+i+"||");
				}
			}
			var aktiveSpielerStringNeu = aktiveSpielerString.substr(0, aktiveSpielerString.length-2);
			// per Ajax eintragen
			$.ajax({
				type: "POST",
				url: 'functions/saveLineup.php',
				cache: false,
				data: {aktiveSpieler: aktiveSpielerStringNeu, taktik: taktik, teamID: int_teamID},
				success: function(data) {
					// Button verbergen
					$("#save").show();
					// Wartezeichen anzeigen
					$("#waitSave").hide();
					if(data == "ok"){
						// Meldung anzeigen
						$("#alertSave").html("<div class='notice marker-on-top bg-darkGreen fg-white'><div><strong>Erfolg!</strong> Die Aufstellung wurde gespeichert.</div></div>");
						$("#alertSave").show();
					}
					else{
						// Meldung anzeigen
						$("#alertSave").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Die Aufstellung konnte nicht gespeichert werden!<br>Fehler: " + data + "</div></div>");
						$("#alertSave").show();
					}
				}
			});
		}
	}

	// Spieler-ID abfragen bei Übergabe des id-Strings
	function getPlayerId(idString){
		var playerData = idString;
			playerData = playerData.replace(/posDivposition_/, "");
			playerData = playerData.replace(/playerID_/, "");
			playerData = playerData.split("_");
		var playerID   = playerData[0];
		return playerID;
	}

	// Höhe der Team-Repository-Liste nach Anzahl der Spieler definieren
	function definePlayerList(){
		var height = 0;
		height = (countPlayersInList() * 20);
		$(".team").css({'height': 0});
		$(".team").css({'height': height});
		// bei weniger als 3 spielern die spielerliste auf 70px hoehe halten
		if(countPlayersInList() <= 2) $(".team").css({'height': 70});
	}

	// Spieler im Repository zählen
	function countPlayersInList(){
		var counter = 0;
		var css = '';
		$(".player").each(function(){
			css = $(this).css("display");
			if(css == 'block') counter++;
		});
		return counter;
	}
	
	// Alter berechnen
	function getAge(dateString) {
	    var today = new Date();
	    var birthDate = new Date(dateString);
	    var age = today.getFullYear() - birthDate.getFullYear();
	    var m = today.getMonth() - birthDate.getMonth();
	    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
	        age--;
	    }
	    return age;
	}

	// Erstellen der gesamten Seite
	function buildContent(int_teamID){
		// Wenn gewählte Mannschaft != 0 ist
		if (int_teamID != 0){
			// Ajax Aufruf für die Aufstellung	
			$.ajax({
				type: 'POST',
				url: 'functions/getAufstellung.php',
				cache: false,
				data: {action: "getLineup", teamID: int_teamID},
				success: function(json_team) {
					var arr_team = JSON.parse(json_team);
					// Schleifendurchlauf
	    	    	for (var i = 0; i < arr_team.length; i++) {
	    	    		// Alter berechnen
	    	    		var playerAlter = getAge(arr_team[i]["playerBirthdate"]);
	    	    		spielerArray[i] = new Array(arr_team[i]["playerID"], arr_team[i]["playerFirstName"] + " " + arr_team[i]["playerSecondName"], playerAlter, arr_team[i]["positionID"]);
	    			}
	    			var breaker = "";
	    			// Erstellen des Team-Repositorys
	    			$(".player").remove();
	    			var countReserve = 0;
	    			for(i=0; i < spielerArray.length; i++){
	    				spielerNamen[""+spielerArray[i][0]] = spielerArray[i][1];
	    				spielerAlter[""+spielerArray[i][0]] = spielerArray[i][2];
	    				var playerName = cutPlayerNames(spielerArray[i][1], 20);
	    				//alert(playerName);
	    				// Alle Spieler auf die Teamliste setzen
	    				$("#team").append(""+breaker+
	    						"<div class='player' id='playerID_"+spielerArray[i][0]+"'>" +
	    							"<div> "+spielerArray[i][1] + " (" + spielerArray[i][2]+")</div>" +
	    						"</div>");
	    				countReserve++;
	    				breaker = "<br>";
	    			}
	    			// Erstellen des Spielfeldes
	    			createPositionDivs();
	    			// Laden der aktuellen Aufstellung in das erstellte Spielfeld
	    			aktAufstellungLaden();
	    			// Ziehen ermöglichen
	    			$(".droppedPlayer").draggable({
	    				helper: 'clone',
	    				ghosting: true,
	    				opacity: 0.4,
	    				zIndex: 80,
	    				fx: 100
	    			});
	    			// Ziehen ermöglichen
	    			$(".player").draggable({
	    				helper: 'clone',
	    				ghosting: true,
	    				opacity: 0.4,
	    				zIndex: 80,
	    				fx: 100
	    			});
	    			// Nicht belegte Positionen nicht ziehbar machen
	    			// Entfernen bei allen Positionen
	    			$(".droppedPlayer").draggable("disable");
	    			// Wenn ein Spieler betrachtet -> draggen disablen
	    			if (player == 1)
	    				$('.player').draggable("disable");
	    			// Für jede belegte Position ziehen ermöglichen
	    			for(i=0; i<=20; i++){
	    				if($("#Pos"+i).children().attr("id") != "" && $("#Pos"+i).children().attr("id") != undefined && player == 0){
	    					$("#Pos"+i).children().draggable("enable");
	    				}
	    			}
	    			// Gezogenen Spieler auf eine Position (inkl. Reserve) fallen lassen
	    			$(".droppedPlayer").droppable({
	    				// $(this) = $(".droppedPlayer")
	    				accept: ".droppedPlayer, .player",
	    				activeClass: 'droppable-active',
	    				hoverClass: 'droppable-hover-player-container',
	    				drop: function(ev, ui) {
	    					// ContainerID, auf das ein Spieler gezogen wurde
	    					var containerID = $(this).parent().attr("id");
	    					containerID = containerID.replace(/Pos/, "");
	    					// Wenn der Spieler auf das Spielfeld gesetzt wird
	    					if(containerID != 16 && containerID != 17 && containerID != 18 && containerID != 19 && containerID != 20){
	    						// Wenn es der Torwart ist
	    						if(containerID == 1)
	    							// Dunkler Torwarthintergrund setzen
	    							$("#Pos"+containerID).css("background-image","url(../img/container_tw.png)");
	    						// Wenn es ein Spieler ist
	    						else
	    							// Dunklen Spielerhintergrund setzen
	    							$("#Pos"+containerID).css("background-image","url(../img/container.png)");
	    					}
	    					// Ziehen dieses Elementes ermöglichen
	    					if (player == 0)
	    						$(this).draggable("enable");
	    					// Handling für das Ziehen eines Spielers aus dem Repository ins Spielfeld oder Reserve
	    					if( $(ui.draggable).hasClass("player") ){
	    						// Neue SpielerID abfragen
	    						var playerID = getPlayerId($(ui.draggable).attr("id"));
	    						// Falls die Position schon besetzt ist -> Spieler austauschen
	    						if($(this).attr("id") != "" && $(this).attr("id") != undefined){
	    							// Alte SpielerID abfragen
	    							var oldPlayerID = $(this).attr("id");
	    							oldPlayerID = oldPlayerID.replace(/posDivposition_/, "");
	    							// Alten Spieler wieder im Repository anzeigen
	    							$("#"+"playerID_"+oldPlayerID+"").show();
	    							$("#"+"playerID_"+oldPlayerID+"").after("<br>");
	    							// Größe des Repositorys neu laden
	    							definePlayerList();
	    						}
	    						// Div des "droppedPlayers" leeren
	    						$(this).html("");
	    						// Neuen Spielernamen erhalten
	    						playerName = cutPlayerNames(spielerNamen[playerID],18);
	    						// Wenn das Ziel des Zuges der Torwart-Container ist
	    						if(containerID == 1){
	    							// Inhalt des neuen Divs neu setzen mit Namen und Alter
	    							var htmltable = 
	    								"<div style='margin-left: 6px; margin-top: -2px'>" +
	    									"<div class='trikot'>" +
	    										"<img src='../img/trikot_tw.png'>" +
	    									"</div>" +
	    									"<div class='container_name_pos' title='"+spielerNamen[playerID]+"'>" +
	    										"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerID]+")</span>" +
	    									"</div>" +
	    								"</div>";
	    						}
	    						// Wenn das Ziel des Zuges nicht der Torwart ist
	    						else{
	    							// Wenn das Ziel des Zuges die Reserve ist
	    							if(containerID == 16 || containerID == 17 || containerID == 18 || containerID == 19 || containerID == 20){
	    								// Inhalt des neuen Divs neu setzen mit Namen und Alter
	    								htmltable = "<div style='position: absolute; width: 235px; height: 15px; font-style: verdana; font-size: 11px'>" +
	    												"<div style='margin-left:2px; float: left;'> "+playerName+" ("+spielerAlter[playerID]+")</div>" +
	    											"</div>";
	    							}
	    							// Wenn das Ziel des Zuges die Aufstellung ist
	    							else{
	    								// Inhalt des neuen Divs neu setzen mit Namen und Alter
	    								htmltable = "<div style='margin-left: 6px; margin-top: -2px'>" +
	    												"<div class='trikot'>" +
	    													"<img src='../img/trikot_feld.png'>" +
	    												"</div>" +
	    												"<div class='container_name_pos' title='"+spielerNamen[playerID]+"'>" +
	    													"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerID]+")</span>" +
	    												"</div>" +
	    											"</div>";
	    							}
	    						}
	    						// Inhalt des neuen Divs hinzufügen
	    						$(this).append(htmltable);
	    						// Neue ID des Divs setzen, auf das abgelegt wurde ($(this) = $(".droppedPlayer"))
	    						$(this).attr("id","posDivposition_"+playerID);
	    						// Größe des Repositorys neu laden
	    						definePlayerList();
	    						// Spieler aus dem Repository ausblenden
	    						$(ui.draggable).hide();
	    						$(ui.draggable).next('br').replaceWith("");
	    						// Größe des Repositorys neu laden
	    						definePlayerList();
	    					}
	    					// Handling für das Ziehen eines Spielers, der sich auf dem Spielfeld befindet
	    					else{
	    						// Neue SpielerID abfragen
	    						playerID = getPlayerId($(ui.draggable).attr("id"));
	    						// ContainerID, die gezogen wurde
	    						var lastContainerID = $(ui.draggable).parent().attr("id");
	    						lastContainerID = lastContainerID.replace(/Pos/, "");
	    						// Falls die Position schon besetzt ist -> Spieler austauschen
	    						if($(this).attr("id") != "" && $(this).attr("id") != undefined){
	    							// Alte SpielerID abfragen
	    							var playerIdLast	= getPlayerId($(this).attr("id"));
	    							// Tauschen der Positionen
	    							$(ui.draggable).html("");
	    							$(ui.draggable).attr("id","");
	    							$(ui.draggable).attr("id","posDivposition_"+playerIdLast);
	    							playerName = cutPlayerNames(spielerNamen[playerIdLast],18);
	    							// Wenn der gezogene Container der des Torwarts war
	    							if(lastContainerID == 1){
	    								$(ui.draggable).append(
	    										"<div style='margin-left: 6px; margin-top: -2px'>" +
	    											"<div class='trikot'>" +
	    												"<img src='../img/trikot_tw.png'>" +
	    											"</div>" +
	    											"<div class='container_name_pos' title='"+spielerNamen[playerIdLast]+"'>" +
	    												"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerIdLast]+")</span>" +
	    											"</div>" +
	    										"</div>");
	    							}
	    							// Wenn der gezogene Container nicht der des Torwarts war
	    							else{
	    								// Wenn der gezogene Container einer der Reserve war
	    								if((lastContainerID == 16 || lastContainerID == 17 || lastContainerID == 18 || lastContainerID == 19 || lastContainerID == 20) ){
	    									$(ui.draggable).append(
	    											"<div style='position: absolute; width: 235px; height: 15px; font-style: verdana; font-size: 11px'>" +
	    												"<div style='float: left;'> "+spielerNamen[playerIdLast]+" ("+spielerAlter[playerIdLast]+")</div>" +
	    											"</div>");
	    								}
	    								// Wenn der gezogene Container der eines Feldspielers war
	    								else{
	    									$("#Pos"+lastContainerID).css("background-image","url(../img/container.png)");
	    									$(ui.draggable).append(
	    											"<div style='margin-left: 6px; margin-top: -2px'>" +
	    												"<div class='trikot'>" +
	    													"<img src='../img/trikot_feld.png'>" +
	    												"</div>" +
	    												"<div class='container_name_pos' title='"+spielerNamen[playerIdLast]+"'>" +
	    													"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerIdLast]+")</span>" +
	    												"</div>" +
	    											"</div>");
	    								}
	    							}
	    							// Größe des Repositorys neu laden
	    							definePlayerList();
	    							// Abgelegten Container bearbeiten
	    							$(this).html("");
	    							$(this).attr("id","");
	    							$(this).attr("id","posDivposition_"+playerID);
	    							playerName = cutPlayerNames(spielerNamen[playerID],18);
	    							// Wenn der abgelegte Container der des Torwarts war
	    							if(containerID == 1){
	    								$(this).append(
	    										"<div style='margin-left: 6px; margin-top: -2px'>" +
	    											"<div class='trikot'>" +
	    												"<img src='../img/trikot_tw.png'>" +
	    											"</div>" +
	    											"<div class='container_name_pos' title='"+spielerNamen[playerID]+"'>" +
	    												"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerID]+")</span>" +
	    											"</div>" +
	    										"</div>");
	    							}
	    							// Wenn der abgelegte Container nicht der des Torwarts war
	    							else{
	    								// Wenn der gezogene Container einer der Reserve war
	    								if( (containerID == 16 || containerID == 17 || containerID == 18 || containerID == 19 || containerID == 20) ){
	    									$(this).append(
	    											"<div style='position: absolute; width: 235px; height: 15px; font-style: verdana; font-size: 11px'>" +
	    												"<div style='float: left;'> "+spielerNamen[playerID]+" ("+spielerAlter[playerID]+")</div>" +
	    											"</div>");
	    								}
	    								// Wenn der gezogene Container der eines Feldspielers war
	    								else{
	    									$("#Pos"+containerID).css("background-image","url(../img/container.png)");
	    									$(this).append(
	    											"<div style='margin-left: 6px; margin-top: -2px'>" +
	    												"<div class='trikot'>" +
	    													"<img src='../img/trikot_feld.png'>" +
	    												"</div>" +
	    												"<div class='container_name_pos' title='"+spielerNamen[playerID]+"'>" +
	    													"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerID]+")</span>" +
	    												"</div>");
	    								}
	    							}
	    							// Größe des Repositorys neu laden
	    							definePlayerList();
	    						}
	    						// Wenn die Position noch nicht besetzt ist
	    						else{
	    							// Alte Positions ID löschen, da kein Spieler mehr im Container
	    							$(ui.draggable).attr("id","");
	    							$(ui.draggable).draggable("disable");
	    							// Standard Container wieder einblenden
	    							if(lastContainerID != 16 && lastContainerID != 17 && lastContainerID != 18 && lastContainerID != 19 && lastContainerID != 20){
	    								// Falls es sich um einen Torwart handelt
	    								if(lastContainerID == 1)
	    									$("#Pos"+lastContainerID).css("background-image","url(../img/container_tw2.png)");
	    								// Falls es sich um einen Feldspieler handelt
	    								else
	    									$("#Pos"+lastContainerID).css("background-image","url(../img/container2.png)");
	    							}
	    							$(ui.draggable).html("");
	    							$(ui.draggable).text("");
	    							if(lastContainerID != 16 && lastContainerID != 17 && lastContainerID != 18 && lastContainerID != 19 && lastContainerID != 20){
	    								$(ui.draggable).append(
	    										"<div class='droppedPlayer'>" +
	    											"<div style='margin-left: 2px; margin-top: 2px;'>" +
	    												"<div style='position:absolute; margin-left:35px; margin-top:18px'>" +
	    														"<span style='color:#00316; font-size:11px; font-weight:normal;'>"+(positions[lastContainerID])+"</span>" +
	    											"</div>" +
	    										"</div>" +
	    									"</div>");
	    							}
		    						else{
		    							$(ui.draggable).append(
		    									"<div style='margin-left: 2px; margin-top: 0px;'>" +
		    										"<div style='position:absolute; margin-top:0px'>" +
		    											"<span style='color:#A8A789; font-size:11px; font-weight:normal;'>"+(positions[lastContainerID])+"</span>" +
		    										"</div>" +
		    									"</div>");
		    						}
		    						// Größe des Repositorys neu laden
		    						definePlayerList();
		    						// Gezogenen Container bearbeiten
		    						$(this).attr("id","posDivposition_"+playerID);
		    						$(this).html("");
		    						$(this).text("");
		    						playerName = cutPlayerNames(spielerNamen[playerID],18);
		    						// Wenn die ContainerID, auf die abgelegt wurde, die des Torwarts war
		    						if(containerID == 1){
		    							$(this).append(
		    									"<div style='margin-left: 6px; margin-top: -2px'>" +
		    										"<div class='trikot'>" +
		    											"<img src='../img/trikot_tw.png'>" +
		    										"</div>" +
		    										"<div class='container_name_pos' title='"+spielerNamen[playerID]+"'>" +
		    											"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerID]+")</span>" +
		    										"</div>");
		    						}
		    						// Wenn der gezogene Container nicht der des Torwarts war
		    						else{
		    							// Wenn der gezogene Container einer der Reserve war
		    							if(containerID == 16 || containerID == 17 || containerID == 18 || containerID == 19 || containerID == 20){
		    								$(this).append(
		    										"<div style='position: absolute; width: 235px; height: 15px; font-style: verdana; font-size: 11px'>" +
		    											"<div style='float: left;'> "+spielerNamen[playerID]+" ("+spielerAlter[playerID]+")</div>" +
		    										"</div>");
		    							}
		    							// Wenn der gezogene Container der eines Feldspielers war
		    							else{
		    								$(this).append(
		    										"<div style='margin-left: 6px; margin-top: -2px'>" +
		    											"<div class='trikot'>" +
		    												"<img src='../img/trikot_feld.png'>" +
		    											"</div>" +
		    											"<div class='container_name_pos' title='"+spielerNamen[playerID]+"'>" +
		    												"<span style='font-size:9px;'>"+playerName+" ("+spielerAlter[playerID]+")</span>" +
		    											"</div>");
		    							}
		    						}
		    						// Größe des Repositorys neu laden
		    						definePlayerList();
	    						}
	    					}
	    					// Größe des Repositorys neu laden
	    					definePlayerList();
	    				}
	    			});
				}
			});
			// Fallen lassen ermöglichen
			$(".team").droppable({
				accept: ".droppedPlayer, .reserveBankContainerDrag",
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover-team-list',
				drop: function(ev, ui) {
					var lastContainerID = $(ui.draggable).parent().attr("id");
					lastContainerID = lastContainerID.replace(/Pos/, "");
					// Wenn es kein Reservespieler ist
					if(lastContainerID != 16 && lastContainerID != 17 && lastContainerID != 18 && lastContainerID != 19 && lastContainerID != 20){
						// Wenn es der Torwart ist
						if(lastContainerID == 1)
							$("#Pos"+lastContainerID).css("background-image","url(../img/container_tw2.png)");
						// Wenn es ein Feldspieler ist
						else
							$("#Pos"+lastContainerID).css("background-image","url(../img/container2.png)");
						$(ui.draggable).text("");
						$(ui.draggable).append(
								"<div class='droppedPlayer'>" +
									"<div style='margin-left: 2px; margin-top: 2px;'>" +
										"<div style='position:absolute; margin-left:35px; margin-top:18px'>" +
											"<span style='color:#00316; font-size:11px; font-weight:normal;'>"+(positions[lastContainerID])+"</span>" +
										"</div>" +
									"</div>" +
								"</div>");
					}
					// Wenn es ein Reservespieler ist
					else{
						$(ui.draggable).text("");
						$(ui.draggable).append(
								"<div style='margin-left: 2px; margin-top: 0px;'>" +
									"<div style='position:absolute; margin-top:0px'>" +
										"<span style='color:#A8A789; font-size:11px; font-weight:normal;'>"+(positions[lastContainerID])+"</span>" +
									"</div>" +
								"</div>");
					}
					// Größe des Repositorys neu laden
					definePlayerList();
					playerID = $(ui.draggable).attr("id");
					playerID = playerID.replace(/posDivposition_/, "");
					// Spieler in der Teamliste anzeigen
					$("#"+"playerID_"+playerID+"").show();
					$("#"+"playerID_"+playerID+"").after("<br>");
					// Größe des Repositorys neu laden
					definePlayerList();
					$(ui.draggable).attr("id","");
					$(ui.draggable).draggable("disable");
				}
			});
		}
	}
});