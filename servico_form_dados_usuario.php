<?php 

//	 ini_set('display_errors',1);
//	 ini_set('display_startup_erros',1);
//	 error_reporting(E_ALL);	

	//	Pega os dados do get e joga na sessão e depois redireciona chamando a página limpa para que o usuáiro não possa alterar as variáveis
	if(isset($_REQUEST['tipo']) && !empty($_REQUEST['tipo']) && isset($_REQUEST['valor']) && !empty($_REQUEST['valor']) && isset($_REQUEST['data']) && !empty($_REQUEST['data']) && isset($_REQUEST['id_user']) && !empty($_REQUEST['id_user'])){
		
		session_start();
		
		// Cria uma sessão.
		$_SESSION['tipo'] = $_REQUEST['tipo'];
		$_SESSION['valor'] = $_REQUEST['valor'];
		$_SESSION['data'] = $_REQUEST['data'];
		$_SESSION['id_user'] = $_REQUEST['id_user'];
		$_SESSION['contratoId'] = $_REQUEST['contratoId'];
		
		header('Location: /servico_form_dados_usuario.php');
	}
	
	session_start();
	
	// Retorna para a tela de serviço.
	if(!isset($_SESSION['tipo'])){
		header('Location: /servico-contador.php');
	}

	require_once('header_restrita.php');
	
	// Realiza a requisição da chamada do controle.
	require_once('Controller/form_dados_usuario-controller.php');
	
	// chama dados do cliente
	$dadosCobranca = new CadastroDadosCobrancaCliente();
	
	// Pega os dadoe do cliente.
	$dados = $dadosCobranca->PegaDadosCliente($_SESSION['id_userSecaoMultiplo']);
	
	// Pega os dados do token
	$dadosToken = $dadosCobranca->PegaDadosToken($_SESSION['id_userSecaoMultiplo']);
	
	$numeroCartao = '';
	$nomeTitular = '';
	$bandeira = 'sem';
	
	if($dadosToken){
		$numeroCartao = $dadosToken->getNumeroCartao();
		$nomeTitular = $dadosToken->getNomeTitular();
		$bandeira = $dadosToken->getBandeira();
	}
	
