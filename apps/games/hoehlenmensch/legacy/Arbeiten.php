<?php  
$title="Arbeiten";
include ("header.php");

$nr=(integer)$_GET["Arbeit"];
$dauer=(integer)$_GET["dauer"];
if ($nr>=1 and $nr<=7 and is_int($nr)===true)
{echo arbeiten ($nr, $dauer);
//include ("status_header.php");
}

else if ($nr>=8 and $nr<=13 and is_int($nr)===true)
{echo arbeiten ($nr);
//include ("status_header.php");
}

else
{echo "Falsche Arbeitsangabe!";}

include ("footer.php");
?>