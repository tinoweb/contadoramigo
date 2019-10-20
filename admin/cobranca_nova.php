<?php 
	session_start();

	include('../classes/phpmailer.class.php');
	include('../classes/config.php');
	include('../classes/pagamento.php');
	include 'check_login.php';
	include 'header.php';

	function getResultado($string){
    	switch ($string){
			case '1': 
				return 'Envio de boleto'; break; 							// não vai
			case '1.2': 
				return 'Boleto com sucesso'; break; 				// vai
			case '2.1': 
				return 'Cartão com sucesso'; break; 					// vai
			case '2.2': 
				return 'Cartão com erro'; break;						// vai
			case '2.3': 
				return 'Cartão não autorizado'; break;	// vai
			case '2.4': 
				return 'TID em branco ou nulo'; break;	// vai
			case '2.5': 
				return 'ERRO retorno cobrança'; break;	// vai
			case '2.6': 
				return 'Token não encontrado'; break;	// vai
			case '3': 
				return 'Desativação de conta'; break;								// não vai
			case '4': 
				return 'Demo expirado'; break;										// não vai
			case '5': 
				return 'Reativação de conta'; break;	// não vai
			case '6': 
				return 'Conta cancelada'; break;								// não vai
			case '7': 
				return 'Conta reativada'; break;								// não vai
			case '8': 
				return 'Demo a vencer'; break;							// não vai
			case '9': 
				return 'Inconsistência boleto'; break; 
			case '9.1': 
				return 'Boleto valor menor'; break; 
			case '9.2': 
				return 'Boleto valor maior'; break; 
			case '9.3': 
				return 'Data do boleto'; break; 
			case '9.9': 
				return 'Venda cancelada'; break; 

		}
	}

	function formataData($string){
		if($string == "")
			return "";
		$aux = explode("-", $string);
		return  $aux[2].'/'.$aux[1].'/'.$aux[0];
	}

	$Config = new Config();
	$mensalidade = $Config->verValor("mensalidade");
	$mensalidade_formatada = number_format($mensalidade,2,",",".");

	$dataInicio = $_GET["dataInicio"];

	if ($dataInicio == "") {
		$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
	}
	$dataInicioFormatada = formataData($dataInicio);
	$dataFim = $_GET["dataFim"];
	if ($dataFim == "") {
		$dataFim = date("Y-m-d");
	}
	

	$dataFimFormatada = formataData($dataFim);

	ob_start();


	require_once "../class/bean.php";
	require_once "../class/cielo.php";
	require_once '../class/pagamento-cartao.php';

	#########################################################################################
	############### Trecho para pagar com o Token ###########################################

	if( isset( $_GET['iniciarCobranca'] ) ){



		$consulta = mysql_query("SELECT * FROM dados_cobranca WHERE id = '12630' ");
		$objeto=mysql_fetch_array($consulta);

		$plano_usuario = $objeto['plano'];

		$plano_meses = $Config->verMeses($plano_usuario); //RECUPERA MESES DO PLANO
		$plano_valor = $Config->verValor($plano_usuario); // RECUPERA VALOR DO PLANO

		$consulta = mysql_query("SELECT * FROM login WHERE idUsuarioPai = '12630' AND id != idUsuarioPai ");
		$empresas_vinculadas = mysql_num_rows($consulta);

		if( $empresas_vinculadas < 1 )
			$empresas_vinculadas = 1;

		$id_atualizar_historico = mysql_query("SELECT * FROM historico_cobranca WHERE id = '12630' AND status_pagamento = 'não pago' ");						
		$total_devendo = mysql_num_rows($id_atualizar_historico);

		//CALCULA VALOR A PAGAR
		$valor_pago = 1;
		$valor_a_cobrar = $valor_pago;

		$Pagamento = new Pagamento(); //OBJETO DOS PAGAMENTOS

		//CALCULA QUANTOS MESES SERÃO SALVOS NO PRÓXIMO PAGAMENTO
		$meses_a_somar = $Pagamento->calcularMesesSomarBoleto($plano_meses, $total_devendo);

		// PEGANDO O ULTIMO NAO PAGO OU VENCIDO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
		$linhaData = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='12630' AND status_pagamento IN ('vencido', 'não pago', 'pendente') ORDER BY idHistorico DESC LIMIT 0,1"));

		$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linhaData["data_pagamento"]))+$meses_a_somar,date('d',strtotime($linhaData["data_pagamento"])),date('Y',strtotime($linhaData["data_pagamento"])))));

		//MAL Pega o token de pagamento cadastrado para o usuário
		$consulta_token_usuario = mysql_query("SELECT * FROM token_pagamento WHERE id_user = '12630' ");
		$dados_cartao_user=mysql_fetch_array($consulta_token_usuario);							

		//Pega o token do usuário
		$token = $dados_cartao_user['token'];
		$bandeira = $dados_cartao_user['bandeira'];
		$numeroCartao = $dados_cartao_user['numero_cartao'];
		$valor_pago = $valor_a_cobrar;
		//Cria um objeto que armazenará os dados do cartao para enviar ao pagamento
		$cartao = new Dados_cartao();
		$mensalidade = $valor_a_cobrar;
		$cartao->setValor($mensalidade);//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
		$cartao->setBandeira($bandeira);//Seta a bandeira 
		$cartao->setValorFinal($mensalidade);

		// //Cria um objeto para o pagamento
		$pagamento = new Pagamento_cartao();
		//Define o token para a cobrança
		$pagamento->setToken($token);
		//Tenta realizar a cobrança
		$pagamento->pagarComToken($cartao);

		$data_pagamento_cartao = $pagamento->getData();

		################ Fim do trecho para pagar com o token de pagamento ######################
		#########################################################################################


		#########################################################################################
		################ Início do trecho do tratamento do retorno ##############################


		$retorno_codigo_erro = $pagamento->getCodigoErro();
		$retorno_codigo_autorizacao = $pagamento->getStatus();
		$retorno_tid = $pagamento->getTid();

		// echo $retorno_codigo_autorizacao;

		$XmlResposta = $pagamento->getXmlRetorno();
		$inserir_log_cartao = mysql_query("INSERT INTO `log_cartao`(`id`, `id_user`, `erro`, `retorno_codigo` ,`data`, `resultado`) VALUES ( '','12630','".$retorno_codigo_erro."','".$retorno_codigo_autorizacao."','".date("Y-m-d H:m:s")."','".$XmlResposta."' )");
		$log_cartao=mysql_fetch_array($inserir_log_cartao);

		if( $retorno_codigo_erro == '' && $retorno_codigo_autorizacao != '' && $retorno_tid != ''){

			if( $retorno_codigo_autorizacao == 6 ){
				$sqlup = "INSERT INTO `relatorio_cobranca` (`idRelatorio`, `id`, `idHistorico`, `data`, `tipo_cobranca`, `resultado_acao`, `envio_email`, `valor_pago`, `tid`, `numero_cartao`, `emissao_NF`, `numero_NF`, `observacao`) 
							VALUES ('','12630','0','".$data_pagamento_cartao."','".$bandeira."','2.1','enviado','".$mensalidade.".00','".$retorno_tid."','".$numeroCartao."','0','0','')";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
			}	
			else if( $retorno_codigo_autorizacao == 5 ){
				$sqlup = "INSERT INTO `relatorio_cobranca` (`idRelatorio`, `id`, `idHistorico`, `data`, `tipo_cobranca`, `resultado_acao`, `envio_email`, `valor_pago`, `tid`, `numero_cartao`, `emissao_NF`, `numero_NF`, `observacao`) 
							VALUES ('','12630','0','".$data_pagamento_cartao."','".$bandeira."','2.3','enviado','','','".$numeroCartao."','0','0','')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
			}
		}
		else if( $retorno_codigo_erro == '052' ){
			$sqlup = "INSERT INTO `relatorio_cobranca` (`idRelatorio`, `id`, `idHistorico`, `data`, `tipo_cobranca`, `resultado_acao`, `envio_email`, `valor_pago`, `tid`, `numero_cartao`, `emissao_NF`, `numero_NF`, `observacao`) 
						VALUES ('','12630','0','".$data_pagamento_cartao.",'".$bandeira."','2.6','enviado','','','".$numeroCartao."','0','0','')";
					$resultadoup = mysql_query($sqlup)
					or die (mysql_error());
		}

		// header("Location: cobranca_nova.php");

	}

