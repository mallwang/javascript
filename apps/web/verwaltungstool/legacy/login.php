<?php $title="Login"; include 'header.php';

if (isset($_GET["benutzer"])){
	$benutzer = $_GET["benutzer"];
}
else{
	$benutzer = "";
}

?>
<!--
<script type="text/javascript">
	// Erst wenn beide felder gefüllt sind wird der loginbutton sichtbar
	// Benutzername mind. 3 stellen - pw mind. 6 stellen
	pruefe('1');
	pruefe('2');
	
	var be = 0;
	var pw = 0;
	function pruefe(id){
		// Benutzer
		if (id=='1'){
			var loginName = $('#loginName').val();
			if (loginName.length>2){
				be = 1;
			}
			else{
				be = 0;
			}
			zugang();
		}
		// Passwort
		else if (id=='2'){
			var loginPasswort = $('#loginPasswort').val();
			if (loginPasswort.length>5){
				pw = 1;
			}
			else{
				pw = 0;
			}
			zugang();
		}
	}
	function zugang(){
		if (be == 1 && pw == 1){
			// Login button sichtbar
			$('#logButton').removeAttr('disabled');
		}
		else{
			// Loginbutton disabled
			$('#logButton').attr('disabled', 'disabled');
		}
	}
</script>
-->
<div class="registrieren">
	<p>
		<br><br><h1>Willkommen zurück!</h1><br><br>
	</p>
	<div style="display: none;" class="alert alert-error">
	
	</div>
	<form id="login" name="form" class="form-horizontal">
		<div class="control-group">
	    	<label class="control-label" name="inputEmail">Benutzer:</label>
	    	<div class="controls">
	      		<input type="text" id="loginName" onkeyup="pruefe('1')" value="<?php echo $benutzer;?>" placeholder="Email/Name">
	    	</div>
	  	</div>
	  	<div class="control-group">
	    	<label class="control-label" name="inputEmail1">Passwort</label>
	    	<div class="controls">
	      		<input type="password" id="loginPasswort" onkeyup="pruefe('2')" placeholder="Passwort">
	    	</div>
	  	</div>
		<div id="alertLoginFehler" style="display: none;" class="alert alert-error">
		</div>
	  	<div class="control-group">
	   		<div class="controls">
	      		<button type="submit" id="logButton" class="btn">Login</button>
	      		<img style="display: none;" id="logWarten" src="img/loading.gif"/>
	      		<br><br>
	      		<a class="linkKlein" href="pwNeu.php">Passwort vergessen?</a>
	      		<br>
	      		<a class="linkKlein" href="bestNeu.php">Neue Bestätigungsmail?</a>
	      	</div>
	  	</div>
	</form>
</div>

<script type="text/javascript">
	document.getElementById('loginName').focus();
</script>

<?php include 'footer.php';?>