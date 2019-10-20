<?php 
/**
 * Classe criada para realizar o controle dos dados Pagamento do contador.
 * Autor: Átano de Farias
 * Data: 28/04/2017
 */
  
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../DataBasePDO/PagamentoContador.php');
	require_once('../Model/PagamentoContador/vo/PagamentoContadorVo.php');
} elseif($requestURI[1] == 'admin') {	
	require_once('../DataBaseMySQL/PagamentoContador.php');
	require_once('../Model/PagamentoContador/vo/PagamentoContadorVo.php');
} else {
	require_once('../DataBasePDO/PagamentoContador.php');
	require_once('../Model/PagamentoContador/vo/PagamentoContadorVo.php');
}

class PagamentoContadorData{
	
	// Método para fazer inclusao;
	public function InclusaoPagamentoContador($contadorId, $dataPagamento, $valorpagamento, $idsCobrancaContador){

		// Invoca a Classe Normatizar os dados.
		$vo = new PagamentoContadorVo();	
		
		// Invoca a classe que manipula os dados
		$pagamentoContador = new PagamentoContador();

		// Pega os dados para realizar a alteração
		$vo->setContadorId($contadorId);
		$vo->setDataPagamento($dataPagamento);
		$vo->setValorpagamento($valorpagamento);
		$vo->setIdsCobrancaContador($idsCobrancaContador);		
		
		// Realiza a inclucao de dados e retorna o Id.
		return $pagamentoContador->incluiPagamento($vo);
	} 
	
	// Método para fazer Alteração;
	public function AlteraPagamentoContador($pagamentoId, $valorpagamento, $idsCobrancaContador) {
		
		// Invoca a Classe Normatizar os dados.
		$vo = new PagamentoContadorVo();	
		
		// Invoca a classe que manipula os dados
		$pagamentoContador = new PagamentoContador();
		
		// Pega os dados para realizar a alteração
		$vo->setPagamentoId($pagamentoId);
		$vo->setValorpagamento($valorpagamento);
		$vo->setIdsCobrancaContador($idsCobrancaContador);

		$pagamentoContador->AtualizaPagamento($vo);
	}

	// Método para pegar os dados do Pagamento.
	public function PegaPagamentoContador($contadorId, $dataPagamento) {
		
		// Invoca a classe que manipula os dados
		$pagamentoContador = new PagamentoContador();

		// Invoca a Classe Normatizar os dados.
		$vo = new PagamentoContadorVo();
		
		$dados = $pagamentoContador->PegaDadosPagamento($contadorId, $dataPagamento);
		
		// Verifioca se existe dados.
		if($dados) {
			
			// Pega os dados para realizar a alteração
			$vo->setPagamentoId($dados['pagamentoId']);
			$vo->setContadorId($dados['contadorId']);
			$vo->setDataPagamento($dados['data_pagamento']);
			$vo->setValorpagamento($dados['valor_pagamento']);
			$vo->setIdsCobrancaContador($dados['Ids_Cobranca_Contador']);
		}
		
		return $vo;
	}
}
?>