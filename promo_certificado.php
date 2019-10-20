<?php include 'header_restrita.php'; ?> 

 
<?php 
	
// precisa disso para atualizar os dados de cobranca, caso necessario

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
	
$resultado_dados_login = mysql_query($sql_dados_login)
or die (mysql_error());

$linha_dados_login = mysql_fetch_array($resultado_dados_login);

	$id_usuario = $linha_dados_login['id'];
	$email_usuario = $linha_dados_login['email'];
	$status_login = $linha_dados_login['status'];
	$assinante = $linha_dados_login['assinante'];
	$data_inclusao = $linha_dados_login['data_inclusao'];
	$forma_pagamento_assinante = $linha_dados_login['forma_pagameto'];
	$pref_telefone = $linha_dados_login['pref_telefone'];
	$telefone = $linha_dados_login['telefone'];

	$numero_cartao = $linha_dados_login['numero_cartao'];
	$codigo_seguranca = $linha_dados_login['codigo_seguranca'];
	$nome_titular = $linha_dados_login['nome_titular'];
	$data_validade = $linha_dados_login['data_validade'];
	
	$passNum = 0;
	while ($passNum < strlen($linha_dados_login["senha"])) {
		$senha = $senha . '*'; 
		$passNum = $passNum + 1;
	} 



//pega o assinante
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


$resultado_meus_dados = mysql_query($sql_meus_dados)
or die (mysql_error());
$linha_meus_dados = mysql_fetch_array($resultado_meus_dados);


// pega os dados de cobranca do assinante - se houver
$sql_dados_empresa = "SELECT sacado,endereco,bairro,cidade,cep,uf FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "' LIMIT 0, 1";
$resultado_dados_empresa = mysql_query($sql_dados_empresa)
or die (mysql_error());
$linha_dados_empresa = mysql_fetch_array($resultado_dados_empresa);


// gera alguns parametros do boleto
$valor_cobrar = number_format(354, 2, ",",".");
$dias_vencimento = 1;
$data_vencimento = date('Y-m-d',(mktime(0,0,0,date('m'),date('d') + $dias_vencimento ,date('Y'))));
$dataPagamento= date('Y-m-d',(mktime(0,0,0,date('m'),date('d'),date('Y'))));
$data_pagamento = $dataPagamento;
$data_diferenca = 0;
$mes_boleto = "99";	   
	   

//pega os estados
		  $arrEstados = array();
$sql = "SELECT * FROM estados ORDER BY sigla";
$result = mysql_query($sql) or die(mysql_error());
while($estados = mysql_fetch_array($result)){
	array_push($arrEstados,array('id'=>$estados['id'],'sigla'=>$estados['sigla']));
}



//faz funcionar o select do formulario
function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

function checked( $value, $prev ){
   return $value==$prev ? ' checked="checked"' : ''; 
};


?>



<!--Validacao-->

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
	

    document.getElementById('form_forma_pagamento').submit()
}

