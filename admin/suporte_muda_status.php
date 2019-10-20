<?php
include "../conect.php";
session_start();

$Status = $_POST["selMudaStatus"];
$Codigo = $_POST["hidCodigo"];

$sql = "UPDATE suporte SET status='$Status' WHERE idPostagem='$Codigo'";
$resultado = mysql_query($sql)
or die (mysql_error());

header("Location: suporte_visualizar.php?codigo=$Codigo");
?>