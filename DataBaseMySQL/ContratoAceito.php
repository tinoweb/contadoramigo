<?php
/**
 *	Classe para manipular os dados da tabela contrato aceito.
 *	Autor: Átano de farias	
 *	Data: 22/03/2017
 */
class ContratoAceito {
	
	// Realiza a inclusao dos dados
	public function InclirContratoAceito($user, $contratoId = 2, $contador = '', $valor = '') {
		
		mysql_query("INSERT INTO `contratos_aceitos`(`user`, `aceito`, `data`, `contratoId`, `contadorId`, `valor`) VALUES ('".$user."','1',NOW(),".$contratoId.", ".$contador.",".$valor." )");
		
		return mysql_insert_id();
	} 
	
	
	// Realiza a inclusao dos dados
	public function InclirServicoAvulso($idUser, $contadorId, $servicoName, $valorPagamento, $cobrancaContadorId, $tipoPagamento) {
		
		$qry = "INSERT INTO servico_avulso (id_user, contadorId, servico_name, data, valor, cobrancaContadorId, tipoPagamento) VALUES ('".$idUser."', '".$contadorId."', '".$servicoName."', NOW(), '".$valorPagamento."', '".$cobrancaContadorId."', '".$tipoPagamento."' )";
	
		mysql_query($qry);
	
		return mysql_insert_id();
	} 
}

