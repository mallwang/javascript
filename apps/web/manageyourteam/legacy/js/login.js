$(document).ready(function() {
	
	// Nach Absenden des "Login"-Buttons
	$("#login").submit(function() {
		$("#alert").hide();
		$("#logWarten").show();
		$("#btnSubmit").attr("disabled");
		// Datenfelder zuweisen
		var email = $('#email').val();
		var passwort = $('#passwort').val();
		// Ajax-Aufruf
		$.ajax({
			type : "POST",
			url : "../../functions/login.php",
			cache : false,
			data : {email: email, passwort: passwort},
			success : function(data) {
				$("#logWarten").hide();
				$("#btnSubmit").removeAttr("disabled");
				if (data == 'pwFalsch') {
					$('#passwort').select();
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Benutzername oder Passwort falsch.<br><br><br><button class='button inverse' type='button' onclick='showDialogPWVergessen()'>Passwort vergessen?</button></div></div>");
					$("#alert").show();
				} else if (data == 'nichtAktiviert') {
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Ihr Account wurde noch nicht aktiviert!<br>Bitte bestätigen Sie die Willkommens-E-Mail, die sie nach der Registrierung erhalten haben oder fordern Sie diese erneut an.<br><br><br><button class='button inverse' type='button' onclick='showDialogNeueBestMail()'>Neue Bestätigungsmail anfordern?</button></div></div>");
					$("#alert").show();
				} else if (data == 'ok') {
					// Weiterleiten auf den internen Vereinsbereich
					window.location = "/Home";
				} else {
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
					$("#alert").show();
				}
			}
		});
		return false;
	});
	
	// Nach Absenden des "Login"-Buttons
	$("#loginBest").submit(function() {
		$("#alert").hide();
		$("#logWarten").show();
		$("#btnSubmit").attr("disabled");
		// Datenfelder zuweisen
		var email = $('#email').val();
		var passwort = $('#passwort').val();
		// Ajax-Aufruf
		$.ajax({
			type : "POST",
			url : "../../functions/login.php",
			cache : false,
			data : {email: email, passwort: passwort},
			success : function(data) {
				$("#logWarten").hide();
				$("#btnSubmit").removeAttr("disabled");
				if (data == 'pwFalsch') {
					$('#passwort').select();
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Benutzername oder Passwort falsch.<br><br><br><button class='button inverse' type='button' onclick='showDialogPWVergessen()'>Passwort vergessen?</button></div></div>");
					$("#alert").show();
				} else if (data == 'nichtAktiviert') {
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Ihr Account wurde noch nicht aktiviert!<br>Bitte bestätigen Sie die Willkommens-E-Mail, die sie nach der Registrierung erhalten haben oder fordern Sie diese erneut an.<br><br><br><button class='button inverse' type='button' onclick='showDialogNeueBestMail()'>Neue Bestätigungsmail anfordern?</button></div></div>");
					$("#alert").show();
				} else if (data == 'ok') {
					// Weiterleiten auf den internen Vereinsbereich
					window.location = "/Home";
				} else {
					$("#alert").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>Fehler: " + data + "</div></div>");
					$("#alert").show();
				}
			}
		});
		return false;
	});
});

// Dialog erstellen
function showDialogPWVergessen() {
	// Variablen für Dialog deklarieren
	var str_title = 'Neues Passwort anfordern';
	var str_content = '<form class="fg-black">'
			+ '<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
			+ '<label>E-Mail Adresse*</label>'
			+ '<div class="input-control text"><input type="email" id="emailNeuesPW" placeholder="E-Mail Adresse" autofocus="autofocus" required="required"></div>'
			+ '<h6>* Pflichtfeld</h6>'
			+ '<button id="btnSubmit1" type="button" class="button primary" onclick="sendNewPassword()">Anfordern</button>'
			+ '<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
			+ '<img id="wait1" src="../../../../img/loading.gif" style="display: none;" />'
			+ '</div>' + '</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width)
	$('#emailNeuesPW').focus();
}

