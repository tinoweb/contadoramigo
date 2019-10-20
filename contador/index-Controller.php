<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */	
require_once('../Model/AreaContador/AreaContadorData.php');
require_once 'util-Controller.php';

$rows = 20 ; 
$page =  1 ;
$paransaAray = false;
$valor = '';
$status = '';
$opcoes = '';
$ordem = '';
$filtro = '';
$filtro2 = '';

if(isset($_GET['page']) && !empty($_GET['page'])){
	$page = $_GET['page'];
}

if(isset($_GET['rows']) && !empty($_GET['rows'])){
	$rows = $_GET['rows'];
	$paransaAray['rows'] = $rows;
}

if(isset($_GET['filtro']) && !empty($_GET['filtro'])){
	$filtro = $_GET['filtro'];
	$paransaAray['filtro'] = $filtro;
}

if(isset($_GET['ordem']) && !empty($_GET['ordem'])){
	$ordem = $_GET['ordem'];
}

if(isset($_GET['valor']) && !empty($_GET['valor'])){
	
	// So pega o filtro se o valor nao for embranco.
	$filtro2 = $filtro;
	
	if($filtro == 'id') {
		
		$valor = (is_numeric($_GET['valor']) ? $_GET['valor'] : 0);
		$paransaAray['valor'] = $valor;
		
	} else {
		$valor = $_GET['valor'];
		$paransaAray['valor'] = $valor;
	}
}

if(isset($_GET['status']) && !empty($_GET['status'])){	
	$status = $_GET['status'];
	$paransaAray['status'] = $status;
}

if(isset($_GET['opcoes']) && !empty($_GET['opcoes'])){
	
	$opcoes = $_GET['opcoes'];
	$paransaAray['opcoes'] = $opcoes;
}


$areaContadorData = new AreaContadorData();

 $getSession = json_decode($_SESSION['DadosContador']);
 
// Gera a grid com os dados do clientes.
$lista = $areaContadorData->listaCarteiraClienteContador($getSession->contadorId, $page, $rows, $filtro2, $valor, $status, $opcoes, $ordem);


$tagTable = " <table style='width: 100%;' cellpadding='5'> "
	."	<tbody> "
	."		<tr> "	
	."        	<th align='center'>Id</th> "
	."        	<th align='left'><a href='../contador/index.php?ordem=2&valor=".$valor."&opcoes=".$opcoes."&status=".$status."&filtro=".$filtro."' style='color: white'>Assinante</a></th> "
	."        	<th align='center'>Status</th> "
	."        	<th align='center'><a href='../contador/index.php?ordem=1&valor=".$valor."&opcoes=".$opcoes."&status=".$status."&filtro=".$filtro."' style='color: white'>Data de Adesão</a></th> "	
	."		</tr> ";
 	
foreach($lista as $val) {
	if(is_object($val)){
		$tagTable .= "<tr>"
			."	<td class='td_calendario'>".$val->getClienteId()."</td> "
			."	<td class='td_calendario' align='left'>"
			."		<form action='realiza_acesso_cliente.php' method='post' target='_blank' style='margin-bottom: 0px;' >"	
			."			<input type='hidden' name='clienteid' value='".$val->getClienteId()."' /> "
			."			<input type='hidden' name='sessionId' value='".$getSession->idsession."' /> "	
			."			<button class='gridButton' type='submit' style='color: #3D6D9E; text-decoration: underline;'>".$val->getAssinante()."</button>"
			."		</form> "
			."  </td>"
			."	<td class='td_calendario' align='center'>".$val->getStatus()."</td> "
			."	<td class='td_calendario' align='center'>".$val->getDataContrato()."</td> "
			."</tr>	";
	}	
}

$tagTable .= "	</table>";	

//Gerencia a paginacao.	
$link = "index.php";

$amount = (isset($lista['QuantidadeItemLista']) && !empty($lista['QuantidadeItemLista']) ? $lista['QuantidadeItemLista'] : 0 );

// Invoca o método para gera a Paginção.
$paginacao = Util::GeraPagination($rows, $page, $amount, $link, $paransaAray);	