?>
<div class="principal" style="margin-top: -17px;">

	<div class="titulo">Serviços com Contador</div>
	
	<!-- Recebe os dados de pessoa fisica -->		
	<input type="hidden" id="pf_nome" value="" />
	<input type="hidden" id="pf_cpf" value="" />
	<input type="hidden" id="pf_endereco" value="" />
	<input type="hidden" id="pf_bairro" value="" />
	<input type="hidden" id="pf_uf" value="" />
	<input type="hidden" id="pf_cidade" value="" />
	<input type="hidden" id="pf_cep" value="" />
	
	<!-- Recebe os dados de pessoa juridica -->
	<input type="hidden" id="pj_razao_social" value="" />
	<input type="hidden" id="pj_cnpj" value="" />
	<input type="hidden" id="pj_endereco" value="" />
	<input type="hidden" id="pj_bairro" value="" />
	<input type="hidden" id="pj_uf" value="" />
	<input type="hidden" id="pj_cidade" value="" />
	<input type="hidden" id="pj_cep" value="" />
		
	<form id="form_dados_cobranca" name="form_dados_cobranca" method="post" action="servico_form_dados_usuario_cadastro.php">
	 
		<div class="tituloVermelho">Dados de cobrança</div>
	 
		<table class="formTabela" style="background:none" cellspacing="3" cellpadding="0" border="0">
			<tbody>

				<tr>
					<td align="right" valign="middle" class="formTabela">Tipo:</td>
					<td class="formTabela">
						<input campo="tipo" type="radio" name="rdbTipo" id="id_PJ" value="PJ" <?php echo ($dados->getTipo() == 'PJ' ? 'checked' : '') ?> /> 
						<label for="PJ">Pessoa Jurídica</label>&nbsp;
						<input campo="tipo" type="radio" name="rdbTipo" id="id_PF" value="PF" <?php echo ($dados->getTipo() == 'PF' ? 'checked' : '') ?> /> 
						<label for="PF">Pessoa Física</label>
					</td>
				</tr>        
				<tr>
					<td colspan="2" style="height:10px;"></td>
				</tr>
				<tr>
					<td class="formTabela" id="txtSacado" valign="middle" align="right">Razão Social:</td>
					<td class="formTabela"><input name="sacado" id="sacado" maxlength="200" style="width: 100%" value="<?php echo $dados->getNome(); ?>" type="text"></td>
				</tr>
				
				<tr>
					<td class="formTabela" id="txtDocumento" valign="middle" align="right">
						<?php 
							echo $dados->getTipo() == 'PF' ? 'CPF:' : '';
							echo $dados->getTipo() == 'PJ' ? 'CNPJ:' : '';
						?>
					</td>
					<td class="formTabela">
						<input class="campoCNPJ" campo="documento" type="text" name="cnpj" id="cnpj" maxlength="18" size="18" value="<?php echo ($dados->getTipo() == "PJ" ? $dados->getDocumento() : '') ?>" style="display: <?php echo ($dados->getTipo() == "PJ" ? 'block' : 'none') ?>;" alt="CNPJ">
						<input class="campoCPF" campo="documento" type="text" name="cpf" id="cpf" maxlength="14" size="14" value="<?= $dados->getTipo() == "PF" ? $dados->getDocumento() : '' ?>" style="display: <?php echo ($dados->getTipo() == "PF" ? 'block' : 'none') ?>;" alt="CPF">
					</td>
				</tr>
				
				<tr>
					<td class="formTabela" valign="middle" align="right">Endereço:</td>
					<td class="formTabela"><input name="endereco" id="endereco" maxlength="75" style="width: 100%" value="<?php echo $dados->getEndereco(); ?>" alt="Endereço" type="text"></td>
				</tr>
				
				<tr>
					<td class="formTabela" valign="middle" align="right">Bairro:</td>
					<td class="formTabela"><input name="bairro" id="bairro" maxlength="30" style="width: 100%" value="<?php echo $dados->getBairro(); ?>" alt="Bairro" type="text"></td>
				</tr>
				
				<tr>
					<td class="formTabela" valign="middle" align="right">UF:</td>
					<td class="formTabela">
						<select class=""  campo="uf" name="selEstado" id="selEstado" alt="UF">
							<option value=""></option>
							<?	
								$estados = $dadosCobranca->getEstados();
								$idEstadoSelecionado = '';
								foreach ($estados as $estado) {
									echo '<option value="'.$estado['id'].';'.strtoupper($estado['sigla']).'" '.$dadosCobranca->selected(strtoupper($estado['sigla']) , $dados->getUF()).'>'.strtoupper($estado['sigla']).'</option>';
									if (strtoupper($estado['sigla']) == strtoupper($dados->getUF()) ) {
										$idEstadoSelecionado = $estado['id'];
									}
								}	
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td class="formTabela" valign="middle" align="right">Cidade:</td>
					<td class="formTabela">
						<select class="comboM "  campo="cidade" name="txtCidade" id="txtCidade" style="width:300px" alt="Cidade">
							<option value=""></option>
							<?
								if ($idEstadoSelecionado != '') {
									$sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
									$result = mysql_query($sql) or die(mysql_error());
									while ($cidades = mysql_fetch_array($result)) {
										echo '<option value="'.$cidades['cidade'].'"'.$dadosCobranca->selected($cidades['cidade'], $dados->getCidade()).' > '.$cidades['cidade'].'</option>';
									}
								}
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td class="formTabela" valign="middle" align="right">CEP:</td>
					<td class="formTabela"><input name="cep" id="cep" maxlength="9" size="12" class="campoCEP" value="<?php echo $dados->getCEP(); ?>" alt="CEP" type="text"></td>
				</tr>

				<tr>
					<td colspan="2" class="formTabela" valign="middle" align="center">
						<br>
					</td>
				</tr>
			</tbody>
		</table>

		<div style="margin-bottom:20px">
			<span class="tituloVermelho">Forma de Pagamento</span><br /><br />
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento1" value="visa" <?php echo ($dados->getFormaPagameto() == "mastercard" ? 'checked="checked"' : '') ?>/>
				<img src="images/logo-visa.png" width="47" height="27" align="center" style="margin-right:15px" title="" alt="" />
			  </label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento2" value="mastercard" <?php echo ($dados->getFormaPagameto() == "mastercard" ? 'checked="checked"' : '') ?>/>
				<img src="images/logo-master.png" width="39" height="27" align="center" style="margin-right:15px" title="Pagar com Mastercard" alt="Mastercard" />
			</label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento4" value="elo" <?php echo ($dados->getFormaPagameto() == "elo" ? 'checked="checked"' : '') ?> />
				<img src="images/logo-elo.png" width="31" height="27" align="center" style="margin-right:15px" title="Pagar com Elo" alt="Elo" />
			</label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento5" value="amex" <?php echo ($dados->getFormaPagameto() == "amex" ? 'checked="checked"' : '') ?>/>
				<img src="images/logo-amex.png" width="25" height="26" align="center" style="margin-right:15px" title="Pagar com American Express" alt="American Express">
			</label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento6" value="diners" <?php echo ($dados->getFormaPagameto() == "diners" ? 'checked="checked"' : '') ?>/>
				<img src="images/logo-diners.png" width="36" height="27" align="center" style="margin-right:15px" title="Pagar com Diners" alt="Diners Club">
			</label>
			<label>
				<input class="comboM atualizarViaAjaxText" type="radio" name="radFormaPagamento" id="radFormaPagamento3" value="boleto" <?php echo ($dados->getFormaPagameto() == "boleto" ? 'checked="checked"' : '') ?>/>
				<img src="images/boletoicon.gif" width="39" height="20" align="center" style="margin-right:15px" />
			</label>	  
		</div>   
		<div id="CampoBotaoAlteracao" bandeira-atual="" style="display:none; margin-top:3px; margin-bottom:-3px;">					
			<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
				<tr id="campo_numero_cartao">
					<td align="right" valign="middle" class="formTabela">Número do Cartão:</td>
					<td class="formTabela inputCartao">
     		            <input id="gravarCartao" type="hidden" value="1" name="gravarCartao">
						<input name="txtNumeroCartao" type="text" id="txtNumeroCartao" style="width:125px; margin-bottom:0px" value="" maxlength="16"  alt="Número do Cartão" onClick="this.setSelectionRange(0, this.value.length)" />
					</td>
				</tr>
				<tr id="campo_nome_titular">
					<td align="right" valign="middle" class="formTabela">Nome do Titular:</td>  
					<td class="formTabela inputCartao">   
						<input name="txtNomeTitular" type="text" id="txtNomeTitular" style="width:170px; margin-bottom:0px" value="" maxlength="200"  alt="Nome do Titular" onClick="this.setSelectionRange(0, this.value.length)"  />
						<span style="clear: both; font-size:10px">Como consta no cartão</span>
					</td>
				</tr>
				<tr id="campo_codigo_seguranca">
					<td align="right" valign="middle" class="formTabela">Código de Segurança:</td>
					<td class="formTabela">
						<input name="txtCodigo" type="text" id="txtCodigo" style="width:35px; margin-bottom:0px" value="" maxlength="3"  alt="Código de Segurança"  />
					</td>
				</tr>
				<tr id="campo_data_validade">
					<td align="right" valign="middle" class="formTabela">Data de Validade:</td>
					<td class="formTabela">
						<input name="txtDataValidade" type="text" id="txtDataValidade" maxlength="7" style="width:60px; margin-bottom:0px" value="" maxlength="8"  alt="Data de Validade"  />
						<span style="font-size:10px"> MM/AAAA</span>
					</td>
				</tr>
				<tr id="campo_botao">
					<td colspan="2">
						<br/>
					</td>
				</tr>
			</table>
		</div>
		<div id="dadosCartao" style="display:none;">
			<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
				<tr id="campo_numero_cartao">
					<td align="right" valign="middle" class="formTabela">Número do Cartão:</td>
					<td class="formTabela text_cartao">
						<?php echo $numeroCartao;?>
					</td>
				</tr>
				<tr id="campo_nome_titular">
					<td align="right" valign="middle" class="formTabela">Nome do Titular:</td>
					<td class="formTabela text_cartao">
						<?php echo $nomeTitular;?>
					</td>
				</tr>
				<tr id="campo_botao">
					<td colspan="2">
						<br/>
					</td>
				</tr>
			</table>
		</div>
		<div class="divLoadCard" style="text-align:center; display: none; width: 100px;">
	    	<img src="images/loading.gif" width="16" height="16">
	    </div>  
		<div class="campoBtn">
			<br/>	
			<input type="button" id="botao_alterar" value="Alterar Dados do Cartão" style="display:none;" />
            <input type="button" id="botao_cancelar" value="Cancelar" style="display:none;" />
            <input type="button" id="btnSalvar" value="Prosseguir" name="btnSalvar" />
		</div>
	</form>
    
