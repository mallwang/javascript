<?php session_start();
	
	if(!isset($_SESSION['nickname'])){
		echo "Für diesen Inhalt haben Sie leider keinen Zugang! Bitte loggen Sie sich ein.";
	}
	
?>