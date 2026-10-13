		<?php
				$title="Status";
				include ("header.php");
				$id = id ($_SESSION['nickname']);
				$arbeitsende = arbeitsende ($id);
				$exp = ep ($id);
				$taetigkeit = taetigkeit ($id);
				$dauer = dauer ($id);
				
				if ($dauer == 30){$random_ertrag = rand(8, 12); $faktor=1; $skill_erhoehung=1;}
				else if ($dauer == 60){$random_ertrag = rand(14, 22); $faktor=1.75; $skill_erhoehung=2;}
				else if ($dauer == 120){$random_ertrag = rand(26, 40); $faktor=3; $skill_erhoehung=4;}
				else if ($dauer == 240){$random_ertrag = rand(46, 64); $faktor=4.5; $skill_erhoehung=8;}			
				
				if (date ("Y-m-d H:i:s") >= $arbeitsende)
				{						
					if ($taetigkeit == 1)
					{
						$tierische_nahrung = rohstoffe($id, 'tierische_nahrung');
						$skill = skills ($id, 'jagen');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET jagen = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$random_id=rand(1, 13); // Zufalls-id für Tabelleneintrag erstellen
						$abfrage_gefangenes_tier = mysql_query ("SELECT name, nahrungspunkte FROM kleine_tiere WHERE id = '$random_id'") or die ("Kein Tier abgefragt!");
						$arrayeintrag_gefangenes_tier = mysql_fetch_array($abfrage_gefangenes_tier);
						$gefangenes_tier = $arrayeintrag_gefangenes_tier['name']; // Tier wird anhand der Zufalls-id ausgewählt
						$erhaltene_nahrung = $arrayeintrag_gefangenes_tier['nahrungspunkte'] * $faktor; // Nahrung wird anhand der Zufalls-id ausgewählt
						$nahrung_neu = $tierische_nahrung + $erhaltene_nahrung; // Neue Nahrung in Besitz
						$eintragen = mysql_query("UPDATE rohstoffe SET tierische_nahrung ='$nahrung_neu' WHERE login_id = '$id'") or die ("Kein Eintrag nahrung!");
						if ($dauer=30){echo "Du hast ein $gefangenes_tier gefangen und erhältst $erhaltene_nahrung Nahrungspunkte<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";}
						else {{echo "Du hast mehrere $gefangenes_tier (e) gefangen und erhältst $erhaltene_nahrung Nahrungspunkte<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";}}
					}
					elseif ($taetigkeit == 2)
					{
						$pflanzliche_nahrung = rohstoffe($id, 'pflanzliche_nahrung');
						$skill = skills ($id, 'sammeln');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET sammeln = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$random_id=rand(1, 21); // Zufalls-id für Tabelleneintrag erstellen
						$abfrage_gesammelte_nahrung = mysql_query ("SELECT name, nahrungspunkte FROM pflanzen WHERE id = '$random_id'") or die ("Keine Pflanze abgefragt!");
						$arrayeintrag_gesammelte_nahrung = mysql_fetch_array($abfrage_gesammelte_nahrung);
						$gesammelte_nahrung = $arrayeintrag_gesammelte_nahrung['name']; // Pflanze wird anhand der Zufalls-id ausgewählt
						$erhaltene_nahrung = $arrayeintrag_gesammelte_nahrung['nahrungspunkte'] * $faktor; // Nahrung wird anhand der Zufalls-id ausgewählt
						$nahrung_neu = $pflanzliche_nahrung + $erhaltene_nahrung; // Neue Nahrung in Besitz
						$eintragen = mysql_query("UPDATE rohstoffe SET pflanzliche_nahrung ='$nahrung_neu' WHERE login_id = '$id'") or die ("Kein Eintrag nahrung!");
						echo "Du hast einen Korb voll $gesammelte_nahrung gesammelt und erhältst $erhaltene_nahrung Nahrungspunkte<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 3)
					{
						$tierische_nahrung = rohstoffe($id, 'tierische_nahrung');
						$skill = skills ($id, 'fischen');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET fischen = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$random_id=rand(1, 20); // Zufalls-id für Tabelleneintrag erstellen
						$abfrage_gefangenen_fisch = mysql_query ("SELECT name, nahrungspunkte FROM fische WHERE id = '$random_id'") or die ("Keinen Fisch abgefragt!");
						$arrayeintrag_gefangenen_fisch = mysql_fetch_array($abfrage_gefangenen_fisch);
						$gefangenen_fisch = $arrayeintrag_gefangenen_fisch['name']; // Fisch wird anhand der Zufalls-id ausgewählt
						$erhaltene_nahrung = $arrayeintrag_gefangenen_fisch['nahrungspunkte'] * $faktor; // Nahrung wird anhand der Zufalls-id ausgewählt
						$nahrung_neu = $tierische_nahrung + $erhaltene_nahrung; // Neue Nahrung in Besitz
						$eintragen = mysql_query("UPDATE rohstoffe SET tierische_nahrung ='$nahrung_neu' WHERE login_id = '$id'") or die ("Kein Eintrag nahrung!");
						echo "Dir zappeln eine Hand voll $gefangenen_fisch im Netz, dafür erhältst du $erhaltene_nahrung Nahrungspunkte<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 4)
					{
						$pflanzliche_nahrung = rohstoffe($id, 'pflanzliche_nahrung');
						$skill = skills ($id, 'pilzesuchen');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET pilzesuchen = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$random_id=rand(1, 4); // Zufalls-id für Tabelleneintrag erstellen
						$abfrage_gesammelte_pilze = mysql_query ("SELECT name, nahrungspunkte FROM pilze WHERE id = '$random_id'") or die ("Keine Pflanze abgefragt!");
						$arrayeintrag_gesammelte_pilze = mysql_fetch_array($abfrage_gesammelte_pilze);
						$gesammelte_pilze = $arrayeintrag_gesammelte_pilze['name']; // Pflanze wird anhand der Zufalls-id ausgewählt
						$erhaltene_nahrung = $arrayeintrag_gesammelte_pilze['nahrungspunkte'] * $faktor; // Nahrung wird anhand der Zufalls-id ausgewählt
						$nahrung_neu = $pflanzliche_nahrung + $erhaltene_nahrung; // Neue Nahrung in Besitz
						$eintragen = mysql_query("UPDATE rohstoffe SET pflanzliche_nahrung ='$nahrung_neu' WHERE login_id = '$id'") or die ("Kein Eintrag nahrung!");
						echo "Du hast einen Korb voll $gesammelte_pilze gesammelt und erhältst $erhaltene_nahrung Nahrungspunkte<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 5)
					{
						$holz = rohstoffe($id, 'holz');
						$skill = skills ($id, 'holzfaellen');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET holzfaellen = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						//$random_ertrag = rand(8, 12); // Zufalls-ertrag erstellen
						$holz_neu = $holz + $random_ertrag; // Neues Holz in Besitz
						$eintragen = mysql_query("UPDATE rohstoffe SET holz ='$holz_neu' WHERE login_id = '$id'") or die ("Kein Eintrag holz!");
						echo "Du warst auf Arbeit und bringst $random_ertrag Hölzer mit nach Hause<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 6)
					{
						$stein = rohstoffe($id, 'stein');
						$skill = skills ($id, 'steinmetzen');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET steinmetzen = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						//$random_ertrag = rand(8, 12); // Zufalls-ertrag erstellen
						$stein_neu = $stein + $random_ertrag; // Neues Stein in Besitz
						$eintragen = mysql_query("UPDATE rohstoffe SET stein ='$stein_neu' WHERE login_id = '$id'") or die ("Kein Eintrag stein!");
						echo "Du warst auf Arbeit und bringst $random_ertrag Steine mit nach Hause<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 7)
					{
						$wasser = rohstoffe($id, 'wasser');
						$skill = skills ($id, 'wassersuchen');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET wassersuchen = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						//$random_ertrag = rand(8, 12); // Zufalls-ertrag erstellen
						$wasser_neu = $wasser + $random_ertrag; // Neues Wasser in Besitz
						$eintragen = mysql_query("UPDATE rohstoffe SET wasser ='$wasser_neu' WHERE login_id = '$id'") or die ("Kein Eintrag wasser!");
						echo "Du warst auf Arbeit und bringst $random_ertrag liter Wasser mit nach Hause<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 8)
					{
						$axt = inventar($id, 'axt');
						$skill = skills ($id, 'axt');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET axt = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$axt_neu = $axt + 1; // Neue Äxte in Besitz
						$eintragen = mysql_query("UPDATE inventar SET axt ='$axt_neu' WHERE login_id = '$id'") or die ("Kein Eintrag axt!");
						echo "Herzlichen Glückwunsch! Du hast nun eine Axt hergestellt.<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 9)
					{
						$jagdspeer = inventar($id, 'jagdspeer');
						$skill = skills ($id, 'jagdspeer');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET jagdspeer = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$jagdspeer_neu = $jagdspeer + 1; // Neue Jagdspeere in Besitz
						$eintragen = mysql_query("UPDATE inventar SET jagdspeer ='$jagdspeer_neu' WHERE login_id = '$id'") or die ("Kein Eintrag jagdspeer!");
						echo "Herzlichen Glückwunsch! Du hast nun einen Jagdspeer hergestellt.<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 10)
					{
						$wasserbeutel = inventar($id, 'wasserbeutel');
						$skill = skills ($id, 'wasserbeutel');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET wasserbeutel = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$wasserschlauch_neu = $wasserbeutel + 1; // Neue Wasserschläuche in Besitz
						$eintragen = mysql_query("UPDATE inventar SET wasserbeutel ='$wasserschlauch_neu' WHERE login_id = '$id'") or die ("Kein Eintrag wasserbeutel!");
						echo "Herzlichen Glückwunsch! Du hast nun einen wasserbeutel hergestellt.<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 11)
					{
						$korb = inventar($id, 'korb');
						$skill = skills ($id, 'korb');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET korb = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$korb_neu = $korb + 1; // Neue Körbe in Besitz
						$eintragen = mysql_query("UPDATE inventar SET korb ='$korb_neu' WHERE login_id = '$id'") or die ("Kein Eintrag wasser!");
						echo "Herzlichen Glückwunsch! Du hast nun einen Korb hergestellt.<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 12)
					{
						$lendenschurz = inventar($id, 'lendenschurz');
						$skill = skills ($id, 'lendenschurz');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET lendenschurz = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$lendenschurz_neu = $lendenschurz + 1; // Neue Lendenschürze in Besitz
						$eintragen = mysql_query("UPDATE inventar SET lendenschurz ='$lendenschurz_neu' WHERE login_id = '$id'") or die ("Kein Eintrag wasser!");
						echo "Herzlichen Glückwunsch! Du hast nun einen Lendenschurz hergestellt.<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
					elseif ($taetigkeit == 13)
					{
						$schuhe = inventar($id, 'schuhe');
						$skill = skills ($id, 'schuhe');
						$skill_neu = $skill + $skill_erhoehung;
						$eintragen_skill_neu = mysql_query("UPDATE skills SET schuhe = '$skill_neu' WHERE login_id = '$id'") or die ("Kein skill abgefragt!");
						$schuhe_neu = $schuhe + 1; // Neue Schuhe in Besitz
						$eintragen = mysql_query("UPDATE inventar SET schuhe ='$schuhe_neu' WHERE login_id = '$id'") or die ("Kein Eintrag wasser!");
						echo "Herzlichen Glückwunsch! Du hast nun ein paar Schuhe hergestellt.<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
					}
						$arbeitsende = mysql_query("UPDATE charakter SET arbeitsende = '9999-00-00 00:00:00' WHERE login_id = '$id'") or die ("Kein Eintrag arbeitsende!");
						$taetigkeit = mysql_query("UPDATE charakter SET taetigkeit = '0' WHERE login_id = '$id'") or die ("Kein Eintrag arbeitsende!");
						$dauer = mysql_query("UPDATE charakter SET dauer = '0' WHERE login_id = '$id'") or die ("Kein Eintrag arbeitsende!");
						$exp_neu = $exp + 1;//erfahrungspunkte erhöhen
						$eintragen_exp = mysql_query("UPDATE charakter SET erfahrungspunkte ='$exp_neu' WHERE login_id = '$id'") or die ("Kein Eintrag wasser!");
				}
				elseif ($taetigkeit == 0)
				{
						echo "Du hast momentan nichts zu tun<br><br><a align='center' href='Hauptseite.php'>Zurück</a>";
				}
				else
				{
						echo "Du bist bereits unterwegs!<br><br>";
						include("status_header.php");
						echo "<br><a align='center' href='Hauptseite.php'>Zurück</a>";
				}
				
				include ("footer.php");
			?>
