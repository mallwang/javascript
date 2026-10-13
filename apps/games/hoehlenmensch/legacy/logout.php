<?php include ("header.php");
	$id = id ($_SESSION['nickname']);
	$eintragen_charakter = mysql_query("UPDATE charakter SET status = 0 WHERE login_id = '$id'");
	session_destroy();
	echo "Logout erfolgreich <br><a href='index.php'>weiter</a>";
	mysql_close();
	include ("footer.php");
?>