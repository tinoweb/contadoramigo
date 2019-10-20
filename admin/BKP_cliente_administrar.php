<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php
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
?>
<?php include 'header.php' ?>

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
</script>

<div class="principal">
<div class="titulo" style="margin-bottom:20px">Editar dados de: <?=$linha["razao_social"]?></div>
<div style="width:450px; float:left">
<?php
$sql = "SELECT * FROM login, dados_cobranca WHERE dados_cobranca.id = login.id AND login.id='" . $id . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linhalogin=mysql_fetch_array($resultado);
?>
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
        <input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:50px" value="<?=$linhalogin["pref_telefone"]?>" maxlength="5" alt="Prefixo do Telefone" class="campoDDDTelefone" />
      </div>
      <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
      <div style="float:left">
        <input name="txtTelefone" type="text" id="txtTelefone" style="width:75px" value="<?=$linhalogin["telefone"]?>" maxlength="15" alt="Telefone" class="campoTelefone" />
      </div></td>
  </tr>
  <tr>
  <td align="right">Forma de Pagamento:</td>
    <td><span style="font-size:14px; margin-right:20px"><?=$linhalogin['forma_pagameto']?></span>
    <?php if($linhalogin['forma_pagameto'] != "boleto") { ?><input type="checkbox" name="cheFormaPagamento" id="cheFormaPagamento" value="boleto" /> Mudar para Boleto<?php } ?></td>
    </tr>
  <tr>
    <td colspan="2" valign="middle" class="formTabela"><div id="divPagamentoCartao" style="margin-top:3px; margin-bottom:-3px; <?php if ($linhalogin['forma_pagameto'] == "boleto" or $linhalogin['forma_pagameto'] == "") {echo 'display:none';} ?>">
      <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
        <tr>
          <td width="108" align="right" valign="middle" class="formTabela">Número do Cartão:</td>
          <td class="formTabela" width="200"><span style="font-size:14px">************<?=substr($linhalogin["numero_cartao"],-4,4)?></span></td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="formTabela">Nome do Titular:</td>
          <td class="formTabela"><span style="font-size:14px"><?=$linhalogin["nome_titular"]?></td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td colspan="2" valign="middle" class="formTabela"><input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle" class="formTabela"><input name="btnSalvar" type="submit" id="btnSalvar"  value="Salvar"/></td>
  </tr>
</table>
</form>
<?php
$id = $_GET["id"];
$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $id . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);
?>
<div class="tituloVermelho">Dados da Empresa</div>
<form name="form_empresa" id="form_empresa" method="post" action="cliente_administrar_gravar_empresa.php" style="margin:0px">
<input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" />
<table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
  <tr>
    <td colspan="2" align="right" valign="middle" class="formTabela">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" align="right" valign="top" class="formTabela"></td>
    </tr>
  <tr>
    <td width="173" align="right" valign="middle" class="formTabela">Razão Social:</td>
    <td class="formTabela"><input name="txtRazaoSocial" id="txtRazaoSocial" type="text" style="width:250px; margin-bottom:0px" value="<?=$linha["razao_social"]?>" maxlength="200"  alt="Razão Social"  /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Nome Fantasia:</td>
    <td class="formTabela"><input name="txtNomeFantasia" id="txtNomeFantasia" type="text" style="width:250px" value="<?=$linha["nome_fantasia"]?>" maxlength="200"  /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CNPJ:</td>
    <td class="formTabela"><input name="txtCNPJ" id="txtCNPJ" type="text" style="width:125px" value="<?=$linha["cnpj"]?>" maxlength="18" alt="CNPJ" class="campoCNPJ" /></td>
  </tr>
    <tr>
    <?php	
		$sql2 = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $id . "' AND tipo='1' LIMIT 0,1";
		$resultado2 = mysql_query($sql2)
		or die (mysql_error());

		$linha2=mysql_fetch_array($resultado2);
	?>
    <td align="right" valign="top" class="formTabela">C&oacute;digo da Atividade Principal:</td><td  class="formTabela"> <input name="txtCNAE_Principal" id="txtCNAE_Principal" type="text" style="width:64px; float:left; margin-right:3px" value="<?=$linha2["cnae"]?>" maxlength="9" onBlur="consultaBanco('../meus_dados_empresa_consulta_cnae.php?codigo='+document.getElementById('txtCNAE_Principal').value+'&campo=Principal', 'atividadePrincipal');" class="campoCNAE" />
      <div id="atividadePrincipal" style="float:left; width:199px"><script>consultaBanco('../meus_dados_empresa_consulta_cnae.php?codigo='+document.getElementById('txtCNAE_Principal').value+'&campo=Principal', 'atividadePrincipal');</script></div>
      </td>
    <?php	
