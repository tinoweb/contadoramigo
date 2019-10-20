<?php

/*
DATA: 03/12/2014
Esta classe realiza a comunicação entre o site e a Cielo.
Recomenda-se manter desacoplado do site, não incluíndo rotinas internas nesta classe.
*/
class Cielo
{	
	//Dados do estabelecimento, informado pelo site
	public $dadosEcNumero;
	public $dadosEcChave;
	
	//Dados do portador, informado pelo site
	public $dadosPortadorNumero;
	public $dadosPortadorVal;
	public $dadosPortadorInd;
	public $dadosPortadorCodSeg;
	public $dadosPortadorNome;
	
	//Dados do pedido, informado pelo site
	public $dadosPedidoNumero;
	public $dadosPedidoValor;
	public $dadosPedidoMoeda = "986";
	public $dadosPedidoData;
	public $dadosPedidoDescricao;
	public $dadosPedidoIdioma = "PT";
	
	//Dados da forma de pagamento, informado pelo site
	public $formaPagamentoBandeira;
	public $formaPagamentoProduto;
	public $formaPagamentoParcelas;		

	public $numeroTruncado;
	public $numeroPedido;

	//Indica se o pedido deve ou não ser capturado automaticamente, informado pelo site
	public $capturar;	
	
	//TID gerado pela transação, é informado na função requisitarTID()
	public $tid;
	
	//Token gerado na requisição de criação do token pela função requisitarToken()
	public $token;
	
	//Status do token (0=bloqueado, 1=desbloqueado)
	public $token_status;	
	
	//URL dde retorno
	public $url_retorno;	

	//Código do erro
	public $codigoErro;
	
	//Status da transação
	//0 = "Transação foi criada". A Cielo criou a transação mas ainda não a processou.
	//1 = "Transação em andamento".
	//2 = "Autenticada". A transação foi autenticada e está aguardando resposta se foi ou não autorizada.
	//3 = "Não Autenticada". A transação não foi autorizada pela Cielo. Este é um status final.
	//4 = "Autorizada". A transação foi autorizada mas ainda não capturada.
	//5 = "Não Autorizada". A transação não foi autenticada e está aguardando aguardando resposta se foi ou não autorizada. Aguarde.
	//6 = "Transação Capturada". A transação foi capturada pela Cielo, este é um status final.
	//9 = "Cancelada". A transação foi cancelada, este é um status final.
	//10 = "Em autenticação". A transação está em autenticação.
	//12 = "Em cancelamento". A transação está em processo de cancelamento.
	public $status;
	
	//Caminho do certificado, informado pelo site
	public $cert_path;
	
	//XML usados para envio e retorno das operações
	public $xmlRequisitarTIDEnvio;
	public $xmlRequisitarTIDResposta;
	public $xmlRequisitarTokenEnvio;
	public $xmlRequisitarTokenResposta;
	public $xmlAutorizacaoEnvio;
	public $xmlAutorizacaoResposta;		
	public $xmlAutorRecTokenEnvio;
	public $xmlAutorRecTokenResposta;
		
	public function __construct($ec_numero, $ec_chave, $cert_path)
	{
		$this->dadosEcNumero = $ec_numero;
		$this->dadosEcChave = $ec_chave;
		$this->cert_path = $cert_path;		
		
		define('VERSAO', "1.1.0");
	}
	
	//Retorna estrutura XML com o cabeçalho do documento
	private function XMLHeader()
	{
		return '<?xml version="1.0" encoding="ISO-8859-1" ?>'; 
	}
	
	//Retorna estrutura XML com os dados do estabelecimento
	private function XMLDadosEc()
	{
		$msg = 
		"<dados-ec>" .
			"<numero>". $this->dadosEcNumero ."</numero>" .
			"<chave>". $this->dadosEcChave ."</chave>" .
		'</dados-ec>';
						
		return $msg;
	}
	
	//Retorna estrutura XML com os dados da forma de pagamento
	private function XMLFormaPagamento()
	{
		$msg = 
		"<forma-pagamento>" .
			"<bandeira>" . $this->formaPagamentoBandeira ."</bandeira>" .
			"<produto>". $this->formaPagamentoProduto ."</produto>" .
			"<parcelas>". $this->formaPagamentoParcelas ."</parcelas>" .
		'</forma-pagamento>';
						
		return $msg;
	}
	
