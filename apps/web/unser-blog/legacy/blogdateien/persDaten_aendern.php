<?php $title="Pers&ouml;nliche Daten &auml;ndern"; include("header.php");
// aktuelle id abfragen
$id = id($_SESSION['nickname']);
// Persönliche Daten zu der momentanen id aus der DB laden
$persdaten_abfrage = getProperty('vorname', 'nachname', 'gebDatum', 'wohnort',
				 'hobbys', 'beschreibung', 'user', 'id', $id);
// Bei keinem eingegebenen Geburtsdatum einen leeren String anzeigen anstatt 0000-00-00
if($persdaten_abfrage[2]==0000-00-00){$persdaten_abfrage[2] = "";}
?>
<div align="center">
    <h2>PERS&Ouml;NLICHE DATEN &Auml;NDERN</h3>
    <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
 
<table>
    <td>
        <form name="passwort" method="post" action="persDaten_neu.php">
	    <table class='farbeTable'>
		<tr>
		    <td><br><br>Vorname: </td><td><br><br> <input value="<?php echo "$persdaten_abfrage[0]"; ?>" name="vorname" type="text" size="41" maxlength="30"></td>
		</tr>
		<tr>
		    <td><br><br>Nachname: </td><td><br><br> <input value="<?php echo "$persdaten_abfrage[1]"; ?>" name="nachname" type="text" size="41" maxlength="30"></td>
		</tr>
		<tr>
		    <td><br><br>Geburtsdatum: </td><td><br><br> <input value="<?php echo "$persdaten_abfrage[2]"; ?>"  name="geburtsdatum" type="date" size="41" maxlength="30"></td>
		</tr>
		<tr>
		    <td><br><br>Wohnort: </td><td><br><br> <input value="<?php echo "$persdaten_abfrage[3]"; ?>"  name="wohnort" type="text" size="41" maxlength="30"></td>
		</tr>
		<tr>
		    <td><br><br>Hobbys: </td><td><br><br> <input value="<?php echo "$persdaten_abfrage[4]"; ?>"  name="hobbys" type="text" size="41" maxlength="50"></td>
		</tr>
		<tr>
		    <td><br><br>Beschreibung: </td>
		    <td><br><br>
			<textarea class='metroSchrift 12' cols="50" rows="10" name="text"><?php echo "$persdaten_abfrage[5]"; ?></textarea>
		    </td>
		</tr>
		<tr>
		    <td> <br><input name="reset" type="reset"> </td>
		    <td> <br><input name="submit" type="submit"> </td>
		</tr>
            </table>
	</form>	
    </td>
</table>
</div>
<?php include("footer.php"); ?>