<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */
require_once('DataBaseMySQL/PagamentoFerias.php');
require_once('Model/PagamentoFerias/vo/PagamentoFeriasVo.php');

class PagamentoFeriasData {
	
	// Método cria do para retornar os dados das fárias de acordo com o período informado.
	public function PegaDadosFeriasPorPeriodo($empresaId, $funcionarioId, $mes, $ano) {

		$objetoPagamento = false;
		
		// Instancia da classe responsavel pela manipulação dos dados das ferias.
		$PagamentoFerias = new PagamentoFerias();

		// Chama o método para realiza a gravação dos dados do pagamento.
		$dados = $PagamentoFerias->PegaFeriasPorPeriodo($empresaId, $funcionarioId, $mes, $ano);
		
		// Verifica se existe ferias com forme a data de refencia do holerite.
		if($dados) {
			
			// Instância a classe de objeto.
			$objetoPagamento = new PagamentoFeriasVo();
		
			$objetoPagamento->setFeriasId($dados['feriasId']);
			$objetoPagamento->setEmpresaId($dados['empresaId']);
			$objetoPagamento->setFuncionarioId($dados['funcionarioId']);
			$objetoPagamento->setDataInclusao($dados['data_inclusao']);	
			$objetoPagamento->setDataPagto($dados['data_pagto']);
			$objetoPagamento->setDataInicio($dados['data_inicio']);
			$objetoPagamento->setDataFim($dados['data_fim']);
			$objetoPagamento->setDiasFerias($dados['diasFerias']);
			$objetoPagamento->setSalarioFuncionario($dados['valorSalarioFuncionario']);
			$objetoPagamento->setValorFeriasMes1($dados['valorFeriasMes1']);
			$objetoPagamento->setValorFeriasMes2($dados['valorFeriasMes2']);			
			$objetoPagamento->setValorFerias($dados['valorFerias']);
			$objetoPagamento->setValorUmTercoFerias($dados['valorUmTercoFerias']);
			$objetoPagamento->setVendaUmTercoFerias($dados['vendaUmTercoFerias']);
			$objetoPagamento->setValorFeriasVendida($dados['valorFeriasVendida']);
			$objetoPagamento->setValorUmTercoFeriasVendida($dados['valorUmTercoFeriasVendida']);
			$objetoPagamento->setDataFeriasAbonoInicio($dados['dataFeriasAbonoInicio']);
			$objetoPagamento->setDataFeriasAbonoFim($dados['dataFeriasAbonoFim']);
			$objetoPagamento->setReferenciaINSS($dados['referenciaINSS']);
			$objetoPagamento->setValorINSS($dados['valorINSS']);
			$objetoPagamento->setReferenciaSecundarioINSS($dados['referenciaSecundarioINSS']);
			$objetoPagamento->setValorSecundarioINSS($dados['valorSecundarioINSS']);			
			$objetoPagamento->setReferenciaIR($dados['referenciaIR']);
			$objetoPagamento->setValorIR($dados['valorIR']);
			$objetoPagamento->setFaixaIR($dados['faixaIR']);
			$objetoPagamento->setValorPensao($dados['valorPensao']);
			$objetoPagamento->setReferenciaPensao($dados['PorcentagemPensao']);			
			$objetoPagamento->setNumeroDependentes($dados['numeroDependentes']);
			$objetoPagamento->setDescontoDepValor($dados['valorDescontoDependente']);
			$objetoPagamento->setValorLiquido($dados['valorliquido']);
		}
		
		return $objetoPagamento;
	}
	
