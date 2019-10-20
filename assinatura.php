<?php session_start(); $paginaAssinatura = '';?>
<?php $nome_meta = "assinatura_orientacoes"; ?>
<?php include 'header.php' ?>

	
	
<!-- Google Code for assinatura Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1067575546;
var google_conversion_language = "pt";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "TgP2CIWs51cQ-tGH_QM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1067575546/?label=TgP2CIWs51cQ-tGH_QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>	



<script type="text/javascript">

jQuery(document).ready(function() {		

	$('#btProsseguir').click(function(e){
		e.preventDefault();
		formSubmitAssina();
	});
	
	$('#link_aviseme').bind('click',function(e){
//		if($('#textoAviseme').css('margin-bottom') == '20px'){
//			$('#textoAviseme').css('margin-bottom','0');
//		} 

		e.preventDefault();
		
		if($('#mensagemEnvio').length > 0){
			if($('#mensagemEnvio').css('display') == 'block'){
				$('#mensagemEnvio').css('display','none');
			}
		}
		
		if($('#formAvise').length > 0){
			$('#formAvise').toggle();
			if($('#formAvise').css('display') == 'none'){
				$('#textoAviseme').css('margin-bottom','0');
			} else {
				$('#textoAviseme').css('margin-bottom','20px');
			}
		}
	});

});


var msg1 = 'É necessário preencher o campo';
var msg2 = 'É necessário selecionar ';
var msg3 = 'No momento o Contador Amigo não oferece suporte para empresas do ramo de Comércio e Indústria.';
var msg4 = 'No momento o Contador Amigo não oferece suporte para empresas com Lucro Presumido.';

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




function consultaDadosExistentes(){
	prosseguir = true;
	fechaDiv('entrar');
	if($('#txtEmailAssina').val() != ''){
		$.ajax({
		  url:'assinatura_checa_dados.php',
		  data: 'email=' + $('#txtEmailAssina').val(),
		  type: 'get',
		  cache: false,
		  async: false,
		  beforeSend: function(){
				$("#hidPass").val('');
				$('#btProsseguir').val('Aguarde...').attr('disabled',true);
		  },
		  success: function(retorno){
				//alert(retorno);
				var arrRetorno = retorno.split("|");
				
			$('#hidPass').val(arrRetorno[1]);
			if(arrRetorno[1] > 0){
				$('#txtEmailAssina').focus();
//				alert('O E-mail já está cadastrado em nosso sistema.');
				switch(arrRetorno[2]){
					case 'inativo':
					case 'demoInativo':
					case 'cancelado':
						alert("Você já possui um cadastro no Contador Amigo com o E-mail informado. Faça o login, informando o email e senha cadastrados anteriormente e reative agora mesmo sua assinatura.");
						abreDiv('entrar');
					break;
					default:
						alert("Você já possui uma conta ativa no Contador Amigo com o E-mail informado. Faça o login normalmente, informando o email e senha cadastrados.");
						abreDiv('entrar');
					break;
				}

				prosseguir = false;
				$('#btProsseguir').val('Prosseguir').attr('disabled',false);
				return false;
			}
		  }
		});
		
		
	}	
	return prosseguir;
}

	function salvarDadosEMKT(){
		var nome = $("#txtAssinante").val();
		var email = $("#txtEmailAssina").val();

		$("#avisoNomePessoal").val(nome);
		$("#avisoEmailPessoal").val(email);

		if( nome != "" && email != '' )
			$("#form_aviso").submit();
		
	}