function adere_promocao() {

	if( document.getElementById('boleto_sacado').value == '' ){
		alert("Preencha as informações de cobrança antes de imprimir o boleto.");
		return;
	}
	if( document.getElementById('boleto_cnpj').value == '' ){
		alert("Preencha as informações de cobrança antes de imprimir o boleto.");
		return;
	}
	if( document.getElementById('boleto_endereco').value == '' ){
		alert("Preencha as informações de cobrança antes de imprimir o boleto.");
		return;
	}
	if( document.getElementById('boleto_bairro').value == '' ){
		alert("Preencha as informações de cobrança antes de imprimir o boleto.");
		return;
	}
	if( document.getElementById('selEstado').value == '' ){
		alert("Preencha as informações de cobrança antes de imprimir o boleto.");
		return;
	}
	if( document.getElementById('txtCidade').value == '' ){
		alert("Preencha as informações de cobrança antes de imprimir o boleto.");
		return;
	}
	if( document.getElementById('boleto_cep').value == '' ){
		alert("Preencha as informações de cobrança antes de imprimir o boleto.");
		return;
	}


	if(document.getElementById('cheTermos').checked == false) {
		window.alert('É necessário concordar com termos e condições de serviço.');
		}
		
		else {
			
			
			   <?php
		   echo "abreJanela('https://www.contadoramigo.com.br/boleto/boleto.php?identificacao=4843543&user=".$_SESSION["id_userSecaoMultiplo"]."&modulo=BOLETOLOCAWEB&ambiente=PRODUCAO&valor=" . urlencode(354) . "&promo=true&numdoc=" . urlencode(str_pad($_SESSION["id_userSecaoMultiplo"] . '99' . date('y', strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT)) . "&sacado=" . urlencode(trataTxt($linha_dados_empresa['sacado'])) . "&cgccpfsac=&enderecosac=" . urlencode(trataTxt($linha_dados_empresa['endereco'])) . "&numeroendsac=&complementosac=&bairrosac=" . urlencode(trataTxt($linha_dados_empresa["bairro"])) . "&cidadesac=" . urlencode(trataTxt($linha_dados_empresa['cidade'])) . "&cepsac=" . urlencode($linha_dados_empresa['cep']) . "&ufsac=" . urlencode(trataTxt($linha_dados_empresa['uf'])) . "&datadoc=" . urlencode(date("d/m/Y")) . "&vencto=" . urlencode(date('d/m/Y',strtotime("+1 days"))) . "&instr1=&instr2=&instr3=&instr4=&instr5=&numdocespec=&nossonum=&cnab=240&campolivreespec=&debug=&logoloja=http://www.contadoramigo.com.br/images/logo_email.png&tituloloja=Contador Amigo&botoesboleto=1&urltopoloja=&cabecalho=1','_blank','width=700, height=600, top=150, left=150, scrollbars=yes, resizable=yes');";
		   
		   ?>	
			
			
			}
		
}
</script>



<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Promoção</div>
  <div class="tituloVermelho">Certificado Digital GRÁTIS! </div>
<div style="font-size:14px; margin-bottom:20px">Você gostou do Contador Amigo mas não quer gastar com o Certificado Digital? Pois bem, esta é a sua chance. Faça uma assinatura semestral cheia, no valor de R$ 354,00 e deixe o certificado digital por nossa conta! <span class="destaque">O Contador Amigo lhe rembolsará  R$ 168,00</span>, referente um certificado E-CNPJ A1 da Valid Certificadora.</div>

<div class="titulo" style="margin-bottom:20px; font-size:16px; line-height:24px">

1. Imprima abaixo boleto promocional, referente à  assinatura semestral<br>
2. Adquira <a href="http://www.validcertificadora.com.br/e-CNPJ-A3-de-1-ano.htm/388C546B-98DA-4A0E-B8F3-89BBA8FB5FEE/RD007797" target="_blank">aqui</a> o seu certificado modelo A1 na Valid Certificadora<br>
3. Envie o comprovante de pagamento ao nosso Help Desk<br>
4. Depositaremos o valor em sua conta em até 2 dias úteis<br>


</div>

<div class="quadro_branco" style="padding:20px 20px 10px 20px"> <span class="tituloAzul">CONDIÇÕES DA PROMOÇÃO</span> 
  <ul>
<li>Ao fazer a assinatura semestral no Contador Amigo, o cliente será reembolsado no valor de R$ 18  (centro e sessenta e oito reais),  equivalente a um Certificado Digital E-CNPJ tipo A1, emitido pela Valid Certificadora.</li>
<li>O reembolso será efetuado por meio de depósito bancário em até 2 (dois) dias úteis, na conta cadastrada pelo assinante no Contador Amigo</li>
<li>Para fazer jus ao reembolso, o Certificado Digital precisa estar no nome da empresa que efetuou a assinatura no Contador Amigo e ser do tipo E-CNPJ.</li>
<li>Não serão feitos rembolsos para certificados digitais tipo E-CPF, mesmo que estejam em nome do titular da empresa.</li>
<li>O comprovante de pagamento do certificado digital não poderá ter data anterior ao  pagamento da assinatura promocional do Contador Amigo.</li>
<li>Esta promoção é válida exclusivamente para assinantes em período de avaliação de 30 dias.</li>
<li>O comprovante de pagamento do certificado digital  poderá ser enviado até 30 dias após o término da promoção.</li>
<li>Caso o assinante, por qualquer motivo, não  obtenha o certificado no prazo estipulado acima, o valor da assinatura não será devolvido. <br>
  </li>
</ul>

  <b>A PROMOÇÃO É VÁLIDA SOMENTE DURANTE O PERÍODO DE AVALIAÇÃO GRATUITA.</b></div>

<div class="tituloVermelho" style="margin-bottom:20px">Informe ou confirme seus dados de cobrança.</div>
<form name="form_forma_pagamento" id="form_forma_pagamento" method="post" action="promo_certificado_atualiza.php">
  <input type="hidden" name="hddStatus" id="hddStatus" value="<?=$linha_meus_dados['status']?>">

   
  <div id="divPagamentoBoleto">
  
  <table border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
    <tr>
      <td valign="middle" class="formTabela">
	  <?php
	  $sql = "SELECT * FROM dados_cobranca WHERE id='" . $linha_meus_dados["id"] . "' LIMIT 0, 1";
	  $resultado = mysql_query($sql)
	  or die (mysql_error());
	  $linha2=mysql_fetch_array($resultado);
	  
	 
	  
	  ?>
        <table width="352" border="0" cellpadding="0" cellspacing="3" class="formTabela" style="background:none">
          <tr>
            <td width="56" align="right" valign="middle" class="formTabela">Tipo:</td>
            <td width="287" class="formTabela">
            	<input type="radio" name="rdbTipo" id="boleto_PJ" value="J" <?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'checked' : ''?>> <label for="boleto_PJ">Pessoa Jurídica</label>
              &nbsp;
              <input type="radio" name="rdbTipo" id="boleto_PF" value="F" <?=strlen($linha2['documento']) <= 14 && $linha2['documento'] != '' ? 'checked' : ''?>> <label for="boleto_PF">Pessoa Física</label>
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
		   
	   if (rdbTipo == "") {
			   alert ("Informe se a cobrança será na Pessoa Jurídica ou na Física");
			   return false;
		   }
		   
		   else { }
			  
		}

        </script>   
          
          <table>
          <tr>
            <td colspan="2" style="height:10px;"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela" id="txtSacado"><?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'Razão Social' : 'Nome'?>:</td>
            <td class="formTabela"><input type="text" name="boleto_sacado" id="boleto_sacado" maxlength="200" style="width: 100%" value="<?=$linha2['sacado']?>" alt="<?=strlen($linha2['documento']) > 14 ? 'Razão Social' : 'Nome'?>"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela" id="txtDocumento"><?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? 'CNPJ' : 'CPF'?>:</td>
            <td class="formTabela">
            <input type="text" name="boleto_cnpj" id="boleto_cnpj" maxlength="18" size="18" class="campoCNPJ" value="<?=strlen($linha2['documento']) > 14 || $linha2['documento'] == '' ? $linha2['documento'] : ''?>" style="display: <?=strlen($linha2['documento']) > 14 || $linha2['documento'] == ''? 'block' : 'none'?>;" alt="CNPJ">
            <input type="text" name="boleto_cpf" id="boleto_cpf" maxlength="14" size="14" class="campoCPF" value="<?=strlen($linha2['documento']) <= 14 ? $linha2['documento'] : ''?>" style="display: <?=strlen($linha2['documento']) <= 14 && $linha2['documento'] != ''? 'block' : 'none'?>;" alt="CPF">
            </td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">Endereço:</td>
            <td class="formTabela"><input type="text" name="boleto_endereco" id="boleto_endereco" maxlength="75" style="width: 100%" value="<?=$linha2['endereco']?>" alt="Endereço"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">Bairro:</td>
            <td class="formTabela"><input type="text" name="boleto_bairro" id="boleto_bairro" maxlength="30" style="width: 100%" value="<?=$linha2['bairro']?>" alt="Bairro"></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">UF:</td>
            <td class="formTabela">
            
