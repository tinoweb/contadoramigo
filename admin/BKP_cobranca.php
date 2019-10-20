<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php include 'header.php' ?>

<?php
$tid = $_GET["tid"];

$assinante = $_GET["assinante"];

$dataInicio = $_GET["dataInicio"];
if ($dataInicio == "") {
	$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y')));
}

$dataFim = $_GET["dataFim"];
if ($dataFim == "") {
	$dataFim = date("Y-m-d");
}

$resultadoAcao = $_GET["acao"];
if ($resultadoAcao == "") {
	$resultadoAcao = "todos";
}

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
?>
<script>

function alterarPeriodo() {
	dataInicio = document.getElementById('DataInicio').value;
	anoInicio = dataInicio.substr(6,4);
	mesInicio = dataInicio.substr(3,2);
	diaInicio = dataInicio.substr(0,2);
	dataFim = document.getElementById('DataFim').value;
	anoFim = dataFim.substr(6,4);
	mesFim = dataFim.substr(3,2);
	diaFim = dataFim.substr(0,2);
	tid = document.getElementById('tid').value;
	assinante = document.getElementById('assinante').value;
	acao = document.getElementById('selAcao').value;

	window.location='cobranca.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&acao='+acao+'&tid='+tid+'&assinante='+assinante;
}
</script>

	<script>
    
	$(document).ready(function(e) {

		esquerda = ($('#assinante').offset().left);
		topo = ($('#assinante').offset().top);
		altura = ($('#assinante').innerHeight());
				
		$('#assinante').keyup(function(){
			if($(this).val() != ''){
				$.ajax({
					url:'preenchecampobusca.php',
					type: 'POST',
					data: 'valor='+$('#assinante').val(),
					async: true,
					success: function(result){
						if(result != ''){
							$('#preenchimentoBusca').css({
								'height':'auto'
								,'display': 'block'
								, 'top': topo + altura + 3
								, 'left': esquerda
							}).fadeIn('fast');
							$('#preenchimentoBusca').html(result);
						} else {
							$('#preenchimentoBusca').html('').css('display','none');
						}
					}						
				});
				
				$('.selResultBusca').live('click',function(){
					$('#assinante').val($(this).html());
					$('#hddIdUser').val($(this).attr('iduser'));
					$('#preenchimentoBusca').fadeOut('fast');
				});
			}else{
				$('#preenchimentoBusca').fadeOut('fast');
			}
		});
    });
    
    </script>
    
<div class="principal">

  <div class="titulo" style="margin-bottom:10px; float:left">Cobrança</div>
<div style="float:left">
<form method="post" action="Javascript:alterarPeriodo()">
TID  
<input name="tid" id="tid" type="text" value="<?=($tid)?>" maxlength="35"  style="width:150px;font-size: 12px;" class="campoTID" />  ou 
Assinante 
<input type="text" name="assinante" id="assinante"  maxlength="255" /> ou 
<div id="preenchimentoBusca" style="position: absolute;"></div>
Exibir 
<select name="selAcao" id="selAcao" style="width:150px;font-size: 12px;"> 
<option value="todos" <?php echo selected( 'todos', $resultadoAcao ); ?> />Todos</option>
<option value="1" <?php echo selected( '1', $resultadoAcao ); ?> />1: Envio de boleto bancário</option>
<option value="2.1" <?php echo selected( '2.1', $resultadoAcao ); ?> />2.1: Cobrança de cartão com sucesso</option>
<option value="2.2" <?php echo selected( '2.2', $resultadoAcao ); ?> />2.2: Cobrança de cartão com erro</option>
<option value="2.3" <?php echo selected( '2.3', $resultadoAcao ); ?> />2.3: Cobrança de cartão com erro (não autorizado)</option>
<option value="3" <?php echo selected( '3', $resultadoAcao ); ?> />3: Desativação de conta</option>
<option value="4" <?php echo selected( '4', $resultadoAcao ); ?> />4: Demo expirado</option>
<option value="5" <?php echo selected( '5', $resultadoAcao ); ?> />5: Cobrança de parcela pendente para usuário inativo</option>
</select>
no período entre  
  <input name="DataInicio" id="DataInicio" type="text" value="<?=date('d/m/Y',strtotime($dataInicio))?>" maxlength="10"  style="width:75px;font-size: 12px;" class="campoData"/> 
  até 
  <input name="DataFim" id="DataFim" type="text" value="<?=date('d/m/Y',strtotime($dataFim))?>" maxlength="10"  style="width:75px;font-size: 12px;" class="campoData" /> <input name="Alterar" type="submit" value="Filtrar" />
