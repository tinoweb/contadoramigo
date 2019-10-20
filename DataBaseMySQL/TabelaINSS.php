<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 10/07/2017
 */

// Classe criada para manipular os dados do INSS
class TabelaINSS {
	
	// Retorna a lista do INSS de
	public function PegaTabelaINSS($ano){
		
		$rows = '';
		
		$query = " SELECT * FROM tabelaINSS WHERE ano = '".$ano."' ORDER by valor ASC";
		
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
			
		return $rows;
	}
	
	// Retorna o menor ano da lista.
	public function PegaMenorAno($ano){
		
		$rows = '';
		
		$query = " SELECT min(ano) ano FROM `tabelaINSS` ";
		
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}	
	
	
	// Realiza a inclusão dos dados do INSS
	public function GravaDadosINSS($object) {
		
		$qry = "INSERT INTO tabelaINSS (ano, valor, porcentagem) 
		VALUES ('".$object->getAno()."', '".$object->getValor()."', '".$object->getPorcentagem()."');";
	
		mysql_query($qry);
	
		return mysql_insert_id();
	}
	
	// Realiza a atyalização dos dados do INSS
	public function AtualizaDadosINSS($object) {
		
		$qry = "UPDATE tabelaINSS 
			SET valor = '".$object->getValor()."', 
				porcentagem = '".$object->getPorcentagem()."'
			WHERE inssId = ".$object->getInssId();
		
		mysql_query($qry);
	
		return mysql_insert_id();
	}	
}