	// Retorna estrutura XML com os dados do portador
	private function XMLDadosPortador()
	{
		$msg = 
		'<dados-portador>' . 
			'<numero>' . $this->dadosPortadorNumero . '</numero>' .
			'<validade>' . $this->dadosPortadorVal . '</validade>' .
			'<indicador>' . $this->dadosPortadorInd .'</indicador>' .
			'<codigo-seguranca>' . $this->dadosPortadorCodSeg .'</codigo-seguranca>';
	
		if($this->dadosPortadorNome != null && $this->dadosPortadorNome != "")
		{
			$msg .= '<nome-portador>' . $this->dadosPortadorNome . '</nome-portador>';
		}
		
		$msg .= '</dados-portador>';
		
		return $msg;
	}
	
	// Retorna estrutura XML com os dados do portador com token
	private function XMLDadosPortadorToken()
	{
		$msg = 
		'<dados-portador>' . 
			'<token>' . $this->token . '</token>' .
		'</dados-portador>';
		
		return $msg;
	}
		
	// Retorna estrutura XML com os dados do cartão
	private function XMLDadosCartao()
	{
		$msg = 
		'<dados-cartao>' . 
			'<numero>'  . $this->dadosPortadorNumero . '</numero>' .
			'<validade>' . $this->dadosPortadorVal . '</validade>' .
			'<indicador>' . $this->dadosPortadorInd . '</indicador>' .
			'<codigo-seguranca>' . $this->dadosPortadorCodSeg . '</codigo-seguranca>';
			
		if($this->dadosPortadorNome != null && $this->dadosPortadorNome != "")
		{
			$msg .= '<nome-portador>' . $this->dadosPortadorNome . '</nome-portador>';
		}
		
		$msg .= '</dados-cartao>';
		
		return $msg;
	}
		
	// Retorna estrutura XML dos dados do pedido
	private function XMLDadosPedido()
	{
		$this->dadosPedidoData = date("Y-m-d") . "T" . date("H:i:s");
		$msg = 
		'<dados-pedido>' .
		'<numero>' . $this->dadosPedidoNumero . '</numero>' .
		'<valor>' . $this->dadosPedidoValor . '</valor>' .
		'<moeda>' . $this->dadosPedidoMoeda . '</moeda>' .
		'<data-hora>' . $this->dadosPedidoData . '</data-hora>';
		if($this->dadosPedidoDescricao != null && $this->dadosPedidoDescricao != "")
		{
			$msg .= '<descricao>' . $this->dadosPedidoDescricao . '</descricao>';
		}
		$msg .= '<idioma>' . $this->dadosPedidoIdioma . '</idioma>' .
		'</dados-pedido>';
						
		return $msg;
	}
	
