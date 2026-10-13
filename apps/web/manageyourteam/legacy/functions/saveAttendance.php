<?php

session_start ();
// DB-Klasse includen
require_once '../inc/DB.php';
include 'lib/funktionen.php';
// Datenbankverbindung herstellen
$db = new DB ();
$con = $db->getConnection ();
// Daten übernehmen
$CHANGES = json_decode ( $_POST ['changes'] );
$appID = mysql_real_escape_string ( $_POST ['appID'] );

$success = true;
$i = 0;
while ( $i < count ( $CHANGES ) ) {
	$allTeams = $db->createPlayerTraining ( $CHANGES [$i] [0], $appID, $CHANGES [$i] [1] );
	if ($allTeams['result'] == false) {
		$success = false;
	}
	$i ++;
}
if ($success) {
	echo "true";
} else
	echo "false";

