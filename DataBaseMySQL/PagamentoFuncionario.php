<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */

// Classe criada para manipular os dados do Pagamento do Funcionario
class PagamentoFuncionario {
	
	// Retorna os dados de pagamento
	public function PegaPagamentoFuncionario($pagtoId){
		
		$rows = '';
		
		$query = " SELECT * FROM dados_pagamentos_funcionario WHERE pagtoId = '".$pagtoId."'";
	
		$consulta = mysql_query($query);		

		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Retorna a menor ano inclusão.
	public function PegaOMenorAno($empresaId){
		
		$rows = '';
		
		$query = " SELECT MIN(YEAR(data_pagto)) AS ano FROM dados_pagamentos_funcionario WHERE empresaId = '".$empresaId."'";
	
		$consulta = mysql_query($query);		

		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Método criado para apaga o pagamento.
	public function ExcluiDadosPagamento($empresaId, $pagtoId){
		
		// Exclui os dados do pagamento.
		$query = "DELETE FROM dados_pagamentos_funcionario WHERE pagtoId = '".$pagtoId."' AND empresaId = '".$empresaId."' ";
		
		// Executa a Exclusão.
		mysql_query($query);		
		
	}	

	// Retorna uma lista com os dados de pagamento do funcionario
	public function PegaListaPagamentoFuncionario($empresaId, $data1, $data2, $funcionarioId = false){
		
		$rows = '';
				
		$query = "SELECT p.pagtoId
					,p.*
					,f.nome AS funcionarioNome
					FROM dados_pagamentos_funcionario p 
					JOIN dados_do_funcionario f ON f.idFuncionario = p.funcionarioId
					WHERE p.empresaId = '".$empresaId."' 
					AND p.data_pagto >= '".$data1."'
					AND p.data_pagto <= '".$data2."'";
		
		if(is_numeric($funcionarioId)) {
			$query .= " AND funcionarioId = '".$funcionarioId."'"; 
		}
		
		$query .= "ORDER by p.data_pagto DESC, f.nome DESC";
		
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
			
		return $rows;
	}
	
	//Retorna uma lista com o ultimo pagamento caso não tenha dados do mes atual
	public function PegaUltimoPagamento($empresaId){
		
		$rows = false;
		
		$query = "SELECT data_pagto FROM dados_pagamentos_funcionario WHERE empresaId = '".$empresaId."' ORDER BY data_pagto DESC LIMIT 1";
		
		//executa consulta.
		$consulta = mysql_query($query);
		if(mysql_num_rows($consulta) > 0){
			$rows = mysql_fetch_array($consulta);
			$rows = $rows['data_pagto'];
		}
		return $rows;
	}
	
	// Retorna uma lista com os dados de pagamento do funcionario
	public function PegaListaPagamentoParaGefip($empresaId, $mes, $ano){
		
		$rows = '';
				
		$query = "SELECT p.pagtoId
					,p.*
					,f.nome AS funcionarioNome
					FROM dados_pagamentos_funcionario p 
					JOIN dados_do_funcionario f ON f.idFuncionario = p.funcionarioId
					WHERE p.empresaId = '".$empresaId."' 
					AND YEAR(p.data_referencia) = '".$ano."'
					AND MONTH(p.data_referencia) = '".$mes."'
					AND ( f.data_demissao IS NULL OR f.data_demissao > '".$ano."-".$mes."-01' )
					ORDER by f.nome DESC";

		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}
	
	// Retorna uma lista com os dados de pagamento do funcionario
	public function PegaListaPagamentoParaGefip13($empresaId, $ano){
		
		$rows = '';
				
		$query = "SELECT p.pagtoId
					,p.*
					,f.nome AS funcionarioNome
					FROM dados_pagamentos_funcionario p 
					JOIN dados_do_funcionario f ON f.idFuncionario = p.funcionarioId
					WHERE p.empresaId = '".$empresaId."'
					AND (MONTH(p.data_referencia) = 11 OR MONTH(p.data_referencia) = 12) 
					AND YEAR(p.data_referencia) = '".$ano."'
					AND tipoPagamento = 'decimoTerceiro'";

		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}	
	
	// Realiza a inclusão dos dados da folha de pagamento.
	public function GravaDadosPagamentoFuncionario($object) {
		
		$qry = "INSERT INTO dados_pagamentos_funcionario (empresaId, funcionarioId, feriasId, tipoPagamento, data_emissao, data_pagto, data_referencia, valor_salario_funcionario, valor_abono, valor_bonus, valor_familia, valor_maternidade, mesesTrabalhado, diasTrabalhado, valor_salario, diasFerias, valor_Ferias, valorUmTercoFerias, vendaUmTercoFerias, valorFeriasVendida, valorUmTercoFeriasVendida, referenciaIRFerias, valorIRFerias, liquidoFerias, valor_bruto, faltas, valor_faltas, referencia_INSS, valor_INSS, referencia_IR, valor_IR, faixaIR, referencia_VR, valor_VR, valor_liquido, referencia_VT, valor_VT, valorPensao, PorcentagemPensao, numeroDependentes, valorDescontoDependente, parcelaDecimo, livroCaixaId) VALUES ('".$object->getEmpresaId()."'
		, '".$object->getFuncionarioId()."'
		, ".($object->getFeriasId() ? "'".$object->getFeriasId()."'" : "NULL" )."
		, '".$object->getTipoPagto()."'
		, NOW()		
		, '".$object->getDataPagto()."'
		, ".($object->getDataReferencia() ? "'".$object->getDataReferencia()."'" : "NULL" )."
		, '".$object->getValorSalarioFuncionario()."'
		, '".$object->getValorAbono()."'
		, '".$object->getValorBonus()."'
		, '".$object->getValorFamilia()."'
		, '".$object->getValorMaternidade()."'
		, '".$object->getMesesTrabalhado()."'		
		, '".$object->getDiasTrabalhado()."'
		, '".$object->getValorSalario()."'
		, '".$object->getDiasFerias()."'
		, '".$object->getValorFerias()."'
		, '".$object->getValorUmTercoFerias()."'
		, ".($object->getVendaUmTercoFerias() ? "'".$object->getVendaUmTercoFerias()."'" : "NULL" )."		
		, '".$object->getValorFeriasVendida()."'	
		, '".$object->getValorUmTercoFeriasVendida()."'
		, '".$object->getReferenciaIRFerias()."'
		, '".$object->getValorIRFerias()."'
		, '".$object->getLiquidoFerias()."'
		, '".$object->getValorBruto()."'
		, '".$object->getFaltas()."'
		, '".$object->getValorFaltas()."'		
		, '".$object->getReferenciaINSS()."'
		, '".$object->getValorINSS()."'
		, '".$object->getReferenciaIR()."'
		, '".$object->getValorIR()."'
		, '".$object->getFaixaIR()."'
		, '".$object->getReferenciaVR()."'
		, '".$object->getValorVR()."'
		, '".$object->getValorliquido()."'
		, '".$object->getReferenciaVT()."'
		, '".$object->getValorVT()."'
		, '".$object->getValorPensao()."'
		, '".$object->getReferenciaPensao()."'
		, '".$object->getNumeroDependentes()."'		
		, '".$object->getDescontoDepValor()."'
		, ".($object->getParcelaDecimo() ? "'".$object->getParcelaDecimo()."'" : "NULL" )."
		, ".($object->getLivroCaixaId() ? "'".$object->getLivroCaixaId()."'" : "NULL" ).")";

		mysql_query($qry) or die(mysql_error());
	
		return mysql_insert_id();
	}
	
	// Método criado para fazer a inclusão de dados no livro caixa do cliente
	public function InclusaoNoLivroCaixa($empresaId, $data, $entrada, $saida, $documento, $descricao, $categoria) {
		 
		$insert = "INSERT INTO user_".$empresaId."_livro_caixa (id, data, entrada, saida, documento_numero, descricao, categoria) "
			."VALUES (NULL, '".$data."', '".$entrada."', '".$saida."', '".$documento."', '".$descricao."', '".$categoria."')";		
		
				
		// Execulta a inclusão dos dados.	
		mysql_query($insert) or die(mysql_error());
	
		return mysql_insert_id();
	}
}