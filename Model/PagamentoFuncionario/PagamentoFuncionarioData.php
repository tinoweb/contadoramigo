<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */
require_once('DataBaseMySQL/PagamentoFuncionario.php');
require_once('Model/PagamentoFuncionario/vo/PagamentoFuncionarioVo.php');

class PagamentoFuncionarioData {
	
	// Pega o pagamento do funcionario.
	public function PegaPagamentoFuncionario($pagtoId) {
		
		$objetoPagamento = false;
		
		// Instancia da classe responsavel pela manipulação dos dados do pagamento do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionario();
		
		// Pega os dados do pagamento do banco.
		$dados = $pagamentoFuncionario->PegaPagamentoFuncionario($pagtoId);
		
		// Verifica se existe dados.
		if($dados) {
			
			$objetoPagamento = new PagamentoFuncionarioVo();
			
			$objetoPagamento->setPagtoId($dados['pagtoId']);
			$objetoPagamento->setEmpresaId($dados['empresaId']);
			$objetoPagamento->setFuncionarioId($dados['funcionarioId']);
			$objetoPagamento->setDataPagto($dados['data_pagto']);
			$objetoPagamento->setDataReferencia($dados['data_referencia']);
			$objetoPagamento->setDataEmissao($dados['data_emissao']); 
			$objetoPagamento->setValorBruto($dados['valor_bruto']);
			$objetoPagamento->setReferenciaINSS($dados['referencia_INSS']);
			$objetoPagamento->setValorINSS($dados['valor_INSS']);
			$objetoPagamento->setReferenciaIR($dados['referencia_IR']);
			$objetoPagamento->setValorIR($dados['valor_IR']);
			$objetoPagamento->setReferenciaVR($dados['referencia_VR']);
			$objetoPagamento->setValorVR($dados['valor_VR']);
			$objetoPagamento->setValorliquido($dados['valor_liquido']);
			$objetoPagamento->setFaixaIR($dados['faixaIR']);
			$objetoPagamento->setReferenciaVT($dados['referencia_VT']);
			$objetoPagamento->setValorVT($dados['valor_VT']);
			$objetoPagamento->setValorSalario($dados['valor_salario']);
			$objetoPagamento->setValorFamilia($dados['valor_familia']);
			$objetoPagamento->setValorMaternidade($dados['valor_maternidade']);
			$objetoPagamento->setValorAbono($dados['valor_abono']);
			$objetoPagamento->setValorBonus($dados['valor_bonus']);
			$objetoPagamento->setValorPensao($dados['valorPensao']);
			$objetoPagamento->setReferenciaPensao($dados['PorcentagemPensao']);
			$objetoPagamento->setDiasTrabalhado($dados['diasTrabalhado']);
			$objetoPagamento->setFaltas($dados['faltas']);
			$objetoPagamento->setValorFaltas($dados['valor_faltas']);
			$objetoPagamento->setTipoPagto($dados['tipoPagamento']);
			$objetoPagamento->setParcelaDecimo($dados['parcelaDecimo']);
			$objetoPagamento->setMesesTrabalhado($dados['mesesTrabalhado']);
			$objetoPagamento->setValorSalarioFuncionario($dados['valor_salario_funcionario']);
			$objetoPagamento->setDescontoDepValor($dados['valorDescontoDependente']);
			$objetoPagamento->setNumeroDependentes($dados['numeroDependentes']);
			$objetoPagamento->setFeriasId($dados['feriasId']);
			$objetoPagamento->setDiasFerias($dados['diasFerias']);
			$objetoPagamento->setValorFerias($dados['valor_Ferias']);
			$objetoPagamento->setValorUmTercoFerias($dados['valorUmTercoFerias']);
			$objetoPagamento->setVendaUmTercoFerias($dados['vendaUmTercoFerias']);
			$objetoPagamento->setValorFeriasVendida($dados['valorFeriasVendida']);
			$objetoPagamento->setValorUmTercoFeriasVendida($dados['valorUmTercoFeriasVendida']);
			$objetoPagamento->setReferenciaIRFerias($dados['referenciaIRFerias']);
			$objetoPagamento->setValorIRFerias($dados['valorIRFerias']);
			$objetoPagamento->setLiquidoFerias($dados['liquidoFerias']);
		
		}
		
		return $objetoPagamento;
	}
	
	//Verificar a data atual no filtro
	public function PegaDataUltimoPagamento($empresaId){
		
		$pagamentoFuncionario = new PagamentoFuncionario();

		$dataPagamento = $pagamentoFuncionario->PegaUltimoPagamento($empresaId);
		
		return $dataPagamento;
	}