$sql2 = "SELECT * FROM dados_da_empresa_codigos WHERE id='" . $id . "' AND tipo='2' ORDER BY idCodigo ASC";
$resultado2 = mysql_query($sql2)
or die (mysql_error());

$totalResultados = mysql_num_rows($resultado2);
?>
    </tr>
    <tr style="margin-top:-3px">
    <td align="right" valign="top" class="formTabela" style="padding-top:3px">C&oacute;digo das Atividades Secundárias:</td>
    <td class="formTabela" valign="top">
		<div id="content">
<?php if ($totalResultados!="0") {
$campo = 1;	
while ($linha2=mysql_fetch_array($resultado2)) {
?>
        	<div id="item<?=$campo?>">
            <div style="float:left; margin-right:5px"><input name="txtCodigoCNAE<?=$campo?>" id="txtCodigoCNAE<?=$campo?>" type="text" style="width:64px; margin-top:3px;" value="<?=$linha2["cnae"]?>" maxlength="9" onBlur="consultaBanco('../meus_dados_empresa_consulta_cnae.php?codigo='+document.getElementById('txtCodigoCNAE<?=$campo?>').value+'&campo=<?=$campo?>', 'atividade<?=$campo?>');" class="campoCNAE" /></div>
            <div id="atividade<?=$campo?>" style="float:left; width:199px"><script>consultaBanco('../meus_dados_empresa_consulta_cnae.php?codigo='+document.getElementById('txtCodigoCNAE<?=$campo?>').value+'&campo=<?=$campo?>', 'atividade<?=$campo?>');</script></div>
            <div style="clear:both"> </div>
</div>
<?php 
$campo = $campo + 1;
} }
?>
</div>
<a href="javascript:add();reposicionaBallons()">Adicionar</a> | <a href="javascript:remove();reposicionaBallons()">Remover</a>
      <input type="hidden" id="count" name="skill_count" value="<?php if ($totalResultados=="0") {echo "1";} else {echo $totalResultados + 1;} ?>">
	</td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Inscri&ccedil;&atilde;o no CCM:</td>
    <td class="formTabela"><input name="txtInscricaoCCM" id="txtInscricaoCCM" type="text" style="width:90px" value="<?=$linha["inscricao_no_ccm"]?>" maxlength="17" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Endere&ccedil;o:</td>
    <td class="formTabela"><input name="txtEndereco" id="txtEndereco" type="text" style="width:250px" value="<?=$linha["endereco"]?>" maxlength="250" alt="Endereço" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Bairro:</td>
    <td class="formTabela"><input name="txtBairro" id="txtBairro" type="text" style="width:250px" value="<?=$linha["bairro"]?>" maxlength="200" alt="Complemento" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">CEP:</td>
    <td class="formTabela"><input name="txtCEP" id="txtCEP" type="text" style="width:70px" value="<?=$linha["cep"]?>" maxlength="9" alt="CEP" class="campoCEP" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Cidade:</td>
    <td class="formTabela"><input name="txtCidade" id="txtCidade" type="text" style="width:250px" value="<?=$linha["cidade"]?>" maxlength="200" alt="Cidade" /></td>
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
      <div style="float:left"><input name="txtPrefixoTelefone" id="txtPrefixoTelefone" type="text" style="width:50px" value="<?=$linha["pref_telefone"]?>" maxlength="5" class="campoDDDTelefone" /></div>
      <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
      <div style="float:left"><input name="txtTelefone" id="txtTelefone" type="text" style="width:75px" value="<?=$linha["telefone"]?>" maxlength="15" class="campoTelefone" /></div></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Ramo de Atividade:</td>
    <td class="formTabela"><select name="selRamoAtividade" id="selRamoAtividade">
      <option value="">Selecione...</option>
      <option value="Comércio" onClick="javascript:alert(msg3)" <?php echo selected( 'Comércio', $linha['ramo_de_atividade'] ); ?> >Comércio</option>
      <option value="Indústria" onClick="javascript:alert(msg3)" <?php echo selected( 'Indústria', $linha['ramo_de_atividade'] ); ?> >Indústria</option>
      <option value="Prestação de Serviços" <?php echo selected( 'Prestação de Serviços', $linha['ramo_de_atividade'] ); ?> >Prestação de Serviços</option>
      </select></td>
  </tr>