</div>

<script>
	
	$(function() {
		
		// Caso a forma de pagamento não seja cartão mostra os dados de cartão. 
		$("input[type='radio'][name='radFormaPagamento']").each(function(){
			if($(this).val() != 'boleto' && $(this).is(":checked")){
				$('#dadosCartao').show();
			}
		});
				
		// Desabilita os campo de alteração.
		$('#txtNumeroCartao').attr('disabled','disabled');
		$('#txtNomeTitular').attr('disabled','disabled');
		$('#txtCodigo').attr('disabled','disabled');
		$('#txtDataValidade').attr('disabled','disabled');
		$('#gravarCartao').attr('disabled','disabled');
		
		
		// Habilita a alteração do cartão. 
		$('#botao_alterar').click(function(){
			
			// Oculta os dados do cartão.
			$('#dadosCartao').hide();
			
			// Chama o método para habilitar a alteração. 
			HabilitaPagamento();
			$(this).hide();
			$('#botao_cancelar').show();
			
		});
		
		// Habilita a alteração do cartão. 
		$('#botao_cancelar').click(function(){
			// Chama o método para desabilitar a alteração. 
			DesabilitaPagamento();
			
			// Mostra os dados do cartão.
			$('#dadosCartao').show();
			$('#botao_cancelar').hide();
			$('#botao_alterar').show();
		});
		
		$("input[type='radio'][name='radFormaPagamento']").click(function(){
		
			if($(this).val() == 'boleto'){
				DesabilitaPagamento();
				$('#dadosCartao').hide();
				$('#botao_cancelar').hide();
				$('#botao_alterar').hide();
			} else {
				pagamentoCartao($(this).val());
			}
		});
		
		
		// Verifica qual a forma de pagamentoinformado.
		function pagamentoCartao(bandeira){
			
			DesabilitaPagamento();
			$('#dadosCartao').hide();
			
			if('<?php echo $bandeira; ?>' == bandeira) {
				$('#dadosCartao').show();
				$('#botao_alterar').show();
				
			} else {
				HabilitaPagamento();
				$('#botao_cancelar').hide();
				$('#botao_alterar').hide();
			}
		}
		
		// Método para abilitar o pagamento.
		function HabilitaPagamento(){
			
			$('#txtNumeroCartao').removeAttr('disabled');
			$('#txtNomeTitular').removeAttr('disabled');
			$('#txtCodigo').removeAttr('disabled');
			$('#txtDataValidade').removeAttr('disabled');
			$('#gravarCartao').removeAttr('disabled');
			
			$('#CampoBotaoAlteracao').show();
		}
		
		// Método para desabilitar o pagamento.
		function DesabilitaPagamento(){
			
			$('#txtNumeroCartao').attr('disabled','disabled');
			$('#txtNomeTitular').attr('disabled','disabled');
			$('#txtCodigo').attr('disabled','disabled');
			$('#txtDataValidade').attr('disabled','disabled');
			$('#gravarCartao').attr('disabled','disabled');
			
			$('#CampoBotaoAlteracao').hide();
		}
		
		// Verifica o tipo do Documento.
		$('input[name="rdbTipo"]').bind('change',function(){
			if($(this).val()=='PJ'){
				$('#txtSacado').html('Razão Social:');
				$('#txtDocumento').html('CNPJ:');
				$('#cnpj').show();
				$('#cpf').hide();
				
			}else{
				$('#txtSacado').html('Nome:');
				$('#txtDocumento').html('CPF:');
				$('#cnpj').hide();
				$('#cpf').show();
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
		
		$('#btnSalvar').click(function() {
			if(verificarPreenhimentoDadosCobranca()){
				$('.campoBtn').hide();
				$('.divLoadCard').show();
				$('#form_dados_cobranca').submit();
			}
		});
		
		var statusPesso = '<?php echo $dados->getTipo();?>';
		
		$('#id_PF').click(function(){
			
			if(statusPesso != 'PF'){
				statusPesso = 'PF';
				AcaoPF();
			}
		});

		$('#id_PJ').click(function(){
			
			if(statusPesso != 'PJ'){
				statusPesso = 'PJ';
				AcaoPJ();
			}
		});		
		
		function AcaoPF(){

			console.log('passou aqui');
			
			$('#pj_razao_social').val($('#sacado').val()); 
			$('#pj_cnpj').val($('#cnpj').val()); 	
			$('#pj_endereco').val($('#endereco').val()); 
			$('#pj_bairro').val($('#bairro').val()); 
			$('#pj_uf').val($('#selEstado').val()); 
			$('#pj_cidade').val($('#txtCidade').val()); 
			$('#pj_cep').val($('#cep').val());	

			$('#sacado').val($('#pf_nome').val());
			$('#cpf').val($('#pf_cpf').val());
			$('#endereco').val($('#pf_endereco').val());
			$('#bairro').val($('#pf_bairro').val());
			$('#selEstado').val($('#pf_uf').val());
			$('#txtCidade').val($('#pf_cidade').val());
			$('#cep').val($('#pf_cep').val());
		}

		function AcaoPJ() {
			
			console.log('passou aqui');

			$('#pf_nome').val($('#sacado').val()); 
			$('#pf_cpf').val($('#cpf').val()); 	
			$('#pf_endereco').val($('#endereco').val()); 
			$('#pf_bairro').val($('#bairro').val()); 
			$('#pf_uf').val($('#selEstado').val()); 
			$('#pf_cidade').val($('#txtCidade').val()); 
			$('#pf_cep').val($('#cep').val()); 

			$('#sacado').val($('#pj_razao_social').val());
			$('#cnpj').val($('#pj_cnpj').val());
			$('#endereco').val($('#pj_endereco').val());
			$('#bairro').val($('#pj_bairro').val());
			$('#selEstado').val($('#pj_uf').val());
			$('#txtCidade').val($('#pj_cidade').val());
			$('#cep').val($('#pj_cep').val());

		}		
		
		//Verifica o preenchimento dos dados de cobranca
		function verificarPreenhimentoDadosCobranca(){

			//Vefifica o Tipo da pessoa, se for PJ verifica o CNPJ
			if( $("#id_PJ").attr("checked") === true ){
				
				//Verifica o preenchimento da Razão Social
				if( $("#sacado").val() === '' ){
					alert("Em Dados de Cobrança, informe a razão social");//Informa o usuario que o acmpo nao foi preenchido
					$("#sacado").focus();//Poe o usuario no campo
					return false;
				}
				
				if( $("#cnpj").val() === '' ){
					alert("Em Dados de Cobrança, informe o CNPJ");
					$("#cnpj").focus();
					return false;
				}
			}//Se for PF, vrifica o CPF
			else if( $("#id_PF").attr("checked") === true ){
				
				//Verifica se foi preenchido o nome. 
				if( $("#sacado").val() === '' ){
					alert("Em Dados de Cobrança, informe o nome");//Informa o usuario que o acmpo nao foi preenchido
					$("#sacado").focus();//Poe o usuario no campo
					return false;
				}

				if( $("#cpf").val() === '' ){
					alert("Em Dados de Cobrança, informe o CPF");
					$("#cpf").focus();
					return false;
				}
			}//Se não escolheu algum deles, avisa o user
			else{
				alert("Em Dados de Cobrança, selecione o tipo de assinante.");
				$("#PJ").focus();
				return false;
			}
			
			//Verifica o preenchimento do endereço
			if( $("#endereco").val() === '' ){
				alert("Em Dados de Cobrança, informe o endereço");//Informa o usuario que o campo nao foi preenchido
				$("#endereco").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento do bairro
			if( $("#bairro").val() === '' ){
				alert("Em Dados de Cobrança, informe o bairro");//Informa o usuario que o campo nao foi preenchido
				$("#bairro").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento do Estado
			if( $("#selEstado").val() === '' ){
				alert("Em Dados de Cobrança, informe o estado");//Informa o usuario que o campo nao foi preenchido
				$("#selEstado").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento da cidade
			if( $("#txtCidade").val() === '' ){
				alert("Em Dados de Cobrança, informe a cidade");//Informa o usuario que o campo nao foi preenchido
				$("#txtCidade").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento do CEP
			if( $("#cep").val() === '' ){
				alert("Em Dados de Cobrança, informe o CEP");//Informa o usuario que o campo nao foi preenchido
				$("#cep").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica se o pagamento foi selecionado.
			if(!$("input[type='radio'][name='radFormaPagamento']").is(':checked') ){
				alert("Selecione a forma de pagamento");//Informa o usuario que o campo nao foi preenchido
				$("input[type='radio'][name='radFormaPagamento']").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica se o campo numero do cartão foi preenchido.
			if($('#txtNumeroCartao').val() === '' && !$('#txtNumeroCartao').attr('disabled')){
				alert("Em Forma de Pagamento, informe o Número do Cartão");//Informa o usuario que o campo nao foi preenchido
				$('#txtNumeroCartao').focus();//Poe o usuario no campo
				return false;
			}
			//Verifica se o campo nome do titular foi preenchido. 
			if($('#txtNomeTitular').val() === '' && !$('#txtNomeTitular').attr('disabled')){
				alert("Em Forma de Pagamento, informe o Nome do Titular");//Informa o usuario que o campo nao foi preenchido
				$('#txtNomeTitular').focus();//Poe o usuario no campo
				return false;
			}
			//Verifica se o campo código foi preenchido.
			if($('#txtCodigo').val() === '' && !$('#txtCodigo').attr('disabled')){
				alert("Em Forma de Pagamento, informe o Código de Segurança");//Informa o usuario que o campo nao foi preenchido
				$('#txtCodigo').focus();//Poe o usuario no campo
				return false;
			}
			//Verifica se o campo data validade foi preenchido.
			if($('#txtDataValidade').val() === '' && !$('#txtDataValidade').attr('disabled')){
				alert("Em Forma de Pagamento, informe a Data de Validade");//Informa o usuario que o campo nao foi preenchido
				$('#txtDataValidade').focus();//Poe o usuario no campo
				return false;
			}
			
			return true;
		}
		
		$('#txtDataValidade').keypress(function(){
			
			var valor = '';
			
			valor = $(this).val().replace(/\D/g,"");
			valor = valor.replace(/(\d{2})(\d)/,"$1/$2");
			valor = valor.replace(/(\d{2})(\d{2})$/,"$1$2");
			
			$(this).val(valor);
		});		
	});

</script>

<?php

	// Verifica se teve algum erro no cartao
	if(isset($_SESSION['erro_boleto_avulso_cartao'])) {
		echo '<script> alert("Os dados do cartão informados não são validos"); $("#radFormaPagamento3").focus();</script>';
		unset($_SESSION['erro_boleto_avulso_cartao']);
	}
?>

<?php include 'rodape.php' ?>