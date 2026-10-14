<?php include 'funktionen.php';?>
<!-- <div align="center"><b>&ouml;ffentlicher Bereich!</b></div> -->
<!DOCTYPE HTML>
<html>
	<head>
		<!-- Metadaten -->
		<title><?php echo $title;?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="author" content="AUTHOR_NAME"/>
		<meta name="keywords" content="bierspiel, browsergame, onlinespiel"/>
		<link rel="icon" href="/img/bier_icon.png" type="image/ico">
		<!-- Einbinden von css- und js-files -->
		<link href="/css/bootstrap.css" rel="stylesheet"/>
		<link href="/css/bootstrap-responsive.css" rel="stylesheet"/>
		<link href="/css/jvectormap-1.2.2.css" rel="stylesheet"/>
		<script type="text/javascript" src="/js/jquery-1.9.0.js"></script>
		<script type="text/javascript" src="/js/bootstrap.js"></script>
		<script type="text/javascript" src="/js/support.js"></script>
		<script type="text/javascript" src="/js/jvectormap-1.2.2.min.js"></script>
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
	</head>
	<body>
			<div class="navbar">
				<noscript>
					<div align="center" class="alert alert-info">
						<b>Sie haben JavaScript deaktiviert!</b><br>
						Um den vollen Leistungsumfang unseres Browsergames nutzen zu können, benötigen Sie JavaScript.<br>
						Bitte aktivieren Sie JavaScript in Ihren Browsereinstellungen und laden Sie diese Seite neu.<br>
						Vielen Dank!
					</div>
				</noscript>
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	                    	<span class="icon-bar"></span>
	                    	<span class="icon-bar"></span>
	                    	<span class="icon-bar"></span>
	                  	</a>
		                <a style="padding: 25px 20px 10px;"  class="brand pointer" href="/Home"><img style="height: 15px; width: 15px;" src="/img/bier_icon.png" class="img-circle"> Schankimperium</a>
		                <form style="margin-bottom: 5px; font-size: 10px;" class="navbar-form pull-right" action="" onsubmit="alert('Enter')">
          					<input class="span2" type="text" id="loginName" placeholder="Email/Name">
              				<input class="span2" type="password" id="loginPasswort" placeholder="Password">
              				<button onclick="login()" id="btnLogin" type="button" class="btn btn-info">Login</button>
              				<img style="display: none;" id="waitLogin" src="/img/loading.gif"/>
              				<br>
              				<a class="pointer" id="neuesPW" onclick="passwortVergessen()">Passwort vergessen?</a>
              				<a style="margin-left: 85px;" class="pointer" id="neueBestaetigungsmail" onclick="neueBest()">Neue Best&auml;tigungsmail?</a>
            			</form>
		                <ul class="nav pull-right">
			            	<li class="dropdown" id="user">
								<li>
				          			<a style="padding: 25px 15px 25px;" href="/Registrierung" class="pointer">Registrieren</a>
				          		</li>
				          	</li>
				          	<!--
				          	<li class="dropdown" id="user">
								<li>
				          			<a href="/Login" class="pointer">Login</a>
				          		</li>
				          	</li>
				          	-->
				        </ul>
		                <div class="nav-collapse collapse navbar-responsive-collapse">
		                  	<ul class="nav">
		                      	<li class="dropdown">
			                        <a style="padding: 25px 15px 25px;" href="" class="dropdown-toggle pointer" data-toggle="dropdown">Das Spiel <b class="caret"></b></a>
			                        <ul class="dropdown-menu">
			                          	<li><a href="/Aufbau" class="pointer">Der Aufbau</a></li>
			                          	<li><a href="/Screenshots" class="pointer">Screenshots</a></li>
			                        </ul>
		                      	</li>
		                    </ul>
				        </div>
				       	
	                    <!--
	                  	<div class="nav-collapse collapse navbar-responsive-collapse">
	                    	<ul class="nav pull-right">
	                    		<li><a onclick="startLoad('startseite/statistik.php', 'statistik')">Statistik</a></li>
	                      		<li><a onclick="startLoad('startseite/werbung.php', 'werbung')">Werbung</a></li>
	                    	</ul>
	                  	
	                  	-->
	                </div>
				</div>
				<br>
				<!-- Formular neues Passwort und neue Bestmail -->
				<div class="registrieren">
					<div id="alertLogin" style="display: none;" class="alert alert-error"></div>
					<form id="login" class="form-horizontal">
					  	<!-- NEUES PASSWORT FORMULAR -->
					    <div id="formNeuesPW" style="display: none;">
					    	<br>
							<br>
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
				<!-- Hinweis -->
			</div>