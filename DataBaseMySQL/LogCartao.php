<?php
/**
 * Autor: Átano de Farias
 * Data: 09/06/2017 
 */
class LogCartao {
	
	public function IncluiLogCartao($id_user, $erro, $retorno_codigo , $resultado) {
	
		$insert = "INSERT INTO log_cartao(id_user, erro, retorno_codigo , resultado) VALUES ('".$id_user."', '".$erro."', '".$retorno_codigo."', '".$resultado."')";
		mysql_query($insert) or die (mysql_error());	
		
		return mysql_insert_id();	
	}
	
}