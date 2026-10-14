<?php
if (isset($_GET["benutzer"]) && isset($_GET["key"])){
	$benutzer = $_GET["benutzer"];
	$key = $_GET["key"];
}
else{
	
}
?>
<script type="text/javascript">
// Passwortsicherheit
var pw1;
function passwortSicherheit(){
	var p = '0%';
	var passwort = $('#inputPasswort1').val();
	if (passwort.length<6){
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
	}
}
// Passwortvergleich
var pw2;
function passwortVergleich(){
	var passwort1 = $('#inputPasswort1').val();
	var passwort2 = $('#inputPasswort2').val();
	// Prüfen, ob Passwort mindestens 6 Stellen lang
	if (passwort2.length==0){
		pw2 = 0;
	}
	// Wenn Passwort kleiner als 6 Stellen
	else if (passwort2.length<6){
		$("#inputPasswort2").css("border-color","red");
		$("#inputPasswort2").css("background-color","salmon");
		pw2 = 0;
	}
	// Wenn Passwort mindestens 6 Stellen lang
	else{
		// Prüfen ob Passwörter identisch
		if (passwort1 == passwort2){
			$("#inputPasswort2").css("border-color","green");
			$("#inputPasswort2").css("background-color","palegreen");
			pw2 = 1;
		}
		// Wenn Passwörter nicht identisch
		else{
			$("#inputPasswort2").css("border-color","red");
			$("#inputPasswort2").css("background-color","salmon");
			pw2 = 0;
		}
	}
}
//Neues Passwort
$('#neuesPWBtn').click(function(){
	// Hinweise entfernen
	$("#alertNeuesPWFehler").hide();
	$("#alertNeuesPWErfolg").hide();
	// Daten aus Formular
	var inputPasswort1 = $('#inputPasswort1').val();
	var inputPasswort2 = $('#inputPasswort2').val();
	var email = "<?php echo $benutzer;?>";
	// Prüfen ob alle ausgefüllt
	if (inputPasswort1.length>0 && inputPasswort2.length>0){
		// Prüfen, ob Passwort1 sicher ist
		if (pw1 == 1){
	        // Prüfen, ob Passwort1 == Passwort2
	        if (inputPasswort1 == inputPasswort2){
	            // Ladeanimation
	            $("#pwNeuWarten").show();
	            var art = "passwortNeu";
	            // Ajax aufruf
	            $.ajax({
					type: "POST",
					url: "datenNeu.php",
					cache: false,
					data: { passwort1: inputPasswort1, passwort2: inputPasswort2, email: inputPasswort, art: art},
					success: function(data){
						$("#pwNeuWarten").hide();
	                    if(data=='pwFalsch'){
	                    	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Das aktuelle Passwort ist nicht korrekt!");
	                        $("#alertNeuesPWFehler").show();
	                    }
	                    else if(data=='gleichesPW'){
	                    	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Das neue und alte Passwort sind identisch!");
	                        $("#alertNeuesPWFehler").show();
	                    }
	                    else if(data=='ok'){
	                    	$('#inputPasswort1').val("");
	                    	$('#inputPasswort2').val("");
	                    	$('#inputPasswort3').val("");
	                    	$("#alertNeuesPWErfolg").html("<strong>Erfolg!</strong> Dein Passwort wurde erfolgreich ge&auml;ndert!<br>"
	                            	+ "Dir wurde an dazu an deine E-Mail Adresse eine Best&auml;tigung gesendet");
	                        $("#alertNeuesPWErfolg").show();
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
	        else{
	        	$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Die eingegebenen Passw&ouml;rter stimmen nicht &uuml;berein!");
	            $("#alertNeuesPWFehler").show();
	        }
		}
		// Passwort ist nicht sicher
		else{
			$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Das Passwort ist nicht sicher und/oder zu kurz!");
            $("#alertNeuesPWFehler").show();
		}
	}
	else{
		$("#alertNeuesPWFehler").html("<strong>Fehler!</strong> Bitte alle Felder ausf&uuml;llen!");
        $("#alertNeuesPWFehler").show();
	}
});
</script>
<!-- PASSWORT ÄNDERN FORMULAR -->
    <div id="formNeuesPW">
    	<b>Neues Passwort</b><br><br>
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label">Neues Passwort:</label>
				<div class="controls">
					<input type="password" id="inputPasswort1" onkeyup="passwortSicherheit()" placeholder="Neues Passwort">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Passwort wiederholen:</label>
				<div class="controls">
					<input type="password" id="inputPasswort2" onkeyup="passwortVergleich()" placeholder="Passwort wiederholen">
					</div>
					<div class="linkKlein">Passwortsicherheit: </div>
	    				<div id="barArt" class="progress progress-info progress-striped">
  						<div id="progress" class="bar" style="width: 0%"></div>
				</div>
			</div>
			<div id="alertNeuesPWFehler" style="display: none;" class="alert alert-error"></div>
			<div id="alertNeuesPWErfolg" style="display: none;" class="alert alert-success"></div>
			<img style="display: none;" id="pwNeuWarten" src="images/loading.gif"/>
			<div class="control-group span3">
				<div class="controls">
					<button type="button" id="neuesPWBtn" class="btn">Absenden</button>
				</div>
			</div>
		</form>
	</div>