<!--
<tr style="margin-top:-3px">
    <td align="right" valign="top" class="formTabela" style="padding-top:3px">Código de Serviço (Prefeitura)</td>
    <td class="formTabela">
      <div id="contentPrefeitura">
  <?php 
$sql2 = "SELECT * FROM dados_da_empresa_codigo_prefeitura WHERE id='" . $id . "' ORDER BY idCodigo ASC";
$resultado2 = mysql_query($sql2)
or die (mysql_error());

$totalResultados = mysql_num_rows($resultado2);	
if ($totalResultados=="0") {
?>
        <div id="itemPrefeitura1">
          <input name="txtCodigoAtividadePrefeitura1" id="txtCodigoAtividadePrefeitura1" type="text" style="width:50px; float:left; margin-right:3px; margin-top:3px" value="" maxlength="5" onBlur="consultaBanco('meus_dados_empresa_consulta_codigo_prefeitura.php?codigo='+document.getElementById('txtCodigoAtividadePrefeitura1').value+'&campo=1', 'denomicacaoPrefeitura1');" />
          <div style="float:left; margin-right:5px; width:210px"></div>
          <div id="denomicacaoPrefeitura1" style="float:left; margin-top:5px; margin-bottom:-5px"></div>
          <div style="clear:both"> </div>
          </div>
  <?php
} else {
	$campo = 1;
	while ($linha2=mysql_fetch_array($resultado2)) {
?>
        <div id="itemPrefeitura<?=$campo?>">
          <input name="txtCodigoAtividadePrefeitura<?=$campo?>" id="txtCodigoAtividadePrefeitura<?=$campo?>" type="text" style="width:50px; float:left; margin-right:3px; margin-top:3px" value="<?=$linha2["codigo_prefeitura"]?>" maxlength="5" onBlur="consultaBanco('../meus_dados_empresa_consulta_codigo_prefeitura.php?codigo='+document.getElementById('txtCodigoAtividadePrefeitura<?=$campo?>').value+'&campo=<?=$campo?>', 'denomicacaoPrefeitura<?=$campo?>');" />
          <?php if ($campo == 1) { ?><div style="float:left; margin-right:5px; margin-top:5px"></div><?php } ?>
          <div id="denomicacaoPrefeitura<?=$campo?>" style="float:left; width:210px"><script>consultaBanco('../meus_dados_empresa_consulta_codigo_prefeitura.php?codigo='+document.getElementById('txtCodigoAtividadePrefeitura<?=$campo?>').value+'&campo=<?=$campo?>', 'denomicacaoPrefeitura<?=$campo?>');</script></div>
          <div style="clear:both"> </div>
          </div>
  <?php 
	$campo = $campo + 1;
	}
}
?>
  </div>
      <a href="javascript:addPrefeitura();reposicionaBallons()">Adicionar</a> | <a href="javascript:removePrefeitura();reposicionaBallons()">Remover</a>
      <input type="hidden" id="countPrefeitura" name="countPrefeitura" value="<?php if ($totalResultados=="0") {echo "2";} else {echo $totalResultados + 1;} ?>">
      </td>
  </tr>
