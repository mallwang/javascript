<?php $title="L&ouml;schen"; include("header.php");
// Übernahme von Wert von vorheriger Seite
$comment_id = $_GET['id'];
$user_id = id($_SESSION['nickname']);
// DB-Abfrage der user_id zu dem aktuellen Blog
$Bloguser = getProperty('user_id', 'kommentare', 'id', $comment_id);
// Wenn aktueller User auch Blogersteller ist
if ($Bloguser[0] == $user_id){
    $delete_comment = mysql_query("DELETE FROM kommentare WHERE id = $comment_id");
    // Mitteilung und Link zurück zu Blogeinträgen des Users
    echo "<div align='center'><h3>Dein Kommentar wurde gel&ouml;scht!</h3></div><br>";
    echo "<div align='center'><a href='home.php?beginn=0'>Zur&uuml;ck</a></div>";   
}// end if
// Wenn aktueller User nicht der Blogersteller ist
else{
    // Mitteilung und Link zurück zu Blogeinträgen des Users
    echo "<div align='center'><h3>Dieses Kommentar ist nicht von dir!</h3></div><br>";
    echo "<div align='center'><a href='home.php?beginn=0'>Zur&uuml;ck</a></div>";
}// end else
echo "<meta http-equiv='refresh' content='0; URL=home.php?beginn=0'>";
include("footer.php");
?>