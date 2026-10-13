<?php session_start();
if(!isset($_SESSION['nickname'])){
	include 'header2.php';
	// Fehlermeldung?>
	<div align="center">
		<br><br><br><br><br>
		<h2>Auf den Inhalt dieser Seite hast du leider keinen Zugriff..!</h2>
		<p>
			<img width="256px" alt="Brain-Schnecke" src="img/stop.png" class="img-rounded">
		</p>
		<br>
		<h4>Bitte <a href="login.php">logge</a> dich hierfür zunächst ein.</h4>
	</div>
	<?php
	include 'footer.php';
	exit;
}?>