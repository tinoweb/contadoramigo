<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 04/08/2017
 */

// Classe criada para manipular os do funcionário no banco de dados.
class DadosSocio {
	
	// Pega os dados do funcionário de acordo com o seu ID.
	public function PegaDadosSocio($idSocio){
		
		$rows = '';
		
		$query = " SELECT * FROM dados_do_responsavel WHERE idSocio = '".$idSocio."'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
	
	// Pega os dados do funcionário de acordo com o seu ID.
	public function PegaDadosSocioResponsavel($empresaId){
		
		$rows = '';
		
		$query = " SELECT * FROM dados_do_responsavel WHERE id = '".$empresaId."' AND responsavel = '1'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}	
	

}