?>

<div class="principal">
   <div id="divFormUpload" class="bubble_top_right box_visualizacao x_visualizacao" style="position: absolute; text-align: center; display: none;">
      <div style="position: absolute; top: 10px; right: 10px; display: block;"><img src="../images/x.png" style="width: 8px; height: 9px; border: 0px; cursor: pointer;"></div>
      <div style="padding: 30px 20px 20px 20px;">
         <form action="processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
            <input name="FileInput" id="FileInput" type="file">
            <input type="submit" id="submit-btn" value="Upload">
            <img src="../images/loading.gif" id="loading-img" style="display:none;" alt="Please Wait">
         </form>
         <div id="progressbox" style="display: none;">
            <div id="progressbar" style="backbround-color: #00a600"></div>
            <div id="statustxt">0%</div>
         </div>
         <div id="output" style="color: #a61d00"></div>
      </div>
   </div>
   <div id="divFormUpload2" class="bubble_top_right box_visualizacao x_visualizacao" style="position: absolute; text-align: center; display: none;">
      <div style="position: absolute; top: 10px; right: 10px; display: block;"><img src="../images/x.png" style="width: 8px; height: 9px; border: 0px; cursor: pointer;"></div>
      <div style="padding: 30px 20px 20px 20px;">
         <form action="processupload2.php" method="post" enctype="multipart/form-data" id="MyUploadForm2">
            <input name="FileInput" id="FileInput2" type="file">
            <input type="submit" id="submit-btn2" value="Upload">
            <img src="../images/loading.gif" id="loading-img" style="display:none;" alt="Please Wait">
         </form>
         <div id="progressbox2" style="display: none;">
            <div id="progressbar2" style="backbround-color: #00a600"></div>
            <div id="statustxt2">0%</div>
         </div>
         <div id="resultado2" style="position:relative; width: 100%; display:none;">
            <div id="output2" style="color: #a61d00"></div>
            <!--<a href="#" id="btFecharUploadCielo">Fechar</a>-->
         </div>
      </div>
   </div>
   <div class="titulo" style="margin-bottom:10px; float:left">Cobrança</div>
   <div style="clear:both"> </div>
   <div style="position: relative; font-size: 11px;">
      <div style="float: left;">
         <form method="post" action="Javascript:alterarPeriodo()">
            TID  
            <input name="tid" id="tid" type="text" value="" maxlength="35" style="width:130px; font-size: 11px;" class="campoTID" disabled="disabled">  ou 
            E-mail 
            <input type="text" name="email" id="email" maxlength="255" style="font-size: 11px;" disabled="disabled">  
            <div id="preenchimentoBusca" style="position: absolute;"></div>
            Exibir 
            <select name="selAcao" id="selAcao" style="font-size: 11px; width: 100px;" disabled="disabled">
               <option value="todos" selected="selected">Todos</option>
               <option value="1">Boleto</option>
               <option value="2">Cartão</option>
            </select>
            por
            <select name="tipoData" id="tipoData" style="font-size: 11px; width: 70px;" disabled="disabled">
               <option value="data" selected="selected">Venda</option>
               <option value="data_pagamento">Pagamento</option>
            </select>
            entre  
            <input name="DataInicio" id="DataInicio" type="text" value="<?php echo $dataInicioFormatada; ?>" maxlength="10" style="width:75px;font-size: 11px;" class="campoData" disabled="disabled"> 
            até 
            <input name="DataFim" id="DataFim" type="text" value="<?php echo $dataFimFormatada ?>" maxlength="10" style="width:75px;font-size: 11px;" class="campoData" disabled="disabled">
            <input name="Alterar" type="submit" value="Filtrar" disabled="disabled">
         </form>
      </div>
      <div style="float: right;">
         <input type="button" value="Iniciar Cobrança" id="btIniciarCobranca" style="">
      </div>
   </div>
   <div style="clear:both; margin-bottom: 20px;"></div>
   <div style="position: relative; float: left; width: 100%;">
      <!-- <a class="imagemDica" position="right" div="divFormUpload2" id="btCarregaArquivo2" style="z-index: 99; position: absolute; padding: 5px 5px 0px 0px; right: 180px; text-align: right; font-weight: normal; font-size: 11px; text-decoration: underline; color: rgb(51, 102, 153); cursor: pointer;">Carregar arquivo CIELO</a> -->
      <!-- <a class="imagemDica" position="right" div="divFormUpload" id="btCarregaArquivo" style="z-index: 99; position: absolute; padding: 5px 5px 0px 0px; right: 0px; text-align: right; font-weight: normal; font-size: 11px; text-decoration: underline; color: rgb(51, 102, 153); cursor: pointer;">Carregar arquivo de notas fiscais</a> -->
      <div class="tituloVermelho">
         Relatório por Data de Venda entre <?php echo $dataInicioFormatada; ?> até <?php echo $dataFimFormatada ?>
      </div>
   </div>
   <div style="clear: both;"></div>
   <table border="0" cellspacing="2" cellpadding="4" style="font-size:11px;" width="100%">
      <tbody>
         <tr>
            <th width="7%" align="center">Venda</th>
            <th width="7%" align="center">Pgto.</th>
            <th width="29%" align="center">Razão Social</th>
            <th width="14%" align="center">CNPJ</th>
            <th width="17%" align="center">TID</th>
            <!--    <th align="center">Nº cartão</th>-->
            <th width="6%" align="center">Valor</th>
            <th width="14%" align="center">Resultado da Ação</th>
            <th width="6%" align="center">nº NF</th>
         </tr>

        <?php 

         	$consulta = mysql_query("SELECT * FROM relatorio_cobranca WHERE data >= '".$dataInicio."' && data <= '".$dataFim."' ORDER BY idRelatorio DESC ");

        ?>

        <?php 

        	while($cobranca=mysql_fetch_array($consulta)){ 

        		$dados_user = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$cobranca['id']."' ");
        		$user=mysql_fetch_array($dados_user);

        		$nome_user = $user['assinante'];
        		$cnpj = $user['documento'];

        		$data = formataData($cobranca['data']);
        		$data_pagamento = formataData($cobranca['data_pagamento']);
        		$tid = $cobranca['tid'];
        		$valor = number_format( $cobranca['valor_pago'] ,2,',','.' );
        		$resultado = getResultado($cobranca['resultado_acao']);
        		$nota_fiscal = $cobranca['numero_NF'];
        		if($nota_fiscal == 0)
        			$nota_fiscal = '';
        		$nota_emitida = $cobranca['emissao_NF'];

        ?>
	         <tr class="guiaTabela" style="<?php if($nota_emitida == 0 && $cobranca['resultado_acao'] == 2.1) echo 'background-color: rgb(255, 246, 195);' ?>" valign="top">
	            <td>
	            	<?php echo $data; ?>
	            </td>
	            <td>
	            	<?php echo $data_pagamento; ?>
	            </td>
	            <td>
	            	<a href="cliente_administrar.php?id=<?php echo $cobranca['id']; ?>" target="_blank">
	               		<?php echo $nome_user; ?>		
	               	</a>
	            </td>
	            <td align="center">
	            	<?php echo $cnpj; ?>
	            </td>
	            <!--
	               <td align="center"></td>
	               -->
	            <td align="center" class="col_tid" id="idRel_9803" style="cursor: pointer;">
	            	<?php echo $tid; ?>
	            </td>
	            <td align="right">
	               <?php echo $valor; ?>   
	            </td>
	            <td align="left">
	            	<?php echo $resultado; ?>
	            </td>
	            <td align="center" class="col_nf" id="idRel_9803" style="cursor: pointer;">
	            	<?php echo $nota_fiscal; ?>
	            </td>
	         </tr>


	    <?php } ?>

      </tbody>
   </table>
</div>

<script>

	$('#btIniciarCobranca').bind('click',function(e){
		e.preventDefault();
		<?
		$checkStatusAgendamento = (mysql_query("SELECT * FROM envio_emails_cobranca WHERE status = 0 LIMIT 0,1"));
		if(mysql_num_rows($checkStatusAgendamento) > 0){
		?>
			alert('Existem mensagens pendentes de agendamento. É necessário enviá-las para rodar a cobrança novamente!');
		<?
		}else{
		?>
			if(confirm('Deseja realmente iniciar a cobrança?')){
				location.href='https://www.contadoramigo.com.br/admin/cobranca_nova.php?iniciarCobranca&dataFim=<?=$dataFim?>&dataInicio=<?=$dataInicio?>';
			}
		<?
		}
		?>
	});

</script>


<?php include '../rodape.php'; ?>