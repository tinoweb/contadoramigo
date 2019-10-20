<?php 
/**
 * Classe responsavel por manipular os dados dos serviços a vulso.
 * Autor: Átano de Farias
 * Data: 23/06/2017 
 */
class DadosServicosAvulso {
	
	// Método criado para pegar os dados do serviço.
	function PegaTodasServicos() {	
	
		$rows = false;
	
		$consulta = mysql_query('SELECT * FROM dados_servico_avulso ORDER BY sequencia ASC');		
		
		if( mysql_num_rows($consulta) > 0 ){
			
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		
		return $rows;	
	}

	// Método criado para pegar os dados do serviço.
	function PegaServicosPorTipo($tipo) {	
	
		$rows = false;
	
		$consulta = mysql_query("SELECT * FROM dados_servico_avulso WHERE tipo = '".$tipo."'");		
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;	
	}

}
