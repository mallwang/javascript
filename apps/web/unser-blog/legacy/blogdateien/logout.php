<?php
// Session starten
session_start();
// Session zersören
session_destroy();
// Automatische Weiterleitung zur Homepage
echo "<meta http-equiv='refresh' content='0; URL=http://www.unser-source.co.de/index.php'>";
//echo "Logout erfolgreich <br> <a href='index.php'>zur Startseite</a>";
?>