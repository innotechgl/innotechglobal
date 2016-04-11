<?php
session_start();
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=emails.csv");

echo $_SESSION['csv'];

?>

