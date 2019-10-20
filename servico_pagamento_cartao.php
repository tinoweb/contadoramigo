<?php
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

session_start();

require_once("conect.php");
require_once("classes/pagamento.php");
require_once('Model/TokenPagamento/TokenPagamentoData.php');

class ServicoPagamentoCartao {
	
	// Método criado para realizar o pagamento 
	public function RealizaPagamento($tipo, $valor, $data, $idUser, $contadorId) {
		
		// Inclui os aquivos que auxilia o pagamento.
		require_once "class/bean.php";
		require_once "class/cielo.php";
		require_once 'class/pagamento-cartao.php';
		require_once "DataBaseMySQL/LogAcessos.php";
		require_once "DataBaseMySQL/LogCartao.php";
		require_once "DataBaseMySQL/CobrancaContador.php";
		require_once "DataBaseMySQL/RelatorioCobranca.php";
		require_once "DataBaseMySQL/ServicoRegistrados.php";
		require_once "DataBaseMySQL/EnvioEmailsCobranca.php";
		require_once "Model/DadosContador/DadosContadorData.php";
		
		// Instancia da classe de log 
		$logAcessos = new LogAcessos();
		
		// Instancia a classe que manipula os dados do tokem de pagamento.
		$tokenPagamento = new TokenPagamentoData();
		
		// Pega os dados do tokem do pagamento.	
		$tokenDados = $tokenPagamento->PegaDadosTokenPagamento($idUser);
	
		// Realiza o pagamento com a cielo.
		//Cria um objeto que armazenará os dados do cartao para enviar ao pagamento
		$cartao = new Dados_cartao();
		$cartao->setValor($valor);//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
		$cartao->setBandeira($tokenDados->getBandeira());//Seta a bandeira 
		$cartao->setValorFinal($valor);
		
		//Cria um objeto para o pagamento
		$pagamento = new Pagamento_cartao();
		//Define o token para a cobrança
		$pagamento->setToken($tokenDados->getToken());

		//Tenta realizar a cobrança
		$pagamento->pagarComToken($cartao);
		$data_pagamento_cartao = $pagamento->getData();
	
		// LOG DE ACESSOS
		$acao1 = "SERVIÇO CONTADOR: Dados da cobrança: titular - ".$tokenDados->getNomeTitular()." / nº - ".$tokenDados->getNumeroCartao()." / bandeira - ".$tokenDados->getBandeira();
		$logAcessos->IncluiLogAcessos($idUser, $acao1);
	
		// Trata erro de retorno
		$retorno_codigo_erro = $pagamento->getCodigoErro();
		$retorno_codigo_autorizacao = $pagamento->getStatus();
		$retorno_tid = $pagamento->getTid();

		$XmlResposta = $pagamento->getXmlRetorno();

		$string_erro = $XmlResposta;
		$string_erro = explode('<autorizacao>', $string_erro);
		$string_erro = explode('</mensagem>', $string_erro[1]);
		$string_erro[0] = strip_tags($string_erro[0]);

		// Grava o erro do log de retorno
		$logAcessos->IncluiLogAcessos($idUser, 'RETORNO: '.$string_erro[0]);	
		
		// Instancia da classe
		$logCartao =  new LogCartao();
		
		// Grava o log do cartão.
		$logCartao->IncluiLogCartao($idUser, $retorno_codigo_erro, $retorno_codigo_autorizacao, $XmlResposta);
		
		//****************** Para teste
		if ( $idUser == 9 || $idUser == 1581 ) {
			$retorno_codigo_autorizacao = 6;
		}
		//******************
		
		// Grava os dados de pagamento em relatorio de cobrança.
		// Verifica o retorno do pagamento do cartao - Código 5 equivale a transação não autorizada
		if(($retorno_codigo_erro == '') && ($retorno_codigo_autorizacao != '5')) {
			
			 // Verifica se foi informao numero do tid
	    	if (is_null($retorno_tid) || $retorno_tid == "") {
	
				// Pega a oresultado da acão. 
				$resultadoAcao = $this->RetornaResultadoAcao($tipo);
				
				// Gravando o pagamento no registro de cobranca.
				$relatorioCobranca = new RelatorioCobranca();	
				
				// realiza a inclusão do pagamento e pega o ultimo código do relatorio de cobrança.
				$idRelatorio = $relatorioCobranca->IncluiRelatorioCobranca($idUser, 0, $tokenDados->getBandeira(), $resultadoAcao, 'não enviado' , '', $valor, $tokenDados->getNumeroCartao(), '', '', $tipo);
				
				// Atualiza a tabela cobranca contador.	
				$cobrancaContador = new CobrancaContador();
				
				// Pega o valor liquido e inclui o valor pago ou apagar.
				$valorLiquido = $valor - number_format(($valor / 2),2,'.','');
				
				$cobrancaContador->IncluiCobrancaContador($idRelatorio, $contadorId, $valor, $valorLiquido, 0);
				
				$_SESSION['servico_erro'] = 'O pagamento não pode ser realizado, pois a sua operadora não autorizou a transação.';
				
				// Retorna para tela de mensagem de servico.
				header('Location: servico-contador-mensagem.php');
				
				// usado para força o redirecionamento.
				die();
	
			} else {
				
				// Pega a oresultado da acão. 
				$resultadoAcao = $this->RetornaResultadoAcao($tipo);
				
				// Gravando o pagamento no registro de cobranca.
				$relatorioCobranca = new RelatorioCobranca();	
				
				// realiza a inclusão do pagamento e pega o ultimo código do relatorio de cobrança.
				$idRelatorio = $relatorioCobranca->IncluiRelatorioCobranca($idUser, 0, $tokenDados->getBandeira(), $resultadoAcao, 'enviado', $retorno_tid, $valor, $tokenDados->getNumeroCartao(), '', '', $tipo);
				
				// Atualiza a tabela cobranca contador.	
				$cobrancaContador = new CobrancaContador();
				
				// Pega o valor liquido e inclui o valor pago ou apagar.
				$valorLiquido = $valor - number_format(($valor / 2),2,'.','');
				
				$cobrancaContadorId = $cobrancaContador->IncluiCobrancaContador($idRelatorio, $contadorId, $valor, $valorLiquido, 0);
				
				// Instancia a classe que é uilizada para minipula os dados do serviço contratado.
				$servicoRegistrados = new ServicoRegistrados();
				
				// Realiza a inclusão do serviço na lista de serviço.
				$servicoRegistrados->IncluiServicoContatado($idUser, $contadorId,$tipo,$valor,$cobrancaContadorId,'cartao');
								
				// LOG DE ACESSOS
				$logAcessos->IncluiLogAcessos($idUser, "MINHA CONTA QUITAR: USUARIO EFETUOU PAGAMENTO DE ".$valor." COM ".$tokenDados->getBandeira());
				$logAcessos->IncluiLogAcessos($idUser, 'RETORNO: '.$string_erro[0]);
				
				// Cria a sessão informando que pagamento foi cartão.
				$_SESSION['pagamento'] = 'Cartao';
				
				$dadosContadorData = new DadosContadorData();
		
				$dadosContador = $dadosContadorData->GetDataDadosContador($contadorId);

				$contadorNome = $dadosContador->getNome(); 
				$email = $dadosContador->getEmail();
				$tipo_mensagem = 'servico_contratado';

				//$envioEmailsCobranca = new EnvioEmailsCobranca();

				// Realiza a gravação dos dados de envio.
				//$envioEmailsCobranca->GravaDadosEnvioEmail($contadorNome, $email, $tipo_mensagem);
				
				// Retorna para tela de mensagem de servico.
				header('Location: servico-contador-sucesso.php');	
				
				// usado para força o redirecionamento.
				die();
			}
		
		} else {
			
			// LOG DE ACESSOS
			$logAcessos->IncluiLogAcessos($idUser, 'SERVIÇO CONTADOR: TENTATIVA DE PAGAMENTO COM ERRO');
			$logAcessos->IncluiLogAcessos($idUser, 'RETORNO: '.$string_erro[0]);
		
			// Cria a sessão com o erro. 	
			$_SESSION['servico_erro'] = 'O pagamento não pode ser realizado, pois o cartão informado é inválido.';
				
			// Retorna para tela de mensagem de servico.
			header('Location: servico-contador-mensagem.php');
		}
		
	}
	
