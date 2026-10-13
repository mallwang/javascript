<?php

require_once ("testkarte.php");

$map = new MyMap();
$map->setSize(10, 8);
$map->setFieldSize(100, 80);
$map->setField(1, 1, array("href"=>'Wald.html', "src"=>'images/Karte_Wald.jpg', "color"=>'#006699', "caption"=>'Wald') );
$map->setField(2, 1, array("href"=>'Wald.html', "src"=>'images/Karte_Wald.jpg', "color"=>'#006699', "caption"=>'Wald') );
$map->setField(8, 5, array("href"=>'Wald.html', "src"=>'images/Karte_See.jpg', "color"=>'#006699', "caption"=>'See') );
$map->setField(2, 3, array("href"=>'Wald.html', "src"=>'images/Karte_See.jpg', "color"=>'#006699', "caption"=>'See') );
$map->setField(6, 2, array("href"=>'Wald.html', "src"=>'images/Karte_See.jpg', "color"=>'#006699', "caption"=>'See') );
$map->setField(3, 2, array("href"=>'Wald.html', "src"=>'images/Karte_See.jpg', "color"=>'#006699', "caption"=>'See') );
$map->setField(3, 4, array("href"=>'Wald.html', "src"=>'images/Karte_Wald.jpg', "color"=>'#006699', "caption"=>'Wald') );
$map->setField(4, 4, array("href"=>'Wald.html', "src"=>'images/Karte_Wald.jpg', "color"=>'#006699', "caption"=>'Wald') );
$map->setField(2, 2, array("href"=>'Wald.html', "src"=>'images/Karte_Wald.jpg', "color"=>'#CCCCCC', "caption"=>'Berge') );
$map->setWindowSize(4, 4);        	// Das Sichtfenster soll 9x9 Felder groß sein
$map->setWindowPosition(3, 6); 		// Position oben/links des Fensters
//$map->showWindow();                 // Ausgabe des Sichtfensters
$map->show();

?>