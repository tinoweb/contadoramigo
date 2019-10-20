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
	    for ($i = 0; $i < strlen($strNumeroCartao); $i++) {
	        if ($i >= 6 && $i <= 11) {
	            $mascaraCartao .= "*";
	        } else {
	            $mascaraCartao .= substr($strNumeroCartao, $i, 1);
	        }
	    }
	    return $mascaraCartao;
	}

	if (!isset($_SESSION['n_empresasVinculadas'])) {
	    $rsEmpresasUsuario                = mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa d ON l.id = d.id WHERE l.idUsuarioPai = " . $_SESSION['id_userSecaoMultiplo'] . " AND d.ativa = 1");
	    $empresas_n = mysql_num_rows($rsEmpresasUsuario);
	    if( $empresas_n < 1 )
	    	$empresas_n = 1;
	    $_SESSION['n_empresasVinculadas'] = $empresas_n;
	    echo $empresas_n;
	}
	else{
		if ($_SESSION['n_empresasVinculadas'] < 1) {
			$_SESSION['n_empresasVinculadas'] = 1;
		}
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
	$resultado_meus_dados = mysql_query($sql_meus_dados) or die(mysql_error());
	$linha_meus_dados = mysql_fetch_array($resultado_meus_dados);

	if ($linha_meus_dados['status'] == 'cancelado' || $linha_meus_dados['status'] == 'demoInativo') {
	    // EM 08/11/2013 - DEMO INATIVO deve perdoar os pagamentos anteriores ...
	    mysql_query("UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento = 'não pago'");
	    // EM 08/11/2013 - ... e gerar um novo para a data corrente
	    
	    $perdoando_pagamentos = mysql_affected_rows();
	    
	    // LOG DE ACESSOS
	    mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: " . $perdoando_pagamentos . " PAGAMENTOS PERDOADOS')");
	    
	    // CHECANDO SE JÁ EXISTE HISTÓRICO VENCIDO
	    $sqlChecaVencido = "SELECT * FROM historico_cobranca WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento='vencido' LIMIT 0,1";
	    $resultadoChecaVencido = mysql_query($sqlChecaVencido) or die(mysql_error());
	    
	    $dataPagamento = date('Y-m-d', (mktime(0, 0, 0, date('m'), date('d'), date('Y'))));
	    
	    if (mysql_num_rows($resultadoChecaVencido) <= 0) {
	        // INSERE UM NOVO VENCIDO NA DATA DE HOJE PARA EFETUAR A COBRANÇA
	        mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '" . $dataPagamento . "', 'vencido')");
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: NOVO PAGAMENTO " . $dataPagamento . " GERADO')");
	    }
	}

	$filtro_pagamentos = "'vencido','não pago'";

	if (isset($_GET['atrasados']) || isset($_GET['reativar_conta'])) {
	    $filtro_pagamentos = "'vencido','não pago'";
	}
	if (isset($_GET['avencer'])) {
	    $filtro_pagamentos = "'a vencer'";
	}
	if( $linha_meus_dados['status'] == 'inativo' ){
		$filtro_pagamentos = "'vencido','não pago'";	
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
								 h.status_pagamento IN (" . $filtro_pagamentos . ")
								 AND l.id='" . $linha_meus_dados["id"] . "'";

	$rs_pagamentos_cartao = mysql_query($sql_pagamentos_cartao) or die(mysql_error());

	// TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
	$total_devendo = mysql_num_rows($rs_pagamentos_cartao);

	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: LOCALIZADOS " . $total_devendo . " PAGAMENTO(s) a vencer, vencido ou não pago')");

	// RECUPERA PLANO E VERIFICA SE E PRÊMIO PARA PEGAR O CODIGO CONTADOR. 
	$sql_plano     = "SELECT plano, tipo_plano, contadorId FROM dados_cobranca WHERE id = '" . $linha_meus_dados["id"] . "'";
	$rs_plano      = mysql_query($sql_plano);
	$linha_plano   = mysql_fetch_array($rs_plano);
	$plano_usuario = $linha_plano['plano'];
	$tipo_Plano = $linha_plano['tipo_plano'];
	$contadorId = $linha_plano['contadorId'];

	$Config      = new Config(); //OBJETO DE CONFIGURAÇÃO
	$plano_meses = $Config->verMeses($plano_usuario); //RECUPERA MESES DO PLANO
	$plano_valor = $Config->verValor($plano_usuario,$tipo_Plano); // RECUPERA VALOR DO PLANO

	$Pagamento = new Pagamento(); //OBJETO DOS PAGAMENTOS

	//CALCULA VALOR A PAGAR
	$valor_pago = (int) ($Config->calcularValorEmpresas($plano_valor, $_SESSION['n_empresasVinculadas']));

	//MULTIPLICA VALOR POR 100, VARIÁVEL USADA APENAS PARA TRANSAÇÃO COM A CIELO
	$valor_a_cobrar = $valor_pago;

	//CALCULA QUANTOS MESES SERÃO SALVOS NO PRÓXIMO PAGAMENTO
	$meses_a_somar = $Pagamento->calcularMesesSomar($plano_meses, $total_devendo);


	//MAL - TESTE 
	// echo 'Meses a somar: ' . $meses_a_somar;
	// echo '<br>';
	// echo 'Plano: ' . $plano_meses;
	// echo '<br>';
	// echo 'Devendo: ' . $total_devendo;
	// echo '<br>';



	//exit();

	// SE HOUVER SOMENTE UM PAGAMENTO A SER FEITO
	if ($plano_meses == 1) {
	    // CHECANDO SE EMPRESAS FORAM CADASTRADAS ANTES DO VENCIMENTO DE ALGUM PAGAMENTO NÃO FEITO
	    $empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $linha_meus_dados["id"] . " AND (e.ativa = 1)"));
	    
	    // DEFININDO O TOTAL A SER COBRADO PADRÃO
	    $pagamentos_cartao = mysql_fetch_array($rs_pagamentos_cartao);
	    
	    // MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	    $idHistoricoAtualizar = "('" . $pagamentos_cartao['idHistorico'] . "')";
	    
	    $status                    = $pagamentos_cartao['status'];
	    $numeroCartao              = ($pagamentos_cartao['numero_cartao'] ? retornaNumeroCartaoMascara($pagamentos_cartao['numero_cartao']) : '');
	    $forma_pagamento_assinante = $pagamentos_cartao['forma_pagameto'];
	    $assinante                 = $pagamentos_cartao['assinante'];
	    $razao_social              = $pagamentos_cartao['nome'];
	    $email_assinante           = $pagamentos_cartao['email'];
	} else {
	    // MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	    $idHistoricoAtualizar      = "('";
	    $arrTestes                 = array();
	    $idHistoricoAtualizarCount = 0;
	    while ($pagamentos_cartao = mysql_fetch_array($rs_pagamentos_cartao)) {
	        if ($idHistoricoAtualizarCount >= $plano_meses) {
	            //Não irá atualizar histórico se total de meses em aberto for maior do que o plano
	            break;
	        }
	        
	        // CHECANDO SE EMPRESAS FORAM CADASTRADAS ANTES DO VENCIMENTO DE ALGUM PAGAMENTO NÃO FEITO
	        $empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id, DATEDIFF('" . date("Y-m-d", strtotime($pagamentos_cartao['data_pagamento'])) . "',data_desativacao) FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $linha_meus_dados["id"] . " AND l.data_inclusao <= '" . ($pagamentos_cartao['data_pagamento']) . "' AND (e.ativa = 1 OR (e.ativa=0 AND DATEDIFF('" . date("Y-m-d", strtotime($pagamentos_cartao['data_pagamento'])) . "',data_desativacao) < 5))"));
	        
	        if ($empresas_para_cobrar > 0) {
	            array_push($arrTestes, array(
	                'idPagto' => $pagamentos_cartao['id'],
	                'qtd_empresas' => $empresas_para_cobrar,
	                'data_pagamento' => $pagamentos_cartao['data_pagamento']
	            ));
	        }
	        
	        $status                    = $pagamentos_cartao['status'];
	        $numeroCartao              = ($pagamentos_cartao['numero_cartao'] ? retornaNumeroCartaoMascara($pagamentos_cartao['numero_cartao']) : '');
	        $forma_pagamento_assinante = $pagamentos_cartao['forma_pagameto'];
	        $assinante                 = $pagamentos_cartao['assinante'];
	        $razao_social              = $pagamentos_cartao['nome'];
	        $email_assinante           = $pagamentos_cartao['email'];
	        $idHistoricoAtualizar .= $pagamentos_cartao['idHistorico'] . "','";
	        
	        $idHistoricoAtualizarCount++;
	    }
	    
	    $idHistoricoAtualizar .= "')";
	    $idHistoricoAtualizar = str_replace(",''", "", $idHistoricoAtualizar);
	}

	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: PAGAMENTO(s) A SER(em) FEITO(s) " . mysql_real_escape_string($idHistoricoAtualizar) . "')");

	// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5

	// ########################################################################################################
	ini_set('allow_url_fopen', 1); // Ativa a diretiva 'allow_url_fopen'


	
	$XMLtransacao = "";
	$retorno_codigo_erro = "";
	$retorno_mensagem_erro = "";
	$retorno_tid = "";
	$retorno_pan = "";
	$retorno_pedido = "";
	$retorno_valor = "";
	$retorno_moeda = "";
	$retorno_data_hora = "";
	$retorno_descricao = "";
	$retorno_idioma = "";
	$retorno_bandeira = "";
	$retorno_produto = "";
	$retorno_parcelas = "";
	$retorno_status = "";
	$retorno_codigo_autenticacao = "";
	$retorno_mensagem_autenticacao = "";
	$retorno_data_hora_autenticacao = "";
	$retorno_valor_autenticacao = "";
	$retorno_eci_autenticacao = "";
	$retorno_codigo_autorizacao = "";
	$retorno_mensagem_autorizacao = "";
	$retorno_data_hora_autorizacao = "";
	$retorno_valor_autorizacao = "";
	$retorno_lr_autorizacao = "";
	$retorno_arp_autorizacao = "";
	$retorno_url_autenticacao = "";

	$erros_cartao = array(
    	"5" => "transacao_nao_autorizada",
    	"6" => "transacao_autorizada",
    	"52" => "token_nao_encontrado",
    	"999" => "falha_sistema",
    );

										
	/*
	####################################################################
	INÍCIO DO TRATAMENTO DO RETORNO DE ERRO DO COMPONENTE DE PAGAMENTO
	####################################################################
	*/	
	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'RETORNO_CODIGO_ERRO: " . $retorno_codigo_erro . " - RETORNO_CODIGO_AUTORIZACAO: ".$retorno_codigo_autorizacao."')");

	

	require_once "class/bean.php";
	require_once "class/cielo.php";
	require_once 'class/pagamento-cartao.php';

	#########################################################################################
	############### Trecho para pagar com o Token ###########################################

	//MAL Pega o token de pagamento cadastrado para o usuário
	$consulta_token_usuario = mysql_query("SELECT * FROM token_pagamento WHERE id_user = '".$linha_meus_dados["id"]."' ");
	$dados_cartao_user=mysql_fetch_array($consulta_token_usuario);

	//Pega o token do usuário
	$token = $dados_cartao_user['token'];
	$bandeira = $dados_cartao_user['bandeira'];
	$numeroCartao = $dados_cartao_user['numero_cartao'];
	
	if ( isset ( $_GET['atrasados'] ) && ( $linha_meus_dados['status'] == 'ativo' || $linha_meus_dados['status'] == 'inativo')   ) {
		
		$juros_consulta_mes = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico IN ".$idHistoricoAtualizar." ");
		$objeto_juros=mysql_fetch_array($juros_consulta_mes);

		$data_juros = $objeto_juros['data_pagamento'];

		function geraTimestamp($data) {
			$partes = explode('-', $data);
			return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
			}

		// Usa a função criada e pega o timestamp das duas datas:
		$time_inicial = geraTimestamp($data_juros);
		$time_final = geraTimestamp(date("Y-m-d"));
		// Calcula a diferença de segundos entre as duas datas:
		$diferenca = $time_final - $time_inicial; // 19522800 segundos
		// Calcula a diferença de dias
		$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
		// echo $dias;
		if( $dias > 0 && $dias < 90){
			if( intval( ( $dias - 1 ) / 30 ) == 0 ){
				$juros = 1;			
			}else{
				$juros = intval( ( $dias - 1 ) / 30 ) + 1;
			}
			$juros = $juros * 0.02;
		}
		else{
			$juros = 0;
		}

		// echo $juros;

		// return;

		$valor_a_cobrar = $valor_a_cobrar + ($valor_a_cobrar * $juros);

	}
	// echo $idHistoricoAtualizar;


	$valor_pago = $valor_a_cobrar;
	$mensalidade = $valor_a_cobrar;
	$forma_pagamento_assinante = $dados_cartao_user['bandeira'];
	//Cria um objeto que armazenará os dados do cartao para enviar ao pagamento
	$cartao = new Dados_cartao();
	// $mensalidade = 1;
	$cartao->setValor($mensalidade);//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
	$cartao->setBandeira($bandeira);//Seta a bandeira 
	$cartao->setValorFinal($mensalidade);

	// echo $cartao->getValorFinal();

	$idHistorico = str_replace('\'', '', $idHistoricoAtualizar);
	$idHistorico = str_replace('(', '', $idHistorico);
	$idHistorico = str_replace(')', '', $idHistorico);
	$idHistorico = str_replace(',', '', $idHistorico);

	$consulta_id_historico = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico = '".$idHistorico."' ");
	$objeto_consulta_id_historico = mysql_fetch_array($consulta_id_historico);

	$idHistorico_aux = $objeto_consulta_id_historico['data_pagamento'];

	$idHistorico_aux = explode('-', $idHistorico_aux);

	$idHistorico = $idHistorico_aux[0].$idHistorico_aux[1];

	// echo $idHistorico;

	// return;

	// echo $idHistorico;

	
	// exit();


	// //Cria um objeto para o pagamento
	$pagamento = new Pagamento_cartao();
	//Define o token para a cobrança
	$pagamento->setToken($token);

	// echo $token;

	//Tenta realizar a cobrança
	$pagamento->pagarComToken($cartao);

	$data_pagamento_cartao = $pagamento->getData();

	// exit();
	//$XMLtransacao = getURL($linha_meus_dados['id'], $valor_a_cobrar);
	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: Dados da cobrança: titular - " .$dados_cartao_user['nome_titular']. " / nº - " . $dados_cartao_user['numero_cartao'] . " / bandeira - " . $dados_cartao_user['bandeira'] . "')");

	// LOG DE ACESSOS
	// mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: XML Gerado - " . mysql_real_escape_string($XMLtransacao) . "')");


	################ Fim do trecho para pagar com o token de pagamento ######################
	#########################################################################################


	#########################################################################################
	################ Início do trecho do tratamento do retorno ##############################


	$retorno_codigo_erro = $pagamento->getCodigoErro();
	$retorno_codigo_autorizacao = $pagamento->getStatus();
	$retorno_tid = $pagamento->getTid();

	// echo '<br>Codigo erro: '.$pagamento->getCodigoErro();
	// echo '<br>Status: '.$pagamento->getStatus();

	$XmlResposta = $pagamento->getXmlRetorno();

	$string_erro = $XmlResposta;
	$string_erro = explode('<autorizacao>', $string_erro);
	$string_erro = explode('</mensagem>', $string_erro[1]);
	$string_erro[0] = strip_tags($string_erro[0]);

	// echo $XmlResposta;

	mysql_query("insert into log_acessos (id_user, acao) VALUES ( '".$linha_meus_dados['id']."', 'RETORNO: ".$string_erro[0]."' )");

	// exit();

	$inserir_log_cartao = mysql_query("INSERT INTO `log_cartao`(`id`, `id_user`, `erro`, `retorno_codigo` , `resultado`) VALUES ( '','".$linha_meus_dados['id']."','".$retorno_codigo_erro."','".$retorno_codigo_autorizacao."','".$XmlResposta."' )");
	$log_cartao=mysql_fetch_array($inserir_log_cartao);

	if ( $linha_meus_dados['id'] == 9 )
		$retorno_codigo_autorizacao = 6;

	// Se não ocorreu erro exibe parâmetros
	if (($retorno_codigo_erro == '') && ($retorno_codigo_autorizacao != '5')) {
	    //Código 5 equivale a transação não autorizada
	    if (is_null($retorno_tid) || $retorno_tid == "") {
	        // INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
	        $sqlup = "INSERT INTO relatorio_cobranca (id,idHistorico ,data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao, tipo_plano, plano) VALUES ('" . $linha_meus_dados["id"] . "', '".$idHistorico."' ,'" . date('Y-m-d') . "', '" . $forma_pagamento_assinante . "', '2.4', 'não enviado', '', " . $valor_pago . ", '" . $numeroCartao . "', '".$tipo_Plano."', '".$plano_usuario."')";
	        $resultadoup = mysql_query($sqlup) or die(mysql_error());
	        
			// Pega o ultimo código do relatorio de cobrança.
			$idRelatorio = mysql_insert_id();
			
			// VERIFICA SE O PAGAMENTO E DE UMA CONTA PRÊMIO E GRAVA O CÓDIGO DO CONTADOR E DO RELATORIO DE COBRANCA.  
			if($tipo_Plano == 'P') {
				$qryUpdate = " INSERT INTO cobranca_contador (idRelatorio, contadorId) VALUE ('".$idRelatorio."', '".$contadorId."'); ";
				mysql_query($qryUpdate);
			}			
			
	        // ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS FILTRADOS NO INICIO DO LOOP (vencidos e não pagos)
	        $sqlup = "UPDATE historico_cobranca SET status_pagamento='pendente', envio_email='enviado', tipo_cobranca='" . $forma_pagamento_assinante . "' WHERE id='" . $linha_meus_dados['id'] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";
	        
	        $resultadoup = mysql_query($sqlup) or die(mysql_error());
	        
	        // PEGANDO O ULTIMO PAGO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
	        $linhaData = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='" . $linha_meus_dados['id'] . "' LIMIT 0,1"));
	        
	        $dataPagamento = date('Y-m-d', strtotime($linhaData["data_pagamento"] . " + " . $meses_a_somar . " month"));
	        if ($linha_meus_dados['status'] == 'cancelado' || $linha_meus_dados['status'] == 'demoInativo') {
	            $dataPagamento = date('Y-m-d', (mktime(0, 0, 0, date('m') + $meses_a_somar, date('d'), date('Y'))));
	        }
	        
	        // LOG DE ACESSOS

	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: USUARIO TENTOU EFETUAR PAGAMENTO DE " . $valor_pago . " COM " . $forma_pagamento_assinante . "')");
	        
	        mysql_query("insert into log_acessos (id_user, acao) VALUES ( '".$linha_meus_dados['id']."', 'RETORNO: ".$string_erro[0]."' )");

	        // CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
	        $sqlChecaAVencer = "SELECT * FROM historico_cobranca WHERE id='" . $linha_meus_dados['id'] . "' AND status_pagamento='a vencer' 
								LIMIT 0,1";
	        $resultadoChecaAVencer = mysql_query($sqlChecaAVencer) or die(mysql_error());
	        
	        if (mysql_num_rows($resultadoChecaAVencer) <= 0) {
	            // SE NÂO FOR LOCALIZADO PAGAMENTO A VENCER, INSERE NO HISTORICO COM UM MES PARA FRENTE
	            // INSERINDO O NOVO REGISTRO DE HISTORICO DE COBRANÇA A VENCER
	            mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '$dataPagamento', 'a vencer')");
	        }
	        
	        $sql = "UPDATE login SET status='ativo' WHERE idUsuarioPai='" . $linha_meus_dados['id'] . "'";
	        $resultado = mysql_query($sql) or die(mysql_error());
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: APESAR DO PROBLEMA COM A TENTATIVA DE COBRANÇA, O USUARIO FOI ATIVADO')");
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: ERRO: TID NULO')");
	        
	        if ($retorno_codigo_erro == '') {
	            $retorno_codigo_erro = 'tidnulo';
	        }
	        
	        header('Location: minha_conta-emcorrecao.php?erro_cartao=' . $retorno_codigo_erro);
	    } else {
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: NÃO OCORREU ERRO - EFETUAR PAGAMENTO')");
	        mysql_query("insert into log_acessos (id_user, acao) VALUES ( '".$linha_meus_dados['id']."', 'RETORNO: ".$string_erro[0]."' )");
	        // PEGANDO DADOS DE COBRANÇA DO USUARIO
	        $sql = "SELECT * FROM dados_cobranca WHERE id='" . $linha_meus_dados['id'] . "' ORDER BY id ASC LIMIT 0,1";
	        $resultado = mysql_query($sql) or die(mysql_error());
	        $linha = mysql_fetch_array($resultado);
	        
	        //RECUPERA ÚLTIMO PAGAMENTO PENDENTE
	        $ultimo_pagamento_pendente = $Pagamento->retornarUltimoPagamentoASomar($linha_meus_dados["id"]);
	        
	        //CALCULA DATA DO PRÓXIMO PAGAMENTO PAGAMENTO JÁ DESCONTANDO OS MESES COM DIVIDA
	        $dataPagamento = $Pagamento->calcularProximoPagamento($linha_meus_dados['status'], $ultimo_pagamento_pendente, $meses_a_somar);
	        
	        // ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS FILTRADOS NO INICIO DO LOOP (vencidos e não pagos)
	        $sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='" . $forma_pagamento_assinante . "',  envio_email='enviado' WHERE id='" . $linha_meus_dados['id'] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";
	        
	        
	        $resultadoup = mysql_query($sqlup) or die(mysql_error());
	        // return;
	        // INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
	        $sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico , data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao, tipo_plano, plano) VALUES ('" . $linha_meus_dados['id'] . "', '".$idHistorico."' , '" . date('Y-m-d') . "', '" . $forma_pagamento_assinante . "', '2.1', 'enviado', '" . $retorno_tid . "', " . $valor_pago . ",'" . $numeroCartao . "', '".$tipo_Plano."', '".$plano_usuario."')";
	        
	        $resultadoup = mysql_query($sqlup) or die(mysql_error());
	        
			// Pega o ultimo código do relatorio de cobrança.
			$idRelatorio = mysql_insert_id();
			
			// VERIFICA SE O PAGAMENTO E DE UMA CONTA PRÊMIO E GRAVA O CÓDIGO DO CONTADOR E DO RELATORIO DE COBRANCA.  
			if($tipo_Plano == 'P') {
				$qryUpdate = " INSERT INTO cobranca_contador (idRelatorio, contadorId) VALUE ('".$idRelatorio."', '".$contadorId."'); ";
				mysql_query($qryUpdate);
			}
			
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: USUARIO EFETUOU PAGAMENTO DE " . $valor_pago . " COM " . $forma_pagamento_assinante . "')");
	        mysql_query("insert into log_acessos (id_user, acao) VALUES ( '".$linha_meus_dados['id']."', 'RETORNO: ".$string_erro[0]."' )");
	        // CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
	        $sqlChecaAVencer = "SELECT idHistorico FROM historico_cobranca WHERE id='" . $linha_meus_dados['id'] . "' AND status_pagamento='a vencer' 
								LIMIT 0,1";
	        $resultadoChecaAVencer = mysql_query($sqlChecaAVencer) or die(mysql_error());
	        
	        if ((mysql_num_rows($resultadoChecaAVencer) <= 0)) {
	            mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: INSERINDO A VENCER NA DATA DE " . $dataPagamento . "')");
	            
	            // SE NÂO FOR LOCALIZADO PAGAMENTO A VENCER, INSERE NO HISTORICO COM UM MES PARA FRENTE
	            // INSERINDO O NOVO REGISTRO DE HISTORICO DE COBRANÇA A VENCER
	            
	            mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '" . $dataPagamento . "', 'a vencer')");
	        }
	        else {
	            $linhaChecaAVencer = mysql_fetch_array($resultadoChecaAVencer);
	            if( strtotime($linhaChecaAVencer) < strtotime($dataPagamento) ){
	            	//ATUALIZA PAGAMENTO 'A VENCER' COM A NOVA DATA DO PAGAMENTO
	            	mysql_query("UPDATE historico_cobranca SET data_pagamento='" . $dataPagamento . "' WHERE idHistorico=" . $linhaChecaAVencer["idHistorico"]);
	            }
	        }
	        
	        if ($status == 'demo' || $status == 'demoInativo' || $status == 'inativo' || $status == 'cancelado') {
	            $sql = "UPDATE login SET status='ativo' WHERE idUsuarioPai='" . $linha_meus_dados['id'] . "'";
	            $resultado = mysql_query($sql) or die(mysql_error());
	            
	            // LOG DE ACESSOS
	            mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados['id'] . ",'MINHA CONTA QUITAR: USUARIO FOI ATIVADO')");
	            
	            $_SESSION['status_userSecao'] = 'ativo';
	            
	            if ($status == 'demo' || $status == 'demoInativo') {
	                if ($linha_meus_dados['id'] != 1581 && $linha_meus_dados['id'] != 9) {
	                    // INSERE NA TABELA DE METRICAS
	                    mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $linha_meus_dados['id'] . ",'ativado','" . date('Y-m-d') . "')");
	                }
	            } else {
	                if ($linha_meus_dados['id'] != 1581 && $linha_meus_dados['id'] != 9) {
	                    // INSERE NA TABELA DE METRICAS
	                    mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $linha_meus_dados['id'] . ",'recuperado','" . date('Y-m-d') . "')");
	                }
	            }
	            
	            $Assinante        = $assinante;
	            $AssinanteExplode = explode(" ", $Assinante);
	            $Razao            = $razao_social;
	            $assuntoMail      = "Novo Assinante";
	            $emailuser        = "secretaria@contadoramigo.com.br";
	        }
	        
	        /* Redirecionando para a página de sucesso */
	        if (isset($_GET["reativar_conta"])) {
	            $ativo = "&ativo";
	        }
	        header('Location: minha_conta-emcorrecao.php?sucesso' . $ativo);
	    }
	} else {
	    if ($retorno_codigo_erro == '') {
	        $retorno_codigo_erro = 'invalido';
	    }
	    
	    // LOG DE ACESSOS
	    mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA QUITAR: TENTATIVA DE PAGAMENTO COM ERRO')");
	    mysql_query("insert into log_acessos (id_user, acao) VALUES ( '".$linha_meus_dados['id']."', 'RETORNO: ".$string_erro[0]."' )");
	    header('Location: minha_conta-emcorrecao.php?erro_cartao=' . $retorno_codigo_erro);
	}

	//echo "idHistoricoAtualizar=" .  $idHistoricoAtualizar . "<br>";	
	//echo "plano_meses=" .  $plano_meses . "<br>";
	//echo "total_devendo=" .  $total_devendo . "<br>";
	//echo "ultimo_pagamento_pendente=" .  $ultimo_pagamento_pendente . "<br>";	
	//echo "meses_a_somar=" .  $meses_a_somar . "<br>";
	//echo "dataPagamento=" .  $dataPagamento . "<br>";
	//echo "valor_pago=" .  $valor_pago . "<br>";
?>