	// Pega uma lista com os pagamenros do funcionario.
	public function PegaListaPagamento($empresaId, $data1, $data2, $funcionarioId = false) {
		
		$out = false;
		
		// Instancia da classe responsavel pela manipulação dos dados do pagamento do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionario();
		
		// Pega os dados do pagamento do banco.
		$listaPagamnetos = $pagamentoFuncionario->PegaListaPagamentoFuncionario($empresaId, $data1, $data2, $funcionarioId);
		
		
		
		if($listaPagamnetos) { 
			
			foreach($listaPagamnetos as $val){
				
				$objetoPagamento = new PagamentoFuncionarioVo();
			
				$objetoPagamento->setPagtoId($val['pagtoId']);
				$objetoPagamento->setEmpresaId($val['empresaId']);
				$objetoPagamento->setFuncionarioId($val['funcionarioId']);
				$objetoPagamento->setFuncionarioNome($val['funcionarioNome']);
				$objetoPagamento->setDataPagto($val['data_pagto']);
				$objetoPagamento->setDataReferencia($val['data_referencia']);
				$objetoPagamento->setDataEmissao($val['data_emissao']); 
				$objetoPagamento->setValorBruto($val['valor_bruto']);
				$objetoPagamento->setReferenciaINSS($val['referencia_INSS']);
				$objetoPagamento->setValorINSS($val['valor_INSS']);
				$objetoPagamento->setReferenciaIR($val['referencia_IR']);
				$objetoPagamento->setValorIR($val['valor_IR']);
				$objetoPagamento->setReferenciaVR($val['referencia_VR']);
				$objetoPagamento->setValorVR($val['valor_VR']);
				$objetoPagamento->setValorliquido($val['valor_liquido']);
				$objetoPagamento->setFaixaIR($val['faixaIR']);
				$objetoPagamento->setReferenciaVT($val['referencia_VT']);
				$objetoPagamento->setValorVT($val['valor_VT']);
				$objetoPagamento->setValorSalario($val['valor_salario']);
				$objetoPagamento->setValorFamilia($val['valor_familia']);
				$objetoPagamento->setValorMaternidade($val['valor_maternidade']);
				$objetoPagamento->setValorAbono($val['valor_abono']);
				$objetoPagamento->setValorBonus($val['valor_bonus']);
				$objetoPagamento->setValorPensao($val['valorPensao']);
				$objetoPagamento->setReferenciaPensao($val['PorcentagemPensao']);
				$objetoPagamento->setDiasTrabalhado($val['diasTrabalhado']);
				$objetoPagamento->setFaltas($val['faltas']);
				$objetoPagamento->setValorFaltas($val['valor_faltas']);
				$objetoPagamento->setTipoPagto($val['tipoPagamento']);
				$objetoPagamento->setParcelaDecimo($val['parcelaDecimo']);
				$objetoPagamento->setMesesTrabalhado($val['mesesTrabalhado']);
				$objetoPagamento->setValorSalarioFuncionario($val['valor_salario_funcionario']);
				$objetoPagamento->setDescontoDepValor($val['valorDescontoDependente']);
				$objetoPagamento->setNumeroDependentes($val['numeroDependentes']);
				$objetoPagamento->setFeriasId($val['feriasId']);
				$objetoPagamento->setDiasFerias($val['diasFerias']);
				$objetoPagamento->setValorFerias($val['valor_Ferias']);
				$objetoPagamento->setValorUmTercoFerias($val['valorUmTercoFerias']);
				$objetoPagamento->setVendaUmTercoFerias($val['vendaUmTercoFerias']);
				$objetoPagamento->setValorFeriasVendida($val['valorFeriasVendida']);
				$objetoPagamento->setValorUmTercoFeriasVendida($val['valorUmTercoFeriasVendida']);
				$objetoPagamento->setReferenciaIRFerias($val['referenciaIRFerias']);
				$objetoPagamento->setValorIRFerias($val['valorIRFerias']);
				$objetoPagamento->setLiquidoFerias($val['liquidoFerias']);
				
				$out[] = $objetoPagamento;
			}
		}
		return $out;
	}
	
