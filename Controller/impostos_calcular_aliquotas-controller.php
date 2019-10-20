<?php
/**
 * classe criada para para auxiliar no controle da págino da aliquota de impostos. 
 * data: 26/12/2017
 */

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// faz a requisição do arquivo de controle de dados do banco.
require_once('DataBaseMySQL/ImpostosAliquotas.php');

class ImpostosCalcularAliquotas {
			
	// Método criado para verificar se a empresa apresentara o valor da fona de salario.
	public function VerificaFatorR($empresaId) {
		
		// Instancia da classe da liquota de impostos.
		$impostosAliquotas = new ImpostosAliquotas();
		
		// Pega os cnae com o fatorR.
		$FatorR = $impostosAliquotas->PegaCNAEComFatorR($empresaId);

		// Verifica se existe dados.
		if($FatorR) {
			
			// Retorna true por existir dados.
			return true;
		}

		// Retorna falso por não existir dados.
		return false;
	}
		
	// Método criado para manipular a apresentação da liquota.
	public function ajaxAliquotasImpostos() {
		
		$faturamentoEmp = isset($_POST['faturamentoEmp']) && !empty($_POST['faturamentoEmp']) ? str_replace(',','.',str_replace('.','',$_POST['faturamentoEmp'])) : 0;
		$folhaSalario = isset($_POST['folhaSalario']) && !empty($_POST['folhaSalario']) ? str_replace(',','.',str_replace('.','',$_POST['folhaSalario'])) : 0;
		$faturamentoEfetivo = isset($_POST['faturamentoEfetivo']) && !empty($_POST['faturamentoEfetivo']) ? str_replace(',','.',str_replace('.','',$_POST['faturamentoEfetivo'])) : 0;

		if($_POST['aberturaStatus'] == 'menor'){
			$faturamentoEmp = ($faturamentoEmp/$_POST['mesAnoMenor'])*12;
		}
		
		// Pega o código da empresa armazenado na sessão.
		$empresaId = $_SESSION['id_empresaSecao'];
		
		$corpoAtividade = false;		
	
		// Instancia da classe da liquota de impostos.
		$impostosAliquotas = new ImpostosAliquotas();		

		// Pega os cnae da empresa.
		$dadosCNAE = $impostosAliquotas->PegaCNAE($empresaId);		
		
		// Verifica se houve retorno do cnae.
		if($dadosCNAE) {
			
			// Percorre o cnae da empresa.
			foreach($dadosCNAE as $val) {

				if($val['Fator_R'] == 1) {

					// Pega a porcentagem da folha de sálario referente ao faturamento da empresa.
					// O calculo do fator R é sobre o faturamento efetivo
					$porc28 = (($folhaSalario * 100) / $faturamentoEfetivo);

					$porc28 = round($porc28);
					
					// Verefica se a porcentagem para definir o anexo.
					if($porc28 >= 28) {
						
						// Defini anexo 3.
						// A faixa da aliquota é encontrada com base no faturamento 12 meses. 
						// Para empresas com menos de 1 ano é o faturamento projetado
						$corpoAtividade[] =  $this->PegaLinhaDadosAtividadeTributos('III', $faturamentoEmp, $val['denominacao'], $val['cnae']);
						
					} else {
						
						// Defini anexo 5.
						$corpoAtividade[] = $this->PegaLinhaDadosAtividadeTributos('V', $faturamentoEmp, $val['denominacao'], $val['cnae']);						
					}
				} else {
					$corpoAtividade[] = $this->PegaLinhaDadosAtividadeTributos($val['anexo'], $faturamentoEmp, $val['denominacao'], $val['cnae']);
				}
			}
		}
				
		// retorna um json com o html com os tributos e nome da atividade.
		echo json_encode($corpoAtividade);
		
	}
	
	private function MontaCampoDeAtividades($atividade, $impostos) {
		
		$tags = '<div style="clear:both; height:5px"></div>'
				.'<div class="tituloAzul">'.$atividade.'</div>'						 
				.'<div style="margin:10px 0 20px 0; display:block">'
				.$impostos
				.'</div>'; 
		
		return $tags;
	}
	
