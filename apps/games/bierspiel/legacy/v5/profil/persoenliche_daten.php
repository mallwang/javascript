<?php $title="PROFIL"; include("../huelle/header.php");

//$aktueller_user = $_SESSION['nickname'];
$akt_user = "NICKNAME";
$akt_userdaten_abfrage = mysql_query("SELECT email, vorname, nachname, DATE_FORMAT (geburtsdatum, '%d.%m.%Y') AS geburtsdatum , wohnort, hobbys, beschreibung FROM user WHERE nickname = '$akt_user'");
$userdaten_abfrage = mysql_fetch_array($akt_userdaten_abfrage);
if ($userdaten_abfrage[3] == '00.00.0000'){
    $geburtsdatum = "";
}
else{
    $geburtsdatum = $userdaten_abfrage[3];
}
?>
<!-- TABELLE FÜR USERDATEN -->
<br>
<table>
<h3 class="inline"><u>Deine Userdaten:</u></h3>
    <tr>
        <td width="250">Nickname: </td>
        <td align="left"><?php echo $akt_user;?> </td>
    </tr>
    <tr>
        <td width="250">E-Mail: </td>
        <td align="left"><?php echo "$userdaten_abfrage[0]"?> </td>
    </tr>
    <tr>
        <td> <a href="email_aendern.php"> E-Mail Adresse &auml;ndern</a> </td>
        <td> <a href="pw_aendern.php">Passwort &auml;ndern</a> </td>
    </tr>
</table>
<br><br>
<!-- TABELLE FÜR PERSÖNLICHE DATEN -->
<table align="center">
<h3 class="inline"><u>Deine Pers&ouml;nlichen Daten:</u></h3><b><a href="persDaten_aendern.php"> Daten &auml;ndern</a></b>
    <tr>
        <td width="250">Vorname: </td>
        <td align="left"><?php echo "$userdaten_abfrage[1]"?> </td>
    </tr>
    <tr>
        <td width="250">Nachname: </td>
        <td align="left"><?php echo "$userdaten_abfrage[2]"?> </td>
    </tr>
    <tr>
        <td width="250">Geburtsdatum: </td>
        <td align="left"><?php echo $geburtsdatum;?> </td>
    </tr>
    <tr>
        <td width="250">Wohnort: </td>
        <td align="left"><?php echo "$userdaten_abfrage[4]"?> </td>
    </tr>
    <tr>
        <td width="250">Hobbys: </td>
        <td align="left"><?php echo "$userdaten_abfrage[5]"?> </td>
    </tr>
    <tr>
        <td width="250" valign="top">Beschreibung: </td>
        <td><textarea class="textfeld" disabled="disabled" cols="50" rows="10" name="text"><?php echo "$userdaten_abfrage[6]"?></textarea></td>
    </tr>
</table> 
<?php include("../huelle/footer.php");?>