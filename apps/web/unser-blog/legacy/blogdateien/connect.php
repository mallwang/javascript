<?php
// DB-Connect mit login und passwort über XAMPP/localhost
//$connect = mysql_connect("localhost", "root", "") or die("Keine Verbindung zur Datenbank hergestellt!");
// DB-Connect mit login und passwort über Webserver
$db = mysql_connect("DB_HOST", "DB_USER", "DB_PASSWORD") or die("Keine Verbindung zur Datenbank hergestellt!");
// Wenn keine Datenbank ausgewählt wurde
if(!mysql_select_db("DB_NAME", $db)){die("Keine Datenbank ausgewählt!" .mysql_error());}
?>