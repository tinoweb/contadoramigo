<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */	
require_once('../Model/AreaContador/AreaContadorData.php');
require_once('../Model/DadosContador/DadosContadorData.php');
require_once 'util-Controller.php';

$rows = 20 ; 
$page =  1 ;
$paransaAray = false;
$valor = '';
$filtro = '';
$filtro2 = '';

$contadorId = 0;

$tagTable = "";
$paginacao = "";

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

if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) { 
	$contadorId = $_GET['contadorId'];
	$paransaAray['contadorId'] = $contadorId;
}

$areaContadorData = new AreaContadorData();
 
if(isset($_GET['contadorId']) && !empty($_GET['contadorId'])) { 

	// Gera a grid com os dados do clientes.
	$lista = $areaContadorData->listaCarteiraClienteContador($contadorId, $page, $rows, $filtro2, $valor);


	$tagTable = " <table style='width: 100%;' cellpadding='5'> "
		."	<tbody> "
		."		<tr> "	
		."        	<th align='center'>Id</th> "
		."        	<th align='left'>Assinante</th> "
		."        	<th align='center'>Data de Adesão</th> "	
		."		</tr> ";

	foreach($lista as $val) {
		if(is_object($val)){
			$tagTable .= "<tr>"
				."	<td class='td_calendario'>".$val->getClienteId()."</td> "
				."	<td class='td_calendario' align='left'>"
				."		<form action='realiza_acesso_cliente.php' method='post' target='_blank' style='margin-bottom: 0px;' >"	
				."			<input type='hidden' name='clienteid' value='".$val->getClienteId()."' /> "
				."			<input type='hidden' name='sessionId' value='".$contadorId."' /> "	
				."			<button class='gridButton' type='submit' style='color: #3D6D9E; text-decoration: underline; background:none;'>".$val->getAssinante()."</button>"
				."		</form> "
				."  </td>"
				."	<td class='td_calendario' align='center'>".$val->getDataContrato()."</td> "
				."</tr>	";
		}	
	}

	$tagTable .= "	</table>";	

	//Gerencia a paginacao.	
	$link = "clientes_premium_contador.php";

	$amount = (isset($lista['QuantidadeItemLista']) && !empty($lista['QuantidadeItemLista']) ? $lista['QuantidadeItemLista'] : 0 );

	// Invoca o método para gera a Paginção.
	$paginacao = Util::GeraPagination($rows, $page, $amount, $link, $paransaAray);
}

function pegaNomeContador() {
	
	if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) {
	
		// Pega o dados do contador.
		$contadorId = $_GET['contadorId'];
	
		$dadosContador =  new DadosContadorData();
		$dados = $dadosContador->GetNameContador($contadorId);
		
		$nome = explode(" ", $dados->getNome());
		
		$nome = $nome[0]." ".$nome[1];
		
		return $nome;
	}	
}