-->
  <tr>
    <td align="right" valign="middle" class="formTabela">Regime de Tributação:</td>
    <td class="formTabela"><select name="selRegimeTributacao" id="selRegimeTributacao">
      <option value="">Selecione...</option>
      <option value="Simples" <?php echo selected( 'Simples', $linha['regime_de_tributacao'] ); ?> >Simples</option>
      <option value="Lucro Presumido" onClick="javascript:alert(msg4)" <?php echo selected( 'Lucro Presumido', $linha['regime_de_tributacao'] ); ?> >Lucro Presumido</option>
    </select></td>
  </tr>
<tr>
    <td align="right" valign="top" class="formTabela">Tipo de empresa:</td>
    <td class="formTabela"><select name="selInscritaComo" id="selInscritaComo" onchange="selecionaRegistro()">
      <option value="Empresa Individual" <?php echo selected( 'Empresa Individual', $linha['inscrita_como'] ); ?>  onchange="selecionaRegistro()" >Empresa Individual</option>
      <option value="Sociedade Empresarial Limitada" <?php echo selected( 'Sociedade Empresarial Limitada', $linha['inscrita_como'] ); ?> onchange="selecionaRegistro()"  >Sociedade Empresarial Limitada</option>
      <option value="Sociedade Simples" <?php echo selected( 'Sociedade Simples', $linha['inscrita_como'] ); ?> onchange="selecionaRegistro()" >Sociedade Simples</option>
    </select></td>
  </tr>
<tr>
    <td colspan="2" class="formTabela">
          <table border="0" width="100%" cellpadding="0" cellspacing="3" class="formTabela" id="divRegistroNire" style="background:none<?php if ($linha['inscrita_como'] == 'Sociedade Simples' ) { echo '; display:none'; } ?>">
 		<tr>
      	<td align="right" valign="middle" class="formTabela" width="170">Registro NIRE:</td>
	    <td class="formTabela"><input name="txtRegistroNire" id="txtRegistroNire" type="text" style="width:90px; float:left; margin-right:3px" value="<?=$linha["registro_nire"]?>" maxlength="12" alt="Cidade" class="campoNIRE" /> 
	    <div style="float:left; margin-right:5px; margin-top:5px"><span style="font-size:10px">9999999999-9</span></div></td>
      	</tr>
      </table>
      
      <table border="0" width="100%" cellpadding="0" cellspacing="3" class="formTabela" id="divRegistroCartorio" style="background:none<?php if ($linha['inscrita_como'] != 'Sociedade Simples' ) { echo '; display:none'; } ?>">
 		<tr>
      	<td align="right" valign="middle" class="formTabela" width="170">Número do Cartório:</td>
	    <td class="formTabela"><input name="txtNumCartorio" id="txtNumCartorio" type="text" style="width:55px; float:left; margin-right:3px" value="<?=$linha["numero_cartorio"]?>" maxlength="7" alt="Cidade" />	</td>
      	</tr>
 		<tr>
 		  <td align="right" valign="middle" class="formTabela">Registro no Cartório:</td>
 		  <td class="formTabela"><input name="txtRegistroCartorio" id="txtRegistroCartorio" type="text" style="width:150px; float:left; margin-right:3px" value="<?=$linha["registro_cartorio"]?>" maxlength="20" alt="Cidade" /></td>
 		  </tr>
      </table>
    </td>
    </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Data de Cria&ccedil;&atilde;o:</td>
    <td class="formTabela"><input name="txtDataCriacao" id="data_de_criacao" type="text" style="width:75px" value="<?=$linha["data_de_criacao"]?>" maxlength="10" class="campoData" /></td>
  </tr>
    <tr>
      <td colspan="2" valign="middle" class="formTabela"></td>
      </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class="formTabela"><input type="submit" value="Salvar" /></td>
      </tr>
  </table>
  </form>
