<?php
/**
 * Autor: Átano de Farias
 * Data: 14/03/2017 
 */
class DadosDaEmpresa {

	// Pega Todos usuarios.
	public function pegaDadosEmpresa($id) {
		
		$rows = '';
		
		$query = ' SELECT * FROM `dados_da_empresa` WHERE id = '.$id.'; ';
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$rows = mysql_fetch_array($consulta);
			
		}
			
		return $rows;	
	}		
}


