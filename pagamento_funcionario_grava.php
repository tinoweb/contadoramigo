<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 18/07/2017
 */
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// inicia a sessão
session_start();

// Realiza a requizição dos arquivo que realiza a conexão com o banco. 
require_once('conect.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento do funcionário.
require_once('Model/PagamentoFuncionario/PagamentoFuncionarioData.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados do pagamento das férias.
require_once('Model/PagamentoFerias/PagamentoFeriasData.php');

class DadosPagamentoFuncionario {
	
	function __construct() {
       		
		// Define o tipo do pagamento.
		$tipoPagto = $_POST['tipoPagto'];
		
		// Pega o código da varável.
		$empresaId = $_POST['empresaId'];
		
		// Pega o código do funcionario.
		$funcionarioId = $_POST['funcionarioId'];
		
		if(!empty($_POST['dataPgto'])){
			$dataPagto = str_replace('/','-',$_POST['dataPgto']);
		} elseif(!empty($_POST['dataPagtoFeiras'])) {
			$dataPagto = str_replace('/','-',$_POST['dataPagtoFeiras']);
		} else {
			header('location: /pagamento_funcionario.php?erro=data_pagamento');
		}
		
		$dataPagto = date('Y-m-d', strtotime($dataPagto));
		
		// Pega a data de referência do pagamento do mês trabalhado. 
		$dataReferencia = 'false';
		if(!empty($_POST['dataReferencia'])) {
			$dataReferencia = date('Y-m-d', strtotime('01-'.str_replace('/','-',$_POST['dataReferencia'])));
		}
				
		// Verifica qual será o tipo de pagamento que sera realizado.
		switch($tipoPagto) {
				
			case 'salario':
				// Realiza a chamada do método para realizar a inclusão do pagamento do salario.
				$this->GravaPagamentoSalario($empresaId, $funcionarioId, $dataPagto, $dataReferencia, $tipoPagto);
				break;
				
			case 'ferias':
				// Realiza a chamada do método para realizar a inclusão do pagamento do férias
				$this->GravaPagamentoFerias($empresaId, $funcionarioId);
				break;
				
			case 'decimoTerceiro':	
				// Realiza a chamada do método para realizar a inclusão do pagamento do décimo terceiro.
				$this->GravaPagamentoDecimo($empresaId, $funcionarioId, $dataPagto, $dataReferencia, $tipoPagto);
				break;
		}

		//Pega o ano.
		$ano = date('Y', strtotime($dataPagto));

		//Pega o Mês.
		$mes = date('m', strtotime($dataPagto));		
		
		
		header('location: /pagamento_funcionario.php?periodoMes='.$mes.'&periodoAno='.$ano.'');
	}
	
	// Método criado para realizar a inclusão dos dados do pagamento do salário.
	private function GravaPagamentoSalario($empresaId, $funcionarioId, $dataPagto, $dataReferencia, $tipoPagto) {

		//  Variável que recebera o id do livro caixa.
		$livroCaixaId = false;
		
		// Pega o ultimo dia do mês que pode ser 28 a 31 .
		$ultimoDia = date('t',strtotime($dataReferencia));
		
		// Pega o valor do sálario do funcionário
		$valorSalarioFuncionario = ($_POST['salario_Funcionario'] ? $_POST['salario_Funcionario'] : 0);	
		$valorSalarioFuncionario = str_replace(".","",$valorSalarioFuncionario);
		$valorSalarioFuncionario = str_replace(",",".",$valorSalarioFuncionario);	
		
		// Pega o valor do salario do funcionario.
		$valorSalario = ($_POST['valor_Salario'] ? $_POST['valor_Salario'] : 0);
		$valorSalario = str_replace(".","",$valorSalario);
		$valorSalario = str_replace(",",".",$valorSalario);

		// Pega a porcentagem do INSS.
		$referenciaINSS = $_POST['porcInss'];

		// Valor de retenção do INSS.
		$valorINSS = ($_POST['retencao_INSS'] ? $_POST['retencao_INSS'] : 0);
		$valorINSS = str_replace(".","",$valorINSS);
		$valorINSS = str_replace(",",".",$valorINSS);

		// Pega a porcentagem do IR
		$referenciaIR = $_POST['porcIR'];

		// Volor de retenção do IR
		$valorIR = ($_POST['retencao_IR'] ? $_POST['retencao_IR'] : 0);
		$valorIR = str_replace(".","",$valorIR);
		$valorIR = str_replace(",",".",$valorIR);

		// Pega a faxa do IR 
		$faixaIR = $_POST['faixaIR'];	

		// Pega a porcentagem do vale transporte.
		$referenciaVT = $_POST['porcVT'];

		// Valor de retenção do vale Tranporte.
		$valorVT = ($_POST['retencao_VT'] ? $_POST['retencao_VT'] : 0);
		$valorVT = str_replace(".","",$valorVT);
		$valorVT = str_replace(",",".",$valorVT);

		// Pega a porcentagem do vale refeição.		
		$referenciaVR = $_POST['porcVR'];

		// Valor de retenção do Vale Refeição
		$valorVR = ($_POST['retencao_VR'] ? $_POST['retencao_VR'] : 0);
		$valorVR = str_replace(".","",$valorVR);
		$valorVR = str_replace(",",".",$valorVR);

		$feriasId = $_POST['feriasId'];	
		
		// Valor das férias
		$valorFerias = $_POST['valorFerias'];
		$valorFerias = str_replace(".","",$valorFerias);
		$valorFerias = str_replace(",",".",$valorFerias);
		
		// Valor 1/3 das Férias
		$valorUmTercoFerias = $_POST['valorUmTercoFerias'];
		$valorUmTercoFerias = str_replace(".","",$valorUmTercoFerias);
		$valorUmTercoFerias = str_replace(",",".",$valorUmTercoFerias);
		
		// Variável que recebe a confirmação se as ferias foi vendida.
		$vendaUmTercoFerias = $_POST['vendaUmTercoFerias'];	
		
		// Valor do abono.
		$valorFeriasVendida = $_POST['valorFeriasVendida'];
		$valorFeriasVendida = str_replace(".","",$valorFeriasVendida);
		$valorFeriasVendida = str_replace(",",".",$valorFeriasVendida);
		
		// Variável que recebe o valor de 1/3 do abono.
		$valorUmTercoFeriasVendida = $_POST['valorUmTercoFeriasVendida'];
		$valorUmTercoFeriasVendida = str_replace(".","",$valorUmTercoFeriasVendida);
		$valorUmTercoFeriasVendida = str_replace(",",".",$valorUmTercoFeriasVendida);		
		
		// Porcentagem do IRRF de férias.
		$porcIRFerias = $_POST['porcIRFerias'];
		$porcIRFerias = str_replace(",",".",$porcIRFerias);	
		
		// Valor do IRRF de Férias.
		$valorIRFerias = $_POST['valorIRFerias'];
		$valorIRFerias = str_replace(".","",$valorIRFerias);
		$valorIRFerias = str_replace(",",".",$valorIRFerias);	
		
		// Pega os dias de ferias.
		$diasFerias = $_POST['diasFerias'];
		
		// Pega o valor liquido das ferias.
		$liquidoFerias = (isset($_POST['liquidoFerias']) && !empty($_POST['liquidoFerias']) ? $_POST['liquidoFerias'] : 0);
		$liquidoFerias = str_replace(".","",$liquidoFerias);
		$liquidoFerias = str_replace(",",".",$liquidoFerias);	
				
		// Valor liquido
		$valorLiquido = ($_POST['valor_Liquido'] ? $_POST['valor_Liquido'] : 0);
		$valorLiquido = str_replace(".","",$valorLiquido);
		$valorLiquido = str_replace(",",".",$valorLiquido);

		// Valor a ser pago para os dependentes.
		$descontoDep = ($_POST['descontoDep'] ? $_POST['descontoDep'] : 0);
		$descontoDep = str_replace(".","",$descontoDep);
		$descontoDep = str_replace(",",".",$descontoDep);
		
		// Passa a quantidade de dependentes para variasvel.
		$numDependentes = ($_POST['numDependentes'] ? $_POST['numDependentes'] : 0);

		// Valor da Pensão
		$valorPensao = ($_POST['valorPensao'] ? $_POST['valorPensao'] : 0);
		$valorPensao = str_replace(".","",$valorPensao);
		$valorPensao = str_replace(",",".",$valorPensao);

		// Pega a porcentagem da pensão.
		$referenciaPensao = $_POST['porcPensao'];

		// Pega a quantidade de dias trabalhados em um mês.
		$diasTrabalhados = ($_POST['diasTrabalhado'] ? $_POST['diasTrabalhado'] : 0);

		// Pega a quantidade de faltas durante o mês. 
		$faltas = ($_POST['txtFaltas'] ? $_POST['txtFaltas'] : '');

		// Valor da faltas.	
		$valorFaltas = ($_POST['retencao_Faltas'] ? $_POST['retencao_Faltas'] : 0);
		$valorFaltas = str_replace(".","",$valorFaltas);
		$valorFaltas = str_replace(",",".",$valorFaltas);

		$valorFamilia = $valorMaternidade = $valorAbono = $valorBonus = 0;

		// Pega os demais proventos.
		for($i = 1; $i <= 5; $i++) {

			// Monta o nome do outros Proventos.
			$filtroValor = 'filtroValor'.$i;
			$valorOpcional = 'valorOpcional'.$i;

			if(isset($_POST[$filtroValor])){

				switch($_POST[$filtroValor]) {
					case 'familia':		
						$valorFamilia = ($_POST[$valorOpcional] ? $_POST[$valorOpcional] : 0 );
						$valorFamilia = str_replace(".","",$valorFamilia);
						$valorFamilia = str_replace(",",".",$valorFamilia);
						break;
					case 'martenidade':		
						$valorMaternidade = ($_POST[$valorOpcional] ? $_POST[$valorOpcional] : 0 );
						$valorMaternidade = str_replace(".","",$valorMaternidade);
						$valorMaternidade = str_replace(",",".",$valorMaternidade);
						break;				
					case 'abono':		
						$valorAbono = ($_POST[$valorOpcional] ? $_POST[$valorOpcional] : 0 );
						$valorAbono = str_replace(".","",$valorAbono);
						$valorAbono = str_replace(",",".",$valorAbono);
						break;
					case 'bonus':
						$valorBonus = ($_POST[$valorOpcional] ? $_POST[$valorOpcional] : 0 );
						$valorBonus = str_replace(".","",$valorBonus);
						$valorBonus = str_replace(",",".",$valorBonus);
						break;						
				}
			}
		}
		
		// Pega o salario Bruto
		$valorBruto = $_POST['salario_Bruto'];
		$valorBruto = str_replace(".","",$valorBruto);
		$valorBruto = str_replace(",",".",$valorBruto);
		
		// Instância a classe que manipula os dados do pagamento.
		$pagtoFuncData = new PagamentoFuncionarioData();

		// Realiza a inclusão do pagamento no livro caixa.
		if($_POST['GravarLivroCaixa'] == 'Sim') {
						
			$valorSaida = $valorBruto - ($valorFerias + $valorUmTercoFerias + $valorFeriasVendida + $valorUmTercoFeriasVendida);
			
			$livroCaixaId = $pagtoFuncData->InclusaoNoLivroCaixa($empresaId, $dataPagto, 0, $valorSaida, '', $funcionarioId, 'Pagto. de Salários');
		}
		
		// Realiza a chamada do metodo para realiza a inclusão dos dados de pagamento para o funcionário.
		$pagtoFuncData->PreparaInclusaoPagtoFuncionario($empresaId, $funcionarioId, $dataPagto, $valorBruto, $referenciaINSS, $valorINSS, $referenciaIR, $valorIR, $faixaIR, $referenciaVR, $valorVR, $referenciaVT, $valorVT, $valorLiquido, $valorSalarioFuncionario, $valorSalario, $valorFamilia, $valorMaternidade, $valorAbono, $valorBonus, $valorPensao, $referenciaPensao, $descontoDep, $diasTrabalhados, $faltas, $valorFaltas, $tipoPagto, $numDependentes, null, 0, $feriasId, $valorFerias, $valorUmTercoFerias, $vendaUmTercoFerias, $valorFeriasVendida, $valorUmTercoFeriasVendida, $porcIRFerias, $valorIRFerias, $diasFerias, $liquidoFerias, $dataReferencia, $livroCaixaId);
	}
	
	// Método criado para realizar a inclusão dos dados do pagamento das ferias
	private function GravaPagamentoFerias($empresaId, $funcionarioId) {
				
		//  Variável que recebera o id do livro caixa.
		$livroCaixaId = false;		
		
		// Formata a data de pagamento das férias.
		$dataPagtoFeiras = str_replace('/','-',$_POST['dataPagtoFeiras']);
		$dataPagtoFeiras = date('Y-m-d', strtotime($dataPagtoFeiras));

		// Formata a data inicial das férias.
		$dataInicio = str_replace('/','-',$_POST['dataFeriasInicio']);
		$dataInicio = date('Y-m-d', strtotime($dataInicio));
		
		// Formata a data final das férias.
		$dataFim = str_replace('/','-',$_POST['dataFeriasFim']);
		$dataFim = date('Y-m-d', strtotime($dataFim));
		
		// Passa para a variável se o funcionário vendeu 1/3 das férias. 	
		$vendaUmTercoFerias = ($_POST['vendaFerias'] ? $_POST['vendaFerias'] : '');
		
		// Pega o dias que o funcionario ira tirar.
		$diasFerias = $_POST['diasFerias'];
		
		// Pega o período do abono.
		if(isset($_POST['dataFeriasAbonoInicio']) && !empty($_POST['dataFeriasAbonoInicio'])) {
			$dataFeriasAbonoInicio = str_replace('/','-',$_POST['dataFeriasAbonoInicio']);
			$dataFeriasAbonoInicio = date('Y-m-d', strtotime($dataFeriasAbonoInicio));
		} else {
			$dataFeriasAbonoInicio = null;
		}
			
		if(isset($_POST['dataFeriasAbonoFim']) && !empty($_POST['dataFeriasAbonoFim']) ) {
			$dataFeriasAbonoFim = str_replace('/','-',$_POST['dataFeriasAbonoFim']);
			$dataFeriasAbonoFim = date('Y-m-d', strtotime($dataFeriasAbonoFim));
		} else {
			$dataFeriasAbonoFim = null;
		}
		
		// Pega o valor do sálario do funcionário
		$salarioFuncionario = ($_POST['salario_Funcionario'] ? $_POST['salario_Funcionario'] : 0);	
		$salarioFuncionario = str_replace(".","",$salarioFuncionario);
		$salarioFuncionario = str_replace(",",".",$salarioFuncionario);
		
		// Pega o valor das férias a ser pago para o funcionário referente ao primeiro mês.
		$valorFeriasMes1 = ($_POST['valorFeriasMes1'] ? $_POST['valorFeriasMes1'] : 0);
		$valorFeriasMes1 = str_replace(".","",$valorFeriasMes1);
		$valorFeriasMes1 = str_replace(",",".",$valorFeriasMes1);
		
		// Pega o valor das férias a ser pago para o funcionário referente ao segundo mês.
		$valorFeriasMes2 = ($_POST['valorFeriasMes2'] ? $_POST['valorFeriasMes2'] : 0);
		$valorFeriasMes2 = str_replace(".","",$valorFeriasMes2);
		$valorFeriasMes2 = str_replace(",",".",$valorFeriasMes2);
		
		// Pega o valor das férias a ser pago para o funcionário.
		$valorFerias = ($_POST['valorFerias'] ? $_POST['valorFerias'] : 0);
		$valorFerias = str_replace(".","",$valorFerias);
		$valorFerias = str_replace(",",".",$valorFerias);
		
		// Pega o valor de 1/3 das férias a ser pago para o funcionário.
		$valorUmTercoFerias = ($_POST['valorUmTercoFerias'] ? $_POST['valorUmTercoFerias'] : 0);
		$valorUmTercoFerias = str_replace(".","",$valorUmTercoFerias);
		$valorUmTercoFerias = str_replace(",",".",$valorUmTercoFerias);
		
		// Pega o valor do abono pecuniário das férias a ser pago para o funcionário.
		$valorferiasVendida = ($_POST['valorAbonoPecuniario'] ? $_POST['valorAbonoPecuniario'] : 0);
		$valorferiasVendida = str_replace(".","",$valorferiasVendida);
		$valorferiasVendida = str_replace(",",".",$valorferiasVendida);		
		
		// Pega o valor de 1/3 do abono a ser pago para o funcionário.
		$valorUmTercoFeriasVendida = ($_POST['abonoPecuniarioUmTerco'] ? $_POST['abonoPecuniarioUmTerco'] : 0);
		$valorUmTercoFeriasVendida = str_replace(".","",$valorUmTercoFeriasVendida);
		$valorUmTercoFeriasVendida = str_replace(",",".",$valorUmTercoFeriasVendida);
		
		// Pega a porcentagem do INSS.
		$referenciaINSS = $_POST['porcInss'];

		// Valor de retenção do INSS.
		$valorINSS = ($_POST['retencao_INSS'] ? $_POST['retencao_INSS'] : 0);
		$valorINSS = str_replace(".","",$valorINSS);
		$valorINSS = str_replace(",",".",$valorINSS);
		
		// Pega a porcentagem do INSS.
		$referenciaSecundarioINSS = $_POST['porcInssSecundario'];

		// Valor de retenção do INSS.
		$valorSecundarioINSS = ($_POST['retencaoSecundarioINSS'] ? $_POST['retencaoSecundarioINSS'] : 0);
		$valorSecundarioINSS = str_replace(".","",$valorSecundarioINSS);
		$valorSecundarioINSS = str_replace(",",".",$valorSecundarioINSS);	
			
		// Valor da Pensão
		$valorPensao = ($_POST['valorPensao'] ? $_POST['valorPensao'] : 0);
		$valorPensao = str_replace(".","",$valorPensao);
		$valorPensao = str_replace(",",".",$valorPensao);

		// Pega a porcentagem da pensão.
		$referenciaPensao = $_POST['porcPensao'];
		
		
		// Pega a porcentagem do IR
		$referenciaIR = $_POST['porcIR'];

		// Volor de retenção do IR
		$valorIR = ($_POST['retencao_IR'] ? $_POST['retencao_IR'] : 0);
		$valorIR = str_replace(".","",$valorIR);
		$valorIR = str_replace(",",".",$valorIR);

		// Pega a faxa do IR 
		$faixaIR = $_POST['faixaIR'];	
		
		// Valor a ser pago para os dependentes.
		$descontoDep = ($_POST['descontoDep'] ? $_POST['descontoDep'] : 0);
		$descontoDep = str_replace(".","",$descontoDep);
		$descontoDep = str_replace(",",".",$descontoDep);
		
		// Passa a quantidade de dependentes para variasvel.
		$numDependentes = ($_POST['numDependentes'] ? $_POST['numDependentes'] : 0);

		// Pega o valor liquido a ser pago para o funcionário.
		$valorLiquido = ($_POST['valor_Liquido'] ? $_POST['valor_Liquido'] : 0);
		$valorLiquido = str_replace(".","",$valorLiquido);
		$valorLiquido = str_replace(",",".",$valorLiquido);

		// Instância a classe que manipula os dados do pagamento.
		$pagtoFuncData = new PagamentoFeriasData();
		
		// Realiza a inclusão do pagamento no livro caixa.
		if($_POST['GravarLivroCaixa'] == 'Sim') {
			
			$valorSaida = $valorFerias + $valorUmTercoFerias + $valorferiasVendida + $valorUmTercoFeriasVendida;
				
			$livroCaixaId = $pagtoFuncData->InclusaoNoLivroCaixa($empresaId, $dataPagtoFeiras, 0, $valorSaida, '', $funcionarioId, 'Pagto. de Salários');
		}
		
		// Realiza a chamada do metodo para realiza a inclusão dos dados de pagamento para o funcionário.
		$pagtoFuncData->PreparaInclusaoPagtoFerias($empresaId, $funcionarioId, $dataPagtoFeiras, $dataInicio, $dataFim, $diasFerias, $salarioFuncionario, $valorFeriasMes1, $valorFeriasMes2, $valorFerias, $vendaUmTercoFerias, $valorUmTercoFerias, $referenciaINSS, $valorINSS, $referenciaIR, $valorIR, $faixaIR, $valorLiquido, $valorferiasVendida, $valorUmTercoFeriasVendida, $dataFeriasAbonoInicio, $dataFeriasAbonoFim, $descontoDep, $numDependentes, $referenciaSecundarioINSS, $valorSecundarioINSS, $valorPensao, $referenciaPensao, $livroCaixaId);

	}
	
	// Método criado para realizar a inclusão dos dados do decimo terceiro.
	private function GravaPagamentoDecimo($empresaId, $funcionarioId, $dataPagto, $dataReferencia, $tipoPagto) {
		
		//  Variável que recebera o id do livro caixa.
		$livroCaixaId = false;
		
		// Pega a informação se o decimo será parcelado ou não, ou sejá, o decimo pode ser parcelado em ate duas vezes.
		if($_POST['decimoTerceiroParcela'] == 0) {
			$parcelaDecimo = 'unica';
		} elseif($_POST['decimoTerceiroParcela'] == 1) {
			$parcelaDecimo = 'primeira';
		} elseif($_POST['decimoTerceiroParcela'] == 2) {
			$parcelaDecimo = 'segunda';
		}
		
		// Pega o valor do sálario do funcionário
		$valorSalarioFuncionario = ($_POST['salario_Funcionario'] ? $_POST['salario_Funcionario'] : 0);	
		$valorSalarioFuncionario = str_replace(".","",$valorSalarioFuncionario);
		$valorSalarioFuncionario = str_replace(",",".",$valorSalarioFuncionario);			
		
		// Pega o valor do salario do funcionario.
		$valorSalario = ($_POST['valor_Salario'] ? $_POST['valor_Salario'] : 0);
		$valorSalario = str_replace(".","",$valorSalario);
		$valorSalario = str_replace(",",".",$valorSalario);

		// Pega a porcentagem do INSS.
		$referenciaINSS = $_POST['porcInss'];

		// Valor de retenção do INSS.
		$valorINSS = ($_POST['retencao_INSS'] ? $_POST['retencao_INSS'] : 0);
		$valorINSS = str_replace(".","",$valorINSS);
		$valorINSS = str_replace(",",".",$valorINSS);

		// Pega a porcentagem do IR
		$referenciaIR = $_POST['porcIR'];

		// Volor de retenção do IR
		$valorIR = ($_POST['retencao_IR'] ? $_POST['retencao_IR'] : 0);
		$valorIR = str_replace(".","",$valorIR);
		$valorIR = str_replace(",",".",$valorIR);

		// Pega a faxa do IR 
		$faixaIR = $_POST['faixaIR'];	

		// Valor liquido
		$valorLiquido = ($_POST['valor_Liquido'] ? $_POST['valor_Liquido'] : 0);
		$valorLiquido = str_replace(".","",$valorLiquido);
		$valorLiquido = str_replace(",",".",$valorLiquido);

		// Valor a ser pago para os dependentes.
		$descontoDep = ($_POST['descontoDep'] ? $_POST['descontoDep'] : 0);
		$descontoDep = str_replace(".","",$descontoDep);
		$descontoDep = str_replace(",",".",$descontoDep);
		
		// Passa a quantidade de dependentes para variasvel.
		$numDependentes = ($_POST['numDependentes'] ? $_POST['numDependentes'] : 0);
				
		// Pega os dias trabalhado.
		$mesesTrabalhado = $_POST['txtDecimoMesesTrabalhado'];
				
		// Pega o salario Bruto
		$valorBruto = $valorSalario;

		// Instância a classe que manipula os dados do pagamento.
		$pagtoFuncData = new PagamentoFuncionarioData();
		
		// Realiza a inclusão do pagamento no livro caixa.
		if($_POST['GravarLivroCaixa'] == 'Sim') {
			$livroCaixaId = $pagtoFuncData->InclusaoNoLivroCaixa($empresaId, $dataPagto, 0, $valorBruto, '', $funcionarioId, 'Pagto. de Salários');
		}		
		
		// Realiza a chamada do metodo para realiza a inclusão dos dados de pagamento para o funcionário.
		$pagtoFuncData->PreparaInclusaoPagtoFuncionario($empresaId, $funcionarioId, $dataPagto, $valorBruto, $referenciaINSS, $valorINSS, $referenciaIR, $valorIR, $faixaIR, 0, 0, 0, 0, $valorLiquido, $valorSalarioFuncionario, $valorSalario, 0, 0, 0, 0, 0, 0, $descontoDep, 0, 0, 0, $tipoPagto, $numeroDependentes, $parcelaDecimo, $mesesTrabalhado, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, $dataReferencia, $livroCaixaId);
		

	}	
	
}

// Chamada para executar a classe DadosPagamentoFuncionario.
$DadosPagamentoFuncionario = new DadosPagamentoFuncionario();



