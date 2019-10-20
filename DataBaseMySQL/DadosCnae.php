<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 07/02/2018
 */

// Classe criada para para manipular os dados do CNAE.
class DadosCnae {

	// Método criado para pegar o cnae.
	public function PegaCnae($cnae){
		
		$rows = '';

		$query = "SELECT * FROM cnae_2018 WHERE cnae = '".$cnae."'";

		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			$rows =  mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
}