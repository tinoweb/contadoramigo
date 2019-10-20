<?
session_start();
$_SESSION['teste'] = "TESTE";

header('location: pg2.php');
?>