	// Método criado para verificar o anexo.
	private function PegaLinhaDadosAtividadeTributos($anexo, $receitaBruta12Meses, $atividade, $cnae) {		
		$tabelaAnexo = false;
		
		// Instancia da classe da liquota de impostos.
		$impostosAliquotas = new ImpostosAliquotas();		
		
		// Verifica qual o anexo selecionado.
		switch($anexo) {

			case 'I':
				$tabelaAnexo = $impostosAliquotas->TabelaAnexo1($receitaBruta12Meses);
				break;
			case 'II':
				$tabelaAnexo = $impostosAliquotas->TabelaAnexo2($receitaBruta12Meses);
				break;
			case 'III':
				$tabelaAnexo = $impostosAliquotas->TabelaAnexo3($receitaBruta12Meses);
				break;
			case 'IV':
				$tabelaAnexo = $impostosAliquotas->TabelaAnexo4($receitaBruta12Meses);
				break;
			case 'V':
				$tabelaAnexo = $impostosAliquotas->TabelaAnexo5($receitaBruta12Meses);
				break;
		}
		
		// Pega o valor da alíquota efetiva.
		$aliquotaNominal = $tabelaAnexo['Aliquota'];
		
		// Pega o valor a ser deduzido.
		$valorDeduzir = $tabelaAnexo['ValorDeduzir'];
		
		// Pega o valor da nota físcal.
		$valorNota = isset($_POST['valorNota']) && !empty($_POST['valorNota']) ? str_replace(',','.',str_replace('.','',$_POST['valorNota'])) : 0;
		
		// Realiza o calculo para pegar o valor da aliquota efetiva.
		$aliquotaEfetiva = (((($receitaBruta12Meses * $aliquotaNominal)/100) - $valorDeduzir)/$receitaBruta12Meses)*100;
						
		// Pega a faixa de tributação.
		$tributos = $this->PegaReparticaoTributos($tabelaAnexo['Faixa'], $anexo);
		
		// Verifica se a alíquota efetiva é superior a 14,92537% da faixa 5 do anexo III;
		if($aliquotaEfetiva > 14.92537 && $anexo == 'III'  && $tributos['Faixa'] == '5ªFaixa') {
			
			// Pega a linha com os tributos.
			$impostos = $this->montaLinhaTributoAnexoIIIFaixa5($aliquotaEfetiva, $cnae, $valorNota);
						
		} // Verifica se a alíquota efetiva é superior a 12,5% da faixa 5 do anexo IV;
		else if($aliquotaEfetiva > 12.5 && $anexo == 'IV'  && $tributos['Faixa'] == '5ªFaixa') {
				
			// Pega a linha com os tributos.
			$impostos = $this->montaLinhaTributoAnexoIVFaixa5($aliquotaEfetiva, $cnae, $valorNota);
		} // Anexo 
		else if($anexo == 'x') {
			$impostos = 'Para as alíquotas desta atividade entre em contato com o nosso help desk.';
		} else {
			
			// Pega a linha com os tributos.
			$impostos = $this->montaLinhaTributo($tributos, $aliquotaEfetiva, $cnae, $valorNota);
		}	
		
		// Retorna o html com os impostos e o nome da atividade. 
		return $this->MontaCampoDeAtividades($atividade, $impostos);
	}
	
	// Metodo criado para pegar a repartição dos tributos.
	private function  PegaReparticaoTributos($Faixa, $anexo) {
		
		$faixaTributos = false;
		
		// Instancia da classe da liquota de impostos.
		$impostosAliquotas = new ImpostosAliquotas();		
		
		// Verifica qual o anexo selecionado.
		switch($anexo) {

			case 'I':
				$faixaTributos = $impostosAliquotas->PegaReparticaoTributosAnexo1($Faixa);
				break;
			case 'II':
				$faixaTributos = $impostosAliquotas->PegaReparticaoTributosAnexo2($Faixa);
				break;
			case 'III':
				$faixaTributos = $impostosAliquotas->PegaReparticaoTributosAnexo3($Faixa);
				break;
			case 'IV':
				$faixaTributos = $impostosAliquotas->PegaReparticaoTributosAnexo4($Faixa);
				break;
			case 'V':
				$faixaTributos = $impostosAliquotas->PegaReparticaoTributosAnexo5($Faixa);
				break;
		}
		
		return $faixaTributos;		
	}
	