	// Pega uma lista com os pagamenros do funcionario.
	public function PegaListaPagamentoParaGefip($empresaId, $mes, $ano) {
		
		$out = false;
		
		// Instancia da classe responsavel pela manipulação dos dados do pagamento do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionario();
		
		// Pega os dados do pagamento do banco.
		$listaPagamnetos = $pagamentoFuncionario->PegaListaPagamentoParaGefip($empresaId, $mes, $ano);		
		
		if($listaPagamnetos) { 
			
			foreach($listaPagamnetos as $val){
				
				$objetoPagamento = new PagamentoFuncionarioVo();
			
				$objetoPagamento->setPagtoId($val['pagtoId']);
				$objetoPagamento->setEmpresaId($val['empresaId']);
				$objetoPagamento->setFuncionarioId($val['funcionarioId']);
				$objetoPagamento->setFuncionarioNome($val['funcionarioNome']);
				$objetoPagamento->setDataPagto($val['data_pagto']);
				$objetoPagamento->setDataReferencia($val['data_referencia']);
				$objetoPagamento->setDataEmissao($val['data_emissao']); 
				$objetoPagamento->setValorBruto($val['valor_bruto']);
				$objetoPagamento->setReferenciaINSS($val['referencia_INSS']);
				$objetoPagamento->setValorINSS($val['valor_INSS']);
				$objetoPagamento->setReferenciaIR($val['referencia_IR']);
				$objetoPagamento->setValorIR($val['valor_IR']);
				$objetoPagamento->setReferenciaVR($val['referencia_VR']);
				$objetoPagamento->setValorVR($val['valor_VR']);
				$objetoPagamento->setValorliquido($val['valor_liquido']);
				$objetoPagamento->setFaixaIR($val['faixaIR']);
				$objetoPagamento->setReferenciaVT($val['referencia_VT']);
				$objetoPagamento->setValorVT($val['valor_VT']);
				$objetoPagamento->setValorSalario($val['valor_salario']);
				$objetoPagamento->setValorFamilia($val['valor_familia']);
				$objetoPagamento->setValorMaternidade($val['valor_maternidade']);
				$objetoPagamento->setValorAbono($val['valor_abono']);
				$objetoPagamento->setValorBonus($val['valor_bonus']);
				$objetoPagamento->setValorPensao($val['valorPensao']);
				$objetoPagamento->setReferenciaPensao($val['PorcentagemPensao']);
				$objetoPagamento->setDiasTrabalhado($val['diasTrabalhado']);
				$objetoPagamento->setFaltas($val['faltas']);
				$objetoPagamento->setValorFaltas($val['valor_faltas']);
				$objetoPagamento->setTipoPagto($val['tipoPagamento']);
				$objetoPagamento->setParcelaDecimo($val['parcelaDecimo']);
				$objetoPagamento->setMesesTrabalhado($val['mesesTrabalhado']);
				$objetoPagamento->setValorSalarioFuncionario($val['valor_salario_funcionario']);
				$objetoPagamento->setDescontoDepValor($val['valorDescontoDependente']);
				$objetoPagamento->setNumeroDependentes($val['numeroDependentes']);
				$objetoPagamento->setFeriasId($val['feriasId']);
				$objetoPagamento->setDiasFerias($val['diasFerias']);
				$objetoPagamento->setValorFerias($val['valor_Ferias']);
				$objetoPagamento->setValorUmTercoFerias($val['valorUmTercoFerias']);
				$objetoPagamento->setVendaUmTercoFerias($val['vendaUmTercoFerias']);
				$objetoPagamento->setValorFeriasVendida($val['valorFeriasVendida']);
				$objetoPagamento->setValorUmTercoFeriasVendida($val['valorUmTercoFeriasVendida']);
				$objetoPagamento->setReferenciaIRFerias($val['referenciaIRFerias']);
				$objetoPagamento->setValorIRFerias($val['valorIRFerias']);
				$objetoPagamento->setLiquidoFerias($val['liquidoFerias']);

				$out[] = $objetoPagamento;
			}
		}
		
		return $out;
	}
	
