<?php 

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);


include '../conect.php';
include '../session.php';
include 'check_login.php' ?>

<?php

$arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}


$id = $_GET["id"];
$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $id . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};
function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};


if(isset($_GET['limpar_historico']) && $_GET['limpar_historico'] > 0){
	
	mysql_query("DELETE FROM log_acessos WHERE id_user = " . $_GET['limpar_historico']);
	header('location: cliente_administrar.php?id=' . $id);
	
}

?>
<?php include 'header.php' ?>
<?

$sql = "SELECT * FROM login, dados_cobranca WHERE dados_cobranca.id = login.id AND login.id='" . $id . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linhalogin=mysql_fetch_array($resultado);

// Variável utilizada para receber o nome do contador.
$nomeContador = false; 

// Verifica se existe um contador para este cliente se for premium.
if(isset($linhalogin['contadorId'])) {
	
$sql2 = "SELECT * FROM dados_contador_balanco WHERE id ='".$linhalogin['contadorId']."'";
	$resultado2 = mysql_query($sql2)
	or die (mysql_error());

	$dadosContador=mysql_fetch_array($resultado2);	
	
	$nomeContador = $dadosContador['nome'];
}

?>
<script type="text/javascript">
	function add() {
		var orig = document.getElementById('content');
		var count = parseInt(document.getElementById('count').value);
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "item"+count);
		var newContent = "<div style=\"float:left; margin-right:5px\"><input name=\"txtCodigoCNAE"+count+"\" id=\"txtCodigoCNAE"+count+"\" type=\"text\" style=\"width:64px; margin-top:3px;\" maxlength=\"9\" onblur=\"consultaBanco('../meus_dados_empresa_consulta_cnae.php?codigo='+document.getElementById('txtCodigoCNAE"+count+"').value+'&campo="+count+"', 'atividade"+count+"');\" /></div> <div id=\"atividade"+count+"\" style=\"float:left; width:199px\"><input type=\"hidden\" id=\"pesquisaCampo"+count+"\" name=\"pesquisaCampo"+count+"\" value=\"ok\" /></div> <div style=\"clear:both\"> </div>";
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		document.getElementById('count').value = count+1;
	}	
	function remove() {
		var count = parseInt(document.getElementById('count').value);
		if (count > 1) {				
			var orig = document.getElementById('content');
			var removeDiv = document.getElementById('item'+(count-1));
			orig.removeChild(removeDiv);			
			document.getElementById('count').value = count - 1;
		}
	}
	
	function addPrefeitura() {
		var orig = document.getElementById('contentPrefeitura');
		var count = parseInt(document.getElementById('countPrefeitura').value);
		var newDiv = document.createElement('div');
		newDiv.setAttribute("id", "itemPrefeitura"+count);
		var newContent = '<input name="txtCodigoAtividadePrefeitura'+count+'" id="txtCodigoAtividadePrefeitura'+count+'" type="text" style="width:50px; float:left; margin-right:3px; margin-top:3px" value="" maxlength="5" onblur="consultaBanco(\'../meus_dados_empresa_consulta_codigo_prefeitura.php?codigo=\'+document.getElementById(\'txtCodigoAtividadePrefeitura'+count+'\').value+\'&campo='+count+'\', \'denomicacaoPrefeitura'+count+'\');" /><div id="denomicacaoPrefeitura'+count+'" style="float:left; width:210px"><input type="hidden" id="pesquisaCampoPrefeitura'+count+'" name="pesquisaCampoPrefeitura'+count+'" value="ok" /></div><div style="clear:both"> </div>';
		newDiv.innerHTML = newContent;
		orig.appendChild(newDiv);
		document.getElementById('countPrefeitura').value = count+1;
	}	
	function removePrefeitura() {
		var count = parseInt(document.getElementById('countPrefeitura').value);
		if (count > 2) {				
			var orig = document.getElementById('contentPrefeitura');
			var removeDiv = document.getElementById('itemPrefeitura'+(count-1));
			orig.removeChild(removeDiv);			
			document.getElementById('countPrefeitura').value = count - 1;
		}
	}
	
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
			$.getJSON('../consultas.php?opcao=cidades&valor='+idUF, function (dados){ 
				if (dados.length > 0){
					var option = '<option></option>';
					$.each(dados, function(i, obj){
						option += '<option value="'+obj.cidade+'">'+obj.cidade+'</option>';
						})
					$('#txtCidade').html(option).show();
				}
			});
		});

		
		
   		$('#btLimparHistorico').click(function(e){
			if(confirm('Deseja mesmo limpar o histórico deste usuário?')){
				location.href = 'cliente_administrar.php?id=<?=$id?>&limpar_historico=<?=$id?>';
			}
		});
		$('#link_ver').bind('click',function(){
//			window.open('mostra_sessao_usuario.php?email=<?=$linhalogin["email"]?>&senha=<?=md5($linhalogin['senha'])?>');
			window.open('mostra_sessao_usuario.php?email=<?=$linhalogin["email"]?>&senha=<?=md5($linhalogin['senha'])?>');
		});
		
	
		$.mostraResultado = function(texto, qualDiv){
			qualDiv.html(texto);
		}
		
	
		$.consultaBancoAjax = function(URL, qualDiv, oCampo){
	//		alert(URL);
				$.ajax({
					url: URL,
					type: 'get',
					dataType:"json",
					cache: false,
					async: true,
					beforeSend: function(){
					},
					success: function(retorno){
						if(!retorno.status){
							alert(retorno.mensagem)
							$.mostraResultado('', qualDiv);
							oCampo.val('').focus();
						}else{
							$.mostraResultado(retorno.mensagem, qualDiv);
						}
					}
				});
		};
	
	
		$.enviaAjax = function(URL, DATA, METHOD, DATATYPE, SUCCESS){
				return $.ajax({
					url: URL,
					data: DATA,
					type: METHOD,
					dataType: DATATYPE,
					cache: false,
					async: false,
					beforeSend: function(){
					},
					success: SUCCESS
				});
		};
	
	
		function getQTDEmpresasCadastradas(URL, DATA, METHOD, DATATYPE, SUCCESS){
			return $.enviaAjax(URL, DATA, METHOD, DATATYPE, SUCCESS);
		}

		
		$('#txtCNAE_Principal, input[id^="txtCodigoCNAE"]').change(function(){
			var 
				$campo 	= $(this)
				, $div		= $campo.attr('div')
				, $campoCheck		= $('#' + $campo.attr('check'))
				, $idEmpresa = $('#hidID').val();			
			;
			if($campo.val() != ''){
				var URL = '../meus_dados_empresa_consulta_cnae.php?acao=blur&codigo=' + $campo.val() + '&campoCheck=' + $campoCheck.val() + '&idEmpresa=' + $idEmpresa
				$.consultaBancoAjax(URL, $('#'+$div), $campo);
			}
			
		});

		$('.btEditarEmpresa').bind('click',function(e){
			e.preventDefault();
			window.open('cliente_administrar_empresas.php?id=' + $(this).attr('idUsuario') + '&editar=' + $(this).attr('idEmpresa') + '', '_blank', 'toolbar=0,location=0,menubar=0,width=430,height=600');
		});


		$(document).on('click','.btAtivaEmpresa',function(){
			var 
				$this = $(this)
				, $indice = $('.fa-circle').index($this)
				, $empresa = $this.attr("empresa")
				, $usuario = $this.attr("usuario")
				, $data = "acao=ativar&empresa=" + $empresa
			;

			//$('.fa-pencil-square-o').eq($indice).removeClass('iconesCinzaEscuro').addClass('iconesAzuis  btEditarEmpresa').css('cursor','pointer');

			return $.enviaAjax("cliente_administrar_empresas_excluir.php", $data, "get", "json", function(retorno){
				if(retorno.status == true) {
					$this.removeClass('iconesVermelhos btAtivaEmpresa').addClass('iconesVerdes btDesativaEmpresa');
					alert('Empresa ativada com sucesso.');
				}else{
					alert('Ocorreu um erro ao desativar a empresa');
				}
			});
			
		});
		
		$(document).on('click','.btDesativaEmpresa',function(){
			var 
				$this = $(this)
				, $indice = $('.fa-circle').index($this)
				, $empresa = $this.attr("empresa")
				, $usuario = $this.attr("usuario")
				, $data = "acao=desativar&empresa=" + $empresa
			;

			//$('.fa-pencil-square-o').eq($indice).removeClass('iconesAzuis btEditarEmpresa').addClass('iconesCinzaEscuro').css('cursor','default');
			
			return $.enviaAjax("cliente_administrar_empresas_excluir.php", $data, "get", "json", function(retorno){
				if(retorno.status == true) {
					$this.removeClass('iconesVerdes btDesativaEmpresa').addClass('iconesVermelhos btAtivaEmpresa');
					alert('Empresa desativada com sucesso.');
				}else{
					alert('Ocorreu um erro ao desativar a empresa');
				}
			});
			
		});

  });
	
