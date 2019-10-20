<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 30/11/2017
 */

// Classe criada para manipular os do funcionário no banco de dados.
class DadosSefipFolha {
	
	// Método criado para pega cnae principal da empresa. 
	public function PegaCNAEPrincipalDaEmpresa($empresaId) {
		
		$sql = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $empresaId . "' AND tipo='1' LIMIT 0, 1";
		
		$resultado = mysql_query($sql) or die (mysql_error());
			
		return mysql_fetch_array($resultado);
	}
	
	// Método criado para pega anexo do cnae principal
	public function PegaAnexoDoCNAEPrincipal($cnae) { 

		$sql = "SELECT * FROM cnae WHERE cnae='" . $cnae . "' LIMIT 0, 1";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		return mysql_fetch_array($resultado);
	}
	
	// Método criado para pega FPAS de acordo com o codigo do CNAE. 
	public function PegaFPS($cnae) {
		
		$sql = "SELECT * FROM cnae_fpas WHERE cnae='" . $cnae . "' LIMIT 0, 1";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		return mysql_fetch_array($resultado);
	}
	
	// Método criado para pega alíquota RAT de acordo com o codigo do CNAE. 
	public function PegaRAT($cnae) {
		
		$sql = "SELECT * FROM cnae_rat WHERE cnae='" . $cnae . "' LIMIT 1";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		return mysql_fetch_array($resultado);
	}
	
	// Método criado para pera os autonomos.
	public function PegaListaDeAutonomosSocio() {
		
		$rows = false;

		$sql = "SELECT 
					pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_pagto  
					, case when pgto.id_autonomo <> 0 then 'autonomo' else 'socio' end tipo
					, case when pgto.id_autonomo <> 0 then aut.id else socio.idSocio end id
					, case when pgto.id_autonomo <> 0 then aut.nome else socio.nome end nome
					, case when pgto.id_autonomo <> 0 then aut.cpf else socio.cpf end cpf
				FROM 
					dados_pagamentos pgto
					left join dados_autonomos aut on pgto.id_autonomo = aut.id
					left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
				WHERE 
					pgto.id_login='" . $empresaId . "'
					AND pgto.id_estagiario = '0'
					AND pgto.id_pj = '0'
					AND pgto.id_lucro = '0'
					AND YEAR(data_pagto) = '".$ano."'
					AND MONTH(data_pagto) = '".$mes."'
					ORDER BY data_pagto DESC";
	
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}	
	
	//Pega os ids de tomadores a serem inseridos no arquivo
	public function SEFIPTomadores($empresaId) {
		
		$rows = false;
		
		$sql = "SELECT * FROM sefip_tomadores WHERE id_user = '".$empresaId."' group by id_tomador ";
			
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;	
	}
	
	//Pega os ids de tomadores a serem inseridos no arquivo
	public function DadosDaEmpresa($empresaId) {
		
		$sql = "SELECT cnpj as cei, razao_social as nome, endereco as endereco, bairro as bairro, cep as cep, cidade as cidade, estado as estado FROM dados_da_empresa WHERE id = '".$empresaId."' ";
			
		$resultado = mysql_query($sql) or die (mysql_error());
		
		return mysql_fetch_array($resultado);	
	}
		
	//Pega os dados do tomadores.
	public function DadosTomadores($tomadorId) {
		
		$sql = "SELECT * FROM dados_tomadores WHERE id = '".$tomadorId."' ";
			
		$resultado = mysql_query($sql) or die (mysql_error());
		
		return mysql_fetch_array($resultado);
	}	
	
	
	//Pega os ids de tomadores a serem inseridos no arquivo
	public function SEFIPTomadoresId($tomadorId) {
		
		$rows = false;
		
		$sql = "SELECT * FROM sefip_tomadores WHERE id_tomador = '".$tomadorId."' ORDER BY ordem ASC";
			
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;	
	}
	
