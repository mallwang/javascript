<?php $title="User"; include ("header.php");
// Variablen deklarieren und initialisieren
$aktuellerUser = $_SESSION['nickname'];
$aktuellerUser_id = id($aktuellerUser);
$id = $_GET["user_id"];
$i = 0;
$name = name($id);
$ersteller = name($id);
$user_id = id($name);
$name_gross = strtoupper ($name);
$email = email($id);
$blog_id = 0;
// Wenn aktueller User den Blogeintrag selbst erstellt hat
if ($user_id == id($_SESSION['nickname'])) {$ersteller = "Dir";
    $delete="<a class='fg-color-white pointer' onclick='deleteBlog($blog_id)'>L&ouml;schen</a>";}
else {$ersteller = name($user_id); $delete="";}
// Persönliche Daten zu der gesuchten id aus der DB laden
$persdaten_abfrage = getProperty('vorname', 'nachname',
                                 'DATE_FORMAT(gebDatum,"%d.%m.%Y") AS datum', 'wohnort',
                                 'hobbys', 'beschreibung', 'user', 'id', $id);
// Bei keinem eingegebenen Geburtsdatum einen leeren String anzeigen anstatt 0000-00-00
if($persdaten_abfrage[2]==0000-00-00){$persdaten_abfrage[2] = "";}// end if
?>
<div align="center">
    <h2>PROFIL VON <?php echo $name_gross;?></h3>
    <a href='abbonieren.php?user_id=<?php echo $user_id; ?>'>Blogeintr&auml;ge abonieren</a></div><br>
</div>
<table>
    <tr>
        <td width="50%">
                        <!-- TABELLE FÜR PERSÖNLICHE DATEN -->
            <table class='farbeTable'>
            <h3 class="inline">pers&ouml;nlich Daten:</h3><br>
                <tr>
                    <td width="200" align="center"><b><i>Vorname: </i></b></td>
                    <td align="left"><?php echo "$persdaten_abfrage[0]"?> </td>
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
                    <td width="200" valign="top" align="center"><b><i>Beschreibung: </i></b></td>
                    <td><textarea class="textfeld" disabled="disabled" cols="50" rows="10" name="text"><?php echo "$persdaten_abfrage[5]"?></textarea></td>
                </tr>
            </table>
            <br><br>
        </td>
        <td valign="top" width="50%">
                    <!-- TABELLE FÜR USERDATEN -->
            <table class='farbeTable'>
            <h3 class="inline">Userdaten:</h3><br>
                <tr>
                    <td width="200" align="center"><b><i>Nickname: </i></b></td>
                    <td align="left"><?php echo "$name"?> </td>
                </tr>
                <tr>
                    <td width="200" align="center"><b><i>E-Mail: </i></b></td>
                    <td align="left"><?php echo "$email"?> </td>
                </tr>
            </table>
            <br><br>
            <table class="farbeTable">
            <h3 class="inline"><b>Profilfoto von <?php echo "$name"?>:</b></h3><br>
                <tr>
                    <td><img src="images/Userbilder/<?php echo $id;?>.jpg"></td>
                </tr>
            </table>
        </td>  
    </tr>
</table>
<br><br><br>
<?php
// Variable aus Formuler übernehmen
$user_id = $_GET["user_id"];
// DB-Abfrage über vorhandene Blogeinträge des Users
$a = "SELECT id, betreff, inhalt, DATE_FORMAT(datum,'%d.%m.%Y - %H:%i:%s') AS datum
FROM blogs WHERE user_id = '$user_id' ORDER BY datum DESC";
$b = mysql_query($a);
// Tabelle start
echo "<table class='zentral farbe replies sticker-left sticker'>";
// Schleifendurchlauf aller Blogeinträge des gesuchten Users
while ($row = mysql_fetch_object($b)){
    // Variablen deklarieren und initialisieren
    $blog_id = $row->id;
    $betreff = $row->betreff;
    $inhalt = $row->inhalt;
    $datum = $row->datum;
    $betreff_comment  = "Re: $betreff";
    // DB-Abfrage über vorhandene Kommentare des aktuellen Blogeintrags und zählen dieser
    $x = mysql_query ("SELECT * FROM kommentare WHERE blog_id = '$blog_id'");
    $anzahl = mysql_num_rows($x);
    // DB-Abfrage über das Datum des aktuellen Kommentars
    $y = mysql_query("SELECT DATE_FORMAT(datum,'%d.%m.%Y - %H:%i:%s') AS datum
                     FROM kommentare WHERE blog_id = '$blog_id' ORDER BY datum DESC Limit 1");
    $z = mysql_fetch_row($y);
    $w = $z[0];
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
                    <img src='images/Userbilder/$id.jpg'>
                </div>
             <div class='reply'>
                <div class='date'>
                    $datum
                </div>
                <div class='author'>
                    $ersteller<br><br>
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
                            <img src='images/Userbilder/$aktuellerUser_id.jpg'>
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
        else {$name = name($user_id); $delete="";}
        
        echo "<div>
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
include("footer.php");
?>