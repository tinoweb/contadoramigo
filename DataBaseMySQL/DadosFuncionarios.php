<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 07/07/2017
 */

// Classe criada para manipular os do funcionário no banco de dados.
class DadosFuncionarios {
	
	// Retorna uma lista de funcionários de acordo com a empresa.
	public function PegaListaFuncionarios($id){
		
		$rows = '';
		
		$query = " SELECT * FROM dados_do_funcionario WHERE id = ".$id." AND nome <> '' ORDER BY nome ";
	
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
			
		return $rows;
	}	
	
	// Pega os dados do funcionário de acordo com o seu ID.
	public function PegaFuncionarios($idFuncionario){
		
		$rows = '';
		
		$query = " SELECT * FROM dados_do_funcionario WHERE idFuncionario = '".$idFuncionario."'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
	
	// Pega a quantidade de depedentes do funcionario.
	public function PegaNumeroDepedentes($idFuncionario){
		
		$rows = '';
		
		$query = " SELECT COUNT(*) as dependentes FROM dados_dependentes_funcionario WHERE idFuncionario = '".$idFuncionario."'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
	
	// Método criado para pegar lista com os dados do funcionario de acordo com o ano e me 
	public function PegaDadosFuncRefPagamentoMes($empresaId, $mes, $ano) {
				
		$rows = '';
				
		$query = "SELECT * FROM dados_do_funcionario f
					WHERE idFuncionario in (SELECT funcionarioId 
						FROM dados_pagamentos_funcionario p
						WHERE p.empresaId = '".$empresaId."' 
						AND YEAR(data_referencia) = '".$ano."'
						AND MONTH(data_referencia) = '".$mes."'
						GROUP BY funcionarioId)
					AND f.data_admissao <= '".$ano."-".$mes."-31'
					AND (f.data_demissao IS NULL OR f.data_demissao > '".$ano."-".$mes."-01')
					ORDER BY pis;";
		
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		return $rows;		
	}	
	
	// Método criado para pegar lista com os dados do funcionario de acordo com o Décimo terceiro. 
	public function PegaDadosFuncRefPagamentoMes13($empresaId, $ano) {
				
		$rows = '';
				
		$query = "SELECT * FROM dados_do_funcionario f
					WHERE idFuncionario in (SELECT funcionarioId 
						FROM dados_pagamentos_funcionario p
						WHERE p.empresaId = '".$empresaId."' 
						AND (MONTH(data_referencia) = 11 OR MONTH(data_referencia) = 12) 
						AND YEAR(data_referencia) = '".$ano."'
						GROUP BY funcionarioId)
					ORDER BY pis;";

		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		return $rows;		
	}	
	
}