	// Método cria do para retornar os dados das fárias de acordo com o código.
	public function PegaDadosFeriasCodigo($feriasId) {

		$objetoPagamento = false;
		
		// Instancia da classe responsavel pela manipulação dos dados das ferias.
		$PagamentoFerias = new PagamentoFerias();

		// Chama o método para realiza a gravação dos dados do pagamento.
		$dados = $PagamentoFerias->PegaPagamentoFeriaso($feriasId);
		
		// Verifica se existe ferias com forme a data de refencia do holerite.
		if($dados) {
			
			// Instância a classe de objeto.
			$objetoPagamento = new PagamentoFeriasVo();
		
			$objetoPagamento->setFeriasId($dados['feriasId']);
			$objetoPagamento->setEmpresaId($dados['empresaId']);
			$objetoPagamento->setFuncionarioId($dados['funcionarioId']);
			$objetoPagamento->setDataInclusao($dados['data_inclusao']);	
			$objetoPagamento->setDataPagto($dados['data_pagto']);
			$objetoPagamento->setDataInicio($dados['data_inicio']);
			$objetoPagamento->setDataFim($dados['data_fim']);
			$objetoPagamento->setDiasFerias($dados['diasFerias']);
			$objetoPagamento->setSalarioFuncionario($dados['valorSalarioFuncionario']);
			$objetoPagamento->setValorFeriasMes1($dados['valorFeriasMes1']);
			$objetoPagamento->setValorFeriasMes2($dados['valorFeriasMes2']);	
			$objetoPagamento->setValorFerias($dados['valorFerias']);
			$objetoPagamento->setValorUmTercoFerias($dados['valorUmTercoFerias']);
			$objetoPagamento->setVendaUmTercoFerias($dados['vendaUmTercoFerias']);
			$objetoPagamento->setValorFeriasVendida($dados['valorFeriasVendida']);
			$objetoPagamento->setValorUmTercoFeriasVendida($dados['valorUmTercoFeriasVendida']);
			$objetoPagamento->setDataFeriasAbonoInicio($dados['dataFeriasAbonoInicio']);
			$objetoPagamento->setDataFeriasAbonoFim($dados['dataFeriasAbonoFim']);
			$objetoPagamento->setReferenciaINSS($dados['referenciaINSS']);
			$objetoPagamento->setValorINSS($dados['valorINSS']);
			$objetoPagamento->setReferenciaSecundarioINSS($dados['referenciaSecundarioINSS']);
			$objetoPagamento->setValorSecundarioINSS($dados['valorSecundarioINSS']);			
			$objetoPagamento->setReferenciaIR($dados['referenciaIR']);
			$objetoPagamento->setValorIR($dados['valorIR']);
			$objetoPagamento->setFaixaIR($dados['faixaIR']);
			$objetoPagamento->setValorPensao($dados['valorPensao']);
			$objetoPagamento->setReferenciaPensao($dados['PorcentagemPensao']);				
			$objetoPagamento->setNumeroDependentes($dados['numeroDependentes']);
			$objetoPagamento->setDescontoDepValor($dados['valorDescontoDependente']);
			$objetoPagamento->setValorLiquido($dados['valorliquido']);
		}
		
		return $objetoPagamento;
	}

	// Método cria do para retornar uma lista com os dados de fárias.
	public function PegaListaPagamentoFerias($empresaId, $data1, $data2, $funcionarioId = false) {

		$out = false;
		
		// Instancia da classe responsavel pela manipulação dos dados das ferias.
		$PagamentoFerias = new PagamentoFerias();

		// Chama o método para realiza a gravação dos dados do pagamento.
		$dados = $PagamentoFerias->PegaListaPagamentoFerias($empresaId, $data1, $data2, $funcionarioId);
		
		// Verifica se existe ferias com forme a data de refencia do holerite.
		if($dados) {
				
			foreach($dados as $val){
							
				// Instância a classe de objeto.
				$objetoPagamento = new PagamentoFeriasVo();

				$objetoPagamento->setFeriasId($val['feriasId']);
				$objetoPagamento->setEmpresaId($val['empresaId']);
				$objetoPagamento->setFuncionarioId($val['funcionarioId']);
				$objetoPagamento->setFuncionarioNome($val['funcionarioNome']);
				$objetoPagamento->setDataInclusao($val['data_inclusao']);	
				$objetoPagamento->setDataPagto($val['data_pagto']);
				$objetoPagamento->setDataInicio($val['data_inicio']);
				$objetoPagamento->setDataFim($val['data_fim']);
				$objetoPagamento->setDiasFerias($val['diasFerias']);
				$objetoPagamento->setSalarioFuncionario($val['valorSalarioFuncionario']);
				$objetoPagamento->setValorFeriasMes1($val['valorFeriasMes1']);
				$objetoPagamento->setValorFeriasMes2($val['valorFeriasMes2']);	
				$objetoPagamento->setValorFerias($val['valorFerias']);
				$objetoPagamento->setValorUmTercoFerias($val['valorUmTercoFerias']);
				$objetoPagamento->setVendaUmTercoFerias($val['vendaUmTercoFerias']);
				$objetoPagamento->setValorFeriasVendida($val['valorFeriasVendida']);
				$objetoPagamento->setValorUmTercoFeriasVendida($val['valorUmTercoFeriasVendida']);
				$objetoPagamento->setDataFeriasAbonoInicio($val['dataFeriasAbonoInicio']);
				$objetoPagamento->setDataFeriasAbonoFim($val['dataFeriasAbonoFim']);
				$objetoPagamento->setReferenciaINSS($val['referenciaINSS']);
				$objetoPagamento->setValorINSS($val['valorINSS']);
				$objetoPagamento->setReferenciaSecundarioINSS($val['referenciaSecundarioINSS']);
				$objetoPagamento->setValorSecundarioINSS($val['valorSecundarioINSS']);
				$objetoPagamento->setReferenciaIR($val['referenciaIR']);
				$objetoPagamento->setValorIR($val['valorIR']);
				$objetoPagamento->setFaixaIR($val['faixaIR']);
				$objetoPagamento->setValorPensao($val['valorPensao']);
				$objetoPagamento->setReferenciaPensao($val['PorcentagemPensao']);				
				$objetoPagamento->setNumeroDependentes($val['numeroDependentes']);
				$objetoPagamento->setDescontoDepValor($val['valorDescontoDependente']);
				$objetoPagamento->setValorLiquido($val['valorliquido']);
				
				$out[] = $objetoPagamento;
			} 
		}
		
		return $out;
	}	

