<?
session_start();
/*
<!--
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
' Kit de Integração Cielo
' Versão: 3.0
' Arquivo: registra_transacao.php
' Função: Registro de uma transação na Cielo Ecommerce
'-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
-->*/
// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5

// ########################################################################################################
ini_set('allow_url_fopen', 1); // Ativa a diretiva 'allow_url_fopen'

function getURL(){

    // Dados obtidos da loja para a transação

    // - dados do processo
    $identificacao = '4843543';
    $modulo = 'CIELO';
    $operacao = 'Registro';
    $ambiente = 'TESTE';

    // - dados do cartão
    $bin_cartao = '401200';

    // - dados do pedido
    $idioma = 'PT';
    $valor = '4900';
    $pedido = '0001';
    $descricao = 'Assinatura Contador Amigo';

    // - dados do pagamento
    $bandeira = 'visa';
    $forma_pagamento = '1';
    $parcelas = '1';
    $autorizar = '1';
    $capturar = 'true';

    // - dados adicionais
    $campo_livre = 'aaa';


    // Monta a variável com os dados para postagem
    $request = 'identificacao=' . $identificacao;
    $request .= '&modulo=' . $modulo;
    $request .= '&operacao=' . $operacao;
    $request .= '&ambiente=' . $ambiente;

    $request .= '&bin_cartao=' . $bin_cartao;

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

    $nodeDadosPedido = $nodeTransacao->getElementsByTagName('dados-pedido')->item(0);
    if ($nodeDadosPedido != '') {
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

    $nodeURLAutenticacao = $nodeTransacao->getElementsByTagName('url-autenticacao')->item(0);
    $retorno_url_autenticacao = $nodeURLAutenticacao->nodeValue;
}

// Se não ocorreu erro exibe parâmetros
if ($retorno_codigo_erro == '') {
    $_SESSION['tid'] = $retorno_tid;

    echo '<b> TRANSAÇÃO </b><br />';
    echo '<b>Código de identificação do pedido (TID): </b>' . $retorno_tid . '<br />';
    echo '<b>Número do pedido (numero): </b>' . $retorno_pedido . '<br />';
    echo '<b>Valor do pedido (valor): </b>' . $retorno_valor . '<br />';
    echo '<b>Moeda do pedido (moeda): </b>' . $retorno_moeda . '<br />';
    echo '<b>Data e hora do pedido (data-hora): </b>' . $retorno_data_hora . '<br />';
    echo '<b>Descrição do pedido (descricao): </b>' . $retorno_descricao . '<br />';
    echo '<b>Idioma do pedido (idioma): </b>' . $retorno_idioma . '<br />';
    echo '<b>Bandeira (bandeira): </b>' . $retorno_bandeira . '<br />';
    echo '<b>Forma de pagamento (produto): </b>' . $retorno_produto . '<br />';
    echo '<b>Número de parcelas (parcelas): </b>' . $retorno_parcelas . '<br />';
    echo '<b>Status do pedido (status): </b>' . $retorno_status . '<br />';
    echo '<b>URL para autenticação (url-autenticacao): </b><a href="' . $retorno_url_autenticacao . '">' . $retorno_url_autenticacao . '</a><br />';
} else {
    echo '<b>Erro: </b>' . $retorno_codigo_erro . '<br />';
    echo '<b>Mensagem: </b>' . $retorno_mensagem_erro . '<br />';
}
?>

