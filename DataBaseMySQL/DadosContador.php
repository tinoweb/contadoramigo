<?php
/**
 * Autor: Átano de Farias
 * Data: 17/03/2017 
 */
class DadosContador {

	// Pega os dados do contador de acordo com seu id.
	public function pegaListaComDadosContador() {
		
		$rows = '';
		
		$query = " SELECT * FROM `dados_contador_balanco` WHERE ativo = 'S'";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
			
		}
			
		return $rows;	
	}

	// Pega os dados do contador de acordo com seu id.
	public function pegaDadosContador($id) {
		
		$rows = '';
		
		$query = " SELECT * FROM `dados_contador_balanco` WHERE id = ".$id."; ";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;	
	}
	
	// Pega os dados do contador de acordo com seu id.
	public function pegaNomeContador($id) {
		
		$rows = '';
		
		$query = " SELECT nome FROM `dados_contador_balanco` WHERE id = ".$id."; ";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;	
	}

	// Pega os dados do contadores ativos. 
	public function pegaDadosContadorAtivo() {
		
		$rows = '';
		
		$query = " SELECT * FROM `dados_contador_balanco` WHERE ativo = 'S'";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
			
		}
	
		return $rows;
	}
	
	// Pega os dados do contadores pelo estado. 
	public function pegaDadosContadorUF($UF) {
		
		$rows = '';
		
		$query = " SELECT * FROM `dados_contador_balanco` WHERE uf = '".$UF."' AND ativo = 'S'";
	
		$consulta = mysql_query($query);		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
	
		return $rows;
	}
	
	// Pega os dados do contadores pelo estado. 
	public function PegaContadorDeAcordoClienteId($id) {
		
		$rows = '';
		
		$query = " SELECT dc.id
				,dc.nome
				,dc.razao_social
				,dc.crc
				,dc.endereco
				,dc.cidade
				,dc.cep
				,dc.documento
				,dc.uf 
				,dc.estado 
				,dc.userId
				,dc.bairro
				,dc.tipoDoc
				,dc.documento2
				,dc.sexo
				,dc.pref_telefone
				,dc.telefone
				,u.userId
				,u.userEmail		
			 FROM dados_cobranca c
			 JOIN dados_contador_balanco dc ON dc.id = c.contadorId
			 JOIN user u ON u.userId = dc.userId 
			 WHERE c.id = '".$id."'";
	
		$consulta = mysql_query($query) or die (mysql_error());		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
	
		return $rows;
	}
	
}