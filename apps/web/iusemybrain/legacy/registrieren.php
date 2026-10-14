<?php $title="Registrieren"; include 'header.php';?>

<script type="text/javascript">
	var benutzer;
	var email;
	var pw1;
	var pw2;
	
	function regCheck(zahl){
		
		if(zahl == '1'){
			// Zeige Wartezeichen
			$("#alertPwNeuFehler1").hide();
			$("#benutzerCheck").show();
			var inputName = $('#inputName').val();
			var art = "benutzer";
			if (inputName.length>3){
				// öffen skript, das den Namen sucht
	            $.ajax({
					type: "POST",
					url: "namenspruefung.php",
					cache: false,
					data: { nickname: inputName, art: art},
					success: function(data){
						$("#benutzerCheck").hide();
	                    if(data=='yes'){
	                        // Input-Hintergrund hellgrün
	                    	$("#inputName").css("border-color","green");
	                    	$("#inputName").css("background-color","palegreen");
	                    	benutzer = 1;
	                    	alleGefuellt(benutzer, email, pw1, pw2);
	                    }
	                    else if(data=='no'){
	                        // Input-Hintergrund rot
	                        $("#inputName").css("border-color","red");
	                        $("#inputName").css("background-color","salmon");
	                        $("#alertPwNeuFehler1").html("<strong>Fehler!</strong> Benutzername existiert bereits in unserer Datenbank!");
                        	$("#alertPwNeuFehler1").show();
	                        // bootstrap-alert in rot anzeigen
	                        benutzer = 0;
	                    }
					}
	            });
	            return false;
			}
			else{
				$("#inputName").css("border-color","red");
				$("#inputEmail1").css("background-color","salmon");
			}
		}
		else if(zahl == '2'){
			$("#alertPwNeuFehler2").hide();
			$("#emailCheck").show();
			var inputEmail = $('#inputEmail1').val();
			// if Email Adresse valide
			var validmailregex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.([a-z][a-z]+)|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i
  			if (validmailregex.test(inputEmail)){
				var art = "email";
				// öffen skript, das den Namen sucht
	            $.ajax({
					type: "POST",
					url: "namenspruefung.php",
					cache: false,
					data: { email: inputEmail, art: art},
					success: function(data){
						$("#emailCheck").hide();
	                    if(data=='yes'){
	                        // Input-Hintergrund hellgrün
	                    	$("#inputEmail1").css("border-color","green");
	                    	$("#inputEmail1").css("background-color","palegreen");
	                    	email = 1;
	                    	alleGefuellt(benutzer, email, pw1, pw2);
	                    }
	                    else if(data=='no'){
	                        // Input-Hintergrund rot
	                        $("#inputEmail1").css("border-color","red");
	                        $("#inputEmail1").css("background-color","salmon");
	                        $("#alertPwNeuFehler2").html("<strong>Fehler!</strong> E-Mail Adresse existiert bereits in unserer Datenbank!");
                        	$("#alertPwNeuFehler2").show();
	                        email = 0;
	                        // bootstrap-alert in rot anzeigen
	                    }
					}
	            });
	            return false;
  			}
  			// Email nicht valide
  			else{
  				$("#emailCheck").hide();
  				$("#inputEmail1").css("border-color","red");
                $("#inputEmail1").css("background-color","salmon");
                $("#alertPwNeuFehler2").html("<strong>Fehler!</strong> Keine gültige E-Mail Adresse!");
            	$("#alertPwNeuFehler2").show();
            	email = 0;
  			}
		}
		else if(zahl == '3'){
			// pruefe, ob Passwort sicher ist
			var p = '0%';
			var passwort = $('#inputPasswort1').val();
			if(passwort.length<6){
				$("#inputPasswort1").css("border-color","red");
				$("#inputPasswort1").css("background-color","salmon");
				$("#progress").css("width", p);
				pw1 = 0;
			}
			else{
				// Sicherheit verteilen
				var s = 0;
				if(passwort.match(/[a-z]/)||passwort.match(/[A-Z]/)){
					s += 2;
				}
				if(passwort.match(/[^a-z\d]/i)||passwort.match(/[1-9]/)){
					s += 2;
					if(!passwort.match(/[a-z]/)||!passwort.match(/[A-Z]/)){
						s -= 2;
					}
				}
				if(passwort.match(/[\d]/i)) {
					s += 2;
				}
				// Prozente des Progressbalkens verteilen
				switch (s) {
				case 2: p='33%'; break;
				case 4: p='66%'; break;
				case 6: p='100%'; break;
				}
				if(p=='33%'){
					$("#barArt").removeAttr('class');
					$("#barArt").attr('class', 'progress progress-danger');
				}
				else if(p=='66%'){
					$("#barArt").removeAttr('class');
					$("#barArt").attr('class', 'progress progress-warning');
				}
				else if(p=='100%'){
					$("#barArt").removeAttr('class');
					$("#barArt").attr('class', 'progress progress-success');
				}
				$("#inputPasswort1").css("border-color","green");
				$("#inputPasswort1").css("background-color","palegreen");
				$("#progress").css("width", p);
				pw1 = 1;
				regCheck('4');
				alleGefuellt(benutzer, email, pw1, pw2);
			}
		}
		else if(zahl == '4'){
			// pruefe, ob passwort mit passwort 2 übereinstimmt
			var paw1 = $('#inputPasswort1').val();
			var paw2 = $('#inputPasswort2').val();
			if (paw1 == paw2){
				$("#inputPasswort2").css("border-color","green");
				$("#inputPasswort2").css("background-color","palegreen");
				pw2 = 1;
				alleGefuellt(benutzer, email, pw1, pw2);
			}
			else{
				$("#inputPasswort2").css("border-color","red");
				$("#inputPasswort2").css("background-color","salmon");
				pw2 = 0;
				alleGefuellt(benutzer, email, pw1, pw2);
			}
		}
	}
	function alleGefuellt(benutzer, email, pw1, pw2){
		if (benutzer == '1'){
			if (email == '1'){
				if (pw1 == '1'){
					if (pw2 == '1'){
						$('#regButton').removeAttr('disabled');
					}
					else{
						$('#regButton').attr('disabled', 'disabled');
					}
				}
				else{
					$('#regButton').attr('disabled', 'disabled');
				}
			}
			else{
				$('#regButton').attr('disabled', 'disabled');
			}
		}
		else{
			$('#regButton').attr('disabled', 'disabled');
		}
	}
		// if(
