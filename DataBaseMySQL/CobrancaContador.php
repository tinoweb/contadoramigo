<?php
/**
 * Classe para manipular os dados da tabela do contador.
 * Autor: Átano de Farias 
 * Data: 29/03/2017 
 */
class CobrancaContador {
	
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
		$query = mysql_query(" SELECT cc.cobrancaContadorId" 
					.",rc.idRelatorio "
					.",rc.resultado_acao"
					.",rc.valor_pago"
					.",rc.data_pagamento"
					.",rc.tipo_plano"
					.",rc.plano"
					.",rc.emissao_NF"
					.",dc.id"
					.",dc.assinante"
					.",dc.documento"
					.",dc.tipo"
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
				." ORDER BY data_pagamento DESC");
				
		// Verifica se existe dados		
		if( mysql_num_rows($query) > 0 ){
			
			while($array = mysql_fetch_array($query)){
				$rows[] = $array ;
			}
			
		}
			
		return $rows; 
	}
	
	// Método para pegar o ano da primeira inclusão do pagamento
	public function PegaAno($contadorId){
		
		$rows = '';
		
		// Define o select para pegar os dados de cobranca.
		$consulta = mysql_query(" SELECT YEAR(rc.data_pagamento) as ano "
				 ."	FROM cobranca_contador cc "
				 ." 	JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio "
				 ." 	JOIN dados_cobranca dc ON dc.id = rc.id "
				 ." 	WHERE cc.contadorId = ".$contadorId
				 ."	AND rc.data_pagamento IS NOT NULL " 
				 ." 	ORDER BY rc.data_pagamento ASC LIMIT 1;");
				
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Método para pegar os dados da tabela cobranca contador de acordo com o código contador.
	public function PegaTodosDadosCobrancaContador($contadorId, $tipoLancamento, $ano, $mes1, $mes2){
		
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
		$query = mysql_query(" SELECT cc.cobrancaContadorId" 
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
					.",sa.servico_name"		 
				." FROM cobranca_contador cc "
				." LEFT JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio "
				." LEFT JOIN servico_avulso sa ON sa.cobrancaContadorId = cc.cobrancaContadorId "
				." LEFT JOIN dados_cobranca dc ON dc.id = rc.id "
				." WHERE cc.contadorId = ".$contadorId
				." AND cc.data_pagamento >= '".$ano."-".$mes1."-01' "
				." AND cc.data_pagamento <= '".$ano."-".$mes2."-31' "			
				." AND (rc.data_pagamento OR cc.tipoLancamento = '".$tipoLancamento."')"
				." ORDER BY cc.data_pagamento ASC");
				
		// Verifica se existe dados		
		if( mysql_num_rows($query) > 0 ){
			
			while($array = mysql_fetch_array($query)){
				$rows[] = $array ;
			}
			
		}
			
		return $rows; 
	}	
	
	// inclui dodos de pagamento para cobranca contador.
	public function IncluiCobrancaContador($idRelatorio, $contadorId, $valorTotal, $valorLiquido, $desconto) {
	
		$insert = "INSERT INTO  cobranca_contador  ( idRelatorio,  contadorId, valor_total, valor_liquido, desconto_porcentagem ) VALUE ('".$idRelatorio."', '".$contadorId."', '".$valorTotal."', '".$valorLiquido."', '".$desconto."');";
	
		mysql_query($insert) or die (mysql_error());	
		
		return mysql_insert_id();	
	}
	
	// Inclui o laçamento de pagemento de comissão ou de pagamento para o contador.
	public function IncluiLancamentoPagamento($contadorId, $valorTotal, $valorLiquido, $desconto, $dataPagamento, $tipoLancamento) {
	
		$insert = "INSERT INTO  cobranca_contador  ( contadorId"
		.", valor_total"
		.", valor_liquido"
		.", desconto_porcentagem"
		.", tipoLancamento"
		.", data_pagamento ) "
		."VALUE ("
		."'".$contadorId."'," 
		."'".$valorTotal."',"
		."'".$valorLiquido."',"
		."'".$desconto."',"
		."'".$tipoLancamento."',"
		."'".$dataPagamento."');";
	
		mysql_query($insert) or die (mysql_error());	
		
		return mysql_insert_id();	
	}	
	
	public function ExcluiPagamentoParaContador($pagamentoId, $contadorId) {
		
		$delete = "DELETE FROM cobranca_contador WHERE (tipoLancamento = 'pago' OR tipoLancamento = 'comissao') AND contadorId = '".$contadorId."' AND cobrancaContadorId ='".$pagamentoId."';"; 
		mysql_query($delete) or die (mysql_error());
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
				
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;

	}
		
	public function PegaTotalPagoOUComissao($contadorId, $data_pagamento, $tipoLancamento) {	
		
		$rows = false;
		
		$consulta = mysql_query("SELECT sum(cc.valor_total) as total_Pago "
		." FROM cobranca_contador cc "
		." WHERE cc.contadorId = '".$contadorId."'"
		." AND cc.data_pagamento <= '".$data_pagamento."'"
		." AND cc.tipoLancamento = '".$tipoLancamento."'" 
		." ORDER BY cc.data_pagamento ASC");

		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
}






