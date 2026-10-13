<?php
//$db = mysql_connect("DB_HOST", "DB_USER", "") or die("Keine Verbindung zur Datenbank hergestellt!");
$db = mysql_connect("DB_HOST", "DB_USER", "DB_PASSWORD") or die("Keine Verbindung zur Datenbank hergestellt!");
if(!$db)
	die("Keine Datenbank");
 
//if(!mysql_select_db("DB_NAME", $db))
if(!mysql_select_db("DB_NAME", $db))
	die("Keine Datenbank ausgewählt!");
?>