</script>

<div class="principal">
<div class="titulo" style="margin-bottom:20px">Editar dados de: <?=$linhalogin["assinante"]?> <small>(cliente desde <?=date('m',strtotime($linhalogin["data_inclusao"]))?> de <?=date('Y',strtotime($linhalogin["data_inclusao"]))?>)</small>
	<?php if($linhalogin['status'] != 'servico-avulso'):?>
		<div id="link_ver" style="position:relative; float: right; font-size:12px;"><a href="#">Entrar como usuário</a></div>
	<?php endif;?>
</div>
<div style="width:450px; float:left">

<div class="tituloVermelho">Dados de Cobrança</div>
<form name="form_dados_cobranca" id="form_dados_cobranca" method="post" action="cliente_administrar_gravar_dados_cobranca.php">
	<table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
	  <tr>
		<td colspan="2" align="right" valign="top" class="formTabela">&nbsp;</td>
	  </tr>
	  <tr>
		<td width="123" align="right" valign="middle" class="formTabela">Assinante:</td>
		<td class="formTabela" width="300"><input name="txtAssinante" type="text" id="txtAssinante" style="width:300px; margin-bottom:0px" value="<?=$linhalogin["assinante"]?>" maxlength="200"  alt="Assinante"  /></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela">E-mail:</td>
		<td class="formTabela"><input name="txtEmail" type="text" id="txtEmail" style="width:300px; margin-bottom:0px" value="<?=$linhalogin["email"]?>" maxlength="200"  alt="E-mail"  /></td>
	  </tr>
	  <tr>
		<td align="right" valign="top" class="formTabela">Senha: </td>
		<td class="formTabela"><input name="txtSenha" type="text" id="txtSenha" style="width:90px; margin-bottom:0px" value="<?=$linhalogin['senha']?>" maxlength="10"/>
		  </td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="formTabela">Telefone:</td>
		<td valign="middle" class="formTabela"><div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
		  <div style="float:left">
			<input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:50px" value="<?=$linhalogin["pref_telefone"]?>" maxlength="3" alt="Prefixo do Telefone" class="inteiro" />
		  </div>
		  <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
		  <div style="float:left">
			<input name="txtTelefone" type="text" id="txtTelefone" style="width:75px" value="<?=$linhalogin["telefone"]?>" maxlength="9" alt="Telefone" class="inteiro" />
		  </div></td>
	  </tr>
		<tr>
		  <td colspan="2" style="height:10px;"></td>
		</tr>

	  <tr>
		<td align="right" valign="middle" class="formTabela">Tipo:</td>
		<td class="formTabela">
		  <input type="radio" name="rdbTipo" id="boleto_PJ" value="J" <?=$linhalogin['tipo'] == 'PJ' ? 'checked' : ''?>> <label for="boleto_PJ">Pessoa Jurídica</label>
		  &nbsp;
		  <input type="radio" name="rdbTipo" id="boleto_PF" value="F" <?=$linhalogin['tipo'] == 'PF' ? 'checked' : ''?>> <label for="boleto_PF">Pessoa Física</label>
		</td>
	  </tr>

		   <script>
	/*
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

			   if rdbTipo == "" {
				   alert ("Informe se a cobrança será na Pessoa Jurídica ou na Física");
				   return false;
			   }

			   else {}
	*/
		   </script>   

		<tr>
		  <td colspan="2" style="height:10px;"></td>
		</tr>
		<tr>
		  <td align="right" valign="middle" class="formTabela" id="txtSacado"><?=$linhalogin['tipo'] == 'PJ' ? 'Razão Social' : 'Nome'?>:</td>
		  <td class="formTabela"><input type="text" name="boleto_sacado" id="boleto_sacado" maxlength="200" style="width: 100%" value="<?=$linhalogin['sacado']?>" alt="<?=$linhalogin['tipo'] == 'PJ' ? 'Razão Social' : 'Nome'?>"></td>
		</tr>

		  <tr>
			<td align="right" valign="middle" class="formTabela" id="txtDocumento"><?=$linhalogin['tipo'] == 'PJ' ? 'CNPJ' : 'CPF'?>:</td>
			<td class="formTabela">
			<input type="text" name="boleto_cnpj" id="boleto_cnpj" maxlength="18" size="18" class="campoCNPJ" value="<?=$linhalogin['tipo'] == 'PJ' ? $linhalogin['documento'] : ''?>" style="display: <?=$linhalogin['tipo'] == 'PJ' ? 'block' : 'none'?>;" alt="CNPJ">
			<input type="text" name="boleto_cpf" id="boleto_cpf" maxlength="14" size="14" class="campoCPF" value="<?=$linhalogin['tipo'] == 'PF' ? $linhalogin['documento'] : ''?>" style="display: <?=$linhalogin['tipo'] == 'PF' ? 'block' : 'none'?>;" alt="CPF">
			</td>
		  </tr>

		  <tr>
			<td align="right" valign="middle" class="formTabela">Endereço:</td>
			<td class="formTabela"><input type="text" name="boleto_endereco" id="boleto_endereco" maxlength="75" style="width: 100%" value="<?=$linhalogin['endereco']?>" alt="Endereço"></td>
		  </tr>

		  <tr>
			<td align="right" valign="middle" class="formTabela">Bairro:</td>
			<td class="formTabela"><input type="text" name="boleto_bairro" id="boleto_bairro" maxlength="30" style="width: 100%" value="<?=$linhalogin['bairro']?>" alt="Bairro"></td>
		  </tr>

		  <tr>
			<td align="right" valign="middle" class="formTabela">UF:</td>
			<td class="formTabela">
			  <select name="selEstado" id="selEstado" alt="UF">
				  <option value="" <?php echo selected( '',$linhalogin['uf'] ); ?>></option>
	  <?

				  foreach($arrEstados as $dadosEstado){
					echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\" " . selected( $dadosEstado['sigla'], $linhalogin['uf'] ) . " >".$dadosEstado['sigla']."</option>";
					if($dadosEstado['sigla'] == $linhalogin['uf']){
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
				<option value="" <?php echo selected( '', $cidade ); ?>></option>
			  <?
			  if($idEstadoSelecionado != ''){
				$sql = "SELECT * FROM cidades WHERE id_uf = '" . $idEstadoSelecionado . "' ORDER BY cidade";
				$result = mysql_query($sql) or die(mysql_error());
				while($cidades = mysql_fetch_array($result)){
				  echo "<option value=\"".$cidades['cidade']."\" " . selected( $cidades['cidade'], $linhalogin['cidade']) . " >".$cidades['cidade']."</option>";
				}
			  }
			  ?>
			  </select>
			</td>
		  </tr>

		  <tr>
			<td align="right" valign="middle" class="formTabela">CEP:</td>
			<td class="formTabela"><input type="text" name="boleto_cep" id="boleto_cep" maxlength="9" size="12" class="campoCEP" value="<?=$linhalogin['cep']?>" alt="CEP"></td>
		  </tr>
 
	  <tr>
		<td colspan="2" align="center" valign="middle" class="formTabela">
			<br/>
			<input name="btnSalvar" type="submit" id="btnSalvar"  value="Salvar"/>
		</td>
	  </tr>
	</table>
	
	<div style="clear:both; width: 100%; height: 30px;"> </div>
	<div class="tituloVermelho">Descontos na Assinatura</div>
	
	<table>
		<tr>
			<td>
				Tipo Desconto:
			</td>
			<td><select id="descontoMesalidade" name="descontoMesalidade">
					<?php if($linhalogin['desconto_mesalidade'] == 1): ?>
						<option value="0">Sem desconto</option>
						<option value="1" selected >Desconto Padão</option>
						<option value="2">Desconto Individual</option>				
					<?php elseif($linhalogin['desconto_mesalidade'] == 2): ?>
						<option value="0">Sem desconto</option>
						<option value="1">Desconto Padão</option>
						<option value="2" selected >Desconto Individual</option>
					<?php else: ?>
						<option value="0" selected>Sem desconto</option>
						<option value="1">Desconto Padão</option>
						<option value="2">Desconto Individual</option>
					<?php endif; ?>
				</select>
			</td>
		</tr>	
		<tr class="lineAssinatura" style="display:none;">
			<td colspan="2">
				<br/>
				<b>Desconto Standard</b>
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Mensal:</td>
			<td>
				<input type="text" name="desconto_S_mensal" id="desconto_S_mensal" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_S_mensal'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Trimestral:</td>
			<td>
				<input type="text" name="desconto_S_trimestral" id="desconto_S_trimestral" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_S_trimestral'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Semestral:</td>
			<td>
				<input type="text" name="desconto_S_semestral" id="desconto_S_semestral" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_S_semestral'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Anual:</td>
			<td>
				<input type="text" name="desconto_S_anual" id="desconto_S_anual" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_S_anual'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td colspan="2">
				<br/>
				<b>Desconto Premium</b>
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Mensal:</td>
			<td>
				<input type="text" name="desconto_P_mensal" id="desconto_P_mensal" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_P_mensal'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Trimestral:</td>
			<td>
				<input type="text" name="desconto_P_trimestral" id="desconto_P_trimestral" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_P_trimestral'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Semestral:</td>
			<td>
				<input type="text" name="desconto_P_semestral" id="desconto_P_semestral" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_P_semestral'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr class="lineAssinatura" style="display:none;">
			<td align="right">Anual:</td>
			<td>
				<input type="text" name="desconto_P_anual" id="desconto_P_anual" class="current" style="width: 100%" value="<?php echo number_format($linhalogin['desconto_P_anual'], 2, ',', '.'); ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" valign="middle" class="formTabela">
				<br/>
				<input name="btnSalvar" type="submit" id="btnSalvar"  value="Salvar"/>
			</td>
		</tr>
	</table>

	<div style="clear:both; width: 100%; height: 30px;"> </div>
	<div class="tituloVermelho">Plano de Assiantura</div>
	
	<table>
	 <tr>
		<td align="right" valign="middle" class="formTabela">Tipo do Plano:</td>
		<td class="formTabela">
			<?php 
				
				// Define plano
				$plano = ($linhalogin['tipo_plano'] == 'P' ? 'Premium' : 'Standard'); 
				
				if($linhalogin['plano'] == 'mensalidade') {
					$assinatura = 'Mensal';
				} elseif($linhalogin['plano'] == 'trimestral') {
					$assinatura = 'Trimestral';
				} elseif($linhalogin['plano'] == 'semestral') {
					$assinatura = 'Semestral';
				} elseif($linhalogin['plano'] == 'anual') {
					$assinatura = 'Anual';
				}  			
				echo $plano.' - '.$assinatura; 
			?>
		</td>
	  </tr>
	  
		<?php 
			if($nomeContador){
				echo "<tr><td class='formTabela' valign='middle' align='right'>Contador Responsável:</td><td class='formTabela'>".$nomeContador."</td></tr>";
			}
		?>
	  <tr>
		<td align="right">Tipo do Pagamento:</td>
		<?php $dados_cartao_token = mysql_query("SELECT * FROM token_pagamento WHERE id_user = '".$_GET['id']."' ");
		$objeto=mysql_fetch_array($dados_cartao_token); ?>
		<td><span style="margin-right:20px"><?=$linhalogin['forma_pagameto']?></span>
		<?php if($linhalogin['forma_pagameto'] != "boleto") { ?><input type="checkbox" name="cheFormaPagamento" id="cheFormaPagamento" value="boleto" /> Mudar para Boleto<?php } ?></td>
		</tr>
		<tr>
			<td colspan="2" valign="middle" class="formTabela">
				<div id="divPagamentoCartao" style="margin-top:3px; margin-bottom:-3px; <?php if ($linhalogin['forma_pagameto'] == "boleto" or $linhalogin['forma_pagameto'] == "") {echo 'display:none';} ?>">
				  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
					<tr>
						<td width="108" align="right" valign="middle" class="formTabela">Número do Cartão:</td>
						<td class="formTabela" width="200"><span style="font-size:14px"><?php echo $objeto['numero_cartao'] ?></span></td>
					</tr>
					<tr>
						<td align="right" valign="middle" class="formTabela">Nome do Titular:</td>
						<td class="formTabela"><span style="font-size:14px"><?php echo $objeto['nome_titular']; ?></span></td>
					</tr>
					<tr>
						<td colspan="2" align="center" valign="middle" class="formTabela">
							<br/>
							<input name="btnSalvar" type="submit" id="btnSalvar"  value="Salvar"/>
						</td>
					</tr>
				  </table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" valign="middle" class="formTabela">
				<input type="hidden" name="hidID" id="hidID" value="<?php if( $linha["id"] == '' ) echo $_GET['id']; else echo $linha["id"]; ?>" />
			</td>
		</tr>
	</table>
</form>


<?php
$id = $_GET["id"];
//$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $id . "' LIMIT 0, 1";
//$resultado = mysql_query($sql)
//or die (mysql_error());

//$linha=mysql_fetch_array($resultado);
?>
<div style="clear:both; width: 100%; height: 30px;"> </div>
<div class="tituloVermelho">Pagamento Avulso</div>

<?php require_once('gerar-boleto.php'); ?>

<!--
<iframe src="cliente_administrar_empresas.php?id=<?=$id?>" style="width: 100%; min-height: 300px; border: 0; display: table; position: relative; float: left;">

</iframe>
-->

<br><br>
<?php
$sql_indica = "SELECT * FROM dados_indicacoes WHERE idUser = '" . $id . "' ORDER BY id DESC";
$resultado_indica = mysql_query($sql_indica)
or die (mysql_error());

if(mysql_num_rows($resultado_indica) > 0){
?>
<div class="tituloVermelho">Indicações Feitas</div>
  <table border="0" cellspacing="2" cellpadding="4" style="font-size:12px; width: 100%;">
    <tr>
      <th align="center" width="1%">Data</th>
      <th align="center" width="98%">Indicado</th>
      <th align="center" width="1%">Premiada</th>
    </tr>
    
<? while($linha_indica=mysql_fetch_array($resultado_indica)) { ?>

	<tr class="guiaTabela" style="background-color:#FFF" valign="top">
    	<td><?=date('d/m/Y',strtotime($linha_indica['data']))?></td>
    	<td><?=$linha_indica['nome_amigo']?></td>
    	<td align="center">
		  <a href="#" class="premiarIndicacao"><span <?=$linha_indica['premiada'] == 1 ? 'class="bullets_indica_ativo" title="Desmarcar prêmio"' : 'class="bullets_indica_naoentrou" title="Marcar prêmio"' ?> value="<?=$linha_indica['id']?>" style="margin-left: 20px;"></span></a>
		</td>
    </tr>

<? } ?>

	</table>

    
  <script>
  $(document).ready(function(e) {


      $('.premiarIndicacao').click(function(e){
				e.preventDefault();
				span_bullet = $(this).children();
				id_indicacao = ($(this).children().attr('value'));
				classe_bullet_atual = ($(this).children().attr('class'));
				if(classe_bullet_atual == 'bullets_indica_ativo'){
					status = '0'; // quando o bullet está verde a ação será de desmarcar o premio
				}else{
					status = '1'; // quando o bullet está vermelho a ação será de marcar o premio
				}
	      $.ajax({
	        url: 'cliente_administrar_gravar_premio.php',
	        type: 'POST',
	        data: 'selStatus=' + status + '&hidID=' + id_indicacao,
			cache:false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},			
	        success: function(resultado){
	          $('body').css('cursor','default');
						if(status == '0'){
							span_bullet.attr('class','bullets_indica_naoentrou');
						}else{
							span_bullet.attr('class','bullets_indica_ativo');
						}
						alert(resultado);
					}
				});

    	});
  });
  
  
  </script>


<?
}
?>

</div>

<script>

$(document).ready(function(e) {
    $('#modificarStatus').click(function(e){
		e.preventDefault();
		var statusSelecionado = ($("#form_status input[name=selStatus]:checked").val());
		$.ajax({
			url: 'cliente_administrar_gravar_status.php',
			type: 'POST',
			data: 'selStatus=' + statusSelecionado + '&hidIDStatus=' + $('#hidIDStatus').val(),
			cache:false,
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(resultado){
				$('body').css('cursor','default');
				if(resultado != ''){//'Status alterado com sucesso!'){
					alert(resultado);
				}
			}
		});
	});
});

function enviaMensagem(arquivoMensagem, assuntoMensagem){
	$.ajax({
		url: '../enviar_mensagens.php',
		type: 'POST',
		data: 'nomeMensagem=<?=addslashes($linhalogin["assinante"])?>&emailMensagem=<?=$linhalogin["email"]?>&assuntoMensagem=' + assuntoMensagem + '&arquivoMensagem=' + arquivoMensagem,
		cache:false,
		success: function(resultado){
		}
	});
}

</script>

<div style="width: 450px; float:right;">
  <div class="tituloVermelho">Status da Conta</div><br />

<form name="form_status" id="form_status" method="post" style="margin:0px">
<label><input name="selStatus" id="selStatus1" type="radio" value="ativo" <?php echo checked( 'ativo', $linhalogin['status'] ); ?> />&nbsp;Ativo</label>&nbsp;&nbsp;
<label><input name="selStatus" id="selStatus2" type="radio" value="inativo" <?php echo checked( 'inativo', $linhalogin['status'] ); ?> />&nbsp;Inativo</label>&nbsp;&nbsp;
<label><input name="selStatus" id="selStatus2" type="radio" value="demo" <?php echo checked( 'demo', $linhalogin['status'] ); ?> />&nbsp;Demo</label> &nbsp;&nbsp;
<label><input name="selStatus" id="selStatus2" type="radio" value="demoInativo" <?php echo checked( 'demoInativo', $linhalogin['status'] ); ?> />&nbsp;Demo Inativo</label>&nbsp;&nbsp;
<label><input name="selStatus" id="selStatus2" type="radio" value="cancelado" <?php echo checked( 'cancelado', $linhalogin['status'] ); ?> />&nbsp;Cancelado</label>&nbsp;&nbsp;
<label><input type="hidden" name="hidIDStatus" id="hidIDStatus" value="<?=$id?>" /></label>
 &nbsp; <a href="#" id="modificarStatus">Modificar</a></form>

  <div style="clear: both; width: 100%; height: 30px;"></div>

  <div class="tituloVermelho">Histórico de Cobrança</div>
<table id="historico_cobranca" border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="100%">
	<tr>
		<th width="140" align="center">Data de Vencimento</th>
        <th width="140" align="center">Status</th>
        <th align="center">Data do Pagamento</th>
	</tr>
<?php

// Função criada para pegar a data do pagamento. 	
function pegaDataPagamento($id, $idHistorico) {

	$sqlPagto = "SELECT * FROM relatorio_cobranca WHERE id='$id' AND idHistorico = '".$idHistorico."'";
	$resultado = mysql_query($sqlPagto)	or die (mysql_error());

	$idRelatorioAtual = '';
	
	if(mysql_num_rows($resultado) > 0){

		$dadosPagto = mysql_fetch_array($resultado);

		$idRelatorioAtual = $dadosPagto['idRelatorio'];
		
		$out = "<a id='pagtoCamp_".$idHistorico."' class='pagtoRelacionamento' href='#' data-idHistorico='".$idHistorico."' data-idRelatorio='".$dadosPagto['idRelatorio']."'>".date('d/m/Y', strtotime($dadosPagto['data']))."</a>";

	} else {

		$out = "<a id='pagtoCamp_".$idHistorico."' class='pagtoRelacionamento' href='#' data-idHistorico='".$idHistorico."' data-idRelatorio='Sem'>Sem Pagamento</a>"; 
	}
	
	$out .= "<div id='campoSelPagto_".$idHistorico."' class='campoSelPagto' style='display:none;'>
				<form id='form_".$idHistorico."' method ='post' style='margin-bottom: 0px;' action='atualiza_relacionamen_historioco_pagamento.php'>
					<input type='hidden' name='userId' value='".$id."' />
					<input type='hidden' name='idHistorico' value='".$idHistorico."' />
					<input type='hidden' name='idRelatorioOld' value='".$idRelatorioAtual."' />
					<select id='selPagto_".$idHistorico."' name='idRelatorio'>
						<option value=''>Selecione</option>
					</select>
					<a class='formSubimt' data-formId='form_".$idHistorico."' href='#'>Ok</a>
				</form>
			</div>";
	
	$out .= ' <div id="load_'.$idHistorico.'" style="text-align:center; display: none;">
	    		<img src="../images/loading.gif" width="16" height="16">
	    	</div> ';
	
	return $out;
}	

// TRAZENDO DADOS DO HISTÓRICO DE PAGAMENTOS
$sql = "SELECT * FROM historico_cobranca WHERE id='$id' ORDER BY idHistorico DESC LIMIT 0, 30";
$resultado = mysql_query($sql)
or die (mysql_error());
$i = 0;
while ($linha=mysql_fetch_array($resultado)) {
?>	
	<tr class="guiaTabela" style="background-color:#FFF;<?php if( $i > 18 ) echo 'display:none'; ?>" valign="top">
    	<td>
		<?php
		// if($linha["status_pagamento"] == "pendente"){
			?>
        <form method="post" name="form_hiscorico_cobranca<?=$linha["idHistorico"]?>" id="form_hiscorico_cobranca<?=$linha["idHistorico"]?>" action="cliente_administrar_gravar_historico_cobranca.php" style="margin:0px; padding:0px">
        <input type="text" name="txtDataCobranca" id="txtDataCobranca" value="<?=date('d/m/Y',strtotime($linha["data_pagamento"]))?>" style="width:75px; margin-right:5px" />
        <input type="hidden" name="hidIDHistorico" id="hidIDHistorico" value="<?=$linha["idHistorico"]?>" />
        <input type="hidden" name="hidIDUserHistorico" id="hidIDUserHistorico" value="<?=$linha["id"]?>" />
        <a href="javascript:document.getElementById('form_hiscorico_cobranca<?=$linha["idHistorico"]?>').submit()">Ok</a>
        </form>
		<?php 
		/*} else {
			echo date('d/m/Y',strtotime($linha["data_pagamento"]));
		}*/
		?>
        </td>
        <td>
		<?php 
		//if($linha["status_pagamento"] == "pendente"){?>
        <form method="post" name="form_hiscorico_cobranca_status<?=$linha["idHistorico"]?>" id="form_hiscorico_cobranca_status<?=$linha["idHistorico"]?>" action="cliente_administrar_gravar_historico_cobranca.php" style="margin:0px; padding:0px">
		<select name="selStatusCobranca" id="selStatusCobranca<?=$linha["idHistorico"]?>">
            <option value="pendente" <?php echo selected( 'pendente', $linha['status_pagamento'] ); ?>>Pendente</option> <? // STATUS PENDENTE É PARA LIBERAR ACESSO AO SITE MOMENTANEAMENTE?>
            <option value="perdoado" <?php echo selected( 'perdoado', $linha['status_pagamento'] ); ?>>Perdoado</option>
            <option value="pago" <?php echo selected( 'pago', $linha['status_pagamento'] ); ?>>Pago</option>
            <option value="não pago" <?php echo selected( 'não pago', $linha['status_pagamento'] ); ?>>Não Pago</option>
            <option value="a vencer" <?php echo selected( 'a vencer', $linha['status_pagamento'] ); ?>>A Vencer</option>
            <option value="vencido" <?php echo selected( 'vencido', $linha['status_pagamento'] ); ?>>Vencido</option>
<!--
            <option value="tid nulo" <?php echo selected( 'tid nulo', $linha['status_pagamento'] ); ?> disabled>TID nulo</option>
            <option value="erro XML" <?php echo selected( 'erro XML', $linha['status_pagamento'] ); ?> disabled>ERRO XML</option>
-->
        </select>
        <input type="hidden" name="hidIDHistorico" id="hidIDHistorico" value="<?=$linha["idHistorico"]?>" />
        <input type="hidden" name="hidIDUserHistorico" id="hidIDUserHistorico" value="<?=$linha["id"]?>" />
        <a href="javascript:if(document.getElementById('selStatusCobranca<?=$linha["idHistorico"]?>').value == 'não pago'){if(confirm('Você está alterando o status deste pagamento para não pago. Isso pode gerar problemas futuros. Deseja continuar?')){document.getElementById('form_hiscorico_cobranca_status<?=$linha["idHistorico"]?>').submit()}else{window.location.reload()}}else{document.getElementById('form_hiscorico_cobranca_status<?=$linha["idHistorico"]?>').submit()}">Ok</a>
        </form>
		<?php 
		/*
		} else {
			echo $linha["status_pagamento"];
		}*/
		?>        
        </td>
        <td align="center" id="Linha<?php echo $linha["idHistorico"];?>">
<?php				  
		echo pegaDataPagamento($id, $linha["idHistorico"]);
?>
        </td>
	</tr>
<?php $i++;} ?>
</table>
<?php if( $i > 18 ){ ?>
<span id="mostrarHistorico" style="margin-top:10px;margin-left:6px;color: #336699;cursor:pointer">ver tudo</span>
<?php } ?>
<br />
<br />

<script>

	
	$( document ).ready(function() {
	    		
  		$("#mostrarHistorico").click(function() {
  			$(this).css("display","none");
  			$("#historico_cobranca tr").css("display","table-row");
  		});	
		
		$('.formSubimt').click(function(e){
			
			e.preventDefault();
			
			var form = '#'+$(this).attr('data-formId');
			
			$(form).submit();			
			
		});
		
		$('.pagtoRelacionamento').click(function(e){
			e.preventDefault();
			
			$(this).hide();
			
			var idHistorico = $(this).attr('data-idHistorico');
			
			var idUser = <?php echo $id; ?>;
			
			var status = false;
				
			$.ajax({
				type: 'POST'
				, url: '/admin/ajax.php'
				, data: {PegaPagamentoCliente:'', idUser: idUser, idHistorico:idHistorico}				
				, dataType: 'json'
				, beforeSend: function() {
					
					$('.campoSelPagto').hide();
					$('.pagtoRelacionamento').show();
					
					$('#pagtoCamp_'+idHistorico).hide();
					$('#load_'+idHistorico).show();
				}
				, success: function(data) {
						
					console.log(data);
					
					if(data['optionPgto']) {
						status = true;							
						$('#selPagto_'+idHistorico).html(data['optionPgto'])
					}
				}
				, error: function(xhr) { 
					console.log(xhr);
					alert('Erro no ajax');
				}
				, complete: function() {
					
					if(status) {
						$('#campoSelPagto_'+idHistorico).show();
						$('#load_'+idHistorico).hide();
					} else {
						$('#pagtoCamp_'+idHistorico).show();
						$('#load_'+idHistorico).hide();
					}
				}
			});
		});
	});

</script>
<!--
<div class="tituloVermelho">Dados do Responsável</div>
<?php
$sql = "SELECT * FROM dados_do_responsavel WHERE id='" . $id . "' ORDER BY idSocio ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) {
?>
<form method="post" name="form_responsavel" id="form_responsavel" action="cliente_administrar_gravar_socio.php" >
  <table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
 <tr>
    <td colspan="2" align="right" valign="middle" class="formTabela">&nbsp;</td>
    </tr>
  <tr>
    <td width="142" align="right" valign="middle" class="formTabela">Nome:</td>
    <td class="formTabela" width="300"><input name="txtNome" type="text" id="txtNome" style="width:300px" value="<?=$linha["nome"]?>" maxlength="200" alt="Nome" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CPF:</td>
    <td class="formTabela"><input name="txtCPF" type="text" id="txtCPF" style="width:125px" value="<?=$linha["cpf"]?>" maxlength="14" alt="CPF" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">RG:</td>
    <td class="formTabela"><input name="txtRG" type="text" id="txtRG" style="width:300px" value="<?=preg_replace('/\D/','',$linha["rg"])?>" maxlength="17" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Data de Emissão:</td>
    <td class="formTabela"><input name="txtDataEmissao" type="text" style="width:300px" value="<?=$linha["data_de_emissao"]?>" maxlength="17" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Orgão Expeditor:</td>
    <td class="formTabela"><input name="txtOrgaoExpedidor" type="text" id="txtOrgaoExpedidor" style="width:300px" value="<?=$linha["orgao_expeditor"]?>" maxlength="250" /></td>
  </tr>
   <tr>
    <td align="right" valign="middle" class="formTabela">Data de Nascimento:</td>
    <td class="formTabela"><input name="txtDataNascimento" type="text" id="txtDataNascimento" style="width:70px" value="<?=$linha["data_de_nascimento"]?>" maxlength="10" /></td>
  </tr>
    <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela"><input name="txtEndereco" id="txtEndereco" type="text" style="width:300px" value="<?=$linha["endereco"]?>" maxlength="250" alt="Endereço" /></td>
  </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Bairro:</td>
      <td class="formTabela"><input name="txtBairro" id="txtBairro" type="text" style="width:300px" value="<?=$linha["bairro"]?>" maxlength="200" alt="Complemento" /></td>
    </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CEP:</td>
    <td class="formTabela"><input name="txtCEP" type="text" id="txtCEP" style="width:70px" value="<?=$linha["cep"]?>" maxlength="9" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Cidade:</td>
    <td class="formTabela"><input name="txtCidade" type="text" id="txtCidade" style="width:300px" value="<?=$linha["cidade"]?>" maxlength="200" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Estado:</td>
    <td class="formTabela"><select name="selEstado" id="selEstado">
      <option value="">Selecione...</option>
      <option value="AC" <?php echo selected( 'AC', $linha['estado'] ); ?> >AC</option>
      <option value="AL" <?php echo selected( 'AL', $linha['estado'] ); ?> >AL</option>
      <option value="AM"  <?php echo selected( 'AM', $linha['estado'] ); ?> >AM</option>
      <option value="AP"  <?php echo selected( 'AP', $linha['estado'] ); ?> >AP</option>
      <option value="BA"  <?php echo selected( 'BA', $linha['estado'] ); ?> >BA</option>
      <option value="CE"  <?php echo selected( 'CE', $linha['estado'] ); ?> >CE</option>
      <option value="DF" <?php echo selected( 'DF', $linha['estado'] ); ?> >DF</option>
      <option value="ES" <?php echo selected( 'ES', $linha['estado'] ); ?> >ES</option>
      <option value="GO" <?php echo selected( 'GO', $linha['estado'] ); ?> >GO</option>
      <option value="MA" <?php echo selected( 'MA', $linha['estado'] ); ?> >MA</option>
      <option value="MG" <?php echo selected( 'MG', $linha['estado'] ); ?> >MG</option>
      <option value="MS" <?php echo selected( 'MS', $linha['estado'] ); ?> >MS</option>
      <option value="MT" <?php echo selected( 'MT', $linha['estado'] ); ?> >MT</option>
      <option value="PA" <?php echo selected( 'PA', $linha['estado'] ); ?> >PA</option>
      <option value="PB" <?php echo selected( 'PB', $linha['estado'] ); ?> >PB</option>
      <option value="PE" <?php echo selected( 'PE', $linha['estado'] ); ?> >PE</option>
      <option value="PI" <?php echo selected( 'PI', $linha['estado'] ); ?> >PI</option>
      <option value="PR" <?php echo selected( 'PR', $linha['estado'] ); ?> >PR</option>
      <option value="RJ" <?php echo selected( 'RJ', $linha['estado'] ); ?> >RJ</option>
      <option value="RN" <?php echo selected( 'RN', $linha['estado'] ); ?> >RN</option>
      <option value="RO" <?php echo selected( 'RO', $linha['estado'] ); ?> >RO</option>
      <option value="RR" <?php echo selected( 'RR', $linha['estado'] ); ?> >RR</option>
      <option value="RS" <?php echo selected( 'RS', $linha['estado'] ); ?> >RS</option>
      <option value="SC" <?php echo selected( 'SC', $linha['estado'] ); ?> >SC</option>
      <option value="SE" <?php echo selected( 'SE', $linha['estado'] ); ?> >SE</option>
      <option value="SP" <?php echo selected( 'SP', $linha['estado'] ); ?> >SP</option>
      <option value="TO" <?php echo selected( 'TO', $linha['estado'] ); ?> >TO</option>
    </select></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Telefone:</td>
    <td valign="middle" class="formTabela"><div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
      <div style="float:left"><input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:50px" value="<?=$linha["pref_telefone"]?>" maxlength="3" class="inteiro" /></div>
      <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
      <div style="float:left"><input name="txtTelefone" type="text" id="txtTelefone" style="width:150px" value="<?=$linha["telefone"]?>" maxlength="9" class="inteiro" /></div></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Código CBO:</td>
    <td class="formTabela"><input name="txtCodigoCBO" type="text" id="txtCodigoCBO" style="width:300px" value="<?=$linha["codigo_cbo"]?>" maxlength="200" /></td>
  </tr>
  <tr>
  <tr>
  <td align="right" valign="middle" class="formTabela">NIT/CI: </td>
  <td class="formTabela"><input name="txtNit" type="text" id="txtNit" style="width:100px" value="<?=$linha["nit"]?>" maxlength="15" /></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" class="formTabela"><input type="hidden" name="hidSocioID" id="hidSocioID" value="<?=$linha["idSocio"]?>" />
    <input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">&nbsp;</td>
    <td class="formTabela">
	<input type="submit" value="Salvar"/>
  </td>
	</tr>
</table>
</form>
<?php } ?>
-->
</div><!-- FIM COLUNA DA DIREITA-->
<div style="clear:both"> </div>
<?
//$sql = "SELECT idPostagem FROM suporte WHERE id='$id' ORDER BY data DESC LIMIT 0,1";
//$resultado = mysql_query($sql)
//or die (mysql_error());
//$rs = mysql_fetch_array($resultado);
//$codigo = $rs['idPostagem'];
?>

