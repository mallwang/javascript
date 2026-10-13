<?php $title="Produktion"; include ("header.php");

$art=$_GET["name"];


if ($art=="axt") {$nr=8;}
else if ($art=="jagdspeer") {$nr=9;}
else if ($art=="wasserschlauch") {$nr=10;}
else if ($art=="korb") {$nr=11;}
else if ($art=="lendenschurz") {$nr=12;}
else if ($art=="schuhe") {$nr=13;}

$dauer=60;

echo arbeiten ($nr, $dauer);

include ("footer.php"); ?>