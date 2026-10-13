<?php $title="Umgebung"; include ("header.php");
$id = id($_SESSION['nickname']);
$abfrage_inventar = mysql_query("SELECT axt, jagdspeer, wasserbeutel, korb, lendenschurz, schuhe FROM inventar WHERE login_id = '$id'") or die ("Fehler! Abfrage Inventar");
$inventar = mysql_fetch_array($abfrage_inventar);
?>

				<table border = "5" width = "200">
						Dein Inventar:
						<tr>
							<td width="150"> <?php echo "Axt: " ?> </td>
							<td align="center" > <?php echo $inventar["axt"];?> </td>
						</tr>
						<tr>
							<td width="150"> <?php echo "Jagdspeer: " ?> </td>
							<td align="center" > <?php echo $inventar["jagdspeer"];?> </td>
						</tr>
						<tr>
							<td width="150"> <?php echo "Wasserbeutel: " ?> </td>
							<td align="center" > <?php echo $inventar["wasserbeutel"];?> </td>
						</tr>
						<tr>
							<td width="150"> <?php echo "Korb: " ?> </td>
							<td align="center" > <?php echo $inventar["korb"];?> </td>
						</tr>
						<tr>
							<td width="150"> <?php echo "Lendenschurz: " ?> </td>
							<td align="center" > <?php echo $inventar["lendenschurz"];?> </td>
						</tr>
						<tr>
							<td width="150"> <?php echo "Schuhe: " ?> </td>
							<td align="center" > <?php echo $inventar["schuhe"];?> </td>
						</tr>
				</table>
		
<?php include ("footer.php"); ?>