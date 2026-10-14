<?php $title= "Suche"; include ("header.php");
// Wert aus Formular übernehmen
$name = $_POST['nickname'];
// Überschrift "SUCHERGEBNISSE"
echo "<h2 align='center'>SUCHERGEBNISSE</h2>";
// Prüfung, ob Eingabefeld ausgefüllt ist
if ($name != ""){
    // Wenn Eingabe * 
    if ($name == "*"){
        // Alle Einträge aus DB holen und absteigend sortieren
        $c = "SELECT name FROM user ORDER BY name ASC";
        $d = mysql_query($c);
    }// end if
    // Wenn Eingabe nicht * ist
    elseif ($name != "*"){
        // Alle Einträge aus DB holen und absteigend sortieren
    $c = "SELECT name FROM user WHERE name LIKE '%$name%' ORDER BY name ASC";
    $d = mysql_query($c);
    }// end elseif
    // Ergebnisse zählen
    $ergebnis = numROWs($d);
    // Prüfung, ob überhaupt ein Ergebnis vorhanden
    if ($ergebnis == 0){
        // Mitteilung auf der Homepage
        echo "
        <div align='center'>
            <table class='farbeTable' id='spacing'>
                <tr>
                    <td align='center'>
                        <br>Leider keine Ergebnisse gefunden<br><br>
                        <a href='suche.php'>Zur&uuml;ck zur Suche</a>
                    </td>
                </tr>
            </table>
        </div><br><br>";
    }// end if
    else{
        // Mitteilung auf der Homepage
        echo "
            <div align='center'>
                <a href='suche.php'>Zur&uuml;ck zur Suche</a><br><br>
                <table class='farbeTable ohneBorder'>
                    <thead class='weiss'>
                        <tr>
                            <th class='weiss'>Name</th>
                            <th class='weiss'>Blogeintr&auml;ge</th>
                            <th class='weiss'>Kommentare</th>
                            <th class='weiss'>Abonnieren</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
        // Schleifendurchlauf
        while ($row = mysql_fetch_object($d)){
            // Variablen name und user_id deklarieren und initialisieren
            $name = $row->name;
            $user_id = id($name);
            // Alle Blogeinträge des aktuellen Users auswählen und zählen
            $x = mysql_query ("SELECT * FROM blogs WHERE user_id = '$user_id'");
            $blogeintraege = numROWs($x);
            // Alle Kommentare des aktuellen Users auswählen und zählen
            $x = mysql_query ("SELECT * FROM kommentare WHERE user_id = '$user_id'");
            $kommentare = numROWs($x);
            // Userübersicht erstellen

            echo "
                <tr>
                    <td>
                        <a title='Profil ansehen' href='user.php?user_id=$user_id' target='_blank' onclick='return popup(this.href);'><b>$name</b></a>
                    </td>
                    <td>
                        $blogeintraege
                    </td>
                    <td>
                        $kommentare
                    </td>
                    <td>
                    <a title='$name abbonieren' href='abbonieren.php?user_id=$user_id'>Abonieren</a>
                    </td>
                <tr>
                ";
        }// end while
    }// end else
    // Tabelle schließen
    echo "
            </table>
        </div>
        ";
}// end if
//else{
//    // Mitteilung auf der Homepage (durch JS abgefangen)
//    echo "
//        <div align='center'>
//            <table class='farbeTable'>
//                <tr>
//                    <td align='center'>
//                        <br>Keinen Suchparameter eingegeben<br><br>
//                        <a href='suche.php'>Zur&uuml;ck zur Suche</a><br><br>
//                    </td>
//                </tr>
//            </table>
//        </div><br><br>
//    ";
//}// end else
include("footer.php");
?>