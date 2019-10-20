<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 13/07/2017
 */

// Classe criada para manipular os dados da tabela do IR.
class TabelaRetencaoIR {
	
	// Retorna a lista do INSS de
	public function PegaDadosRetencaoIR($ano){
		
		$rows = '';
		
		$query = "SELECT * FROM tabelas WHERE ano_calendario = '".$ano."'";
		
		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
	
	public function PegaValorProLabore(){
		
		$rows = '';
		
		$query = "SELECT ValorBruto1 FROM tabelas t WHERE ano_calendario = '".date("Y")."'";
		
		$consulta = mysql_query($query);
		
		if(mysql_num_rows($consulta)){
			$rows = mysql_fetch_array($consulta);
		}
		
		return($rows);
	}
}
?>