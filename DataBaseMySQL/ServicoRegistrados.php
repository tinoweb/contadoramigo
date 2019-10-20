<?php
/**
 * Autor: Átano de Farias
 * Data: 27/06/2017 
 */
class ServicoRegistrados {

	public function IncluiServicoContatado($id_user, $contadorId, $servico_name, $valor, $cobrancaContadorId, $tipoPagamento) {
		
		// Inclui o serviço do boleto.
		mysql_query("INSERT INTO servico_avulso (id_user, contadorId, servico_name, data, valor, cobrancaContadorId, tipoPagamento) VALUES ('".$id_user."', '".$contadorId."', '".$servico_name."', NOW(), '".$valor."','".$cobrancaContadorId."', '".$tipoPagamento."')");
		
		return mysql_insert_id();
	}	
			
}




