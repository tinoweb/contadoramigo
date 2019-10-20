<?php
/**
 * Classe para manipular os dados da tabela do contador.
 * Autor: Átano de Farias 
 * Data: 27/04/2017 
 */
class PagamentoContador {
	
	// Método para incluir os dados de pagamento para o contador.
	function incluiPagamento($objetc) {
		
		// Verifica se a variável e objeto. 
		if(is_object($objetc)) {
		
			// Monta a insert.
			$insert = "INSERT INTO `pagamento_contador` (`contadorId`,data_pagamento,`valor_pagamento`,`Ids_Cobranca_Contador`) "
			."VALUE (".$objetc->getContadorId().",'".$objetc->getdataPagamento()."','".$objetc->getValorpagamento()."','".$objetc->getIdsCobrancaContador()."');";
			
			// Executa a instrução.
			mysql_query($insert);
			
			// Retorn o Código do pagamento.
			return mysql_insert_id();
		}
	}
	
	// Método criado para atualizar os dados do contado.
	function AtualizaPagamento($objetc) {
		
		// Verifica se a variável e objeto. 
		if(is_object($objetc)) {
		
			// Monta o update.
			$update = " UPDATE `pagamento_contador` "
					." SET	`valor_pagamento` = '".$objetc->getValorpagamento()."', "
					."		`Ids_Cobranca_Contador` = '".$objetc->getIdsCobrancaContador()."' "
					." WHERE `pagamentoId` = ".$objetc->getPagamentoId();
			
			// Executa a instrução.
			mysql_query($update);
		}
	}
	
	// Método criado para pegar os dados do contador para o contador de acordo com o mes e ano.
	function PegaDadosPagamento($contadorId, $mesAno) {
	
		$rows = '';
		
		$consulta = " SELECT * FROM `pagamento_contador` WHERE `contadorId` = ".$contadorId." AND `data_pagamento` = '".$mesAno."'; ";
	
		// Define o select para pegar os dados de cobranca.
		$consulta = mysql_query($consulta);
				
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
}