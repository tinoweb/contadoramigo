<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);	

	//	Pega os dados do get e joga na sessão e depois redireciona chamando a página limpa para que o usuáiro não possa alterar as variáveis
	if(isset($_REQUEST['tipo']) && !empty($_REQUEST['tipo']) && isset($_REQUEST['valor']) && !empty($_REQUEST['valor']) && isset($_REQUEST['contratoId']) && !empty($_REQUEST['contratoId'])){
		
		session_start();
		
		// Cria uma sessão.
		$_SESSION['tipo'] = $_REQUEST['tipo'];
		$_SESSION['valor'] = $_REQUEST['valor'];
		$_SESSION['contratoId'] = $_REQUEST['contratoId'];
		
		header('Location: /servico_informar_dados_contratar.php');
	}
	
	session_start();
	
	// Retorna para a tela de serviço.
	if(!isset($_SESSION['tipo'])){
		header('Location: /servico-contador.php');
	}	
	if (isset($_SESSION["id_userSecao"])) {		
		require_once('header_restrita.php');
	} else {
		require_once('header.php' );	
	}
	

	// Realiza a requisição da chamada do controle.
	require_once('Controller/form_dados_usuario-controller.php');
	
	// chama dados do cliente
	$dadosCobranca = new CadastroDadosCobrancaCliente();
	
	// Pega os dadoe do cliente.
	$dados = $dadosCobranca->PegaDadosCliente($_SESSION['idUserNew']);

	$getTipo = 'PJ';
	$getNome = '';
	$getDocumento = '';
	$getEndereco = '';
	$getBairro = '';
	$getUF = '';
	$getCidade = '';
	$getCEP = '';
	$getAssinante = '';
	$getEmail = '';
	$getPrefixo = '';
	$getTelefone = '';
	
	if(isset($_SESSION['IRPF_Juridica'])){
		$getNome = $_SESSION['nome'];
		$getTipo = 'PF';
		$getDocumento = $_SESSION['cpf_resp'];
		$getPrefixo = $_SESSION['ddd'];
		$getTelefone = $_SESSION['tel_contato'];
		$getEmail = $_SESSION['e-mail'];
		$irpf = $_SESSION['IRPF_Juridica'];		
	}
	// Verifica se a dados.
	elseif($dados) {
		$getTipo = $dados->getTipo();
		$getNome = $dados->getNome();
		$getDocumento = $dados->getDocumento();
		$getEndereco = $dados->getEndereco();
		$getBairro = $dados->getBairro();
		$getUF = $dados->getUF();
		$getCidade = $dados->getCidade();
		$getCEP = $dados->getCEP();
		$getAssinante = $dados->getAssinante();
		$getEmail = $dados->getEmail();
		$getPrefixo = $dados->getPrefTelefone();
		$getTelefone = $dados->getTelefone();
	}


	// Pega os dados do token
	$numeroCartao = '';
	$nomeTitular = '';
	$bandeira = 'sem';
?>
<div class="principal minHeight">
	
	<div class="titulo">Serviços com Contador</div>
	
	<!-- Recebe os dados de pessoa fisica -->		
	<input type="hidden" id="pf_nome" value="" />
	<input type="hidden" id="pf_cpf" value="" />
	<input type="hidden" id="pf_email" value="" />
	<input type="hidden" id="pf_ddd" value="" />
	<input type="hidden" id="pf_telefone" value="" />
	<input type="hidden" id="pf_endereco" value="" />
	<input type="hidden" id="pf_bairro" value="" />
	<input type="hidden" id="pf_uf" value="" />
	<input type="hidden" id="pf_cidade" value="" />
	<input type="hidden" id="pf_cep" value="" />
	
	<!-- Recebe os dados de pessoa juridica -->
	<input type="hidden" id="pj_razao_social" value="" />
	<input type="hidden" id="pj_cnpj" value="" />
	<input type="hidden" id="pj_responsavel" value="" />
	<input type="hidden" id="pj_email" value="" />
	<input type="hidden" id="pj_ddd" value="" />
	<input type="hidden" id="pj_telefone" value="" />	
	<input type="hidden" id="pj_endereco" value="" />
	<input type="hidden" id="pj_bairro" value="" />
	<input type="hidden" id="pj_uf" value="" />
	<input type="hidden" id="pj_cidade" value="" />
	<input type="hidden" id="pj_cep" value="" />
	
