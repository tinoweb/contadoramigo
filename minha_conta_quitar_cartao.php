<?
//AUMENTANDO TIMEOUT
ini_set('mysql.connect_timeout', 30000);
ini_set('default_socket_timeout', 30000);

include "conect.php";
include "session.php";
include "classes/config.php";
include "classes/pagamento.php";

?>
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

function retornaNumeroCartaoMascara($strNumeroCartao)
{
	$mascaraCartao = "";
	for($i=0; $i<strlen($strNumeroCartao); $i++)
	{
		if($i>=6 && $i<=11)
		{
			$mascaraCartao .= "*";
		}else
		{
			$mascaraCartao .= substr($strNumeroCartao, $i, 1);
		}
	}
	return $mascaraCartao;
}

if(!isset($_SESSION['n_empresasVinculadas']))
{
	$rsEmpresasUsuario = mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa d ON l.id = d.id WHERE l.idUsuarioPai = " . $_SESSION['id_userSecaoMultiplo'] . " AND d.ativa = 1");
	$_SESSION['n_empresasVinculadas'] = mysql_num_rows($rsEmpresasUsuario);
}

$sql_meus_dados = "
				SELECT 
					l.id
					, l.status
					, l.email
					, l.assinante
				FROM 
					login l
				WHERE 
					l.id='" . $_SESSION["id_userSecaoMultiplo"] . "'
				";
$resultado_meus_dados = mysql_query($sql_meus_dados)
or die (mysql_error());
$linha_meus_dados = mysql_fetch_array($resultado_meus_dados);

if($linha_meus_dados['status'] == 'cancelado' || $linha_meus_dados['status'] == 'demoInativo')
{
	// EM 08/11/2013 - DEMO INATIVO deve perdoar os pagamentos anteriores ...
	mysql_query("UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento = 'não pago'");
	// EM 08/11/2013 - ... e gerar um novo para a data corrente
	
	$perdoando_pagamentos = mysql_affected_rows();

	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: ".$perdoando_pagamentos." PAGAMENTOS PERDOADOS')");

	// CHECANDO SE JÁ EXISTE HISTÓRICO VENCIDO
	$sqlChecaVencido = "SELECT * FROM historico_cobranca WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento='vencido' LIMIT 0,1";
	$resultadoChecaVencido = mysql_query($sqlChecaVencido)
	or die (mysql_error());

	$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m'),date('d'),date('Y'))));
	
	if(mysql_num_rows($resultadoChecaVencido) <= 0)
	{
		// INSERE UM NOVO VENCIDO NA DATA DE HOJE PARA EFETUAR A COBRANÇA
		mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '" . $dataPagamento . "', 'vencido')");
	
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: NOVO PAGAMENTO ".$dataPagamento." GERADO')");	
	}
}

$filtro_pagamentos = "'vencido','não pago'";

if(isset($_GET['atrasados']) || isset($_GET['reativar_conta']))
{
	$filtro_pagamentos = "'vencido','não pago'";
}
if(isset($_GET['avencer']))
{
	$filtro_pagamentos = "'a vencer'";
}

$sql_pagamentos_cartao = "SELECT 
							h.id
							, h.status_pagamento
							, h.idHistorico
							, h.data_pagamento
							, d.forma_pagameto
							, d.numero_cartao
							, l.email
							, l.assinante
							, l.nome
							, l.status
						FROM 
							login l
						INNER JOIN 
							historico_cobranca h ON l.id = h.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
						WHERE 
							 h.status_pagamento IN (".$filtro_pagamentos.")
							 AND l.id='" . $linha_meus_dados["id"] . "'";
		
$rs_pagamentos_cartao = mysql_query($sql_pagamentos_cartao)
or die (mysql_error());

// TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
$total_devendo = mysql_num_rows($rs_pagamentos_cartao);

// LOG DE ACESSOS
mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: LOCALIZADOS ".$total_devendo." PAGAMENTO(s) a vencer, vencido ou não pago')");

