<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 21/09/2017
 */

// Classe criada para manipular os dados do Pagamento do Funcionario
class PagamentoFerias {
	
	// Retorna os dados de pagamento
	public function PegaPagamentoFeriaso($feriasId){
		
		$rows = '';
		
		$query = " SELECT * FROM dados_ferias_funcionario WHERE feriasId = '".$feriasId."'";
	
		$consulta = mysql_query($query);		

		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
	
	// Método criado para apaga o pagamento.
	public function ExcluiDadosPagamentoFerias($empresaId, $feriasId){
		
		// Exclui os dados do pagamento.
		$query = "DELETE FROM dados_ferias_funcionario WHERE feriasId = '".$feriasId."' AND empresaId = '".$empresaId."' ";
		
		// Executa a Exclusão.
		mysql_query($query);
	}	
	
	// Método criado par a verificar se existe férias neste período.
	public function PegaFeriasPorPeriodo($empresaId, $funcionarioId, $mes, $ano) {
	
		$rows = false;
		
		// Estrutura que retorna os dados das férias de acordo com período informado.
		$query = " SELECT * FROM dados_ferias_funcionario " 
				." WHERE empresaId = ".$empresaId
				." AND funcionarioId = ".$funcionarioId
				." AND (Month(data_inicio) = ".$mes." OR Month(data_fim) = ".$mes.") "
				." AND (Year(data_inicio) = ".$ano." OR Year(data_fim) = ".$ano.") ";
		
		$consulta = mysql_query($query);		

		// Verifica se existe algum retorno.
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
		
		return $rows;
	}
		
	// Retorna uma lista com os dados de pagamento de férias do funcionario
	public function PegaListaPagamentoFerias($empresaId, $data1, $data2, $funcionarioId = false){
		
		$rows = '';
				
		$query = "SELECT p.*, f.nome AS funcionarioNome
				  FROM dados_ferias_funcionario p
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
	
	// Realiza a inclusão dos dados do pagamento das ferias.
	public function GravaDadosPagamentoFerias($object) {

		// Mónta a estrutura para a inclusão dos dados.
		$qry = "INSERT INTO dados_ferias_funcionario (empresaId, funcionarioId, data_inclusao, data_pagto, data_inicio, data_fim, diasFerias ,valorSalarioFuncionario, valorFeriasMes1, valorFeriasMes2, valorFerias, valorUmTercoFerias, vendaUmTercoFerias, dataFeriasAbonoInicio, dataFeriasAbonoFim, valorFeriasVendida, valorUmTercoFeriasVendida, referenciaINSS, valorINSS, referenciaSecundarioINSS, valorSecundarioINSS, referenciaIR, valorIR, faixaIR, valorPensao, PorcentagemPensao, numeroDependentes, valorDescontoDependente, valorliquido, livroCaixaId)		
		VALUES ('".$object->getEmpresaId()."'
		, '".$object->getFuncionarioId()."'
		,  NOW()
		,'".$object->getDataPagto() ."'
		,'".$object->getDataInicio()."'
		,'".$object->getDataFim()."'
		,'".$object->getDiasFerias()."'
		,'".$object->getSalarioFuncionario()."'
		,'".$object->getValorFeriasMes1()."'
		,'".$object->getValorFeriasMes2()."'
		,'".$object->getValorFerias()."'
		,'".$object->getValorUmTercoFerias()."'
		,'".$object->getVendaUmTercoFerias()."'
		,".($object->getDataFeriasAbonoInicio() ? "'".$object->getDataFeriasAbonoInicio()."'" : "NULL" )."
		,".($object->getDataFeriasAbonoFim() ? "'".$object->getDataFeriasAbonoFim()."'" : "NULL" )."
		,'".$object->getValorFeriasVendida()."'
		,'".$object->getValorUmTercoFeriasVendida()."'
		,'".$object->getReferenciaINSS()."'
		,'".$object->getValorINSS()."'
		,'".$object->getReferenciaSecundarioINSS()."'
		,'".$object->getValorSecundarioINSS()."'
		,'".$object->getReferenciaIR()."'
		,'".$object->getValorIR()."'
		,'".$object->getFaixaIR()."'
		,'".$object->getValorPensao()."'
		,'".$object->getReferenciaPensao()."'		
		,'".$object->getNumeroDependentes()."'		
		,'".$object->getDescontoDepValor()."'
		,'".$object->getValorLiquido()."'
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