function formSubmitAssina(){ 

			var erro = false;
			var valor = $("#selRamoAtividade").val();
			if( valor === "Comércio" ){
				erro = true;
				alert("Infelizmente o Contador Amigo ainda não atende empresas do ramo do Comércio e Indústria. Deixaremos seu e-mail guardado e lhe informaremos quando este serviço estiver disponível.");
				salvarDadosEMKT();
				return false;
			}
			if( valor === "Indústria" ){
				erro = true;
				alert("Infelizmente o Contador Amigo ainda não atende empresas do ramo do Comércio e Indústria. Deixaremos seu e-mail guardado e lhe informaremos quando este serviço estiver disponível.");
				salvarDadosEMKT();
				return false;
			}

			if( valor === "" ){
				alert("Selecione o ramo de atividade")
				return;
			}

			


			if( validElement('txtAssinante', msg1) == false){document.getElementById('txtAssinante').focus(); return false;}
			if( validElement('txtEmailAssina', msg1)== false){
					document.getElementById('txtEmailAssina').focus(); return false;
			} 
			if( validElement('txtPrefixoTelefoneCobranca', msg1) == false){document.getElementById('txtPrefixoTelefoneCobranca').focus(); return false;}
			if( validElement('txtTelefoneCobranca', msg1) == false){document.getElementById('txtTelefoneCobranca').focus(); return false;}

			if( validElement('txtSenhaAssina', msg1) == false){document.getElementById('txtSenhaAssina').focus(); return false;}
			if(document.getElementById('txtSenhaAssina').value.length < 8) {
				window.alert('A senha deve ter no mínimo 8 caracteres.');
				return false;
			}
			if( validElement('txtConfirmaSenhaAssina', msg1) == false){document.getElementById('txtConfirmaSenhaAssina').focus(); return false;}
			var Senha = document.getElementById('txtSenhaAssina').value;
			var ConfirmaSenha = document.getElementById('txtConfirmaSenhaAssina').value;

			if(Senha != ConfirmaSenha) {
				window.alert('As senhas não conferem.');
				document.getElementById('txtConfirmaSenhaAssina').select(); 
				return false;
			}

			if(document.getElementById('hidPass').value == "erro") {
				alert('O e-mail já está cadastrado em nosso sistema.');
				return false;
			} 
			if(document.getElementById('cheTermos').checked == false) {
				window.alert('É necessário concordar com termos e condições de serviço.');
				return false;
			}

			var email = document.getElementById('txtEmailAssina');
			if(fnValidaEmail(email) == false){
				document.getElementById('txtEmailAssina').focus();
				return false;
			}

			if( erro === true ){
				salvarDadosEMKT();
				return false;
			}

			if(consultaDadosExistentes()){
				if( erro === false )
					document.getElementById('frmAssinatura').submit();

			}
						
 }
</script>


<div class="principal minHeight">

<?php include 'ballon_contrato.php'; ?>

  
  
  
<!--coluna da esquerda -->  
   
<div style="width:100%; max-width:450px; float:left">  
 <h1>Cadastre-se</h1>
 
 <div style="margin-bottom: 20px; font-size: 16px; color: #a61d00">Acesso integral a todas as funcionalidades do site, <br>
por <strong>30 dias, sem compromisso!</strong></div>
   
<form name="frmAssinatura" id="frmAssinatura" method="post" action="assinatura_gravar.php">
<input type="hidden" name="hidPass" id="hidPass">
<table border="0" cellpadding="0" cellspacing="3" style="margin-bottom:20px;" class="formTabela">
  
  <tr>
    <td align="right" valign="top" class="formTabela">Nome:</td>
    <td valign="top" class="formTabela"><input name="txtAssinante" type="text" id="txtAssinante" style="width:100%; max-width: 300px; margin-bottom:0px" maxlength="200"  alt="Nome"  /></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">E-mail:</td>
    <td valign="top" class="formTabela"><input type="text" name="txtEmailAssina" id="txtEmailAssina" style="widht:100%; max-width:300px" maxlength="200" alt="E-mail" /></td>
    </tr>  
    <tr>
    <td align="right" valign="top" class="formTabela">Telefone:</td>
    <td valign="top" class="formTabela"><input name="txtPrefixoTelefoneCobranca" type="text" id="txtPrefixoTelefoneCobranca" style="width:50%; max-width:30px" maxlength="2" alt="Prefixo do Telefone para Cobrança" /> <input name="txtTelefoneCobranca" type="text" id="txtTelefoneCobranca" style="width:50%; max-width:75px" maxlength="9" alt="Telefone para Cobrança" />
      </td>
    </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Senha:</td>
    <td valign="top" class="formTabela"><input type="password" name="txtSenhaAssina" id="txtSenhaAssina" style="width:50%; max-width:80px" maxlength="10" alt="Senha" />
      <span style="font-size:10px">Entre 8 a  10 caracteres.</span></td>
    </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">Repita:</td>
    <td valign="top" class="formTabela"><input type="password" name="txtConfirmaSenhaAssina" id="txtConfirmaSenhaAssina" style="width:50%; max-width:80px" maxlength="10" alt="Confirmação de Senha" /></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="formTabela">Tipo:</td>
    <td class="formTabela">
      	<select name="selRamoAtividade" id="selRamoAtividade">
      		<option value="" selected="selected">Selecione</option>
	        <option value="Prestação de Serviços">Prestação de Serviços</option>
	        <option value="Comércio" onclick="javascript:alert(msg3)">Comércio</option>
	        <option value="Indústria" onclick="javascript:alert(msg3)">Indústria</option>
	        <option value="sem_empresa">Não tenho empresa</option>
	      </select>
	  </td>
    </tr>

  </table>  
    <label>
        <input type="checkbox" name="cheTermos" id="cheTermos" />
    </label> 
    Li  e concordo com os <a href="javascript:abreDiv('contrato')">termos e condições</a>.
    <br>
    <br>
    <input type="button" value="Entrar" id="btProsseguir" />
