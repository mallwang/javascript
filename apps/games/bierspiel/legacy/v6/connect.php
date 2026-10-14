<?php include ("configuration.php");


// Datenbankverbindung oeffnen
if(!($verbindung = mysql_connect($dbhost, $dbuser, $dbpassword))){
   die("Konnte keine Verbindung zur Datenbank herstellen!");
}

// Datenbank auswaehlen
if(!(@mysql_select_db($dbdatenbank, $verbindung))){
    die("Fehler " . mysql_errno() . ": " . mysql_error());
    //showerror();
}

mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");

// Fehlerausgabe
//function showerror(){
//    die("Fehler " . mysql_errno() . ": " . mysql_error());
//}
?>