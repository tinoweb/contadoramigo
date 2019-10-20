<?php
	
	// //MAL INICIO 
	// //PROVISORIO PARA REDIRECIONAR PARA A NOVA MINHA CONTA< ONDE SERÃO TESTADAS AS NOVAS CONFIGURAÇÕES DE PAGAMENTO
	// $dadosCookie = explode(" ",$_COOKIE['contadoramigoHTTPS']);
	// $email_cielo = $dadosCookie[0];
	

	// if ( $email_cielo == "cielo@contadoramigo.com.br") {
	// 	$url = "minha_conta_nova.php";
	// 	$get = '';	
	// 	foreach ($_GET as $key => $value) {
	// 		$get.= $key.'='.$value.'&';
	// 	}

	// 	if( $get != '')
	// 		$url = $url.'?'.$get;

	// 	$_SESSION["id_empresaSecao"] = '0';
	// 	header("Location: ".$url." ");
		

	// }

	// //FIM
	

	include 'header_restrita.php';
	include 'classes/config.php';

	//CALCULA VALOR A SER COBRADO DO CLIENTE, USADO EM TODAS AS SITUAÇÕES DESSA PÁGINA
	function calcularValorCobrar($Config, $total_empresas, $plano, $valor_plano, $valor_total_devendo)
	{
	    if (($plano == 'mensalidade') & ($valor_total_devendo > 0)) {
	        //Há valores em atrasado, retornando o valor já calculado
	        return $valor_total_devendo;
	    } else {
	        //Multiplica empresas
	        return $Config->calcularValorEmpresas($valor_plano, $total_empresas);
	    }
	}

	//RETORNA TOTAL DE EMPRESAS CADSATRADAS DO CLIENTE
	function totalEmpresas($id)
	{
	    $rsTotalEmpresas = mysql_fetch_array(mysql_query("SELECT COUNT(*) total_empresas FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = '" . $id . "' AND e.ativa = 1"));
	    if( intval($rsTotalEmpresas['total_empresas']) == 0 )
	    	return 1;
	    else
	 		return $rsTotalEmpresas['total_empresas'];
	}

	/*
	#####################################
	VALIDAÇÕES
	#####################################
	*/

	$show_log = false;

	$arrEstados = array();
	$sql        = "SELECT * FROM estados ORDER BY sigla";
	$result = mysql_query($sql) or die(mysql_error());
	while ($estados = mysql_fetch_array($result)) {
	    array_push($arrEstados, array(
	        'id' => $estados['id'],
	        'sigla' => $estados['sigla']
	    ));
	}

	/*
	DEVE SER ALTERADO O STATUS DE PAGAMENTO ANTES DE TUDO PARA NÃO CORRER O RISCO DE EFETIVAR PAGAMENTOS FUTUROS (STATUS A VENCER)


	####################################################################
	ATUALIZANDO PAGAMENTOS VENCIDOS
	####################################################################
	*/


	/*
	$sqlUpdateVencidos = "
	UPDATE 
	historico_cobranca h 
	INNER JOIN 
	login l ON h.id = l.id 
	SET 
	h.status_pagamento = (CASE 
	WHEN l.status in ('demo','demoInativo') THEN 'não pago' 
	ELSE 'vencido' 
	END) 
	WHERE 
	l.id = '" . $_SESSION["id_userSecao"] . "' 
	AND status_pagamento IN ('a vencer') 
	AND DATEDIFF(data_pagamento, DATE(now())) <= 0
	";
	*/
	$sqlUpdateVencidos  = "
				UPDATE 
					historico_cobranca h 
				INNER JOIN 
					login l ON h.id = l.id 
				SET 
					h.status_pagamento = (CASE 
											WHEN l.status in ('demo','demoInativo') THEN 'não pago' 
											ELSE 'vencido' 
										END) 
				WHERE 
					l.id = '" . $_SESSION["id_userSecaoMultiplo"] . "' 
					AND status_pagamento IN ('a vencer') 
					AND DATEDIFF(data_pagamento, DATE(now())) < 0
				";
	//echo $sqlUpdateVencidos;
	$rsAtualizaVencidos = mysql_query($sqlUpdateVencidos);

	// ############################## LOG ###############################
	if ($show_log == true) {
	    echo "alterando status de pagamentos para vencido (afetou " . mysql_affected_rows() . ")<BR><BR>";
	}
	// ############################## LOG ###############################

	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS VENCIDOS
	####################################################################
	*/


	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS NAO PAGOS
	####################################################################
	*/

	//$sqlUpdateNaoPago = "UPDATE `historico_cobranca` SET status_pagamento = 'não pago' WHERE DATEDIFF(data_pagamento, DATE(now())) between -90 AND -6 AND status_pagamento IN ('vencido', 'a vencer')";
	//$sqlUpdateNaoPago = "UPDATE `historico_cobranca` SET status_pagamento = 'não pago' WHERE id = '" . $_SESSION["id_userSecao"] . "' AND status_pagamento IN ('vencido') AND DATEDIFF(data_pagamento, DATE(now())) <= -6";
	$sqlUpdateNaoPago  = "UPDATE `historico_cobranca` SET status_pagamento = 'não pago' WHERE id = '" . $_SESSION["id_userSecaoMultiplo"] . "' AND status_pagamento IN ('vencido') AND DATEDIFF(data_pagamento, DATE(now())) <= -6";
	$rsAtualizaNaoPago = mysql_query($sqlUpdateNaoPago);

	// ############################## LOG ###############################
	if ($show_log == true) {
	    echo "alterando status de pagamentos para não pago (afetou " . mysql_affected_rows() . ")<BR><BR>";
	}

	/*
	####################################################################
	DESATIVANDO USUARIO
	####################################################################
	*/

	$sqlUpdateLogin = "
				UPDATE 
					login l 
				SET 
					l.status = CASE WHEN l.status =  'ativo' THEN  'inativo' 
									WHEN l.status =  'demo' THEN  'demoInativo' 
									ELSE l.status END 
				WHERE 
					l.id = '" . $_SESSION["id_userSecaoMultiplo"] . "' 
					AND ( SELECT COUNT(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento =  'não pago') >0
			";

	$rsUpdateLogin = mysql_query($sqlUpdateLogin);

	if (mysql_affected_rows() > 0) {
	    $_SESSION['status_userSecao'] = ($_SESSION['status_userSecao'] == 'ativo' ? 'inativo' : ($_SESSION['status_userSecao'] == 'demo' ? 'demoInativo' : $_SESSION['status_userSecao']));
	}

	if ($show_log == true) {
	    echo "inativando usuario (afetou " . mysql_affected_rows() . ")<BR><BR>";
	}


	$sql_dados_login = "
		SELECT 
			l.id
			, l.email
			, l.status
			, dc.assinante
			, dc.data_inclusao
			, dc.forma_pagameto
			, dc.pref_telefone
			, dc.telefone
			, dc.plano
			, dc.numero_cartao
			, dc.codigo_seguranca
			, dc.nome_titular
			, dc.data_validade
		FROM 
			login l
			INNER JOIN dados_cobranca dc ON l.id = dc.id
		WHERE 
			l.id='" . $_SESSION["id_userSecaoMultiplo"] . "' 
		LIMIT 0, 1
		";

	$resultado_dados_login = mysql_query($sql_dados_login) or die(mysql_error());

	$linha_dados_login = mysql_fetch_array($resultado_dados_login);

	$id_usuario                = $linha_dados_login['id'];
	$email_usuario             = $linha_dados_login['email'];
	$status_login              = $linha_dados_login['status'];
	$assinante                 = $linha_dados_login['assinante'];
	$plano                     = $linha_dados_login['plano'];
	$data_inclusao             = $linha_dados_login['data_inclusao'];
	$forma_pagamento_assinante = $linha_dados_login['forma_pagameto'];
	$pref_telefone             = $linha_dados_login['pref_telefone'];
	$telefone                  = $linha_dados_login['telefone'];

	$numero_cartao    = $linha_dados_login['numero_cartao'];
	$codigo_seguranca = $linha_dados_login['codigo_seguranca'];
	$nome_titular     = $linha_dados_login['nome_titular'];
	$data_validade    = $linha_dados_login['data_validade'];

	$passNum = 0;
	while ($passNum < strlen($linha_dados_login["senha"])) {
	    $senha   = $senha . '*';
	    $passNum = $passNum + 1;
	}

	//Cria novo objeto de configuração
	$Config = new Config();

	//Recebe array de valores de assinaturas
	$valores = $Config->listarValores();

	//Recebe demais valores do plano
	$valorMensal = $Config->verMensalidade();
	$valor_plano = $Config->verValor($plano);
	$plano       = $plano;
	$meses_plano = $Config->verMeses($plano);

	function selected($value, $prev)
	{
	    return $value == $prev ? ' selected="selected"' : '';
	}
	;

	function checked($value, $prev)
	{
	    return $value == $prev ? ' checked="checked"' : '';
	}
	;
	?>

	<script type="text/javascript">
	$(document).ready(function(e) {

		$('input[name="rdbTipo"]').bind('change',function(){
			if($(this).val()=='J'){
				$('#txtSacado').html('Razão Social');
				$('#txtDocumento').html('CNPJ');
				$('#boleto_cnpj').css('display','block');
				$('#boleto_cpf').css('display','none');
				
			}else{
				$('#txtSacado').html('Nome');
				$('#txtDocumento').html('CPF');
				$('#boleto_cnpj').css('display','none');
				$('#boleto_cpf').css('display','block');
			}
		});

		$('#selEstado').bind('change',function(){
			var arrDadosEstado = $('#selEstado').val().split(';');
			var idUF = arrDadosEstado[0];
			$.getJSON('consultas.php?opcao=cidades&valor='+idUF, function (dados){ 
				if (dados.length > 0){
					var option = '<option></option>';
					$.each(dados, function(i, obj){
						option += '<option value="'+obj.cidade+'">'+obj.cidade+'</option>';
						})
					$('#txtCidade').html(option).show();
				}
			});
		});
		$("#btnSalvarPlano").click(function() {
			$("#form_assinante_plano").submit();
		});
		$("#btnSalvarDados").click(function() {
			$("#form_forma_pagamento_cobranca").submit();
		});



		$('#btnSalvar').click(function(){
			if($('#txtEmail').val() != $('#hddEmailUser').val()){
				$.ajax({
				  url:'assinatura_checa_email.php',
				  data: 'email=' + $('#txtEmail').val(), 
				  type: 'get',
				  async: false,
				  cache:false,
				  success: function(retorno){
					  if(retorno > 0){
						$('#txtEmail').focus();
						alert('O E-mail já está cadastrado em nosso sistema.');
						return false;
					  }else{
						  frmAssinante();
					  }
				  }
				});
			}else{
				if($('#divSenha').css('display') == 'block'){
					if($('#passNovaSenha').val() == ""){
						alert('Preencha a nova senha!');
						$('#passNovaSenha').focus();
						return false;
					}else{
						if($('#passNovaSenha').val().length < 8){
							alert('A senha deve ter pelo menos 8 caracteres!');
							$('#passNovaSenha').focus();
							return false;
						}else{
							if($('#passNovaSenha').val() != $('#passConfirmaSenha').val()){
								alert('As senhas não conferem!');
								$('#passNovaSenha').focus();
								return false;
							}else{
								frmAssinante();
							}
						}
					}
				}else{
					frmAssinante();
				}
			}
		});

	});

	 var msg1 = 'É necessário preencher o campo';
	 var msg2 = 'É necessário selecionar ';

	function fnValidaEmail(email){

	        var v_email = email.value;
	        var jSintaxe;
	        var jArroba;
	        var jPontos;

		var ExpReg = new RegExp('[^a-zA-Z0-9\.@_-]', 'g');

	        jSintaxe = !ExpReg.test(v_email);
		if(jSintaxe == false){
	            window.alert('Favor digitar o e-mail corretamente!');
	            return false;
		}
		jPontos = (v_email.indexOf('.') > 0) && !(v_email.indexOf('..') > 0);
		if (jPontos == false){
	            window.alert('Favor digitar o e-mail corretamente!');
	            return false;
		}
		jArroba = (v_email.indexOf('@') > 0) && (v_email.indexOf('@') == v_email.lastIndexOf('@'));
		if (jArroba == false){
	            window.alert('Favor digitar o e-mail corretamente!');
	            return false;
		}

	        return true;

	}


	 function frmAssinante(){   
	            if(validElement('txtAssinante', msg1) == false){return false}

				if( validElement('txtEmail', msg1)== false){
	                return false
	            } else {
	               var email = document.getElementById('txtEmail');
	                if(fnValidaEmail(email) == false){
	                    return false
	                }
	            }

				if(document.getElementById('divSenha').style.display != 'none'){
					if(document.getElementById('passNovaSenha').value != document.getElementById('passConfirmaSenha').value) {
						window.alert('A senha e a confirmação de senha são diferentes.');
						return false
					}
				} else {
					document.getElementById('passNovaSenha').value = "";
					document.getElementById('passConfirmaSenha').value = "";
				}				
				if( validElement('txtPrefixoTelefone', msg1) == false){return false}
	            if( validElement('txtTelefone', msg1) == false){return false}
				document.getElementById('form_assinante').submit()

	 }

	 function formFormaPagamentoSubmit() {
		
		if( validElement('boleto_sacado', msg1) == false){return false}
		
		if (document.getElementById('boleto_PJ').checked) {
			if( validElement('boleto_cnpj', msg1) == false){return false}
		}else{
			if( validElement('boleto_cpf', msg1) == false){return false}
		}

		if( validElement('boleto_endereco', msg1) == false){return false}
		if( validElement('selEstado', msg1) == false){return false}
		if( validElement('txtCidade', msg1) == false){return false}
		if( validElement('boleto_cep', msg1) == false){return false}
		

		document.getElementById('form_forma_pagamento_cobranca').submit()
	}

	function formFormaPagamentoSubmit2() {
		
		
		

		if (!document.getElementById('radFormaPagamento3').checked) {
			var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";
		
			if( validElement('txtNumeroCartao', msg1) == false){return false}
		
			if(document.getElementById('txtNumeroCartao').value != '<?= '************' . substr($numero_cartao, -4, 4) ?>') {
				for (var i = 0; i < document.getElementById('txtNumeroCartao').value.length; i++) {
					if (iChars.indexOf(document.getElementById('txtNumeroCartao').value.charAt(i)) != -1) {
						alert ("Digite um número de cartão válido.");
						return false;
					}
				}
			}
		
			if( validElement('txtCodigo', msg1) == false){return false}
			if( validElement('txtNomeTitular', msg1) == false){return false}
			if( validElement('txtDataValidade', msg1) == false){return false}
		}
		document.getElementById('form_forma_pagamento2').submit()
	}


	</script>
	<div class="principal">


	<div class="titulo" style="margin-bottom:20px">Dados da Conta</div>

	<div style="clear: both;"></div>

	<div style="float:left; margin-top:80px; margin-bottom:-30px; position:relative">
	      <img src="images/assistente.png" width="109" height="375" alt=""/> 
	    </div>

	<? //INICIO DO BALLON 
	?>
	<div class="bubble_left" style="width:305px; margin-left:16px; margin-right:24px; float:left;">
	    <div style="padding:20px; font-size:12px"> 
	    
	<?php
	$mostra_mensagem_ajuda        = true; // variavel que controla a exibição da mensagem de ajuda no final do balão amarelo
	$demo_vencido                 = false;
	$mostra_botao_prox_boleto     = false;
	$possui_nao_pagos             = false;
	$mostra_mensagem_cancelamento = false;

	
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
	//					l.status <> 'cancelado' AND l.id='" . $_SESSION["id_userSecao"] . "'

	$resultado_meus_dados = mysql_query($sql_meus_dados) or die(mysql_error());
	$linha_meus_dados = mysql_fetch_array($resultado_meus_dados);

	if( isset( $_GET['error_dados_cobranca'] ) ):
		
		echo '
				<script>
				
					alert("É necessário preencher os dados de cobrança antes de preencher os dados do cartão");
				
				</script>
			';
	
	endif;

	// PAGAMENTO FEITO POR CARTÃO NA PROPRIA PAGINA RETORNA A URL COM O ERRO DO CARTÃO
	if (isset($_GET["erro_cartao"])) {



		switch ($_GET["erro_cartao"]) {
			case 'transacao_nao_autorizada':
				echo '	<div style="clear: both;">
				            <span class="tituloVermelho">Transação não autorizada</span><br />
				            <br />
				            A operadora não autorizou o débito em seu cartão. Contate sua operadora, confira ao lado as informações de cobrança ou altere a forma de pagamento para boleto e tente novamente.<br />
				            <br />
						</div>';
				break;
			case 'token_nao_encontrado';
				echo '	<div style="clear: both;">
				            <span class="tituloVermelho">Falha na transação</span><br />
				            <br />
				            A operadora ainda não pode confirmar o pagamento. Sua assinatura foi quitada provisoriamente. Caso a venda não se confirme, entraremos em contato.<br />
				            <br />
						</div>';
				break;
			case 'tidnulo';
				echo '<div style="clear: both;">
				            <span class="tituloVermelho">Falha na transação</span><br />
				            <br />
				            Houve uma falha na comunicação com a operadora de seu cartão.
				            Confira ao lado as informações de cobrança e tente novamente.<br />
				            <br />
				        </div>';
				break;
			case '999';
				echo '<div style="clear: both;">
				            <span class="tituloVermelho">Falha na transação</span><br />
				            <br />
				            O pagamento não autorizado pelo banco emissor do cartão.
				            <br />
				            <br />
				        </div>';
				break;
			default:
				# code...
				break;
		}

	    
	
	    
	}
	// FIM PAGAMENTO FEITO POR CARTÃO NA PROPRIA PAGINA RETORNA A URL COM O ERRO DO CARTÃO


	// INICIO DA APRESENTAÇÃO DAS MENSAGENS DE ACORDO COM OS DADOS DO USUARIO
	switch ($linha_meus_dados['status']) {
	    
	    
	    // USUARIOS DEMO INATIVO
	    case 'demoInativo':
	        
	        $mostra_mensagem_cancelamento = true;
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO DEMO INATIVO LOGADO')");
	        
	        $mostra_botao_acao = true;
	        $query_string      = "?atrasados";
	        
	        if ($forma_pagamento_assinante == "boleto") {
	            
	            $value_botao = "Imprimir boleto";
	            
	        } else {
	            
	            $value_botao = "Ativar Assinatura";
	            
	        }
	        
	        //Calcula valor a cobrar		
	        //$valor_cobrar = number_format($valor_plano, 2, ",",".");
	        
	        //RECEBE TOTAL DE EMPRESAS
	        $empresas = totalEmpresas($id_usuario);
	        
	        //Calcula valor a pagar
	        $valor_cobrar = calcularValorCobrar($Config, $empresas, $plano, $valor_plano, 0);
	        
	        //Formata valor a pagar
	        $valor_cobrar = number_format($valor_plano, 2, ",", ".");
	        
	        $dias_vencimento          = 2;
	        $pagina_destino_pagamento = 'minha_conta_quitar_cartao_nova.php'; //'minha_conta_ativar.php';
	        
	        $data_vencimento = date('d/m/Y', (mktime(0, 0, 0, date('m'), date('d') + $dias_vencimento, date('Y'))));
	        
	        $dataPagamento = date('Y-m-d', (mktime(0, 0, 0, date('m'), date('d'), date('Y'))));
	        
	        $data_pagamento = $dataPagamento;
	        $data_diferenca = 0;
	        $mes_boleto     = date('m', strtotime($dataPagamento));
	?>
	        <span class="tituloVermelho">Prazo de avaliação esgotado</span><br />
	        <br /> 
	        Para continuar utilizando o Contador Amigo, você deverá confirmar sua assinatura.<br />
	        <br />
	        <strong>Preencha os dados de cobrança ao lado</strong> e clique no botão &quot;Ativar Assinatura&quot; para efetuar o pagamento da primeira mensalidade, no valor de <span class="destaque">R$ <?= $valor_cobrar ?></span>.<br />
	        <br />

	<?php
	        
	        break;
	    
	    
	    // USUARIOS DEMO 
	    case 'demo':
	        
	        $mostra_mensagem_cancelamento = true;
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO DEMO LOGADO')");
	        
	        $mostra_botao_acao = true;
	        
	        if ($forma_pagamento_assinante == "boleto") {
	            
	            $value_botao = "Imprimir boleto";
	            
	        } else {
	            
	            $value_botao = "Ativar Assinatura";
	            
	        }
	        
	        //Calcula valor a cobrar			
	        //$valor_cobrar = number_format($valor_plano, 2, ",",".");
	        
	        $dias_vencimento          = 2;
	        $pagina_destino_pagamento = 'minha_conta_quitar_cartao_nova.php';
	        
	        $data_vencimento = date('d/m/Y', (mktime(0, 0, 0, date('m'), date('d') + $dias_vencimento, date('Y'))));
	        
	        //RECEBE TOTAL DE EMPRESAS
	        $empresas = totalEmpresas($id_usuario);
	        
	        //Calcula valor a cobrar
	        $valor_cobrar = calcularValorCobrar($Config, $empresas, $plano, $valor_plano, 0);
	        
	        //Formata valor a cobrar
	        $valor_cobrar = number_format($valor_plano, 2, ",", ".");
	        
	        // DIFERENCA MAIOR QUE ZERO = DATA FUTURA / DATA MENOR QUE ZERO = DATA PASSADA
	        $sql_demo = "
					SELECT 
						h.id
						, h.status_pagamento
						, h.idHistorico
						, h.data_pagamento
						, d.forma_pagameto
						, DATEDIFF(data_pagamento, DATE(NOW())) diferenca 
					FROM 
						login l
						INNER JOIN 
							historico_cobranca h ON l.id = h.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
					WHERE 
						l.status = 'demo'
						AND h.status_pagamento IN ('a vencer','vencido','não pago')
						AND l.id='" . $linha_meus_dados["id"] . "'
					ORDER BY 
						idHistorico ASC
					";
	        
	        $resultado_demo = mysql_query($sql_demo) or die(mysql_error());
	        
	        // DEFININDO O TOTAL A SER COBRADO PADRÃO
	        $pagamentos_demo = mysql_fetch_array($resultado_demo);
	        
	        $data_diferenca = $pagamentos_demo['diferenca'];
	        
	        // MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	        //		$idHistoricoAtualizar = "('" . $pagamentos_demo['idHistorico'] .  "')";
	        
	        if ($data_diferenca >= 0) {
	            // SE AINDA NÃO VENCEU
	            $totalQuitar = 1;
	            
	            $data_pagamento = $pagamentos_demo['data_pagamento'];
	            $mes_boleto     = date('m', strtotime($pagamentos_demo['data_pagamento']));
	            $query_string   = "?avencer";
	            
	            
	?>
	    
	            <span class="tituloVermelho">Período de avaliação gratuita</span><br />
	            <br />
	            Você poderá continuar utilizando o Contador Amigo por mais <span class="destaque"><?= $data_diferenca ?> dia<?= $data_diferenca > 1 ? 's' : '' ?></span>.<br />
	            <br />
	            Para confirmar sua assinatura, preencha os dados de cobrança ao lado e realize o pagamento da primeira mensalidade<?php if($forma_pagamento_assinante != '') echo ', no valor de <span class="destaque">R$ '.$valor_cobrar.'</span>';?>.<br />
	            <br />
	    
	            
	            <div id="divCarregando" style="margin-top:10px; text-align:center; <?= "display:none" ?>"><img src="images/loading.gif" width="16" height="16" /> Carregando, por favor aguarde...</div>
	            

	<!--<div id="divErroTemporarioCartao" style="display: none;clear: both; margin-top: 15px;">
	    <span class="tituloVermelho">Desculpe-nos, devido a um problema técnico o pagamento por cartão está temporariamente inativo. Estamos trabalhando para restabelecer a comunicação o mais brevemente possível. Enquando isso, por favor, utilize o pagamento por boleto. Se precisar de liberação imediata, envie-nos o comprovamente pelo Help Desk e seu acesso será liberado na hora.</span><br />
	    <br />
	</div>-->
	<?
	        } else {
	            // SE VENCEU
	            $demo_vencido = true;
	            $query_string = "?atrasados";
	            
	            $totalQuitar = 1;
	            
	            $dataPagamento = date('Y-m-d', (mktime(0, 0, 0, date('m'), date('d'), date('Y'))));
	            
	            $data_pagamento = $dataPagamento;
	            $mes_boleto     = date('m', strtotime($dataPagamento));
	            
	            
	?>
	            <span class="tituloVermelho">Prazo de avaliação esgotado</span><br />
	            <br /> 
	            Para continuar utilizando o Contador Amigo, você deverá confirmar sua assinatura.<br />
	            <br />
	            <strong>Preencha os dados de cobrança ao lado</strong> e clique no botão &quot;Ativar Assinatura&quot; para efetuar o pagamento da primeira mensalidade, no valor de <span class="destaque">R$ <?= $valor_cobrar ?></span>.<br />
	            <br />

	<?php
	            
	        }
	        
	        
	        break;
	    
	    
	    // USUARIOS ATIVOS - AQUI TEM UMA DIFERENÇA DE ACORDO COM O STATUS DE PAGAMENTO
	    case 'ativo':
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO ATIVO LOGADO')");
	        
	        $mostra_mensagem_cancelamento = true;
	        
	        $value_botao     = "";
	        $dias_vencimento = 2;
	        
	        // DIFERENCA MAIOR QUE ZERO = DATA FUTURA / DATA MENOR QUE ZERO = DATA PASSADA
	        $sql_ativo = "
					SELECT 
						h.id
						, h.status_pagamento
						, h.idHistorico
						, h.data_pagamento
						, d.data_inclusao
						, DATEDIFF(data_pagamento, DATE(NOW())) diferenca 
					FROM 
						login l
						INNER JOIN 
							historico_cobranca h ON l.id = h.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
					WHERE 
						l.status = 'ativo'
						AND h.status_pagamento IN ('a vencer','vencido','não pago')
						AND l.id='" . $linha_meus_dados["id"] . "'
					ORDER BY 
						idHistorico ASC
					";
	        //a vencer','vencido','não pago
	        
	        $resultado_ativo = mysql_query($sql_ativo) or die(mysql_error());
	        
	        // FORMA DE PAGAMENTO DO USUARIO
	        $rsfPagto = mysql_fetch_array(mysql_query("SELECT forma_pagameto FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "'"));
	        $fPagto   = $rsfPagto['forma_pagameto'];
	        
	        
	        // TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
	        $rs_total_devendo = mysql_query("SELECT id, idHistorico, data_pagamento FROM historico_cobranca WHERE status_pagamento IN ('vencido','não pago') AND id='" . $linha_meus_dados["id"] . "'");
	        
	        $total_devendo = mysql_num_rows($rs_total_devendo);
	        $arrTestes     = array();
	        
	        while ($dados_total_devendo = mysql_fetch_assoc($rs_total_devendo)) {
	            //echo "SELECT l.id FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"] . " AND l.data_inclusao <= '" . $dados_total_devendo['data_pagamento'] . " 23:59:59' AND (e.ativa = 1 OR (ativa = 0 AND DATEDIFF(data_desativacao, DATE(l.data_inclusao)) > 0 ));";
	            //echo "SELECT l.id, DATEDIFF('" . date("Y-m-d",strtotime($dados_total_devendo['data_pagamento'])) . "',data_desativacao) FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"] . " AND l.data_inclusao <= '" . ($dados_total_devendo['data_pagamento']) . "' AND (e.ativa = 1 OR (e.ativa=0 AND DATEDIFF('" . date("Y-m-d",strtotime($dados_total_devendo['data_pagamento'])) . "',data_desativacao) < 5))";
	            
	            // CHECANDO SE EMPRESAS FORAM CADASTRADAS ANTES DO VENCIMENTO DE ALGUM PAGAMENTO NÃO FEITO
	            $empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id, DATEDIFF('" . date("Y-m-d", strtotime($dados_total_devendo['data_pagamento'])) . "',data_desativacao) FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"] . " AND l.data_inclusao <= '" . ($dados_total_devendo['data_pagamento']) . "' AND (e.ativa = 1 OR (e.ativa=0 AND DATEDIFF('" . date("Y-m-d", strtotime($dados_total_devendo['data_pagamento'])) . "',data_desativacao) < 5))"));
	            
	            //"SELECT l.id FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"] . " AND l.data_inclusao <= '" . $dados_total_devendo['data_pagamento'] . " 23:59:59' AND (e.ativa = 1 OR (ativa = 0 AND DATEDIFF(data_desativacao, DATE(l.data_inclusao)) > 0 ))"));
	            
	            if ($empresas_para_cobrar > 0) {
	                array_push($arrTestes, array(
	                    'idPagto' => $dados_total_devendo['idHistorico'],
	                    'qtd_empresas' => $empresas_para_cobrar,
	                    'data_pagamento' => $dados_total_devendo['data_pagamento']
	                ));
	            }
	        }
	        //var_dump($arrTestes);
	        
	        $valor_total_devendo = 0;
	        foreach ($arrTestes as $dados) {
	            //$valor_total_devendo += ($mensalidade_unitaria * $dados['qtd_empresas']);
	            $valor_total_devendo += $Config->calcularValorEmpresas($valor_plano, $dados["qtd_empresas"]);
	        }
	        
	        //		var_dump($arrTestes);
	        //echo "R$ " . $valor_total_devendo . "<BR>";
	        
	        // TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS A VENCER
	        $total_a_vencer = mysql_num_rows(mysql_query("SELECT id, data_pagamento FROM historico_cobranca WHERE status_pagamento IN ('a vencer') AND id='" . $linha_meus_dados["id"] . "'"));
	        
	        $totalQuitar    = 0;
	        //		$idHistoricoAtualizar = "";
	        $data_diferenca = "";
	        
	        if ($fPagto == "boleto") {
	            $mostra_botao_prox_boleto = true;
	        } else {
	            $mostra_botao_prox_pagamento = true;
	            $pagina_destino_pagamento    = 'minha_conta_quitar_cartao_nova.php';
	        }
	        
	        
	        // SE HOUVER ALGUM VENCIDO OU NÂO PAGO, NÃO MOSTRAR O BOTÃO PARA QUITAR O PRÓXIMO
	        if ($total_devendo >= 1) {
	            $mostra_botao_prox_boleto    = false;
	            $mostra_botao_prox_pagamento = false;
	            $query_string                = "?atrasados";
	        }
	        
	        if ($total_devendo + $total_a_vencer <= 1) {
	            // VARIAVEL CONTROLA QUANTOS PAGAMENTOS DEVEM SER PAGOS
	            //$totalQuitar=1;	
	            
	            // CALCULANDO O TOTAL A SER COBRADO COM O TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
	            //$valor_cobrar = number_format(($mensalidade), 2, ",",".");
	            //$valor_cobrar = number_format(($valor_total_devendo), 2, ",",".");
	            
	            $pagamentos_ativo = mysql_fetch_array($resultado_ativo);
	            
	            if ($total_devendo == 1) {
	                $data_pagamento_pendente = $pagamentos_ativo['data_pagamento'];
	                $data_diferenca          = $pagamentos_ativo['diferenca'];
	                //$valor_cobrar = number_format($valor_total_devendo, 2, ",", ".");
	            } else if ($total_a_vencer == 1) {
	                // CHECANDO SE EMPRESAS FORAM CADASTRADAS ANTES DO VENCIMENTO DE ALGUM PAGAMENTO NÃO FEITO
	                //				$empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"] . " AND (e.ativa = 1 OR (e.ativa=0 AND DATEDIFF('" . date("Y-m-d") . "',data_desativacao) < 5))"));
	                //$empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"] . " AND (e.ativa = 1)"));				
	                
	                //"SELECT l.id FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $_SESSION["id_userSecaoMultiplo"] . " AND l.data_inclusao <= '" . $dados_total_devendo['data_pagamento'] . " 23:59:59' AND (e.ativa = 1 OR (ativa = 0 AND DATEDIFF(data_desativacao, DATE(l.data_inclusao)) > 0 ))"));														
	                $query_string           = "?avencer";
	                $data_proximo_pagamento = $pagamentos_ativo['data_pagamento'];
	                $data_diferenca         = $pagamentos_ativo['diferenca'];
	                //$valor_cobrar = number_format(($mensalidade_unitaria * $empresas_para_cobrar), 2, ",", ".");
	            }
	            
	            //RECEBE TOTAL DE EMPRESAS
	            $empresas = totalEmpresas($id_usuario);
	            
	            $valor_cobrar = calcularValorCobrar($Config, $empresas, $plano, $valor_plano, $valor_total_devendo);
	            $valor_cobrar = number_format($valor_cobrar, 2, ",", ".");
	            
	            if ($pagamentos_ativo['data_pagamento'] != '') {
	                $data_pagamento = $pagamentos_ativo['data_pagamento'];
	                $mes_boleto     = date('m', strtotime($pagamentos_ativo['data_pagamento']));
	                
	            } else {
	                $sql_dtPagto = "
					SELECT 
						MAX(h.data_pagamento) data_pagamento
					FROM 
						login l
						INNER JOIN 
							historico_cobranca h ON l.id = h.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
					WHERE 
						l.status = 'ativo'
						AND l.id='" . $linha_meus_dados["id"] . "'
					";
	                $rs_dtPagto  = mysql_query($sql_dtPagto);
	                $dtPagto     = mysql_fetch_array($rs_dtPagto);
	                
	                $data_pagamento = date('Y-m-d', strtotime($dtPagto['data_pagamento'] . " + 1 month"));
	                $mes_boleto     = date('m', strtotime($data_pagamento));
	            }
	        } else {
	            // CALCULANDO O TOTAL A SER COBRADO COM O TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
	            //$valor_cobrar = number_format(($mensalidade * ($total_devendo)), 2, ",",".");
	            //$valor_cobrar = number_format(($valor_total_devendo), 2, ",",".");
	            
	            //RECEBE TOTAL DE EMPRESAS
	            $empresas = totalEmpresas($id_usuario);
	            
	            //Calculando o valor a cobrar
	            $valor_cobrar = calcularValorCobrar($Config, $empresas, $plano, $valor_plano, $valor_total_devendo);
	            
	            //Calcula o valor devido
	            
	            
	            //PLANO É MENSAL, COBRANDO TOTAL COM VALORES DEVENDO
	            $valor_cobrar = number_format(($valor_cobrar), 2, ",", ".");
	            
	            // echo ("valor_total_devendo=" . $valor_total_devendo . "<br>");
	            // echo ("valor_cobrar=" . $valor_cobrar . "<br>");
	            
	            // MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	            //		$idHistoricoAtualizar = "('";
	            while ($pagamentos_ativo = mysql_fetch_array($resultado_ativo)) {
	                // CALCULAR O TOTAL DEVEDOR SOMENTE PARA PAGAMENTOS VENCIDOS E NÃO PAGOS
	                if ($pagamentos_ativo['status_pagamento'] == 'a vencer') {
	                    $data_proximo_pagamento = $pagamentos_ativo['data_pagamento'];
	                } else {
	                    $data_pagamento = $pagamentos_ativo['data_pagamento'];
	                    $mes_boleto     = date('m', strtotime($pagamentos_ativo['data_pagamento']));
	                }
	            }
	            
	            if ($fPagto == "boleto" && $total_devendo > 1) {
	                $mes_boleto = "00";
	            }
	            
	        }
	        
	        
	        $data_proximo_pagamento = ($data_proximo_pagamento == '' ? $data_pagamento : $data_proximo_pagamento);
	        
	        //		if($data_pagamento == ''){
	        //			$rs_data_pagamento = mysql_fetch_array(mysql_query("SELECT h.data_pagamento	FROM historico_cobranca h WHERE h.id='" . $linha_meus_dados["id"] . "' AND h.status_pagamento IN ('a vencer') ORDER BY h.idHistorico DESC LIMIT 0,1"));
	        //			$data_pagamento = $rs_data_pagamento['data_pagamento'];
	        //echo $data_pagamento;
	        //		}
	        
	        //		echo "###".$data_pagamento . "###<BR>";
	        
	        $data_vencimento = date('d/m/Y', (mktime(0, 0, 0, date('m', strtotime($data_pagamento)), date('d', strtotime($data_pagamento)) + $dias_vencimento, date('Y', strtotime($data_pagamento)))));
	        
	        //		echo "###".$data_vencimento . "###<BR>";
	        
	        // CALCULANDO O TOTAL A SER COBRADO COM O TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
	        //$valor_cobrar = number_format(($mensalidade * $totalQuitar), 2, ",",".");
	        
	        //		$idHistoricoAtualizar .= "')";
	        //		$idHistoricoAtualizar  = str_replace(",''","",$idHistoricoAtualizar);
	        
	        // NÃO POSSUI NENHUM PAGAMENTO VENCIDO OU NÃO PAGO
	        if ($total_devendo <= 0 && ($total_a_vencer > 0)) { // SE POSSUIR PAGAMENTOS A VENCER E FOR BOLETO DEVE APARECER ESTA MENSAGEM
	            
	?>
		
	            <span class="tituloVermelho">Assinatura ativa
	            <br />
	            <br />
	            </span>
				
				Não existem pendências em sua conta e sua assinatura encontra-se ativa.<br />
				<br />
				Sua próxima mensalidade vence dia <strong><?= date('d/m/Y', strtotime($data_proximo_pagamento)) ?></strong>.<br />
				<br />
				Assinante desde: <strong><?= date('d/m/Y', strtotime($data_inclusao)) ?></strong><br />
				
				<br />
				Para mudar a forma de pagamento, altere ao lado seus Dados de Cobrança. A ação será válida para a sua próxima fatura.<br /><br />

	<?
	            
	            if ($mostra_botao_prox_boleto == true) {
	                $mostra_botao_acao = false;
	                $value_botao       = "Emitir próximo boleto";
	            }
	            if ($mostra_botao_prox_pagamento == true) {
	                $mostra_botao_acao = true;
	                $value_botao       = "Pagar próxima mensalidade";
	            }
	            
	?>
	            Caso queira quitar a próxima mensalidade clique no botão abaixo.<br />
	            <br />

	<?php
	            
	            // POSSUI NENHUM PAGAMENTO VENCIDO OU NÃO PAGO
	        } else {
	            
	            $mostra_botao_acao = true;
	            
	?>

	            <span class="tituloVermelho">Assinatura pendente</span><br />
	            <br />
	            Consta em nosso sistema um débito pendente no valor de <span class="destaque">R$ <?= $valor_cobrar ?></span>. Sua conta ficará sujeita a desativação a partir de <span class="destaque"><strong><?= date('d/m/Y', (mktime(0, 0, 0, date('m', strtotime($data_pagamento)), date('d', strtotime($data_pagamento)) + 5, date('Y', strtotime($data_pagamento))))) ?></strong></span>.<br />
	            <br />

	<?
	            
	            if ($fPagto == "boleto") {
	                
	                $value_botao = "Reemissão de boleto";
	                
	?>
					Para emitir a segunda via do boleto, clique no botão abaixo.<br />
					<br />

	<?php
	                
	            } else {
	                
	                $value_botao = "Quitar assinatura";
	                
	?>

	                Confira ao lado seus dados de cobrança e efetue o pagamento, clicando no botão abaixo.<br />
	                 <br />

	<?php
	            }
	            
	        }
	        
	        break;
	    
	    
	    
	    case 'inativo':
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO INATIVO LOGADO')");
	        
	        $mostra_botao_acao            = true;
	        $mostra_mensagem_cancelamento = true;
	        
	        $value_botao              = "";
	        $dias_vencimento          = 2;
	        $pagina_destino_pagamento = 'minha_conta_quitar_cartao_nova.php';
	        $query_string             = "?atrasados";
	        
	        // DIFERENCA MAIOR QUE ZERO = DATA FUTURA / DATA MENOR QUE ZERO = DATA PASSADA
	        $sql_inativo = "
					SELECT 
						h.id
						, h.status_pagamento
						, h.idHistorico
						, h.data_pagamento
						, d.data_inclusao
						, d.forma_pagameto
						, DATEDIFF(data_pagamento, DATE(NOW())) diferenca 
					FROM 
						login l
						INNER JOIN 
							historico_cobranca h ON l.id = h.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
					WHERE h.status_pagamento IN ('vencido','não pago')
						AND l.id='" . $linha_meus_dados["id"] . "'
					ORDER BY 
						idHistorico ASC
					";
	        
	        $resultado_inativo = mysql_query($sql_inativo) or die(mysql_error());
	        
	        $totalQuitar                     = 0;
	        //		$idHistoricoAtualizar = "";
	        $data_diferenca                  = "";
	        $mensagem_data_pagamento_passada = true; // CONTROLE SE A MENSAGEM SERÁ PASSADA OU FUTURA
	        
	        // TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
	        $total_devendo = mysql_num_rows($resultado_inativo);
	        
	        
	        if ($total_devendo > 0) { // CHECANDO SE O USUARIO TEM PAGAMENTOS vencidos OU nao pagos
	            
	            $mensagem_data_pagamento_passada = true;
	            
	            // MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	            //			$idHistoricoAtualizar = "('";
	            while ($pagamentos_inativo = mysql_fetch_array($resultado_inativo)) {
	                
	                $data_pagamento = $pagamentos_inativo['data_pagamento'];
	                $data_diferenca = $pagamentos_inativo['diferenca'];
	                $mes_boleto     = date('m', strtotime($pagamentos_inativo['data_pagamento']));
	                
	                // VARIAVEL CONTROLA QUANTOS PAGAMENTOS DEVEM SER PAGOS
	                $totalQuitar += 1;
	                
	                //				$idHistoricoAtualizar .= $pagamentos['idHistorico'] . "','";
	                
	            }
	            
	            if ($totalQuitar > 1) {
	                $mes_boleto = "00";
	            }
	            
	        } else { // SE NÃO TIVER PAGAMENTOS vencidos OU não pagos CHECAR SE TEM a vencer
	            
	            $sql_inativo = "
					SELECT 
						h.id
						, h.status_pagamento
						, h.idHistorico
						, h.data_pagamento
						, d.data_inclusao
						, d.forma_pagameto
						, DATEDIFF(data_pagamento, DATE(NOW())) diferenca 
					FROM 
						login l
						INNER JOIN 
							historico_cobranca h ON l.id = h.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
					WHERE h.status_pagamento IN ('a vencer')
						AND l.id='" . $linha_meus_dados["id"] . "'
					ORDER BY 
						idHistorico ASC
					";
	            
	            $resultado_inativo_a_vencer = mysql_query($sql_inativo) or die(mysql_error());
	            
	            // MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
	            //			$idHistoricoAtualizar = "('";
	            if ($pagamentos_inativo = mysql_fetch_array($resultado_inativo_a_vencer)) { // SE TIVER PAGAMENTOS A VENCER, DEVERA GERAR UM BOLETO COM A DATA DESTE PAGAMENTO
	                
	                $mensagem_data_pagamento_passada = false;
	                
	                $data_pagamento = $pagamentos_inativo['data_pagamento'];
	                $data_diferenca = $pagamentos_inativo['diferenca'];
	                $mes_boleto     = date('m', strtotime($pagamentos_inativo['data_pagamento']));
	                
	                // VARIAVEL CONTROLA QUANTOS PAGAMENTOS DEVEM SER PAGOS
	                $totalQuitar += 1;
	                
	                //				$idHistoricoAtualizar .= $pagamentos['idHistorico'] . "','";
	                
	            } else { // SE NÃO TIVER PARAMENTOS a vencer DEVE GERAR UM NOVO
	                
	                $mensagem_data_pagamento_passada = false;
	                
	                $data_vencimento = date('d/m/Y', (mktime(0, 0, 0, date('m'), date('d') + $dias_vencimento, date('Y'))));
	                
	                $dataPagamento = date('Y-m-d', (mktime(0, 0, 0, date('m'), date('d'), date('Y'))));
	                
	                // INSERE UM NOVO a vencer NA DATA DE HOJE PARA EFETUAR A COBRANÇA
	                mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '" . $dataPagamento . "', 'a vencer')");
	                
	                $data_pagamento = $dataPagamento;
	                $data_diferenca = 0;
	                $mes_boleto     = date('m', strtotime($dataPagamento));
	                
	                
	            }
	            
	        }
	        
	        $data_vencimento = date('d/m/Y', (mktime(0, 0, 0, date('m', strtotime($data_pagamento)), date('d', strtotime($data_pagamento)) + $dias_vencimento, date('Y', strtotime($data_pagamento)))));
	        
	        
	        // TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
	        $rs_total_devendo = mysql_query($sql_inativo);
	        
	        $total_devendo = mysql_num_rows($rs_total_devendo);
	        
	        //RECEBE TOTAL DE EMPRESAS
	        $empresas = totalEmpresas($id_usuario);
	        
	        //Aplicando cálculo de valor
	        $valor_total_devendo = $Config->calcularValorEmpresas($valorMensal * $total_devendo, $empresas);
	        $valor_total_devendo = number_format($valor_total_devendo, 2, ",", ".");
	        
	        $valor_cobrar = calcularValorCobrar($Config, $empresas, $plano, $valor_plano, 0);
	        
	        //Formatando valor do boleto
	        $valor_cobrar = number_format($valor_cobrar, 2, ",", ".");
	?>

	        <span class="tituloVermelho">Conta Inativa</span><br />
	        <br />
	        <?
	        if ($mensagem_data_pagamento_passada == true) {
	?>
	          Consta em nosso sistema um débito  pendente no valor  de <span class="destaque">R$ <?= $valor_total_devendo ?></span>. 
	          <!--Sua conta ficará sujeita a desativação desde o dia <span class="destaque"><strong><?= date('d/m/Y', (mktime(0, 0, 0, date('m', strtotime($data_pagamento)), date('d', strtotime($data_pagamento)), date('Y', strtotime($data_pagamento))))) ?></strong></span>.--><br />
	          <br />
					<?
	        } else {
	?>
	          Seu próximo pagamento, no valor de <span class="destaque">R$ <?= $valor_cobrar ?></span> está previsto para <span class="destaque"><?= date('d/m/Y', (mktime(0, 0, 0, date('m', strtotime($data_pagamento)), date('d', strtotime($data_pagamento)), date('Y', strtotime($data_pagamento))))) ?></span>.<br />
	          <br />
	        <?
	        }
	        
	        
	        if ($forma_pagamento_assinante == "boleto") {
	            
	            if ($mensagem_data_pagamento_passada == true) {
	                $value_botao = "Reemissão de boleto";
	?>
	            Para emitir a segunda via do boleto, clique no botão abaixo.<br />
	            <br />
	             Sua conta será reativada em até 48 horas após o pagamento.<br />
	            <br />
	<?php
	                
	            } else {
	                $value_botao = "Emissão de boleto";
	?>
	            Para emitir o boleto, clique no botão abaixo.<br />
	            <br />
	<?php
	                
	            }
	            
	        } else {
	            
	            $value_botao = "Quitar assinatura";
	            
	?>
	            Confira ao lado seus dados de cobrança e efetue o pagamento, clicando no botão abaixo.<br />
	            <br />
	            Sua conta será reativada automaticamente.<br />
	            <br />
	<?php
	            
	        }
	        
	        
	        break;
	    
	    
	    
	    case 'cancelado':
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO CANCELADO LOGADO')");
	        
	        $mostra_botao_acao = true;
	        
	        $value_botao              = "Ativar Assinatura";
	        //$valor_cobrar = number_format($mensalidade, 2, ",",".");
	        $dias_vencimento          = 2;
	        $pagina_destino_pagamento = 'minha_conta_quitar_cartao_nova.php'; //'minha_conta_ativar.php';
	        $query_string             = "?atrasados";
	        
	        $dataPagamento = date('Y-m-d', (mktime(0, 0, 0, date('m'), date('d'), date('Y'))));
	        
	        $data_vencimento = date('d/m/Y', (mktime(0, 0, 0, date('m'), date('d') + $dias_vencimento, date('Y'))));
	        
	        $data_pagamento = $dataPagamento;
	        $data_diferenca = 0;
	        $mes_boleto     = date('m', strtotime($dataPagamento));
	        
	        //RECEBE TOTAL DE EMPRESAS
	        $empresas = totalEmpresas($id_usuario);
	        
	        //Calcula valor a pagar
	        $valor_cobrar = calcularValorCobrar($Config, $empresas, $plano, $valor_plano, 0);
	        
	        $valor_cobrar = number_format($valor_cobrar, 2, ",", ".");
	?>

	        <span class="tituloVermelho">Assinatura Cancelada</span><br />
	        <br /> 
	       	Se você deseja voltar ao Contador Amigo, aqui vai uma proposta: reative agora mesmo sua conta pagando apenas <span class="destaque">R$ <?= $mensalidade ?>,00</span>. Seus débitos anteriores serão perdoados.<br /> 
	        <br /> 
	        <strong>Preencha os dados de cobrança ao lado</strong> e clique no botão &quot;Ativar Assinatura&quot; para efetuar o pagamento.<br />
	        <br /> 
	<?
	        
	        break;
	        
	        
	        
	        
	}


	if ($mostra_botao_prox_boleto == true) { // SOMENTE PARA ATIVOS QUE TEM UM BOLETO A VENCER
	?>

	<div style="width: 100%; clear: both;">
	    <form style="text-align:center">
	        <input type="button" name="button2" id="button2" value="<?= $value_botao ?>" onclick="<?php
	    
	    $sql_dados_empresa = "SELECT sacado,endereco,bairro,cidade,cep,uf FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "' LIMIT 0, 1";
	    $resultado_dados_empresa = mysql_query($sql_dados_empresa) or die(mysql_error());
	    $linha_dados_empresa = mysql_fetch_array($resultado_dados_empresa);
	    
	    // LOG DE ACESSOS
	    mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO " . strtoupper($linha_meus_dados['status']) . " BOLETO a vencer A EMITIR " . $mes_boleto . " de " . date('Y', strtotime($data_pagamento)) . " ')");
	    
	    
	    /*            echo "abreJanela('https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . $valor_cobrar . "&numdoc=" . str_pad($_SESSION["id_userSecao"] . $mes_boleto . date('Y',strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT) . "&sacado=" . trataTxt($linha_dados_empresa['razao_social']) . "&cgccpfsac=&enderecosac=" . trataTxt($linha_dados_empresa['endereco']) ."&numeroendsac=&complementosac=&bairrosac=" . trataTxt($linha_dados_empresa["bairro"]) . "&cidadesac=" . trataTxt($linha_dados_empresa['cidade']) . "&cepsac=" . $linha_dados_empresa['cep'] . "&ufsac=" . trataTxt($linha_dados_empresa['estado']) . "&datadoc=" . date("d/m/Y") . "&vencto=" . date('d/m/Y',strtotime($data_proximo_pagamento)) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1','_blank','width=676, height=500, top=150, left=150, scrollbars=yes, resizable=yes');";*/
	    if ($linha_dados_empresa['sacado'] == '') {
	        
	        echo "alert('Preencha os dados de cobrança ao lado.')";
	        
	    } else {
	        
	        echo "abreJanela('https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . urlencode($valor_cobrar) . "&numdoc=" . urlencode(str_pad($_SESSION["id_userSecaoMultiplo"] . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT)) . "&sacado=" . urlencode(trataTxt($linha_dados_empresa['sacado'])) . "&cgccpfsac=&enderecosac=" . urlencode(trataTxt($linha_dados_empresa['endereco'])) . "&numeroendsac=&complementosac=&bairrosac=" . urlencode(trataTxt($linha_dados_empresa["bairro"])) . "&cidadesac=" . urlencode(trataTxt($linha_dados_empresa['cidade'])) . "&cepsac=" . urlencode($linha_dados_empresa['cep']) . "&ufsac=" . urlencode(trataTxt($linha_dados_empresa['uf'])) . "&datadoc=" . urlencode(date("d/m/Y")) . "&vencto=" . urlencode(date('d/m/Y', strtotime($data_proximo_pagamento))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1','_blank','width=676, height=500, top=150, left=150, scrollbars=yes, resizable=yes');";
	        
	    }
	    //&vencto = " . date('d/m/Y',(mktime(0,0,0,date('m'),date('d') + $dias_vencimento ,date('Y')))) . "
	?>" />
	    </form>
	</div>
	<?
	}


	if ($mostra_botao_acao == true) {
	?>

	<div style="width: 100%; clear: both;">
	    <form style="text-align:center">
	        <input type="button" name="button2" id="button2" value="<?= $value_botao ?>" onclick="<?php
	    
	    $sql_dados_empresa = "SELECT * FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "' LIMIT 0, 1";
	    $resultado_dados_empresa = mysql_query($sql_dados_empresa) or die(mysql_error());
	    $linha_dados_empresa = mysql_fetch_array($resultado_dados_empresa);
	    
	    if ($forma_pagamento_assinante == "" || $linha_dados_empresa['sacado'] == '') {
	        
	        echo "alert('Preencha o plano de assinatura, dados de cobrança e forma de pagamento antes de ativar sua assinatura.')";
	        
	    } else if ($forma_pagamento_assinante == "boleto") {
	        
	        
	        // LOG DE ACESSOS
	        mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO " . strtoupper($linha_meus_dados['status']) . " BOLETO A EMITIR " . $mes_boleto . " de " . date('Y', strtotime($data_pagamento)) . " ')");
	        
	        if ($linha_meus_dados['status'] == 'demoInativo' || $linha_meus_dados['status'] == 'cancelado' || $demo_vencido == true) {
	            
	            if ($linha_dados_empresa['sacado'] == '') {
	                
	                $link_boleto = "alert('Preencha os dados de cobrança ao lado.')";
	                
	            } else {
	                
	                $link_boleto = "abreJanelaRedirect('minha_conta_altera_status_pagamento.php','" . urlencode("https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . urlencode($valor_cobrar) . "&numdoc=" . urlencode(str_pad($_SESSION["id_userSecaoMultiplo"] . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT)) . "&sacado=" . urlencode(trataTxt($linha_dados_empresa['sacado'])) . "&cgccpfsac=&enderecosac=" . urlencode(trataTxt($linha_dados_empresa['endereco'])) . "&numeroendsac=&complementosac=&bairrosac=" . urlencode(trataTxt($linha_dados_empresa["bairro"])) . "&cidadesac=" . urlencode(trataTxt($linha_dados_empresa['cidade'])) . "&cepsac=" . urlencode($linha_dados_empresa['cep']) . "&ufsac=" . urlencode(trataTxt($linha_dados_empresa['uf'])) . "&datadoc=" . urlencode(date("d/m/Y")) . "&vencto=" . urlencode($data_vencimento) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1") . "','_blank','width=676, height=500, top=150, left=150, scrollbars=yes, resizable=yes');";
	                
	            }
	            echo $link_boleto;
	            
	        } else {
	            
	            if ($linha_dados_empresa['sacado'] == '') {
	                
	                $link_boleto = "alert('Preencha os dados de cobrança ao lado.')";
	                
	            } else {
	                
	                $link_boleto = "abreJanela('https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . urlencode($valor_cobrar) . "&numdoc=" . urlencode(str_pad($_SESSION["id_userSecaoMultiplo"] . $mes_boleto . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT)) . "&sacado=" . urlencode(trataTxt($linha_dados_empresa['sacado'])) . "&cgccpfsac=&enderecosac=" . urlencode(trataTxt($linha_dados_empresa['endereco'])) . "&numeroendsac=&complementosac=&bairrosac=" . urlencode(trataTxt($linha_dados_empresa["bairro"])) . "&cidadesac=" . urlencode(trataTxt($linha_dados_empresa['cidade'])) . "&cepsac=" . urlencode($linha_dados_empresa['cep']) . "&ufsac=" . urlencode(trataTxt($linha_dados_empresa['uf'])) . "&datadoc=" . urlencode(date("d/m/Y")) . "&vencto=" . urlencode(date('d/m/Y', (mktime(0, 0, 0, date('m'), date('d') + $dias_vencimento, date('Y'))))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1','_blank','width=676, height=500, top=150, left=150, scrollbars=yes, resizable=yes');";
	            }
	            echo $link_boleto;
	            
	        }
	        
	        
	    } else {
	        
	        if ($data_diferenca < (int) (-5)) {
	            $reativar_conta = "?reativar_conta";
	        }
	        echo "abreDiv2('divCarregando');location.href='" . $pagina_destino_pagamento . "" . $query_string . "" . $reativar_conta . "'";
	        //echo "abreDiv2('divErroTemporarioCartao');";
	        
	        
	    }
	?>" />
	    </form>
	</div>
	<?
	}


	if ($mostra_mensagem_ajuda == true) {
	?>

	  <div style="width: 100%; margin-top: 15px; clear: both;">
	  Em caso de dúvida, entre em contato conosco  através da página de <a href="suporte.php">help desk</a>.
	  </div>
	<?php
	}

	if ($mostra_mensagem_cancelamento == true) {
	?>

	  <div style="width: 100%; margin-top: 15px; clear: both; font-size:10px">
	  Para cancelar sua assinatura <a href="minha_conta_cancela.php" style="color:#666;">clique aqui</a>.
	  </div>
	<?php
	}
	?>

	<div id="divCarregando" style="margin-top:10px; text-align:center; <?= "display:none" ?>"><img src="images/loading.gif" width="16" height="16" /> Carregando, por favor aguarde...</div>

	<!--<div id="divErroTemporarioCartao" style="display: none;clear: both; margin-top: 15px;">
	    <span class="tituloVermelho">Desculpe-nos, devido a um problema técnico o pagamento por cartão está temporariamente inativo. Estamos trabalhando para restabelecer a comunicação o mais brevemente possível. Enquando isso, por favor, utilize o pagamento por boleto. Se precisar de liberação imediata, envie-nos o comprovamente pelo Help Desk e seu acesso será liberado na hora.</span><br />
	    <br />
	</div>-->

	<?php
	if (isset($_GET["reemitir"])) {
	    //Usuário clicou no botão "Imprimir Boleto" da "Área Restrita", exibindo mensagem pois o bloqueador de popup impede abertura automática
	?>
		<script>
			 alert("Clique no botão 'Reemissão de Boleto'");
		</script>
	<?php
	}
	?>
	<?php

	if (isset($_GET["sucesso"])) {
	    
	?>
	    <div style="clear: both; margin-top: 15px;">

	        <span class="tituloVermelho">Transação efetuada com sucesso!</span><br />
	        <br />
	        
	    </div>
	<?php
	    
	}
	if (isset($_GET["erro_cartao"])) {
	    
	?>
	    <div style="clear: both; margin-top: 15px;">

	        <span class="tituloVermelho">Ocorreu um erro durante a transação</span><br />
	        <br />
	        
	    </div>
	<?php
	    
	}
	?>
			</div>


	</div>


	<div style="float:left; width:500px">
	<form name="form_assinante" id="form_assinante" method="post" action="minha_conta_dados_cobranca_gravar_nova.php" style="display:inline">

	<div class="tituloVermelho" style="margin-bottom:20px">Assinante</div>


	  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
	    <tr>
	      <td align="right" valign="middle" class="formTabela">Assinante:</td>
	      <td class="formTabela"><input name="txtAssinante" type="text" id="txtAssinante" style="width:300px; margin-bottom:0px" value="<?= $assinante ?>" maxlength="200"  alt="Assinante"  /></td>
	    </tr>
	    <tr>
	      <td align="right" valign="middle" class="formTabela">E-mail:</td>
	      <td class="formTabela"><input name="txtEmail" type="text" id="txtEmail" style="width:300px; margin-bottom:0px" value="<?= $email_usuario ?>" maxlength="200"  alt="E-mail"  />
	      <input type="hidden" name="hddEmailUser" id="hddEmailUser" value="<?= $email_usuario ?>" />
	      <div name="divPass" id="divPass" style="display:none"></div>
	      </td>
	    </tr>
	    <tr>
	      <td align="right" valign="top" class="formTabela">Senha: </td>
	      <td class="formTabela">
	      
	      
	      <span style="margin-right:5px"><?= $senha ?></span><a href="JavaScript:abreDiv('divSenha');">Alterar senha</a>
	        <div id="divSenha" style="display:none; clear:both">
	          <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
	            <tr>
	              <td align="right" class="formTabela">Nova Senha:</td>
	              <td class="formTabela">
	              <input name="passNovaSenha" type="password" id="passNovaSenha" style="width:90px; margin-bottom:0px" value="" maxlength="10"/>
	              <span style="font-size:10px"> Máximo 10 caracteres.</span>
	                </td>
	            </tr>
	            <tr>
	              <td align="right" valign="middle" class="formTabela">Confirmar:</td>
	              <td class="formTabela"><input name="passConfirmaSenha" type="password" id="passConfirmaSenha" style="width:90px; margin-bottom:0px" maxlength="10"/></td>
	            </tr>
	          </table>
	        </div>
	      
	      
	      
	      
	      </td>
	    </tr>
	    <tr>
	      <td align="right" valign="middle" class="formTabela">Telefone:</td>
	      <td valign="middle" class="formTabela"><div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
	        <div style="float:left">
	          <input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:30px" value="<?= $pref_telefone ?>" maxlength="2" alt="Prefixo do Telefone" class="campoDDDTESTE" />
	        </div>
	        <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
	        <div style="float:left">
	          <input name="txtTelefone" type="text" id="txtTelefone" style="width:75px" value="<?= $telefone ?>" maxlength="9" alt="Telefone" />
	        </div></td>
	    </tr>
	    <tr>
	      <td colspan="2" valign="middle" class="formTabela"><input type="hidden" name="hidID" id="hidID" value="<?= $linha_meus_dados["id"] ?>" /></td>
	    </tr>    
	  </table>
	  <div style="margin-top:10px;margin-bottom:20px">
		<input name="btnSalvar" type="button" id="btnSalvar"  value="Salvar" />
	</div>
	</form>
<form name="form_assinante" id="form_assinante_plano" method="post" action="minha_conta_plano_gravar_nova.php" style="display:inline">
	  <div style="margin-top:20px;margin-bottom:20px" class="tituloVermelho">Plano de Assinatura</div>
	<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
		
	<?php
	foreach ($valores as $item) {
	    $plano_nome = $Config->verPlano($item['configuracao']);
	    $meses      = $Config->verMeses($item['configuracao']);
	?>
		<tr>

			<td><input type="radio" name="radPlano" id="radPlano_<?php
	    echo ($item["configuracao"]);
	?>" value="<?php
	    echo ($item["configuracao"]);
	?>" <?php
	    echo checked($item["configuracao"], $plano);
	?> /></td>
			<td><label for="radPlano_<?php
	    echo ($item["configuracao"]);
	?>"> <?php
	    echo ($Config->exibirDesconto($item["configuracao"], $plano_nome, $item["valor"], $valorMensal, $meses));
	?></label></td>

		</tr>

	<?php
	}
	?>

	</table>

	<p style="margin: 0;margin-top: 5px;">Valor cobrado por empresa.</p>

	


	<?php
	$data = date('d', strtotime($data_inclusao));

	if ($data == 31) {
	    $data = '1';
	}
	if (date('d/m', strtotime($data_inclusao)) == "29/02") {
	    $data = '28';
	}

	?>
	<div style="margin-top:10px;margin-bottom:20px">
		<input type="hidden" name="hidID" value="<?= $id_usuario ?>" />
		<input name="btnSalvaPlanor" type="button" id="btnSalvarPlano"  value="Salvar" />
	</div>
	</form>

	<form name="form_forma_pagamento" id="form_forma_pagamento_cobranca" method="post" action="minha_conta_forma_pagamento_nova.php" style="display:inline">
	<input type="hidden" name="hddStatus" id="hddStatus" value="<?= $linha_meus_dados['status'] ?>">
	<div style="margin-bottom:20px" class="tituloVermelho">Dados de cobrança</div>


	<div id="divPagamentoBoleto" style="margin-bottom:20px; <?php //if ($forma_pagamento_assinante != "boleto") {echo 'display:none';} 
	?>">
	  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
	    <tr>
	      <td valign="middle" class="formTabela">
		  <?php
	$sql = "SELECT * FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "' LIMIT 0, 1";
	$resultado = mysql_query($sql) or die(mysql_error());
	$linha2 = mysql_fetch_array($resultado);
	?>
	        <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
	          <tr>
	            <td align="right" valign="middle" class="formTabela">Tipo:</td>
	            <td class="formTabela">
	            	<input type="radio" name="rdbTipo" id="boleto_PJ" value="J" <?= strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'checked' : '' ?>> <label for="boleto_PJ">Pessoa Jurídica</label>
	              &nbsp;
	              <input type="radio" name="rdbTipo" id="boleto_PF" value="F" <?= strlen($linha2['documento']) <= 14 && $linha2['documento'] != '' ? 'checked' : '' ?>> <label for="boleto_PF">Pessoa Física</label>
	            </td>
	          </tr>
	          </table>
	          
	       <script>
		   function checkRadio() {
			   var rdbTipo = "";
			   var len = document.getElementById(form_forma_pagamento).rdbTipo.lenght;
			   var i;
			   
			   for (i = 0; i<len; i++) {
				   if (document.getElementById(form_forma_pagamento).rdbTipo[i].checked) {
					   rdbTipo = document.getElementById(form_forma_pagamento).rdbTipo[i].value;
					   break
				   }
			   }
			   
			   if (rdbTipo == "" ) {
				   alert ("Informe se a cobrança será na Pessoa Jurídica ou na Física");
				   return false;
			   }
			   
			}
				   
		   </script>   
	          
	          <table>
	          <tr>
	            <td colspan="2" style="height:10px;"></td>
	          </tr>
	          <tr>
	            <td align="right" valign="middle" class="formTabela" id="txtSacado"><?= strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'Razão Social' : 'Nome' ?>:</td>
	            <td class="formTabela"><input type="text" name="boleto_sacado" id="boleto_sacado" maxlength="200" style="width: 100%" value="<?= $linha2['sacado'] ?>" alt="<?= strlen($linha2['documento']) > 14 ? 'Razão Social' : 'Nome' ?>"></td>
	          </tr>
	          <tr>
	            <td align="right" valign="middle" class="formTabela" id="txtDocumento"><?= strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'CNPJ' : 'CPF' ?>:</td>
	            <td class="formTabela">
	            <input type="text" name="boleto_cnpj" id="boleto_cnpj" maxlength="18" size="18" class="campoCNPJ" value="<?= strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? $linha2['documento'] : '' ?>" style="display: <?= strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'block' : 'none' ?>;" alt="CNPJ">
	            <input type="text" name="boleto_cpf" id="boleto_cpf" maxlength="14" size="14" class="campoCPF" value="<?= strlen($linha2['documento']) <= 14 ? $linha2['documento'] : '' ?>" style="display: <?= strlen($linha2['documento']) <= 14 && $linha2['documento'] != '' ? 'block' : 'none' ?>;" alt="CPF">
	            </td>
	          </tr>
	          <tr>
	            <td align="right" valign="middle" class="formTabela">Endereço:</td>
	            <td class="formTabela"><input type="text" name="boleto_endereco" id="boleto_endereco" maxlength="75" style="width: 100%" value="<?= $linha2['endereco'] ?>" alt="Endereço"></td>
	          </tr>
	          <tr>
	            <td align="right" valign="middle" class="formTabela">Bairro:</td>
	            <td class="formTabela"><input type="text" name="boleto_bairro" id="boleto_bairro" maxlength="30" style="width: 100%" value="<?= $linha2['bairro'] ?>" alt="Bairro"></td>
	          </tr>
	          <tr>
	            <td align="right" valign="middle" class="formTabela">UF:</td>
	            <td class="formTabela">
	              <select name="selEstado" id="selEstado" alt="UF">
	                  <option value="" <?php
	echo selected('', $linha2['uf']);
	?>></option>
	      <?

	foreach ($arrEstados as $dadosEstado) {
	    echo "<option value=\"" . $dadosEstado['id'] . ";" . $dadosEstado['sigla'] . "\" " . selected($dadosEstado['sigla'], $linha2['uf']) . " >" . $dadosEstado['sigla'] . "</option>";
	    if ($dadosEstado['sigla'] == $linha2['uf']) {
	        $idEstadoSelecionado = $dadosEstado['id'];
	    }
	}

	?>
	              </select>
	            </td>
	          </tr>
	          <tr>
	            <td align="right" valign="middle" class="formTabela">Cidade:</td>
	            <td class="formTabela">
	              <select name="txtCidade" id="txtCidade" style="width:300px" class="comboM" alt="Cidade">
	                <option value="" <?php
	echo selected('', $cidade);
	?>></option>
	              <?
	if ($idEstadoSelecionado != '') {
	    $sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
	    $result = mysql_query($sql) or die(mysql_error());
	    while ($cidades = mysql_fetch_array($result)) {
	        echo "<option value=\"" . $cidades['cidade'] . "\" " . selected($cidades['cidade'], $linha2['cidade']) . " >" . $cidades['cidade'] . "</option>";
	    }
	}
	?>
	              </select>
	            </td>
	          </tr>
	          <tr>
	            <td align="right" valign="middle" class="formTabela">CEP:</td>
	            <td class="formTabela"><input type="text" name="boleto_cep" id="boleto_cep" maxlength="9" size="12" class="campoCEP" value="<?= $linha2['cep'] ?>" alt="CEP"></td>
	          </tr>
	        </table>
	      </td>
	    </tr>
	  </table>
	</div>
	<div style="margin-bottom:20px;"><input type="hidden" name="hidID" id="hidID" value="<?= $id_usuario ?>" /><input name="btnSalvar5" type="button" onclick="formFormaPagamentoSubmit()" value="Salvar" /></div>
</form>
	<form name="form_forma_pagamento" id="form_forma_pagamento2" method="post" action="minha_conta_forma_pagamento2_nova.php" style="display:inline">
	<div style="margin-bottom:20px"><span class="tituloVermelho">Forma de Pagamento</span>
	<br />
	<br />

	    <label>
	    	<input type="radio" name="radFormaPagamento" id="radFormaPagamento1" value="visa" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php	echo checked('visa', $forma_pagamento_assinante);?> />
	      	<img src="images/logo-visa.png" width="47" height="27" align="center" style="margin-right:15px" title="" alt="" />
	      </label>
	    <label>
	    	<input type="radio" name="radFormaPagamento" id="radFormaPagamento2" value="mastercard" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo checked('mastercard', $forma_pagamento_assinante);?> />
	    	<img src="images/logo-master.png" width="39" height="27" align="center" style="margin-right:15px" title="Pagar com Mastercard" alt="Mastercard" />
	    </label>
	    <label>
	    	<input type="radio" name="radFormaPagamento" id="radFormaPagamento4" value="elo" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo checked('elo', $forma_pagamento_assinante);?> />
			<img src="images/logo-elo.png" width="31" height="27" align="center" style="margin-right:15px" title="Pagar com Elo" alt="Elo" />
		</label>
	    <label>
	    	<input type="radio" name="radFormaPagamento" id="radFormaPagamento5" value="amex" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo checked('amex', $forma_pagamento_assinante);?> />
			<img src="images/logo-amex.png" width="25" height="26" align="center" style="margin-right:15px" title="Pagar com American Express" alt="American Express">
		</label>
		<label>
	    	<input type="radio" name="radFormaPagamento" id="radFormaPagamento6" value="diners" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo checked('diners', $forma_pagamento_assinante);?> />
			<img src="images/logo-diners.png" width="36" height="27" align="center" style="margin-right:15px" title="Pagar com Diners" alt="Diners Club">
		</label>
	    <label>
	    	<input type="radio" name="radFormaPagamento" id="radFormaPagamento3" value="boleto" onclick="Javascript:fechaDiv('divPagamentoCartao')" <?php	echo checked('boleto', $forma_pagamento_assinante);?> />
    	    <img src="images/boletoicon.gif" width="39" height="20" align="center" style="margin-right:15px" />
    	</label>	      
	 </div>     


	<div id="divPagamentoCartao" style="margin-top:3px; margin-bottom:-3px; <?php
	if (($forma_pagamento_assinante == "boleto") or ($forma_pagamento_assinante == "")) {
	    echo 'display:none';
	}
	?>">

	<?php 
		//MAL Os dados dod cartão agora são armazenados na tabela token_pagamento
		$consulta_numero_cartao = mysql_query("SELECT * FROM token_pagamento WHERE id_user = '".$_SESSION["id_userSecaoMultiplo"]."' ");
		$token=mysql_fetch_array($consulta_numero_cartao);

		if( $forma_pagamento_assinante != "boleto" && $forma_pagamento_assinante != "" ){
			echo '
				<script>

					var temp_cartao;
					var temp_nome_titular;

					$( document ).ready(function() {
 						$("#campo_codigo_seguranca").fadeOut(0);
						$("#campo_data_validade").fadeOut(0);
						$("#campo_botao_salvar").fadeOut(0);
						$("#campo_botao_alterar_dados").fadeIn(0);


						$("#campo_botao_alterar_dados_botao").click(function() {
							$("#campo_codigo_seguranca").fadeIn(0);
							$("#campo_data_validade").fadeIn(0);
							$(this).fadeOut(0);
							$("#campo_botao_salvar").fadeIn(0);
							$("#campo_botao_cancelar_botao").fadeIn(0);

							temp_cartao = $("#txtNumeroCartao").val();
							$("#txtNumeroCartao").val("");
							temp_nome_titular = $("#txtNomeTitular").val();
							$("#txtNomeTitular").val("");
							
						});


						$("#campo_botao_cancelar_botao").click(function() {

							$("#txtNumeroCartao").val(temp_cartao);
							$("#txtNomeTitular").val(temp_nome_titular);

							$("#campo_codigo_seguranca").fadeOut(0);
							$("#campo_data_validade").fadeOut(0);
							$(campo_botao_alterar_dados_botao).fadeIn(0);
							$("#campo_botao_salvar").fadeOut(0);
							$("#campo_botao_cancelar_botao").fadeOut(0);
							
						});
	
						$("#radFormaPagamento3").click(function() {

							if(temp_cartao != "")
								$("#txtNumeroCartao").val(temp_cartao);
							if(temp_nome_titular != "")
								$("#txtNomeTitular").val(temp_nome_titular);

							$("#campo_codigo_seguranca").fadeOut(0);
							$("#campo_data_validade").fadeOut(0);
							$(campo_botao_alterar_dados_botao).fadeIn(0);
							$("#campo_botao_salvar").fadeIn(0);
							$("#campo_botao_cancelar_botao").fadeOut(0);
							$("#campo_botao_alterar_dados_botao").fadeOut(0);
							
							$("#divPagamentoCartao").fadeIn(0);
							$("#divPagamentoCartao").css("opacity","0.4");

							$("#txtNumeroCartao").attr("disabled","disabled");
							$("#txtNomeTitular").attr("disabled","disabled");

						});

						$("#radFormaPagamento1").click(function() {

							$("#txtNumeroCartao").val(temp_cartao);
							$("#txtNomeTitular").val(temp_nome_titular);

							$("#campo_codigo_seguranca").fadeOut(0);
							$("#campo_data_validade").fadeOut(0);
							$(campo_botao_alterar_dados_botao).fadeIn(0);
							$("#campo_botao_salvar").fadeOut(0);
							$("#campo_botao_cancelar_botao").fadeOut(0);

							$("#divPagamentoCartao").css("opacity","1");

							$("#txtNumeroCartao").attr("disabled","");
							$("#txtNomeTitular").attr("disabled","");
							
						});

						$("#radFormaPagamento2").click(function() {

							$("#txtNumeroCartao").val(temp_cartao);
							$("#txtNomeTitular").val(temp_nome_titular);

							$("#campo_codigo_seguranca").fadeOut(0);
							$("#campo_data_validade").fadeOut(0);
							$(campo_botao_alterar_dados_botao).fadeIn(0);
							$("#campo_botao_salvar").fadeOut(0);
							$("#campo_botao_cancelar_botao").fadeOut(0);

							$("#divPagamentoCartao").css("opacity","1");

							$("#txtNumeroCartao").attr("disabled","");
							$("#txtNomeTitular").attr("disabled","");
							
						});


						$("#radFormaPagamento4").click(function() {

							$("#txtNumeroCartao").val(temp_cartao);
							$("#txtNomeTitular").val(temp_nome_titular);

							$("#campo_codigo_seguranca").fadeOut(0);
							$("#campo_data_validade").fadeOut(0);
							$(campo_botao_alterar_dados_botao).fadeIn(0);
							$("#campo_botao_salvar").fadeOut(0);
							$("#campo_botao_cancelar_botao").fadeOut(0);

							$("#divPagamentoCartao").css("opacity","1");

							$("#txtNumeroCartao").attr("disabled","");
							$("#txtNomeTitular").attr("disabled","");
							
						});

						$("#radFormaPagamento5").click(function() {

							$("#txtNumeroCartao").val(temp_cartao);
							$("#txtNomeTitular").val(temp_nome_titular);

							$("#campo_codigo_seguranca").fadeOut(0);
							$("#campo_data_validade").fadeOut(0);
							$(campo_botao_alterar_dados_botao).fadeIn(0);
							$("#campo_botao_salvar").fadeOut(0);
							$("#campo_botao_cancelar_botao").fadeOut(0);

							$("#divPagamentoCartao").css("opacity","1");

							$("#txtNumeroCartao").attr("disabled","");
							$("#txtNomeTitular").attr("disabled","");
							
						});

						$("#radFormaPagamento6").click(function() {

							$("#txtNumeroCartao").val(temp_cartao);
							$("#txtNomeTitular").val(temp_nome_titular);

							$("#campo_codigo_seguranca").fadeOut(0);
							$("#campo_data_validade").fadeOut(0);
							$(campo_botao_alterar_dados_botao).fadeIn(0);
							$("#campo_botao_salvar").fadeOut(0);
							$("#campo_botao_cancelar_botao").fadeOut(0);

							$("#divPagamentoCartao").css("opacity","1");

							$("#txtNumeroCartao").attr("disabled","");
							$("#txtNomeTitular").attr("disabled","");
							
						});
	



					});		
				</script>
			';
		}

	?>
			
				
	  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
	    <tr>
	      <td align="right" valign="middle" class="formTabela">Número do Cartão:</td>
	      <td class="formTabela"><input name="txtNumeroCartao" type="text" id="txtNumeroCartao" style="width:125px; margin-bottom:0px" value="<?= $token['numero_cartao'] ?>" maxlength="16"  alt="Número do Cartão" onClick="this.setSelectionRange(0, this.value.length)" /></td>
	      </tr>
	    <tr>
	      <td align="right" valign="middle" class="formTabela">Nome do Titular:</td>
	      <td class="formTabela"><input name="txtNomeTitular" type="text" id="txtNomeTitular" style="width:170px; margin-bottom:0px" value="<?= $token['nome_titular'] ?>" maxlength="200"  alt="Nome do Titular" onClick="this.setSelectionRange(0, this.value.length)"  />
	      <span style="clear: both; font-size:10px">Como consta no cartão</span></td>
	    </tr>
	    <tr id="campo_codigo_seguranca">
	      <td align="right" valign="middle" class="formTabela">Código de Segurança:</td>
	      <td class="formTabela"><input name="txtCodigo" type="text" id="txtCodigo" style="width:35px; margin-bottom:0px" value="" maxlength="3"  alt="Código de Segurança"  /></td>
	    </tr>
	    <tr id="campo_data_validade">
	      <td align="right" valign="middle" class="formTabela">Data de Validade:</td>
	      <td class="formTabela"><input name="txtDataValidade" type="text" id="txtDataValidade" style="width:60px; margin-bottom:0px" value="" maxlength="8"  alt="Data de Validade"  />
	      <span style="font-size:10px"> MM/AAAA</span></td>
	    </tr>
	    <tr>
	      <td colspan="2" valign="middle" class="formTabela">&nbsp;</td>
	    </tr>
	  </table>
	</div>


	    <div id="campo_botao_salvar"><input type="hidden" name="hidID" id="hidID" value="<?= $id_usuario ?>" /><input name="btnSalvar2" type="button" id="btnSalvar2"  value="Salvar" onclick="formFormaPagamentoSubmit2()" /><input type="button" id="campo_botao_cancelar_botao"  value="Cancelar" style="display:none;margin-left:20px;"/></div>
	    <div id="campo_botao_alterar_dados" style="display:none"><input type="button" id="campo_botao_alterar_dados_botao"  value="Alterar Dados do Cartão"/></div>
	</form>    
	</div>

	<div style="clear:both; height:20px;"> </div>

	</div>

	<?php
	include 'rodape.php';
	?>


	<?
	// EM 08/11/2013 - SE VEIO O AVISO DE PENDENCIA FINANCEIRA MOSTA O ALERTA
	if ($_REQUEST['aviso'] == 1) {
	    
	    if ($linha_meus_dados["status"] == 'cancelado' || $linha_meus_dados["status"] == 'demoInativo') {
	?>
			<script>
	      $(document).ready(function(e) {
	        if(confirm('Deseja reativar sua assinatura nesse momento?')){
						<?
	        if ($forma_pagamento_assinante == "boleto") {
	?>
		          <?= $link_boleto ?>
						<?
	        } else {
	?>
		          abreDiv2('divCarregando');
		          location.href="<?= $pagina_destino_pagamento . "" . $reativar_conta ?>";
							//abreDiv2('divErroTemporarioCartao');
						<?
	        }
	?>
	        }
	      });
	    </script>

	<?
	    } else {
	        $sql_checa_pendencias_cartao = "SELECT * FROM historico_cobranca WHERE id = '" . $linha_meus_dados["id"] . "' AND status_pagamento IN ('vencido','não pago')";
	        $total_pendencias_cartao     = mysql_num_rows(mysql_query($sql_checa_pendencias_cartao));
	if($forma_pagamento_assinante == "boleto")
			$mensagem_alert = 'Dejeja emitir o boleto agora?';
		else
			$mensagem_alert = 'Deseja efetuar o pagamento agora?';
?>
		<script>
      $(document).ready(function(e) {
        if(confirm('Verificamos que sua conta está com <?=$total_pendencias_cartao?> mensalidade<?=$total_pendencias_cartao > 1 ? "s" : ""?> em atraso. <?php echo $mensagem_alert; ?>')){
					<? if($forma_pagamento_assinante == "boleto"){ ?>
	          <?=$link_boleto?>
					<? } else { ?>
//							abreDiv2('divErroTemporarioCartao');
	          abreDiv2('divCarregando');
	          location.href="<?=$pagina_destino_pagamento."".$reativar_conta?>";
					<? } ?>
        }
      });
    </script>
	<?php
	    }
	    
	}
?>

<?php 

	//MAL Exibe erros

	if( isset( $_GET['erro_cartao'] ) && $_GET['erro_cartao'] == 'token' ):
		
		echo '<script>
		
			alert("Ocorreu um erro durante a transação, tente novamente");
		
		</script>';
	
	endif;

?>