	// Monta a linha de tributos de acordo com o nome e porcentagem.
	private function montaLinhaTributo($tributos, $aliquotaEfetiva, $cnae, $valorNota) {
		
		$out = '';
		
		$totalPorc = 0;
		
		foreach($tributos as $key => $val) {

			switch ($key) {
				case '0':
					break;					
				case 'IRPJ':
					
					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : ''); 
					
					// efetua o calculo para pegar a porcentagem da aliquota efetiva.
					$porcImposto = (($val / 100) * $aliquotaEfetiva);
					
					// Soma as porcentagem
					$totalPorc += $porcImposto;
					
					// aplica a porcentagem na nota.
					$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	
					
					// Formata para duas casas.
					$porcImposto = number_format($porcImposto, 2, ',', '.');
					$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
					
					// Monta o texto com nome e valor do tributo. 	
					$out .= 'IRPJ:  R$ '.$valorNotaAux.' ('.$porcImposto.'%)';

					break;
				case 'CSLL':

					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : ''); 
					
					// efetua o calculo para pegar a porcentagem da aliquota efetiva.
					$porcImposto = (($val / 100) * $aliquotaEfetiva);
					
					// Soma as porcentagem
					$totalPorc += $porcImposto;
					
					// aplica a porcentagem na nota.
					$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	
					
					// Formata para duas casas.
					$porcImposto = number_format($porcImposto, 2, ',', '.');
					$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
					
					// Monta o texto com nome e valor do tributo. 	
					$out .= 'CSLL: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
					break;
				case 'Cofins':
					
					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : '');				
					
