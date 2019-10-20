<?php
/**
 * Classe para manipular os dados da tabela do contador.
 * Autor: Átano de Farias 
 * Data: 29/03/2017 
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador' || $requestURI[1] == 'admin') {
	require_once('../conect.PDO.php');
} else {
	require_once('conect.PDO.php');
}
 
class CobrancaContador extends AccessDB {
	
	// Método para pegar os dados da tabela cobranca contador de acordo com o código contador.
	public function PegaDadosCobrancaContador($contadorId, $mes = '' , $ano = ''){
		
		$rows = "";
		
		// Caso a data ele não tenha sido definido ele pega a data atual.
		if(empty($mes)) {
			$mes = date('m');
		}
		if(empty($ano)) {
			$ano = date('Y');
		}
		
		// Define o select para pegar os dados de cobranca.
		$query = " SELECT cc.cobrancaContadorId" 
					.",rc.idRelatorio "
					.",rc.resultado_acao"
					.",rc.valor_pago"
					.",rc.tipo_plano"
					.",rc.plano"
					.",rc.emissao_NF"
					.",dc.id"
					.",dc.assinante"
					.",dc.documento"
					.",dc.tipo"
					.",cc.data_pagamento"
					.",cc.valorStatus"
					.",cc.valor_total"
					.",cc.valor_liquido"
					.",cc.linkNFE"
					.",sa.status"
				." FROM cobranca_contador cc "
				." JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio "
				." LEFT JOIN servico_avulso sa ON sa.cobrancaContadorId = cc.cobrancaContadorId "
				." JOIN dados_cobranca dc ON dc.id = rc.id "
				." WHERE cc.contadorId = ".$contadorId
				." AND rc.data_pagamento >= '".$ano."-".$mes."-01 00:00:00' "
				." AND rc.data_pagamento <= '".$ano."-".$mes."-31 23:59:00' "
				." ORDER BY cc.data_pagamento DESC";
				
		$query = $this->PDO->prepare($query);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	// Método para pegar o ano da primeira inclusão do pagamento
	public function PegaAno($contadorId){
		
		// Define o select para pegar os dados de cobranca.
		$query = " SELECT YEAR(rc.data_pagamento) as ano "
				 ."	FROM cobranca_contador cc "
				 ." 	JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio "
				 ." 	JOIN dados_cobranca dc ON dc.id = rc.id "
				 ." 	WHERE cc.contadorId = ".$contadorId
				 ."	AND rc.data_pagamento IS NOT NULL " 
				 ." 	ORDER BY rc.data_pagamento ASC LIMIT 1;";
				
		$query = $this->PDO->prepare($query);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC); 
	}
	
	// Método para incluir os dados de pagamento para o contador.
	function AtualizaLinqueNFE($id, $linkNFE) {

		// Monta a insert.
		$query = $this->PDO->prepare(" UPDATE cobranca_contador "
		." SET linkNFE = '".$linkNFE."'"
		." WHERE cobrancaContadorId = ".$id.";");
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
	
	// Método para pegar os dados da tabela cobranca contador de acordo com o código contador.
	public function PegaTodosDadosCobrancaContador($contadorId, $tipoLancamento, $ano = '', $mes1 = '', $mes2 = ''){
		
		$rows = "";
		
		// Caso a data ele não tenha sido definido ele pega a data atual.
		if(empty($mes1)) {
			$mes1 = '01';
		}
		
		if(empty($mes2)) {
			$mes2 = '12';
		}
		
		if(empty($ano)) {
			$ano = date('Y');
		}
		
		// Define o select para pegar os dados de cobranca.
		$query = " SELECT cc.cobrancaContadorId" 
					.",rc.idRelatorio "
					.",rc.resultado_acao"
					.",rc.valor_pago"
					.",rc.tipo_plano"
					.",rc.plano"
					.",rc.emissao_NF"
					.",rc.tipo as tipoAcao"
					.",dc.id"
					.",dc.assinante"
					.",dc.documento"
					.",dc.tipo"
					.",cc.data_pagamento"
					.",cc.valorStatus"
					.",cc.valor_total"
					.",cc.valor_liquido"
					.",cc.linkNFE"
					.",cc.tipoLancamento"
					.",sa.status"
					.",servico_name"
				." FROM cobranca_contador cc "
				." LEFT JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio "
				." LEFT JOIN servico_avulso sa ON sa.cobrancaContadorId = cc.cobrancaContadorId "
				." LEFT JOIN dados_cobranca dc ON dc.id = rc.id "
				." WHERE cc.contadorId = ".$contadorId
				." AND cc.data_pagamento >= '".$ano."-".$mes1."-01' "
				." AND cc.data_pagamento <= '".$ano."-".$mes2."-31' "			
				." AND (rc.data_pagamento OR cc.tipoLancamento = '".$tipoLancamento."' )"
				." ORDER BY cc.data_pagamento ASC";
		
		$query = $this->PDO->prepare($query);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public function PegaTotalAReceberOUApagar($contadorId, $data_pagamento, $tipoLancamento) {
		
		$rows = false;
		
		// Verifica qual sera a perquisa, ou seja, total comissão ou total a pagara. 
		if($tipoLancamento == 'comissao') {
			$query ="SELECT sum(cc.valor_total - cc.valor_liquido) as total_receber ";
		} else {
			$query =" SELECT sum(cc.valor_liquido) as total_receber ";
		}
		
		$query .= " FROM cobranca_contador cc "
		." LEFT JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio "
		." LEFT JOIN servico_avulso sa ON sa.cobrancaContadorId = cc.cobrancaContadorId "
		." LEFT JOIN dados_cobranca dc ON dc.id = rc.id "
		." WHERE cc.contadorId = '".$contadorId."'"
		." AND cc.data_pagamento <= '".$data_pagamento."'"
		." AND rc.resultado_acao != '9.9' "
		." AND rc.data_pagamento "
		." ORDER BY cc.data_pagamento ASC ";
				
		$query = $this->PDO->prepare($query);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC); 

	}
		
	public function PegaTotalPagoOUComissao($contadorId, $data_pagamento, $tipoLancamento) {	
		
		$rows = false;
		
		$query = "SELECT sum(cc.valor_total) as total_Pago "
		." FROM cobranca_contador cc "
		." WHERE cc.contadorId = '".$contadorId."'"
		." AND cc.data_pagamento <= '".$data_pagamento."'"
		." AND cc.tipoLancamento = '".$tipoLancamento."'" 
		." ORDER BY cc.data_pagamento ASC";

		$query = $this->PDO->prepare($query);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC); 
	}	
}






