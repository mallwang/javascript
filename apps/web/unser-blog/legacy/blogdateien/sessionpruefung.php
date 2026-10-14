<html>
	<head>
		<meta charset="UTF-8"/>
	</head>
	<link rel="icon" href="mail.ico" type="image/x-icon">
	<link href="modern.css" rel="stylesheet" type="text/css" />
	<body>
<?php
	if(!isset($_SESSION['nickname']))
	{
	echo "<div align='center'>
				<table width='800'>
					<td  align = 'center'><h2 class='UEschrift'> HERZLICH WILLKOMMEN IN UNSEREM BLOG </h2></td>
				</table>
			      <table class='farbeTable'><br>
					<td align='center'>
						  <div>
							<br><h4 class='farbe_grau'>Bitte erst <a href='http://www.unser-source.co.de/index.php'>einloggen</a></h4><br>
						  </div>
					</td>
			      </table>
		    </div>
		";
	exit;
	}
?>
	</body>
</html>




