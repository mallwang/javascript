<?php $title="Spielaufbau";  include '../sessionpruefung.php';
// Wenn User eingeloggt ist
if (isset($_SESSION['nickname'])){
	// Meldung und Abbruch
	echo "Diese Seite kann vom internen Bereich nicht aufgerufen werden!";
	include '../footer.php';
	exit;
}
?>
<div class="inhalt" align="center">
	<div align="center">
		<img style="height: 80px;" src="/img/bauarbeiter.png">
		<h2>Beschreibung des Spielablaufs</h2>
	</div>
</div>

<?php include '../footer.php';?>