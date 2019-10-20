<?php 
/**
 * Data: 20/07/2017
 * Auntor: Átano de FariasJacinto
 */
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

// Relaiza a requisição do arquivo de conexão
require_once('../conect.php');

// Realiza a requisição do arquivo que retorna o objeto com os Lista de porcentagem do inss.
require_once('../Model/TabelaINSS/TabelaINSSData.php');

if(isset($_POST['IncluirINSS'])) {
	
	$tabelaINSSData = new TabelaINSSData();

	$ano = 0;
	
	for($i=1; $i<=3; $i++) {
		
		$nomeAno = 'ano_'.$i;  
		$nomeValor = 'valor_'.$i;
		$nomePorc = 'porc_'.$i;
		
		if(isset($_POST[$nomeAno]) && !empty($_POST[$nomeAno]) && isset($_POST[$nomeValor]) && !empty($_POST[$nomeValor]) && isset($_POST[$nomePorc]) && !empty($_POST[$nomePorc])) {
			$ano = $_POST[$nomeAno];
			$valor = str_replace(',','.',str_replace('.','',$_POST[$nomeValor]));
			$porcentagem = str_replace(',','.',$_POST[$nomePorc]);

			$tabelaINSSData->PreparaDadosGravaINSS($ano, $valor, $porcentagem);
		}
	}

	if($ano) {
		header('location: /admin/retencao_inss.php?ano='.$ano);
	} else {
		header('location: /admin/retencao_inss.php');
	}
}

if(isset($_POST['atualizarINSS'])) {
	
	$tabelaINSSData = new TabelaINSSData();

	for($i=1; $i<=3; $i++) {
		
		$nomeInssId = 'inssId_'.$i;
		$nomeValor = 'valor_'.$i;
		$nomePorc = 'porc_'.$i;
		
		if(isset($_POST[$nomeInssId]) && !empty($_POST[$nomeInssId]) && isset($_POST[$nomeValor]) && !empty($_POST[$nomeValor]) && isset($_POST[$nomePorc]) && !empty($_POST[$nomePorc])) {
			$inssId = $_POST[$nomeInssId];
			$valor = str_replace(',','.',str_replace('.','',$_POST[$nomeValor]));
			$porcentagem = str_replace(',','.',$_POST[$nomePorc]);

			$tabelaINSSData->PreparaDadosAlteraINSS($inssId, $valor, $porcentagem);
		}
	}
	
	if($ano) {
		header('location: /admin/retencao_inss.php?ano='.$ano);
	} else {
		header('location: /admin/retencao_inss.php');
	}
}