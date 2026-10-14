<?php $title="Login"; include 'header.php';?>

<script type="text/javascript">
	function pruefe(){
		var benutzer = $('#pwNeuName').val();
		// Wenn Eingabe kleiner 3
		if(benutzer.length<3){
			// Absenden Button disabled
			$('#absButton').attr('disabled', 'disabled');
		}
		else{
			// Absenden Button enabled
			$('#absButton').removeAttr('disabled');
		}
	}
</script>

<div class="registrieren">
	<p>
		<br><br><h1>Neues Passwort?</h1><br><br>
	</p>
	<div id="alertPwNeu" style="display: none;" class="alert alert-success">
		<strong>Erfolg!</strong> Dir wurde ein neues Passwort zugesandt!
	</div>
	<div id="alertPwNeuFehler" style="display: none;" class="alert alert-error">
	
	</div>
	<form id="pwNeu" class="form-horizontal">
		<div class="control-group">
	    	<label class="control-label" name="inputBenutzer">Benutzer:</label>
	    	<div class="controls">
	      		<input type="text" id="pwNeuName" onkeyup="pruefe()" placeholder="Email/Name">
	    	</div>
	  	</div>
	  	<div class="control-group">
	   		<div class="controls">
	      		<button type="submit" id="absButton" disabled="disabled" class="btn">Absenden</button>
	      		<img style="display: none;" id="pwNeuWarten" src="img/warten.gif"/>
	      	</div>
	  	</div>
	</form>
</div>


<?php include 'footer.php';?>