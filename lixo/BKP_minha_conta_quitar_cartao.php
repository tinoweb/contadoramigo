<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
' Kit de Integração Cielo
' Versão: 3.0
' Arquivo: autorizacao_direta_transacao.php
' Função: Autorização direta de uma transação na Cielo Ecommerce
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
-->
<?
include "conect.php";
include "session.php";

// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5

// ########################################################################################################
ini_set('allow_url_fopen', 1); // Ativa a diretiva 'allow_url_fopen'

function getURL(){
	// BUSCANDO SE O USUARIO TEM COBRANÇA NÃO PAGA
	$sql = "SELECT * FROM historico_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' AND status_pagamento='não pago' ORDER BY idHistorico ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	// SE O USARIO NÃO TIVER COBRANÇA NÃO PAGA ...
	if(mysql_num_rows($resultado) == "0") {
		$totalQuitar = "5000"; // ... SERÁ COBRADO 50 REAIS ...
	} else {
		$totalQuitar = 50 * mysql_num_rows($resultado) . "00"; // ... OU 50 REAIS VEZES O NUMERO DE COBRANÇAS NÃO PAGAS
	}

	$sql = "SELECT * FROM dados_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	$linha=mysql_fetch_array($resultado);
	
	$NomeTitular = $linha["nome_titular"];
	$NumeroCartao = $linha["numero_cartao"];
	$DataValidade = date('Ym',strtotime($linha["data_validade"]));
	$Codigo = $linha["codigo_seguranca"];
	$FormaPagamento = $linha['forma_pagameto'];
	
	// Dados obtidos da loja para a transação

    // - dados do processo
    $identificacao = '4843543';
    $modulo = 'CIELO';
    $operacao = 'Autorizacao-Direta';
    $ambiente = 'PRODUCAO';

    // - dados do cartão
    $nome_portador_cartao = $NomeTitular;
    $numero_cartao = $NumeroCartao;
    $validade_cartao = $DataValidade;
    $indicador_cartao = '1';
    $codigo_seguranca_cartao = $Codigo;

    // - dados do pedido
    $idioma = 'PT';
    $valor = $totalQuitar;
    $pedido = $_SESSION["id_userSecao"] . date("dmYHis");
    $descricao = 'Assinatura Contador Amigo';

    // - dados do pagamento
    $bandeira = $FormaPagamento;
    $forma_pagamento = '1';
    $parcelas = '1';
    $autorizar = '1';
    $capturar = 'true';

    // - dados adicionais
    $campo_livre = '';

    // Monta a variável com os dados para postagem
    $request = 'identificacao=' . $identificacao;
    $request .= '&modulo=' . $modulo;
    $request .= '&operacao=' . $operacao;
    $request .= '&ambiente=' . $ambiente;

    $request .= '&nome_portador_cartao=' . $nome_portador_cartao;
    $request .= '&numero_cartao=' . $numero_cartao;
    $request .= '&validade_cartao=' . $validade_cartao;
    $request .= '&indicador_cartao=' . $indicador_cartao;
    $request .= '&codigo_seguranca_cartao=' . $codigo_seguranca_cartao;

    $request .= '&idioma=' . $idioma;
    $request .= '&valor=' . $valor;
    $request .= '&pedido=' . $pedido;
    $request .= '&descricao=' . $descricao;

    $request .= '&bandeira=' . $bandeira;
    $request .= '&forma_pagamento=' . $forma_pagamento;
    $request .= '&parcelas=' . $parcelas;
    $request .= '&autorizar=' . $autorizar;
    $request .= '&capturar=' . $capturar;

    $request .= '&campo_livre=' . $campo_livre;

    // Faz a postagem para a Cielo
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://comercio.locaweb.com.br/comercio.comp');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$XMLtransacao = GetURL();

// Carrega o XML
$objDom = new DomDocument();
$loadDom = $objDom->loadXML($XMLtransacao);

$nodeErro = $objDom->getElementsByTagName('erro')->item(0);
if ($nodeErro != '') {
    $nodeCodigoErro = $nodeErro->getElementsByTagName('codigo')->item(0);
    $retorno_codigo_erro = $nodeCodigoErro->nodeValue;

    $nodeMensagemErro = $nodeErro->getElementsByTagName('mensagem')->item(0);
    $retorno_mensagem_erro = $nodeMensagemErro->nodeValue;
}

$nodeTransacao = $objDom->getElementsByTagName('transacao')->item(0);
if ($nodeTransacao != '') {
    $nodeTID = $nodeTransacao->getElementsByTagName('tid')->item(0);
    $retorno_tid = $nodeTID->nodeValue;

    $nodePAN = $nodeTransacao->getElementsByTagName('pan')->item(0);
    $retorno_pan = $nodePAN->nodeValue;

    $nodeDadosPedido = $nodeTransacao->getElementsByTagName('dados-pedido')->item(0);
    if ($nodeTransacao != '') {
        $nodeNumero = $nodeDadosPedido->getElementsByTagName('numero')->item(0);
        $retorno_pedido = $nodeNumero->nodeValue;

        $nodeValor = $nodeDadosPedido->getElementsByTagName('valor')->item(0);
        $retorno_valor = $nodeValor->nodeValue;

        $nodeMoeda = $nodeDadosPedido->getElementsByTagName('moeda')->item(0);
        $retorno_moeda = $nodeMoeda->nodeValue;

        $nodeDataHora = $nodeDadosPedido->getElementsByTagName('data-hora')->item(0);
        $retorno_data_hora = $nodeDataHora->nodeValue;

        $nodeDescricao = $nodeDadosPedido->getElementsByTagName('descricao')->item(0);
        $retorno_descricao = $nodeDescricao->nodeValue;

        $nodeIdioma = $nodeDadosPedido->getElementsByTagName('idioma')->item(0);
        $retorno_idioma = $nodeIdioma->nodeValue;
    }

    $nodeFormaPagamento = $nodeTransacao->getElementsByTagName('forma-pagamento')->item(0);
    if ($nodeFormaPagamento != '') {
        $nodeBandeira = $nodeFormaPagamento->getElementsByTagName('bandeira')->item(0);
        $retorno_bandeira = $nodeBandeira->nodeValue;

        $nodeProduto = $nodeFormaPagamento->getElementsByTagName('produto')->item(0);
        $retorno_produto = $nodeProduto->nodeValue;

        $nodeParcelas = $nodeFormaPagamento->getElementsByTagName('parcelas')->item(0);
        $retorno_parcelas = $nodeParcelas->nodeValue;
    }

    $nodeStatus = $nodeTransacao->getElementsByTagName('status')->item(0);
    $retorno_status = $nodeStatus->nodeValue;

    $nodeAutenticacao = $nodeTransacao->getElementsByTagName('autenticacao')->item(0);
    if ($nodeAutenticacao != '') {
        $nodeCodigoAutenticacao = $nodeAutenticacao->getElementsByTagName('codigo')->item(0);
        $retorno_codigo_autenticacao = $nodeCodigoAutenticacao->nodeValue;

        $nodeMensagemAutenticacao = $nodeAutenticacao->getElementsByTagName('mensagem')->item(0);
        $retorno_mensagem_autenticacao = $nodeMensagemAutenticacao->nodeValue;

        $nodeDataHoraAutenticacao = $nodeAutenticacao->getElementsByTagName('data-hora')->item(0);
        $retorno_data_hora_autenticacao = $nodeDataHoraAutenticacao->nodeValue;

        $nodeValorAutenticacao = $nodeAutenticacao->getElementsByTagName('valor')->item(0);
        $retorno_valor_autenticacao = $nodeValorAutenticacao->nodeValue;

        $nodeECIAutenticacao = $nodeAutenticacao->getElementsByTagName('eci')->item(0);
        $retorno_eci_autenticacao = $nodeECIAutenticacao->nodeValue;
    }

    $nodeAutorizacao = $nodeTransacao->getElementsByTagName('autorizacao')->item(0);
    if ($nodeAutorizacao != '') {
        $nodeCodigoAutorizacao = $nodeAutorizacao->getElementsByTagName('codigo')->item(0);
        $retorno_codigo_autorizacao = $nodeCodigoAutorizacao->nodeValue;

        $nodeMensagemAutorizacao = $nodeAutorizacao->getElementsByTagName('mensagem')->item(0);
        $retorno_mensagem_autorizacao = $nodeMensagemAutorizacao->nodeValue;

        $nodeDataHoraAutorizacao = $nodeAutorizacao->getElementsByTagName('data-hora')->item(0);
        $retorno_data_hora_autorizacao = $nodeDataHoraAutorizacao->nodeValue;

        $nodeValorAutorizacao = $nodeAutorizacao->getElementsByTagName('valor')->item(0);
        $retorno_valor_autorizacao = $nodeValorAutorizacao->nodeValue;

        $nodeLRAutorizacao = $nodeAutorizacao->getElementsByTagName('lr')->item(0);
        $retorno_lr_autorizacao = $nodeLRAutorizacao->nodeValue;

        $nodeARPAutorizacao = $nodeAutorizacao->getElementsByTagName('arp')->item(0);
        $retorno_arp_autorizacao = $nodeARPAutorizacao->nodeValue;
    }

    $nodeURLAutenticacao = $nodeTransacao->getElementsByTagName('url-autenticacao')->item(0);
    $retorno_url_autenticacao = $nodeURLAutenticacao->nodeValue;
}

// Se não ocorreu erro exibe parâmetros
if (($retorno_codigo_erro == '') and ($retorno_codigo_autorizacao != '5')) { //Código 5 equivale a transação não autorizada
	
	// PEGANDO DADOS DE COBRANÇA DO USUARIO
	$sql = "SELECT * FROM dados_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' ORDER BY id ASC LIMIT 0,1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	$linha=mysql_fetch_array($resultado);
	
	// CHECANDO SE TEM ALGUM HISTÓRIO NÃO PAGO PARA ESCOLHER SE ATUALIZA O não pago OU O pendente
	$sql = "SELECT * FROM historico_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' AND status_pagamento='não pago' ORDER BY idHistorico ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	if(mysql_num_rows($resultado) == "0") {
		$sql = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='" . $linha["forma_pagameto"] . "' WHERE id='" . $_SESSION["id_userSecao"] . "' AND status_pagamento='pendente'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	} else {
		$sql = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='" . $linha["forma_pagameto"] . "' WHERE id='" . $_SESSION["id_userSecao"] . "' AND status_pagamento='não pago'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	}
	
	$sql = "SELECT * FROM historico_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' ORDER BY idHistorico DESC LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	$linha2=mysql_fetch_array($resultado);

	$novo_pagamento = new DateTime($linha2['data_pagamento']);	
	$novo_pagamento->modify('+1 month');
	$novo_pagamento = $novo_pagamento->format( 'Y-m-d' );
	$sql = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $_SESSION["id_userSecao"] . "', '$novo_pagamento', 'pendente')";
	
	//se der certo pode apagar este comentario
	//$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m')+1,date('d'),date('Y'))));
	//$sql = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $_SESSION["id_userSecao"] . "', '$dataPagamento', 'pendente')";
	
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$sql = "UPDATE login SET status='ativo' WHERE id='" . $_SESSION["id_userSecao"] . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());


	// INSERINDO O REGISTRO NA TABELA DE RELATORIO DE COBRANÇA PARA ENTRAR NO RELATORIO
	$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email, tid) VALUES ('" . $_SESSION["id_userSecao"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha["forma_pagameto"] . "', '2.1', 'enviado', '" . $retorno_tid . "')";
	$resultadoup = mysql_query($sqlup)
	or die (mysql_error());
					
	
	$_SESSION['status_userSecao'] = 'ativo';
	//$_SESSION["email_userSecao"]
	
	/* Montando a mensagem a ser enviada no corpo do e-mail. */
	include "mensagens/conta_reativada.php";
 
