<?php
include "conect.php";
session_start();

$codigo = $_GET["codigo"];

$sql = "UPDATE suporte SET status='Não Respondido' WHERE idPostagem='$codigo'";
$resultado = mysql_query($sql)
or die (mysql_error());

header("Location: suporte_visualizar.php?codigo=$codigo");
?>