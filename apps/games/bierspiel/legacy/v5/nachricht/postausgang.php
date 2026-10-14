<?php $title="POSTEINGANG"; include("../huelle/header.php");
//$aktueller_user = $_SESSION['nickname'];
$akt_user = "NICKNAME";
$akt_user_id_abfrage = mysql_query("SELECT id FROM user WHERE nickname = '$akt_user'");
$akt_user_id = mysql_result($akt_user_id_abfrage, 0);
echo $akt_user_id;
$alle_nachrichten_abfrage = mysql_query("SELECT DATE_FORMAT(versendet,'%d.%m.%Y - %H:%i') AS versendet, betreff, text, zu_user FROM pns WHERE von_id = '$akt_user_id'");
echo $alle_nachrichten_abfrage;
// Anzahl an Nachrichten für aktuellen User zählen
$anzahl_nachrichten = mysql_num_rows($alle_nachrichten_abfrage);
if ($anzahl_nachrichten == 0){
    // Mitteilung an aktuellen User
    echo"
        <br><br><br><br>
        <div align='center'>
            <h3>Sie haben noch keine Nachrichten versendet.</h3>
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        "; 
}// end if
else{
    if($anzahl_nachrichten == 1){
        $gesamtnachrichteninfo = "Nachricht";
    }// end if
    elseif($anzahl_nachrichten > 1){
        $gesamtnachrichteninfo = "Nachrichten";
    }// end else
    // Mitteilung an aktuellen User
    echo"
        <br><br><br><br>
        <div align='center'>
            <h3>Sie haben insgesamt $anzahl_nachrichten $gesamtnachrichteninfo versendet.</h3>
        </div>
        <br><br><br><br><br>
        ";
        ?>
        <script src="../js/ausklappen.js"></script>
        <table class="bordered">
            <thead class="span12" align="center">
                <tr>
                    <th class="right">Datum</th>
                    <th>
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=nickname&richtung=ASC'>
                            <img title='Aufw&auml;rts sortieren' src='../images/Pfeil_oben.jpg' alt='Aufw&auml;rts sortieren'/>
                        </a> Betreff
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=nickname&richtung=DESC'>
                        <img title='Abw&auml;rts sortieren' src='../images/Pfeil_unten.jpg' alt='Abw&auml;rts sortieren'/>
                        </a>
                    </th>
                    <th class="right">
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=id&richtung=ASC'>
                            <img title='Aufw&auml;rts sortieren' src='../images/Pfeil_oben.jpg' alt='Aufw&auml;rts sortieren'/>
                        </a> An
                        <a href='useruebersicht.php?a=<?php echo $a;?>&b=<?php echo $b;?>&suche=id&richtung=DESC'>
                        <img title='Abw&auml;rts sortieren' src='../images/Pfeil_unten.jpg' alt='Abw&auml;rts sortieren'/>
                        </a>
                    </th>
                    <th>
                        Aktionen
                    </th>
                </tr>
            </thead>
            <tbody class="span12" >
        <?php
        while($row = mysql_fetch_object($alle_nachrichten_abfrage)){
            $versendet = $row->versendet;
            $betreff = $row->betreff;
            $text = nl2br($row->text);
            $zu_user = $row->zu_user;
        
            echo "<tr>
                <td>
                    $versendet
                </td>
                <td>
                    <ausklapp><a href='#'><b>$betreff</b></a></ausklapp>
                    <form style='display: none;'>$text</form>
                </td>
                <td class='right'>
                    $zu_user
                </td>
                <td class='left'>
                    <a href='neue_nachricht.php?user=$zu_user'>weitere Nachricht</a><br>
                    <a href='#'>L&ouml;schen</a>
                </td>
                </tr>
                ";
        }// end while
        echo "</tbody><tfoot></tfoot></table>";
}// end else
include("../huelle/footer.php");?>