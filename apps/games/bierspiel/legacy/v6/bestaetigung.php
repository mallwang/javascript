<?php $title="Bestätigung"; include '../sessionpruefung.php';

// Wenn User eingeloggt ist
if (isset($_SESSION['nickname'])){
	// Meldung und Abbruch
	echo "Sie haben ihren Account bereits aktiviert!";
	include 'footer.php';
	exit;
}

if (isset($_GET["benutzer"])){
	$benutzer = $_GET["benutzer"];
}
else{
	$benutzer = "";
}
$willkommen = "Willkommen zur&uuml;ck";
$nickname = "";
$hinweis = "";
// Wenn neuer Accont bestätigt wird
if ($_GET["art"] == "neuerAccount"){
	// Werte übernehmen
	if(isset($_GET["key"])){
		$schluessel = $_GET["key"];
		// User abfragen, dessen Account aktiviert wurde
		$schluessel_abfrage = anfrage("SELECT nickname FROM user WHERE schluessel = '$schluessel'");
		$nickname = $schluessel_abfrage[0];
		// Kein Nickname -> falscher Schlüssel
		if($nickname == false){
			$hinweis = "<div id='alertBestFehler' class='alert alert-error'>Best&auml;tigungslink nicht mehr aktuell oder nicht korrekt!</div>";
		}
		// Wenn Schlüssel zu Nickname gehört
		else{
			$aktiviert_abfrage = anfrage("SELECT aktiviert FROM user WHERE schluessel = '$schluessel'");
			$aktiviert = $aktiviert_abfrage[0];
			// Wenn der Account noch nicht aktiviert wurde
			if($aktiviert == 0){
				// Account aktivieren
				$aktivieren = mysql_query("UPDATE user SET aktiviert = 1 WHERE nickname = '$nickname'");
				// Wenn Account erfolgreich aktiviert wurde
	            if($aktivieren){
					$hinweis = "<div id='alertBestErfolg' class='alert alert-success'>Dein Account wurde erfolgreich aktiviert!</div>";
	            }
	            // Wenn Account nicht aktiviert werden konnte
	            else{
					$hinweis = "<div id='alertBestFehler' class='alert alert-error'>Dein Account konnte nicht aktiviert werden!<br>Bitte wende dich diesbezüglich an den Support!</div>";
	            }
	            $willkommen = "Herzlich Willkommen";
	        }
	        // Wenn der Account schon aktiviert wurde
	        else{
				$hinweis = "<div id='alertBestFehler' class='alert alert-error'>Dein Account wurde bereits aktiviert!</div>";
	        	$willkommen = "Hallo";
	        }
	    }
	}
}

