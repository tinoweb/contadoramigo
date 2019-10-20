<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

session_start();

// paga serrao anterior do pagamento.
unset($_SESSION['pagamento']);

require_once "conect.php";
require_once "servico_pagamento_cartao.php";
require_once "DataBaseMySQL/ContratoAceito.php";

$contratoAceito = new ContratoAceito();
	
// Verifica se o poste foi informado.
if(isset($_POST['id_user']) && isset($_POST['contratoId']) && isset($_POST['contadorId']) && isset($_POST['valor'])) {

	$tipo = $_POST['tipo']; 
	$valor = $_POST['valor']; 
	$data = $_POST['data'];
	$idUser = $_POST['id_user'];
	$contratoId = $_POST['contratoId'];
	$contadorId = $_POST['contadorId'];

	if(!isset($_SESSION['cartao'])) {

		// grava contrato aceito.
		$contratoAceito->InclirContratoAceito($idUser, $contratoId, $contadorId, $valor);

		$_SESSION['pagamento'] = 'boleto';
		$_SESSION['LinkBoleto'] = 'boleto.class.php?tipo='. $tipo .'&valor='. $valor .'&data='. $data .'&id_user='. $idUser.'&contadorId='.$contadorId;
				
		// Redireciona para o boleto.
		header( 'Location: servico-contador-sucesso.php' );

		die();

	} else {

		// Apaga sessão do cartao.
		unset($_SESSION['cartao']);

		// Instancia classe que e responsavel por realizar o pagamento.
		$servicoPagamentoCartao = new ServicoPagamentoCartao();

		// Chama o método para realizar o pagamento.
		$servicoPagamentoCartao->RealizaPagamento($tipo, $valor, $data, $idUser, $contadorId, $contratoId);
	}
}
?>