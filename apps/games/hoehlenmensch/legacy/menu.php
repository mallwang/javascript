<?php
if (!isset($hmenu)) $hmenu="";
function href ($seite, $menu, $hmenu) {
    if ($menu != $seite) echo "href='$seite.php'";
    if ($hmenu== $seite) echo ' class="akt"';
}
?>
<div id="nav">
<h2>Tätigkeiten</h2>		
		<ul type="circle">
			<li><a href="dauer.php?Arbeit=1">Jagen</a></li>
			<li><a href="dauer.php?Arbeit=2">Sammeln</a></li>
			<li><a href="dauer.php?Arbeit=3">Fischen</a></li>
			<li><a href="dauer.php?Arbeit=4">Pilze suchen</a></li>
			<li><a href="dauer.php?Arbeit=5">Holz fällen</a></li>
			<li><a href="dauer.php?Arbeit=6">Steine metzen</a></li>
			<li><a href="dauer.php?Arbeit=7">Wasser suchen</a></li>
			<li><a href="Status.php">Status</a></li>
			<li><a href="herstellung.php">Herstellung</a></li>
			<li><a href="Hauptseite.php">Hauptseite</a></li>
			<li><a href="inventar.php">Inventar</a></li>
			<li><a href="profil.php">Profil</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
		
<div class="border"></div>
</div>