<?php $title="Login"; include 'header.php';?>

<script type="text/javascript">
	pruefe();
	function pruefe(){
		var email = $('#bestNeuEMail').val();
		// Wenn Eingabe kleiner 3
		if(email.length<3){
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
		<br><br><h1>Bestätigungsmail weg?</h1><br><br>
	</p>
	<div id="alertBestNeu" style="display: none;" class="alert alert-success">
		<strong>Erfolg!</strong> Dir wurde eine neue E-Mail zugesandt!
	</div>
	<div id="alertBestNeuFehler" style="display: none;" class="alert alert-error">
	
	</div>
	<form id="bestNeu" class="form-horizontal">
		<div class="control-group">
	    	<label class="control-label" name="inputBenutzer">E-Mail:</label>
	    	<div class="controls">
	      		<input type="email" id="bestNeuEMail" onkeyup="pruefe()" onblur="pruefe()" placeholder="E-Mail Adresse">
	    	</div>
	  	</div>
	  	<div class="control-group">
	   		<div class="controls">
	      		<button type="submit" id="absButton" disabled="disabled" class="btn">Absenden</button>
	      		<img style="display: none;" id="bestNeuWarten" src="img/warten.gif"/>
	      	</div>
	  	</div>
	</form>
</div>


<?php include 'footer.php';?>