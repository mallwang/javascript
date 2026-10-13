<?php $title="I use my brain"; include 'header.php';
$begruessung = "";
if (isset($_SESSION["username"]))
	$begruessung = "Willkommen zurück " . $_SESSION['username'] . "!";
else
	$begruessung = "Herzlich willkommen bei i-use-my-brain!";
?>

<div align="center" class="hero-unit">
  	<h1><?php echo $begruessung;?></h1>
  	<p>Hier entsteht demnächst eine Internetpräsenz.</p>	
</div>

<?php include 'footer.php';?>