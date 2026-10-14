<?php $title="Login"; include 'sessionpruefung.php';

// Wenn User eingeloggt ist
if (isset($_SESSION['nickname'])){
	// Meldung und Abbruch
	echo "Sie sind bereits eingeloggt!";
	include 'footer.php';
	exit;
}

if (isset($_GET["benutzer"])){
	$benutzer = $_GET["benutzer"];
}
else{
	$benutzer = "";
}

?>
<!-- <script src="/js/login.js"></script> -->
<script type="text/javascript">
	function login(){
		// Fehlermeldung und Wartezeichen ausblenden
    	$("#alertLogin").hide();
    	$("#waitLogin").show();
    	// Benutzername und Passwort abfragen
    	inputName = $('#loginName').val();
    	inputPasswort = $('#loginPasswort').val();
    	// Wenn eines der Felder leer ist
    	if (inputName.length == 0 || inputPasswort.length == 0){
        	// Wartezeichen verbergen
        	$("#waitLogin").hide();
        	// Fehlermeldung
			$("#alertLogin").html("<b>Fehler!</b> Bitte f&uuml;llen Sie alle Felder aus!");
			$("#alertLogin").show();
    	}
    	else{
	    	art = "login";
	           $.ajax({
	        	   		type: "POST",
	        	   		url: "validate.php",
	        	   		cache: false,
	        	   		data: { nickname: inputName, passwort: inputPasswort, art: art},
	        	   		success: function(data){
	        	   		$("#waitLogin").hide();
	                    if(data=='nichtAktiviert'){
	                    	$("#alertLogin").html("<strong>Fehler!</strong> Ihr Account wurde noch nicht aktiviert!<br>" +
	                    			"Bitte best&auml;tigen Sie die Willkommens-EMail, die sie nach der Registrierung erhalten haben.");
	                     	$("#alertLogin").show();
	                    }
	                    else if(data=='no'){
	                    	$("#alertLogin").html("<strong>Fehler!</strong> Fehlerhafte Zugangsdaten!<br>Bitte &uuml;berpr&uuml;fen Sie Ihre Eingabe und versuchen Sie es erneut.");
	                    	$("#alertLogin").show();
	                    }
	                    else if(data=='ok'){
		                    // neues Ziel festlegen
	                        document.location='/Firma';
	                    }
	                    else{
	                    	$("#alertLogin").html("<strong>Fehler!</strong> Es ist ein unerwarteter Fehler aufgetreten!<br>" +
	                    			"Bitte kontaktieren sie diesbez&uuml;glich den Support<br><br>Fehler: " + data);
	                    	$("#alertLogin").show();
	                    }
	                }
	            });
	        return false;
    	}
	}

	function passwortVergessen(){
		$('#formNeueBest').slideUp();
		$('#formNeuesPW').slideDown();
	}

	function neueBest(){
		$('#formNeuesPW').slideUp();
		$('#formNeueBest').slideDown();
	}
