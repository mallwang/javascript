<?php $title="Profil &auml;ndern"; include ("header.php");
// Weitere Variablen deklarieren und initialisieren
$id = id($_SESSION['nickname']);
$nickname = $_SESSION['nickname'];
// Werte aus Formular übernehmen wenn vorhanden, ansonsten Werte aus DB verwenden
$persdaten_abfrage = getProperty('vorname', 'nachname', 'gebDatum', 'wohnort',
				 'hobbys', 'beschreibung', 'user', 'id', $id);
if($_POST["vorname"]==""){$vorname = $persdaten_abfrage[0];}
else{$vorname = $_POST["vorname"];}
if($_POST["nachname"]==""){$nachname = $persdaten_abfrage[1];}
else{$nachname = $_POST["nachname"];}
if($_POST["geburtsdatum"]==""){$geburtsdatum = $persdaten_abfrage[2];}
else{$geburtsdatum = $_POST["geburtsdatum"];}
if($_POST["wohnort"]==""){$wohnort = $persdaten_abfrage[3];}
else{$wohnort = $_POST["wohnort"];}
if($_POST["hobbys"]==""){$hobbys = $persdaten_abfrage[4];}
else{$hobbys = $_POST["hobbys"];}
if($_POST["text"]==""){$beschreibung = $persdaten_abfrage[5];}
else{$beschreibung = $_POST["text"];}

// Weitere Variablen deklarieren und initialisieren
$id = id($_SESSION['nickname']);
$nickname = $_SESSION['nickname'];
// Prüfung, ob mindestens ein Felder ausgefüllt ist
if($_POST["vorname"]=="" AND $_POST["nachname"]=="" AND $_POST["geburtsdatum"]=="" AND
   $_POST["wohnort"]=="" AND $_POST["hobbys"]=="" AND $_POST["text"]==""){
	// Mitteilung auf der Homepage
        echo "
                <div align='center'>
                        <table class='farbeTable'><br><br>
                                <td align='center'>
                                        <div>
                                                <br>Eingabefehler. Bitte mindestens ein Feld ausf&uuml;llen!<br><br>
                                                <a href='persDaten_aendern.php'>Zur&uuml;ck</a><br><br>
                                        </div>
                                </td>
                        </table> 
                </div>
        ";
}// end if
else{
	// DB mit übergebenen Werten füllen
	updateProperty('user', 'vorname', $vorname, 'nachname', $nachname,
		       'gebDatum', $geburtsdatum, 'wohnort', $wohnort,
		       'hobbys', $hobbys, 'beschreibung', $beschreibung, 'id', $id);
	// Mitteilung auf der Homepage
        echo "
            <div align='center'>
                    <table class='farbeTable'><br><br>
                            <td align='center'>
                                    <div>
                                            <br>Deine Pers&ouml;nlichen Daten wurde erfolgreich ge&auml;ndert.<br><br>
                                            <a href='profil.php'>Zur&uuml;ck zum Profil</a><br><br>
                                    </div>
                            </td>
                    </table> 
            </div>
        ";
}// end else
include ("footer.php");
?>