<form id="form_dados_cobranca" name="form_dados_cobranca" method="post" action="servico_form_dados_usuario_cadastro_sem_login.php">
	 
		<div class="tituloVermelho">Dados de cobrança</div>

		<table class="formTabela" style="background:none" cellspacing="3" cellpadding="0" border="0">
			<tbody>
				<?php if(isset($irpf)): ?>
				<input type="hidden" value="IRPF_PF" id="id_irpf">				
				
				<?php else: ?>
				<tr>
					<td align="right" valign="middle" class="formTabela">Tipo:</td>
					<td class="formTabela">
						<input campo="tipo" type="radio" name="rdbTipo" id="id_PJ" value="PJ" <?php echo ($getTipo == 'PJ' ? 'checked' : '') ?> /> 
						<label for="PJ">Pessoa Jurídica</label>&nbsp;
						<input campo="tipo" type="radio" name="rdbTipo" id="id_PF" value="PF" <?php echo ($getTipo == 'PF' ? 'checked' : '') ?> /> 
						<label for="PF">Pessoa Física</label>
					</td>
				</tr> 
				<?php endif; ?>
				<tr>
					<td colspan="2" style="height:10px;"></td>
				</tr>
				<tr>
					<td class="formTabela" id="txtSacado" valign="middle" align="right">Razão Social:</td>
					<td class="formTabela"><input name="sacado" id="sacado" maxlength="200" style="width: 100%" value="<?php echo $getNome; ?>" type="text"></td>
				</tr>
				
				<tr>
					<td class="formTabela" id="txtDocumento" valign="middle" align="right">
						<?php 
							echo $getTipo == 'PF' ? 'CPF:' : '';
							echo $getTipo == 'PJ' ? 'CNPJ:' : '';
						?>
					</td>
					<td class="formTabela">						
						<input class="campoCNPJ" campo="documento" type="text" name="cnpj" id="cnpj" maxlength="18" size="18" value="<?php echo ($getTipo == "PJ" ? $getDocumento : '') ?>" style="display: <?php echo ($getTipo == "PJ" && !isset($_SESSION['IRPF_Juridica']) ? 'block' : 'none') ?>;" alt="CNPJ">	
						<input class="campoCPF" campo="documento" type="text" name="cpf" id="cpf" maxlength="14" size="14" value="<?= $getTipo == "PF" ? $getDocumento : '' ?>" style="display: <?php echo ($getTipo == "PF" ? 'block' : 'none') ?>;" alt="CPF">
						
					</td>
				</tr>
				<tr id="campoRepresentante">
					<td class="formTabela" align="right" valign="middle">Representante:</td>
					<td class="formTabela">
						<input name="assinante" id="txtAssinante" style="width:300px; margin-bottom:0px" value="<?php echo $getAssinante;?>" maxlength="200" alt="Assinante" type="text">
					</td>
				</tr>
	    		<tr>
	      			<td class="formTabela" align="right" valign="middle">E-mail:</td>
	      			<td class="formTabela">
	      				<input name="email" id="inputEmail" style="width:300px; margin-bottom:0px" value="<?php echo $getEmail;?>" maxlength="200" alt="E-mail" type="text">
	      			</td>
	    		</tr>
    			<tr>
      				<td class="formTabela" align="right" valign="middle">Telefone:</td>
      				<td class="formTabela" valign="middle">
       					<div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
        				<div style="float:left">
          					<input name="ddd" id="ddd" style="width:30px" value="<?php echo $getPrefixo;?>" maxlength="2" alt="Prefixo do Telefone" type="text">
        				</div>
        				<div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
        				<div style="float:left">
          					<input name="telefone" id="txtTelefone" style="width:75px" value="<?php echo $getTelefone;?>" maxlength="9" alt="Telefone" type="text">
        				</div>
        			</td>
    			</tr> 
				
				<tr>
					<td class="formTabela" valign="middle" align="right">Endereço:</td>
					<td class="formTabela"><input name="endereco" id="endereco" maxlength="75" style="width: 100%" value="<?php echo $getEndereco; ?>" alt="Endereço" type="text"></td>
				</tr>
				
				<tr>
					<td class="formTabela" valign="middle" align="right">Bairro:</td>
					<td class="formTabela"><input name="bairro" id="bairro" maxlength="30" style="width: 100%" value="<?php echo $getBairro; ?>" alt="Bairro" type="text"></td>
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
									echo '<option value="'.$estado['id'].';'.strtoupper($estado['sigla']).'" '.$dadosCobranca->selected(strtoupper($estado['sigla']) , $getUF).'>'.strtoupper($estado['sigla']).'</option>';
									if (strtoupper($estado['sigla']) == strtoupper($getUF) ) {
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
										echo '<option value="'.$cidades['cidade'].'"'.$dadosCobranca->selected($cidades['cidade'], $getCidade).' > '.$cidades['cidade'].'</option>';
									}
								}
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td class="formTabela" valign="middle" align="right">CEP:</td>
					<td class="formTabela"><input name="cep" id="cep" maxlength="9" size="12" class="campoCEP" value="<?php echo $getCEP; ?>" alt="CEP" type="text"></td>
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
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento1" value="visa" />
				<img src="images/logo-visa.png" width="47" height="27" align="center" style="margin-right:15px" title="" alt="" />
			  </label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento2" value="mastercard"/>
				<img src="images/logo-master.png" width="39" height="27" align="center" style="margin-right:15px" title="Pagar com Mastercard" alt="Mastercard" />
			</label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento4" value="elo" onclick="Javascript:pagamentoCartao('elo')"/>
				<img src="images/logo-elo.png" width="31" height="27" align="center" style="margin-right:15px" title="Pagar com Elo" alt="Elo" />
			</label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento5" value="amex" />
				<img src="images/logo-amex.png" width="25" height="26" align="center" style="margin-right:15px" title="Pagar com American Express" alt="American Express">
			</label>
			<label>
				<input type="radio" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento6" value="diners" />
				<img src="images/logo-diners.png" width="36" height="27" align="center" style="margin-right:15px" title="Pagar com Diners" alt="Diners Club">
			</label>
			<label>
				<input class="comboM atualizarViaAjaxText" type="radio" name="radFormaPagamento" id="radFormaPagamento3" value="boleto" />
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
            <img id="loading" src="images/loading.gif" width="16" height="16" style="display: none;">
		</div>
	</form>
    
