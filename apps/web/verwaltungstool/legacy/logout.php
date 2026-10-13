<?php session_start();

// Session zerstören
session_destroy();

header("location: http://verwaltung.i-use-my-brain.com");
?>