<div style="width:970px;float:left">

	<div style="clear:both; width: 100%; height: 30px;"> </div>
		<div class="tituloVermelho">Dados da Empresa</div>



		<?
			$sql = "SELECT emp.id, razao_social, cnpj, ativa, cidade,estado FROM dados_da_empresa emp INNER JOIN login l ON emp.id = l.id  WHERE l.idUsuarioPai = '" . $id . "' ORDER BY ativa DESC, razao_social";
			$resultado = mysql_query($sql)
			or die (mysql_error());
			
		?>

				<table width="100%" cellpadding="5">
		        	<tr>
		            	<th style="width: 5% !important;" align="center">Alterar</th>
		            	<th style="width: 40% !important;" align="left">Razão Social</th>
		            	<th style="width: 15% !important;">CNPJ</th>
		            	<th style="width: 10% !important;">Estado</th>
		            	<th style="width: 15% !important;">Cidade</th>
		            	<th style="width: 5% !important;" align="center">Status</th>
		            </tr>
		<?

			if(mysql_num_rows($resultado) > 0){
				
				$esconde_botao_excluir = false;
				if(mysql_num_rows($resultado) == 1){ $esconde_botao_excluir = true;}
				
				// TRAZENDO OS DADOS PARA MONTAR TABELA DE AUTONOMOS
				while($linha=mysql_fetch_array($resultado)){
					$idEmpresa				= $linha["id"];
					$razao_social			= ($linha["razao_social"]);
					$cnpj 						= $linha["cnpj"];
					$ativa						= $linha["ativa"];
					$cidade						= $linha["cidade"];
					$estado						= $linha["estado"];
		?>
		            <tr>
		                <td class="td_calendario" align="center">
		                  <? //if($ativa == 1){ ?>
		                    	<i class="fa fa-pencil-square-o iconesAzuis iconesGrd btEditarEmpresa" style="cursor: pointer;" idUsuario="<?=$id?>" idEmpresa="<?=$idEmpresa?>"></i>
		                  		<!--<img src="images/edit.png" width="24" height="23" border="0" title="Editar" />-->
											<? //} else { ?>
		                    	<!--<i class="fa fa-pencil-square-o iconesCinzaEscuro iconesGrd"></i>-->
		                  <? //} ?>
		                </td>
		                <td class="td_calendario"><?=$razao_social?></td>
		                <td class="td_calendario" align="center"><?=str_replace('-', '', str_replace('/', '', str_replace('.', '', $cnpj)))?></td>      
		                <td class="td_calendario"><?=$estado?></td>
		                <td class="td_calendario"><?=$cidade?></td>
		                <td class="td_calendario" align="center">
		                  	<? if($ativa == 1){ ?>
		<!--                      <a href="#" class="" onClick="if (confirm('Tem certeza de que deseja desativar esta empresa?'))location.href='meus_dados_empresa_excluir.php?id=<?=$id?>&empresa=<?=$idEmpresa?>'" alt="Ativa" title="Ativa">-->
		                      	<i class="fa fa-circle iconesVerdes iconesPeq btDesativaEmpresa" empresa="<?=$idEmpresa?>" usuario="<?=$id?>" style="cursor: pointer;"></i> 
		<!--                      </a>-->
		                    <? } else { ?>
		<!--                      <a href="#" onClick="if (confirm('Você tem certeza que deseja reativar esta Empresa?'))location.href='meus_dados_empresa_ativar.php?id=<?=$id?>&empresa=<?=$idEmpresa?>';" alt="Inativa" title="Inativa">-->
		                        <i class="fa fa-circle iconesVermelhos iconesPeq btAtivaEmpresa" empresa="<?=$idEmpresa?>" usuario="<?=$id?>" style="cursor: pointer;"></i> 
		<!--                      </a>-->
		                    <? } ?>


											<? //($ativa == 1 ? 'ativa' : 'inativa')?>
		                </td>
		            </tr>
		<?	
				}

			}else{
		?>
				<tr>
		            <td class="td_calendario">&nbsp;</td>        
		            <td class="td_calendario">&nbsp;</td>        
		            <td class="td_calendario">&nbsp;</td>        
		            <td class="td_calendario">&nbsp;</td>     
		            <td class="td_calendario">&nbsp;</td>     
		            <td class="td_calendario">&nbsp;</td>       
		        </tr>
		<?		
			}

		?>

				</table>