	// Envia XML para Cielo
	private function enviarXml($xml)
	{
		if ($this->dadosEcNumero == '1006993069')
		{
			//URL de teste
			$url = "https://qasecommerce.cielo.com.br/servicos/ecommwsec.do";
		}
		else	
		{
			//URL de produção
			$url = "https://ecommerce.cielo.com.br/servicos/ecommwsec.do";
		}
				
		$sessao_curl = curl_init();
		curl_setopt($sessao_curl, CURLOPT_URL, $url);		
		curl_setopt($sessao_curl, CURLOPT_FAILONERROR, true);
		curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($sessao_curl, CURLOPT_CAINFO, $this->cert_path);
		curl_setopt($sessao_curl, CURLOPT_SSLVERSION, 4);
		curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 40);
		curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($sessao_curl, CURLOPT_POST, true);
		curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, $xml );
	
		$resultado = curl_exec($sessao_curl);
		
		curl_close($sessao_curl);
	
		if ($resultado)
		{
			return $resultado;
		}
		else
		{
			return curl_error($sessao_curl);
		}
	}

	// Envia requisição para criação um TID junto à Cielo. Esta é a etapa inicial de qualquer venda	
	public function requisitarTID()
	{
		$xmlEnvio = 
		$this->XMLHeader() . "\n" .
		'<requisicao-tid id="' . md5(date("YmdHisu")) . '" versao ="' . VERSAO .'">' . "\n   "
			. $this->XMLDadosEc() . "\n   " 
			. $this->XMLFormaPagamento() . "\n" .
		'</requisicao-tid>';		
		
		$respostaXml = $this->enviarXml("mensagem=" . $xmlEnvio);
		
		$objResposta = simplexml_load_string($respostaXml);
		
		//Recebe XML de envio e de retorno da operação
		$this->xmlRequisitarTIDEnvio = $xmlEnvio;
		$this->xmlRequisitarTIDResposta = $respostaXml;
		
		//Recebe TID
		$this->tid = $objResposta->tid;
	}
	// Envia requisição para criação um TID junto à Cielo. Esta é a etapa inicial de qualquer venda	
	public function requisitarToken()
	{
		$xmlEnvio = 
		$this->XMLHeader() . "\n" .
		'<requisicao-token id="' . md5(date("YmdHisu")) . '" versao ="' . VERSAO .'">' . "\n   "
			. $this->XMLDadosEc() . "\n   " 
			. $this->XMLDadosPortador() . "\n" .
		'</requisicao-token>';		
		
		$respostaXml = $this->enviarXml("mensagem=" . $xmlEnvio);
		
		$objResposta = simplexml_load_string($respostaXml);
		
		//Recebe XML de envio e de retorno da operação
		$this->xmlRequisitarTokenEnvio = $xmlEnvio;
		$this->xmlRequisitarTokenResposta = $respostaXml;
		
		$elementToken = "token";
		$elementDadosToken = "dados-token";		
		$elementCodigoToken = "codigo-token";		
		$elementStatusToken = "status";
		$elementNumeroTruncado = "numero-cartao-truncado";		
		
		$this->token = $objResposta->$elementToken->$elementDadosToken->$elementCodigoToken;
		$this->token_status = $objResposta->$elementToken->$elementDadosToken->$elementStatusToken;
		$this->numeroTruncado = $objResposta->$elementToken->$elementDadosToken->$elementNumeroTruncado;
	}
	
	// Envia requisição para criação de nova venda, enviando os dados do pedido e do cartão do cliente
	public function requisicaoAutorizacao()
	{
		$xmlEnvio = $this->XMLHeader() . "\n" .
	   '<requisicao-autorizacao-portador id="' . md5(date("YmdHisu")) . '" versao ="' . VERSAO . '">' . "\n"
			. '<tid>' . $this->tid . '</tid>' . "\n   "
			. $this->XMLDadosEc() . "\n   " 
			. $this->XMLDadosCartao() . "\n   "
			. $this->XMLDadosPedido() . "\n   "
			. $this->XMLFormaPagamento() . "\n   "
			. '<capturar-automaticamente>' . $this->capturar . '</capturar-automaticamente>' . "\n" .
	   '</requisicao-autorizacao-portador>';
	   
		$respostaXml = $this->enviarXml("mensagem=" . $xmlEnvio);
		
		$objResposta = simplexml_load_string($respostaXml);
		
		//Recebe status da transação. É este parâmetro que irá determinar a tratativa da transação.
		$this->status = $objResposta->status;

		//Recebe XML de envio e de retorno da operação
		$this->xmlAutorizacaoEnvio = $xmlEnvio;
		$this->xmlAutorizacaoResposta = $respostaXml;

		$dadosPedido = "dados-pedido";
		$numero = "numero";		
		
		$this->numeroPedido = $objResposta->$dadosPedido->$numero;
		
	}
	
	// Envia requisição para criação de nova venda com token
	public function requisicaoAutorizacaoToken()
	{
		$xmlEnvio = $this->XMLHeader() . "\n" .
	   '<requisicao-transacao id="' . md5(date("YmdHisu")) . '" versao="1.2.1">'
			. $this->XMLDadosEc() . "\n   " 
			. $this->XMLDadosPortadorToken() . "\n   "
			. $this->XMLDadosPedido() . "\n   "
			. $this->XMLFormaPagamento() . "\n   "
			. '<url-retorno>' . $this->url_retorno . '</url-retorno>' . "\n" 			
			. '<autorizar>4</autorizar>' . "\n"
			. '<capturar>' . $this->capturar . '</capturar>' . "\n" .
	   '</requisicao-transacao>';

		$respostaXml = $this->enviarXml("mensagem=" . $xmlEnvio);
		
		$objResposta = simplexml_load_string($respostaXml);		
		
		//Recebe status da transação. É este parâmetro que irá determinar a tratativa da transação.
		$this->status = $objResposta->status;

		$codigo = "codigo";
		$this->codigoErro = $objResposta->codigo;
		
		//Recebe XML de envio e de retorno da operação
		$this->xmlAutorRecTokenEnvio = $xmlEnvio;
		$this->xmlAutorRecTokenResposta = $respostaXml;



	}
}
?>