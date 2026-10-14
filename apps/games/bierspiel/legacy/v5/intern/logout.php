<?php session_start(); include("../huelle/connect.php");
$nickname = $_SESSION['nickname'];
// Status auf '0' ändern
$eintragen = mysql_query("UPDATE user SET status = '0' WHERE nickname = '$nickname'");

// Session zerstören
session_destroy();
// db-Verbindung schließen
mysql_close();

echo "<meta http-equiv='refresh' content='0; URL=../index.php'>";
?>