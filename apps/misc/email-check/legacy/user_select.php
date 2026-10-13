<?php $title= "Suche"; include ("header.php");

$name = $_POST['nickname'];
$user_id = id($name);

$suchergebnis = getProperty1('name', 'user', 'name', $name);

$x = mysql_query ("SELECT * FROM blogs WHERE user_id = '$user_id'");
$blogeintraege = mysql_num_rows($x);

$x = mysql_query ("SELECT * FROM kommentare WHERE user_id = '$user_id'");
$kommentare = mysql_num_rows($x);


if ($suchergebnis[0])
{
    $persdaten_abfrage = getProperty6('vorname', 'nachname', 'DATE_FORMAT(gebDatum,"%d.%m.%Y") AS datum', 'wohnort', 'hobbys', 'beschreibung', 'user', 'id', $user_id);
    

?>

<div align="center">
    <h2><?php echo "$name";?></h3>
    <a href='abbonieren.php'>Abbonieren</a><br><br>
</div>

<table>
    <tr>
        <td width="250">Vorname: </td>
        <td align="left"><?php echo "$persdaten_abfrage[0]";?> </td>
    </tr>
    <tr>
        <td width="250">Nachname: </td>
        <td align="left"><?php echo "$persdaten_abfrage[1]";?> </td>
    </tr>
    <tr>
        <td width="250">Blogeintr&auml;ge: </td>
        <td align="left"><?php echo "$blogeintraege";?> <a href='show_blogs.php?user_id=<?php echo "$user_id";?>'>Blogeintr&auml;ge anzeigen</a> </td>
    </tr>
    <tr>
        <td width="250">Kommentare: </td>
        <td align="left"><?php echo "$kommentare";?> </td>
    </tr>
    <tr>
        <td width="250">Geburtsdatum: </td>
        <td align="left"><?php echo "$persdaten_abfrage[2]";?></td>
    </tr>
    <tr>
        <td width="250">Wohnort: </td>
        <td align="left"><?php echo "$persdaten_abfrage[3]";?> </td>
    </tr>
    <tr>
        <td width="250">Hobbys: </td>
        <td align="left"><?php echo "$persdaten_abfrage[4]";?> </td>
    </tr>
    <tr>
        <td width="250" valign="top">Beschreibung: </td>
        <td><textarea class="textfeld" disabled="disabled" cols="50" rows="10" name="text"><?php echo "$persdaten_abfrage[5]";?></textarea></td>
    </tr>
</table>

<?php
    echo "<br><div align='center'><a href='suche.php'>Zur&uuml;ck zur Suche</a></div>";
}

else
{
    echo "Dieser Nutzter existiert nicht <br><br>";
    echo "<div align='center'><a href='suche.php'>Zur&uuml;ck zur Suche</a></div>";
}
?>


<?php include("footer.php"); ?>