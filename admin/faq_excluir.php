<?php
include '../conect.php';
include '../session.php';
include 'check_login.php';

$faq = $_GET["id"];

$sql = "DELETE FROM faq WHERE id_faq='" . $faq . "'";
$resultado = mysql_query($sql)
or die (mysql_error());

header('Location: faq_lista.php' );
?>