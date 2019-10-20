<?php
/**
 * Autor: Átano de Farias
 * Data: 14/03/2017 
 */
class DadosTokenPagamento{

	// Pega os dados do usuarios.
	public function pegaDadosToken($id_user) {
		
		$rows = '';
		
		$query = ' SELECT * FROM  token_pagamento  WHERE id_user = '.$id_user.'; ';
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;	
	}
	
	// Atauliza token de pagamento
	public function AtualizaTokenPagamento($id, $token, $numero_cartao, $NomeTitular, $FormaPagamento) {
	
		$sql = " UPDATE  token_pagamento  
				SET  token ='".$token."'
					, numero_cartao ='".$numero_cartao."'
					, nome_titular ='".$NomeTitular."'
					, bandeira ='".$FormaPagamento."'
					, data_criacao ='".date("Y-m-y H:m:s")."' 
				WHERE id = '".$id."' ";
	
		mysql_query($sql) or die (mysql_error());
	}
	
	// Inclui Token de pagamento
	public function GravaTokenPagamento( $idUser, $token, $numero_cartao, $NomeTitular, $FormaPagamento) {
	
		$sql = " INSERT INTO  token_pagamento ( id_user ,  token ,  numero_cartao ,  nome_titular , bandeira ,  data_criacao )
				VALUES ( '".$idUser."','".$token."','".$numero_cartao."','".$NomeTitular."','".$FormaPagamento."','".date("Y-m-y H:m:s")."' )";
		$resultado = mysql_query($sql);
		
		return mysql_insert_id();
	}		
}