<?php $title = "Home"; include ("header.php");?>
<h2 align="center">DEINE ABONEMENTS</h2><br>

<?php
// Variable deklarieren und initialisieren
$user_id_session = id($_SESSION['nickname']);
$aktuellerUser = name($user_id_session);
$beginn = $_GET['beginn'];
$i = 0;
$v = mysql_query("SELECT id FROM blogs WHERE user_id IN (SELECT DISTINCT abbonierter_id FROM abbonnements WHERE user_id = $user_id_session)");
$eintraege_Gesamt = mysql_num_rows($v);
$y = mysql_query("SELECT DISTINCT abbonierter_id FROM abbonnements WHERE user_id = $user_id_session");
$anzahlAbonnierungen = mysql_num_rows($y);
// DB-Abfrage über Abonnements und Zählung dieser
$rest = $eintraege_Gesamt - $beginn;
$a = mysql_query("SELECT id, user_id, betreff, inhalt,
                         DATE_FORMAT(datum,'%d.%m.%Y - %H:%i:%s') AS datum
                         FROM blogs WHERE user_id IN(SELECT DISTINCT abbonierter_id FROM abbonnements WHERE user_id = $user_id_session)
                         ORDER BY datum DESC LIMIT $beginn, $rest");
$restanzahl = mysql_num_rows($a);
$a = anfrage('abbonierter_id', 'abbonnements', 'user_id', $user_id_session);
$anzahl = numROWs($a);
// Festlegen des Weiter- und Zurueck-Pfeils
if($beginn == 0){
    $zurueck = "";
    $anfang = "";
}
else{
    $beginnZ = $beginn - 5;
    $zurueck = "<a href='home.php?beginn=$beginnZ'>
        <img title='vorherige Seite' src='images/pfeil_links.jpg' alt='vorherige Seite'/>
    </a>";
    $anfang = "<a href='home.php?beginn=0'>
        <img title='erste Seite' src='images/doppelpfeil_links.jpg' alt='erste Seite'/>
    </a>";
}
if (($eintraege_Gesamt - $beginn) > 5){
    $beginnW = $beginn + 5;
    $weiter = "<a href='home.php?beginn=$beginnW'>
        <img title='naechste Seite' src='images/pfeil_rechts.jpg' alt='naechste Seite'/>
    </a>";
}
else{
    $weiter = "";
}
$letzterSeiteneintrag = $eintraege_Gesamt-($eintraege_Gesamt%5);
if($restanzahl > 5){
    $ende = "<a href='home.php?beginn=$letzterSeiteneintrag'>
        <img title='letzte Seite' src='images/doppelpfeil_rechts.jpg' alt='letzte Seite'/>
    </a>";
    $bisBlogeintrag = $beginn + 5;
}
else{
    $ende = "";
    $bisBlogeintrag = $eintraege_Gesamt;
}
// Aktuell dargestellte Blogeintraege
$vonBlogeintrag = $beginn + 1;
if($eintraege_Gesamt!=0){
    echo "
    <h3 align='center'><b>
        $anfang $zurueck Blogeintr&auml;ge $vonBlogeintrag bis $bisBlogeintrag $weiter $ende<b/><br>
        <h4><div align='center'>Gesamtanzahl: $eintraege_Gesamt</div></h4>
    </h3><br>
    ";
}

// Wenn abonnierte User keine Eintraege verfasst haben
if ($eintraege_Gesamt == 0 AND $anzahlAbonnierungen > 0){
    echo "
    <div align='center'>
        <table class='farbeTable'>
            <tr>
                <td align='center'><br>
                    Deine abonnierten User haben noch keine Blogeintr&auml;ge verfasst!<br><br>
                </td>
            </tr>
        </table>
    </div>";
}// end if
// Wenn noch keinen abonniert
else if ($eintraege_Gesamt == 0 AND $anzahlAbonnierungen == 0){
     echo "
    <div align='center'>
        <table class='farbeTable'>
            <tr>
                <td align='center'><br><br>
                    Du abbonierst momentan noch niemanden!<br><br>
                    Du kannst einen User &uuml;ber die Schaltfl&auml;che
                    <a href='suche.php'>SUCHEN</a> abbonieren<br><br></td></tr>
                </td>
            </tr>
        </table>
    </div>";
}
// Wenn User jemanden aboniert
else{
        // DB-Abfrage über vorhandene Blogeinträge der Abonierten User
        $a = mysql_query("SELECT id, user_id, betreff, inhalt,
                         DATE_FORMAT(datum,'%d.%m.%Y - %H:%i:%s') AS datum
                         FROM blogs WHERE user_id IN(SELECT DISTINCT abbonierter_id FROM abbonnements WHERE user_id = $user_id_session)
                         ORDER BY datum DESC LIMIT $beginn, 5");
        // Tabelle start
        echo "<table class='zentral farbe replies sticker-left sticker'>";

        //Schleifendurchlauf aller Blogeinträge des Abonierten Users
        while ($row = mysql_fetch_object($a)){
            // Variablen deklarieren und initialisieren
            $blog_id = $row->id;
            $user_id = $row->user_id;
            $name = name($user_id);
            $id = $row->id;
            $betreff = $row->betreff;
            $inhalt = $row->inhalt;
            $datum = $row->datum;
            $betreff_comment  = "Re: $betreff";
            // DB-Abfrage über vorhandene Kommentare des aktuellen Blogeintrags und zählen dieser
            $x = mysql_query ("SELECT * FROM kommentare WHERE blog_id = '$id'");
            $anzahl = mysql_num_rows($x);
            // DB-Abfrage über das Datum des aktuellen Kommentars
            $y = mysql_query("SELECT DATE_FORMAT(datum,'%d.%m.%Y - %H:%i:%s') AS datum
                             FROM kommentare WHERE blog_id = '$id' ORDER BY datum DESC Limit 1");
            $z = mysql_fetch_row($y);
            $w = $z[0];
            // Wenn ein Kommentar existiert
            if($w != ""){
                $kommentarVorhanden = "<abc><a href='#'>Kommentare anzeigen</a></abc><z><a href='#'>Kommentare verbergen</a></z> Neuester Eintrag: " . $w;
                $kommentarVerfassen = "";
            }// end if
            else{
                $kommentarVorhanden = "";
                $kommentarVerfassen = "<abc><a href='#'>Kommentar verfassen</a><abc>";
                }// end else
            // Füllen der Tabelle mit einer Übersicht über alle Blogeinträge
            echo "
                <ul class='replies'>
                    <li class='farbeTable'>
                        <b class='sticker sticker-left sticker-color-red'></b>
                        <div class='avatar'>
                            <img src='images/Userbilder/$user_id.jpg'>
                        </div>
                        <div class='reply'>
                            <div class='date'>
                                $datum
                            </div>
                            <div class='author'>
                                $name<br><br>
                            </div>
                            <div class='text'>
                                <a class='fg-color-white'><b>$betreff</b></a>
                                <h4 class='fg-color-white'>$inhalt</h4><br>
                            </div>
                            <div class='text'>
                                <br>$kommentarVerfassen<br>
                                Geschriebene Kommentare: <b>$anzahl </b>$kommentarVorhanden
                            </div><br>
                        </div>
                        <zusatzBereich>
                            <ul class='comments'>
                                <li class='bg-color-grey'>
                                    <b class='sticker sticker-right sticker-color-grey'></b>
                                    <div class='avatar'>
                                        <img src='images/Userbilder/$user_id_session.jpg'>
                                    </div>
                                    <div class='reply'><b>Dein Kommentar</b><br>
                                        <form name='kommentar' action='send_comment_home.php?blog_id=$blog_id' method='post' onsubmit='return checkKommentieren($i)'>
                                            <div>
                                                    <input class='metroSchrift 12' placeholder='Betreff' name='betreff' value='Re: $betreff' size='50' maxlength='50'>
                                            </div>
                                            <div>
                                                    <textarea class='metroSchrift 11' cols='60%' rows='5%' name='text'></textarea>
                                            </div>
                                            <div>
                                                <input name='submit' type='submit'></td>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                ";
            // DB-Abfrage über vorhandene Kommentare zum aktuellen Blog
            $c = "SELECT id, blog_id, user_id, betreff, inhalt, datum FROM kommentare WHERE blog_id = '$blog_id' ORDER BY datum DESC";
            $d = mysql_query($c);
            // Schleifendurchlauf aller Kommentare des aktuellen Blogs
            while ($row = mysql_fetch_object($d)){
                // Variablen deklarieren und initialisieren
                $datum = $row->datum;
                $comment_id = $row->id;
                $user_id = $row->user_id;
                $betreff = $row->betreff;
                $inhalt = $row->inhalt;
                $betreff_comment  = "Re: $betreff";
                // Wenn aktueller User den Blogeintrag selbst erstellt hat
                if ($user_id == id($_SESSION['nickname'])) {
                    $name = "Von Dir"; $delete="<a class='fg-color-white pointer' onclick='deleteCommentAbbonnements($comment_id)'>L&ouml;schen</a>";}
                else {$name = name($user_id); $delete=""; $bild = "standardBild.jpg";}
                
                echo "
                    <div>
                        <ul class='comments'>
                            <li class='bg-color-grey'>
                                <b class='sticker sticker-right sticker-color-grey'></b>
                                <div class='avatar'>
                                    <img src='images/Userbilder/$user_id.jpg'>
                                </div>
                                <div class='reply'>
                                    <div class='date'>
                                        $datum
                                    </div>
                                    <div class='author'>
                                        $name<br><br>
                                    </div>
                                    <div class='text'>
                                        <a class='fg-color-white'><b>$betreff</b></a>
                                        <h4 class='fg-color-white'>$inhalt</h4><br>
                                        $delete
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                ";
            }// end while
            echo "
            </zusatzBereich>
                    </li>
                </ul>
                ";
            $i += 1;
        }// end while
        echo "</table><br>";
        echo "
                <h3 align='center'>
                    $anfang $zurueck Blogeintr&auml;ge $vonBlogeintrag bis $bisBlogeintrag $weiter $ende<br>
                    <h4><div align='center'>Gesamtanzahl: $eintraege_Gesamt</div></h4>
                </h3>
                ";
}// end else
include ("footer.php");
?>