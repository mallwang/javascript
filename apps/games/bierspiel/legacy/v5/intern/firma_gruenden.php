<?php $title="NEUE FIRMA"; session_start(); include("../huelle/connect.php");

$standort = $_POST['standort'];
$firmenname = $_POST['firmenname'];
$akt_user = $_SESSION['nickname'];
$eintrag_firma = mysql_query("UPDATE firmendaten SET hauptsitz = '$standort', firmenname='$firmenname' WHERE
                             user_id = (SELECT id FROM user WHERE nickname = '$akt_user')");
$adsf = "UPDATE firmendaten SET hauptsitz = '$standort', firmenname='$firmenname' WHERE
                             user_id = (SELECT id FROM user WHERE nickname = '$akt_user')";
//echo $adsf;
if ($eintrag_firma){
    $anzahl_firmen= mysql_query("SELECT firmenanzahl FROM standorte");
    $firmenanzahl = $anzahl_firmen + 1;
    $update_firmendaten  = mysql_query("UPDATE standorte SET firmenanzahl = '$firmenanzahl' WHERE name = '$standort'");
    if ($update_firmendaten){
    // Nachricht für aktuellen Benutzer
    include ("../huelle/header.php");
    echo"
        <br><br><br><br>
            <div align='center'>
                <h3>Herzlichen Gl&uuml;ckwunsch!</h3><br>
                Ihre Firma ist nun bei den Beh&ouml;rden gemeldet.
            </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        ";
    }
}
else{
    // Nachricht für aktuellen Benutzer
    echo "
        <br><br><br><br>
            <div align='center'>
            <h3>Das Anmelden Ihrer Firma ist fehlgeschlagen!</h3><br>
            Bitte versuchen Sie es nochmal oder senden Sie ein Support-Ticket.
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        ";
}
include("../huelle/footer.php");?>