					// Se a nota for emitida para exterior define o PIS como 0. 	
					if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
						// Monta o texto com nome e valor do tributo. 	
						$out .= 'Cofins: R$ 0,00 (0%)';	
					} else {
						
						// efetua o calculo para pegar a porcentagem da aliquota efetiva.
						$porcImposto = (($val / 100) * $aliquotaEfetiva);

						// Soma as porcentagem
						$totalPorc += $porcImposto;

						// aplica a porcentagem na nota.
						$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

						// Formata para duas casas.
						$porcImposto = number_format($porcImposto, 2, ',', '.');
						$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
						
						// Monta o texto com nome e valor do tributo. 	
						$out .= 'Cofins: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';	
					}
					
					break;
				case 'PIS_Pasep':
					
					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : ''); 				

					// Se a nota for emitida para exterior define o PIS como 0. 	
					if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
						$out .= 'PIS/Pasep: R$ 0,00 (0%)';
					}else {
						
						// efetua o calculo para pegar a porcentagem da aliquota efetiva.
						$porcImposto = (($val / 100) * $aliquotaEfetiva);

						// Soma as porcentagem
						$totalPorc += $porcImposto;

						// aplica a porcentagem na nota.
						$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

						// Formata para duas casas.
						$porcImposto = number_format($porcImposto, 2, ',', '.');
						$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');	
						
						// Monta o texto com nome e valor do tributo. 	
						$out .= 'PIS/Pasep: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';		
					}
	
					break;
				case 'CPP':
					
					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : ''); 
					
					// efetua o calculo para pegar a porcentagem da aliquota efetiva.
					$porcImposto = (($val / 100) * $aliquotaEfetiva);
					
					// Soma as porcentagem
					$totalPorc += $porcImposto;
					
					// aplica a porcentagem na nota.
					$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	
					
					// Formata para duas casas.
					$porcImposto = number_format($porcImposto, 2, ',', '.');
					$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
					
					// Monta o texto com nome e valor do tributo. 	
					$out .= 'CPP: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
					break;
				case 'ISS':
					
					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : ''); 				
					
										
					if($this->checkCNAETrocaISSParaICMS($cnae)) {
						
						// efetua o calculo para pegar a porcentagem da aliquota efetiva.
						$porcImposto = (($val / 100) * $aliquotaEfetiva);

						// Soma as porcentagem
						$totalPorc += $porcImposto;

						// aplica a porcentagem na nota.
						$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

						// Formata para duas casas.
						$porcImposto = number_format($porcImposto, 2, ',', '.');
						$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');	

						// Monta o texto com nome e valor do tributo. 	
						$out .= 'ICMS: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
					} else {

						// Se a nota for emitida para exterior define o iss como 0. 	
						if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
							$out .= 'ISS: R$ 0,00 (0%)';
						}else {
							
							// efetua o calculo para pegar a porcentagem da aliquota efetiva.
							$porcImposto = (($val / 100) * $aliquotaEfetiva);

							// Soma as porcentagem
							$totalPorc += $porcImposto;

							// aplica a porcentagem na nota.
							$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

							// Formata para duas casas.
							$porcImposto = number_format($porcImposto, 2, ',', '.');
							$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');	
							// Monta o texto com nome e valor do tributo. 	
							$out .= 'ISS: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';		
						}							
					}
					
					break;
				case 'IPI':
					
					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : ''); 
					
					// efetua o calculo para pegar a porcentagem da aliquota efetiva.
					$porcImposto = (($val / 100) * $aliquotaEfetiva);
					
					// Soma as porcentagem
					$totalPorc += $porcImposto;
					
					// aplica a porcentagem na nota.
					$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	
					
					// Formata para duas casas.
					$porcImposto = number_format($porcImposto, 2, ',', '.');
					$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
					
					// Monta o texto com nome e valor do tributo. 	
					$out .= 'IPI: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
					break;
				case 'ICMS':
					
					// inclui o "-"
					$out .= (!empty($out) ? ' - ' : ''); 
					
					// efetua o calculo para pegar a porcentagem da aliquota efetiva.
					$porcImposto = (($val / 100) * $aliquotaEfetiva);
					
					// Soma as porcentagem
					$totalPorc += $porcImposto;
					
					// aplica a porcentagem na nota.
					$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	
					
					// Formata para duas casas.
					$porcImposto = number_format($porcImposto, 2, ',', '.');
					$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
					
					// Monta o texto com nome e valor do tributo. 	
					$out .= 'ICMS: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
					break;
			}			
		}
		
		// Pega o total dos tributos.
		$out .= ' <br/><b> Total: R$ '.number_format($TotalNotaFiscal, 2, ',', '.').' ('.number_format($totalPorc, 2, ',', '.').'%)</b>';
		
		// retorna a linha de tributos. 
		return $out;
	}
		
	// Monta a linha de tributos de acordo com o nome e porcentagem.
	private function montaLinhaTributoAnexoIIIFaixa5($aliquotaEfetiva, $cnae, $valorNota) {
		
		$out = '';
		
		/** Tributação  IRPJ **/

			// efetua o calculo para pegar a porcentagem da aliquota efetiva.
			// Retira 5 referente ao 5% do ISS.
			$porcImposto = ($aliquotaEfetiva - 5) * (6.02 / 100);

			// Soma as porcentagem
			$totalPorc += $porcImposto;

			// aplica a porcentagem na nota.
			$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

			// Formata para duas casas.
			$porcImposto = number_format($porcImposto, 2, ',', '.');
			$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');

			// Monta o texto com nome e valor do tributo. 	
			$out .= 'IRPJ:  R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
		
		/** Fim Tributação IRPJ**/
				
		/** Tributação  CSLL **/
		
			// efetua o calculo para pegar a porcentagem da aliquota efetiva.
			// Retira 5 referente ao 5% do ISS.
			$porcImposto = ($aliquotaEfetiva - 5) * (5.26 / 100);

			// Soma as porcentagem
			$totalPorc += $porcImposto;

			// aplica a porcentagem na nota.
			$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

			// Formata para duas casas.
			$porcImposto = number_format($porcImposto, 2, ',', '.');
			$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');

			// Monta o texto com nome e valor do tributo. 	
			$out .= ' - CSLL: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';

		/** Fim Tributação CSLL**/

		/** Tributação  Cofins **/			
			
			// Se a nota for emitida para exterior define o PIS como 0. 	
			if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - Cofins: R$ 0,00 (0%)';
			} else {
				
				// efetua o calculo para pegar a porcentagem da aliquota efetiva.
				// Retira 5 referente ao 5% do ISS.
				$porcImposto = ($aliquotaEfetiva - 5) * (19.28 / 100);

				// Soma as porcentagem
				$totalPorc += $porcImposto;

				// aplica a porcentagem na nota.
				$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

				// Formata para duas casas.
				$porcImposto = number_format($porcImposto, 2, ',', '.');
				$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
				
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - Cofins: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
			}		

		/** Fim Tributação Cofins**/
				
		/** Tributação  PIS/Pasep **/			

			// Se a nota for emitida para exterior define o PIS como 0. 	
			if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - PIS/Pasep: R$ 0,00 (0%)';
			}else {
				
				// efetua o calculo para pegar a porcentagem da aliquota efetiva.
				// Retira 5 referente ao 5% do ISS.
				$porcImposto = ($aliquotaEfetiva - 5) * (4.18 / 100);

				// Soma as porcentagem
				$totalPorc += $porcImposto;

				// aplica a porcentagem na nota.
				$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

				// Formata para duas casas.
				$porcImposto = number_format($porcImposto, 2, ',', '.');
				$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
				
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - PIS/Pasep: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
			}		
		
		/** Fim Tributação  PIS/Pasep **/
		
		/** Tributação  CPP **/

			// efetua o calculo para pegar a porcentagem da aliquota efetiva.
			// Retira 5 referente ao 5% do ISS.
			$porcImposto = ($aliquotaEfetiva - 5) * (65.26 / 100);

			// Soma as porcentagem
			$totalPorc += $porcImposto;

			// aplica a porcentagem na nota.
			$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

			// Formata para duas casas.
			$porcImposto = number_format($porcImposto, 2, ',', '.');
			$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');

			// Monta o texto com nome e valor do tributo. 	
			$out .= ' - CPP: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
		
		/** Fim Tributação  CPP **/	

		/** Tributação  ISS **/			

			if($this->checkCNAETrocaISSParaICMS($cnae)) {
				
				// efetua o calculo para pegar a porcentagem da aliquota efetiva.
				// Retira 5 referente ao 5% do ISS.
				$porcImposto = 5;

				// Soma as porcentagem
				$totalPorc += $porcImposto;

				// aplica a porcentagem na nota.
				$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

				// Formata para duas casas.
				$porcImposto = number_format($porcImposto, 2, ',', '.');
				$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
				
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - ICMS: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
				
			} else {
				
				// Se a nota for emitida para exterior define o iss como 0. 	
				if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
					$out .= ' - ISS: R$ 0,00 (0%)';
				}else {
					
					// efetua o calculo para pegar a porcentagem da aliquota efetiva.
					// Retira 5 referente ao 5% do ISS.
					$porcImposto = 5;

					// Soma as porcentagem
					$totalPorc += $porcImposto;

					// aplica a porcentagem na nota.
					$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

					// Formata para duas casas.
					$porcImposto = number_format($porcImposto, 2, ',', '.');
					$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
					
					// Monta o texto com nome e valor do tributo. 	
					$out .= ' - ISS: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';		
				}				
			}
		
		/** Fim Tributação  ISS **/			
		
		// Pega o total dos tributos.
		$out .= ' <br/><b> Total: R$ '.number_format($TotalNotaFiscal, 2, ',', '.').' ('.number_format($totalPorc, 2, ',', '.').'%)</b>';
	
		// retorna a linha de tributos. 
		return $out;
	}

	// Monta a linha de tributos de acordo com o nome e porcentagem.
	private function montaLinhaTributoAnexoIVFaixa5($aliquotaEfetiva, $cnae, $valorNota) {
		
		$out = '';

		/** Tributação  IRPJ **/

			// efetua o calculo para pegar a porcentagem da aliquota efetiva.
			// Retira 5 referente ao 5% do ISS.
			$porcImposto = ($aliquotaEfetiva - 5) * (31.33 / 100);

			// Soma as porcentagem
			$totalPorc += $porcImposto;

			// aplica a porcentagem na nota.
			$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

			// Formata para duas casas.
			$porcImposto = number_format($porcImposto, 2, ',', '.');
			$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');

			// Monta o texto com nome e valor do tributo. 	
			$out .= 'IRPJ:  R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
		
		/** Fim Tributação IRPJ**/
				
		/** Tributação  CSLL **/
		
			// efetua o calculo para pegar a porcentagem da aliquota efetiva.
			// Retira 5 referente ao 5% do ISS.
			$porcImposto = ($aliquotaEfetiva - 5) * (32.00 / 100);

			// Soma as porcentagem
			$totalPorc += $porcImposto;

			// aplica a porcentagem na nota.
			$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

			// Formata para duas casas.
			$porcImposto = number_format($porcImposto, 2, ',', '.');
			$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');

			// Monta o texto com nome e valor do tributo. 	
			$out .= ' - CSLL: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';

		/** Fim Tributação CSLL**/

		/** Tributação  Cofins **/			

			// Se a nota for emitida para exterior define o PIS como 0. 	
			if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - Cofins: R$ 0,00 (0%)';
			} else {
				
				// efetua o calculo para pegar a porcentagem da aliquota efetiva.
				// Retira 5 referente ao 5% do ISS.
				$porcImposto = ($aliquotaEfetiva - 5) * (30.13 / 100);

				// Soma as porcentagem
				$totalPorc += $porcImposto;

				// aplica a porcentagem na nota.
				$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

				// Formata para duas casas.
				$porcImposto = number_format($porcImposto, 2, ',', '.');
				$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
				
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - Cofins: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
			}		

		/** Fim Tributação Cofins**/
				
		/** Tributação  PIS/Pasep **/			

			// Se a nota for emitida para exterior define o PIS como 0. 	
			if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - PIS/Pasep: R$ 0,00 (0%)';
			}else {
				
				// efetua o calculo para pegar a porcentagem da aliquota efetiva.
				// Retira 5 referente ao 5% do ISS.
				$porcImposto = ($aliquotaEfetiva - 5) * (6.54 / 100);

				// Soma as porcentagem
				$totalPorc += $porcImposto;

				// aplica a porcentagem na nota.
				$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

				// Formata para duas casas.
				$porcImposto = number_format($porcImposto, 2, ',', '.');
				$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
				
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - PIS/Pasep: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
			}			
		
		/** Fim Tributação  PIS/Pasep **/
		
		/** Tributação  ISS **/			

			if($this->checkCNAETrocaISSParaICMS($cnae)) {
				
				// efetua o calculo para pegar a porcentagem da aliquota efetiva.
				// Retira 5 referente ao 5% do ISS.
				$porcImposto = 5;

				// Soma as porcentagem
				$totalPorc += $porcImposto;

				// aplica a porcentagem na nota.
				$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

				// Formata para duas casas.
				$porcImposto = number_format($porcImposto, 2, ',', '.');
				$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
				
				// Monta o texto com nome e valor do tributo. 	
				$out .= ' - ICMS: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';
				
			} else {
				
				// Se a nota for emitida para exterior define o iss como 0. 	
				if(isset($_POST['emissaoNota']) && $_POST['emissaoNota'] == 'exterior') {
					$out .= ' - ISS: R$ 0,00 (0%)';
				}else {
					
					// efetua o calculo para pegar a porcentagem da aliquota efetiva.
					// Retira 5 referente ao 5% do ISS.
					$porcImposto = 5;

					// Soma as porcentagem
					$totalPorc += $porcImposto;

					// aplica a porcentagem na nota.
					$TotalNotaFiscal += $valorNotaAux = ($valorNota * $porcImposto)/100;	

					// Formata para duas casas.
					$porcImposto = number_format($porcImposto, 2, ',', '.');
					$valorNotaAux = number_format($valorNotaAux, 2, ',', '.');
					
					// Monta o texto com nome e valor do tributo. 	
					$out .= ' - ISS: R$ '.$valorNotaAux.' ('.$porcImposto.'%)';		
				}				
			}
		/** Fim Tributação  ISS **/		
		
		
		// Pega o total dos tributos.
		$out .= ' <br/> <b>Total: R$ '.number_format($TotalNotaFiscal, 2, ',', '.').' ('.number_format($totalPorc, 2, ',', '.').'%)</b>';
	
		// retorna a linha de tributos. 
		return $out;
	}
	
	//Método criado para verificar se deve ser feita a troca do ISS para o ICMS.
	private function checkCNAETrocaISSParaICMS($cnae) {
		 
		// Lista cnae
		$cnaeArray = array(
			'4921-3/02'
			,'4929-9/02'
			,'4930-2/02'
			,'5021-1/02'
			,'5022-0/02'
		);
			
		// Verifica se o cnae informado esta na lista de cnae que deve ser apresentado o ICMS no lugar do ISS.
		if(in_array($cnae, $cnaeArray)){
			return true;	
		}	
			
		return false;
	}
}
?>