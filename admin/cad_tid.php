<?php
include "../conect.php";
$idPagto = $_POST["id_pagto"];
$numero_TID = ($_POST["valor"]);

//Atualizar dados em dados da empresa.
$sql = "UPDATE relatorio_cobranca SET tid = '" . $numero_TID . "' WHERE idRelatorio=" . $idPagto;
$resultado = mysql_query($sql)
or die (mysql_error());
echo $numero_TID;
?>