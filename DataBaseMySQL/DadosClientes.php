<?php
/**
 * Autor: Átano de Farias
 * Data: 14/03/2017 
 */
class DadosCliente {

	// Pega os dados do usuarios.
	public function pegaDadosCliente($id_login) {
		
		$rows = '';
		
		$query = ' SELECT * FROM `dados_clientes` WHERE id_login = '.$id_login.'; ';
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$rows = mysql_fetch_array($consulta);
			
		}
			
		return $rows;	
	}
	
	public function pegaCliente($empresaId){
		
		$rows = false;
		
	  	$query = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $empresaId . " AND status = 'A' ORDER BY apelido";
	  	
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array;
			}
		}
		
		return $rows;			
              
	}
		
}