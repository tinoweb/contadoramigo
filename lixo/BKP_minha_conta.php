<?php 
include 'header_restrita.php' ;?>
<?php 
$sql = "SELECT * FROM login WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

	$passNum = 0;
	while ($passNum < strlen($linha["senha"])) {
		$senha = $senha . '*'; 
		$passNum = $passNum + 1;
		} 

$sql = "SELECT * FROM dados_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
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
<script type="text/javascript">
$(document).ready(function(e) {

	$('#btnSalvar').click(function(){
		if($('#txtEmail').val() != $('#hddEmailUser').val()){
			$.ajax({
			  url:'assinatura_checa_email.php',
			  data: 'email=' + $('#txtEmail').val(), 
			  type: 'get',
			  async: false,
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
			frmAssinante();			
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
    if (!document.getElementById('radFormaPagamento3').checked) {
		var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";
	
		if( validElement('txtNumeroCartao', msg1) == false){return false}
	
		if(document.getElementById('txtNumeroCartao').value != '<?='************' . substr($linha["numero_cartao"],-4,4)?>') {
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
	document.getElementById('form_forma_pagamento').submit()
}


</script>
<div class="principal">

<div style="float:left; width:310px">
<table cellpadding="0" cellspacing="0" border="0">
  <tr><td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td></tr>
  <tr>
  <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
  <td width="285" bgcolor="#ffff99" valign="top">
  
<div style="width:245px; margin-left:20px; font-size:12px"> 
<div class="saudacao">Status da conta</div>
<?php
$sql2 = "SELECT * FROM historico_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' AND (status_pagamento='pendente' OR status_pagamento='não pago') ORDER BY idHistorico ASC LIMIT 0,1";
$resultado2 = mysql_query($sql2)
or die (mysql_error());

$linha2=mysql_fetch_array($resultado2);

$sql3 = "SELECT * FROM historico_cobranca WHERE id='" . $_SESSION["id_userSecao"] . "' AND status_pagamento='pendente' ORDER BY idHistorico DESC LIMIT 0,3";
$resultado3 = mysql_query($sql3)
or die (mysql_error());
$totalQuitar = mysql_num_rows($resultado3);

if($totalQuitar == "0") {
	$totalQuitar = 1;
}

if(date('Ymd',strtotime($linha2["data_pagamento"])) <= date('Ymd')){
	if (isset($_GET["erro_cartao"])) { 
		if($_GET["erro_cartao"] == 'invalido') {
?>
 <span class="tituloVermelho">Transação não autorizada</span><br />
 <br />
A operadora não autorizou o débito em seu cartão. Confira ao lado as informações de cobrança ou altere a forma de pagamento para boleto e tente novamente.<br />
<br />
<?php 
		} else { 
?>
 <span class="tituloVermelho">Falha na transação</span><br />
 <br />
 Houve uma falha na comunicação com a operadora de seu cartão.
 Confira ao lado as informações de cobrança e tente novamente.<br />
 <br />
<?php 
		}
	} else if(($_SESSION['status_userSecao'] == "demo") or ($_SESSION['status_userSecao'] == "demoInativo")){
?>
<span class="tituloVermelho">Prazo de avaliação esgotado</span><br />
<br /> 
Para continuar utilizando o Contador Amigo, você deverá confirmar sua assinatura.<br />
<br />
<strong>Preencha os dados de cobrança ao lado</strong> e clique no botão &quot;Ativar Assinatura&quot; para efetuar o pagamento da primeira mensalidade, no valor de <span class="destaque">R$ 50,00</span>.<br />
<br />
<?php
	} else {

		if(date('Ymd',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"])),date('d',strtotime($linha2["data_pagamento"]))+5,date('Y',strtotime($linha2["data_pagamento"]))))) > date('Ymd')){ 
?><span class="tituloVermelho">Assinatura pendente</span><br />
<br />
Consta em nosso sistema um débito  pendente no valor  de <span class="destaque">R$ <?=50 * $totalQuitar?>,00</span>. Sua conta ficará inativa a partir de <span class="destaque"><strong><?=date('d/m/Y',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"])),date('d',strtotime($linha2["data_pagamento"]))+5,date('Y',strtotime($linha2["data_pagamento"])))))?></strong></span>.<br />
<br />
<?php 
			if ($linha['forma_pagameto'] == "boleto") {

		?>
Para emitir a segunda via do boleto, clique no botão abaixo.<br />
<br />

<?php
			} else { 
?>
Confira ao lado seus dados de cobrança e efetue o pagamento, clicando no botão abaixo.<br />
 <br />
<?php
			}
		} else { 
?>
<span class="tituloVermelho">Conta Inativa</span><br />
<br />
Consta em nosso sistema um débito  pendente no valor  de <span class="destaque">R$ <?=50 * $totalQuitar?>,00</span>. Sua conta está inativa desde o dia <span class="destaque"><strong><?=date('d/m/Y',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"])),date('d',strtotime($linha2["data_pagamento"]))+5,date('Y',strtotime($linha2["data_pagamento"])))))?></strong></span>.<br />
<br />
<?php 
			if ($linha['forma_pagameto'] == "boleto") {
?>
Para emitir a segunda via do boleto, clique no botão abaixo.<br />
<br />
 Sua conta será reativada em até 48 horas após a efetuação do pagamento.<br />
<br />
<?php 
			} else { 
?>Confira ao lado seus dados de cobrança e efetue o pagamento, clicando no botão abaixo.<br />
 <br />
 Sua conta será reativada automaticamente.<br />
<br />
<?php 
			}
?>
<?php
		}
	} 
?>
 Em caso de dúvida, entre em contato conosco  através da página de <a href="suporte.php">ajuda</a>.<br />
<br />
<?php 
	if(($_SESSION['status_userSecao'] == "demo") or ($_SESSION['status_userSecao'] == "demoInativo")){ ?>
<form style="text-align:center">
  <label>
    <input type="button" name="button2" id="button2" value="Ativar Assinatura" onclick="<?php
    	if($linha['forma_pagameto'] == "") {
			echo "alert('Preencha os dados de cobrança antes de ativar sua assinatura.')";
		}else if ($linha['forma_pagameto'] == "boleto") {
			$sql3 = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
			$resultado3 = mysql_query($sql3)
			or die (mysql_error());
			$linha3=mysql_fetch_array($resultado3);
		
			echo "abreJanela('https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=50,00&numdoc=" . str_pad($_SESSION["id_userSecao"] . date('mY',strtotime($linha2["data_pagamento"])), 10, "0", STR_PAD_LEFT) . "&sacado=" . $linha3['razao_social'] . "&cgccpfsac=&enderecosac=" . $linha3['endereco'] ."&numeroendsac=&complementosac=&bairrosac=" . $linha3["bairro"] . "&cidadesac=" . $linha3['cidade'] . "&cepsac=" . $linha3['cep'] . "&ufsac=" . $linha3['estado'] . "&datadoc=" . date("d/m/Y") . "&vencto=" . date('d/m/Y',(mktime(0,0,0,date('m'),date('d')+5,date('Y')))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1','_blank','width=676, height=500, top=150, left=150, scrollbars=yes, resizable=yes');";
		} else {
			echo "abreDiv2('divCarregando');location.href='minha_conta_ativar.php'";
		}
?>" />
  </label>
</form>
<?php 
	} else { 
	

	?>
<form style="text-align:center">
  <label>
    <input type="button" name="button" id="button" value="Reemissão do Boleto" onclick="<?php
		if ($linha['forma_pagameto'] == "boleto") {
			$sql3 = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
			$resultado3 = mysql_query($sql3)
			or die (mysql_error());
			$linha3=mysql_fetch_array($resultado3);
		
			echo "abreJanela('https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . 50 * $totalQuitar . ",00&numdoc=" . str_pad($_SESSION["id_userSecao"] . date('mY',strtotime($linha2["data_pagamento"])), 10, "0", STR_PAD_LEFT) . "&sacado=" . trataTxt($linha3['razao_social']) . "&cgccpfsac=&enderecosac=" . trataTxt($linha3['endereco']) ."&numeroendsac=&complementosac=&bairrosac=" . trataTxt($linha3["bairro"]) . "&cidadesac=" . trataTxt($linha3['cidade']) . "&cepsac=" . $linha3['cep'] . "&ufsac=" . trataTxt($linha3['estado']) . "&datadoc=" . date("d/m/Y") . "&vencto=" . date('d/m/Y',(mktime(0,0,0,date('m'),date('d')+5,date('Y')))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1','_blank','width=676, height=500, top=150, left=150, scrollbars=yes, resizable=yes');";
		} else {
			if(date('Ymd',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"])),date('d',strtotime($linha2["data_pagamento"]))+5,date('Y',strtotime($linha2["data_pagamento"]))))) <= date('Ymd')) {
				$reativar_conta = "?reativar_conta";
			}
			echo "abreDiv2('divCarregando');location.href='minha_conta_quitar_cartao.php" . $reativar_conta . "'";
		}
	?>" />
    <input type="hidden" value="<?=$linha3['razao_social']?>" />
  </label>
</form>
<?php 
	} 
?>
<div id="divCarregando" style="margin-top:10px; text-align:center; <?="display:none"?>"><img src="images/loading.gif" width="16" height="16" /> Carregando, por favor aguarde...</div>
<?php 
} else { 
	if($_SESSION['status_userSecao'] == "demo"){
?>
<span class="tituloVermelho">Perído de avaliação gratuita</span><br />
<br />
Você poderá continuar utilizando o Contador Amigo por mais <span class="destaque"><?=date('d',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"]))-date('m'),date('d',strtotime($linha2["data_pagamento"]))-date('d'),date('Y',strtotime($linha2["data_pagamento"]))-date('Y'))))?> dia<?php if (date('d',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"]))-date('m'),date('d',strtotime($linha2["data_pagamento"]))-date('d'),date('Y',strtotime($linha2["data_pagamento"]))-date('Y')))) != 1) { ?>s<?php } ?></span>.<br />
<br />
Para confirmar sua assinatura, preencha os dados de cobrança ao lado e realize o pagamento da primeira mensalidade, no valor de <span class="destaque">R$ 50,00</span>.<br />
<br />
<form style="text-align:center">
  <label>
    <input type="button" name="button2" id="button2" value="Ativar Assinatura" onclick="<?php
    if($linha['forma_pagameto'] == "") {
		echo "alert('Preencha os dados de cobrança antes de ativar sua assinatura.')";
	}else if ($linha['forma_pagameto'] == "boleto") {
		$sql3 = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
		$resultado3 = mysql_query($sql3)
		or die (mysql_error());
		$linha3=mysql_fetch_array($resultado3);
		
		echo "abreJanela('https://comercio.locaweb.com.br/comercio.comp?identificacao=4843543&modulo=BOLETOLOCAWEB&ambiente=TESTE&valor=" . 50 * $totalQuitar . ",00&numdoc=" . str_pad($_SESSION["id_userSecao"] . date('mY',strtotime($linha2["data_pagamento"])), 10, "0", STR_PAD_LEFT) . "&sacado=" . $linha3['razao_social'] . "&cgccpfsac=&enderecosac=" . $linha3['endereco'] ."&numeroendsac=&complementosac=&bairrosac=" . $linha3["bairro"] . "&cidadesac=" . $linha3['cidade'] . "&cepsac=" . $linha3['cep'] . "&ufsac=" . $linha3['estado'] . "&datadoc=" . date("d/m/Y") . "&vencto=" . date('d/m/Y',(mktime(0,0,0,date('m'),date('d')+5,date('Y')))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1','_blank','width=676, height=500, top=150, left=150, scrollbars=yes, resizable=yes');";
	} else {
		echo "abreDiv2('divCarregando');location.href='minha_conta_ativar.php'";
	}
	?>" />
  </label>
</form>
<div id="divCarregando" style="margin-top:10px; text-align:center; <?="display:none"?>"><img src="images/loading.gif" width="16" height="16" /> Carregando, por favor aguarde...</div>
<?php
	} else { 
		if(isset($_GET["sucesso"])) { 
?>
<span class="tituloVermelho">Transação efetuada com sucesso!</span><br />
<br />
<?php 
		} else {
?>
<span class="tituloVermelho">Assinatura ativa
<br />
<br />
</span>
<?php 
		}
?>

Não existem pendências em sua conta e sua assinatura encontra-se ativa.<br />
<br />
Sua mensalidade vence todo dia <strong><?=date('d',strtotime($linha2["data_pagamento"]))?></strong>.<br />
<br />
Assinante desde: <strong><?=date('d/m/Y',strtotime($linha["data_inclusao"]))?></strong><br />

<br />
Para mudar a forma de pagamento, altere ao lado seus Dados de Cobranaça. A ação será válida para a sua próxima fatura.
<? 
	}
}
?>
</div>
</td>
  <td background="images/balloon_fundo_dir.png" width="7"> </td>
  </tr>
  <tr><td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td></tr>
  </table>
  </div>


<div style="float:right; width:615px">
<span class="titulo">Meus Dados</span><br /><br />

<div class="tituloVermelho">Assinante</div>
<form name="form_assinante" id="form_assinante" method="post" action="minha_conta_dados_cobranca_gravar.php"><br />

  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
    <tr>
      <td align="right" valign="middle" class="formTabela">Assinante:</td>
      <td class="formTabela"><input name="txtAssinante" type="text" id="txtAssinante" style="width:300px; margin-bottom:0px" value="<?=$linha["assinante"]?>" maxlength="200"  alt="Assinante"  /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">E-mail:</td>
      <td class="formTabela"><input name="txtEmail" type="text" id="txtEmail" style="width:300px; margin-bottom:0px" value="<?=$_SESSION["email_userSecao"]?>" maxlength="200"  alt="E-mail"  />
      <input type="hidden" name="hddEmailUser" id="hddEmailUser" value="<?=$_SESSION["email_userSecao"]?>" />
      <div name="divPass" id="divPass" style="display:none"></div>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top" class="formTabela">Senha: </td>
      <td class="formTabela">
      
      
      <span style="margin-right:5px"><?=$senha?></span><a href="JavaScript:abreDiv('divSenha');">Alterar senha</a>
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
          <input name="txtPrefixoTelefone" type="text" id="txtPrefixoTelefone" style="width:50px" value="<?=$linha["pref_telefone"]?>" maxlength="5" alt="Prefixo do Telefone" />
        </div>
        <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
        <div style="float:left">
          <input name="txtTelefone" type="text" id="txtTelefone" style="width:75px" value="<?=$linha["telefone"]?>" maxlength="9" alt="Telefone" />
        </div></td>
    </tr>
    <tr>
      <td colspan="2" valign="middle" class="formTabela"><input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class="formTabela"><input name="btnSalvar" type="button" id="btnSalvar"  value="Salvar" /></td>
    </tr>
  </table>

<br />

<?php
$data = date('d',strtotime($linha["data_inclusao"]));

if($data == 31){
	$data='1';
}
if(date('d/m',strtotime($linha["data_inclusao"])) == "29/02") {
	$data='28';
}

?>

</form>

<div style="margin-bottom:5px"><span class="tituloVermelho">Dados de cobrança</span></div>
<form name="form_forma_pagamento" id="form_forma_pagamento" method="post" action="minha_conta_forma_pagamento.php"><br />
Pagamento efetuado  por meio de:<br />
<br />

    <label><input type="radio" name="radFormaPagamento" id="radFormaPagamento1" value="visa" onclick="Javascript:abreDiv2('divPagamentoCartao');fechaDiv('divPagamentoBoleto')" <?php echo checked( 'visa', $linha['forma_pagameto'] ); ?> />
      <img src="images/visaicon.png" width="35" height="20" align="top" style="margin-right:15px" /></label>
    <label><input type="radio" name="radFormaPagamento" id="radFormaPagamento2" value="mastercard" onclick="Javascript:abreDiv2('divPagamentoCartao');fechaDiv('divPagamentoBoleto')" <?php echo checked( 'mastercard', $linha['forma_pagameto'] ); ?> />
      <img src="images/mastercardicon.png" width="31" height="20" align="top" style="margin-right:15px" /></label>
    <label><input type="radio" name="radFormaPagamento" id="radFormaPagamento3" value="boleto" onclick="Javascript:fechaDiv('divPagamentoCartao');abreDiv2('divPagamentoBoleto')" <?php echo checked( 'boleto', $linha['forma_pagameto'] ); ?> />
      <img src="images/boletoicon.gif" width="39" height="20" align="top" style="margin-right:15px" /></label>
    <div id="divPagamentoCartao" style="margin-top:3px; margin-bottom:-3px; <?php if (($linha['forma_pagameto'] == "boleto") or ($linha['forma_pagameto'] == "")) {echo 'display:none';} ?>">
  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
    <tr>
      <td align="right" valign="middle" class="formTabela">Número do Cartão:</td>
      <td class="formTabela"><input name="txtNumeroCartao" type="text" id="txtNumeroCartao" style="width:125px; margin-bottom:0px" value="<?php
      if (isset($linha["numero_cartao"])) {
		echo '************' . substr($linha["numero_cartao"],-4,4);
	}?>" maxlength="16"  alt="Número do Cartão"  /></td>
      </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Código de Segurança:</td>
      <td class="formTabela"><input name="txtCodigo" type="text" id="txtCodigo" style="width:35px; margin-bottom:0px" value="<?=$linha["codigo_seguranca"]?>" maxlength="3"  alt="Código de Segurança"  /></td>
      </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Nome do Titular:</td>
      <td class="formTabela"><input name="txtNomeTitular" type="text" id="txtNomeTitular" style="width:200px; margin-bottom:0px" value="<?=$linha["nome_titular"]?>" maxlength="200"  alt="Nome do Titular"  />
      <span style="font-size:10px">Como consta no cartão</span></td>
    </tr>
    <tr>
      <td align="right" valign="middle" class="formTabela">Data de Validade:</td>
      <td class="formTabela"><input name="txtDataValidade" type="text" id="txtDataValidade" style="width:60px; margin-bottom:0px" value="<? if(isset($linha["data_validade"])) { echo date('m/Y',strtotime($linha["data_validade"])); } ?>" maxlength="8"  alt="Data de Validade"  />
      <span style="font-size:10px"> MM/AAAA</span></td>
    </tr>
    <tr>
      <td colspan="2" valign="middle" class="formTabela">&nbsp;</td>
    </tr>
  </table>
</div>
<div id="divPagamentoBoleto" style="margin-top:3px; margin-bottom:-3px; <?php if ($linha['forma_pagameto'] != "boleto") {echo 'display:none';} ?>">
  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
    <tr>
      <td valign="middle" class="formTabela">
	  <?php
	  $sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";
	  $resultado = mysql_query($sql)
	  or die (mysql_error());
	  $linha2=mysql_fetch_array($resultado);
	  ?>
      <strong>Razão Social:</strong> <?=$linha2['razao_social']?><br />
      <strong>CNPJ:</strong> <?=$linha2['cnpj']?><br />
      <strong>Endereço:</strong> <?=$linha2['endereco']?>
      <br />
      <strong>Bairro:</strong> <?=$linha2["bairro"]?><br />
      <strong>Cidade:</strong> <?=$linha2['cidade']?><br />
      <strong>CEP:</strong> <?=$linha2['cep']?><br />
      <strong>Estado:</strong> <?=$linha2['estado']?>
      </td>
    </tr>
  </table>
</div>
    <div style="margin-top:3px; margin-left:62px"><input type="hidden" name="hidID" id="hidID" value="<?=$linha["id"]?>" /><input name="btnSalvar2" type="button" id="btnSalvar2"  value="Salvar" onclick="formFormaPagamentoSubmit()" /></div>
</form>    
</div>

<div style="clear:both"> </div>

</div>

<?php include 'rodape.php' ?>