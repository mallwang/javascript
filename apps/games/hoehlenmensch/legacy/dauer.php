<?php $title="Dauer"; include ("header.php");?>
<?php $nr=(integer)$_GET["Arbeit"]; ?>
<?php
	if ($nr==1) {$text="jagen?";}
	else if ($nr==2) {$text="Früchte sammeln?";}
	else if ($nr==3) {$text="fischen?";}
	else if ($nr==4) {$text="Pilze suchen?";}
	else if ($nr==5) {$text="Holz fällen?";}
	else if ($nr==6) {$text="Steine metzen?";}
	else if ($nr==7) {$text="Wasser suchen?";}
	echo "<b>Wie lange möchtest du $text</b>";
?>
<br>
<br>
<?php $nr=(integer)$_GET["Arbeit"]; ?>
<?php echo "<form name='Jagen' action='Arbeiten.php?Arbeit=$nr' method='get'>" ?>
	<table>
	<tr>
		<td><input type="radio" name="dauer" value="30" checked="checked"> 30 Minuten </td><td>(<b>Verbrauch:</b> 0,4 tierische Nahrung und 0,25 liter Wasser)</td>
	</tr>
	<tr>
		<td><input type="radio" name="dauer" value="60" checked="checked"> 60 Minuten </td><td>(<b>Verbrauch:</b> 0,8 tierische Nahrung und 0,5 liter Wasser)</td>
	</tr>
	<tr>
		<td><input type="radio" name="dauer" value="120" checked="checked"> 120 Minuten </td><td>(<b>Verbrauch:</b> 1,6 tierische Nahrung und 1 liter Wasser)</td>
	</tr>
	<tr>
		<td><input type="radio" name="dauer" value="240" checked="checked"> 240 Minuten </td><td>(<b>Verbrauch:</b> 3,2 tierische Nahrung und 2 liter Wasser)</td>
	</tr>
	<tr>
		<td><input name="submit" type="submit"></td><td><?php echo "<input type='hidden' name='Arbeit' value=$nr>" ?></td>
	</tr>
	</table>
	<!--<input type="radio" name="dauer" value="30" checked="checked"> 30 Minuten (<b>Verbrauch:</b> 0,4 tierische Nahrung und 0,25 liter Wasser)<br>
	<input type="radio" name="dauer" value="60"> 1 Stunde (<b>Verbrauch:</b> 0,8 tierische Nahrung und 0,5 liter Wasser)<br>
	<input type="radio" name="dauer" value="120"> 2 Stunden (<b>Verbrauch:</b> 1,6 tierische Nahrung und 1 liter Wasser)<br>
	<input type="radio" name="dauer" value="240"> 4 Stunden (<b>Verbrauch:</b> 3,2 tierische Nahrung und 2 liter Wasser)<br>-->
	<?php echo "<input type='hidden' name='Arbeit' value=$nr>" ?>
	<!--<input name="submit" type="submit">-->
</form>

<?php include ("footer.php"); ?>