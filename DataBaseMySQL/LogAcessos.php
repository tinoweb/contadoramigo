<?php
/**
 * Autor: Átano de Farias
 * Data: 09/06/2017 
 */
class LogAcessos {
	
	public function IncluiLogAcessos($id_user, $acao) {
	
		$insert = "INSERT INTO log_acessos (id_user, acao) VALUES ('".$id_user."', '".$acao."')";
		mysql_query($insert) or die (mysql_error());	
		
		return mysql_insert_id();	
	}
	
}