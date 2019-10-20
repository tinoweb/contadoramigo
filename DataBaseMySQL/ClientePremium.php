<?php
/**
 * Autor: Átano de Farias
 * Data: 22/02/2018 
 */
class ClientePremium {
	
	// Pega todo os cliente Premim.
	public function PegaListaClientesPremium() {	
	
		$rows = false;
		
		$query = "SELECT d.*, l.*, MAX(ca.data) as dataContrato 
				FROM dados_cobranca d
				JOIN login l on l.id = d.id
				LEFT JOIN contratos_aceitos ca ON ca.user = d.id
				WHERE d.tipo_plano = 'P'
				GROUP by l.id ORDER by ca.data ASC";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
			
		return $rows;	
	}
	
	// Pega a quantidade de premium.
	public function PegaQuantidadeClientesPremium() {	
	
		$rows = false;
		
		$query = "SELECT COUNT(*) as quantidade FROM dados_cobranca WHERE tipo_plano = 'P'";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows['quantidade'];	
	}
	
	// Pega os dados do contador de acordo com seu id.
	public function pegaListaComDadosContador() {
		
		$rows = '';
		
		$query = " SELECT * FROM `dados_contador_balanco` WHERE ativo = 'S'";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
			
		}
			
		return $rows;	
	}
	
		// Altera o contador responsável pelo cliente.
	public function AtualizaContadorPremium($id, $contadorId) {
		
		$rows = false;
		
		// instrução para atualizar o contador do cliente.
		$update = "UPDATE dados_cobranca 
				SET contadorId = '".$contadorId."' 
				WHERE id = '".$id."'
				AND tipo_plano = 'P'";
		
		// execulta a atualização
		mysql_query($update);
		
	}

	// Altera o contador do pagamento.
	public function AtualizaContadorPagamentoPremium($cobrancaContadorId, $contadorId) {
		
		$rows = false;
		
		$update = "UPDATE cobranca_contador SET contadorId = '".$contadorId."' WHERE cobrancaContadorId = '".$cobrancaContadorId."'";
		
		// execulta a atualização
		mysql_query($update);
	}
	
	public function PegaDadosPagamentoPremium($id){
		
		$rows = false;
		
		$query = "SELECT cc.cobrancaContadorId
					,dc.assinante
					,cc.valor_total
					,cc.valor_liquido
					,cc.contadorId
					,rc.data
					,rc.data_pagamento
					,rc.tipo
					,rc.tipo_cobranca
					FROM dados_cobranca dc  
					LEFT JOIN relatorio_cobranca rc ON rc.id = dc.id
					LEFT JOIN cobranca_contador cc ON cc.idRelatorio = rc.idRelatorio
					WHERE dc.id = ".$id."
					AND rc.resultado_acao IN ('1.2','2.1','7.1')
					AND rc.tipo IN ('Premium', 'ComplementarPremium')
					ORDER by rc.data_pagamento DESC"; 
		
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
			
		return $rows;
	}
}