	// Pega uma lista com os pagamenros do funcionario.
	public function PegaListaPagamentoParaGefip13($empresaId, $ano) {
		
		$out = false;
		
		// Instancia da classe responsavel pela manipulação dos dados do pagamento do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionario();
		
		// Pega os dados do pagamento do banco.
		$listaPagamnetos = $pagamentoFuncionario->PegaListaPagamentoParaGefip13($empresaId, $ano);		
		
		if($listaPagamnetos) { 
			
			foreach($listaPagamnetos as $val){
				
				$objetoPagamento = new PagamentoFuncionarioVo();
			
				$objetoPagamento->setPagtoId($val['pagtoId']);
				$objetoPagamento->setEmpresaId($val['empresaId']);
				$objetoPagamento->setFuncionarioId($val['funcionarioId']);
				$objetoPagamento->setFuncionarioNome($val['funcionarioNome']);
				$objetoPagamento->setDataPagto($val['data_pagto']);
				$objetoPagamento->setDataReferencia($val['data_referencia']);
				$objetoPagamento->setDataEmissao($val['data_emissao']); 
				$objetoPagamento->setValorBruto($val['valor_bruto']);
				$objetoPagamento->setReferenciaINSS($val['referencia_INSS']);
				$objetoPagamento->setValorINSS($val['valor_INSS']);
				$objetoPagamento->setReferenciaIR($val['referencia_IR']);
				$objetoPagamento->setValorIR($val['valor_IR']);
				$objetoPagamento->setReferenciaVR($val['referencia_VR']);
				$objetoPagamento->setValorVR($val['valor_VR']);
				$objetoPagamento->setValorliquido($val['valor_liquido']);
				$objetoPagamento->setFaixaIR($val['faixaIR']);
				$objetoPagamento->setReferenciaVT($val['referencia_VT']);
				$objetoPagamento->setValorVT($val['valor_VT']);
				$objetoPagamento->setValorSalario($val['valor_salario']);
				$objetoPagamento->setValorFamilia($val['valor_familia']);
				$objetoPagamento->setValorMaternidade($val['valor_maternidade']);
				$objetoPagamento->setValorAbono($val['valor_abono']);
				$objetoPagamento->setValorBonus($val['valor_bonus']);
				$objetoPagamento->setValorPensao($val['valorPensao']);
				$objetoPagamento->setReferenciaPensao($val['PorcentagemPensao']);
				$objetoPagamento->setDiasTrabalhado($val['diasTrabalhado']);
				$objetoPagamento->setFaltas($val['faltas']);
				$objetoPagamento->setValorFaltas($val['valor_faltas']);
				$objetoPagamento->setTipoPagto($val['tipoPagamento']);
				$objetoPagamento->setParcelaDecimo($val['parcelaDecimo']);
				$objetoPagamento->setMesesTrabalhado($val['mesesTrabalhado']);
				$objetoPagamento->setValorSalarioFuncionario($val['valor_salario_funcionario']);
				$objetoPagamento->setDescontoDepValor($val['valorDescontoDependente']);
				$objetoPagamento->setNumeroDependentes($val['numeroDependentes']);
				$objetoPagamento->setFeriasId($val['feriasId']);
				$objetoPagamento->setDiasFerias($val['diasFerias']);
				$objetoPagamento->setValorFerias($val['valor_Ferias']);
				$objetoPagamento->setValorUmTercoFerias($val['valorUmTercoFerias']);
				$objetoPagamento->setVendaUmTercoFerias($val['vendaUmTercoFerias']);
				$objetoPagamento->setValorFeriasVendida($val['valorFeriasVendida']);
				$objetoPagamento->setValorUmTercoFeriasVendida($val['valorUmTercoFeriasVendida']);
				$objetoPagamento->setReferenciaIRFerias($val['referenciaIRFerias']);
				$objetoPagamento->setValorIRFerias($val['valorIRFerias']);
				$objetoPagamento->setLiquidoFerias($val['liquidoFerias']);

				$out[] = $objetoPagamento;
			}
		}
		
		return $out;
	}
	
	public function PegaMenorAnoIncluido($empresaId){
		
		// Instancia da classe responsavel pela manipulação dos dados do pagamento do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionario();
		
		// Pega o ano. 
		$ano = $pagamentoFuncionario->PegaOMenorAno($empresaId);
		
		return $ano['ano'];
	}
	
	// Método usado para excluir o pagamento.
	public function ExcluiDadosPagamentoFuncionario($empresaId, $pagtoId){
		
		// Instancia da classe responsavel pela manipulação dos dados do pagamento do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionario();
		
		// Pega o ano. 
		$pagamentoFuncionario->ExcluiDadosPagamento($empresaId, $pagtoId);
	}
	