</form>
</div>
<div style=" clear:both"> </div>
<input type="button" value="Iniciar Cobrança" onClick="location.href='cobranca.php?iniciarCobranca&dataInicio=<?=date('Y-m-d')?>'" />

<?php 
if(isset($_GET["iniciarCobranca"])) { 

	function trataTxt($var) {
	
		$str = $var; 
		
		$str = str_replace("á","a",$str);	
		$str = str_replace("à","a",$str);	
		$str = str_replace("â","a",$str);	
		$str = str_replace("ã","a",$str);	
		$str = str_replace("Á","A",$str);	
		$str = str_replace("À","A",$str);	
		$str = str_replace("Â","A",$str);	
		$str = str_replace("Ã","A",$str);	
	
		$str = str_replace("é","e",$str);	
		$str = str_replace("è","e",$str);	
		$str = str_replace("ê","e",$str);	
		$str = str_replace("É","E",$str);	
		$str = str_replace("È","E",$str);	
		$str = str_replace("Ê","E",$str);	
	
		$str = str_replace("í","i",$str);	
		$str = str_replace("ì","i",$str);	
		$str = str_replace("î","i",$str);	
		$str = str_replace("Í","I",$str);	
		$str = str_replace("Ì","I",$str);	
		$str = str_replace("Î","I",$str);	
	
		$str = str_replace("ó","o",$str);	
		$str = str_replace("ò","o",$str);	
		$str = str_replace("ô","o",$str);	
		$str = str_replace("õ","o",$str);	
		$str = str_replace("Ó","O",$str);	
		$str = str_replace("Ò","O",$str);	
		$str = str_replace("Ô","O",$str);	
		$str = str_replace("Õ","O",$str);	
	
		$str = str_replace("ú","u",$str);	
		$str = str_replace("ù","u",$str);	
		$str = str_replace("û","u",$str);	
		$str = str_replace("ü","u",$str);	
		$str = str_replace("Ú","U",$str);	
		$str = str_replace("Ù","U",$str);	
		$str = str_replace("Û","U",$str);	
		$str = str_replace("Ü","U",$str);	
	
		$str = str_replace("ñ","n",$str);	
		$str = str_replace("Ñ","N",$str);	
	
		$str = str_replace("ç","c",$str);
		$str = str_replace("Ç","C",$str);
		
		return $str;
	}


	function getURL($id){
		$sqlup = "SELECT * FROM dados_cobranca WHERE id='" . $id . "' LIMIT 0, 1";
		$resultadoup = mysql_query($sqlup)
		or die (mysql_error());
		$linhaup=mysql_fetch_array($resultadoup);

		$NomeTitular = $linhaup["nome_titular"];
		$NumeroCartao = $linhaup["numero_cartao"];
		$DataValidade = date('Ym',strtotime($linhaup["data_validade"]));
		$Codigo = $linhaup["codigo_seguranca"];
		$FormaPagamento = $linhaup['forma_pagameto'];

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
		$valor = 5000;
		$pedido = $id . date("dmYHis");
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



	$sql = "SELECT * FROM login ORDER BY id ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());


// INICIO DO LOOP QUE PEGA TODOS OS LOGINS E INICIA A COBRANÇA
	while ($linha=mysql_fetch_array($resultado)) {
		$acaoRealizada = "";
		$sql2 = "SELECT * FROM historico_cobranca WHERE id='" . $linha["id"] . "' AND status_pagamento='pendente' ORDER BY idHistorico DESC LIMIT 0,1";
		$resultado2 = mysql_query($sql2)
		or die (mysql_error());
		$linha2=mysql_fetch_array($resultado2);
	
		$sql3 = "SELECT * FROM dados_cobranca WHERE id='" . $linha["id"] . "' ORDER BY id ASC LIMIT 0,1";
		$resultado3 = mysql_query($sql3)
		or die (mysql_error());
		$linha3=mysql_fetch_array($resultado3);
		
	
		$sql4 = "SELECT * FROM dados_da_empresa WHERE id='" . $linha["id"] . "' ORDER BY id ASC LIMIT 0,1";
		$resultado4 = mysql_query($sql4)
		or die (mysql_error());
		$linha4=mysql_fetch_array($resultado4);
	
		if($linha["status"] == "ativo") {
			//Ação 01: Enviar e-mail para quem está a 5 dias de vencer a assinatura e é boleto.
			if((date('Ymd',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"])),date('d',strtotime($linha2["data_pagamento"]))-5,date('Y',strtotime($linha2["data_pagamento"]))))) <= date('Ymd')) and ($linha3["forma_pagameto"] == "boleto") and ($linha2["envio_email"] == "")){
				//Componente de Envio de e-mail.
				$Assinante = $linha["assinante"];
				$AssinanteExplode = explode(" ", $Assinante);
				$vencimento = date('d/m/Y',strtotime($linha2["data_pagamento"]));
				$emailuser = $linha["email"];
				$assuntoMail = "Boleto a vencer";
				include '../mensagens/boleto_a_vencer.php';
			 //$mensagemHTML = 'teste';
				include '../mensagens/componente_envio.php';
			
			//exit;
			
				$sqlUp = "UPDATE historico_cobranca SET envio_email='enviado' WHERE idHistorico='" . $linha2["idHistorico"] . "'";
				$resultadoUp = mysql_query($sqlUp)
				or die (mysql_error());
			
				//Mensagem a exibir no relatório final.
				$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email) VALUES ('" . $linha["id"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha3["forma_pagameto"] . "', '1', 'enviado')";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
			}
		
		
		
		
		
		
			//Ação 02: Efetuar cobrança do cartão
			if((date('Ymd',strtotime($linha2["data_pagamento"])) <= date('Ymd')) and ($linha3["forma_pagameto"] != "boleto")) { 
				// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5

				// ########################################################################################################
				ini_set('allow_url_fopen', 1); // Ativa a diretiva 'allow_url_fopen'
	
				$XMLtransacao = GetURL($linha["id"]);

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
					$sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='" . $linha3["forma_pagameto"] . "' WHERE id='" . $linha["id"] . "' AND status_pagamento='pendente'";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
	
					$sqlup = "SELECT * FROM historico_cobranca WHERE id='" . $linha["id"] . "' ORDER BY idHistorico DESC LIMIT 0, 1";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());

					$linhaup=mysql_fetch_array($resultadoup);
	
					$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linhaup["data_pagamento"]))+1,date('d',strtotime($linhaup["data_pagamento"])),date('Y',strtotime($linhaup["data_pagamento"])))));
					$linha2["data_pagamento"] = $dataPagamento;
	
					$sqlup = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha["id"] . "', '$dataPagamento', 'pendente')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
	
					$sqlup = "UPDATE login SET status='ativo' WHERE id='" . $linha["id"] . "'";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
	
					//Envio de e-mail alertando a cobrança em seu cartão.
					$Assinante = $linha["assinante"];
					$AssinanteExplode = explode(" ", $Assinante);
					$emailuser = $linha["email"];
					$assuntoMail = "Aviso de cobrança de assinatura";
					include '../mensagens/cartao_confirmado.php';
					include '../mensagens/componente_envio.php';
				
					$sqlUp = "UPDATE historico_cobranca SET envio_email='enviado' WHERE idHistorico='" . $linha2["idHistorico"] . "'";
					$resultadoUp = mysql_query($sqlUp)
					or die (mysql_error());
				
					//Mensagem a exibir no relatório final.
					$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email, tid) VALUES ('" . $linha["id"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha3["forma_pagameto"] . "', '2.1', 'enviado', '" . $retorno_tid . "')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
				} 

				// Caso retorne com erro.
				else if(($retorno_codigo_erro != '') and ($linha2["envio_email"] == "")) {
					$sqlUp = "UPDATE historico_cobranca SET envio_email='não enviado' WHERE idHistorico='" . $linha2["idHistorico"] . "'";
					$resultadoUp = mysql_query($sqlUp)
					or die (mysql_error());
			
					//Mensagem a exibir no relatório final.
					$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email) VALUES ('" . $linha["id"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha3["forma_pagameto"] . "', '2.2', 'não enviado')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
				}
				
				// Caso o cartão ocorra uma transação não autorizada.
				else if(($retorno_codigo_autorizacao == '5') and ($linha2["envio_email"] == "")) {
					//Componente de Envio de e-mail.
					$Assinante = $linha["assinante"];
					$AssinanteExplode = explode(" ", $Assinante);
					$vencimento = date('d/m/Y',strtotime($linha2["data_pagamento"]));
					$emailuser = $linha["email"];
					$assuntoMail = "Erro ao cobrar sua assinatura";
					include '../mensagens/cartao_erro.php';
					include '../mensagens/componente_envio.php';
			
					$sqlUp = "UPDATE historico_cobranca SET envio_email='não enviado' WHERE idHistorico='" . $linha2["idHistorico"] . "'";
					$resultadoUp = mysql_query($sqlUp)
					or die (mysql_error());
			
					//Mensagem a exibir no relatório final.
					$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email) VALUES ('" . $linha["id"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha3["forma_pagameto"] . "', '2.3', 'enviado')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
				}
			}
		
			//Ação 03: Desativar os usuários que não pagaram a mensalidade ha mais de 5 dias.
			// a data de pagamento anterior + 5 dias deve ser maior que a data atual
			if(date('Ymd',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"])),date('d',strtotime($linha2["data_pagamento"]))+5,date('Y',strtotime($linha2["data_pagamento"]))))) <= date('Ymd')){
				$sqlUp = "UPDATE login SET status='inativo' WHERE id='" . $linha["id"] . "'";
				$resultadoUp = mysql_query($sqlUp)
				or die (mysql_error());
				$linha["status"] = "inativo";
			
				$sqlup = "UPDATE historico_cobranca SET status_pagamento='não pago', envio_email='enviado' WHERE id='" . $linha["id"] . "' AND status_pagamento='pendente'";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
				
				$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"]))+1,date('d',strtotime($linha2["data_pagamento"])),date('Y',strtotime($linha2["data_pagamento"])))));
				$linha2["data_pagamento"] = $dataPagamento;

				$sqlup = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha["id"] . "', '$dataPagamento', 'pendente')";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
			
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $linha["assinante"];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linha["email"];
				$assuntoMail = "Conta Inativa";
				include '../mensagens/conta_inativa.php';
				include '../mensagens/componente_envio.php';
			
				//Mensagem a exibir no relatório final.
				$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email) VALUES ('" . $linha["id"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha3["forma_pagameto"] . "', '3', 'enviado')";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
			
/*
				//tirar da lista de ativos do emkt
				error_reporting(E_ALL);
				require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
				// Esses valores podem ser obtidos na página de configurações do
				// Email Marketing

				$hostName = 'emailmkt7';
				$login 	  = 'contadoramigo1';
				$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
				$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

				$contatos = array();
				array_push($contatos, array('email'=> $linha["email"]));

				//Caso queira remover de listas, informar os IDs desta no 2o parametro.
				$repositorio->desativar($contatos,array(26587));
				$repositorio->importar($contatos, array(26588));
*/

			}
		}
		
		//Ação 04: Desativar usuários trial com o prazo de avaliação esgotado
		else if($linha["status"] == "demo") {
			if(date('Ymd',strtotime($linha2["data_pagamento"])) <= date('Ymd')){
				$sqlup = "UPDATE login SET status='demoInativo' WHERE id='" . $linha["id"] . "'";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
				$linha["status"] = "demoInativo";
			
			
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $linha["assinante"];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $linha["email"];
				$assuntoMail = "Período de avaliação expirado";
				include '../mensagens/demo_inativo.php';
				include '../mensagens/componente_envio.php';
				
			
				//Mensagem a exibir no relatório final.
				$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email) VALUES ('" . $linha["id"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha3["forma_pagameto"] . "', '4', 'enviado')";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
					
				

/*
				//tirar da lista de ativos do emkt
				error_reporting(E_ALL);
				require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
				// Esses valores podem ser obtidos na página de configurações do
				// Email Marketing

				$hostName = 'emailmkt7';
				$login 	  = 'contadoramigo1';
				$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
				$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

				$contatos = array();
				array_push($contatos, array('email'=> $linha["email"]));

				//Caso queira remover de listas, informar os IDs desta no 2o parametro.
				$repositorio->desativar($contatos,array(38414));
				$repositorio->importar($contatos, array(26588));
*/
				
				
			}
		}
	
		else if($linha["status"] == "inativo") {
			
			if(date('Ymd',strtotime($linha2["data_pagamento"])) <= date('Ymd')){
				$sqlup = "SELECT * FROM historico_cobranca WHERE id='" . $linha["id"] . "' AND status_pagamento='não pago' ORDER BY idHistorico DESC LIMIT 0,3";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
				$parcelasDevidas = mysql_num_rows($resultadoup);

				/* 
				TESTA QUANTAS VEZES O USUÁRIO ESTÁ COM O STATUS DE HISTÓRICO DE COBRANÇA não pago
				SE FOR MENOS QUE 3 MESES, É GERADO UM NOVO REGISTRO NA TABELA DE PAGAMENTOS
				*/
				if($parcelasDevidas < 3) {
					// ATUALIZA O STATUS DO PAGAMENTO DE pendente PARA não pago
					$sqlup = "UPDATE historico_cobranca SET status_pagamento='não pago' WHERE id='" . $linha["id"] . "' AND status_pagamento='pendente'";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
				
					$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"]))+1,date('d',strtotime($linha2["data_pagamento"])),date('Y',strtotime($linha2["data_pagamento"])))));
					$linha2["data_pagamento"] = $dataPagamento;
					
					// GERA A PRÓXIMA COBRANÇA com o status de pendente
					$sqlup = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha["id"] . "', '$dataPagamento', 'pendente')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
				
					/*
					GERA O REGISTRO NO RELATÓRIO DE COBRANÇA INFORMANDO QUE O USUARIO NÃO FOI COBRADO E NADA FOI FEITO
					*/
					$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico, data, tipo_cobranca, resultado_acao, envio_email) VALUES ('" . $linha["id"] . "', '" . $linha2["idHistorico"] . "', '" . date('Y-m-d') . "', '" . $linha3["forma_pagameto"] . "', '5', 'não enviado')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
				} 
			}
		}
	}
} 
?>
<br />
<br />
<?php 