	// Prepara os arquivos para a inclusão dos dados.
	public function PreparaInclusaoPagtoFerias($empresaId, $funcionarioId, $dataPagto, $dataInicio, $dataFim, $diasFerias, $salarioFuncionario, $valorFeriasMes1, $valorFeriasMes2, $valorFerias, $vendaUmTercoFerias, $valorUmTercoFerias, $referenciaINSS, $valorINSS, $referenciaIR, $valorIR, $faixaIR, $valorLiquido, $valorferiasVendida, $valorUmTercoFeriasVendida, $dataFeriasAbonoInicio, $dataFeriasAbonoFim, $descontoDep, $numeroDependentes, $referenciaSecundarioINSS, $valorSecundarioINSS, $valorPensao, $referenciaPensao, $livroCaixaId = false){
		  
		// Instância a classe de objeto.
		$objetoPagamento = new PagamentoFeriasVo();
		
		// Passa os valores do pagamento para o objeto.
		$objetoPagamento->setEmpresaId($empresaId);
		$objetoPagamento->setFuncionarioId($funcionarioId);
		$objetoPagamento->setDataPagto($dataPagto);
		$objetoPagamento->setDataInicio($dataInicio);
		$objetoPagamento->setDataFim($dataFim);
		$objetoPagamento->setDiasFerias($diasFerias);
		$objetoPagamento->setSalarioFuncionario($salarioFuncionario);
		$objetoPagamento->setValorFeriasMes1($valorFeriasMes1);
		$objetoPagamento->setValorFeriasMes2($valorFeriasMes2);
		$objetoPagamento->setValorFerias($valorFerias);
		$objetoPagamento->setValorUmTercoFerias($valorUmTercoFerias);
		$objetoPagamento->setVendaUmTercoFerias($vendaUmTercoFerias);
		$objetoPagamento->setValorFeriasVendida($valorferiasVendida);
		$objetoPagamento->setValorUmTercoFeriasVendida($valorUmTercoFeriasVendida);
		$objetoPagamento->setDataFeriasAbonoInicio($dataFeriasAbonoInicio);
		$objetoPagamento->setDataFeriasAbonoFim($dataFeriasAbonoFim);		
		$objetoPagamento->setReferenciaINSS($referenciaINSS);
		$objetoPagamento->setValorINSS($valorINSS);
		$objetoPagamento->setReferenciaIR($referenciaIR);
		$objetoPagamento->setValorIR($valorIR);
		$objetoPagamento->setFaixaIR($faixaIR);
		$objetoPagamento->setNumeroDependentes($numeroDependentes);
		$objetoPagamento->setDescontoDepValor($descontoDep);
		$objetoPagamento->setValorLiquido($valorLiquido);
		$objetoPagamento->setReferenciaSecundarioINSS($referenciaSecundarioINSS);
		$objetoPagamento->setValorSecundarioINSS($valorSecundarioINSS);
		$objetoPagamento->setValorPensao($valorPensao);
		$objetoPagamento->setReferenciaPensao($referenciaPensao);
		$objetoPagamento->setLivroCaixaId($livroCaixaId);

		// Instancia da classe responsavel pelos dados das ferias.
		$PagamentoFerias = new PagamentoFerias();

		// Chama o método para realiza a gravação dos dados do pagamento.
		$idPagamento = $PagamentoFerias->GravaDadosPagamentoFerias($objetoPagamento);

		return $idPagamento;
	}
	
	// Método usado para excluir o pagamento.
	public function ExcluiDadosPagamentoFuncionario($empresaId, $feriasId){
		
		// Instancia da classe responsavel pelos dados das ferias.
		$PagamentoFerias = new PagamentoFerias();
		
		// Chama o metodo para realizar a exclusão.
		$PagamentoFerias->ExcluiDadosPagamentoFerias($empresaId, $feriasId);
	}
	
	// Método usado para fazer a inclusão do pagamento de ferias no livro caixa.
	public function InclusaoNoLivroCaixa($empresaId, $data, $entrada, $saida, $documento, $descricao, $categoria){
		
		// Instancia da classe responsavel pelos dados das ferias.
		$PagamentoFerias = new PagamentoFerias();
		
		// Chama o metodo para realizar a exclusão.
		return $PagamentoFerias->InclusaoNoLivroCaixa($empresaId, $data, $entrada, $saida, $documento, $descricao, $categoria);
	}	
	
}