	// Prepara os arquivos para a inclusão dos dados.
	public function PreparaInclusaoPagtoFuncionario($empresaId, $funcionarioId, $dataPagto, $valorBruto, $referenciaINSS, $valorINSS, $referenciaIR, $valorIR, $faixaIR, $referenciaVR, $valorVR, $referenciaVT, $valorVT, $valorLiquido, $valorSalarioFuncionario, $valorSalario, $valorFamilia, $valorMaternidade, $valorAbono, $valorBonus, $valorPensao, $referenciaPensao, $descontoDep, $diasTrabalhados, $faltas, $valorFaltas, $tipoPagto, $numeroDependentes, $parcelaDecimo=0, $mesesTrabalhado=0, $feriasId=0, $valorFerias=0, $valorUmTercoFerias=0, $vendaUmTercoFerias=0, $valorFeriasVendida=0, $valorUmTercoFeriasVendida=0, $porcIRFerias=0, $valorIRFerias=0, $diasFerias=0, $liquidoFerias=0, $dataReferencia="", $livroCaixaId = false ){
		
		// Instância a classe de objeto.
		$objetoPagamento = new PagamentoFuncionarioVo();
		
		// Passa os valores do pagamento para o objeto.

		$objetoPagamento->setEmpresaId($empresaId);
		$objetoPagamento->setFuncionarioId($funcionarioId);
		$objetoPagamento->setDataPagto($dataPagto);
		$objetoPagamento->setDataReferencia($dataReferencia);
		$objetoPagamento->setValorBruto($valorBruto);
		$objetoPagamento->setReferenciaINSS($referenciaINSS);
		$objetoPagamento->setValorINSS($valorINSS);
		$objetoPagamento->setReferenciaIR($referenciaIR);
		$objetoPagamento->setValorIR($valorIR);
		$objetoPagamento->setFaixaIR($faixaIR);
		$objetoPagamento->setReferenciaVR($referenciaVR);
		$objetoPagamento->setValorVR($valorVR);
		$objetoPagamento->setReferenciaVT($referenciaVT);
		$objetoPagamento->setValorVT($valorVT);
		$objetoPagamento->setValorliquido($valorLiquido);
		$objetoPagamento->setValorSalario($valorSalario);
		$objetoPagamento->setValorFamilia($valorFamilia);
		$objetoPagamento->setValorMaternidade($valorMaternidade);
		$objetoPagamento->setValorAbono($valorAbono);
		$objetoPagamento->setValorBonus($valorBonus);
		$objetoPagamento->setValorPensao($valorPensao);
		$objetoPagamento->setReferenciaPensao($referenciaPensao);
		$objetoPagamento->setDescontoDepValor($descontoDep);
		$objetoPagamento->setNumeroDependentes($numeroDependentes);
		$objetoPagamento->setDiasTrabalhado($diasTrabalhados);
		$objetoPagamento->setFaltas($faltas);
		$objetoPagamento->setValorFaltas($valorFaltas);
		$objetoPagamento->setTipoPagto($tipoPagto);
		$objetoPagamento->setParcelaDecimo($parcelaDecimo);
		$objetoPagamento->setMesesTrabalhado($mesesTrabalhado);
		$objetoPagamento->setFeriasId($feriasId);
		$objetoPagamento->setDiasFerias($diasFerias);
		$objetoPagamento->setValorFerias($valorFerias);
		$objetoPagamento->setValorUmTercoFerias($valorUmTercoFerias);
		$objetoPagamento->setVendaUmTercoFerias($vendaUmTercoFerias);
		$objetoPagamento->setValorFeriasVendida($valorFeriasVendida);
		$objetoPagamento->setValorUmTercoFeriasVendida($valorUmTercoFeriasVendida);		
		$objetoPagamento->setValorSalarioFuncionario($valorSalarioFuncionario);
		$objetoPagamento->setReferenciaIRFerias($porcIRFerias);
		$objetoPagamento->setValorIRFerias($valorIRFerias);
		$objetoPagamento->setLiquidoFerias($liquidoFerias);
		$objetoPagamento->setLivroCaixaId($livroCaixaId);

		// Instancia da classe responsavel pela manipulação dos dados do pagamento do funcionário.
		$pagamentoFuncionario = new PagamentoFuncionario();
		
		// Chama o método para realiza a gravação dos dados do pagamento.
		$idPagamento = $pagamentoFuncionario->GravaDadosPagamentoFuncionario($objetoPagamento);

		return $idPagamento;
	}
	
	// Método usado para fazer a inclusão do pagamento do funcionario no livro caixa.
	public function InclusaoNoLivroCaixa($empresaId, $data, $entrada, $saida, $documento, $descricao, $categoria){
		
		// Instancia da classe responsavel pelos dados das ferias.
		$PagamentoFerias = new PagamentoFerias();
		
		// Chama o metodo para realizar a exclusão.
		return $PagamentoFerias->InclusaoNoLivroCaixa($empresaId, $data, $entrada, $saida, $documento, $descricao, $categoria);
	}	
}