<?php
include "../conect.php";
$idPagto = $_POST["id_pagto"];
if(strlen($_POST["valor"]) > 2){
	$numero_NF = str_pad($_POST["valor"],4,"0",STR_PAD_LEFT);
}else{
	$numero_NF = '';
}

//Atualizar dados em dados da empresa.
$sql = "UPDATE relatorio_cobranca SET numero_NF = '" . $numero_NF . "', emissao_NF = " . ($numero_NF != '' ? '1' : '0') . " WHERE idRelatorio=" . $idPagto;
$resultado = mysql_query($sql)
or die (mysql_error());
echo $numero_NF;

?>