</div>

<div style="clear:both; width: 100%; height: 30px;"> </div>
<div class="tituloVermelho">
  Histórico Suporte
</div>




<?php 



$sql = "SELECT * FROM suporte WHERE id='" . $_GET["id"] . "' AND tipoMensagem='pergunta' ORDER BY ultimaResposta DESC";

$resultado = mysql_query($sql)
or die (mysql_error());

$total = mysql_num_rows($resultado);

if ($total != 0) {
?>
<table border="0" cellpadding="4" cellspacing="2">
  <tr>
  	<th width="18"></th>
  	<th width="170">Início do chamado</th>
    <th width="516">Assunto</th>
    <th width="170">Última Resposta em</th>
    <th width="60">Ação</th>
  </tr>
<?php while ($linha=mysql_fetch_array($resultado)) { 
$arrNomeArquivo = explode("/",$linha["anexo"]);
?>  
<tr class="guiaTabela">
    <td class="" align="center"><?           
	echo '<div class="fa fa-circle ';
	switch($linha['status']){
		case "Respondido":
//			echo 'bullets_indica_ativo';
			echo 'iconesVerdeClaro';
			$alt = 'Respondido';
		break;
		case "Não Respondido":
		case "Em análise":
//			echo 'bullets_indica_naoentrou';
			echo 'iconesVermelhos';
			$alt = 'Não Respondido';
		break;
	}
	echo '" title="'.$alt.'" style="margin: 0 auto;"></div>';
?></td>
	<td class=""><?=date('d/m/Y', strtotime($linha["data"]))?>, às <?=date('H:i', strtotime($linha["data"]))?></td>
    <td class=""><?=$linha["titulo"]?></td>
    <td class="">
<?php
if ($linha["ultimaResposta"] == $linha["data"] || $linha["ultimaResposta"] == '') {
	echo 'Sem Resposta';
}
else {
	echo date('d/m/Y', strtotime($linha["ultimaResposta"])) . ", às " . date('H:i', strtotime($linha["ultimaResposta"]));
}
?></td>
    <td class=""><a href="suporte_visualizar.php?codigo=<?=$linha["idPostagem"]?>">Visualizar</a></td>
  </tr>
<?php } ?>
</table>
<?php } ?>