// Neues Passwort
function neuesPW(){
	// Hinweise entfernen
	$("#alertNeuesPWFehler").hide();
	$("#alertNeuesPWErfolg").hide();
	// Daten aus Formular
	var inputBenutzer = $('#inputBenutzerPasswort').val();
	// Prüfen ob alle ausgefüllt
	if (inputBenutzer.length>0){
		// Ladeanimation
        $("#pwNeuWarten").show();
        var art = "passwort";
		// Ajax-Aufruf
        $.ajax({
			type: "POST",
			url: "datenNeu.php",
			cache: false,
			data: { nickname: inputBenutzer, art: art},
			success: function(data){
				$("#pwNeuWarten").hide();
                if(data=='nichtAlleWerte'){
                	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Bitte alle Felder ausf&uuml;llen!");
                    $("#alertNeuesPWFehler").show();
                }
                else if(data=='nichtExistentEmail'){
                	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Es existiert leider kein Eintrag zu diesem Benutzer in unserer Datenbank!");
                    $("#alertNeuesPWFehler").show();
                }
                else if(data=='nichtExistentBenutzer'){
                	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Es existiert leider kein Eintrag zu diesem Benutzer in unserer Datenbank!");
                    $("#alertNeuesPWFehler").show();
                }
                else if(data=='ok'){
                	$("#alertNeuesPWErfolg").html("<strong>Erfolg!</strong> An deine E-Mail Adresse wurde eine Best&auml;tigungsmail versandt.<br>Nachdem du diese best&auml;tigt hast, wird ein neues Passwort f&uuml;r dich generiert.");
                    $("#alertNeuesPWErfolg").show();
                 	// Button deaktivieren
                    $('#neuesPWBtn').attr('disabled', 'disabled');
                }
                else {
                	$("#alertNeuesPWFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
                			"Fehler: " + data);
                    $("#alertNeuesPWFehler").show();
                }
			}
        });
        return false;
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Bitte geben Sie einen Wert an!");
        $("#alertNeuesPWFehler").show();
	}
}
// Neue Bestätigungsmail
function neueMail(){
	// Hinweise entfernen
	$("#alertNeueBestFehler").hide();
	$("#alertNeueBestErfolg").hide();
	// Daten aus Formular
	var inputBenutzer = $('#inputBenutzerBest').val();
	// Prüfen ob alle ausgefüllt
	if (inputBenutzer.length>0){
		// Ladeanimation
        $("#bestNeuWarten").show();
        var art = "best";
		// Ajax-Aufruf
        $.ajax({
			type: "POST",
			url: "datenNeu.php",
			cache: false,
			data: { nickname: inputBenutzer, art: art},
			success: function(data){
				$("#bestNeuWarten").hide();
                if(data=='nichtAlleWerte'){
                	$("#alertNeueBestFehler").html("<strong>Fehler!</strong> Bitte alle Felder ausf&uuml;llen!");
                    $("#alertNeueBestFehler").show();
                }
                else if(data=='nichtExistentEmail'){
                	$("#alertNeueBestFehler").html("<strong>Fehler!</strong> Es existiert leider kein Eintrag zu diesem Benutzer in unserer Datenbank!");
                    $("#alertNeueBestFehler").show();
                }
                else if(data=='nichtExistentBenutzer'){
                	$("#alertNeueBestFehler").html("<strong>Fehler!</strong> Es existiert leider kein Eintrag zu diesem Benutzer in unserer Datenbank!");
                    $("#alertNeueBestFehler").show();
                }
                else if(data=='schonAktiviert'){
                	$("#alertNeueBestFehler").html("<strong>Fehler!</strong> Sie haben Ihren Account bereits aktiviert!");
                    $("#alertNeueBestFehler").show();
                }
                else if(data=='ok'){
                	$("#alertNeueBestErfolg").html("<strong>Erfolg!</strong> An deine E-Mail Adresse wurde eine Best&auml;tigungsmail versandt.<br>Bitte best&auml;tige diese, um deinen Account zu aktivieren.");
                    $("#alertNeueBestErfolg").show();
                    // Button deaktivieren
                    $('#neueBestBtn').attr('disabled', 'disabled');
                }
                else {
                	$("#alertNeueBestFehler").html("<strong>Es ist ein unerwarteter Fehler aufgetreten!</strong>\nBitte kontaktieren sie diesbez&uuml;glich den Support\n\n" + 
                			"Fehler: " + data);
                    $("#alertNeueBestFehler").show();
                }
			}
        });
        return false;
	}
	// Wenn nicht alle Felder ausgefüllt sind
	else{
		$("#alertNeueBestFehler").html("<strong>Fehler!</strong> Bitte geben Sie einen Wert an!");
        $("#alertNeueBestErfolg").show();
	}
}
</script>
<div class="registrieren">
	<p>
		<br><br><h1>Willkommen zur&uuml;ck!</h1><br><br>
	</p>
	<form id="login" class="form-horizontal">
		<div class="control-group">
	    	<label class="control-label">Benutzer:</label>
	    	<div class="controls">
	      		<input type="text" id="loginName" value="<?php echo $benutzer;?>" placeholder="Email/Name">
	    	</div>
	  	</div>
	  	<div class="control-group">
	    	<label class="control-label">Passwort</label>
	    	<div class="controls">
	      		<input type="password" id="loginPasswort" placeholder="Passwort">
	    	</div>
	  	</div>
		<div id="alertLogin" style="display: none;" class="alert alert-error"></div>
	  	<div class="control-group">
	   		<div class="controls">
	   		<button onclick="login()" id="btnLogin" type="button" class="btn btn-info">Login</button>
	      		<img style="display: none;" id="waitLogin" src="/img/loading.gif"/>
	      		<br><br>
	      		<a class="linkKlein pointer" id="neuesPW" onclick="passwortVergessen()">Passwort vergessen?</a>
	      		<br>
	      		<a class="linkKlein pointer" id="neueBestaetigungsmail" onclick="neueBest()">Neue Best&auml;tigungsmail?</a>
	      	</div>
	  	</div>
	  	<!-- NEUES PASSWORT FORMULAR -->
	    <div id="formNeuesPW" style="display: none;">
	    	<b>Neues Passwort?</b><br><br>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">Benutzer/E-Mail:</label>
					<div class="controls">
						<input type="text" id="inputBenutzerPasswort" placeholder="Benutzer/E-Mail">
					</div>
				</div>
				<div id="alertNeuesPWFehler" style="display: none;" class="alert alert-error"></div>
				<div id="alertNeuesPWErfolg" style="display: none;" class="alert alert-success"></div>
				<img style="display: none;" id="pwNeuWarten" src="/img/loading.gif"/>
				<div class="control-group span3">
					<div class="controls">
						<button type="button" id="neuesPWBtn" class="btn" onclick="neuesPW()">Absenden</button>
					</div>
				</div>
			</form>
		</div>
		<!-- NEUE BESTAETIGUNGSMAIL FORMULAR -->
	    <div id="formNeueBest" style="display: none;">
	    	<b>Neue Best&auml;tigungsmail?</b><br><br>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">Benutzer/E-Mail:</label>
					<div class="controls">
						<input type="text" id="inputBenutzerBest" placeholder="Benutzer/E-Mail">
					</div>
				</div>
				<div id="alertNeueBestFehler" style="display: none;" class="alert alert-error"></div>
				<div id="alertNeueBestErfolg" style="display: none;" class="alert alert-success"></div>
				<img style="display: none;" id="bestNeuWarten" src="/img/loading.gif"/>
				<div class="control-group span3">
					<div class="controls">
						<button type="button" id="neueBestBtn" class="btn" onclick="neueMail()">Absenden</button>
					</div>
				</div>
			</form>
		</div>
	</form>
</div>
<!-- Fokus auf Loginname setzen -->
<script type="text/javascript">
	document.getElementById('loginName').focus();
</script>