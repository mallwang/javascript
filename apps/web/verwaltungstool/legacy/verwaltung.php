<?php $title="Verwaltung"; include 'geheim.php'; include 'header.php';
$id = $_SESSION['userId'];
?>

<div align="center" class="hero-unit">
  	<h1>Hallo <?php echo $_SESSION['username'];?></h1>
  	<p>Hier kannst du all deine Studienunterlagen verwalten.</p>
  	Deine User-Id lautet: <?php echo $id;?>
  	<br><br>
  	<a>Neuen Ordner anlegen</a>
  	<br>
</div>

<?php include 'footer.php';?>