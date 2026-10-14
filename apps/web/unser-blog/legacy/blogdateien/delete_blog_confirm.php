<?php $title="L&ouml;schen"; include("header.php");
// Übernahme von Wert von vorheriger Seite
$blog_id = $_GET['blog_id'];
$user_id = id($_SESSION['nickname']);
// DB-Abfrage der user_id zu dem aktuellen Blog
$Bloguser = getProperty('user_id', 'blogs', 'id', $blog_id);
// Wenn aktueller User auch Blogersteller ist
if ($Bloguser[0] == $user_id){
    $delete_blog = mysql_query("DELETE FROM blogs WHERE id = $blog_id");
    $delete_comments = mysql_query("DELETE FROM kommentare WHERE blog_id = $blog_id");
    // Mitteilung und Link zurück zu Blogeinträgen des Users
    echo "<div align='center'><h3>Dein Blog und Kommentare dazu wurden gel&ouml;scht!</h3></div><br>";
    echo "<div align='center'><a href='blog.php'>Zur&uuml;ck</a></div>";   
}// end if
// Wenn aktueller User nicht der Blogersteller ist
else{
    // Mitteilung und Link zurück zu Blogeinträgen des Users
    echo "<div align='center'><h3>Dieser Blogeintrag ist nicht von dir!</h3></div><br>";
    echo "<div align='center'><a href='blog.php'>Zur&uuml;ck</a></div>";
}// end else
echo "<meta http-equiv='refresh' content='0; URL=blog.php?beginn=0'>";
include("footer.php");
?>