/* Medida preventiva para evitar que outros domínios sejam remetente da sua mensagem. */
if (eregi('tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$', $_SERVER[HTTP_HOST])) {
        $emailsender='Contador_Amigo<webmaster@contadoramigo.com.br>'; // Substitua essa linha pelo seu e-mail@seudominio
} else {
        $emailsender = "Contador_Amigo<webmaster@contadoramigo.com.br>";
        //    Na linha acima estamos forçando que o remetente seja 'webmaster@seudominio',
        // Você pode alterar para que o remetente seja, por exemplo, 'contato@seudominio'.
}
 
/* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  */
if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
else $quebra_linha = "\n"; //Se "nÃ£o for Windows"
 
// Passando os dados obtidos pelo formulário para as variáveis abaixo
$nomeremetente     = 'Contador Amigo';
$emailremetente    = $emailsender;
$emaildestinatario = $_SESSION["email_userSecao"];
$comcopia          = $_POST['comcopia'];
$comcopiaoculta    = $_POST['comcopiaoculta'];
$assunto           = 'Conta Reativada';
$mensagem          = $mensagemHTML;
 
/* Montando o cabeÃ§alho da mensagem */
$headers = "MIME-Version: 1.1" .$quebra_linha;
$headers .= "Content-type: text/html; charset=utf-8" .$quebra_linha;
// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
$headers .= "From: " . $emailsender.$quebra_linha;
$headers .= "Cc: " . $comcopia . $quebra_linha;
$headers .= "Bcc: " . $comcopiaoculta . $quebra_linha;
$headers .= "Reply-To: " . $emailremetente . $quebra_linha;
// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)
 
/* Enviando a mensagem */

//É obrigatório o uso do parâmetro -r (concatenação do "From na linha de envio"), aqui na Locaweb:

if(!mail($emaildestinatario, $assunto, $mensagemHTML, $headers ,"-r".$emailsender)){ // Se for Postfix
    $headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
    mail($emaildestinatario, $assunto, $mensagemHTML, $headers );
}
 
/* Redirecionando para a página de sucesso */
	
	if(isset($_GET["reativar_conta"])){
		$ativo = "&ativo";
	}
	header('Location: minha_conta.php?sucesso' . $ativo);
} else {
	if($retorno_codigo_erro == '') {
		$retorno_codigo_erro = 'invalido';
	}
	
	header('Location: minha_conta.php?erro_cartao=' .  $retorno_codigo_erro);
}
?>