// RECUPERA PLANO
$sql_plano = "SELECT plano FROM dados_cobranca WHERE id = '" . $linha_meus_dados["id"] . "'";
$rs_plano = mysql_query($sql_plano);
$linha_plano = mysql_fetch_array($rs_plano);
$plano_usuario = $linha_plano['plano'];

$Config = new Config(); //OBJETO DE CONFIGURAÇÃO
$plano_meses = $Config->verMeses($plano_usuario); //RECUPERA MESES DO PLANO
$plano_valor = $Config->verValor($plano_usuario); // RECUPERA VALOR DO PLANO

$Pagamento = new Pagamento(); //OBJETO DOS PAGAMENTOS

//CALCULA VALOR A PAGAR
$valor_pago = (int)($Config->calcularValorEmpresas($plano_valor, $_SESSION['n_empresasVinculadas']));

//MULTIPLICA VALOR POR 100, VARIÁVEL USADA APENAS PARA TRANSAÇÃO COM A CIELO
$valor_a_cobrar = $valor_pago;

//CALCULA QUANTOS MESES SERÃO SALVOS NO PRÓXIMO PAGAMENTO
$meses_a_somar = $Pagamento->calcularMesesSomar($plano_meses, $total_devendo);


//MAL - TESTE 
echo 'Meses a somar: '.$meses_a_somar; echo '<br>';
echo 'Plano: '.$plano_meses; echo '<br>';
echo 'Devendo: '.$total_devendo; echo '<br>';



//exit();

// SE HOUVER SOMENTE UM PAGAMENTO A SER FEITO
if($plano_meses == 1)
{	
	// CHECANDO SE EMPRESAS FORAM CADASTRADAS ANTES DO VENCIMENTO DE ALGUM PAGAMENTO NÃO FEITO
	$empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $linha_meus_dados["id"] . " AND (e.ativa = 1)"));
	
	// DEFININDO O TOTAL A SER COBRADO PADRÃO
	$pagamentos_cartao = mysql_fetch_array($rs_pagamentos_cartao);

	// MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	$idHistoricoAtualizar = "('" . $pagamentos_cartao['idHistorico'] .  "')";

	$status = $pagamentos_cartao['status'];
	$numeroCartao = ($pagamentos_cartao['numero_cartao'] ? retornaNumeroCartaoMascara($pagamentos_cartao['numero_cartao']) : '');
	$forma_pagamento_assinante = $pagamentos_cartao['forma_pagameto'];
	$assinante = $pagamentos_cartao['assinante'];
	$razao_social = $pagamentos_cartao['nome'];
	$email_assinante = $pagamentos_cartao['email'];	
}
else
{
	// MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	$idHistoricoAtualizar = "('";
	$arrTestes = array();
	$idHistoricoAtualizarCount = 0;
	while($pagamentos_cartao = mysql_fetch_array($rs_pagamentos_cartao))
	{
		if ($idHistoricoAtualizarCount >= $plano_meses)
		{
			//Não irá atualizar histórico se total de meses em aberto for maior do que o plano
			break;
		}

		// CHECANDO SE EMPRESAS FORAM CADASTRADAS ANTES DO VENCIMENTO DE ALGUM PAGAMENTO NÃO FEITO
		$empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id, DATEDIFF('" . date("Y-m-d",strtotime($pagamentos_cartao['data_pagamento'])) . "',data_desativacao) FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $linha_meus_dados["id"] . " AND l.data_inclusao <= '" . ($pagamentos_cartao['data_pagamento']) . "' AND (e.ativa = 1 OR (e.ativa=0 AND DATEDIFF('" . date("Y-m-d",strtotime($pagamentos_cartao['data_pagamento'])) . "',data_desativacao) < 5))"));		
		
		if($empresas_para_cobrar > 0)
		{
			array_push($arrTestes,array('idPagto'=>$pagamentos_cartao['id'],'qtd_empresas'=>$empresas_para_cobrar,'data_pagamento'=>$pagamentos_cartao['data_pagamento']));
		}

		$status = $pagamentos_cartao['status'];
		$numeroCartao = ($pagamentos_cartao['numero_cartao'] ? retornaNumeroCartaoMascara($pagamentos_cartao['numero_cartao']) : '');
		$forma_pagamento_assinante = $pagamentos_cartao['forma_pagameto'];
		$assinante = $pagamentos_cartao['assinante'];
		$razao_social = $pagamentos_cartao['nome'];
		$email_assinante = $pagamentos_cartao['email'];
		$idHistoricoAtualizar .= $pagamentos_cartao['idHistorico'] . "','";		

		$idHistoricoAtualizarCount ++;
	}

	$idHistoricoAtualizar .= "')";	
	$idHistoricoAtualizar  = str_replace(",''","",$idHistoricoAtualizar);
}

