<?php
if(!isset($_SESSION['nickname'])){
	echo "	<!-- Hinweis -->
					<div style='font-size: 20px;' align='center' id='alertHinweis' class='alert alert-error'><b>Auf diesen Seiteninhalt haben Sie keinen Zugriff! Bitte loggen sie sich ein.</b></div>
				";
	include 'footer.php';
	exit;
}
?>