</div>

<script>
	
	$(function() {
		
		var statusPesso = '<?php echo $getTipo;?>';
		
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

			$('#pj_razao_social').val($('#sacado').val()); 
			$('#pj_cnpj').val($('#cnpj').val()); 	
			$('#pj_endereco').val($('#endereco').val()); 
			$('#pj_bairro').val($('#bairro').val()); 
			$('#pj_uf').val($('#selEstado').val()); 
			$('#pj_cidade').val($('#txtCidade').val()); 
			$('#pj_cep').val($('#cep').val());
			$('#pj_email').val($('#inputEmail').val()); 
			$('#pj_ddd').val($('#ddd').val());
			$('#pj_telefone').val($('#txtTelefone').val());
			$('#pj_responsavel').val($('#txtAssinante').val());
			

			$('#sacado').val($('#pf_nome').val());
			$('#cpf').val($('#pf_cpf').val());
			$('#endereco').val($('#pf_endereco').val());
			$('#bairro').val($('#pf_bairro').val());
			$('#selEstado').val($('#pf_uf').val());
			$('#txtCidade').val($('#pf_cidade').val());
			$('#cep').val($('#pf_cep').val());
			$('#inputEmail').val($('#pf_email').val());
			$('#ddd').val($('#pf_ddd').val());
			$('#txtTelefone').val($('#pf_telefone').val());
		}

		function AcaoPJ() {

			$('#pf_nome').val($('#sacado').val()); 
			$('#pf_cpf').val($('#cpf').val()); 	
			$('#pf_endereco').val($('#endereco').val()); 
			$('#pf_bairro').val($('#bairro').val()); 
			$('#pf_uf').val($('#selEstado').val()); 
			$('#pf_cidade').val($('#txtCidade').val()); 
			$('#pf_cep').val($('#cep').val()); 
			$('#pf_email').val($('#inputEmail').val()); 
			$('#pf_ddd').val($('#ddd').val());
			$('#pf_telefone').val($('#txtTelefone').val());
			
			$('#sacado').val($('#pj_razao_social').val());
			$('#cnpj').val($('#pj_cnpj').val());
			$('#endereco').val($('#pj_endereco').val());
			$('#bairro').val($('#pj_bairro').val());
			$('#selEstado').val($('#pj_uf').val());
			$('#txtCidade').val($('#pj_cidade').val());
			$('#cep').val($('#pj_cep').val());
			$('#inputEmail').val($('#pj_email').val());
			$('#ddd').val($('#pj_ddd').val());
			$('#txtTelefone').val($('#pj_telefone').val());
			$('#txtAssinante').val($('#pj_responsavel').val());
			

		}		

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
				$('#campoRepresentante').show();
				
			}else{
				$('#txtSacado').html('Nome:');
				$('#txtDocumento').html('CPF:');
				$('#cnpj').hide();
				$('#cpf').show();
				$('#campoRepresentante').hide();
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
				consultaEmailExistentes();				
			}
		});
		
		//Verifica o preenchimento dos dados de cobranca
		function verificarPreenhimentoDadosCobranca(){
			
			// Usadas para validar o email
			var emailFilter=/^.+@.+\..{2,}$/;
			var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/;
			
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
				
				//Verifica o preenchimento do Responsavel
				if( $("#txtAssinante").val() === '' ){
					alert("Em Dados de Cobrança, informe o Representante"); //Informa o usuario que o campo nao foi preenchido
					$("#txtAssinante").focus();//Poe o usuario no campo
					return false;
				}				
				
			}//Se for PF, vrifica o CPF
			else if( $("#id_PF").attr("checked") === true || $("#id_irpf").val() == "IRPF_PF"){
				
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
		
			//Verifica o preenchimento do Email
			if( $("#inputEmail").val() === '' ){
				alert("Em Dados de Cobrança, informe o Email"); //Informa o usuario que o campo nao foi preenchido
				$("#inputEmail").focus();//Poe o usuario no campo
				return false;
			} else if(!(emailFilter.test($("#inputEmail").val()))||$("#inputEmail").val().match(illegalChars)){
				alert("Por favor, informe um email válido."); //Informa o usuario que o email não esta correto.
				return false;
			}
			
			//Verifica o preenchimento do Prefixo do telefone
			if( $("#ddd").val() === '' ){
				alert("Em Dados de Cobrança, informe o DDD"); //Informa o usuario que o campo nao foi preenchido
				$("#ddd").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento do telefone
			if( $("#txtTelefone").val() === '' ){
				alert("Em Dados de Cobrança, informe o Telefone"); //Informa o usuario que o campo nao foi preenchido
				$("#txtTelefone").focus();//Poe o usuario no campo
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
	
	function consultaEmailExistentes(){
						
		//fechaDiv('entrar');
		
		var emailtxt = $('#inputEmail').val(); 
	
		// Realiza subimit. 
		var realizaSubit = false;
		
		$.ajax({
			url:'servico_checa_dados_ajax.php',
			data: 'email=' + emailtxt,
			type: 'post',
			cache: false,
			async: false,
			beforeSend: function() {
				$('#btnSalvar').hide();
				$('#loading').show();
			},
			success: function(retorno){				
	
				if(retorno > 0) {
					alert("Você já possui uma conta ativa no Contador Amigo com o E-mail informado. Faça o login normalmente, informando o email e senha cadastrados.");
					//abreDiv('entrar');
				} else {
					realizaSubit = true;
				}
			},
			error: function(xhr) {
				alert("Erro ao verificar email contate o help desk");
				return false;
			},
			complete: function() {
				
				if(realizaSubit){
					
					$('.campoBtn').hide();
					$('.divLoadCard').show();
					
					$('#form_dados_cobranca').submit();	
				}
				
				$('#loading').hide();
				$('#btnSalvar').show();
			}
			
		});
	}
</script>

<?php

	// Verifica se teve algum erro no cartao
	if(isset($_SESSION['erro_boleto_avulso_cartao'])) {
		echo '<script> alert("Os dados do cartão informados não são validos"); $("#radFormaPagamento3").focus();</script>';
		unset($_SESSION['erro_boleto_avulso_cartao']);
	}
?>

<?php include 'rodape.php'; ?>