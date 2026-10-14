<?php $title="NACHRICHT"; include("../huelle/header.php");

$empfaenger = $_POST['empfaenger'];
$betreff = $_POST['betreff'];
$inhalt = $_POST['inhalt'];
//$aktueller_user = $_SESSION['nickname'];
$akt_user = "NICKNAME";
$akt_user_id_abfrage = mysql_query("SELECT id FROM user WHERE nickname = '$akt_user'");
$akt_user_id = mysql_result($akt_user_id_abfrage, 0);
if ($empfaenger == "" OR $betreff == "" OR $inhalt == ""){
    echo "BITTE KORREKTE EINGABEN MACHEN";
}
else{
    // Alle Empfänger feststellen
    $teile = explode(", ", $empfaenger);
    // Anzahl an Empfaengern
    $anzahl_empfaenger = count($teile);
    // Zaehler fuer gesendete Nachrichten
    $zaeher_versendet = 0;
    // Zaehler fuer nicht gesendete Nachrichten  (evtl. Posteingang voll)
    $zaehler_nicht_versendet = 0;
    // solange ein Empfänger existiert
    foreach($teile as $empfaenger){
        // Empfänger-ID abfragen
        $empf_id_abfrage = mysql_query("SELECT id FROM user WHERE nickname = '$empfaenger'");
        $empf_id = mysql_result($empf_id_abfrage, 0);
        // Eintragen der aktuellen Nachricht
        $insert = "INSERT INTO `pns`(`betreff`, `von_id`, `von_user`, `zu_id`, `zu_user`, `text`)
        VALUES ('$betreff','$akt_user_id','$akt_user','$empf_id','$empfaenger','$inhalt')";
        //echo $insert;
        $eintragen = mysql_query($insert);
        if ($eintragen){
            $zaeher_versendet += 1;
        }
        else{
            $zaehler_nicht_versendet += 1;
        }
    }
    if($zaeher_versendet+$zaehler_nicht_versendet == $anzahl_empfaenger){
        // Nachricht für aktuellen Benutzer
        echo"
            <br><br><br><br>
                <div align='center'>
                    <h3>Alle Ihre Nachrichten wurden &uuml;bermittelt.</h3>
                </div>
            ";
    }
    else{
        // Nachricht für aktuellen Benutzer
        echo"
            <br><br><br><br>
                <div align='center'>
                    <h3>Es konnten nicht alle Nachrichten &uuml;bermittelt werden.</h3><br>
                    versendete Anzahl: $zaeher_versendet;<br>
                    nicht versendete Anzahl: $zaehler_nicht_versendet.
                </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            ";
    }
}

include("../huelle/footer.php");?>