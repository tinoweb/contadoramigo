<?php 
	
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);
	
	include 'header_restrita.php';
	include 'minha-conta.class.php';
	require_once('Model/DadosContratoContadorCliente/DadosContratoContadorClienteData.php');
	
	$minha_conta = new Minha_conta();
	$minha_conta->setid_user($_SESSION['id_userSecaoMultiplo']);
	//Pega os dados do usuario e inicia todas as verificações iniciais
	$minha_conta->execute();

	// Variável que auxilia na verificação  se o usuário possui pedencia.
	$pendencia = 0;
	
	if($minha_conta->pegaHistoricoVencidaNaoPago()) {
		$pendencia = 1;	
	}

	// Mata a sessção que confirma que teve ativação da conta.
	unset($_SESSION['AtivacaoContaDesativada']);
?>
<style>
	.boxContrato {
		padding: 0px;
		width: 500px; 
		height: 470px; 
		position: absolute; 
		left: 50%; 
		margin-left: -223px; 
		top: 150px; 
		display: none; 
		z-index: 999; 
		background: rgb(245, 246, 247) none repeat scroll 0% 0%;
	}
</style>    
<div class="principal">
	<div class="titulo" style="margin-bottom:20px">Dados da Conta</div>
	
	<!--coluna da esquerda-->
	<div style="float:left; width:510px">
	
	<!--bonequinha-->
		<div style="float:left; margin-top:30px; margin-right: 10px">
			<img src="images/assistente.png" width="70" alt=""> 
		</div>
		
		<!--caixa-->
		<div style="float: left; width:430px">
		
		<!--caixa amarela-->
		<div class="bubble_left" style="width:430px; margin-bottom: 10px">
		    <div style="padding:20px; font-size:12px"> 	
		    <?php echo $minha_conta->getTextoBallon(); ?>
		    <?php echo $minha_conta->getMensagensNotificacao(); ?>		
		  	<div style="width: 100%; clear: both;margin-bottom:10px;">
		  		Em caso de dúvida, entre em contato conosco pelo <a href="suporte.php">help desk</a>.
		  	</div>
			<?php $minha_conta->getDadosPagamento(); ?>
			</div>				
		</div>
		<!--fim da caixa amarela-->	
	
		<!--termo e logo ssl-->	
			<a href="javascript:abreDiv('contrato')">Termos e Condições de Serviço</a><br><br>
		
			<span id="ss_img_wrapper_115-55_image_en"><a href="http://www.alphassl.com/ssl-certificates/wildcard-ssl.html" target="_blank" title="SSL Certificates"><img alt="Wildcard SSL Certificates" border=0 id="ss_img" src="//seal.alphassl.com/SiteSeal/images/alpha_noscript_115-55_en.gif" title="SSL Certificate"></a></span>
			<script type="text/javascript" src="//seal.alphassl.com/SiteSeal/alpha_image_115-55_en.js"></script>
		<!--fim do termo e logo ssl-->	
	
		</div>
		<!--fim da caixa-->
	
	</div>

<!--coluna da direita-->
	<div style="float:right; width:420px">
	