	// Pega a numeração do resultado da ação.
	public function RetornaResultadoAcao($servico) {
			
		// Resultado da acao definida de acordo com o boleto.
		switch($servico) {
			case 'Gfip_GPS':
					$resultadoAcao = '8.2';
				break;
			case 'Simples_DAS':
					$resultadoAcao = '8.3';
				break;
			case 'Defis':
					$resultadoAcao = '8.4';
				break;	
			case 'Rais_negativa':
					$resultadoAcao = '8.5';
				break;
			case 'Dirf':
					$resultadoAcao = '8.6';
				break;
			case 'F_empresa':
					$resultadoAcao = '8.7';
				break;
			case 'F_sociedade':
					$resultadoAcao = '8.8';
				break;
			case 'MEI-ME':
					$resultadoAcao = '8.9';
				break;
			case 'CPOM':
					$resultadoAcao = '9.0';
				break;
			case 'A_empresa':
					$resultadoAcao = '9.4';
				break;
			case 'A_sociedade':		
					$resultadoAcao = '9.5';
				break;
			case 'decore':
					$resultadoAcao = '9.6';
				break;
			case 'DBE':
					$resultadoAcao = '9.7';
				break;
			case 'funcionario_C_D':
					$resultadoAcao = '9.8';
				break;
			case 'regularizacao_emp':
					$resultadoAcao = '11.0';
				break;
			case 'DCTF':
					$resultadoAcao = '11.2';
				break;				
			case 'DES':
					$resultadoAcao = '11.3';
				break;
			case 'IRPF':
					$resultadoAcao = '11.4';
				break;
			default:
					$resultadoAcao = '9.3';
				break;
		}

		// Retorna o resultado da acão.
		return $resultadoAcao;
	}
		
}
?>