<select name="selEstado" id="selEstado" alt="UF">
                  <option value="" <?php echo selected( '',$linha2['uf'] ); ?>></option>
      <?
            
	
			
			
                  foreach($arrEstados as $dadosEstado){
                    echo "<option value=\"".$dadosEstado['id'].";".$dadosEstado['sigla']."\" " . selected( $dadosEstado['sigla'], $linha2['uf'] ) . " >".$dadosEstado['sigla']."</option>";
                    if($dadosEstado['sigla'] == $linha2['uf']){
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
                  echo "<option value=\"".$cidades['cidade']."\" " . selected( $cidades['cidade'], $linha2['cidade']) . " >".$cidades['cidade']."</option>";
                }
              }
              ?>
              </select>
            </td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="formTabela">CEP:</td>
            <td class="formTabela"><input type="text" name="boleto_cep" id="boleto_cep" maxlength="9" size="12" class="campoCEP" value="<?=$linha2['cep']?>" alt="CEP"></td>
          </tr>
          <tr><td></td><td valign="baseline" height="40"><input name="btnSalvar2" type="button" id="btnSalvar2"  value="Salvar" onclick="formFormaPagamentoSubmit()" /></td></tr>
        </table>
      </td>
    </tr>
  </table>

<input type="hidden" name="radFormaPagamento" id="radFormaPagamento3" value="boleto">    
<input type="hidden" name="hidID" id="hidID" value="<?=$id_usuario?>" />

</div>
</form> 
  

  
<label><input type="checkbox" name="cheTermos" id="cheTermos" onMouseDown="<?php $termo_clicado = "sim" ?>"/></label> 
  Li  e concordo com os termos e condições desta promoção.<br><br>


<!--botão que gera o boleto-->


<form name="emissao_de_boleto">

<!--<div id="concordar" name="concordar" class="destaque" style="display:none; padding:20px">É necessário concordar com termos e condições de serviço.</div>-->

<input type="button" name="button2" id="button2" class="botao_verde" value="IMPRIMA AQUI O BOLETO PROMOCIONAL" onclick="javascript:adere_promocao();"/> 
  </form>
</div>


<!--fim do botão que gera o boleto-->


    

</body>
</html>