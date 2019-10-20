<?php
/**
 * Autor: Átano de Farias
 * Data: 09/06/2017 
 */
class RelatorioCobranca {
	
	public function IncluiRelatorioCobranca($id, $idHistorico, $forma_pagamento_assinante, $resultado_acao, $enviado = 'enviado', $retorno_tid, $valor_pago, $numeroCartao, $tipo_Plano, $plano_usuario, $tipo) {
	
		$insert = "INSERT INTO relatorio_cobranca (id, idHistorico , data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao, tipo_plano, plano, tipo) 
				VALUES ('".$id."', '".$idHistorico."' , NOW(), '".$forma_pagamento_assinante."', '".$resultado_acao."', '".$enviado."', '".$retorno_tid."', '".$valor_pago."','".$numeroCartao."', '".$tipo_Plano."', '".$plano_usuario."', '".$tipo."')";
	
		mysql_query($insert) or die (mysql_error());	
		
		return mysql_insert_id();	
	}
	
}