// LOG DE ACESSOS
mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: PAGAMENTO(s) A SER(em) FEITO(s) " . mysql_real_escape_string($idHistoricoAtualizar) . "')");

// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5

// ########################################################################################################
ini_set('allow_url_fopen', 1); // Ativa a diretiva 'allow_url_fopen'

function getURL($vId, $vValor, $vTitular, $vNumero, $vValidade, $vCodigo, $vBandeira)
{
	$NomeTitular = $vTitular;
	$NumeroCartao = $vNumero;
	$DataValidade = $vValidade;
	$Codigo = $vCodigo;
	$FormaPagamento = $vBandeira;

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
    $valor = $vValor;
    $pedido = $vId . date("dmYHis");
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

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	// EM 25/02/2015 - Mudando o time out da conexão
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 0); //timeout in seconds

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$sqlCompPagamento = "SELECT * FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "' LIMIT 0, 1";
$resultadoCompPagamento = mysql_query($sqlCompPagamento)
or die (mysql_error());
$linhaCompPagamento=mysql_fetch_array($resultadoCompPagamento);

$pTitular = $linhaCompPagamento["nome_titular"];
$pNumero = $linhaCompPagamento["numero_cartao"];
$pValidade = date('Ym',strtotime($linhaCompPagamento["data_validade"]));
$pCodigo = $linhaCompPagamento["codigo_seguranca"];
$pBandeira = $linhaCompPagamento['forma_pagameto'];

//$XMLtransacao = getURL($linha_meus_dados['id'], $valor_a_cobrar);
// LOG DE ACESSOS
mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: Dados da cobrança: titular - " . $pTitular . " / nº - " . $pNumero . " / validade - " . $pValidade . " / bandeira - " . $pBandeira . "')");

// LOG DE ACESSOS
mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: XML Gerado - " . mysql_real_escape_string($XMLtransacao) . "')");

// Carrega o XML
$objDom = new DomDocument();

$loadDom = $objDom->loadXML($XMLtransacao);

$nodeErro = $objDom->getElementsByTagName('erro')->item(0);
if ($nodeErro != '') 
{
    $nodeCodigoErro = $nodeErro->getElementsByTagName('codigo')->item(0);
    $retorno_codigo_erro = $nodeCodigoErro->nodeValue;

    $nodeMensagemErro = $nodeErro->getElementsByTagName('mensagem')->item(0);
    $retorno_mensagem_erro = $nodeMensagemErro->nodeValue;
}