if($assinante != "") {

	$sql = "SELECT * FROM relatorio_cobranca r INNER JOIN login l ON r.id = l.id WHERE 1 = 1 ";
	$sql .= " AND l.assinante LIKE '%" . $assinante . "%'";
	$titulo_vermelho = "Busca por Assinante: " . $assinante . "";

} else {
	
	$sql = "SELECT * FROM relatorio_cobranca WHERE 1 = 1 ";

	if($tid != "") {

		$sql .= " AND tid='$tid' ";

		$titulo_vermelho = "Busca por TID: " . $tid . "";

	}else{

		if($dataInicio != "" && $dataFim != "") {
			$sql .= " AND data BETWEEN '$dataInicio' AND '$dataFim'";
		}
		
		if($resultadoAcao != "todos") {
			$sql .= " AND resultado_acao='$resultadoAcao'";
		}
	
		$titulo_vermelho = "Relatório do " . ($dataInicio == $dataFim ? "dia " . date('d/m/Y',strtotime($dataInicio)) : "período entre " . date('d/m/Y',strtotime($dataInicio)) . " até " . date('d/m/Y',strtotime($dataFim)) );
		
	}

}

$sql .= " ORDER BY idRelatorio DESC";

$resultado = mysql_query($sql)
or die (mysql_error());
?>
<div class="tituloVermelho">
	<?=$titulo_vermelho?>