<!--Formulario com os dados de login e contado do assinante -->
		<form name="form_assinante" id="form_assinante" method="post" action="minha_conta_dados_cobranca_gravar_nova.php" style="display:inline">
			<div class="tituloVermelho" style="margin-bottom:20px">Assinante</div>
	  		<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
	   			<tr>
	      			<td align="right" valign="middle" class="formTabela">Assinante:</td>
	      			<td class="formTabela"><input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="assinante" name="txtAssinante" type="text" id="txtAssinante" style="width:300px; margin-bottom:0px" value="<?= $minha_conta->getassinante(); ?>" maxlength="200"  alt="Assinante"  /></td>
	    		</tr>
	    		<tr>
	      			<td align="right" valign="middle" class="formTabela">E-mail:</td>
	      			<td class="formTabela"><input  class="atualizarViaAjaxText" tabela="login" campo="email"  name="txtEmail" type="text" id="txtEmail" style="width:300px; margin-bottom:0px" value="<?= $minha_conta->getemail_usuario(); ?>" maxlength="200"  alt="E-mail"  />
	      				<input type="hidden" name="hddEmailUser" id="hddEmailUser" value="<?= $minha_conta->getemail_usuario(); ?>" />
	      				<div name="divPass" id="divPass" style="display:none"></div>
	      			</td>
	    		</tr>
	    		<tr>
	      			<td align="right" valign="top" class="formTabela">Senha: </td>
	      			<td class="formTabela">
	      				<span style="margin-right:5px"><?= $minha_conta->getsenha(); ?></span>
	      				<a class="alterarSenha" status="fechado" href="JavaScript:abreDiv('divSenha');">Alterar senha</a>
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
		              				<td class="formTabela">
		              					<input name="passConfirmaSenha" type="password" id="passConfirmaSenha" style="width:90px; margin-bottom:0px" maxlength="10"/>
		              				</td>
		            			</tr>
		          			</table>
	        			</div>
	      			</td>
	    		</tr>
    			<tr>
      				<td align="right" valign="middle" class="formTabela">Telefone:</td>
      				<td valign="middle" class="formTabela"><div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
        				<div style="float:left">
          					<input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="pref_telefone"  name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:30px" value="<?= $minha_conta->getpref_telefone(); ?>" maxlength="2" alt="Prefixo do Telefone" class="campoDDDTESTE" />
        				</div>
        				<div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
        				<div style="float:left">
          					<input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="telefone" name="txtTelefone" type="text" id="txtTelefone" style="width:75px" value="<?= $minha_conta->gettelefone(); ?>" maxlength="9" alt="Telefone" />
        				</div>
        			</td>
    			</tr>
    			<tr>
      				<td colspan="2" valign="middle" class="formTabela"><input type="hidden" name="hidID" id="hidID" value="<?= $minha_conta->getid_user(); ?>" /></td>
    			</tr>    
  			</table>
  			<div style="margin-top:10px;margin-bottom:20px">
				<input name="btnSalvar" type="button" id="btnSalvar"  value="Salvar" style="display:none" />
			</div>
		</form>

       
       <!--Formulario com o plano do assinante -->
       
        <div style="margin-top:20px;margin-bottom:5px" class="tituloVermelho">Plano de Assinatura</div>
        
       	<form id="atualizaplanoForm" action="minha_conta_atualiza_tipo_plano.php" method="post">
            <div class="campoTipoPlano" style="border-bottom: 1px solid #ddd; display: block; margin-bottom: 12px; padding: 8px 2px; width: 396px;">
                <?php $minha_conta->inputComTipoPlano(); ?>
            </div>
		<!--form name="form_assinante" id="form_assinante_plano" method="post" action="minha_conta_plano_gravar_nova.php" style="display:inline"-->
			<?php $minha_conta->getPlanospagamento(); ?>
			<p style="margin: 0;margin-top: 5px;">Valor cobrado por empresa.</p>
			<div style="margin-top:10px;margin-bottom:20px">
				<input type="hidden" name="hidID" value="<?= $minha_conta->getid_user(); ?>" />
				<input name="btnSalvaPlanor" type="button" id="btnSalvarPlano"  value="Salvar" style="display:none" />
                <button id="bt_alteraplano" type="button">Alterar Plano</button>
			</div>
		</form>
		
		
		<!--Formulario com os dados de cobranca-->
		
		<form name="form_forma_pagamento" id="form_forma_pagamento_cobranca" method="post" action="minha_conta_forma_pagamento_nova.php" style="display:inline">
			<input type="hidden" name="hddStatus" id="hddStatus" value="<?= $minha_conta->getstatus_login(); ?>">
			<div style="margin-bottom:20px" class="tituloVermelho">Dados de cobrança</div>
			<div id="divPagamentoBoleto" style="margin-bottom:10px; ">
		  		<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
		    		<tr>
		      			<td valign="middle" class="formTabela">
			        		<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela">Tipo:</td>
			            			<td class="formTabela">
			            				<input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="tipo" type="radio" name="rdbTipo" id="boleto_PJ" value="PJ" <?= $minha_conta->getdados_cobranca_tipo() == 'PJ' ? 'checked' : '' ?>> 
			            				<label for="boleto_PJ">Pessoa Jurídica</label>&nbsp;
			              				<input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="tipo" type="radio" name="rdbTipo" id="boleto_PF" value="PF" <?= $minha_conta->getdados_cobranca_tipo() == 'PF' ? 'checked' : '' ?>> 
			              				<label for="boleto_PF">Pessoa Física</label>
			              			</td>
			          			</tr>
			          		</table>
			          		<table>
			          			<tr>
			            			<td colspan="2" style="height:10px;"></td>
			          			</tr>
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela" id="txtSacado"><?= $minha_conta->getdados_cobranca_tipo() == 'PJ' ? 'Razão Social' : 'Nome' ?>:</td>
			            			<td class="formTabela">
			            				<input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="sacado" type="text" name="boleto_sacado" id="boleto_sacado" maxlength="200" style="width: 100%" value="<?= $minha_conta->getdados_cobranca_sacado() ?>" alt="<?= strlen($minha_conta->getdados_cobranca_documento()) > 14 ? 'Razão Social' : 'Nome' ?>">
			            			</td>
			          			</tr>
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela" id="txtDocumento">
										<?php 
											echo $minha_conta->getdados_cobranca_tipo() == 'PF' ? 'CPF:' : '';
											echo $minha_conta->getdados_cobranca_tipo() == 'PJ' ? 'CNPJ:' : '';
										?>
                                    </td>
			            			<td class="formTabela">
			            				<input class="campoCNPJ atualizarViaAjaxText" tabela="dados_cobranca" campo="documento" type="text" name="boleto_cnpj" id="boleto_cnpj" maxlength="18" size="18" value="<?= $minha_conta->getdados_cobranca_tipo() == "PJ" ? $minha_conta->getdados_cobranca_documento() : '' ?>" style="display: <?= $minha_conta->getdados_cobranca_tipo() == "PJ" ? 'block' : 'none' ?>;" alt="CNPJ">
			            				<input class="campoCPF atualizarViaAjaxText" tabela="dados_cobranca" campo="documento" type="text" name="boleto_cpf" id="boleto_cpf" maxlength="14" size="14" value="<?= $minha_conta->getdados_cobranca_tipo() == "PF" ? $minha_conta->getdados_cobranca_documento() : '' ?>" style="display: <?= $minha_conta->getdados_cobranca_tipo() == "PF" ? 'block' : 'none' ?>;" alt="CPF">
						            </td>
			        			</tr>
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela">Endereço:</td>
			            			<td class="formTabela">
			            				<input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="endereco" type="text" name="boleto_endereco" id="boleto_endereco" maxlength="75" style="width: 100%" value="<?= $minha_conta->getdados_cobranca_endereco() ?>" alt="Endereço">
			            			</td>
			          			</tr>
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela">Bairro:</td>
			            			<td class="formTabela">
			            				<input class="atualizarViaAjaxText" tabela="dados_cobranca" campo="bairro" type="text" name="boleto_bairro" id="boleto_bairro" maxlength="30" style="width: 100%" value="<?= $minha_conta->getdados_cobranca_bairro() ?>" alt="Bairro">
			            			</td>
			          			</tr>
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela">UF:</td>
		            				<td class="formTabela">
		              					<select class="atualizarViaAjaxText" tabela="dados_cobranca" campo="uf" name="selEstado" id="selEstado" alt="UF">
		                  					<option value="" <?php echo $minha_conta->selected('', $minha_conta->getdados_cobranca_uf()); ?>></option>
									      	<?	
												$estados = $minha_conta->getarrEstados();
												$idEstadoSelecionado = '';
												foreach ($estados as $estado) {
													echo '<option value="'.$estado['id'].';'.strtoupper($estado['sigla']).'" '.$minha_conta->selected(strtoupper($estado['sigla']) , $minha_conta->getdados_cobranca_uf()).'>'.strtoupper($estado['sigla']).'</option>';
													if (strtoupper($estado['sigla']) == strtoupper($minha_conta->getdados_cobranca_uf()) ) {
													    $idEstadoSelecionado = $estado['id'];
													}
												}	
											?>
		              					</select>
			            			</td>
			          			</tr>
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela">Cidade:</td>
			            			<td class="formTabela">
			              				<select class="comboM atualizarViaAjaxText" tabela="dados_cobranca" campo="cidade" name="txtCidade" id="txtCidade" style="width:300px" alt="Cidade">
			                				<option value="" <?php echo $minha_conta->selected('', $cidade);	?>></option>
								            <?
												if ($idEstadoSelecionado != '') {
												    $sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
												    $result = mysql_query($sql) or die(mysql_error());
												    while ($cidades = mysql_fetch_array($result)) {
												        echo '<option value="'.$cidades['cidade'].'"'.$minha_conta->selected($cidades['cidade'], $minha_conta->getdados_cobranca_cidade()).' > '.$cidades['cidade'].'</option>';
												    }
												}
											?>
			              				</select>
			            			</td>
			          			</tr>
			          			<tr>
			            			<td align="right" valign="middle" class="formTabela">CEP:</td>
			            			<td class="formTabela">
			            				<input class="campoCEP atualizarViaAjaxText" tabela="dados_cobranca" campo="cep" type="text" name="boleto_cep" id="boleto_cep" maxlength="9" size="12"  value="<?= $minha_conta->getdados_cobranca_cep() ?>" alt="CEP">
			            			</td>
			          			</tr>
			        		</table>
		      			</td>
		    		</tr>
		  		</table>
			</div>
			<div style="margin-bottom:20px;display:none"><input type="hidden" name="hidID" id="hidID" value="<?= $minha_conta->getid_user() ?>" /><input name="btnSalvar5" type="button" onclick="formFormaPagamentoSubmit()" value="Salvar" /></div>
		</form>
		
		<!--Formulario com os dados deo cartao-->
		
		<form name="form_forma_pagamento" id="form_forma_pagamento2" method="post" action="minha_conta_forma_pagamento2_nova.php" style="display:inline">
			<div style="margin-bottom:20px"><span class="tituloVermelho">Forma de Pagamento</span><br /><br />
			    <label>
			    	<input type="radio" tabela="dados_cobranca" campo="forma_pagameto" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento1" value="visa" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php	echo $minha_conta->checked('visa', $minha_conta->getforma_pagameto());?> />
			      	<img src="images/logo-visa.png" width="47" height="27" align="center" style="margin-right:15px" title="" alt="" />
			      </label>
			    <label>
			    	<input type="radio" tabela="dados_cobranca" campo="forma_pagameto" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento2" value="mastercard" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo $minha_conta->checked('mastercard', $minha_conta->getforma_pagameto());?> />
			    	<img src="images/logo-master.png" width="39" height="27" align="center" style="margin-right:15px" title="Pagar com Mastercard" alt="Mastercard" />
			    </label>
			    <label>
			    	<input type="radio" tabela="dados_cobranca" campo="forma_pagameto" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento4" value="elo" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo $minha_conta->checked('elo', $minha_conta->getforma_pagameto());?> />
					<img src="images/logo-elo.png" width="31" height="27" align="center" style="margin-right:15px" title="Pagar com Elo" alt="Elo" />
				</label>
			    <label>
			    	<input type="radio" tabela="dados_cobranca" campo="forma_pagameto" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento5" value="amex" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo $minha_conta->checked('amex', $minha_conta->getforma_pagameto());?> />
					<img src="images/logo-amex.png" width="25" height="26" align="center" style="margin-right:15px" title="Pagar com American Express" alt="American Express">
				</label>
				<label>
			    	<input type="radio" tabela="dados_cobranca" campo="forma_pagameto" name="radFormaPagamento" class="escolhaCartao" id="radFormaPagamento6" value="diners" onclick="Javascript:abreDiv2('divPagamentoCartao')" <?php echo $minha_conta->checked('diners', $minha_conta->getforma_pagameto());?> />
					<img src="images/logo-diners.png" width="36" height="27" align="center" style="margin-right:15px" title="Pagar com Diners" alt="Diners Club">
				</label>
			    <label>
			    	<input class="comboM atualizarViaAjaxText" tabela="dados_cobranca" campo="forma_pagameto" type="radio" name="radFormaPagamento" id="radFormaPagamento3" value="boleto" onclick="Javascript:fechaDiv('divPagamentoCartao')" <?php	echo $minha_conta->checked('boleto', $minha_conta->getforma_pagameto());?> />
		    	    <img src="images/boletoicon.gif" width="39" height="20" align="center" style="margin-right:15px" />
		    	</label>	      
			</div>     
			<div id="divPagamentoCartao" bandeira-atual="<?php echo $minha_conta->getdados_cartao_bandeira(); ?>" style="margin-top:3px; margin-bottom:-3px; <?php if (($minha_conta->getforma_pagameto() == "boleto") or ($minha_conta->getforma_pagameto() == "")) { echo 'display:none'; } ?>">					
				<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
			    	<tr id="campo_numero_cartao">
			      		<td align="right" valign="middle" class="formTabela">Número do Cartão:</td>
			      		<td class="formTabela text_cartao">
                       		<?= $minha_conta->getdados_cartao_numero_cartao(); ?>
                        </td>
                        <td class="formTabela inputCartao">
                            <input name="txtNumeroCartao" type="text" id="txtNumeroCartao" style="width:125px; margin-bottom:0px" value="<?= $minha_conta->getdados_cartao_numero_cartao(); ?>" maxlength="16"  alt="Número do Cartão" onClick="this.setSelectionRange(0, this.value.length)" />
						</td>
			      	</tr>
			    	<tr id="campo_nome_titular">
			      		<td align="right" valign="middle" class="formTabela">Nome do Titular:</td>
			      		<td class="formTabela text_cartao">
                        	<?= $minha_conta->getdados_cartao_nome_titular(); ?>
                        </td>    
                       	<td class="formTabela inputCartao">     
                            <input name="txtNomeTitular" type="text" id="txtNomeTitular" style="width:170px; margin-bottom:0px" value="<?= $minha_conta->getdados_cartao_nome_titular(); ?>" maxlength="200"  alt="Nome do Titular" onClick="this.setSelectionRange(0, this.value.length)"  />
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
                        	<input name="txtDataValidade" type="text" id="txtDataValidade" style="width:60px; margin-bottom:0px" value="" maxlength="8"  alt="Data de Validade"  />
			      			<span style="font-size:10px"> MM/AAAA</span>
			      		</td>
			    	</tr>
			    	<tr>
			      		<td colspan="2" valign="middle" class="formTabela">&nbsp;</td>
			    	</tr>
			  	</table>
			</div>
	    	<div class="divLoadCard" style="text-align:center; display: none;">
	    		<img src="images/loading.gif" width="16" height="16">
	    	</div>    
		    <div id="campo_botao_salvar"><input type="hidden" name="hidID" id="hidID" value="<?= $minha_conta->getid_user() ?>" />
		    	<input name="btnSalvar2" type="button" id="btnSalvar2"  value="Salvar" onclick="formFormaPagamentoSubmit2()" style="display:none;" />
		    	<input type="button" id="campo_botao_cancelar_botao"  value="Cancelar" style="display:none;margin-left:20px;"/>
		    </div>
		    <div id="campo_botao_alterar_dados" style="display:none"><input type="button" id="campo_botao_alterar_dados_botao"  value="Alterar Dados do Cartão"/></div>
		</form>   
		<!--fim do formulario com os dados do cartão-->
	</div>
	<!--fim da coluna da direita-->
	
