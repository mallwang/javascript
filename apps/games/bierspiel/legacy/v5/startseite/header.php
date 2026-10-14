<?php include("../huelle/connect.php"); /*include ("../sonstiges/dbAbfragen.php");*/?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
    <head><script type="text/javascript" async="" src="https://ssl.google-analytics.com/ga.js"></script>
        <title><?php echo $title;?></title>
        
        <meta charset="utf-8">
        <meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, maximum-scale=1">
        <meta name="description" content="Metro UI CSS">
        <meta name="author" content="Sergey Pimenov">
        <meta name="keywords" content="windows 8, modern style, Metro UI, style, modern, css, framework">
    
        <link href="../css/modern.css" rel="stylesheet">
        <link href="../css/modern-responsive.css" rel="stylesheet">
        <link href="../css/site.css" rel="stylesheet" type="text/css">
        <link href="http://share.pluso.ru/pluso.css" type="text/css" rel="stylesheet">
            
        
        <script src="http://share.pluso.ru/pluso.js" charset="UTF-8"></script>
        <script src="http://share.pluso.ru/counter.php?u=http%3A%2F%2Fmetroui.org.ua%2F%23&amp;k=PLUSO_KEY" charset="UTF-8"></script>
        <script src="http://share.pluso.ru/ping.php?t=show&amp;u=http%3A%2F%2Fmetroui.org.ua%2F%23&amp;ref=&amp;w=1920&amp;h=1080" charset="UTF-8"></script>
        <script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
        <script src="../js/jquery-1.8.2.min.js"></script>
        <script src="../js/google-analytics.js"></script>
        <script src="../js/github.info.js"></script>
        <script src="../js/dropdown.js"></script>
    </head>
    
    <body class="modern-ui">
        <div class="page">
            <script type="text/javascript">
                function getWindowsSize(){
                    $("#sizeX").html('W: '+$(window).width());
                    $("#sizeY").html('H: '+$(window).height());
                }
                $(function(){
                    getWindowsSize();
                    $(window).resize(function(){
                    getWindowsSize()
                    });
                })
            </script>
    
        <div class="navigation-bar">
            <div class="navigation-bar-inner">
                <span class="menu-pull"></span>
        
                <div class="brand">
                    <a href="../index.php" title="Home"><span class="metro-ui-logo place-left"></span></a>
                    <span class="name"><?php echo $title;?></span>
                </div>
        
                <ul>
                    <li><a href="../index.php">Home</a></li>
        
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Das Spiel</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="bierspiel_aufbau.php">Der Aufbau</a></li>
                            <li><a href="screenshots.php">Screenshots</a></li>
                        </ul>
                    </li>
        
                    <li data-role="dropdown" class="sub-menu">
                        <a href="#">Kontakt</a>
                        <ul class="dropdown-menu" style="">
                            <li><a href="mailto:MAIL_CONTACT@example.com">E-Mail</a></li>
                            <li><a href="support_ticket.php">Support Ticket</a></li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
        </div>