// Dialog erstellen
function showDialogNeueBestMail() {
	// Variablen für Dialog deklarieren
	var str_title = 'Neue Bestätigungsmail anfordern';
	var str_content = '<form class="fg-black">'
			+ '<div id="alert1" style="display: none; font-size: 14px; margin-top: 8px;"></div>'
			+ '<label>E-Mail Adresse*</label>'
			+ '<div class="input-control text"><input type="email" id="emailNeueBest" placeholder="E-Mail Adresse" required="required"></div>'
			+ '<h6>* Pflichtfeld</h6>'
			+ '<button id="btnSubmit1" type="button" class="button primary" onclick="sendNewMail()">Anfordern</button>'
			+ '<button id="btnAbort1" class="button place-right" type="button" onclick="$.Dialog.close()">Abbrechen</button>'
			+ '<img id="wait1" src="../../../../img/loading.gif" style="display: none;" />'
			+ '</div>' + '</form>';
	var int_width = 300;
	// Dialog erstellen
	createDialog(str_title, str_content, int_width);
	$('#emailNeueBest').focus();
}

// Neues Passwort
function sendNewPassword() {
	$('#alert1').hide();
	$("#btnSubmit1").hide();
	$("#wait1").show();
	// E-Mail Adresse abfragen
	var str_email = $('#emailNeuesPW').val();
	// Ajax-Aufruf
	$.ajax({
		type: "POST",
		url: "../../../../functions/newPW.php",
		cache: false,
		data: {
			email: str_email},
			success: function(data){
				$("#btnSubmit1").show();
				$("#wait1").hide();
				if(data=='ok'){
					// Meldung anzeigen
					$('#alert1').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Ein neues Passwort wurde an ' + str_email + ' versandt!</div></div>');
					$("#alert1").show();
					// Button ändern
					$("#btnSubmit1").hide();
					$("#btnAbort1").attr("class", "button place-right success");
					$("#btnAbort1").html("Fertig");
				}
				else if (data=='emailExistiertNicht'){
					// Meldung anzeigen
					$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Diese E-Mail Adresse existiert nicht.</div></div>");
					$("#alert1").show();
				}
				else{
					// Meldung anzeigen
					$('#alert1').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
					$("#alert1").show();
				}
			}
	});
}

// Neue Bestätigungsmail
function sendNewMail() {
	$('#alert1').hide();
	$("#btnSubmit1").hide();
	$("#wait1").show();
	// E-Mail Adresse abfragen
	var str_email = $('#emailNeueBest').val();
	// Ajax-Aufruf
	$.ajax({
		type: "POST",
		url: "../../../../functions/newBestmail.php",
		cache: false,
		data: {
			email: str_email},
			success: function(data){
				$("#btnSubmit1").show();
				$("#wait1").hide();
				if(data=='ok'){
					// Meldung anzeigen
					$('#alert1').html('<div class="notice marker-on-top bg-darkGreen fg-white"><div><strong>Erfolg!</strong> Eine neue Bestätigungsmail wurde an ' + str_email + ' versandt!</div></div>');
					$("#alert1").show();
					// Button ändern
					$("#alert1").hide();
					$("#btnSubmit1").hide();
					$("#btnAbort1").attr("class", "button place-right success");
					$("#btnAbort1").html("Fertig");
				}
				else if (data=='emailExistiertNicht'){
					// Meldung anzeigen
					$("#alert1").html("<div class='notice marker-on-top bg-darkRed fg-white'><div><strong>Fehler!</strong> Diese E-Mail Adresse existiert nicht.</div></div>");
					$("#alert1").show();
				}
				else{
					// Meldung anzeigen
					$('#alert1').html('<div class="notice marker-on-top bg-darkRed fg-white"><div><strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten.\nFehler: ' + data + '</div></div>');
					$("#alert1").show();
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