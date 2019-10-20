<?php 


	class Pagamento_cartao
	{
		
		private $token;
		private $valor;
		private $numeroTruncado;
		private $tid;
		private $status;
		private $numeroPedido;
		private $data;
		private $erro;

		private $xmlRetorno;

		function getXmlRetorno(){
			return $this->xmlRetorno;
		}
		function setXmlRetorno($string){
			$this->xmlRetorno = $string;
		}

		function getToken(){
			return $this->token;
		}
		function setToken($string){
			$this->token = $string;
		}

		function getNumeroTruncado(){
			return $this->numeroTruncado;
		}
		function setNumeroTruncado($string){
			$this->numeroTruncado = $string;
		}

		function getTid(){
			return $this->tid;
		}
		function setTid($string){
			$this->tid = $string;
		}

		function getStatus(){
			return $this->status;
		}
		function setStatus($string){
			$this->status = $string;
		}

		function getNumeroPedido(){
			return $this->numeroPedido;
		}
		function setNumeroPedido($string){
			$this->numeroPedido = $string;
		}

		function getData(){
			$aux = explode("T", $this->data);
			return $aux[0].' '.$aux[1];
		}
		function setData($string){
			$this->data = $string;
		}

		function getValor(){
			return $this->valor;
		}
		function setValor($string){
			$this->valor = $string;
		}

		function getCodigoErro(){
			return $this->codigoErro;
		}
		function setCodigoErro($string){
			$this->codigoErro = $string;
		}


		function gerarToken($cartao){
			
			//Pega os dados do estabelecimento, true para producao e false para teste(Preencher na classe com os dados de produção, estão atualmente apeas os dados de teste)
			$dados = new Estabelecimento();

			//Cria nova instância
			$Cielo = new Cielo($dados->getEstabelecimento(), $dados->getChave(), $dados->getCert_path());

			//Requisita TID
			$Cielo->requisitarTID();

			//Informa número do cartão 
			$Cielo->dadosPortadorNumero = $cartao->getNumero_cartao();

			//Validade do cartão no formato aaaamm. Exemplo: 201212 (dez/2012)
			$Cielo->dadosPortadorVal = $cartao->getValidade();

			//Indicador sobre o envio do Código de segurança: 
			//0 – estabelecimento não informou 1 – informado 2 – ilegível 9 – portador informou que não existe no cartão
			$Cielo->dadosPortadorCodSeg = $cartao->getCodigo_seguranca();

			//Obrigatório indicador ser = 1.
			$Cielo->dadosPortadorInd = "1";

			//Nome impresso no cartão.
			$Cielo->dadosPortadorNome = $cartao->getNome();

			//Data do pedido, informar como YYYY-MM-DDTHH:mm:SS
			$Cielo->dadosPedidoData = date('YYYY-MM-DD').'T'.date('HH:mm:SS');

			//Gera token
			$Cielo->requisitarToken();

			$this->token = $Cielo->token;

			$this->numeroTruncado = $Cielo->numeroTruncado;
			
			$this->trataStatusToken($Cielo);

			$this->exibeDadosXmlToken($Cielo);

		}

		function pagarComToken($cartao){
			
			//Pega os dados do estabelecimento, true para producao e false para teste
			$dados = new Estabelecimento();
			//Cria um objeto com os dados do cartao recebidos via POST
			
			//Cria nova instância
			$Cielo = new Cielo($dados->getEstabelecimento(), $dados->getChave(), $dados->getCert_path());

			//Nome da bandeira (minúsculo): “visa” “mastercard” “diners” “discover” “elo” “amex” “jcb” “aura”
			$Cielo->formaPagamentoBandeira = $cartao->getBandeira();

			//Para vendas recorrentes o código do produto é sempre Crédito à Vista (1)
			$Cielo->formaPagamentoProduto = 1;

			//Para vendas recorrentes as parcelas são sempre 1
			$Cielo->formaPagamentoParcelas = 1;

			//Define URL de retorno
			$Cielo->url_retorno = "http://ambientedeteste2.hospedagemdesites.ws";

			//Número do pedido. Recomenda-se que seja um valor único por pedido
			$Cielo->dadosPedidoNumero = rand(1,1000000);

			//Requisita TID
			$Cielo->requisitarTID();    

			$this->tid = $Cielo->tid;//Pega o TID para identificar a resposta da transacao

			//Valor a ser cobrado sem  pontos ou vírgulas. Por exemplo, para informar R$ 100,50 informe como 10050
			$Cielo->dadosPedidoValor = $cartao->getValorFinal();

			//Data do pedido, informar como YYYY-MM-DDTHH:mm:SS
			$Cielo->dadosPedidoData = date('YYYY-MM-DD').'T'.date('HH:mm:SS');

			//Define se a transação será automaticamente capturada caso seja autorizada
			$Cielo->capturar = true;

			//Define se a transação será automaticamente capturada caso seja autorizada
			$Cielo->token = urlencode($this->token);

			//Envia dados do portador para Cielo autorizar
			$Cielo->requisicaoAutorizacaoToken();

			$this->status = $Cielo->status;
			
			$this->numeroPedido = $Cielo->dadosPedidoNumero;			

			$this->data = $Cielo->dadosPedidoData;

			$this->valor = $Cielo->dadosPedidoValor;
			
			$this->codigoErro = $Cielo->codigoErro;

			$this->xmlRetorno = $Cielo->xmlAutorRecTokenResposta;

			$this->tratarStatusTokenTransacao($Cielo->status);

			$this->exibeDadosXmlTransacao($Cielo);

		}

		function tratarStatusTokenTransacao($status){

			//Realiza tratamento do status
			switch ($status) {

				case 0:
				    echo "Transação foi criada";
				    break;  
				    
				case 1:
				    echo "Transação em andamento";
				    break;
				    
				case 2:
				    echo "Autenticada";
				    break;

				case 3:
				    echo "Não Autenticada";
				    break;
				    
				case 4:
				    echo "Autorizada";
				    break;      

				case 5:
				    echo "Não Autorizada";
				    break;      
				    
				case 6:
				    echo "Transação capturada";
				    break;
				    
				case 9:
				    echo "Cancelada";
				    break;      

				case 10:
				    echo "Em autenticação";
				    break;
				    
				case 11:
				    echo "Em cancelamento";
				    break;      
				    
				default:
				    echo "Erro de transação";
				}

				echo "<hr />";

		}

		function exibeDadosXmlTransacao($Cielo){
			echo '<pre>';
			//Exibe dados xml de envio para requisição do TID
			//ATENÇÃO, OS DADOS DESTE XML CONTÉM A CHAVE DE SEGURANÇA DO ESTABELECIMENTO
			// //PARA NÃO COMPROMETER A SEGURANÇA RECOMENDA-SE NÃO ARMAZENAR ESTES DADOS EM BANCO
			echo "<div>TID envio</div>";
			echo $Cielo->xmlRequisitarTIDEnvio;
			echo "<hr />";

			//Exibe dados xml de resposta para requisição do TID
			echo "<div>TID resposta</div>";
			echo $Cielo->xmlRequisitarTIDResposta;
			echo "<hr />";

			//Exibe dados xml da requisição de autorização
			//ATENÇÃO, OS DADOS DESTE XML CONTÉM AS INFORMAÇÕES DE CARTÃO DO CLIENTE.
			//PARA NÃO COMPROMETER A SEGURANÇA RECOMENDA-SE NÃO ARMAZENAR ESTES DADOS EM BANCO
			echo "<div>Autorização envio</div>";
			echo $Cielo->xmlAutorRecTokenEnvio;
			echo "<hr />";

			//Exibe dados xml da respota da requisição de autorização
			echo "<div>Autorização resposta</div>";
			echo $Cielo->xmlAutorRecTokenResposta;
			echo "<hr />";
			echo '</pre>';
		}

		function trataStatusToken($status){
			echo '<h1> Token Gerado - Passo 02/04</h1><hr>';
			switch ($status) {
			    case 0:
					echo "Token Bloqueado";
					break;	
					
				case 1:
			        echo "Token Desbloqueado";
			        break;
					
				default:
					echo "Erro de transação";
			}
			echo "<hr/>";
		}

		function exibeDadosXmlToken($Cielo){
			echo '<pre>';
			//Exibe dados xml da requisição de autorização
			//ATENÇÃO, OS DADOS DESTE XML CONTÉM AS INFORMAÇÕES DE CARTÃO DO CLIENTE.
			//PARA NÃO COMPROMETER A SEGURANÇA RECOMENDA-SE NÃO ARMAZENAR ESTES DADOS EM BANCO
			echo "<div>Autorização envio</div>";
			echo $Cielo->xmlRequisitarTokenEnvio;
			echo "<hr />";

			//Exibe dados xml da respota da requisição 
			echo "<div>Autorização resposta</div>";
			echo $Cielo->xmlRequisitarTokenResposta;
			echo "<hr />";

			//Recebe token (GUARDAR ESTE DADO PARA TRANSAÇÕES FUTURAS)
			$token = $Cielo->token;

			echo 'token=' . $Cielo->token;

			echo "<hr><a href='pagar.php?token=" . $token . "'>Ir para pagamento</a><hr />";
			echo '</pre>';



		}
	}



	// #########################################################################################
	// ############### Trecho para criar o Token ###############################################

	
	include 'cielo.php';
	include 'bean.php';


	// //Cria um objeto com os dados do cartao
	// $cartao = new Dados_cartao();

	// $cartao->setNome("vitor maradei");//nome do assinante como está no cartão
	// $cartao->setNumero_cartao("4984538142034439");//Número atual é o numero do ambiente de teste
	// $cartao->setValidade("202104");//Validade no formato AAAAMM
	// $cartao->setCodigo_seguranca("673");//Código de segurança,
	// // $cartao->setValor("59");//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
	// // $cartao->setBandeira("visa");//Seta a bandeira 

	// //Cria um objeto para o pagamento
	// $pagamento = new Pagamento_cartao();
	// //Gera o token de pagamento
	// $pagamento->gerarToken($cartao);
	// echo $pagamento->getToken();





	################ Fim do trecho para criar o token de pagamento ##########################
	#########################################################################################


	// ---------------------------------------------------------------------------------------------------------------------------------------


	#########################################################################################
	############### Trecho para pagar com o Token ###########################################

	


	$cartao = new Dados_cartao();
	// $cartao->setNome("Nome do assinante");//nome do assinante como está no cartão
	// $cartao->setNumero_cartao("4551870000000183");//Número atual é o numero do ambiente de teste
	// $cartao->setValidade("201620");//Validade no formato AAAAMM
	// $cartao->setCodigo_seguranca("123");//Código de segurança,
	$cartao->setValor("1");//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
	$cartao->setBandeira("visa");//Seta a bandeira 
	$cartao->setValorFinal(1.00);

	//Cria um objeto para o pagamento
	$pagamento = new Pagamento_cartao();

	// $id = $id

	$pagamento->setToken("45x3rxgB+mC63bIITELMVy7XJp9nnuwXJ3oseJjy6Dc=");
	$pagamento->pagarComToken($cartao);

	echo $pagamento->getTid();
	echo '<br>';
	echo $pagamento->getStatus();
	echo '<br>';
	echo $pagamento->getNumeroPedido();
	echo '<br>';
	echo $pagamento->getData();
	echo '<br>';
	echo $pagamento->getValor();





	################ Fim do trecho para pagar com o token de pagamento ######################
	#########################################################################################
	

?>







