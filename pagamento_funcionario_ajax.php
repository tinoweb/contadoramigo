<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 13/07/2017
 */

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// inicia a sessão
session_start();

// Realiza a requizição dos arquivo que realiza a conexão com o banco. 
require_once('conect.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados do funcionário.
require_once('Model/DadosFuncionarios/DadosFuncionariosData.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento do funcionário.
require_once('Model/PagamentoFuncionario/PagamentoFuncionarioData.php');

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento do funcionário.
require_once('Model/PagamentoFerias/PagamentoFeriasData.php');

// Realiza a requisição do arquivo que retorna o objeto com os Lista de porcentagem do inss.
require_once('Model/TabelaINSS/TabelaINSSData.php');

// Realiza a requisição do arquivo que retorna o objeto com os Lista de porcentagem e valores do IR.
require_once('Model/TabelaIR/TabelaIRData.php');


// Classe criada para realiza o calculo do pagamento do funcioário.
class RealizaCalculoFolhaPagamento {
	
	// Define os atributos que receberam os valores
	private $DataAdmissao = '';
	private $DiasTrabalhado = 0;
	private $SalarioFuncionario = 0;
	private $Salario = 0;
	private $FeriasId =	null;
	private $DiasFerias = 0;
	private $ValorFerias = 0;
	private $ValorUmTercoFerias	= 0;
	private $VendaUmTercoFerias = 'N';
	private $ValorFeriasVendida = 0;
	private $ValorUmTercoFeriasVendida = 0;
	private $ValorINSSFerias = 0;
	private $AliquotaIRFerias =	0;
	private $ValorIRFerias = 0;
	private $SalarioBruto = 0;
	private $TotalVencimentos = 0;
	private $ValorIR = 0;
	private $AliquotaIR = 0;
	private $Faixa = 0; 	
	private $ValorINSS = 0;
	private $PorcentagemInss = 0;
	private $ValorSecundarioINSS = 0;
	private $PorcSecundarioInss = 0;
	private $NumDependentes = 0;
	private $DescontoDep = 0;
	private $ValorPensao = 0;
	private $PorcentagemPensao = 0;
	private $ValorValetran = 0;
	private $ValeTransportePorc = 0;
	private $ValorRefeicao = 0;
	private $ValeRefeicaoPorc = 0;
	private $ValorFaltas = 0;
	private $ValorLiquido = 0;
	private $AdiantamentoDecimoTerceiro = 0;
	private $ValorLiquidoFerias = 0; 
	private $SomaValoresFerias = 0;
	private $ValorFeriasMes1 = 0;
	private $ValorFeriasMes2 = 0;	
	private $ValorFeriasVendidaMes1 = 0;
	private $ValorFeriasVendidaMes2 = 0;
	private $Abono = 0;
	private $Bonus = 0;
    private $Familia = 0;
    private $Martenidade = 0;
	private $ProventosTotal = 0;
	
	private $Teste = '';
	
	// Método privado para pega os valore para o calculo do INSS. 
	private function PegaValoresINSS($ano, $salarioBruto, $returnArray=false){

		// Define as variáveis.
		$valorINSS = $valorINSSAux = 0;
		$porcentagemInss = $porcentagemInss = 0;
				
		// Instância a classe responsavel por pegar os dados do INSS.
		$tabelaINSS = new TabelaINSSData();
		
		$dadosInss = $tabelaINSS->PegaINSSPorAno($ano);
			
		// Percorre a lista de dados para montar a lista de input com os dados do INSS.
		foreach($dadosInss as $val) {

			// Variável auxiliar para pegar o ultimo cálculo do INSS.
			$porcentagemInssAux = $val->getPorcentagem();
			$valorINSSAux = (($val->getValor() * $porcentagemInssAux) / 100);
			$valorINSSAux = number_format($valorINSSAux, 2, '.', '');
						
			// Verifica se o salário esta dentro da tabela e pega o valor e a porcentagem.
			if($val->getValor() > $salarioBruto && !$porcentagemInss) {
				$porcentagemInss = $val->getPorcentagem();
				$valorINSS = (($salarioBruto * $porcentagemInss) / 100);
				$valorINSS = number_format($valorINSS, 2, '.', '');
			}
		}
	
		// verifica se o retorn sera feito pelo array
		if($returnArray) {
			// Verifica se o salário esta dentro da tabela.	
			if($porcentagemInss) {
				return array('valorINSS'=>$valorINSS, 'porcentagemInss'=>$porcentagemInss);
			} else {
				return array('valorINSS'=>$valorINSSAux, 'porcentagemInss'=>$porcentagemInssAux);
			}
		} else {
			// Verifica se o salário esta dentro da tabela.	
			if($porcentagemInss) {
				// Pega o valor do INSS.
				$this->ValorINSS = $valorINSS; 
				// Pega a porcentagem do INSS.
				$this->PorcentagemInss = $porcentagemInss;
			} else {
				// Pega o valor do INSS.
				$this->ValorINSS = $valorINSSAux;
				// Pega a porcentagem do INSS.
				$this->PorcentagemInss = $porcentagemInssAux;
			}
		}
	}	
	
	// Método privado para pega os valore para o calculo do IR. 
	private function PegaValoresIR($ano, $salarioBruto){
	
		$aliquotaIR = 0; 
		$descontoIR = 0;
		$faixa = 0;
		
		// Instância a classe responsavel por pegar os dados do IR.
		$tabelaIR = new TabelaIRData();
		
		// Chama o método para pegar os dados do IR.
		$dadosIR = $tabelaIR->PegaIRPorAno($ano);
		
		// calcula alíquota e desconto do IR com base no ValorBruto para cálculo da pensão
		if ($salarioBruto <= $dadosIR->getValorBruto1()) {
			$aliquotaIR = $dadosIR->getAliquota1(); 
			$descontoIR = $dadosIR->getDesconto1();
			$faixa = 1;
		}
		if ($salarioBruto > $dadosIR->getValorBruto1() && $salarioBruto <= $dadosIR->getValorBruto2()){
			$aliquotaIR = $dadosIR->getAliquota2(); 
			$descontoIR = $dadosIR->getDesconto2();
			$faixa = 2;
		}
		if ($salarioBruto > $dadosIR->getValorBruto2() && $salarioBruto <= $dadosIR->getValorBruto3()){ 
			$aliquotaIR = $dadosIR->getAliquota3(); 
			$descontoIR = $dadosIR->getDesconto3();
			$faixa = 3;
		}

		if ($salarioBruto > $dadosIR->getValorBruto3() && $salarioBruto <= $dadosIR->getValorBruto4()){
			$aliquotaIR = $dadosIR->getAliquota4(); 
			$descontoIR = $dadosIR->getDesconto4();
			$faixa = 4;
		}	
		if ($salarioBruto > $dadosIR->getValorBruto4()){ 
			$aliquotaIR = $dadosIR->getAliquota5(); 
			$descontoIR = $dadosIR->getDesconto5();
			$faixa = 5;
		}
		
		// Retorna o valor da aliquota e desconto do IR.
		return array('aliquotaIR'=>$aliquotaIR, 'descontoIR'=>$descontoIR, 'descontoDependentes'=>$dadosIR->getDescontoDependentes(), 'faixa'=>$faixa);
	}
	
	// Método privado para pegar o valor da pensão.
	private function CalculavalordaPensao($salarioBruto, $valorINSS,$porcentagemPensao,$descontoIR,$aliquotaIR,$descontoDependentes) {
			
		// Formula para pegar o valor da Pensão.
		$a = $salarioBruto - $valorINSS; 
		$b = $aliquotaIR/100; 
		$c = $salarioBruto - $valorINSS - $descontoDependentes; 
		$d = $descontoIR; 
		$e = $porcentagemPensao/100; 
		$f = $b * $c; 
		$x = $a - $f + $d; 
		$y = $e*$x; 
		$w = $e*$b; 
		$valorPensao = $y/(1-$w);
			
		return number_format($valorPensao, 2, '.', '');
	}
	
	// Método criado para realizar o calculo da folha de pagamento do funcionário referente ao salário.
	public function RealizaCalculoPagamentoSalario($empresaId, $funcionarioId, $dataPagamento, $proventos, $faltas, $dataReferencia){
		
		// Variável utilizada para retorno. 
		$out = '';
		
		// Faz a chamada do metodo para passa o valor dos proventos para os seus devidos atributos.
		$this->TratarOutrosProventos($proventos);
				
		// Variável que recebe o mês.
		$mes = date('m', strtotime(str_replace('/','-',$dataReferencia)));
		
		// Variável que recebe o ano.
		$ano = date('Y', strtotime(str_replace('/','-',$dataReferencia)));
		
		// Pega o ultimo dia do mês. // realiza a substituição da "barra" para "traço" recupera as horas da string e pega o ultimo dia do mês.  
		$ultimoDia = date('t',strtotime(str_replace('/','-',$dataReferencia)));
						
		// Pega os dados do funcionario.
		$classFuncionarios = new DadosFuncionariosData();
		
		// Chama o método para pegar os dados do funcionário.
		$dadosFuncionarios = $classFuncionarios->PegaDadosFuncionario($funcionarioId);
		
		// Verifica se neste periodo existe férias para este funcionario. 
		$dadosFerias = $this->VerificaPagamentoDeFerias($empresaId, $funcionarioId, $mes, $ano);
		
		// pega a data de admissão do funcionario para verificar a quantidade de dias trabalhado mês.
		$this->DataAdmissao = $dadosFuncionarios->getDataAdmissao();
		
		// Verifica se existe dados das ferias no holerite.		
		if($dadosFerias) {
	
				$diasFeriasAbono = 0;

				// Váriavel que recebe o código referente ao pagamento de ferias.
				$this->FeriasId = $dadosFerias['feriasId'];					

				// Passa o valor do salário funcionario o atributo.
				$this->SalarioFuncionario = $dadosFuncionarios->getValorSalario();

				// Váriavel que recebe a quantidade de dias que o funcionário ira goza.
				$this->DiasFerias = $dadosFerias['diasFerias'];

				// Váriavel que recebe o valor das ferias.
				$this->ValorFerias = $dadosFerias['valorFerias'];

				// Váriavel que recebe o valor de 1/3 das ferias.
				$this->ValorUmTercoFerias = $dadosFerias['valorUmTercoFerias'];

				// Pega o total de vencomento de ferias.
				$TotalVencimentoFerias = $this->ValorFerias + $this->ValorUmTercoFerias;

				// Váriavel que recebe a confirmação se as ferias foi vendida.
				$this->VendaUmTercoFerias = $dadosFerias['vendaUmTercoFerias'];					

				$diasTrabalhado = $ultimoDia - $this->DiasFerias;

				// Verifica se a ferias foi vendida.
				if($this->VendaUmTercoFerias == 'S') {

					// Váriavel que recebe o valor da ferias vendida.
					$this->ValorFeriasVendida = $dadosFerias['valorFeriasVendida'];

					// Váriavel que recebe o valor da venda de 1/3 das ferias. 
					$this->ValorUmTercoFeriasVendida = $dadosFerias['valorUmTercoFeriasVendida'];						

					// Pega a quantidade de dias trabalhado durante o mês.
					$diasTrabalhado = $diasTrabalhado - 10;
				}

				// Váriavel que recebe a porcentagem do IRRF referente a ferias.
				$this->AliquotaIRFerias = $dadosFerias['referenciaIR'];

				// Váriavel que recebe o valor IRRF referente a ferias.
				$this->ValorIRFerias = $dadosFerias['valorIR'];

				// pega os dias trabalados. 
				$this->DiasTrabalhado = $diasTrabalhado;		
			
				// Pega a quantidade de dias trabalhado.
				$this->Salario = ($this->SalarioFuncionario / $ultimoDia) * $this->DiasTrabalhado;
			
				// Verifica a quantidade de faltas durante o mês.
				if($faltas > 0 && $this->Salario > 0) {
					$this->ValorFaltas = ($this->SalarioFuncionario / $ultimoDia) * $faltas;
				}

				// Pega soma Ferias + Abono.
				$totalFeriaAbono = $TotalVencimentoFerias + $this->ValorFeriasVendida + $this->ValorUmTercoFeriasVendida;
			
				// Atributo que recebe o Total de vencimento.
				$this->SalarioBruto = $this->TotalVencimentos = $this->Salario + $totalFeriaAbono + $this->ProventosTotal;
						
				// Pega o Salário + Proventos + Férias + 1/3 Férias.
				$ValorBrutoCalculoINSS = $this->Salario + $TotalVencimentoFerias + $this->Martenidade;
			
				// Passa o numero de dependentes do funcionario para a variável.
				$this->NumDependentes = $dadosFuncionarios->getDependentes();

				// Pega os dados do INSS e passa para o atributo ValorINSS e PorcentagemINSS. 
				$this->PegaValoresINSS($ano, $ValorBrutoCalculoINSS);
			
				/** Para realizar o calculo do IR o valor do das Ferias Mais o INSS deve ser retirado para o calculo IR base **/					
				// Pega o Salário + Proventos.
				$ValorBrutoCalculoIR = $this->Salario + $this->ProventosTotal;
			
				$valorINSSParcial = 0;
			
				// verifica se realiza o calculo para pegar o valor parcial do inss refente ao salário.
				if($ValorBrutoCalculoIR > 0){
					$valorINSSParcial = (($ValorBrutoCalculoIR * $this->PorcentagemInss) / 100);
					$valorINSSParcial = number_format($valorINSSParcial, 2, '.', '');
				}
			
				// chama o método para calcula alíquota e desconto do IR com base no valor bruto para cálculo da pensão.
				$dadosIr1 = $this->PegaValoresIR($ano, $ValorBrutoCalculoIR);

				// Valores que serão usados para o cálculo do valor da pensão caso o funcionario page. 
				$descIRPensao = $dadosIr1['descontoIR'];
				$aliqIRPensao = $dadosIr1['aliquotaIR'];
				$descontoDep = $dadosIr1['descontoDependentes'];

				// Pega o valor descontdo de depedente e multiplica pela a quantidade de dependentes.
				$this->DescontoDep = $descontoDep * $this->NumDependentes;

				// Verifica se o funcuinário paga pensão.
				if($dadosFuncionarios->getPensao()) {
					// Passa o valor da porcentagem para variável
					$this->PorcentagemPensao = $dadosFuncionarios->getPercPensao();
					// Chama o método para pegar o valor dapensão.
					$this->ValorPensao = $this->CalculavalordaPensao($ValorBrutoCalculoINSS, $this->ValorINSS, $this->PorcentagemPensao, $descIRPensao,$aliqIRPensao,$this->DescontoDep);
				}
			
				// Pega o valor parcial do salário de férias. 
				$pegaINSSParcialFerias = $this->ValorINSS - $valorINSSParcial;
			
				// Pega o liquido de férias.
				$this->ValorLiquidoFerias = $totalFeriaAbono - ($this->ValorPensao + $pegaINSSParcialFerias + $this->ValorIRFerias);
		
				// Verifica se sera feito o calculo para o IR. 
				if($ValorBrutoCalculoIR > 0){
				
					// Define a base de calculo do IR.
					$baseCalculoIR = $ValorBrutoCalculoIR - $valorINSSParcial - $this->DescontoDep - $this->ValorPensao - $this->ValorFaltas;

					// Pega os dados do IR.
					$dadosIr2 = $this->PegaValoresIR($ano, $baseCalculoIR);

					$descontoIR = $dadosIr2['descontoIR'];
					$this->AliquotaIR = $dadosIr2['aliquotaIR'];
					$this->Faixa = $dadosIr2['faixa'];

					//Calcula o valor do IR incluindo a pensao
					$valorIR = (($baseCalculoIR * $this->AliquotaIR)/100 ) - $descontoIR;
					
					// Se o valor for menor que 10 ele define que o ir e isento.
					if($valorIR < 10) {
						$valorIR = 0;
					}
					
					$this->ValorIR = number_format($valorIR, 2, '.', '');
					
				} else {
					$this->AliquotaIR = 0;
					$this->Faixa = 1;
					$this->ValorIR = 0;
				}
				
				// subtrai do salário o valor das faltas.
				$valorSalarioFalta = $this->Salario - $this->ValorFaltas;

				// Verifica se sera necessario realiza o calculo do vale Transporte.
				if($dadosFuncionarios->getValeTransporte() > 0 && $valorSalarioFalta > 0){
					$this->ValeTransportePorc = 6;
					$valorValetran = (($valorSalarioFalta * $this->ValeTransportePorc) / 100);
					$this->ValorValetran = number_format($valorValetran, 2, '.', '');
				}

				// Verifica se será cobrado o vale refeição.
				if($dadosFuncionarios->getValeRefeicao() != 0 && $dadosFuncionarios->getValeRefeicaoPorc() > 0 && $valorSalarioFalta > 0) {	
					$valorRefeicao = (($valorSalarioFalta * $dadosFuncionarios->getValeRefeicaoPorc())/100);	
					$this->ValorRefeicao = number_format($valorRefeicao, 2, '.', '');
				}

				//porcentagem do vale refeição.	
				$this->ValeRefeicaoPorc = $dadosFuncionarios->getValeRefeicaoPorc();
							
				// pega o valor liquido.
				$valorLiquido = ($this->SalarioBruto - $this->ValorINSS - $this->ValorIR - $this->ValorPensao - $this->ValorValetran - $this->ValorRefeicao - $this->ValorFaltas - $this->ValorIRFerias - $this->ValorLiquidoFerias);
	
				$this->ValorLiquido = number_format($valorLiquido, 2, '.', '');

		} // Se não ter férias registrada realiza o calculo para o salario. 
		else {
			
			// Passa o valor do funcionario para a variável
			$this->SalarioFuncionario = $this->Salario = $dadosFuncionarios->getValorSalario();

			// Pega a quantidade de dias trabalhados durante o mês de adminssão.
			$diasTrabalhado = $this->PegaDiasTrabalhadoNoMesAdminssao($dataReferencia, $this->DataAdmissao);

			// Verifica a quantidade de dias trabalhados durante o mes.
			if($diasTrabalhado) {
				$this->Salario  = ($this->SalarioFuncionario / $ultimoDia) * $diasTrabalhado;
			} else {
				// Pega o valor do ultimo dia do mês;
				$diasTrabalhado = $ultimoDia;
			}
			
			// Pega os dias trabalhado.
			$this->DiasTrabalhado = $diasTrabalhado;

			// Verifica a quantidade de faltas durante o mes e.
			if($faltas) {
				$this->ValorFaltas = ($this->SalarioFuncionario / $ultimoDia) * $faltas;
			}		

			// Pega o valor do salario bruto.
			$this->TotalVencimentos = $this->SalarioBruto = $this->Salario + $this->ProventosTotal;
			
			$this->Teste = $this->ProventosTotal;

			// Pega o Salário + Proventos.
			$ValorBrutoCalculoINSS = $this->Salario + $this->Martenidade;			
						
			// Passa o numero de dependentes do funcionario para a variável.
			$this->NumDependentes = $dadosFuncionarios->getDependentes();

			// Pega os dados do INSS e passa para o atributo ValorINSS e PorcentagemINSS. 
			$this->PegaValoresINSS($ano, $ValorBrutoCalculoINSS);

			// chama o método para calcula alíquota e desconto do IR com base no valor bruto para cálculo da pensão.
			$dadosIr1 = $this->PegaValoresIR($ano, $this->SalarioBruto);

			// Valores que serão usados para o cálculo do valor da pensão caso o funcionario page. 
			$descIRPensao = $dadosIr1['descontoIR'];
			$aliqIRPensao = $dadosIr1['aliquotaIR'];
			$descontoDep = $dadosIr1['descontoDependentes'];

			// Pega o valor descontdo de depedente e multiplica pela a quantidade de dependentes.
			$this->DescontoDep = $descontoDep * $this->NumDependentes;

			// Verifica o funcuinario paga pensão.
			if($dadosFuncionarios->getPensao()) {
				// Passa o valor da porcentagem para variável
				$this->PorcentagemPensao = $dadosFuncionarios->getPercPensao();
				// Chama o método para pegar o valor dapensão.
				$this->ValorPensao = $this->CalculavalordaPensao($this->SalarioBruto, $this->ValorINSS, $this->PorcentagemPensao, $descIRPensao,$aliqIRPensao,$this->DescontoDep);
			}

			// Define a base de calculo do IR.
			$baseCalculoIR = $this->SalarioBruto - $this->ValorINSS - $this->DescontoDep - $this->ValorPensao - $this->ValorFaltas;

			// Pega os dados do IR.
			$dadosIr2 = $this->PegaValoresIR($ano, $baseCalculoIR);

			$descontoIR = $dadosIr2['descontoIR'];
			$this->AliquotaIR = $dadosIr2['aliquotaIR'];
			$this->Faixa = $dadosIr2['faixa'];

			//Calcula o valor do IR incluindo a pensao
			$valorIR = (($baseCalculoIR * $this->AliquotaIR)/100 ) - $descontoIR;

			// Se o valor for menor que 10 ele define que o ir e isento.
			if($valorIR < 10) {
				$valorIR = 0;
			}			
						
			$this->ValorIR = number_format($valorIR, 2, '.', '');

			// Verifica se sera necessario realiza o calculo do vale Transporte.
			if($dadosFuncionarios->getValeTransporte()){
				$this->ValeTransportePorc = 6;
				$valorValetran = (($this->SalarioBruto * $this->ValeTransportePorc) / 100);
				$this->ValorValetran = number_format($valorValetran, 2, '.', '');
			}

			$teste = $dadosFuncionarios->getValeRefeicao();
			
			// Verifica se será cobrado o vale refeição.
			if($dadosFuncionarios->getValeRefeicao() !=0 && $dadosFuncionarios->getValeRefeicaoPorc() > 0) {	
				$valorRefeicao = (($this->SalarioBruto * $dadosFuncionarios->getValeRefeicaoPorc())/100);	
				$this->ValorRefeicao = number_format($valorRefeicao, 2, '.', '');
			}

			//porcentagem do vale refeição.	
			$this->ValeRefeicaoPorc = $dadosFuncionarios->getValeRefeicaoPorc();
			
			// pega o valor liquido.
			$valorLiquido = ($this->SalarioBruto - $this->ValorINSS - $this->ValorIR - $this->ValorPensao - $this->ValorValetran - $this->ValorRefeicao - $this->ValorFaltas);
			$this->ValorLiquido = number_format($valorLiquido, 2, '.', '');
		}
		
		// Chama o método criado para retorna os dados como json 		
		$this->MetodoDeRetornoValoresJSOM();
	}

	// Método criado para realizar o calculo da folha de pagamento do funcionário referente ao salário.
	public function RealizaCalculoPagamentoFerias($empresaId, $funcionarioId, $diasFerias, $dataPagtoFeiras, $vendaUmTercoFerias, $dataInicio, $dataFim, $dataAbonoInicio, $dataAbonoFim){
		
		// Variável utilizada para retorno. 
		$out = '';
		
		// Variável que recebe o ano.
		$ano = date('Y', strtotime(str_replace('/','-',$dataPagtoFeiras)));

		// Pega os dados do funcionario.
		$classFuncionarios = new DadosFuncionariosData();
		
		// Chama o método para pegar os dados do funcionário.
		$dadosFuncionarios = $classFuncionarios->PegaDadosFuncionario($funcionarioId);
		
		// Passa o valor do funcionario para a variável
		$this->SalarioFuncionario = $dadosFuncionarios->getValorSalario();
		
		// pega a data de admissão do funcionario para verificar a quantidade de dias trabalhado mês.
		$this->DataAdmissao = $dadosFuncionarios->getDataAdmissao();
	
		// pega os dias de ferais e passa para o atributo.
		$this->DiasFerias = $diasFerias;
		
		// Pega os Valores da Férias.
		$this->PegaValorFerias($dataInicio, $dataFim);
						
		// Soma as ferias mais 1/3.
		$this->SomaValoresFerias = $this->ValorFerias + $this->ValorUmTercoFerias;	

		// Verifica se o funcionario ira vender 1/3 das férias. 
		if($vendaUmTercoFerias == 'S') {
			
			// Passa para o atributo que as ferias foi vendida.
			$this->VendaUmTercoFerias = $vendaUmTercoFerias;
			
			$this->PegaValorFerias($dataAbonoInicio, $dataAbonoFim, 'abono');
		}		

		// Pega o bruto de ferias ou total de vencimento.
		$this->SalarioBruto = $this->TotalVencimentos += $this->SomaValoresFerias;
		
		// Passa o numero de dependentes do funcionario para a variável.
		$this->NumDependentes = $dadosFuncionarios->getDependentes();
		
		// Pega os dados do INSS. 
		$this->PegaValoresINSS($ano, $this->SomaValoresFerias);

		// chama o método para calcula alíquota e desconto do IR com base no valor bruto para cálculo da pensão.
		$dadosIr1 = $this->PegaValoresIR($ano, $this->SomaValoresFerias);
		
		// Valores que serão usados para o cálculo do valor da pensão caso o funcionario page.
		$descIRPensao = $dadosIr1['descontoIR'];
		$aliqIRPensao = $dadosIr1['aliquotaIR'];
		$descontoDep = $dadosIr1['descontoDependentes'];

		// Pega o valor descontdo de depedente e multiplica pela a quantidade de dependentes.
		$this->DescontoDep = $descontoDep * $this->NumDependentes;

		// Verifica o funcuinario paga pensão.
		if($dadosFuncionarios->getPensao()) {
			// Passa o valor da porcentagem para variável
			$this->PorcentagemPensao = $dadosFuncionarios->getPercPensao();
			// Chama o método para pegar o valor dapensão.
			$this->ValorPensao = $this->CalculavalordaPensao($this->SalarioBruto, $this->ValorINSS, $this->PorcentagemPensao, $descIRPensao,$aliqIRPensao,$this->DescontoDep);
		}		

		// Define a base de calculo do IR usa a soma das feris para não adicionar o abono.
		$baseCalculoIR = $this->SomaValoresFerias - $this->ValorINSS - $this->DescontoDep - $this->ValorPensao;
		
		// Pega os dados do IR.
		$dadosIr2 = $this->PegaValoresIR($ano, $baseCalculoIR);
		
 		$descontoIR = $dadosIr2['descontoIR'];
		$this->AliquotaIR = $dadosIr2['aliquotaIR'];
		$this->Faixa = $dadosIr2['faixa'];
		
		// Calcula o valor do IR incluindo a pensao
		$valorIR = (($baseCalculoIR * $this->AliquotaIR)/100 ) - $descontoIR;
		
		// Se o valor for menor que 10 ele define que o ir e isento.
		if($valorIR < 10) {
			$valorIR = 0;
		}
		
		$this->ValorIR = number_format($valorIR, 2, '.', '');
				
		// Soma ao total de vencimento o abono de férias.
		$this->TotalVencimentos += $this->ValorFeriasVendida + $this->ValorUmTercoFeriasVendida;	
		
		// O Valor do INSS secundario e utilizado para o desconto quando o mês e quebrado. 
		if($this->ValorSecundarioINSS > 0) {
			// Pega o valor liquido a ser pago.
			$valorLiquido = ($this->TotalVencimentos - $this->ValorSecundarioINSS - $this->ValorIR - $this->ValorPensao);
			$this->ValorLiquido = number_format($valorLiquido, 2, '.', '');
		} else {
			// Pega o valor liquido a ser pago.
			$valorLiquido = ($this->TotalVencimentos - $this->ValorINSS - $this->ValorIR - $this->ValorPensao);
			$this->ValorLiquido = number_format($valorLiquido, 2, '.', '');
		}
			
		// Chama o método criado para retorna os dados como json 		
		$this->MetodoDeRetornoValoresJSOM();
	}	
	
	// Método criado para realizar o calculo da folha de pagamento do funcionário referente ao décimo terceiro.
	public function RealizaCalculoDecimoTerceiro($empresaId, $funcionarioId, $dataPagamento, $decimo, $decimoMesesTrabalhado, $dataReferencia){
		
		// Variável utilizada para retorno. 
		$out = '';
		
		// Variável que recebe o ano.
		$ano = date('Y', strtotime(str_replace('/','-',$dataReferencia)));
						
		// Pega os dados do funcionario.
		$classFuncionarios = new DadosFuncionariosData();
		
		// Chama o método para pegar os dados do funcionário.
		$dadosFuncionarios = $classFuncionarios->PegaDadosFuncionario($funcionarioId);
		
		// Pega o valor do sálario do funcionário.
		$this->SalarioFuncionario = $this->Salario = $dadosFuncionarios->getValorSalario();
		
		// Pega a data de admissão do funcionario para verificar a quantidade de dias trabalhado mês.
		$this->DataAdmissao = $dadosFuncionarios->getDataAdmissao();
		
		// Formula para pegar o valor do décimo terceiro de acordo com meses trabalhado.
		$this->Salario = ($this->Salario / 12) * $decimoMesesTrabalhado;
		
		// Neste ponto faz a verificação para saber o tipo de pagamento do decimo.
		switch($decimo){
			
			// Ação para a parcela unica do décimo terceiro.		
			case 0:
				
				// Pega o valor do salario bruto.
				$this->SalarioBruto = $this->Salario;
				
				// Passa o numero de dependentes do funcionario para a variável.
				$this->NumDependentes = $dadosFuncionarios->getDependentes();

				// Pega os dados do INSS. 
				$dadosInss = $this->PegaValoresINSS($ano, $this->SalarioBruto);

				// chama o método para calcula alíquota e desconto do IR com base no valor bruto para cálculo da pensão.
				$dadosIr1 = $this->PegaValoresIR($ano, $this->SalarioBruto);

				// Valores que serão usados para o cálculo do valor da pensão caso o funcionario page.
				$descIRPensao = $dadosIr1['descontoIR'];
				$aliqIRPensao = $dadosIr1['aliquotaIR'];
				$descontoDep = $dadosIr1['descontoDependentes'];

				// Pega o valor descontdo de depedente e multiplica pela a quantidade de dependentes.
				$this->DescontoDep = $descontoDep * $this->NumDependentes;
				
				// Verifica o funcuinario paga pensão.
				if($dadosFuncionarios->getPensao()) {
					// Passa o valor da porcentagem para variável
					$this->PorcentagemPensao = $dadosFuncionarios->getPercPensao();
					// Chama o método para pegar o valor dapensão.
					$this->ValorPensao = $this->CalculavalordaPensao($this->SalarioBruto, $this->ValorINSS, $this->PorcentagemPensao, $descIRPensao,$aliqIRPensao,$this->DescontoDep);
				}

				// Define a base de calculo do IR.
				$baseCalculoIR = $this->SalarioBruto - $this->ValorINSS - $this->DescontoDep - $this->ValorPensao;

				// Pega os dados do IR.
				$dadosIr2 = $this->PegaValoresIR($ano, $baseCalculoIR);

				$descontoIR = $dadosIr2['descontoIR'];
				$this->AliquotaIR = $dadosIr2['aliquotaIR'];
				$this->Faixa = $dadosIr2['faixa'];

				//Calcula o valor do IR incluindo a pensao
				$valorIR = (($baseCalculoIR * $this->AliquotaIR)/100) - $descontoIR;
				
				// Se o valor for menor que 10 ele define que o ir e isento.
				if($valorIR < 10) {
					$valorIR = 0;
				}	
				
				$this->ValorIR = number_format($valorIR, 2, '.', '');

				// pega o valor liquido.
				$valorLiquido = ($this->SalarioBruto - $this->ValorINSS - $this->ValorIR);
				$this->ValorLiquido = number_format($valorLiquido, 2, '.', '');				
	
				// pega o valor liquido.
				$valorLiquido = ($this->SalarioBruto - $this->ValorINSS - $this->ValorIR - $this->ValorPensao);
				$this->ValorLiquido = number_format($valorLiquido, 2, '.', '');
				
				break;
			// Ação para a primeira parcela do décimo terceiro.
			// Será repassado para o funcionário metade do valor do 13° Salario sem realizar o calculo do para o IRRF ou INSS.
			case 1:
				
				// Pega o valor do salario bruto.
				$this->ValorLiquido = $this->SalarioBruto = $this->Salario = ($this->Salario  / 2);

				break;
				
			// Ação para a segunda parcela do décimo terceiro.	
			// Será repassado para o funcionário metade do valor do 13° Salario com a retenção do IRRF ou INSS.
			case 2:
				
				// Pega o valor do salario bruto.
				$this->SalarioBruto = $this->Salario;
				
				// Por ser a segunda parcela ele divide o valor do sálario bruto para por dois e utilizara ela para subtrair para volor liquido.  
				$this->AdiantamentoDecimoTerceiro = ($this->Salario  / 2);				
				
				// Passa o numero de dependentes do funcionario para a variável.
				$this->NumDependentes = $dadosFuncionarios->getDependentes();

				// Pega os dados do INSS. 
				$dadosInss = $this->PegaValoresINSS($ano, $this->Salario);

				// chama o método para calcula alíquota e desconto do IR com base no valor bruto para cálculo da pensão.
				$dadosIr1 = $this->PegaValoresIR($ano, $this->SalarioBruto);

				// Valores que serão usados para o cálculo do valor da pensão caso o funcionario page.
				$descIRPensao = $dadosIr1['descontoIR'];
				$aliqIRPensao = $dadosIr1['aliquotaIR'];				
				$descontoDep = $dadosIr1['descontoDependentes'];

				// Pega o valor descontdo de depedente e multiplica pela a quantidade de dependentes.
				$this->DescontoDep = $descontoDep * $this->NumDependentes;
				
				// Verifica o funcuinario paga pensão.
				if($dadosFuncionarios->getPensao()) {
					// Passa o valor da porcentagem para variável
					$this->PorcentagemPensao = $dadosFuncionarios->getPercPensao();
					// Chama o método para pegar o valor dapensão.
					$this->ValorPensao = $this->CalculavalordaPensao($this->SalarioBruto, $this->ValorINSS, $this->PorcentagemPensao, $descIRPensao,$aliqIRPensao,$this->DescontoDep);
				}

				// Define a base de calculo do IR.
				$baseCalculoIR = $this->SalarioBruto - $this->ValorINSS - $this->DescontoDep - $this->ValorPensao;

				// Define a base de calculo do IR.
				$baseCalculoIR = $this->SalarioBruto - $this->ValorINSS - $this->DescontoDep;

				// Pega os dados do IR.
				$dadosIr2 = $this->PegaValoresIR($ano, $baseCalculoIR);

				$descontoIR = $dadosIr2['descontoIR'];
				$this->AliquotaIR = $dadosIr2['aliquotaIR'];
				$this->Faixa = $dadosIr2['faixa'];

				//Calcula o valor do IR incluindo a pensao
				$valorIR = (($baseCalculoIR * $this->AliquotaIR)/100) - $descontoIR;
				
				// Se o valor for menor que 10 ele define que o ir e isento.
				if($valorIR < 10) {
					$valorIR = 0;
				}
				
				$this->ValorIR = number_format($valorIR, 2, '.', '');

				// pega o valor liquido.
				$valorLiquido = ($this->SalarioBruto - $this->ValorINSS - $this->ValorIR - $this->ValorPensao - $this->AdiantamentoDecimoTerceiro);
				$this->ValorLiquido = number_format($valorLiquido, 2, '.', '');
				
				break;
		}
		
		// pega o total de vencimento.
		$this->TotalVencimentos = $this->SalarioBruto;
				
		// Chama o método criado para retorna os dados como json 		
		$this->MetodoDeRetornoValoresJSOM();
	}
	
	// Método criado para verifica os dias trabalhados no mês de admissão.
	function PegaDiasTrabalhadoNoMesAdminssao($dataReferencia, $dataAdmissao) {
		
		$out = false;
		
		// Dados da data referente ao pagamento.
		$anoPagamento = date('Y', strtotime(str_replace('/','-',$dataReferencia))); // Pega o ano de pagamento.
		$mesPagamento = date('m', strtotime(str_replace('/','-',$dataReferencia))); // Pega o mês de pagamento.
		$ultimoDiaPagamento = date('t',strtotime(str_replace('/','-',$dataReferencia))); //pega o ultimo dia do mês
		
		// Dados da data de admissão do funcionario.
		$anoAdmisso = date('Y', strtotime(str_replace('/','-',$dataAdmissao))); // Pega o ano de pagamento.
		$mesAdmisso = date('m', strtotime(str_replace('/','-',$dataAdmissao))); // Pega o mês de pagamento.
		$diaAdmisso = date('d', strtotime(str_replace('/','-',$dataAdmissao))); // Pega o mês de pagamento.
		
		// Verifica se o ano de admissão e o mesmo ano do pagamento.
		if($anoAdmisso == $anoPagamento) {
			
			// Verifica se e o mesmo mês.
			if($mesAdmisso == $mesPagamento){
				$out = 1 + $ultimoDiaPagamento - $diaAdmisso;
			}
		}
	
		// Retorna a quantidade de dias de das trabalhado no mês de admissão.
		return $out;
	}
	
	// Método criado para verificar periodo de ferias
	function VerificaPagamentoDeFerias($empresaId, $funcionarioId, $mes, $ano) {
		
		$out = false;
		
		// Instancia da classe responsavel por manipular os dados.
		$pagamentoFeria = new PagamentoFeriasData();
		
		// Pega os dados de ferias.
		$dadosFerias = $pagamentoFeria->PegaDadosFeriasPorPeriodo($empresaId, $funcionarioId, $mes, $ano);
		
		// Verifica se existe dados.
		if($dadosFerias) {
			
			$mesInicio = date('m', strtotime(str_replace('/','-',$dadosFerias->getDataInicio()))); 
			$mesFim = date('m', strtotime(str_replace('/','-',$dadosFerias->getDataFim())));
			
			// Verifica se o período de ferias e quebrado ou não, ou seja, se o funcionário ira tirar as ferias dentro do mês.
			if($mesInicio == $mesFim) {
				
				// passa os dados de ferias 
				$out = array('feriasId'=>$dadosFerias->getFeriasId()
							,'diasFerias'=>$dadosFerias->getDiasFerias()
							,'valorFerias'=>$dadosFerias->getValorFerias()
							,'salarioFuncionario'=>$dadosFerias->getSalarioFuncionario()
							,'valorUmTercoFerias'=>$dadosFerias->getValorUmTercoFerias()
							,'vendaUmTercoFerias'=>$dadosFerias->getVendaUmTercoFerias()
							,'valorFeriasVendida'=>$dadosFerias->getValorFeriasVendida()
							,'valorUmTercoFeriasVendida'=>$dadosFerias->getValorUmTercoFeriasVendida()
							,'dataFeriasAbonoInicio'=>$dadosFerias->getDataFeriasAbonoInicio()
							,'dataFeriasAbonoFim'=>$dadosFerias->getDataFeriasAbonoFim()
							,'referenciaINSS'=>$dadosFerias->getReferenciaINSS()
							,'valorINSS'=>$dadosFerias->getValorINSS()
							,'referenciaIR'=>$dadosFerias->getReferenciaIR()
							,'valorIR'=>$dadosFerias->getValorIR()
							,'faixaIR'=>$dadosFerias->getFaixaIR()
							,'numeroDependentes'=>$dadosFerias->getNumeroDependentes()
							,'valorDescontoDependente'=>$dadosFerias->getDescontoDepValor()
							,'valorliquido'=>$dadosFerias->getValorLiquido()
							,'feriasMes'=>'normal');				
				
			} // Neste caso e mês quebrado e é verificado se e referente ao primeiro mês de férias.
			elseif($mesInicio == $mes) {
				
				// Variavel que pega a data do ultimo dia do mês.  
				$dataAux = date('Y-m-t',strtotime(str_replace('/','-',$dadosFerias->getDataInicio())));
				
				// Verifica a quantidade de dias que o funcionário tirou durante o mês. 
				$diasAux = $this->DiferencaData($dataAux, $dadosFerias->getDataInicio());
						
				// Pega o valor de férias e de 1/3 das férias proporcional aos dias que o funcionario gozou.
				$valorFerias = $dadosFerias->getValorFeriasMes1();
				$valorUmTercoFerias = $dadosFerias->getValorFeriasMes1() / 3;
				
				// Pega o liquido de ferias.
				$liquidoFerias = ($dadosFerias->getValorLiquido() / $dadosFerias->getDiasFerias()) * $diasAux;
				
				// Verifica se o funcionario realizou a venda das ferias.
				if($dadosFerias->getVendaUmTercoFerias() == 'S') {
					
					// Pega as data para verificação.
					$dataInicialFerias = new DateTime($dadosFerias->getDataInicio());
					$dataFinalAbono = new DateTime($dadosFerias->getDataFeriasAbonoFim());	
					
					// Verifica se a data do abono e anterior aos dias gozados pelo funcionário.
					if($dataFinalAbono < $dataInicialFerias) {
						$vendaUmTercoFerias = $dadosFerias->getVendaUmTercoFerias();
						$vendaUmTercoFerias = $dadosFerias->getValorFeriasVendida();
						$valorUmTercoFeriasVendida = $dadosFerias->getValorUmTercoFeriasVendida();
					} else {
						$vendaUmTercoFerias = "N";
						$vendaUmTercoFerias = $valorUmTercoFeriasVendida = 0;
					}
				}
				
				// passa os dados de ferias 
				$out = array('feriasId'=>$dadosFerias->getFeriasId()
							,'diasFerias'=>$diasAux
							,'valorFerias'=>$valorFerias
							,'valorUmTercoFerias'=>$valorUmTercoFerias
							,'vendaUmTercoFerias'=>$vendaUmTercoFerias
							,'valorFeriasVendida'=>$vendaUmTercoFerias
							,'valorUmTercoFeriasVendida'=>$valorUmTercoFeriasVendida
							,'referenciaINSS'=>0
							,'valorINSS'=>0
							,'referenciaIR'=>$dadosFerias->getReferenciaIR()
							,'valorIR'=>$dadosFerias->getValorIR()
							,'valorliquido'=>$liquidoFerias
							,'feriasMes'=>'parcialUm');	

				
			} // Neste caso e mês quebrado e é verificado se e referente ao segundo mês de férias.
			elseif($mesFim == $mes) {
				
				// Variavel que pega a data do primeiro dia do mês.  
				$dataAux = date('Y-m-',strtotime(str_replace('/','-',$dadosFerias->getDataFim()))).'01';
				
				// Verifica a quantidade de dias do mês
				$diasAux = $this->DiferencaData($dadosFerias->getDataFim(), $dataAux);
				
				// Pega o valor de férias e de 1/3 das férias proporcional aos dias que o funcionario gozou.
				$valorFerias = $dadosFerias->getValorFeriasMes2();
				$valorUmTercoFerias = $dadosFerias->getValorFeriasMes2() / 3;
	
				// Pega o liquido de ferias.
				$liquidoFerias = ($dadosFerias->getValorLiquido() / $dadosFerias->getDiasFerias()) * $diasAux;				
				
				// Verifica se o funcionario realizou a venda das ferias.
				if($dadosFerias->getVendaUmTercoFerias() == 'S') {
					
					// Pega as data para verificação.
					$dataFinalFerias = new DateTime($dadosFerias->getDataFim());
					$dataInicioAbono = new DateTime($dadosFerias->getDataFeriasAbonoInicio());	
					
					// Verifica se a data do abono e posterior aos dias gozados ao funcionário.
					if($dataFinalFerias < $dataInicioAbono) {
						$vendaUmTercoFerias = $dadosFerias->getVendaUmTercoFerias();
						$vendaUmTercoFerias = $dadosFerias->getValorFeriasVendida();
						$valorUmTercoFeriasVendida = $dadosFerias->getValorUmTercoFeriasVendida();
					} else {
						$vendaUmTercoFerias = "N";
						$vendaUmTercoFerias = $valorUmTercoFeriasVendida = 0;
					}
				}
				
				// passa os dados de ferias 
				$out = array('feriasId'=>$dadosFerias->getFeriasId()
							,'diasFerias'=>$diasAux
							,'valorFerias'=>$valorFerias
							,'valorUmTercoFerias'=>$valorUmTercoFerias
							,'vendaUmTercoFerias'=>$vendaUmTercoFerias
							,'valorFeriasVendida'=>$vendaUmTercoFerias
							,'valorUmTercoFeriasVendida'=>$valorUmTercoFeriasVendida
							,'referenciaINSS'=>0
							,'valorINSS'=>0
							,'referenciaIR'=>0
							,'valorIR'=>0
							,'valorliquido'=>$liquidoFerias 
							,'feriasMes'=>'parcialDois');
			}
		}
		
		// Retorna os dados das férias.
		return $out;
	}
	
	// Metodo criado para pegar o valor das ferias
	private function PegaValorFerias($dataInicio, $dataFim, $tipoCalculo='ferias') {
		
		// Pega os meses.
		$mesInicio = date('m', strtotime(str_replace('/','-',$dataInicio))); 
		$mesFim = date('m', strtotime(str_replace('/','-',$dataFim)));
		
		switch($tipoCalculo) {
			case 'ferias':	
				$diasCalculo = $this->DiasFerias;
				break;
			case 'abono':
				$diasCalculo = 10; 
				break;				
		}
			
		// Realiza a verificação se os meses são iguais.
		if($mesInicio == $mesFim){
	
			// Pega o ultimo dia do mês.
			$ultimoDia = date('t', strtotime(str_replace('/','-',$dataInicio))); 
			
			// Realiza o calculo para definir o valor das férias de acordo com os dias que o funcionário ira goza.
			$valor = (($this->SalarioFuncionario) / $ultimoDia) * $diasCalculo;

			// Pega um terço do valor das férias.
			$valorUmTerco = $valor / 3;
	
		} else {
			
			// Define as variaveis como o valor zero. 
			$valor1 = $valor2 = $valorUmTerco1 = $valorUmTerco2 = 0;
			
			// Pega a data Final do mes.
			$dataUltimoDiaMes = date('Y-m-t', strtotime(str_replace('/','-',$dataInicio))); 
			
			// Verifica a quantidade de dias referente as férias durante o mês.
			$QuantidadeDias1 = $this->DiferencaData($dataUltimoDiaMes, $dataInicio);
					
			// Pega a quantidade de dias para fazer o calculo do segundo mês.
			$QuantidadeDias2 = $diasCalculo - $QuantidadeDias1;
			
			// Pega o ultimo dia do mês.
			$ultimoDiaMes1 = date('t', strtotime(str_replace('/','-',$dataInicio))); 
			
			// Realiza o calculo para definir o valor das férias ou do Abono de acordo com os dias que o funcionário ira goza ou trabalha.
			$valor1 = (($this->SalarioFuncionario) / $ultimoDiaMes1) * $QuantidadeDias1;
			
			// Pega um terço do valor das férias ou do valor do abono.
			$valorUmTerco1 = $valor1 / 3;
			
			// Pega o ultimo dia do mês.
			$ultimoDiaMes2 = date('t', strtotime(str_replace('/','-',$mesFim))); 
			
			// Realiza o calculo para definir o valor das férias de acordo com os dias que o funcionário ira goza.
			$valor2 = (($this->SalarioFuncionario) / $ultimoDiaMes2) * $QuantidadeDias2;
			
			// Pega um terço do valor das férias.
			$valorUmTerco2 = $valor2 / 3;
			
			// Bega o inss de ferias.
			if($tipoCalculo == 'ferias') {
				
				// Pega o valor do primeiro mês de férias para ser apresentado no holerite
				$this->ValorFeriasMes1 = $valor1;	
				
				// Pega o valor do segundo mês de férias para ser apresentado no holerite
				$this->ValorFeriasMes2 = $valor2;
				
				/**** Verifica o valor do segundo mês INSS****/
				$anoMes1 = date('Y', strtotime(str_replace('/','-',$dataInicio)));
				
				// Pega o bruto do ferias
				$salarioBruto1 = $valor1 + $valorUmTerco1;

				// Pega o valor do INSS
				$INSSMes1 = $this->PegaValoresINSS($anoMes1, $salarioBruto1, true);
				
				/**** Verifica o valor do segundo mês INSS****/
				$anoMes2 = date('Y', strtotime(str_replace('/','-',$dataFim))); 
				
				// Pega o bruto do ferias
				$salarioBruto2 = $valor2 + $valorUmTerco2;
				
				// Pega o valor do INSS
				$INSSMes2 = $this->PegaValoresINSS($anoMes2, $salarioBruto2, true);
				
				if($INSSMes1['porcentagemInss'] == $INSSMes2['porcentagemInss']) {
					$this->PorcSecundarioInss = $INSSMes1['porcentagemInss'];
				}
				$this->ValorSecundarioINSS = $INSSMes1['valorINSS'] + $INSSMes2['valorINSS'];				
			}
			
			$valor = $valor1 + $valor2;
			$valorUmTerco = $valorUmTerco1 + $valorUmTerco2;			
		}
		
		switch($tipoCalculo) {
			// Passa o valor das ferias e valor de 1/3 ferias para os atributos.	
			case 'ferias':	
				$this->ValorFerias = $valor;
				$this->ValorUmTercoFerias = $valorUmTerco;
				break;
			// Passa o valor das ferias e valor de 1/3 ferias para os atributos.	
			case 'abono':
				$this->ValorFeriasVendida = $valor;
				$this->ValorUmTercoFeriasVendida = $valorUmTerco;
				break;				
		}
	}
		
	// Método utilizado para verificar a diferença entre datas.
	private function DiferencaData($dataFinal,$dataInicial){
		$dataFinal = strtotime(str_replace('/','-',$dataFinal));
		$dataInicial = strtotime(str_replace('/','-',$dataInicial));
		$diferenca = $dataFinal - $dataInicial;
		$dias = (int)floor( $diferenca / (60 * 60 * 24)) + 1;
		
		// retorna numero de dias.
		return $dias;
	}
	
	// Método criado para tratar outros Proventos.
	private function TratarOutrosProventos($proventos){
		
		// Transforma o objeto json em uma lista array.
		$arrayProventos	= json_decode($proventos);
		
		// Percorre a lista de proventos. 
		foreach($arrayProventos as $key => $val) {
			
			// Passa o valor dos provento para seus devidos atributos.
			switch($key){
				case 'abono':
					$this->Abono = $val;
					break;
				case 'bonus':
					$this->Bonus = $val;
					break;
				case 'familia':
					$this->Familia = $val;
					break;
				case 'martenidade':
					$this->Martenidade = $val;
					break;	
			}
			
			// Pega o total dos proventos.
			$this->ProventosTotal = $this->ProventosTotal + $val;
		}
	}
	
	// Metodo criado para definir o retorno dos valores.
	private function MetodoDeRetornoValoresJSOM(){
		
		// Cria um array com os dados.
		$out = array(
			'dataAdmissao'=>date('d/m/Y', strtotime($this->DataAdmissao)),
			'diasTrabalhado'=>$this->DiasTrabalhado,
			'salarioFuncionario'=>number_format($this->SalarioFuncionario, 2, ',', '.'),
			'salario'=>number_format($this->Salario, 2, ',', '.'),
			'feriasId'=>$this->FeriasId,
			'diasFerias'=>$this->DiasFerias,
			'valorFerias'=>number_format($this->ValorFerias, 2, ',', '.'),
			'valorUmTercoFerias'=>number_format($this->ValorUmTercoFerias, 2, ',', '.'),
			'vendaUmTercoFerias'=>$this->VendaUmTercoFerias,
			'valorFeriasVendida'=>number_format($this->ValorFeriasVendida, 2, ',', '.'),
			'valorUmTercoFeriasVendida'=>number_format($this->ValorUmTercoFeriasVendida, 2, ',', '.'),			
			'aliquotaIRFerias'=>$this->AliquotaIRFerias,
			'valorIRFerias'=>number_format($this->ValorIRFerias, 2, ',', '.'),
			'salarioBruto'=>number_format($this->SalarioBruto, 2, ',', '.'),
			'totalVencimentos'=>number_format($this->TotalVencimentos, 2, ',', '.'),
			'valorIR'=>number_format($this->ValorIR, 2, ',', '.'),
			'aliquotaIR'=>$this->AliquotaIR,
			'faixaIR'=>$this->Faixa,
			'valorInss'=>number_format($this->ValorINSS, 2, ',', '.'),
			'porcentagemInss'=>$this->PorcentagemInss,
			'valorSecundarioINSS'=>number_format($this->ValorSecundarioINSS, 2, ',', '.'),
			'porcSecundarioInss'=>$this->PorcSecundarioInss,			
			'numDependentes'=>$this->NumDependentes,
			'descontoDep'=>number_format($this->DescontoDep, 2, ',', '.'),
			'valorPensao'=>number_format($this->ValorPensao, 2, ',', '.'),
			'porcPensao'=>$this->PorcentagemPensao,
			'valeTransporte'=>number_format($this->ValorValetran, 2, ',', '.'),
			'valeTransportePorc'=>$this->ValeTransportePorc,
			'valeRefeicao'=>number_format($this->ValorRefeicao, 2, ',', '.'),
			'valeRefeicaoPorc'=>$this->ValeRefeicaoPorc,
			'valorFaltas'=>number_format($this->ValorFaltas, 2, ',', '.'),
			'adiantamentoDecimoTerceiro'=>number_format($this->AdiantamentoDecimoTerceiro, 2, ',', '.'),
			'liquidoFerias'=>number_format($this->ValorLiquidoFerias, 2, ',', '.'),
			'valorLiquido'=>number_format($this->ValorLiquido, 2, ',', '.'),
			'valorFeriasMes1'=>number_format($this->ValorFeriasMes1, 2, ',', '.'),
			'valorFeriasMes2'=>number_format($this->ValorFeriasMes2, 2, ',', '.'),	
			'teste'=>$this->Teste
		);

		// Retorna um json com os dados da folha de pagamento.
		echo json_encode($out);
	}
}

// Verifica se o 
if(isset($_POST['method']) && !empty($_POST['method'])) {

	// Pega os dados do pagamento.
	$metodo = $_POST['method'];
	$empresaId = (isset($_SESSION['id_empresaSecao']) ? $_SESSION["id_empresaSecao"] : 0 );
	$funcionarioId =  (isset($_POST['funcionarioId']) ? $_POST['funcionarioId'] : 0);
	$dataPagamento =  (isset($_POST['dataPagamento']) ? $_POST['dataPagamento'] : '');
	$proventos = (isset($_POST['proventos']) ? $_POST['proventos'] : 0);
	$faltas = (isset($_POST['faltas']) ? $_POST['faltas'] : 0);
	$tipoPagto = (isset($_POST['tipoPagto']) ? $_POST['tipoPagto'] : '');
	$decimoMesesTrabalhado = (isset($_POST['decimoMesesTrabalhado']) ? $_POST['decimoMesesTrabalhado'] : 0);
    $decimo = (isset($_POST['decimo']) ? $_POST['decimo'] : '');
    $diasFerias = (isset($_POST['diasFerias']) ?$_POST['diasFerias'] : 0);
    $vendaUmtercoFerias = (isset($_POST['vendaUmtercoFerias']) ? $_POST['vendaUmtercoFerias'] : '');
	$dataPagtoFeiras = (isset($_POST['dataPagtoFeiras']) ? $_POST['dataPagtoFeiras']: '');
	$dataInicio = (isset($_POST['dataFeriasInicio']) ? $_POST['dataFeriasInicio']: '');
	$dataFim = (isset($_POST['dataFeriasFim']) ? $_POST['dataFeriasFim']: '');
	$dataAbonoInicio = (isset($_POST['dataAbonoInicio']) ? $_POST['dataAbonoInicio']: '');
	$dataAbonoFim = (isset($_POST['dataAbonoFim']) ? $_POST['dataAbonoFim']: '');
	$dataReferencia = (isset($_POST['dataReferencia']) ? '01/'.$_POST['dataReferencia'] : '');
	
	// Instância da classe para manipula o cálculo do pagamento.
	$classCalc = new RealizaCalculoFolhaPagamento();
	
	if($tipoPagto == 'salario') {
		// Chama o método para realizar o calculo do salário.
		$classCalc->RealizaCalculoPagamentoSalario($empresaId, $funcionarioId, $dataPagamento, $proventos, $faltas, $dataReferencia);
	} elseif($tipoPagto == 'ferias') {
		// Chama o método para realizar o calculo das ferias.
		$classCalc->RealizaCalculoPagamentoFerias($empresaId, $funcionarioId, $diasFerias, $dataPagtoFeiras, $vendaUmtercoFerias, $dataInicio, $dataFim, $dataAbonoInicio, $dataAbonoFim);
	} elseif($tipoPagto == 'decimoTerceiro'){
		// Chama o método para realizar o calculo do décimo terceiro.
		$classCalc->RealizaCalculoDecimoTerceiro($empresaId, $funcionarioId, $dataPagamento, $decimo, $decimoMesesTrabalhado, $dataReferencia);
	}
}

?>