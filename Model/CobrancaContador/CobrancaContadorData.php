<?php 
/**
 * Classe para manipular os dados do cobranca do contador.
 * Autor: Átano de Farias
 * Data: 30/03/2017
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../DataBasePDO/CobrancaContador.php');
	require_once('../Model/CobrancaContador/vo/CobrancaContadorVo.php');
} elseif($requestURI[1] == 'admin') {	
	require_once('../DataBaseMySQL/CobrancaContador.php');
	require_once('../Model/CobrancaContador/vo/CobrancaContadorVo.php');
} else {
	require_once('DataBasePDO/CobrancaContador.php');
	require_once('Model/CobrancaContador/vo/CobrancaContadorVo.php');
}

class CobrancaContadorData {

	// Método criado para pegar os dados da cobranca contador.
	function getCobrancaContador($contadorId, $mes = '' , $ano = ''){
		
		// Instância a classe que controla os dados da tabela cobranca do contador.
		$cobrancaContador = new CobrancaContador();
		
		// Pega os dados de cobranca com os dados do cliente.
		$dados = $cobrancaContador->PegaDadosCobrancaContador($contadorId, $mes, $ano);
		
		// Variável que recebera os dados.
		$out = '';
		
		// verifica se houver retorno de dados.
		if($dados) {	

			// Monta a lista de objeto.
			foreach($dados as $dados) {
		
				$vo = new CobrancaContadorVo();
			
				$vo->setCobrancaContadorId($dados['cobrancaContadorId']);
				$vo->setIdRelatorio($dados['idRelatorio']);
				$vo->setResultadoAcao($dados['resultado_acao']);
				$vo->setValorPago($dados['valor_pago']);
				$vo->setDataPagamento($dados['data_pagamento']);
				$vo->setTipoPlano($dados['tipo_plano']);
				$vo->setPlano($dados['plano']);
				$vo->setEmissaoNF($dados['emissao_NF']);
				$vo->setUsurId($dados['id']);
				$vo->setAssinante($dados['assinante']);
				$vo->setDocumento($dados['documento']);
				$vo->setTipo($dados['tipo']);
				$vo->setValorStatus($dados['valorStatus']);
				$vo->setValorTotal($dados['valor_total']);
				$vo->setValorLiquido($dados['valor_liquido']);
				$vo->setLinkNFE($dados['linkNFE']);
				$vo->setStatusServico($dados['status']);
				
				$out[] = $vo;
			}
		}
		
		return $out;
	}

	// Método criado para pegar todos os dados da cobranca do contador.
	function PegaTodosDadosPagamento($contadorId, $tipoLancamento, $ano = '', $mes1 = '', $mes2 = '' ){
		
		// Instância a classe que controla os dados da tabela cobranca do contador.
		$cobrancaContador = new CobrancaContador();
		
		// Pega os dados de cobranca com os dados do cliente.
		$dados = $cobrancaContador->PegaTodosDadosCobrancaContador($contadorId, $tipoLancamento, $ano, $mes1, $mes2);
		
		// Variável que recebera os dados.
		$out = '';
		
		// verifica se houver retorno de dados.
		if($dados) {	

			// Monta a lista de objeto.
			foreach($dados as $dados) {
		
				$vo = new CobrancaContadorVo();
			
				$vo->setCobrancaContadorId($dados['cobrancaContadorId']);
				$vo->setIdRelatorio($dados['idRelatorio']);
				$vo->setResultadoAcao($dados['resultado_acao']);
				$vo->setValorPago($dados['valor_pago']);
				$vo->setDataPagamento($dados['data_pagamento']);
				$vo->setTipoPlano($dados['tipo_plano']);
				$vo->setPlano($dados['plano']);
				$vo->setEmissaoNF($dados['emissao_NF']);
				$vo->setUsurId($dados['id']);
				$vo->setAssinante($dados['assinante']);
				$vo->setDocumento($dados['documento']);
				$vo->setTipo($dados['tipo']);
				$vo->setValorStatus($dados['valorStatus']);
				$vo->setValorTotal($dados['valor_total']);
				$vo->setValorLiquido($dados['valor_liquido']);
				$vo->setLinkNFE($dados['linkNFE']);
				$vo->setStatusServico($dados['status']);
				$vo->setTipoLancamento($dados['tipoLancamento']);
				$vo->setServicoName($dados['servico_name']);
				$vo->setTipoAcao($dados['tipoAcao']);
				
				$out[] = $vo;
			}
		}
		
		return $out;
	}	
	
	// Pega os o ano.
	public function getAno($contadorId){
	
		// Instância a classe que controla os dados da tabela cobranca do contador.
		$cobrancaContador = new CobrancaContador();
		
		$ano = '';
		
		// Pega os dados do contador de acordo com código do usuário.
		$contador = $cobrancaContador->PegaAno($contadorId);
	
		// Verificar se existe dados. 
		if($contador){
			$ano = $contador['ano'];
		}
		
		return $ano;
	}
	
	public function pegaSaldoAPagar($contadorId, $ano = '') {

		$out = false;

		$tipoLancamento = 'pago';
		
		if(empty($ano)) {
			$ano = date('Y');
		}
		
		$anoAux = $ano - 1;
		
		$data_pagamento = $anoAux."-12-31";
		
		// Instância a classe que controla os dados da tabela cobranca do contador.
		$cobrancaContador = new CobrancaContador();
		
		// Pega os valores para poder obiter o saldo.
		$aReceberOUApagar = $cobrancaContador->PegaTotalAReceberOUApagar($contadorId, $data_pagamento, $tipoLancamento);
		$pagoOUComissao = $cobrancaContador->PegaTotalPagoOUComissao($contadorId, $data_pagamento, $tipoLancamento);	
		
		// Recebe o saldo.
		$out = $aReceberOUApagar['total_receber'] - $pagoOUComissao['total_Pago'];
		
		return $out;
	}
	
	public function PegaSaldoComissao($contadorId, $ano = '') {
		
		$out = false;
		$tipoLancamento = 'comissao';
			
		if(empty($ano)) {
			$ano = date('Y');
		}
		
		$anoAux = $ano - 1;
		
		$data_pagamento = $anoAux."-12-31";	
				
		// Instância a classe que controla os dados da tabela cobranca do contador.
		$cobrancaContador = new CobrancaContador();
		
		// Pega os valores para poder obiter o saldo.
		$aReceberOUApagar = $cobrancaContador->PegaTotalAReceberOUApagar($contadorId, $data_pagamento, $tipoLancamento);
		$pagoOUComissao = $cobrancaContador->PegaTotalPagoOUComissao($contadorId, $data_pagamento, $tipoLancamento);		
		
		// Recebe o saldo.
		$out = $aReceberOUApagar['total_receber'] - $pagoOUComissao['total_Pago'];
		
		return $out;
	}
}