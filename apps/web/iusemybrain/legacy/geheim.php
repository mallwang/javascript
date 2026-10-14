<?php session_start();
if(!isset($_SESSION['nickname'])){
	include 'header2.php';
	// Fehlermeldung?>
	<div align="center">
		<br>
		<h2>Auf den Inhalt dieser Seite hast du leider keinen Zugriff..!</h2>
		<p>
			<img width="400px" alt="Brain-Schnecke" src="img/schnecke3.png" class="img-rounded">
		</p>
		<br>
		<h4>Bitte <a href="login.php">logge</a> dich hierfür zunächst ein.</h4>
	</div>
	<?php
	include 'footer.php';
	exit;
}?>