<div style="clear:both"> </div>

</div>
<!--fim do div principal-->

<!--quadro branco de alerta-->
<div class="quadro_branco" id="campoConfirmacaoStandard" style="padding: 0px; width: 380px; height: 127px; position: absolute; left: 50%; margin-left: -223px; margin-top: -120px; top: 50%; display: none; z-index: 999; background: rgb(245, 246, 247) none repeat scroll 0% 0%;">
    <div style='padding: 15px 15px;'>
    	Deseja realmente voltar para o plano Standard? <br/> Nele você não contará com o auxilio de um contador para o cumprimento de suas obrigações fiscais.
        <br/>
        <br/>
    	<button id="btConfirma" >Confirma</button>
        <button id="btCancelarAcao" >Cancelar</button>
    </div>    
</div>
<!--fim do quadro branco de alerta -->

<script>
	
	var aux_ajax_temp = '';
	
	$( document ).ready(function() {

		var temp_cartao;
		var temp_nome_titular;
		var forma_pagameto = '<?php echo $minha_conta->getforma_pagameto()?>';

		<?php if( $minha_conta->getforma_pagameto() != "boleto" && $minha_conta->getforma_pagameto() != "" ){ ?>
			$("#campo_codigo_seguranca").fadeOut(0);
			$("#campo_data_validade").fadeOut(0);
			$("#campo_botao_salvar").fadeOut(0);
			$("#campo_botao_alterar_dados").fadeIn(0);
			$('.text_cartao').show();
			$('.inputCartao').hide();
		<?php } else {?>
			if($('#txtNumeroCartao').val().length > 0 && $('#txtNomeTitular').val().length > 0) {
				$('.text_cartao').show();
				$('.inputCartao').hide();
			} else {
				$('.text_cartao').hide();
				$('.inputCartao').show();
			}
		<?php } ?>


		$("#campo_botao_alterar_dados_botao").click(function() {
			
			$("#campo_codigo_seguranca").fadeIn(0);
			$("#campo_data_validade").fadeIn(0);
			$(this).fadeOut(0);
			$("#btnSalvar2").css('display', 'initial');
			$("#campo_botao_salvar").fadeIn(0);
			$("#campo_botao_cancelar_botao").fadeIn(0);

			temp_cartao = $("#txtNumeroCartao").val();
			$("#txtNumeroCartao").val("");
			temp_nome_titular = $("#txtNomeTitular").val();
			$("#txtNomeTitular").val("");
			$('.text_cartao').hide();
			$('.inputCartao').show();
			
		});


		$("#campo_botao_cancelar_botao").click(function() {

			$("#txtNumeroCartao").val(temp_cartao);
			$("#txtNomeTitular").val(temp_nome_titular);

			$("#campo_codigo_seguranca").fadeOut(0);
			$("#campo_data_validade").fadeOut(0);
			$("#campo_botao_alterar_dados_botao").fadeIn(0);
			$("#campo_botao_salvar").fadeOut(0);
			$("#campo_botao_cancelar_botao").fadeOut(0);
			$('.text_cartao').show();
			$('.inputCartao').hide();
			
			<?php if( $minha_conta->getforma_pagameto() == "visa" ){ ?>
				$('#radFormaPagamento1').attr("checked", "checked");
			<?php } ?>

			<?php if( $minha_conta->getforma_pagameto() == "mastercard" ){ ?>
				$('#radFormaPagamento2').attr("checked", "checked");
			<?php } ?>

			<?php if( $minha_conta->getforma_pagameto() == "elo" ){ ?>
				$('#radFormaPagamento4').attr("checked", "checked");
			<?php } ?>

			<?php if( $minha_conta->getforma_pagameto() == "amex" ){ ?>
				$('#radFormaPagamento5').attr("checked", "checked");
			<?php } ?>

			<?php if( $minha_conta->getforma_pagameto() == "Diners" ){ ?>
				$('#radFormaPagamento6').attr("checked", "checked");
			<?php } ?>
			
			<?php if( $minha_conta->getforma_pagameto() == "boleto" ){ ?>
				$('#radFormaPagamento3').attr("checked", "checked");
				$('#divPagamentoCartao').hide();
			<?php } ?>
		});

		$("#radFormaPagamento3").click(function() {

			if(temp_cartao != "")
				$("#txtNumeroCartao").val(temp_cartao);
			if(temp_nome_titular != "")
				$("#txtNomeTitular").val(temp_nome_titular);

			$("#campo_codigo_seguranca").fadeOut(0);
			$("#campo_data_validade").fadeOut(0);
			$("#campo_botao_alterar_dados_botao").fadeIn(0);
			$("#campo_botao_salvar").fadeIn(0);
			$("#campo_botao_cancelar_botao").fadeOut(0);
			$("#campo_botao_alterar_dados_botao").fadeOut(0);

			$('#campo_numero_cartao').hide();
			$('#campo_nome_titular').hide();
			
			$("#divPagamentoCartao").fadeIn(0);
			$("#divPagamentoCartao").css("opacity","0.4");

			$("#txtNumeroCartao").attr("disabled","disabled");
			$("#txtNomeTitular").attr("disabled","disabled");

		});

		
		var planoId = '';
		var assinaturaId = '';
		var planoAtual = '';
		var forma_pagameto = '<?php echo $minha_conta->getforma_pagameto()?>';
		
		// Pega o plano atual.
		$('.inputPlano').each(function(){
			if($(this).is(":checked")){
				planoId = $(this).attr('id');
				planoAtual = $(this).val();
			}
		});
		
		// Pega o assinatura atual. 
		$('.atualizarValorAvencer').each(function(){
			if($(this).is(":checked")){
				assinaturaId = $(this).attr('id');
			}
		});
		
		$('#tipo_plano_P').click(function(){
			ShowPremiun();
		});
		
		$('#tipo_plano_S').click(function(){
			ShowStandard();
		});
		
		// Método para altera o plano.
		function ShowPremiun() {
			$('.classP').show();
			$('.classS').hide();
		}
		
		// Método para altera o plano.
		function ShowStandard() {
			$('.classP').hide();
			$('.classS').show();
		}
		
		// Acão do botão alterar plano.
		$('#bt_alteraplano').click(function(){
			
			var tipo_plano = '';
			
			// pega o plano.
			$('.inputPlano').each(function(){
				if($(this).is(":checked")){
					tipo_plano = $(this).val();
				}
			});
						
			var forma_pagameto = "<?php echo $minha_conta->getforma_pagameto()?>";
			
			// verifica se o pagamento e com cartão e e anual, caso seja boqueia.
			if(forma_pagameto != 'boleto' && $('#radPlano_anual').is(":checked")) {
				atualizarItem($("#radPlano_mensalidade"));
				alert("O pagamento por cartão de crédito não pode ser usado para o plano anual. Altere a forma de pagamento para boleto.");
				location = "minha_conta.php";
				return;
			}
					
			// Verifica se o plano foi alterado 
			if(planoAtual != tipo_plano) {	
				// Verifica se não existe mensalidade em atrazo antes de realiza a acão.
				if(<?php echo $pendencia;?> == 0) {
			
					// Verifica o plano selecionado
					if(tipo_plano == 'P') {
		
						var assinatura = '';
						var valorExtenxo = '';
						
						// valida os campo de cobranca ante de mostra o contrato para o cliente.
						if($('#txtCidade').val().trim().length > 0 && $('#selEstado').val().trim().length > 0 && $('#boleto_endereco').val().trim().length > 0 && $('#boleto_cnpj').val().trim().length > 0 || $('#boleto_cpf').val().trim().length > 0 && $('#boleto_sacado').val().trim().length > 0 && $('#boleto_bairro').val().trim().length > 0) {	
							$('input:radio[name="radPlano"]').each(function(){
								if($(this).attr("checked")){
									assinatura = $(this).val();
								}
							});
					
							$.ajax({
								type: 'text'
								, url: 'ajaxContrato.php'
								, data: {valorExtenxo:valorExtenxo}
								, async: true
								, cache: false,
								beforeSend: function() {
									// setting a timeout
									//$(placeholder).addClass('loading');
								},
								success: function(data) {
									$('.boxContrato').html(data);
								},
								error: function(xhr) {
									alert("Erro na apresentação do contrato.");
								},
								complete: function() {
					
									if( assinatura  == "mensalidade" ) {
										$('.ValorPagoContratado').html($('#extenso_mensalidade').val() + ' mensais');
									}
									
									if( assinatura == "trimestral" ) {
										$('.ValorPagoContratado').html($('#extenso_trimestral').val() + ' trimestral');
									}
									
									if( assinatura == "semestral" ) {
										$('.ValorPagoContratado').html($('#extenso_semestral').val() + ' semestral');
									}
									
									if( assinatura == "anual" ) {
										$('.ValorPagoContratado').html($('#extenso_anual').val() + ' anual');
									}
								
									abreDiv('contrato_premio');
								},
							});
				
						} else {
							
							$('#'+planoId).attr('checked','checked');
							
							// volta os valores para posiçao inicial.
							if(planoAtual == "P") {
								ShowPremiun();
							} else {
								ShowStandard();
							}
							
							alert('Por favor, verifique se seus dados de cobrança foram preenchidos corretamente.');
						}
						
					} else {
						
						// abre a janela de confirmaçao do plano Standard.
						$("#campoConfirmacaoStandard").show();
					}
				} else {
					
					$('#'+planoId).attr('checked','checked');
					
					// volta os valores para posiçao inicial.
					if(planoAtual == "P") {
						ShowPremiun();
					} else {
						ShowStandard();
					}
	
					alert('Você tem parcelas pendentes. Para alterar o plano, envie um pedido ao nosso help desk.');
					//alert('Não é possível alterar o plano quando existem mensalidades pendentes');
				}
			} else {
				
				// realiza apenas a alteracao da assinatura.
				$('#atualizaplanoForm').submit();
	
			}
				
		});
		
		// Botão da janela de confirmaçao do plano Standard - confirmar
		$('#btConfirma').click(function(){
			$('#atualizaplanoForm').submit();
		});
		
		// Botão da janela de confirmaçao do plano Standard - cancelar
		$('#btCancelarAcao').click(function(){
		
			// volta os valores para posiçao inicial.
			$('#'+planoId).attr('checked','checked');
			if(planoAtual == "P") {
				ShowPremiun();
			} else {
				ShowStandard();
			}
			
			$("#campoConfirmacaoStandard").hide();
			
		});

		function cartaoJacadastrado(){
			$("#campo_codigo_seguranca").css("display","none");
			$("#campo_data_validade").css("display","none");
			$('.text_cartao').show();
			$('.inputCartao').hide();
			$("#campo_botao_salvar").fadeOut(0);
			$("#campo_botao_cancelar_botao").fadeOut(0);
		}
		$("#radFormaPagamento1").click(function() {
			<?php if( $minha_conta->getdados_cartao_bandeira() == "visa" ){ ?>
				cartaoJacadastrado();
				atualizarItem($(this));
				mostraDadosCartao();
			<?php } else { ?>
				mostraCadastro();
			<?php } ?>
		});
		$("#radFormaPagamento2").click(function() {
			<?php if( $minha_conta->getdados_cartao_bandeira() == "mastercard" ){ ?>
				cartaoJacadastrado();
				atualizarItem($(this));
				mostraDadosCartao();
			<?php } else { ?>
				mostraCadastro();
			<?php } ?>
		});
		$("#radFormaPagamento3").click(function() {
			$("#btnSalvar2").css("display","none");
		});
		$("#radFormaPagamento4").click(function() {
			<?php if( $minha_conta->getdados_cartao_bandeira() == "elo" ){ ?>
				cartaoJacadastrado();
				atualizarItem($(this));
				mostraDadosCartao();
			<?php } else { ?>
				mostraCadastro();
			<?php } ?>
		});
		$("#radFormaPagamento5").click(function() {
			<?php if( $minha_conta->getdados_cartao_bandeira() == "amex" ){ ?>
				cartaoJacadastrado();
				atualizarItem($(this));
				mostraDadosCartao();
			<?php } else { ?>
				mostraCadastro();
			<?php } ?>
		});
		$("#radFormaPagamento6").click(function() {
			<?php if( $minha_conta->getdados_cartao_bandeira() == "Diners" ){ ?>
				cartaoJacadastrado();
				atualizarItem($(this));
				mostraDadosCartao();
			<?php } else { ?>
				mostraCadastro();
			<?php } ?>
		});

		$(".alterarSenha").click(function(event) {
			if( $(this).attr("status") === "fechado" ){
				$("#btnSalvar").css("display","initial");
				$(this).attr("status","aberto");
			}
			else if( $(this).attr("status") === "aberto" ){
				$("#btnSalvar").css("display","none");
				$(this).attr("status","fechado");
			}

		});
		$(".atualizarViaAjaxText").focus(function() {
			aux_ajax_temp = $(this).val();
		});
		$(".atualizarViaAjaxText").change(function() {
			if( $(this).val() === '' ){
				alert("O campo não pode ficar vazio");
				$(this).val(aux_ajax_temp);
				$(this).focus();
				return false;
			}

			atualizarItem($(this));
		});
		
		function mostraDadosCartao(){
			
				$("#txtNumeroCartao").val(temp_cartao);
				$("#txtNomeTitular").val(temp_nome_titular);
	
				$("#campo_codigo_seguranca").fadeOut(0);
				$("#campo_data_validade").fadeOut(0);
				
				$('#campo_numero_cartao').show();
				$('#campo_nome_titular').show();
				$('#campo_botao_alterar_dados').fadeIn(0);
				$("#campo_botao_alterar_dados_botao").fadeIn(0);
				$("#campo_botao_salvar").fadeOut(0);
				$("#campo_botao_cancelar_botao").fadeOut(0);
	
				$("#divPagamentoCartao").css("opacity","1");
	
				$("#txtNumeroCartao").attr("disabled","");
				$("#txtNomeTitular").attr("disabled","");
		}
		
		function mostraCadastro() {
			
			$("#campo_codigo_seguranca").fadeIn(0);
			$("#campo_data_validade").fadeIn(0);
			$("#campo_botao_alterar_dados_botao").fadeOut(0);
			$("#btnSalvar2").css('display', 'initial');
			$("#campo_botao_salvar").fadeIn(0);
			$("#campo_botao_cancelar_botao").fadeIn(0);

			temp_cartao = $("#txtNumeroCartao").val();
			$("#txtNumeroCartao").val("");
			temp_nome_titular = $("#txtNomeTitular").val();
			$("#txtNomeTitular").val("");
			$('.text_cartao').hide();
			$('.inputCartao').show();
			
		}		
		
		function atualizarItem(objeto){
			tabela = $(objeto).attr("tabela");
			campo = $(objeto).attr("campo");
			valor = $(objeto).val();

			parametros = "&tabela="+tabela+"&campo="+campo+"&valor="+valor;

			if( campo === "email" ){
				if( !validarEmailCadastrado(valor) ){
					location = "minha_conta.php";
					return false;
				}
			}

			<?php if( $minha_conta->getforma_pagameto() != "boleto" && $minha_conta->getforma_pagameto() != "" ){ ?>
				if( valor === "anual" && campo === 'plano' ){
					$("#radPlano_semestral").click();
					alert("O pagamento por cartão de crédito não pode ser usado para o plano anual. Altere a forma de pagamento para boleto.");
					location = "minha_conta.php";
					return;
				}
			<?php } ?>

			$.ajax({
				url:'ajax.php'
				, data: 'atualizarMinhaConta=atualizarMinhaConta'+parametros
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					console.log(retorno);
				    if( valor === 'boleto' || valor === '<?php echo $minha_conta->getdados_cartao_bandeira() ?>' ){
				    	if( valor === '<?php echo $minha_conta->getdados_cartao_bandeira() ?>' && $('#radPlano_anual').is(":checked")) {
							alert("O pagamento por cartão de crédito não pode ser usado para o plano anual.");
				    		atualizarItem($("#radPlano_mensalidade"));
						}
				    	location = "minha_conta.php";
				    }
				}
			});
		}
		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}
		function validarEmailCadastrado(valor){	
			if(isEmail(valor) === false){
				alert("Informe um email válido");
				$('#txtEmail').focus();
				$('#txtEmail').select();
                return false;
            }
			$.ajax({
				url:'assinatura_checa_email.php',
				data: 'email=' + $('#txtEmail').val(), 
				type: 'get',
				async: false,
				cache:false,			
				success: function(retorno){
				  if(retorno > 0){
					$('#txtEmail').focus();
					$('#txtEmail').select();
					alert('O E-mail já está cadastrado em nosso sistema.');
					return false;
				  }
				}
			});
			window.location.href = "minha_conta.php";
			return true;
		}
		//Verifica se o plano de assinatura foi escolhido
		function verificarPreenchimentoPlano(){
			if( $("#radPlano_mensalidade").attr("checked") === false && $("#radPlano_trimestral").attr("checked") === false && $("#radPlano_semestral").attr("checked") === false && $("#radPlano_anual").attr("checked") === false ){
				alert('Selecione uma assinatura e clique no botão "Alterar Plano" para continuar');
				$("#radPlano_mensalidade").focus();
				return false;
			} else if($('input:radio[name=radPlano]:checked').val() != $('#planoNoBanco').val()){
				alert('Percebemos que você tentou alterar o plano de assinatura. Clique no botão "Alterar Plano" para continuar.');
				return false;
			} else if($('input:radio[name=tipo_plano]:checked').val() != $('#planoAtual').val()) {
				alert('Percebemos que você tentou alterar o plano de assinatura. Clique no botão "Alterar Plano" para continuar.');
				return false;
			}
			return true;
		}
		//Verifica o preenchimento dos dados de cobranca
		function verificarPreenhimentoDadosCobranca(){

			//Vefifica o Tipo da pessoa, se for PJ verifica o CNPJ
			if( $("#boleto_PJ").attr("checked") === true ){
				
				//Verifica o preenchimento da Razão Social
				if( $("#boleto_sacado").val() === '' ){
					alert("Em Dados de Cobrança, informe a razão social");//Informa o usuario que o acmpo nao foi preenchido
					$("#boleto_sacado").focus();//Poe o usuario no campo
					return false;
				}
				
				if( $("#boleto_cnpj").val() === '' ){
					alert("Em Dados de Cobrança, informe o CNPJ");
					$("#boleto_cnpj").focus();
					return false;
				}
			}//Se for PF, vrifica o CPF
			else if( $("#boleto_PF").attr("checked") === true ){
				
				//Verifica se foi preenchido o nome. 
				if( $("#boleto_sacado").val() === '' ){
					alert("Em Dados de Cobrança, informe o nome");//Informa o usuario que o acmpo nao foi preenchido
					$("#boleto_sacado").focus();//Poe o usuario no campo
					return false;
				}

				if( $("#boleto_cpf").val() === '' ){
					alert("Em Dados de Cobrança, informe o CPF");
					$("#boleto_cpf").focus();
					return false;
				}
			}//Se não escolheu algum deles, avisa o user
			else{
				alert("Em Dados de Cobrança, selecione o tipo de assinante.");
				$("#boleto_PJ").focus();
				return false;
			}
			
			//Verifica o preenchimento do endereço
			if( $("#boleto_endereco").val() === '' ){
				alert("Em Dados de Cobrança, informe o endereço");//Informa o usuario que o acmpo nao foi preenchido
				$("#boleto_endereco").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento do bairro
			if( $("#boleto_bairro").val() === '' ){
				alert("Em Dados de Cobrança, informe o bairro");//Informa o usuario que o acmpo nao foi preenchido
				$("#boleto_bairro").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento do Estado
			if( $("#selEstado").val() === '' ){
				alert("Em Dados de Cobrança, informe o estado");//Informa o usuario que o acmpo nao foi preenchido
				$("#selEstado").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento da cidade
			if( $("#txtCidade").val() === '' ){
				alert("Em Dados de Cobrança, informe a cidade");//Informa o usuario que o acmpo nao foi preenchido
				$("#txtCidade").focus();//Poe o usuario no campo
				return false;
			}
			//Verifica o preenchimento do CEP
			if( $("#boleto_cep").val() === '' ){
				alert("Em Dados de Cobrança, informe o CEP");//Informa o usuario que o acmpo nao foi preenchido
				$("#boleto_cep").focus();//Poe o usuario no campo
				return false;
			}
			return true;
		}
		//Verifica se a forma de cobrança foi infirmada corretamento juntamento com os dados d cartão se necessário
		function verificarPreenhimentoFormaCobranca(){
			//Verifica se alguma forma de cobrança foi selecionada
			if( $("#radFormaPagamento1").attr("checked") === false && $("#radFormaPagamento2").attr("checked") === false && $("#radFormaPagamento3").attr("checked") === false && $("#radFormaPagamento4").attr("checked") === false && $("#radFormaPagamento5").attr("checked") === false && $("#radFormaPagamento6").attr("checked") === false  ){
				alert("Informe a Forma de Pagamento (Cartão ou Boleto)");
				$("#radFormaPagamento1").focus();
				return false;
			}
			//Verifica se a forma é por cartão
			if( $("#radFormaPagamento3").attr("checked") === false ){
				//Se for por cartão, verifica se os campos ja estão pre-preenchidos
				if( $("#txtNumeroCartao").val() != '' && $("#txtNomeTitular").val() != '' && $("#txtCodigo").val() == '' && $("#txtDataValidade").val() == '' ){
					return true;
				}
				else{
					$("#txtNumeroCartao").focus();
					alert("Preenha os dados do Cartão e clique em salvar");
					return false;
				}
			}
			return true;
		}
		//Verifica se o usuario preenheu os dados de cobranca
		function verificarPreencimentoCampos(){
			if( !verificarPreenchimentoPlano() )
				return false;
			if( !verificarPreenhimentoDadosCobranca() )
				return false;
			if( !verificarPreenhimentoFormaCobranca() )
				return false;
			// alert("Ok");
			return true;
		}
	     //Acao de gerar o boleto
		$(".pagarCartaoAVencer").click(function() {
			var tipo = $(this).parent().parent().attr("tipo");
			pagarCartaoAVencer($(this));//Chama a função que redireciona para a url de pagamento
		});	
		//Funcao que realiza o pagamento de uma mensalidade com o cartao
		function pagarCartaoAVencer(objeto){
			if( verificarPreencimentoCampos() ){
				//Coloca o loader no lugar do botao, para evitar que atraso na requisicao provoque erros por parte do usuario
				$(objeto).parent().empty().append('<div class="divCarregando2" style="margin-top:10px; text-align:center"><img src="images/loading.gif" width="16" height="16"></div>');
				//Redireciona para a pagina de pagamento
				location.href='minha_conta_quitar_cartao_nova.php?avencer'; // quando for aprovado alterar o link para o arquivo sem estar em alteração
			}
		}
		 //Acao de gerar o boleto
		$(".pagarCartaoAtrasados").click(function() {
			var tipo = $(this).parent().parent().attr("tipo");
			pagarCartaoAtrasado($(this));//Chama a função que redireciona para a url de pagamento
		});	
		//Funcao que realiza o pagamento de uma mensalidade com o cartao
		function pagarCartaoAtrasado(objeto){
			//Coloca o loader no lugar do botao, para evitar que atraso na requisicao provoque erros por parte do usuario
			if( verificarPreencimentoCampos() ){
				$(objeto).parent().empty().append('<div class="divCarregando2" style="margin-top:10px; text-align:center"><img src="images/loading.gif" width="16" height="16"></div>');
				//Redireciona para a pagina de pagamento
				location.href='minha_conta_quitar_cartao_nova.php?atrasados';
			}
		}
	    //Acao de gerar o boleto
		$(".gerarBoleto").click(function() {
			//Chama a funcao que gera o boleto
			gerarBoleto($(this));
		});	
		//Função que abre a janela com o boleto
	    function gerarBoleto(objeto){
	    	//Pega o id do historico para geração do boleto
	    	var id_historico = $(objeto).parent().parent().attr("id");
	    	//Define o tipo de boleto: Mensalidade para boleto gerados atraves do historico ou avulso, gerado pelo admin para um determinado user
	    	var tipo = "mensalidade";
	    	//Define o id do usuario
	    	var id_user = "<?php echo $minha_conta->getid_user(); ?>";
	    	// Pega o tipo do Plano
			var tipo_plano = $('input:radio[name=tipo_plano]:checked').val();
			//Concatena os parametros
			var plano =  $('input:radio[name=radPlano]:checked').val();
			// Define o prazo do boleto.
			var prazo = 1;
			

	    	// console.log($(objeto).parent().parent());

	    	var parametros = 'id_historico='+id_historico+'&tipo='+tipo+'&id_user='+id_user+'&tipo_plano='+tipo_plano+'&plano='+plano+'&prazo='+prazo;
	    	
	    	if( $(objeto).parent().parent().attr("gerar-segunda-via") === 'true' )
	    		parametros = parametros+'&segunda_via';

	    	parametros = parametros+'&via='+$(objeto).parent().parent().attr("via");
	    	//Define o link da pagina de geração do boleto
	    	var link = 'gerar-boleto.php?';
			
	    	if( $(objeto).parent().parent().attr("gerar-segunda-via") === 'false' && $(objeto).parent().parent().attr("boleto-vencido") === 'true' ){
		    	location = 'boleto_vencido.php?'+parametros;	
		    }
		    else{
		    	if( verificarPreencimentoCampos() ){
		    		//Verifica se existem boletos pendentes para o usuario, se existir, exibe um confirma avisando que outros boletos anteriores ainda nao foram pagos
			    	if( verificarBoletosPendentes(id_historico,id_user) === false )
			    		location = link+parametros;//Abre a janela com o boleto gerado
			    }
		    }
	    }
	    function verificarBoletosPendentes(id_historico,id_user){
	    	var result = false;
	    	$.ajax({
	    		url:'ajax.php'
	    		, data: 'verificacaoBoletos=verificacaoBoletos&id_historico='+id_historico+'&id_user='+id_user
	    		, type: 'post'
	    		, async: false
	    		, cache:false
	    		, success: function(retorno){
	    			console.log(retorno);
	    			if( retorno === 'erro' ){
						if( confirm("Há boletos pendentes de meses anteriores. Deseja prosseguir?") === false )
							result = true;
					}	    		    
	    		}
	    	});
	    	return result;
	    }
	     //Acao de gerar o boleto para usuario cancelado
		$(".ativarAssinatura").click(function() {
			//Chama a funcao que gera o boleto
			gerarBoletoAtivarusuario($(this));
		});	
		//Função que abre a janela com o boleto
	    function gerarBoletoAtivarusuario(objeto){
	    	//Pega o id do historico para geração do boleto
	    	var id_historico = $(objeto).parent().parent().attr("id");
	    	//Define o tipo de boleto: Mensalidade para boleto gerados atraves do historico ou avulso, gerado pelo admin para um determinado user
	    	var tipo = "mensalidade";
	    	//Define o id do usuario
	    	var id_user = "<?php echo $minha_conta->getid_user(); ?>";
	    	//Concatena os parametros
	    	var parametros = "via=0&id_historico="+id_historico+"&tipo="+tipo+"&id_user="+id_user;
	    	//Define o link da pagina de geração do boleto
	    	var link = "gerar-boleto.php?";
	    	//Abre a janela com o boleto gerado
	    	if( verificarPreencimentoCampos() ){
				// console.log(link+parametros);
				$(objeto).parent().empty().append('<div class="divCarregando2" style="margin-top:10px; text-align:center"><img src="images/loading.gif" width="16" height="16"></div>');
				
		    	$.ajax({
		    		url:'minha_conta_altera_status_pagamento.php'
		    		, type: 'post'
		    		, async: true
		    		, cache:false
		    		, success: function(){
		    			//abreJanela(link+parametros,'_blank','width=700, height=600, top=150, left=150, scrollbars=yes, resizable=yes');
		    			location = link+parametros;//,'_blank','width=700, height=600, top=150, left=150, scrollbars=yes, resizable=yes');
		    			// location = 'minha_conta.php';
		    		}
		    	});
		    }
		    // location = link+parametros;//,'_blank','width=700, height=600, top=150, left=150, scrollbars=yes, resizable=yes');
	    }
	});

</script>
<script>
	$(function(){
		$('.pagamentoBloqueado').click(function(e){
			e.preventDefault();
			alert('Não é possível realizar este pagamento, pois há mensalidades anteriores em aberto.');
		});
	});
</script>
<script>
$(document).ready(function(e) {
				
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
});
</script>
<script type="text/javascript">
	$(document).ready(function(e) {

		$('input[name="rdbTipo"]').bind('change',function(){
			if($(this).val()=='PJ'){
				$('#txtSacado').html('Razão Social:');
				$('#txtDocumento').html('CNPJ:');
				$('#boleto_cnpj').show();
				$('#boleto_cpf').hide();
				
			}else{
				$('#txtSacado').html('Nome:');
				$('#txtDocumento').html('CPF:');
				$('#boleto_cnpj').hide();
				$('#boleto_cpf').show();
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
		

		// document.getElementById('form_forma_pagamento_cobranca').submit()
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
		
		$('#campo_botao_salvar').hide();
		$('.divLoadCard').show();
		
		document.getElementById('form_forma_pagamento2').submit()
	}
</script>
<style type="text/css" media="screen">
	.bubble_left:after{
		top: 140px;
	}
	.bubble_left:before{
		top: 140px;	
	}
</style>


<?php 
	
	if( isset($_SESSION['erro_certificado']) && $_SESSION['erro_certificado'] == 'true' ){
		$_SESSION['erro_certificado'] = '';
		echo '<script>
		
			
			$( document ).ready(function() {
			    
				alert("Para ter direito à promoção, é preciso primeiro confirmar sua assinatura no Contador Amigo. Vá em Meus Dados/dados da Conta e escolha seu plano de assinatura.");
			    
			});
		
		</script>';
	}
	
	// Verifica se o usuário tem pedencias e informa a mensagem que não foi possivel realiza a alteracão do plano.  
	if(isset($_SESSION['pendenciasdepagamento'])) {
		
		// apaga a sessão.
		unset($_SESSION['pendenciasdepagamento']);
		echo "<script> alert('Você tem parcelas pendentes. Para alterar o plano, envie um pedido ao nosso help desk.'); </script>";
		//echo "<script> alert('Não é possível alterar o plano quando existem mensalidades pendentes'); </script>";	
	}
	
	// Verifica se o usuário tem pedencias e informa a mensagem que não foi possivel realiza a alteracão do plano.  
	if(isset($_SESSION['erro_cartao'])) {
		// apaga a sessão.
		unset($_SESSION['erro_cartao']);
		echo "<script> alert('Os dados do cartão informados não são validos'); </script>";	
	}
?>

<!-- DIV QUE IR RECEBER O CONTRATO DO PRÊMIO -->
<div class="quadro_branco boxContrato" id="contrato_premio"></div>

	<?php include 'ballon_contrato.php'; ?>

<?php include 'rodape.php';	?>
