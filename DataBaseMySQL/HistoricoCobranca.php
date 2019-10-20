<?php
/**
 * Autor: Átano de Farias
 * Data: 07/03/2017 
 */
class HistoricoCobranca {

	// Pega o historico de cobrança do usuario que estiver vencido ou não pago.
	public function pegaHistoricoVencidaNaoPago30Dias($id_user) {
		$rows = false;
	
		$consulta = mysql_query(" SELECT * FROM login l 
									JOIN historico_cobranca hc ON hc.id = l.id
									WHERE l.id = ".$id_user." 
									AND hc.status_pagamento IN ('Vencido','não Pago')
									AND l.status IN ('ativo','Inativo')
									AND DATEDIFF(data_pagamento, NOW()) < -30; ");		
		
		// Verifica se houve retorno de dados.
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;	
	}
	
	// Pega o historico de cobrança do usuario que estiver vencido ou não pago.
	public function pegaHistoricoVencidaNaoPago($id_user) {
		$rows = false;
	
		$consulta = mysql_query(" SELECT * FROM login l 
									JOIN historico_cobranca hc ON hc.id = l.id
									WHERE l.id = ".$id_user." 
									AND hc.status_pagamento IN ('vencido','não Pago')
									AND l.status IN ('ativo','inativo')");		
		
		// Verifica se houve retorno de dados.
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;	
	}		
}


