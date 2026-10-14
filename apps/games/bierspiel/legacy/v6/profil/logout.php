<?php session_start(); include '../connect.php';

// Benutzer auslesen
$user = $_SESSION["nickname"];
// Status auf "0" setzen
$aktivieren = mysql_query("UPDATE user SET status = 0 WHERE email = '$user'");
// Session zerstören
session_destroy();

?>

<script type="text/javascript">
	document.location='/Home';
</script>