<br />
<br />
















<script>
$(document).ready(function(e) {
	
  $('#tabela_suporte tbody tr:even').css('background-color','#DEDDD6');
  $('#tabela_suporte tbody tr:odd').css('background-color','#FFFFFF');
	  
  var codigo = "<?=$codigo?>";

	$('#btConversaAnterior').bind('click',function(ev){
		ev.preventDefault();

		$.ajax({
			url:"suporte_conversa_anterior.php"
			, async:true
			, dataType:"json"
			, data: "idUsuario=<?=$idUsuario?>&idPergunta=" + codigo + "&nomeUser=<?=$nome?>"
			, type:"POST"
			, cache:false
			, success: function(result){
				//alert(result.data);
				if(result.data != ''){
					$('#tabela_suporte tbody:first').prepend(result.data);
					$('#tabela_suporte tbody tr:even').css('background-color','#DEDDD6');
					$('#tabela_suporte tbody tr:odd').css('background-color','#FFFFFF');
					codigo=result.codigo;
				}else{
					alert('Não há mais conversas...');
					$('#btConversaAnterior').css('display','none');
				}
			}
		});
		
	});
});
</script>

<div style="clear:both"> </div>




<div style="clear:both; margin-top: 10px;"> </div>
<?php
// TRAZENDO DADOS DO HISTÓRICO DE ACESSOS
$sql_log = "SELECT * FROM log_acessos WHERE id_user='$id' ORDER BY id DESC LIMIT 0, 100";
$resultado_log = mysql_query($sql_log)
or die (mysql_error());