elseif ($_GET["art"] == "neuesPasswort"){
	// Werte übernehmen
	if(isset($_GET["key"])){
		$schluessel = $_GET["key"];
		// User abfragen, der Passwort vergessen hat
		$schluessel_abfrage = anfrage("SELECT nickname, email FROM user WHERE schluessel_pw = '$schluessel'");
		$nickname = $schluessel_abfrage[0];
		// Kein Nickname -> falscher Schlüssel
		if($nickname == false){
			$hinweis = "<div id='alertBestFehler' class='alert alert-error'>Best&auml;tigungslink nicht mehr aktuell oder nicht korrekt!</div>";
		}
		// Wenn Schlüssel zu Nickname gehört
		else{
			// Neues Passwort generieren
			$passwortNeu = substr(md5(microtime()), mt_rand(0, 24), 8);
			$passwort = md5($passwortNeu);
			// Passwort updaten
			$passwortEintragen = mysql_query("UPDATE user SET passwort = '$passwort' WHERE nickname = '$nickname'");
			// Wenn Passwort eingetragen
			if ($passwortEintragen){
				// Schluessel löschen
				$schluesselEntfernen = mysql_query("UPDATE `user` SET `schluessel_pw` = 0 WHERE nickname = '$nickname'");
				// Wenn Schlüssel gelöscht
				if ($schluesselEntfernen){
					// Daten für E-Mail Nachricht festlegen
					$empfaenger = $schluessel_abfrage[1];
					$absender = "MAIL_SENDER@example.com";
					$betreff = "Neues Passwort";
					$header  = "MIME-Version: 1.0\r\n";
					$header .= "Content-type: text/html; charset=utf-8\r\n";
					$header .= "From: $absender\r\n";
					$header .= "Reply-To: $absender\r\n";
					// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
					$header .= "X-Mailer: PHP ". phpversion();
					$text = "
					<html>
						<body>
							<div>
								Hallo $benutzer,<br><br>
								soeben wurde ein neues Passwort f&uuml;r dich generiert.<br><br>
								Es lautet: $passwortNeu<br><br><br>
								Sch&ouml;ne Gr&uuml;&szlig;e<br><br>
								Das Team des Schankimperiums<br>
								-----------------------------------------------------------------------------<br>
								Bitte antworten Sie nicht auf diese E-Mail, da diese automatisch erstellt wurde.
							</div>
						</body>
					</html>";
					// Mail versenden
					$versand = mail($empfaenger, $betreff, $text, $header);
					// Wenn E-Mail versandt wurde
					if ($versand){
						$hinweis = "<div id='alertNeuesPWErfolg' class='alert alert-success'>Ein neues Passwort wurde für dich generiert und an deine E-Mail Adresse versandt.</div>";
					}
					// Wenn E-Mail nicht versandt wurde
					else{
						$hinweis = "<div id='alertNeuesPWFehler' class='alert alert-error'>Es ist ein Fehler aufgetreten!<br>Bitte kontaktiere den Support<br>Fehlernummer: 102</div>";
					}
				}
				// Wenn Schlüssel nicht entfernt wurde
				else{
					$hinweis = "<div id='alertNeuesPWFehler' class='alert alert-error'>Es ist ein Fehler aufgetreten!<br>Bitte kontaktiere den Support<br>Fehlernummer: 103</div>";
				}
			}
			// Wenn kein Passwort eingetragen
			else{
				$hinweis = "<div id='alertNeuesPWFehler' class='alert alert-error'>Es ist ein Fehler aufgetreten!<br>Bitte kontaktiere den Support<br>Fehlernummer: 104</div>";
			}
		}
	}
}
?>
<div class="registrieren">
	<p>
		<br><br><h1><?php echo $willkommen . " " . $benutzer . "!";?></h1>
		<?php echo $hinweis;?><br>
		Du kannst dich nun einloggen.
	</p>
</div>
	<!-- 
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
	   		<button onclick="login()" type="button" class="btn btn-info">Login</button>
	      		<img style="display: none;" id="waitLogin" src="/img/loading.gif"/>
	      		<br><br>
	      		<a class="linkKlein" id="neuesPW" >Passwort vergessen?</a>
	      		<br>
	      		<a class="linkKlein" id="neueBestaetigungsmail">Neue Best&auml;tigungsmail?</a>
	      	</div>
	  	</div>
	  	
	  	<!-- NEUES PASSWORT FORMULAR 
	    <div id="formNeuesPW" style="display: none;">
	    	<b>Neues Passwort?</b><br><br>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">Benutzer/E-Mail:</label>
					<div class="controls">
						<input type="text" id="inputBenutzer" onkeyup="passwortSicherheit()" placeholder="Benutzer/E-Mail">
					</div>
				</div>
				<div id="alertNeuesPWFehler" style="display: none;" class="alert alert-error"></div>
				<div id="alertNeuesPWErfolg" style="display: none;" class="alert alert-success"></div>
				<img style="display: none;" id="pwNeuWarten" src="/img/loading.gif"/>
				<div class="control-group span3">
					<div class="controls">
						<button type="button" id="neuesPWBtn" class="btn">Absenden</button>
					</div>
				</div>
			</form>
		</div>
	</form>
</div>

<script type="text/javascript">
	document.getElementById('loginPasswort').focus();
</script>
-->