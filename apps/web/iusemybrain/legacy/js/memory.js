var zeilen = 4;					// Anzahl der Zeilen
var spalten = 5;				// Anzahl der Spalten

var bildpath = 'img/memory/';	// Verzeichnis der Bilder (Spielkarten)
var bildew = '.jpg';			// Dateierweiterung der Bilddateien (Spielkarten)
var deckblatt = 'deck.jpg';		// Bilddatei des Deckblattes
var offen = 'aufgedeckt.jpg';			// Bilddatei für aufgedeckte Felder
var bild_w = 100;				// Breite der Bilder
var bild_h = 100;				// Höhe der Bilder

var wertung = 5;				// Punkte für richtiges "Paar"

var Bilder = zeilen*spalten;

var zugnr = 0;
var geszug = 0;
var erg = 0;
var punkte = 0;
var richtig = 0;
var falsch = 0;

start();

var pos=1;
document.write('<table align="center">');
for (z = 0; z < zeilen; z++)
{
	document.write('<tr height="' + bild_h + '">');
	for (s = 0; s < spalten; s++)
	{
		document.write('<td width="' + bild_w + '" height="' + bild_h + '"><a href="javascript:zug(' + pos + ')"><img class="img-rounded" id="' + pos + '" src="' + bildpath + deckblatt + '" alt="" height="' + bild_h + '" width="' + bild_w + '" name=' + pos + '></a></td>');
		pos++;
	}
	document.write('</tr>');
}
document.write('</table>');

// Bilder verteilen
function start()
{
	// Array mit (zeilen*spalten) Stellen erstellen
	BildW = new Array(Bilder);
	var i=1;
	// Bis i = der Anzahl an Bildern
	while(i <= Bilder){
		var gleich = 0;
		BildW[i] = Math.round(Math.random()*(Bilder/2));
		if (BildW[i] < 1) BildW[i] = 1;
		if (BildW[i] > (Bilder/2)) BildW[i] = (Bilder/2);
		if (i > 1){
			for (var vergl=1; vergl <= i-1; vergl++){
				if (BildW[i] == BildW[vergl]){
					var gleich = gleich + 1;
					if (gleich == 2) break;
				}
			}
		}	
		if (gleich < 2) i++;
	}
}

// Zug
function zug(feld){
	if (BildW[feld] != "g"){
		zugnr = zugnr + 1;
		Bild_aktuell = BildW[feld];
		// erstes Bild wird aufgedeckt
		if (zugnr == 1){
			if (erg == 1){
				aendern(zug1pos, offen);
				aendern(zug2pos, offen);
				erg = 0;
			}
			if (erg == 2){
				aendern(zug1pos, deckblatt);
				aendern(zug2pos, deckblatt);
				erg = 0;
			}
			zug1pos = feld;
		}
		// zweites Bild wird aufgedeckt
		if (zugnr == 2){
			geszug = geszug + 1;
			if (feld != zug1pos){
				zugnr = 0;
				zug2pos = feld;
				if (BildW[feld] == BildW[zug1pos]){
					punkte = punkte + wertung;
					richtig = richtig + 1;
					BildW[feld] = "g";
					BildW[zug1pos] = "g";
					erg = 1;
					if (richtig == Bilder/2){
						ausgabe();
						aendern(feld, Bild_aktuell + bildew);
						var mitteilung = "";
						if (punkte<10){
							mitteilung = "G%FCckwunsch, du hast " + punkte + " Punkte erreicht%21\nDas ist ein sehr verbesserungswürdiges Ergebnis%21\nNochmal spielen%3F";
						}
						else if(punkte>=10 && punkte<20){
							mitteilung = "G%FCckwunsch, du hast " + punkte + " Punkte erreicht%21\nDas ist ein verbesserungswürdiges Ergebnis%21\nNochmal spielen%3F";
						}
						else if(punkte>=20 && punkte<30){
							mitteilung = "G%FCckwunsch, du hast " + punkte + " Punkte erreicht%21\nDas ist ein schlechtes Ergebnis%21\nNochmal spielen%3F";
						}
						else if(punkte>=30 && punkte<40){
							mitteilung = "G%FCckwunsch, du hast " + punkte + " Punkte erreicht%21\nDas ist ein gutes Ergebnis%21\nNochmal spielen%3F";
						}
						else if(punkte>=40 && punkte<45){
							mitteilung = "G%FCckwunsch, du hast " + punkte + " Punkte erreicht%21\nDas ist ein sehr gutes Ergebnis%21\nNochmal spielen%3F";
						}
						else if(punkte>=45 && punkte<50){
							mitteilung = "G%FCckwunsch, du hast " + punkte + " Punkte erreicht%21\nDas ist ein au%DFerordentliches Ergebnis%21\nNochmal spielen%3F";
						}
						else if(punkte=50){
							mitteilung = "G%FCckwunsch, du hast " + punkte + " Punkte erreicht%21\nDas ist ein perfektes Ergebnis%21\nNochmal spielen%3F";
						}
						var Eingabe = confirm(unescape(mitteilung));
						if (Eingabe){
							window.location.href = "memory.php";
						}
					}
					else{
						setTimeout(function(){aendern(feld, offen)},500);
						setTimeout(function(){aendern(zug1pos, offen)},500);
					}
				}
				else{
					erg = 2;
					punkte = punkte - 2;
					falsch = falsch + 1;
					setTimeout(function(){aendern(feld, deckblatt)},500);
					setTimeout(function(){aendern(zug1pos, deckblatt)},500);
				}
			}
			else{
				zugnr = 1;
			}
		}
		aendern(feld, Bild_aktuell + bildew);
		ausgabe();
	}
}

// Bild ändern
function aendern(position, bildname){
	document.getElementById(position).src = bildpath + bildname;
}

// Spielinfos ausgeben
function ausgabe(){
	document.game.punkt_aus.value = punkte;
	document.game.geszug_aus.value = geszug;
	document.game.falsch_aus.value = falsch;
}