$nodeTransacao = $objDom->getElementsByTagName('transacao')->item(0);
if ($nodeTransacao != '') 
{
    $nodeTID = $nodeTransacao->getElementsByTagName('tid')->item(0);
    $retorno_tid = $nodeTID->nodeValue;

    $nodePAN = $nodeTransacao->getElementsByTagName('pan')->item(0);
    $retorno_pan = $nodePAN->nodeValue;

    $nodeDadosPedido = $nodeTransacao->getElementsByTagName('dados-pedido')->item(0);
    if ($nodeTransacao != '') 
    {
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
    if ($nodeFormaPagamento != '') 
    {
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
    if ($nodeAutenticacao != '') 
    {
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
    if ($nodeAutorizacao != '') 
    {
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
	
	require_once "class/bean.php";
	require_once "class/cielo.php";
	require_once 'class/pagamento-cartao.php';

	//MAL Pega o token de pagamento cadastrado para o usuário
	$consulta_token_usuario = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$linha_meus_dados["id"]."' ");
	$dados_cartao_user=mysql_fetch_array($consulta_token_usuario);							

	//Pega o token do usuário
	$bandeira = $dados_cartao_user['forma_pagameto'];
	$numeroCartao = $dados_cartao_user['numero_cartao'];
	$codigo = $dados_cartao_user['codigo_seguranca'];
	$nome = $dados_cartao_user['nome_titular'];
	$data_validade = $dados_cartao_user['data_validade'];
	$data_validade = explode('-', $data_validade);
	$data_validade = $data_validade[0].$data_validade[1];
	$valor_pago = $valor_a_cobrar;
	//Cria um objeto que armazenará os dados do cartao para enviar ao pagamento

	$mensalidade = $valor_a_cobrar;

	$cartao = new Dados_cartao();
	$cartao->setNome($nome);//nome do assinante como está no cartão
	$cartao->setNumero_cartao($numeroCartao);//Número atual é o numero do ambiente de teste
	$cartao->setValidade($data_validade);//Validade no formato AAAAMM
	$cartao->setCodigo_seguranca($codigo);//Código de segurança,
	$cartao->setValor($mensalidade);//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
	$cartao->setBandeira($bandeira);//Seta a bandeira 

	//Cria um objeto para o pagamento
	$pagamento = new Pagamento_cartao();

	//Tenta realizar a cobrança
	$pagamento->pagarComToken($cartao);

	$data_pagamento_cartao = $pagamento->getData();

	// exit();						
	################ Fim do trecho para pagar com o token de pagamento ######################
	#########################################################################################


	#########################################################################################
	################ Início do trecho do tratamento do retorno ##############################


	$retorno_codigo_erro = $pagamento->getCodigoErro();
	$retorno_codigo_autorizacao = $pagamento->getStatus();
	$retorno_tid = $pagamento->getTid();

	$pagementos_pendentes = mysql_query("SELECT * FROM tabela WHERE coluna = '".$valor."' ");
	$objeto=mysql_fetch_array($pagementos_pendentes);


	$XmlResposta = $pagamento->getXmlRetorno();

	$inserir_log_cartao = mysql_query("INSERT INTO `log_cartao`(`id`, `id_user`, `data`, `resultado`) VALUES ( '','".$linha_meus_dados["id"]."','".date("Y-m-d H:m:s")."','".$XmlResposta."' )");
	$log_cartao=mysql_fetch_array($inserir_log_cartao);

	if( $retorno_codigo_erro == '' &&  $retorno_codigo_autorizacao == '')
		$retorno_codigo_autorizacao = 5;

// LOG DE ACESSOS
mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: Retorno código do erro: " . $retorno_codigo_erro . " | Retorno código autorização: " . $retorno_codigo_autorizacao . "')");

//TESTE COM RETORNO
// $retorno_codigo_erro = '';
// $retorno_codigo_autorizacao = "6";
// $retorno_tid  = '123';

//echo "retorno_codigo_erro=" . $retorno_codigo_erro . "<br>";
//echo "retorno_codigo_autorizacao=" . $retorno_codigo_autorizacao . "<br>";

// Se não ocorreu erro exibe parâmetros
if (($retorno_codigo_erro == '') && ($retorno_codigo_autorizacao != '5')) 
{
	//Código 5 equivale a transação não autorizada
	if(is_null($retorno_tid) || $retorno_tid == "")
	{
		// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
		$sqlup = "INSERT INTO relatorio_cobranca (id, data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao) VALUES ('" . $linha_meus_dados["id"] . "', '" . date('Y-m-d') . "', '" . $forma_pagamento_assinante . "', '2.4', 'não enviado', '', " . $valor_pago . ".00, '" . $numeroCartao . "')";
		$resultadoup = mysql_query($sqlup)
		or die (mysql_error());
	
		// ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS FILTRADOS NO INICIO DO LOOP (vencidos e não pagos)
		$sqlup = "UPDATE historico_cobranca SET status_pagamento='pendente', envio_email='enviado', tipo_cobranca='" . $forma_pagamento_assinante . "' WHERE id='" . $linha_meus_dados['id'] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";					

		$resultadoup = mysql_query($sqlup)
		or die (mysql_error());		

		// PEGANDO O ULTIMO PAGO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
		$linhaData = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='" . $linha_meus_dados['id'] . "' LIMIT 0,1"));		

		$dataPagamento=date('Y-m-d',strtotime($linhaData["data_pagamento"] . " + " . $meses_a_somar . " month"));
		if($linha_meus_dados['status'] == 'cancelado' || $linha_meus_dados['status'] == 'demoInativo')
		{
			$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m') + $meses_a_somar, date('d'),date('Y'))));
		}	
		
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: USUARIO TENTOU EFETUAR PAGAMENTO DE " . $valor_pago . ".00 COM " . $forma_pagamento_assinante . "')");

		// CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
		$sqlChecaAVencer = "SELECT * FROM historico_cobranca WHERE id='" . $linha_meus_dados['id'] . "' AND status_pagamento='a vencer' 
							LIMIT 0,1";
		$resultadoChecaAVencer = mysql_query($sqlChecaAVencer)
		or die (mysql_error());
				
		if(mysql_num_rows($resultadoChecaAVencer) <= 0)
		{				
			// SE NÂO FOR LOCALIZADO PAGAMENTO A VENCER, INSERE NO HISTORICO COM UM MES PARA FRENTE
			// INSERINDO O NOVO REGISTRO DE HISTORICO DE COBRANÇA A VENCER
			mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '$dataPagamento', 'a vencer')");
		}
												
		$sql = "UPDATE login SET status='ativo' WHERE idUsuarioPai='" . $linha_meus_dados['id'] . "'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
			
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: APESAR DO PROBLEMA COM A TENTATIVA DE COBRANÇA, O USUARIO FOI ATIVADO')");
			
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: ERRO: TID NULO')");

		if($retorno_codigo_erro == '') 
		{
			$retorno_codigo_erro = 'tidnulo';
		}

		header('Location: minha_conta.php?erro_cartao=' .  $retorno_codigo_erro);
	}
	else
	{
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: NÃO OCORREU ERRO - EFETUAR PAGAMENTO')");	

		// PEGANDO DADOS DE COBRANÇA DO USUARIO
		$sql = "SELECT * FROM dados_cobranca WHERE id='" . $linha_meus_dados['id'] . "' ORDER BY id ASC LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		$linha=mysql_fetch_array($resultado);

		//RECUPERA ÚLTIMO PAGAMENTO PENDENTE
		$ultimo_pagamento_pendente = $Pagamento->retornarUltimoPagamentoASomar($linha_meus_dados["id"]);	

		//CALCULA DATA DO PRÓXIMO PAGAMENTO PAGAMENTO JÁ DESCONTANDO OS MESES COM DIVIDA
		$dataPagamento = $Pagamento->calcularProximoPagamento($linha_meus_dados['status'], $ultimo_pagamento_pendente, $meses_a_somar);

		// ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS FILTRADOS NO INICIO DO LOOP (vencidos e não pagos)
		$sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='" . $forma_pagamento_assinante . "',  envio_email='enviado' WHERE id='" . $linha_meus_dados['id'] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";
		$resultadoup = mysql_query($sqlup)
		or die (mysql_error());
		
		// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
		$sqlup = "INSERT INTO relatorio_cobranca (id, data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao) VALUES ('" . $linha_meus_dados['id'] . "', '" . date('Y-m-d') . "', '" . $forma_pagamento_assinante . "', '2.1', 'enviado', '" . $retorno_tid . "', " . $valor_pago . ".00,'" . $numeroCartao . "')";
		
		$resultadoup = mysql_query($sqlup)
		or die (mysql_error());	
			
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: USUARIO EFETUOU PAGAMENTO DE " . $valor_pago . ".00 COM " . $forma_pagamento_assinante . "')");
					
		// CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
		$sqlChecaAVencer = "SELECT idHistorico FROM historico_cobranca WHERE id='" . $linha_meus_dados['id'] . "' AND status_pagamento='a vencer' 
							LIMIT 0,1";
		$resultadoChecaAVencer = mysql_query($sqlChecaAVencer)
		or die (mysql_error());

		if ((mysql_num_rows($resultadoChecaAVencer) <= 0))
		{			
			mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: INSERINDO A VENCER NA DATA DE " . $dataPagamento . "')");
		
			// SE NÂO FOR LOCALIZADO PAGAMENTO A VENCER, INSERE NO HISTORICO COM UM MES PARA FRENTE
			// INSERINDO O NOVO REGISTRO DE HISTORICO DE COBRANÇA A VENCER
			
			mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '" . $dataPagamento . "', 'a vencer')");			
		}
		else
		{
			$linhaChecaAVencer = mysql_fetch_array($resultadoChecaAVencer);

			//ATUALIZA PAGAMENTO 'A VENCER' COM A NOVA DATA DO PAGAMENTO
			mysql_query("UPDATE historico_cobranca SET data_pagamento='" . $dataPagamento . "' WHERE idHistorico=" . $linhaChecaAVencer["idHistorico"]);
		}

		if($status == 'demo' || $status == 'demoInativo' || $status == 'inativo' || $status == 'cancelado')
		{
			$sql = "UPDATE login SET status='ativo' WHERE idUsuarioPai='" . $linha_meus_dados['id'] . "'";
			$resultado = mysql_query($sql)
			or die (mysql_error());	
			
			// LOG DE ACESSOS
			mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: USUARIO FOI ATIVADO')");
	
			$_SESSION['status_userSecao'] = 'ativo';
	
			if($status == 'demo' || $status == 'demoInativo')
			{
				if($linha_meus_dados['id'] != 1581 && $linha_meus_dados['id'] != 9)
				{
					// INSERE NA TABELA DE METRICAS
					mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $linha_meus_dados['id'] . ",'ativado','" . date('Y-m-d') . "')");
				}	
			}
			else
			{
				if($linha_meus_dados['id'] != 1581 && $linha_meus_dados['id'] != 9)
				{ 
					// INSERE NA TABELA DE METRICAS
					mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $linha_meus_dados['id'] . ",'recuperado','" . date('Y-m-d') . "')");
				}
			}
	
			$Assinante = $assinante;
			$AssinanteExplode = explode(" ", $Assinante);
			$Razao = $razao_social;
			$assuntoMail = "Novo Assinante";
			$emailuser = "secretaria@contadoramigo.com.br";
		}
		
		/* Redirecionando para a página de sucesso */		
		if(isset($_GET["reativar_conta"]))
		{
			$ativo = "&ativo";
		}
		header('Location: minha_conta.php?sucesso' . $ativo);
	}
}
else
{
	if($retorno_codigo_erro == '')
	{
		$retorno_codigo_erro = 'invalido';
	}

	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: TENTATIVA DE PAGAMENTO COM ERRO')");

	header('Location: minha_conta.php?erro_cartao=' .  $retorno_codigo_erro);
}

//echo "idHistoricoAtualizar=" .  $idHistoricoAtualizar . "<br>";	
//echo "plano_meses=" .  $plano_meses . "<br>";
//echo "total_devendo=" .  $total_devendo . "<br>";
//echo "ultimo_pagamento_pendente=" .  $ultimo_pagamento_pendente . "<br>";	
//echo "meses_a_somar=" .  $meses_a_somar . "<br>";
//echo "dataPagamento=" .  $dataPagamento . "<br>";
//echo "valor_pago=" .  $valor_pago . "<br>";
?>