</script>

<div class="registrieren">
	<p>
		<br><br><h1>Schön, Sie zu sehen!</h1><br><br>
	</p>
	<form id="registrierung" class="form-horizontal">
		<div class="control-group">
	    	<label class="control-label" name="inputName">Benutzername:</label>
	    	<div class="controls">
	      		<input type="text" id="inputName" onblur="regCheck('1')" placeholder="Benutzername">
	      		<div style="display: none;" id="benutzerCheck"class="progress progress-striped progress-success active">
  					<div class="bar" style="width: 100%;"></div>
				</div>
				<div id="alertPwNeuFehler1" style="display: none;" class="alert alert-error">
				</div>
	    	</div>
	  	</div>
	  	<div class="control-group">
	    	<label class="control-label" name="inputEmail1">Email:</label>
	    	<div class="controls">
	      		<input type="email" id="inputEmail1" onblur="regCheck('2')" placeholder="Email">
	      		<div style="display: none;" id="emailCheck"class="progress progress-striped progress-success active">
  					<div class="bar" style="width: 100%;"></div>
				</div>
				<div id="alertPwNeuFehler2" style="display: none;" class="alert alert-error">
				</div>
	    	</div>
	  	</div>
	  	<div class="control-group">
	    	<label class="control-label" name="inputPassword">Passwort:</label>
	    	<div class="controls">
	      		<input type="password" id="inputPasswort1" onkeyup="regCheck('3')" placeholder="Passwort">
	    	</div>
	  	</div>
	  	<div class="control-group">
	    	<label class="control-label" name="inputPassword">Passwort bestätigen:</label>
	    	<div class="controls">
	      		<input type="password" id="inputPasswort2" onkeyup="regCheck('4')" placeholder="Passwort">
	    	</div><br>
	    	<div class="linkKlein">Passwortsicherheit: </div>
	    	<div id="barArt" class="progress progress-info progress-striped">
  				<div id="progress" class="bar" style="width: 0%"></div>
			</div>
	  	</div>
	  	<div id="alertPwNeuFehler" style="display: none;" class="alert alert-error">
		</div>
		<div id="alertRegErfolg" style="display: none;" class="alert alert-success">
		</div>
	  	<div class="control-group">
	   		<div class="controls">
	      		<button id="regButton" type="submit" disabled="disabled" class="btn">Registrieren</button>
	      		<img style="display: none;" id="regWarten" src="img/warten.gif"/>
	    	</div>
	  	</div>
	</form>
</div>

<script type="text/javascript">
	document.getElementById('inputName').focus();
</script>

<?php include 'footer.php';?>