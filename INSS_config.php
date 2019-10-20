<?
session_start();

//if(!isset($_SESSION['INSS_variaveis'])){
//	$_SESSION['INSS_variaveis'] = array();
//}

if(isset($_POST['IVeoutros'])){
	$_SESSION['INSS_variaveis']['cod_pagamentos'] = '2003';
	$_SESSION['INSS_variaveis']['optante_simples'] = '2';
}

if(isset($_POST['somenteIV'])){
	$_SESSION['INSS_variaveis']['cod_pagamentos'] = '2100';
	$_SESSION['INSS_variaveis']['optante_simples'] = '1';
}

if(isset($_POST['valor'])){
	$_SESSION['INSS_variaveis']['valor'] = $_POST['valor'];
}

?>