	public function DadosAutonomos($id) {
		
		$sql = "SELECT * FROM dados_autonomos WHERE id = '".$id."' ";
			
		$resultado = mysql_query($sql) or die (mysql_error());
		
		return mysql_fetch_array($resultado);	
	}
	
	public function DadosPagamentos($empresaId, $autonomoId, $mes, $ano) {
		
		$sql = "SELECT * FROM dados_pagamentos WHERE id_login = '".$empresaId."' AND id_autonomo = '".$autonomoId."' AND YEAR(data_pagto) = '".$ano."' AND MONTH(data_pagto) = '".$mes."' ";
			
		$resultado = mysql_query($sql) or die (mysql_error());
		
		return mysql_fetch_array($resultado);
	}
	
	//Busca todos os pagamentos no periodo informado
	public function BuscaTodosPagtosPeriodoInformado($empresaId, $mes, $ano) {
		
		$rows = false;
		
		$sql = "SELECT * FROM dados_pagamentos WHERE id_login = '".$empresaId."' AND YEAR(data_pagto) = '".$ano."' AND MONTH(data_pagto) = '".$mes."' ";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}	
	
	// Pega os dados do sócio e autonos.
	public function PegaDadosAutonomosSocio($empresaId, $mes, $ano) {
	
		$rows = false;
		
		$sql = "SELECT 
					case when pgto.id_autonomo <> 0 then 'autonomo' else 'socio' end tipo_trabalhador
					, case when pgto.id_autonomo <> 0 then REPLACE(REPLACE(aut.pis, '-', ''), '.', '') else socio.nit end nit
					, case when pgto.id_autonomo <> 0 then aut.nome else socio.nome end nome
					, case when pgto.id_autonomo <> 0 then '        ' else socio.data_admissao end data_admissao
					, case when pgto.id_autonomo <> 0 then '        ' else socio.data_de_nascimento end data_de_nascimento
					, case when pgto.id_autonomo <> 0 then aut.cbo else socio.codigo_cbo end codigo_cbo
					, sum(pgto.valor_bruto) pro_labore
					, sum(pgto.INSS) inss
					, sum(pgto.outra_fonte) outra_fonte
				FROM 
					dados_pagamentos pgto
					left join dados_autonomos aut on pgto.id_autonomo = aut.id
					left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
				WHERE 
					pgto.id_login='".$empresaId."'
					AND pgto.id_estagiario = '0'
					AND pgto.id_pj = '0'
					AND pgto.id_lucro = '0'
					AND YEAR(data_pagto) = '".$ano."'
					AND MONTH(data_pagto) = '".$mes."'
				GROUP BY 1, 2, 3, 4, 5, 6
				ORDER BY 2
				";

		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
		
	}
	
	//Busca todos os pagamentos no periodo informado
	public function PegaPagtoFuncionario($empresaId, $funcionarioId, $mes, $ano) {
		
		$rows = '';
				
		$query = "SELECT * FROM dados_pagamentos_funcionario
					WHERE empresaId = '".$empresaId."'
					AND funcionarioId = '".$funcionarioId."'
					AND YEAR(data_referencia) = '".$ano."'
					AND MONTH(data_referencia) = '".$mes."'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ) {
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}
	
	// Retorna o valor do 13° completo.
	public function PegaPagtoDecimoTerceiroFuncionario($empresaId, $funcionarioId, $ano) {
		
		$rows = '';
				
		$query = "SELECT * FROM dados_pagamentos_funcionario
					WHERE empresaId = '".$empresaId."'
					AND funcionarioId = '".$funcionarioId."'
					AND (MONTH(data_referencia) = 11 OR MONTH(data_referencia) = 12) 
					AND YEAR(data_referencia) = '".$ano."'
					AND tipoPagamento = 'decimoTerceiro'
					AND (parcelaDecimo = 'segunda' OR parcelaDecimo = 'unica' )";

		$consulta = mysql_query($query);
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	
}