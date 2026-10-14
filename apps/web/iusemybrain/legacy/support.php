<?php $title="Support-Ticket"; include("header.php");?>
<div class="support">
	<p>
		<br><br><h1>Teilen Sie uns Ihr Anliegen mit!</h1><br><br>
	</p>

	<form id="support" class="form-horizontal">
		<div class="control-group">
	      	<input maxlength="50" class="span3" type="text" id="betreff" onkeyup="pruefe('1')" placeholder="Betreff">
	      	<input class="span3" type="email" id="eMail" onkeyup="pruefe('1')" placeholder="E-Mail Adresse">
	  	</div>
	  	<div class="control-group">
	    	<textarea id="text" class="span6" rows="8" placeholder="Ihre Nachricht"></textarea>
	  	</div>
		<div id="alertError" style="display: none;" class="alert alert-error"></div>
		<div id="alertSuccess" style="display: none;" class="alert alert-success"></div>
	  	<div class="control-group">
	   		<div class="controls">
	      		<button style="width: 200px;" type="submit" id="betreffButton" class="btn">Absenden</button>
	      		<img style="display: none;" id="supportWarten" src="img/warten.gif"/>
	      	</div>
	  	</div>
	</form>
</div>
<script type="text/javascript">
	document.getElementById('betreff').focus();
</script>
<?php include("footer.php");?>