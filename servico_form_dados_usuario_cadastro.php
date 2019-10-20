<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);	

	session_start();
	
	// inclui arquivo de conexão com o banco de dados.
	require('conect.php');
	
	// inclusão dos arquivos de buscar e auxiliar dados da crobanca. 
	require_once('DataBaseMySQL/DadosCobranca.php');
	
	// inclui arquivo de manipulação do dados do token de pagamento
	require_once('DataBaseMySQL/DadosTokenPagamento.php');

	// Verifica se o poste com os dados foram informados.
	if(isset($_POST['sacado']) && !empty($_POST['sacado'])) {
		
		// Pega o código do usuario.
		$id_user = $_SESSION['id_userSecaoMultiplo'];
		
		$dadosCobranca = new DadosCobranca();
		
		$tipo = $_POST['rdbTipo'];
		$sacado = $_POST['sacado'];
		$documento = ( $tipo == 'PJ' ? $_POST['cnpj'] : $_POST['cpf']);
		$endereco = $_POST['endereco'];
		$bairro = $_POST['bairro'];
		$uf = explode(";", $_POST['selEstado']);
		$uf = $uf[1]; 
		$cidade = $_POST['txtCidade'];
		$cep = $_POST['cep'];
		$form_Pagamento = ( isset($_POST['radFormaPagamento']) && !empty($_POST['radFormaPagamento']) ? $_POST['radFormaPagamento'] : 'boleto' ) ;
	
		// Se o pagamento for no cartão ele grava os dados. 
		if(isset($_POST['gravarCartao']) && ($form_Pagamento == 'visa' || $form_Pagamento == 'mastercard' || $form_Pagamento == 'elo' || $form_Pagamento == 'amex' || $form_Pagamento == 'diners')) {
		
			
			// Fecha a coneção com banco de dados para evitar conflito MySQL server has gone away.
			mysql_close($conexao);
						
			// Trecho para criar o Token. 
			require_once"class/bean.php";
			require_once"class/cielo.php";
			require_once'class/pagamento-cartao.php';
			
			$nomeTitular = $_POST["txtNomeTitular"];
			$NumeroCartao = $_POST["txtNumeroCartao"];
			$Codigo = $_POST["txtCodigo"];
			
			$mes = substr($_POST["txtDataValidade"], 0, 2);
			$ano = substr($_POST["txtDataValidade"], 3, 4);
			
			$Datavalidade = date('Y-m-d',mktime(0,0,0,$mes,1,$ano));
			$Datavalidade = explode("-", $Datavalidade);
			$Datavalidade = $Datavalidade[0].$Datavalidade[1];

			// Cria um Objeto com os dados do cartao
			$cartao = new Dados_cartao();
			
			// Define normaliza o nome, deixando sem acentos e em caixa baixa
			$NomeTitular = strtolower(Normaliza($nomeTitular));
			
			//Define nome do assinante como está no cartão
			$cartao->setNome($NomeTitular);
			
			//Define número atual é o numero do ambiente de teste
			$cartao->setNumero_cartao($NumeroCartao);
			
			//Define validade no formato AAAAMM
			$cartao->setValidade($Datavalidade);
			
			// Define código de segurança
			$cartao->setCodigo_seguranca($Codigo);
		
			// Cria um objeto para o pagamento
			$pagamento = new Pagamento_cartao();
			
			// Gera o token de pagamento
			$pagamento->gerarToken($cartao);
			
			// MAL Essa parte esta destinada a gerar o token, enviando os dados do cartão para a cielo e armazenando apenas o token, todo o código acima desse será refeito
			$token = $pagamento->getToken();

			// Verifica foi gerado o token 
			if( $token ){
				
				// inclui arquivo de conexão com o banco de dados.
				require('conect.php');
				
				// se retorna simpleXML coverte para array.
				if(is_object($token)){
					$array = json_decode(json_encode($token), true);
					$tokenAux = $array[0];
				} else {
					$tokenAux = $token;	
				}
				
				// inclui a classe responsavel por pegar os dados do token de pagamento do banco.  
				$token = new DadosTokenPagamento();
			
				//Retorna os dados do token de pagamento.
				$dadostoken = $token->pegaDadosToken($id_user);
		
				//Pega o nnumero do cartao, que é retornado de forma truncada
				$numero_cartao = $pagamento->getNumeroTruncado();
	 
				// Verifica se será necessário atualizar o tokem ou criar um novo.
				if( isset( $dadostoken['id'] ) && !empty($dadostoken['id']) ){
	
					// Método para raliza a atualizacão do token.
					$token->AtualizaTokenPagamento($dadostoken['id'], $tokenAux, $numero_cartao, $nomeTitular, $form_Pagamento);
					
				} else {
					
					// Método para incluir um token de pagamento
					$token->GravaTokenPagamento($id_user, $tokenAux, $numero_cartao, $nomeTitular, $form_Pagamento);	
				}
				
			} else {
				
				//Informa o usuário que aconteceu algum erro durante a geração do token
				$_SESSION['erro_boleto_avulso_cartao'] = 'erro_cartao';
				
				header('Location: /servico_form_dados_usuario.php');
				die();
			}
		}
		
		// Pega os dados de cobranca para ver se esta definido no cartao.
		$dadosC = $dadosCobranca->pegaDadosLogin($id_user);
		
		if($dadosC['forma_pagameto'] == 'boleto') {
			
			// Grava os dados de cobranca.
			$dadosCobranca->AtualizaDadosCobranca($id_user, $sacado, $documento, $endereco, $bairro, $cep, $cidade, $uf, $tipo);
		} else {
		
			$formaPagameto = ", forma_pagameto = '".$form_Pagamento."'";
			
			// Grava os dados de cobranca.
			$dadosCobranca->AtualizaDadosCobranca($id_user, $sacado, $documento, $endereco, $bairro, $cep, $cidade, $uf, $tipo, $formaPagameto);
		}
		
		// Verifica se vai ser gerado bolero.
		if(isset($_SESSION['tipo']) && !empty($_SESSION['tipo']) && isset($_SESSION['valor']) && !empty($_SESSION['valor']) && isset($_SESSION['data']) && !empty($_SESSION['data']) && isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])){
			
			// Cria uma sessão.
			$tipo = $_SESSION['tipo'];
			$valor = $_SESSION['valor'];
			$data = $_SESSION['data'];
			$id_user = $_SESSION['id_user'];
			$contratoId = $_SESSION['contratoId'];
			
			// Apaga esta sessões.
			unset($_SESSION['tipo']);
			unset($_SESSION['valor']);
			unset($_SESSION['data']);
			unset($_SESSION['id_user']);
			unset($_SESSION['contratoId']);
			
			// Verifica se o pagamento e cartao ou boleto.
			if($form_Pagamento == 'boleto') {
			
				// Redireciona para a pagina de contrato.
				header('Location: /servicos_contador_contrato.php?tipo='.$tipo.'&valor='.$valor.'&data='.$data.'&id_user='.$id_user.'&contratoId='.$contratoId);
			
			} else {
		
				// Redireciona para a pagina de contrato.
				header('Location: /servicos_contador_contrato.php?tipo='.$tipo.'&valor='.$valor.'&data='.$data.'&id_user='.$id_user.'&contratoId='.$contratoId.'&cartao=ok');
				
			}
			
		} else {
			
			// Apaga esta sessões.
			unset($_SESSION['tipo']);
			unset($_SESSION['valor']);
			unset($_SESSION['data']);
			unset($_SESSION['id_user']);
			unset($_SESSION['contratoId']);
			
			// Redireciona para a pagina de contrato.
			header('Location: /servico-contador.php');
		}	
	} else {
		// Redireciona para a pagina de contrato.
		header('Location: /servico-contador.php');
	}
	
	// Método criado para normalizar caracter.
	function Normaliza($string) {
		$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
		'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
		'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
		'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
		return strtr($string, $table);
	}
	
?>