</div>
<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="100%">
    <tr>
    <th width="63" align="center">Data</th>
    <th width="248" align="center">Razão Social</th>
    <th width="150" align="center">CNPJ</th>
    <th align="center" width="43">Tipo</th>
    <th align="center" width="100">TID</th>
    <th align="center" width="310">Resultado da Ação</th>
<!--    <th align="center" width="91">Envio de Email</th>-->
    </tr> 
<?php
while ($linha=mysql_fetch_array($resultado)) {
	$sql2 = "SELECT * FROM dados_da_empresa WHERE id='" . $linha["id"] . "'";
	$resultado2 = mysql_query($sql2)
	or die (mysql_error());
	$linha2=mysql_fetch_array($resultado2);
?>
    <tr class="guiaTabela" style="background-color:#FFF" valign="top">
		<td><?=date('d/m/Y',strtotime($linha["data"]))?></td>
		<td><a href="cliente_administrar.php?id=<?=$linha["id"]?>" target="_blank">
		  <?=$linha2["razao_social"]?>
		</a></td>
		<td align="center"><?=($linha2["cnpj"])?></td>
	    <td align="center"><?php
		switch ($linha["tipo_cobranca"]){
			case 'visa': $forma_pagameto = '<img src="../images/visaicon.png" width="35" height="20" title="Visa" />'; 
			break;
			case 'mastercard': $forma_pagameto = '<img src="../images/mastercardicon.png" width="31" height="20" title="MasterCard" />'; 
			break;
			case 'boleto': $forma_pagameto = '<img src="../images/boletoicon.gif" width="39" height="20" title="Boleto Bancário" />'; 
			break;
			default: $forma_pagameto = ''; break;
		}
		echo $forma_pagameto;
		?></td>
        <td>
        <?php
        echo $linha['tid'] != '' ? $linha['tid'] : '';
        ?>
        </td>
        <td><?php
		switch ($linha["resultado_acao"]){
			case '1': $resultado_acao = 'Envio de boleto bancário'; break;
			case '2.1': $resultado_acao = 'Cobrança de cartão com sucesso'; break;
			case '2.2': $resultado_acao = 'Cobrança de cartão com erro'; break;
			case '2.3': $resultado_acao = 'Cobrança de cartão com erro (não autorizado)'; break;
			case '3': $resultado_acao = 'Desativação de conta'; break;
			case '4': $resultado_acao = 'Demo expirado'; break;
			case '5': $resultado_acao = 'Cobrança de parcela pendente para usuário inativo'; break;
		}
		echo $resultado_acao;
		?></td>
<!--
        <td><?php
        if($linha["resultado_acao"] == "2.2"){
        ?>
	        <div id="cobrarCartao<?=$linha["idRelatorio"]?>">
	        <?=$linha["envio_email"]?> | <a href="javascript:consultaBanco('cobranca_tentar_novamente.php?idRelatorio=<?=$linha["idRelatorio"]?>', 'cobrarCartao<?=$linha["idRelatorio"]?>')">tentar novamente</a></div>
        <?php	
        }else{
            echo $linha["envio_email"];
        }
        ?></td>
-->
    </tr>
<?php } ?>
  </table>  
</div>
<?php include '../rodape.php' ?>