if(mysql_num_rows($resultado_log) > 0){
?>

  <div style="width: 970px; float:left;">
      <a href="#" id="btLimparHistorico" style="position: relative; padding: 5px 5px 0 0;  float: right; text-align:right; font-weight: normal; font-size: 11px; text-decoration: none;">limpar histórico do usuário</a>
    <div class="tituloVermelho">
      Histórico da conta
    </div>
   
  <table border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="100%">
    <tr>
      <th width="20%" align="center">Data</th>
          <th align="center" width="80%">Interação</th>
    </tr>
  <?php
  while ($linha_log=mysql_fetch_array($resultado_log)) {
  ?>
    <tr class="guiaTabela" style="background-color:#FFF" valign="top">
        <td>
          <?=date('d/m/Y H:i:s',strtotime($linha_log["data"]))?>
        </td>
        <td>
          <?=htmlspecialchars($linha_log["acao"])?>
        </td>
    </tr>
  <?php } ?>
  </table>
  </div>

<?php 
} 
?>

<div style="clear:both"> </div>
</div>


<script>
	$(function(){
		
		if($('#descontoMesalidade').val() == 2) {
			$('.lineAssinatura').show();
		}
		
		$('#descontoMesalidade').change(function() {
			
			if($(this).val() == 2) {
				$('.lineAssinatura').show();
			} else {
				$('.lineAssinatura').hide();
			}
			
		});
	});
</script>

<?php include '../rodape.php' ?>