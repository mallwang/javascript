<?php $title="Profil"; include("header.php");
// Variablen deklarieren und initialisieren
$id = id($_SESSION['nickname']);
$name = name($id);
$email = email($id);
// Persönliche Daten zu der momentanen id aus der DB laden
$persdaten_abfrage = getProperty('vorname', 'nachname',
                                 'DATE_FORMAT(gebDatum,"%d.%m.%Y") AS datum', 'wohnort',
                                 'hobbys', 'beschreibung', 'user', 'id', $id);
// Bei keinem eingegebenen Geburtsdatum einen leeren String anzeigen anstatt 0000-00-00
if($persdaten_abfrage[2]==0000-00-00){$persdaten_abfrage[2] = "";}
?>
<div align="center">
    <h2>PROFIL</h3>
</div>
<table>
    <tr>
        <td width="100%">
                        <!-- TABELLE FÜR PERSÖNLICHE DATEN -->
            <table class="farbeTable">
            <h3 class="inline"><b>Deine pers&ouml;nlichen Daten:</b></h3><b><a class="farbe" href="persDaten_aendern.php"> Daten &auml;ndern</a></b><br><br>
                <tr>
                    <td width="200" align="center"><br><b><i>Vorname: </i></b></td>
                    <td align="left"><br><?php echo "$persdaten_abfrage[0]"?> </td>
                </tr>
                <tr>
                    <td width="200" align="center"><b><i>Nachname: </i></b></td>
                    <td align="left"><?php echo "$persdaten_abfrage[1]"?> </td>
                </tr>
                <tr>
                    <td width="200" align="center"><b><i>Geburtsdatum: </i></b></td>
                    <td align="left"><?php echo "$persdaten_abfrage[2]"?> </td>
                </tr>
                <tr>
                    <td width="200" align="center"><b><i>Wohnort: </i></b></td>
                    <td align="left"><?php echo "$persdaten_abfrage[3]"?> </td>
                </tr>
                <tr>
                    <td width="200" align="center"><b><i>Hobbys: </i></b></td>
                    <td align="left"><?php echo "$persdaten_abfrage[4]"?> </td>
                </tr>
                <tr>
                    <td width="200" valign="top" align="center"><b><i>Beschreibung: </i></b><br><br></td>
                    <td><textarea class="textfeld" disabled="disabled" cols="50" rows="10" name="text"><?php echo "$persdaten_abfrage[5]"?></textarea><br><br></td>
                </tr>
            </table>
            <br><br>
        </td>
        <td valign="top" width="50%">
                    <!-- TABELLE FÜR USERDATEN -->
            <table class="farbeTable">
            <h3 class="inline"><b>Deine Userdaten:</b></h3><b><a href="email_aendern.php" class="farbe"> E-Mail &auml;ndern / </a></b><b><a href="pw_aendern.php" class="farbe">Passwort &auml;ndern</a></b><br><br>
                <tr>
                    <td width="200" align="center"><br><b><i>Nickname: </i></b></td>
                    <td align="left"><br><?php echo "$name"?> </td>
                </tr>
                <tr>
                    <td width="200" align="center"><b><i>E-Mail: </i></b><br><br></td>
                    <td align="left"><?php echo "$email"?> <br><br></td>
                </tr>
            </table>
            <br><br>
            <table class="farbeTable">
            <h3 class="inline"><b>Dein Profilfoto:</b></h3><b><a href="fotoHochladen.php" class="farbe"> Profilfoto &auml;ndern</a></b><br><br>
                <tr>
                    <td><img src="images/Userbilder/<?php echo $id;?>.jpg"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table align="center" border="0" class="farbeTable profZentral">
    <div align="center">
    <h3 class="inline"><b>Deine Abonnements:</b></h3><br>
    </div>
    <?php
    // Aktuellen User abfragen
    $user_id = id($_SESSION['nickname']);
    // DB-Abfrage über Abonnements und Zählung dieser
    $obj = anfrage('abbonierter_id', 'abbonnements', 'user_id', $user_id);
    $anzahl = numROWs($obj);
    // Wenn User niemanden abboniert
    if ($anzahl == 0){
        // Tabelle füllen
        echo "<tr><td align='left'>Du hast noch niemanden abboniert</td></tr>";
    }// end if
    // Wenn User jemanden abboniert
    else{
        // Schleifendurchlauf aller Abbonnierten User
        while ($row = mysql_fetch_object($obj)){
            // Variablen deklarieren und initialisieren
            $abbonierter_id = $row->abbonierter_id;
            $abbonierter_name = name($abbonierter_id);
            // Füllen der Tabelle mit einer Übersicht über alle Abbonierten
            echo "
            <tr>
                <td width='200' align='center'><b><i>$abbonierter_name</i></b></td>
                <td align='left'><a class='fg-color-white pointer' onclick='deleteAbbonnement($abbonierter_id)'>Abonnement stoppen</a></td>
            </tr>
            ";
        }// end while
    }// end else
    // Tabelle ende
echo "</table>";
include("footer.php");
?>