<br />
</div>

<script>

$(document).ready(function(e) {
    $('#modificarStatus').click(function(e){
		e.preventDefault();
		var statusSelecionado = ($("#form_status input[name=selStatus]:checked").val());
		$.ajax({
			url: 'cliente_administrar_gravar_status.php',
			type: 'POST',
			data: 'selStatus=' + statusSelecionado + '&hidID=' + $('#hidID').val(),
			beforeSend: function(){
				$('body').css('cursor','wait');
			},
			success: function(resultado){
				$('body').css('cursor','default');
				if(resultado == 'Status alterado com sucesso!'){
					if(statusSelecionado == 'ativo'){
						if(confirm('Deseja enviar mensagem de ativação de usuário?')){
							enviaMensagem('conta_reativada_boleto', 'Contador Amigo - Ativação de Conta');
						}
					}
				}else{
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
		success: function(resultado){
		}
	});
}

</script>

<div style="width: 450px; float:right;">
  <div class="tituloVermelho">Status da Conta</div><br />

<form name="form_status" id="form_status" method="post" style="margin:0px">
<label><input name="selStatus" id="selStatus1" type="radio" value="ativo" <?php echo checked( 'ativo', $linhalogin[status] ); ?> />Ativo</label> &nbsp; 
<label><input name="selStatus" id="selStatus2" type="radio" value="inativo" <?php echo checked( 'inativo', $linhalogin[status] ); ?> />Inativo</label> &nbsp;
<label><input name="selStatus" id="selStatus2" type="radio" value="demo" <?php echo checked( 'demo', $linhalogin[status] ); ?> />Demo</label> &nbsp;
<label><input name="selStatus" id="selStatus2" type="radio" value="demoInativo" <?php echo checked( 'demoInativo', $linhalogin[status] ); ?> />Demo Inativo</label>
<label><input name="selStatus" id="selStatus2" type="radio" value="cancelado" <?php echo checked( 'cancelado', $linhalogin[status] ); ?> />Cancelado</label>
<label><input type="hidden" name="hidID" id="hidID" value="<?=$id?>" /></label>
 &nbsp; <a href="#" id="modificarStatus">Modificar</a></form>
<br />
<br />


  <div class="tituloVermelho">Histórico de Cobrança</div>
<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="100%">
	<tr>
		<th width="140" align="center">Data</th>
        <th align="center" width="139">Tipo de cobrança</th>
        <th align="center" width="139">Status</th>
	</tr>
<?php
// TRAZENDO DADOS DO HISTÓRICO DE PAGAMENTOS
$sql = "SELECT * FROM historico_cobranca WHERE id='$id' ORDER BY idHistorico DESC LIMIT 0, 30";
$resultado = mysql_query($sql)
or die (mysql_error());
while ($linha=mysql_fetch_array($resultado)) {
?>
	<tr class="guiaTabela" style="background-color:#FFF" valign="top">
    	<td>
		<?php
		// if($linha["status_pagamento"] == "pendente"){
			?>
        <form method="post" name="form_hiscorico_cobranca<?=$linha["idHistorico"]?>" id="form_hiscorico_cobranca<?=$linha["idHistorico"]?>" action="cliente_administrar_gravar_historico_cobranca.php" style="margin:0px; padding:0px">
        <input type="text" name="txtDataCobranca" id="txtDataCobranca" value="<?=date('d/m/Y',strtotime($linha["data_pagamento"]))?>" style="width:75px; margin-right:5px" />
        <input type="hidden" name="hidIDHistorico" id="hidIDHistorico" value="<?=$linha["idHistorico"]?>" />
        <input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" />
        <a href="javascript:document.getElementById('form_hiscorico_cobranca<?=$linha["idHistorico"]?>').submit()">Ok</a>
        </form>
		<?php 
		/*} else {
			echo date('d/m/Y',strtotime($linha["data_pagamento"]));
		}*/
		?>
        </td>
        <td align="center"><?php
    switch ($linha["tipo_cobranca"]){
		case 'visa': $forma_pagameto = '<img src="../images/visaicon.png" width="35" height="20" align="top" style="margin-right:15px" title="Visa" />'; break;
		case 'mastercard': $forma_pagameto = '<img src="../images/mastercardicon.png" width="31" height="20" align="top" style="margin-right:15px" title="MasterCard" />'; break;
		case 'boleto': $forma_pagameto = '<img src="../images/boletoicon.gif" width="39" height="20" align="top" style="margin-right:15px" title="Boleto Bancário" />'; break;
		case '': $forma_pagameto = ''; break;
		//case '': $forma_pagameto = ''; break;
	}
	echo $forma_pagameto;
	?></td>
        <td>
		<?php 
		//if($linha["status_pagamento"] == "pendente"){?>
        <form method="post" name="form_hiscorico_cobranca_status<?=$linha["idHistorico"]?>" id="form_hiscorico_cobranca_status<?=$linha["idHistorico"]?>" action="cliente_administrar_gravar_historico_cobranca.php" style="margin:0px; padding:0px">
		<select name="selStatusCobranca" id="selStatusCobranca">
            <option value="não pago" <?php echo selected( 'não pago', $linha['status_pagamento'] ); ?>>Não Pago</option>
            <option value="pago" <?php echo selected( 'pago', $linha['status_pagamento'] ); ?>>Pago</option>
            <option value="pendente" <?php echo selected( 'pendente', $linha['status_pagamento'] ); ?>>Pendente</option>
            <option value="perdoado" <?php echo selected( 'perdoado', $linha['status_pagamento'] ); ?>>Perdoado</option>
        </select>
        <input type="hidden" name="hidIDHistorico" id="hidIDHistorico" value="<?=$linha["idHistorico"]?>" />
        <input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" />
        <a href="javascript:document.getElementById('form_hiscorico_cobranca_status<?=$linha["idHistorico"]?>').submit()">Ok</a>
        </form>
		<?php 
		/*
		} else {
			echo $linha["status_pagamento"];
		}*/
		?>        
        </td>
	</tr>
<?php } ?>
</table>
<br />
<br />

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
    <td class="formTabela"><input name="txtRG" type="text" id="txtRG" style="width:300px" value="<?=$linha["rg"]?>" maxlength="17" /></td>
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
      <div style="float:left"><input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:50px" value="<?=$linha["pref_telefone"]?>" maxlength="5" /></div>
      <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
      <div style="float:left"><input name="txtTelefone" type="text" id="txtTelefone" style="width:150px" value="<?=$linha["telefone"]?>" maxlength="15" /></div></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Código CBO:</td>
    <td class="formTabela"><input name="txtCodigoCBO" type="text" id="txtCodigoCBO" style="width:300px" value="<?=$linha["codigo_cbo"]?>" maxlength="200" /></td>
  </tr>
  <tr>
<!--
  <tr>
    <td align="right" valign="middle" class="formTabela">Pró-labore:</td>
    <td class="formTabela"><input name="txtProLabore" type="text" id="txtProLabore" style="width:300px" value="<?=number_format($linha["pro_labore"],2,",",".")?>" maxlength="200" /></td>
  </tr>
-->
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
</div>


<div style="clear:both"> </div>
</div>
<?php include '../rodape.php' ?>