</form>

</div>

<script>
	
	$("#txtSenhaAssina").keypress(function(event) {
		var tam = $("#txtSenhaAssina").val().length + 1;
		var string = $("#txtSenhaAssina").val();
		var nova_string = "";
		if(tam > 10){
			alert("Sua senha pode ter no máximo 10 caracteres");
		}
	});
	$("#txtConfirmaSenhaAssina").keypress(function(event) {
		var tam = $("#txtConfirmaSenhaAssina").val().length + 1;
		var string = $("#txtConfirmaSenhaAssina").val();
		var nova_string = "";
		if(tam > 10){
			alert("Sua senha pode ter no máximo 10 caracteres");
		}
	});

</script>

<div style="float:right ; width:100%; max-width:490px; margin-top: 40px">

    <div class="bubble_right_top" style="width:60%; max-width:290px; float:left">
        <div style="padding:15px">
			<span class="tituloPeq">No momento estamos atendendo apenas as empresas:</span>
            <ul>
                <li>Optantes pelo Simples Nacional</li>
                <li>Prestadoras de Serviços</li>
            </ul>
            
            <div id="textoAviseme"<?=(!isset($_SESSION["email_aviso_enviado"]) ? "" : " style=\"margin-bottom:20px;\"")?>>
                Em breve atenderemos também o <strong>comércio</strong>. Quer ser avisado? Envie-nos um <a id="link_aviseme" href="#">e-mail</a>.
            </div>
    
            <div id="formAvise" style="display:none;">
                <form action="aviso_envio.php" method="post" name="form_aviso" id="form_aviso" style="display:inline">
                    <label for="avisoNomePessoal" id="avisoLabelNomePessoal" style="margin-right:12px">Nome: </label>
                    <input name="avisoNomePessoal" maxlength="256" id="avisoNomePessoal" value="" type="text" style="width:100%; margin-bottom:5px"/><br />
                    <label for="avisoEmailPessoal" id="avisoLabelEmailPessoal" style="margin-right:10px"> E-mail: </label>
                    <input name="avisoEmailPessoal" maxlength="100" size="40" id="avisoEmailPessoal" value="" type="text" style="width:100%; margin-bottom:5px"/><br />
                    <input type="hidden" name="AvisoPaginaAtual" id="AvisoPaginaAtual" value="<?=substr($_SERVER['REQUEST_URI'],1)?>" />
                    <input type="button" value="Enviar" name="Submit" onclick="valida_msg_aviso()" />
                </form>
            </div>
            <div id="mensagemEnvio" style="display:<?=(!isset($_SESSION["email_aviso_enviado"]) ? "none" : "block")?>;">
                <div class="tituloVermelhoLight" height="25" style="padding-top:0px; text-align:left; font-size:14px">Solicitação enviada com sucesso!<br /></div>
            </div>
        </div>
    </div>
    

    <div style="float:right; width: 38%">
      <img src="images/boneca-assinatura.png" alt="Faça você mesmo a contabilidade online de sua empresa." style="width:100%; max-width: 193px" title="Faça você mesmo a contabilidade online de sua empresa."/> 
    </div>
</div>


<div style="clear:both"></div>

</div>
<? unset($_SESSION["email_aviso_enviado"]) ?>
<div style="clear: both; height: 10px;"></div>



<?php include 'rodape.php' ?>


