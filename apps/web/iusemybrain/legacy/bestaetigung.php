<?php include 'connect.php'; $title="Bestätigung"; include 'header.php'; include 'funktionen.php';
//error_reporting(0);
if (isset($_GET["benutzer"])){
	$benutzer = $_GET["benutzer"];
}
else{
	$benutzer = "";
}

$willkommen = "Willkommen zurück!";
$nickname = "";
$hinweis = "";
// Werte übernehmen
if(isset($_GET["key"])){
    $schluessel = $_GET["key"];
    // User abfragen, dessen Account aktiviert wurde
    $schluessel_abfrage = anfrage("SELECT nickname FROM user WHERE schluessel = '$schluessel'");
    $nickname = $schluessel_abfrage[0];
    // Kein Nickname -> falscher Schlüssel
    if($nickname == ""){
    }
    else{
    	$nicknameNeu = $nickname . "!";
        $aktiviert_abfrage = mysql_query("SELECT aktiviert FROM user WHERE schluessel = '$schluessel'");
        $aktiviert = mysql_result($aktiviert_abfrage, 0);
        // Wenn der Account noch nicht aktiviert wurde
        if($aktiviert == 0){
        	// Account aktivieren und Hinweis zum einloggen
            $aktivieren = mysql_query("UPDATE user SET aktiviert = 1 WHERE nickname = '$nickname'");
            if($aktivieren){?>
            <script type="text/javascript">
				$("#alertBestErfolg").html("<strong>Erfolg!</strong> Dein Account wurde erfolgreich aktiviert!");
				$("#alertBestErfolg").show();
            </script>
            <?php 
            }
            else{?>
            <script type="text/javascript">
				$("#alertBestFehler").html("<strong>Fehler!</strong> Dein Account konnte nicht aktiviert werden!<br>"
						+ "Bitte wende dich diesbezüglich an den Support!");
            	$("#alertBestFehler").show();
            </script>
            <?php	
            }
            $willkommen = "Herzlich Willkommen";
        	$hinweis = "dein Account wurde erfolgreich aktiviert!";
        }
        else{
        	// Hinweis, dass Account schon aktiviert wurde?>
        	<script type="text/javascript">
        		$("#alertBestFehler").show();
        		$("#alertBestFehler").html("<strong>Fehler!</strong> Dein Account wurde bereits aktiviert!");
        	</script>
        	<?php 
        	$willkommen = "Hallo";
        	$hinweis = "dein Account wurde bereits aktiviert!";
        }
    }
}?>

<div class="registrieren">
	<p>
		<br><br><h1><?php echo $willkommen . " " . $nicknameNeu;?></h1>
		<?php echo $hinweis;?><br>
	</p>
	<div id="alertBestErfolg" style="display: none;" class="alert alert-success">
	</div>
	<div id="alertBestFehler" style="display: none;" class="alert alert-error">
	</div>
	<div id="alertLoginFehler" style="display: none;" class="alert alert-error">
	</div>
	<form id="login" class="form-horizontal">
		<div class="control-group">
	    	<label class="control-label" name="inputEmail">Benutzer:</label>
	    	<div class="controls">
	      		<input type="text" id="loginName" value="<?php echo $benutzer;?>" placeholder="Email/Name">
	    	</div>
	  	</div>
	  	<div class="control-group">
	    	<label class="control-label" name="inputEmail1">Passwort</label>
	    	<div class="controls">
	      		<input type="password" id="loginPasswort" placeholder="Passwort">
	    	</div>
	  	</div>
	  	<div class="control-group">
	   		<div class="controls">
	      		<button type="submit" class="btn">Login</button>
	      		<img style="display: none;" id="logWarten" src="img/warten.gif"/>
	    	</div>
	  	</div>
	</form>
</div>

<script type="text/javascript">
	document.getElementById('loginPasswort').focus();
</script>

<?php include 'footer.php';?>