<?php $title="TICKET"; include("../huelle/header.php");

$betreff = $_POST['betreff'];
$inhalt = $_POST['inhalt'];

if ($betreff == "" OR $inhalt == ""){
    echo "
        <br><br><br><br>
        <div align='center'>
            <h3>mit JavaScript abfangen.<br>
            Bitte alle Felder ausf&uuml;llen!</h3>
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        ";
}
else{
    $insert = "INSERT INTO `support_ticket`(`betreff`, `inhalt`) VALUES ('$betreff', '$inhalt')";
    $eintragen = mysql_query($insert);
    if ($eintragen){
        echo "
            <br><br><br><br>
                <div align='center'>
                <h3>Ihre Nachricht wurde &uuml;bermittelt.</h3>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            ";
    }
    else{
        echo "
            <br><br><br><br>
                <div align='center'>
                <h3>Fehler beim &Uuml;bermitteln ihrer Nachricht!<br>
                Bitte versuchen Sie es nochmal.</h3>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            ";
    }
}

include("../huelle/footer.php"); ?>