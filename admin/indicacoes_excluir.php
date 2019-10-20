<?php
include '../conect.php';
include '../session.php';
include 'check_login.php';

$id = $_GET["linha"];

$sql = "DELETE FROM dados_indicacoes WHERE id='" . $id . "'";
$resultado = mysql_query($sql)
or die (mysql_error());

header('Location: indicacoes.php' );
?>