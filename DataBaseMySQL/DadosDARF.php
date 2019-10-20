<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */

// Classe criada para manipular os dados do Pagamento do Funcionario
class DadosDARF {

	// Retorna uma lista com os dados de pagamento do funcionario
	public function PegaListaPagamentoFuncionario($empresaId, $data1, $data2, $funcionarioId = false){
		
		$rows = '';
				
		$query = " SELECT p.pagtoId               
						,p.empresaId
						,p.funcionarioId
						,p.data_pagto
						,sum(p.valor_abono) as valor_abono
						,sum(p.valor_familia) as valor_familia              
						,sum(p.valor_maternidade) as valor_maternidade         
						,sum(p.valor_salario) as valor_salario      
						,sum(p.valor_Ferias) as valor_Ferias  
						,sum(p.valorUmTercoFerias) as valorUmTercoFerias
						,sum(p.valorFeriasVendida) as valorFeriasVendida       
						,sum(p.valorUmTercoFeriasVendida) as valorUmTercoFeriasVendida           
						,sum(p.valorIRFerias) as IRFerias             
						,sum(p.valor_bruto) as valor_bruto              
						,sum(p.valor_INSS) as INSS                 
						,sum(p.valor_IR) as IR                         
						,sum(p.valorDescontoDependente) as desconto_dependentes 
						,sum(p.valor_liquido) as valor_liquido
						,f.idFuncionario              
						,f.nome                       
						,f.cpf
					FROM dados_pagamentos_funcionario p 
					JOIN dados_do_funcionario f ON f.idFuncionario = p.funcionarioId
					WHERE p.empresaId = '".$empresaId."' 
					AND p.data_pagto >= '".$data1."'
					AND p.data_pagto <= '".$data2."'"; 		
		
		if(is_numeric($funcionarioId)) {
			$query .= " AND funcionarioId = '".$funcionarioId."'"; 
		}
		
		$query .= "GROUP BY  p.funcionarioId, p.data_pagto
				   ORDER BY p.data_pagto DESC, f.nome ASC ";
		
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
			
		return $rows;
	}
	
	// Método criado para pegar os dados de pagamentos (pró-labore, Autônomos, pessoa jurídica, distr. de lucros).
	public function PegaListaPagamentos($empresaId, $mes, $ano) {
	
		// Query que pega uma lista de pagamentos.
		$query = "SELECT pgto.id_pagto
					, pgto.valor_bruto
					, pgto.INSS
					, pgto.IR
					, pgto.ISS
					, pgto.valor_liquido
					, pgto.data_emissao
					, pgto.data_pagto  
					, pgto.desconto_dependentes
					, pgto.codigo_servico
					, pgto.descricao_servico
					, case 
						  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) = 4 then 'Anual' 
						  when pgto.id_lucro <> 0 AND LENGTH(pgto.data_periodo_ini) > 4 then 'Antecipação mensal' 
						  else '' 
					  end periodo
					, case 
						  when pgto.id_autonomo <> 0 then aut.dependentes 
						  when pgto.id_socio <> 0 then socio.dependentes
						  else 0
					  end dependentes
					, case 
						  when pgto.id_autonomo <> 0 then 'Autônomos' 
						  when pgto.id_socio <> 0 then 'pró-labore' 
						  when pgto.id_pj <> 0 then 'pessoa jurídica' 
						  when pgto.id_lucro <> 0 then 'distr. de lucros' 
						  else 'Estagiários' 
					  end tipo
					, case 
						  when pgto.id_autonomo <> 0 then aut.id
							when pgto.id_socio <> 0 then socio.idSocio
						  when pgto.id_pj <> 0 then pj.id 
						  when pgto.id_lucro <> 0 then dl.idSocio
						  else est.id 
					  end id
					, case 
						  when pgto.id_autonomo <> 0 then aut.nome
						  when pgto.id_socio <> 0 then socio.nome
						  when pgto.id_pj <> 0 then pj.nome
						  when pgto.id_lucro <> 0 then dl.nome
						  else est.nome 
					  end nome
					, case 
						  when pgto.id_autonomo <> 0 then aut.cpf 
						  when pgto.id_socio <> 0 then socio.cpf 
						  when pgto.id_pj <> 0 then pj.cnpj 
						  when pgto.id_lucro <> 0 then dl.cpf 
						  else est.cpf
					  end cpf
					, case 
						  when pgto.id_pj <> 0 then pj.op_simples
						  else 0
					  end op_simples
					, case 
						  when pgto.id_autonomo <> 0 then '3' 
						  when pgto.id_socio <> 0 then '2' 
						  when pgto.id_pj <> 0 then '5' 
						  when pgto.id_lucro <> 0 then '1' 
						  else '4' 
					  end ordem
				FROM 
					dados_pagamentos pgto
					left join dados_autonomos aut on pgto.id_autonomo = aut.id
					left join dados_do_responsavel socio on pgto.id_socio = socio.idSocio
					left join estagiarios est on pgto.id_estagiario = est.id
					left join dados_pj pj on pgto.id_pj = pj.id
					left join dados_do_responsavel dl on pgto.id_lucro = dl.idSocio
				WHERE 
					pgto.id_login='".$empresaId."'";

		if(!empty($mes)){
			$query .= " AND MONTH(CASE WHEN pgto.id_pj > 0 THEN pgto.data_emissao ELSE pgto.data_pagto END) = '".$mes."'";
		}
		if(!empty($ano)){
			$query .= " AND YEAR(CASE WHEN pgto.id_pj > 0 THEN pgto.data_emissao ELSE pgto.data_pagto END) = '".$ano."'";
		}

		$query .= " HAVING 1=1 ";

		$query .= " AND tipo in ('pró-labore', 'Autônomos', 'pessoa jurídica')";			

		$query .= " ORDER BY ordem, codigo_servico, data_pagto DESC";

		// Execulta a query.
		$consulta = mysql_query($query) or die (mysql_error());
		
		$out = false;
		
		// Verifica se 
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$out[] = $array ;
			}
		}
			
		return $out;
	}
}