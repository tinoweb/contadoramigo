<?php 
/**
 * Classe responsavel por manipular os dados dos serviços a vulso.
 * Autor: Átano de Farias
 * Data: 22/02/2018 
 */
class ServicosAvulso {
	
	// Método criado para pegar os dados do serviço.
	function PegaTodasServicosEClientes() {	
	
		$rows = false;
	
		$query = "SELECT sa.id_user
					,dc.assinante
					,dc.uf
					,sa.contadorId
					,sa.servico_name
					,sa.id
					,sa.data
					,sa.valor
					,sa.cobrancaContadorId 
					,sa.tipoPagamento
					FROM servico_avulso sa
					JOIN dados_cobranca dc ON dc.id = sa.id_user 
					JOIN login l ON l.id = dc.id
					JOIN cobranca_contador cc ON cc.cobrancaContadorId = sa.cobrancaContadorId
					JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio
					WHERE ((sa.tipoPagamento = 'cartao') OR (sa.tipoPagamento = 'boleto' AND rc.data_pagamento IS NOT NULL))
                    AND sa.status != 'Concluído'
				ORDER BY sa.data ASC, dc.assinante ASC";		
		
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		
		return $rows;	
	}
	
	// Pega os dados do contador de acordo com seu id.
	public function pegaListaComDadosContador() {
		
		$rows = false;
		
		$query = " SELECT * FROM dados_contador_balanco WHERE ativo = 'S'";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
			
		}
			
		return $rows;	
	}
	
	// Pega a quantidade de serviços.
	public function PegaQuantidadeServicos() {	
	
		$rows = false;
		
		$query = "SELECT COUNT(*) as quantidade 
				FROM servico_avulso sa
				JOIN dados_cobranca dc ON dc.id = sa.id_user 
				JOIN login l ON l.id = dc.id
				JOIN cobranca_contador cc ON cc.cobrancaContadorId = sa.cobrancaContadorId
				JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio
				WHERE (sa.tipoPagamento = 'cartao' OR sa.tipoPagamento = 'boleto' AND rc.data_pagamento IS NOT NULL)
				AND sa.status != 'Concluído'";
	
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows['quantidade'];	
	}
	
	// Altera o contador responsável pelo serviço.
	public function AtualizaContadorServico($id, $contadorId) {
		
		$rows = false;
		
		// instrução para atualizar o contador do serviço.
		$update = "UPDATE servico_avulso SET contadorId = '".$contadorId."' WHERE id = '".$id."'";
		
		// execulta a atualização
		mysql_query($update);
		
	}

	// Altera o contador do pagamento do serviço.
	public function AtualizaContadorPagamentoServico($cobrancaContadorId, $contadorId) {
		
		$rows = false;
		
		// instrução para atualizar o contador do serviço.
		$update = "UPDATE cobranca_contador SET contadorId = '".$contadorId."' WHERE cobranca_contador.cobrancaContadorId = '".$cobrancaContadorId."'";
		
		// execulta a atualização
		mysql_query($update);
		
	}	
}
