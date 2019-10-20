<?php

	// ini_set('display_errors',1);
	// ini_set('display_startup_erros',1);
	// error_reporting(E_ALL);
	
	// echo "<pre>";
		// print_r($_GET);
	// echo "</pre>";
	
	// Incluir o arquivo de conexão. 
	require_once('../conect.php');
	
	// Inclui arquivo que manipula os dados.
	require_once('../Model/PagamentoContador/PagamentoContadorData.php');
	
	// $pagamentoId = $contadorId = $dataPagamento = $valorpagamento = $idsCobrancaContador = ''; 
	
	// // Verifica se os parametros foram informados para da inicio a gravaçâo dos dados.
	if(isset($_GET['acao']) && !empty($_GET['acao'])) {
		
		// Invoca a classe que manipula os dados.
		$pagamentoContador = new PagamentoContadorData();
	
		// Verifica se e uma atualização ou inclusão.
		if($_GET['acao'] == 'update'){
			
			$pagamentoId = $_GET['pagamentoId'];
			$contadorId = $_GET['contadorId'];
			$valorpagamento = str_replace(',','.',str_replace('.','',$_GET['valorPagamento']));
			$idsCobrancaContador = $_GET['idsCobrancaContador'];
			
			// realiza a atualização.
			$pagamentoContador->AlteraPagamentoContador($pagamentoId, $valorpagamento, $idsCobrancaContador);
		} elseif($_GET['acao'] == 'insert'){
			
			$contadorId = $_GET['contadorId'];
			$dataPagamento = $_GET['dataPagamento']; 
			$valorpagamento = str_replace(',','.',str_replace('.','',$_GET['valorPagamento']));
			$idsCobrancaContador = $_GET['idsCobrancaContador'];
			
			// realiza a inclusao.
			$pagamentoContador->InclusaoPagamentoContador($contadorId, $dataPagamento, $valorpagamento, $idsCobrancaContador);
		}
	}

	// retora para a tela de pagamento.
	header("Location: pagamentocontador.php?contadorId=".$contadorId);
	