<?php
/**
 * Autor: Átano de Farias
 * Data: 15/02/2017 
 */
class Categorias {
	
	function PegaTodasCategorias() {	
	
		$rows = false;
	
		$consulta = mysql_query('SELECT * FROM categoria ORDER BY categoriaNome ASC');		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
			
		}
			
		return $rows;	
	}		
}


