<?php 	include ("sessionpruefung.php"); include ("functions.php");include ("connect.php");
		$date = date('Y-m-d H:i:s');
		$timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 60 minutes");
		$_SESSION['time'] = date('Y-m-d H:i:s', $timestamp);
?>
    <link href="main.css" rel="stylesheet" type="text/css" />

<html>
	<head>
		<title><?php echo $title; ?></title>
	</head>
	<body>
		<table width="100%" Cellpadding="1" Cellspacing="1" Align="Center">
			<tr Valign="top">
			<td Valign="top" Align="left" Width="250">
				<?php include "home.php"; ?> </td>
			<td Valign="middle" Align="middle">
				<?php include "head.php"; ?> </td>
			</tr>
			<tr>
			<td Valign="top" Align="left" Width="100">
				<?php include "menu.php"; ?> </td>
			<td Valign="top" Align="left">
				
	