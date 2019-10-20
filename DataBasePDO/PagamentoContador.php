<?php
/**
 * Classe para manipular os dados da tabela do contador.
 * Autor: Átano de Farias 
 * Data: 27/04/2017 
 */
require_once('../conect.PDO.php');

class PagamentoContador extends AccessDB {
	
	// Método para incluir os dados de pagamento para o contador.
	function incluiPagamento($objetc) {
		
		// Verifica se a variável e objeto. 
		if(is_object($objetc)) {
		
			// Monta a insert.
			$query = $this->PDO->prepare("INSERT INTO `pagamento_contador` (`contadorId`,data_pagamento,`valor_pagamento`,`Ids_Cobranca_Contador`) "
			."VALUE (".$objetc->getContadorId().",'".$objetc->getdataPagamento()."','".$objetc->getValorpagamento()."','".$objetc->getIdsCobrancaContador()."');");
			
			if(!$query->execute()){
				$info = $query->errorInfo();
				throw new Exception($info[2]);
			}
			
			// Retorn o Código do pagamento.
			return $query->lastInsertId();
		}
	}
	
	// Método criado para atualizar os dados do contado.
	function AtualizaPagamento($objetc) {
		
		// Verifica se a variável e objeto. 
		if(is_object($objetc)) {
		
			// Monta o update.
			$query = $this->PDO->prepare( " UPDATE `pagamento_contador` "
					." SET	`valor_pagamento` = '".$objetc->getValorpagamento()."', "
					."		`Ids_Cobranca_Contador` = '".$objetc->getIdsCobrancaContador()."' "
					." WHERE `pagamentoId` = ".$objetc->getPagamentoId());
			
			if(!$query->execute()){
				$info = $query->errorInfo();
				throw new Exception($info[2]);
			}
		
			$query->fetch(PDO::FETCH_ASSOC);
		}
	}
	
	// Método criado para pegar os dados do contador para o contador de acordo com o mes e ano.
	function PegaDadosPagamento($contadorId, $mesAno) {
	
		$query = $this->PDO->prepare(" SELECT * FROM `pagamento_contador` WHERE `contadorId` = ".$contadorId." AND `data_pagamento` = '".$mesAno."'");
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
}