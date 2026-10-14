<?php $title="Finanz&uuml;bersicht"; include '../sessionpruefung.php'; include '../geheim.php';
// FirmenId abfragen
$firmaId = $_SESSION['firmaId'];
// Alle Einnahmen und Ausgaben abfragen
$finanzen = anfrage("SELECT * FROM finanzen WHERE firma_id = '$firmaId'");
?>
<br><br>
<!-- INHALTSBESCHREIBUNG -->
<div class="inhalt" align="center">
	<div class='finanzAlles'>
		<div class='finanzArt'>
			<div align='center' class='finanzUeberschrift einnahmen'><h3>Einnahmen</h3></div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung einnahmen' align='right'>
					Filialumsatz:
				</div>
				<div class='finanzWert einnahmen' align='right'>
					<?php echo $finanzen[8];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung einnahmen' align='right'>
					Geb&auml;udeverkauf:
				</div>
				<div class='finanzWert einnahmen' align='right'>
					<?php echo $finanzen[6];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung einnahmen' align='right'>
					Zinsertr&auml;ge:
				</div>
				<div class='finanzWert einnahmen' align='right'>
					<?php echo $finanzen[9];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung einnahmen' align='right'>
					Geb&auml;udeumbau:
				</div>
				<div class='finanzWert einnahmen' align='right'>
					<?php echo $finanzen[7];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung einnahmen' align='right'>
					<br>
				</div>
				<div class='finanzWert einnahmen' align='right'>
				<br>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung einnahmen unterstrichen' align='right'>
					<b>Gesamteinnahmen:</b>
				</div>
				<div class='finanzWert einnahmen unterstrichen' align='right'>
					<b><?php echo $finanzen[10];?></b>
				</div>
			</div>
		</div>
		<div class='finanzArt'>
			<div align='center' class='finanzUeberschrift ausgaben'><h3>Ausgaben</h3></div>
				<div class='finanzPosition'>
					<div class='finanzBeschreibung ausgaben' align='right'>
						Getr&auml;nkekauf:
					</div>
					<div class='finanzWert ausgaben' align='right'>
						<?php echo $finanzen[13];?>
					</div>
				</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung ausgaben' align='right'>
					Geb&auml;udeneubau:
				</div>
				<div class='finanzWert ausgaben' align='right'>
					<?php echo $finanzen[11];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung ausgaben' align='right'>
					Geb&auml;udeumbau:
				</div>
				<div class='finanzWert ausgaben' align='right'>
					<?php echo $finanzen[12];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung ausgaben' align='right'>
					Mitarbeitergeh&auml;lter:
				</div>
				<div class='finanzWert ausgaben' align='right'>
					<?php echo $finanzen[14];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung ausgaben ' align='right'>
					Zinsaufwendungen:
				</div>
				<div class='finanzWert ausgaben' align='right'>
					<?php echo $finanzen[15];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung ausgaben unterstrichen' align='right'>
					<b>Gesamtausgaben:</b>
				</div>
				<div class='finanzWert ausgaben unterstrichen' align='right'>
					<b><?php echo $finanzen[16];?></b>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='inhalt' align='center'>
	<div class='finanzArt'>
			<div align='center' class='finanzUeberschrift'><h3>Jahresgesamt&uuml;bersicht</h3></div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung' align='right'>
					Kontostand Beginn:
				</div>
				<div class='finanzWert' align='right'>
					<?php echo $finanzen[3];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung' align='right'>
					Einnahmen:
				</div>
				<div class='finanzWert' align='right'>
					<?php echo "+ " . $finanzen[10];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung' align='right'>
					Ausgaben:
				</div>
				<div class='finanzWert' align='right'>
					<?php echo "- " . $finanzen[16];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung' align='right'>
					Kredit:
				</div>
				<div class='finanzWert' align='right'>
					<?php echo "+ " . $finanzen[4];?>
				</div>
			</div>
			<div class='finanzPosition'>
				<div class='finanzBeschreibung unterstrichen' align='right'>
					<b>Kontostand Momentan:</b>
				</div>
				<div class='finanzWert unterstrichen' align='right'>
					<b><?php echo $finanzen[2];?></b>
				</div>
			</div>
		</div>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php include '../footer.php';?>