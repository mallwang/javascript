<?php
$teststring = $_POST["test"];

$regex = "/([a-zA-Z0-9]+)@{1}(([a-zA-Z]+)\.+)([a-zA-Z]+)/";
$passt = preg_match("$regex", $teststring);

echo "<div align='center'>";
//echo "Dein Regex-String: <b><h3>" . $regex . "</h3></b><p>";
if ($passt==true){
    echo "Das ist eine E-Mail Adresse!";
}
else{
    echo "Das ist leider keine E-Mail Adresse!";
}
echo "<p><a href='index.php'>Zur&uuml;ck</a></div>";

?>