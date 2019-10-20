<?php 
include 'conect.php';
include 'session.php';
include 'check_login.php';

$socio = $_GET["socio"];

mysql_query("UPDATE dados_do_responsavel SET status = 0 WHERE idSocio = ".$socio."");

header('Location: meus_dados_socio.php?editar=' . $socio );
?>