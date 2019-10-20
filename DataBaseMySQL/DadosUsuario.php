<?php
/**
 * Autor: Átano de Farias
 * Data: 16/03/2017 
 */
class DadosUsuario {

	// Pega Todos usuarios.
	public function pegaDadosCobranca($id) {
		
		$rows = false;
		
		$query = ' SELECT c.id,
					c.assinante,
					c.pref_telefone,
					c.telefone,
					c.sacado,
					c.documento,
					c.endereco,
					c.numero,
					c.complemento,
					c.bairro,
					c.cep,
					c.cidade,
					c.uf,
					c.tipo,
					l.nome,
					l.assinante
					email FROM dados_cobranca c, login l
					WHERE c.id = l.id 
					AND l.id = '.$id.'; ';
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			$row = mysql_fetch_array($consulta);
			
		}
			
		return $row;	
	}		
}


