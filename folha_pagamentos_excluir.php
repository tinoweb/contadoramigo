<?php 
session_start();

include "conect.php";

$ID = $_GET["excluir"];

if(isset($_GET["idLivroCaixa"]) && $_GET["idLivroCaixa"] != '' && is_numeric($_GET["idLivroCaixa"])){
	$sqlDeleteLivroCaixa = "DELETE FROM user_" . $_SESSION["id_empresaSecao"] . "_livro_caixa WHERE id = " . $_GET["idLivroCaixa"];
	mysql_query($sqlDeleteLivroCaixa);
}

$sql="DELETE FROM dados_pagamentos WHERE id_pagto = " . $ID . "";

$resultado = mysql_query($sql)
or die (mysql_error());

if(isset($_REQUEST['categoria'])){
	$separadorParam = '?';
	$txtParamCategoria = '&categoria='.$_REQUEST['categoria'];
}
if(isset($_REQUEST['nome'])){
	$separadorParam = '?';
	$txtParamNome = '&nome='.$_REQUEST['nome'];
}
if(isset($_REQUEST['dataInicio'])){
	$separadorParam = '?';
	$txtParamDataInicio = '&dataInicio='.$_REQUEST['dataInicio'];
}
if(isset($_REQUEST['dataFim'])){
	$separadorParam = '?';
	$txtParamDataFim = '&dataFim='.$_REQUEST['dataFim'];
}
if(isset($_REQUEST['periodoMes'])){
	$separadorParam = '?';
	$txtParamPeriodoMes = '&periodoMes='.$_REQUEST['periodoMes'];
}
if(isset($_REQUEST['periodoAno'])){
	$separadorParam = '?';
	$txtParamPeriodoAno = '&periodoAno='.$_REQUEST['periodoAno'];
}
if(isset($_REQUEST['hddTipoFiltro'])){
	$separadorParam = '?';
	$txtParamTipoFiltro = '&hddTipoFiltro='.$_REQUEST['hddTipoFiltro'];
}

if(isset($_REQUEST['categoria'])){
}

header('Location: ' . $_SERVER['HTTP_REFERER'] . $separadorParam . $txtParamCategoria . $txtParamNome . $txtParamDataInicio . $txtParamDataFim . $txtParamPeriodoMes . $txtParamPeriodoAno . $txtParamTipoFiltro );
exit;
?>