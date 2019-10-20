<?php session_start(); $paginaAssinatura = '';?>
<?php $nome_meta = "assinatura_orientacoes2"; ?>

<?php //include 'meta_assinatura_orientacoes.php' ?>
<?php include 'header.php' ?>
<script type="text/javascript">

jQuery(document).ready(function() {		

	// var w = screen.width;
	// var h = screen.height;
	// if( w < 800 )
	// 	location = "assinatura.php";

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
	if(($('#txtCNPJ').val() != '') && ($('#txtEmailAssina').val() != '')){
		$.ajax({
		  url:'assinatura_checa_dados.php',
		  data: 'cnpj=' + $('#txtCNPJ').val() + '&email=' + $('#txtEmailAssina').val(),
		  type: 'get',
		  cache: false,
		  async: false,
		  beforeSend: function(){
			$("#hidPassCNPJ").val('');
			$("#hidPass").val('');
			$('#btProsseguir').val('Checando...').attr('disabled',true);

		  },
		  success: function(retorno){
			$('#hidPassCNPJ').val(retorno.split("|")[0]);
			if(retorno.split("|")[0] > 0){
				$('#txtCNPJ').focus();
				alert('O CNPJ já está cadastrado em nosso sistema.');
				prosseguir = false;
				$('#btProsseguir').val('Prosseguir').attr('disabled',false);

			}
			$('#hidPass').val(retorno.split("|")[1]);
			if(retorno.split("|")[1] > 0){
				$('#txtEmailAssina').focus();
				alert('O E-mail já está cadastrado em nosso sistema.');
				prosseguir = false;
				$('#btProsseguir').val('Prosseguir').attr('disabled',false);
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


<form action="aviso_envio.php" method="post" name="form_aviso" id="form_aviso" style="display:none">
    <label for="avisoNomePessoal" id="avisoLabelNomePessoal" style="margin-right:12px">Nome: </label>
    <input name="avisoNomePessoal" maxlength="256" id="avisoNomePessoal" value="" type="text" style="width:200px; margin-bottom:5px"><br>
    <label for="avisoEmailPessoal" id="avisoLabelEmailPessoal" style="margin-right:10px"> E-mail: </label>
    <input name="avisoEmailPessoal" maxlength="100" size="40" id="avisoEmailPessoal" value="" type="text" style="width:200px; margin-bottom:5px"><br>
    <input type="hidden" name="AvisoPaginaAtual" id="AvisoPaginaAtual" value="assinatura.php">
    <input type="button" value="Enviar" name="Submit" style="margin-left:130px" onclick="valida_msg_aviso()">
</form>

<div class="principal">
<!--CONTRATO -->
<div class="layer_branco" id="contrato" style="width:446px; height:365px; background:#FFF; position:absolute; left:50%; margin-left:-223px; top:150px; display:none; z-index: 999;">
<div style="text-align:right; margin-right:20px; margin-top:15px"><a href="javascript:fechaDiv('contrato')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
<div style="overflow:auto; height:310px; width:406px; margin-left:20px; margin-top:10px;">
<div style="text-align:center; font-weight:bold; margin-bottom:5px">CONTRATO DE PRESTAÇÃO DE SERVIÇOS<br />DE INFORMAÇÃO VIRTUAL</div>
<div>
  <p>As partes, VAD - ESTUDIO  MULTIMÍDIA LTDA., CNPJ/MF: 96.533.310/0001-40, detentora dos direitos de  propriedade do site Contador Amigo, sediada no endereço www.contadoramigo.com.br, doravante denominada  CONTRATADA, e o USUÁRIO ASSINANTE, doravante denominado USUÁRIO, têm entre si  justo e acertado o presente CONTRATO DE PRESTAÇÃO DE SERVIÇOS DE INFORMAÇÃO  VIRTUAL, que se regerá pelas condições e cláusulas seguintes: <br />
    <strong><br />
    1. DOS SERVIÇOS DO CONTRATADA</strong><br />
    1.1. A CONTRATADA disponibilizará  ao USUÁRIO, diuturnamente, informações e serviços virtuais, consistentes em:  calendário de obrigações fiscais; orientações para pagamento de impostos,  obtenção de certidões negativas, alterações contratuais e emissão de notas fiscais;  disponibilização de modelos de procurações; tutorial para uso dos serviços  públicos federais, estadual e municipal; sistema de livro caixa; tira-dúvidas  fiscal; além de outros serviços que vierem a ser contemplados futuramente.<br />
    1.2. A CONTRATADA poderá também  disponibilizar outros serviços no futuro, aos quais o USUÁRIO poderá aderir  livremente, caso assim o deseje, mediante pagamento de valor a ser definido.<br />
    1.3. Os serviços serão prestados  de forma contínua, salvo os casos de interrupções ocasionadas por manutenção no  sistema, falhas operacionais das empresas fornecedoras de energia elétrica e  serviços de telecomunicações, caso fortuito ou força maior, situações essas em  que a CONTRATADA não será responsabilizada, em razão de não haverem sido por  ela ocasionadas.<br />
  <strong><br />
  2. DO OBJETO DO CONTRATO</strong><br />
    2.1. O objeto deste Contrato é a  prestação ao USUÁRIO dos serviços descritos na Cláusula 1ª. O USUÁRIO terá  direito ao uso irrestrito de tais serviços, desde que preencha o cadastro  específico e efetue prévio pagamento mensal, especificado no sistema.<br />
  <strong><br />
  3. RETRIBUIÇÃO PELOS SERVIÇOS</strong><br />
    3.1.&nbsp;O pagamento das  mensalidades deverá ser efetuado pelo USUÁRIO, conforme opção feita por ele no  ato da assinatura. São disponibilizadas ao USUÁRIO as seguintes modalidades  de pagamento: (i) boleto bancário; (ii) cartão de crédito ou (iii) débito  automático em conta corrente. Conforme a opção de forma de pagamento escolhida  pelo USUÁRIO, este declara expressamente, desde já, concordar com que a CONTRATADA  efetue débitos mensais em seu cartão de crédito ou conta corrente.<br />
    3.2. Se  houver atraso no pagamento das mensalidades superior a 5 (cinco) dias corridos,  o acesso do USUÁRIO aos serviços da CONTRATADA será automaticamente bloqueado.<br />
    3.3. Havendo atraso no pagamento  das mensalidades, o USUÁRIO só poderá ter acesso novamente aos serviços se  quitar integralmente todas as parcelas devidas, sem acréscimo de juros. Se o  atraso for superior a 3 (três) meses, o novo acesso do USUÁRIO poderá ser feito  mediante o pagamento de apenas 3 (três) mensalidades. <br />
  <strong><br />
  4. PRAZO E RESCISÃO</strong><br />
    4.1. O prazo do presente Contrato  é indeterminado, podendo tanto a CONTRATADA como o USUÁRIO rescindi-lo a  qualquer tempo, sem ensejar direito a qualquer indenização ou pagamento de uma  parte à outra. As partes deverão avisar uma à outra, com antecedência de até 15  (quinze) dias, sua intenção de rescindir o Contrato.<br />
    4.2. Caso a CONTRATADA decida  rescindir o presente Contrato no período compreendido entre o pagamento de uma  mensalidade pelo USUÁRIO e a prestação de serviços respectiva, a CONTRATADA  deverá manter os serviços à disposição do USUÁRIO até que se complete o período  do pagamento já efetuado.<br />
    4.3. A CONTRATADA poderá  rescindir o presente Contrato de pleno direito, por justa causa, se o USUÁRIO  violar qualquer de suas cláusulas ou condições, especialmente as obrigações  previstas na Cláusula 5ª., abaixo, devendo o USUÁRIO ressarcir os danos e  prejuízos causados à CONTRATADA e/ou a terceiros. <br />
    4.4. No momento da assinatura  deste Contrato, a relação entre as partes reflete um equilíbrio econômico e  financeiro contratual, que deverá perdurar durante todo o período em que se  desenvolver esta relação. Se, contudo, durante a execução deste Contrato fatos  externos&nbsp;contribuírem para que este equilíbrio seja rompido, concordam as  partes desde já que o Contrato poderá ser resolvido, sem que qualquer das  partes seja obrigada a indenizar a outra.<br />
  <strong><br />
  5. OBRIGAÇÕES DO USUÁRIO</strong><br />
    5.1. O USUÁRIO obriga-se a respeitar  a propriedade e os direitos intelectuais, autorais e industriais da CONTRATADA,  sob pena de exclusão imediata do portal, sem direito a qualquer indenização e sem  prejuízo das responsabilidades civis e penais aplicáveis ( especialmente, art.  184, do Código Penal). <br />
    5.2. Obriga-se o USUÁRIO, também,  sob as penas da lei, e sem prejuízo do direito da CONTRATADA de rescindir de  imediato o presente Contrato, por justa causa, a não utilizar os serviços contratados  para fins ilegais, imorais ou não autorizados pelo presente instrumento, tais  como: <br />
    a) Enviar arquivos que contenham  vírus, arquivos defeituosos ou qualquer outro programa de computador similar  que possa prejudicar o funcionamento dos servidores do Contador Amigo&nbsp;ou  equipamento de terceiros; <br />
    b) Tentar violar sistema de  segurança de informações de terceiros ou tentar obter acesso não autorizado a  redes de computador conectadas à internet; <br />
    c) Usar qualquer dispositivo,  programa de computador, ou outro meio que possa interferir nas atividades e operações  da CONTRATADA; <br />
    d) Fazer download de qualquer  arquivo que configure pirataria de programa de computador; <br />
    e) Criar, reproduzir em livros,  apostilas, sites de internet, mídias, blogs, sites de relacionamento etc.,  copiar ou distribuir qualquer parte do Contador Amigo, sem estar autorizado  expressamente pela CONTRATADA. <br />
    5.3. Cada USUÁRIO poderá ter  somente uma conta de cadastro personalizada com seu nome. Essa conta pessoal  poderá ser utilizada em um ou mais computadores (ex.: casa, escritório etc.), mas  não poderá ser usada em mais de um computador ao mesmo tempo. <br />
    5.4. O USUÁRIO é obrigado a  manter sigilo sobre a sua senha de acesso aos serviços, permanecendo  responsável pelo uso de sua conta pessoal, ficando ciente, neste ato, da  existência de sistema de bloqueio de senha de duplo acesso.<br />
    5.5. O USUÁRIO compromete-se a  notificar imediatamente a CONTRATADA sobre qualquer uso não autorizado de sua  conta pessoal. <br />
    5.6. O USUÁRIO garante que todas  as informações fornecidas à CONTRATADA são verdadeiras, completas e não contêm  erros. O USUÁRIO obriga-se a manter suas informações cadastrais constantemente  atualizadas junto a CONTRATADA. <br />
    5.7. O USUÁRIO deverá possuir o  equipamento e os programas de computador necessários para acessar os serviços. <br />
    5.8. Ao utilizar os serviços, o  USUÁRIO se compromete a não violar a legislação e regulamentação vigentes, bem  como a não ceder, transferir ou comercializar os serviços, sob qualquer forma  ou pretexto. <br />
    5.9. O USUÁRIO concorda em manter  a CONTRATADA, seus diretores e empregados indenes de quaisquer danos, perdas,  despesas, reclamações e/ou reivindicações de quaisquer terceiros com relação a  e/ou em decorrência da utilização indevida dos serviços por parte do USUÁRIO. <br />
  <strong><br />
  6. LIMITAÇÃO DE RESPONSABILIDADE</strong><br />
  <strong>6.1. O USUÁRIO declara ter entendido perfeitamente que a CONTRATADA não  presta, em hipótese alguma, serviços de contabilidade. O conteúdo constante do  portal Contador Amigo deve ser encarado como mera referência informativa e, para  o cumprimento de suas obrigações fiscais, o USUÁRIO deverá confirmar as  informações contidas no portal com um profissional de contabilidade, ou  consultar diretamente a legislação vigente. </strong><br />
    6.2. Tendo em vista as constantes  modificações na legislação fiscal e as inúmeras particularidades de cada atividade  profissional, o USUÁRIO expressamente exonera a CONTRATADA de qualquer  responsabilidade por dano de qualquer natureza ou prejuízo causado por  eventuais imprecisões, falta de atualização ou erros nas informações e serviços  a ele disponibilizados.<br />
    6.3. A CONTRATADA não terá qualquer  responsabilidade por dano de qualquer natureza, prejuízo ou perda no  equipamento do USUÁRIO causado pela utilização dos serviços, bem como não será  responsável por qualquer vírus que possa vir a infectar o equipamento do  USUÁRIO como conseqüência do uso dos serviços. <br />
    6.4. O USUÁRIO será o único  responsável pela qualidade dos equipamentos para utilização dos serviços  disponibilizados pelo Contador Amigo. <br />
    6.5. Ao utilizar-se de  &quot;links&quot; encontrados no Contador Amigo para outros endereços na  internet, o USUÁRIO declara fazê-lo por sua própria conta, ficando a CONTRATADA  isenta de qualquer responsabilidade direta ou indireta pela disponibilidade de  tais endereços ou pelos respectivos conteúdos, propaganda, produtos, serviços ou  outros materiais contidos ou disponibilizados através dos sites encontrados em  tais endereços, bem como por quaisquer custos, danos ou perdas que efetivamente  ou alegadamente venham a ser causados pelos referidos conteúdos, produtos,  serviços disponíveis em referidos sites, e pela utilização ou confiança  depositada pelo USUÁRIO em tais conteúdos, produtos ou serviços. <br />
  <strong><br />
  7. POLÍTICA DE PRIVACIDADE DO USUÁRIO</strong><br />
    7.1. As informações prestadas  pelo USUÁRIO serão utilizadas exclusivamente pela CONTRATADA e pelo Suporte,  com fins meramente administrativos, relativos à navegabilidade, não podendo a CONTRATADA,  nem o Suporte, vender ou divulgar para terceiros informações acerca do USUÁRIO,  salvo sob autorização expressa deste ou por ordem judicial. <br />
    7.2. Não obstante o disposto no  item anterior, a CONTRATADA poderá informar, de forma publicitária, dados  gerais e estatísticos acerca de seus USUÁRIOS, não podendo, todavia, descer a  identificações individuais. <br />
    7.3. O USUÁRIO concorda que o Contador  Amigo envie ao seu correio eletrônico notícias de conteúdo contábil e de  publicidade. <br />
    7.4. Partindo do pressuposto de  que sempre há uma margem de risco quanto a clonagem de dados armazenados na  internet ou outros procedimentos ilícitos que possam vir a capturar dados e  senhas do USUÁRIO, sem qualquer possibilidade de controle por parte da  CONTRATADA, esta compromete-se a adotar todos os meios tecnológicos disponíveis  a fim de evitar tais ocorrências, porém, não pode garantir total segurança aos  referidos dados, devendo o USUÁRIO ter todo o cuidado ao elaborar seu login e  senha. <br />
    7.5. A CONTRATADA não se  responsabiliza pelas relações estabelecidas entre seus parceiros e o USUÁRIO. <strong></strong><br />
  <strong><br />
  8. DISPOSIÇÕES GERAIS</strong><br />
    8.1. Este Contrato poderá ser  alterado unilateralmente pela CONTRATADA, em função de evolução tecnológica ou  da ocorrência de outro fator estranho à relação contratual, que venha a influir  no aprimoramento desta e/ou na melhoria dos serviços prestados. Essas  modificações poderão resultar inclusive em alterações de preços para se alcançar  um novo equilíbrio contratual. O USUÁRIO que não concordar com a alteração terá  o prazo de 30 (trinta) dias para manifestar sua discordância e rescindir este  Contrato, sem que para tanto seja obrigado a pagar qualquer valor a título de  indenização. Não sendo formalizada a discordância no prazo previsto, o Contrato  será considerado automaticamente aditado com as novas regras, e terá validade  para todos os fins de direito. Contudo, a qualquer tempo o USUÁRIO terá o  direito de discordar e rescindir o Contrato, não gerando indenização para  qualquer das partes. <br />
    8.2. Este Contrato será  interpretado de acordo com as leis da República Federativa do Brasil. As partes  elegem o Foro da Comarca de São Paulo/SP como competente para solucionar  quaisquer disputas que possam decorrer deste Contrato, com exclusão de qualquer  outro, por mais privilegiado que seja. <br />
    8.3. As partes concordam com os  termos deste instrumento e se comprometem a cumpri-lo até o seu termo com base  no princípio da<br />
boa fé. </p>
</div>
</div>
</div>
<!--FIM DO CONTRATO -->

<!--BALLOM OPTANTE -->
<div style="width:200px; position:absolute; margin-left:750px; margin-top:130px; display:none" id="optante">
<div style="width:8px; position:absolute; margin-left:175px; margin-top:15px"><a href="javascript:fechaDiv('optante')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
 <div style="width:214px; height:127px;  background-image:url(images/balloon_assinatura.png)">
   <div style="margin-left:40px; width:140px; margin-top:25px; font-size:12px; position:absolute;">
     Não sabe se sua empresa é optante pelo Simples? Entre <a href="http://www8.receita.fazenda.gov.br/SimplesNacional/aplicacoes/ATBHE/Consultaoptantes.app/Consultaropcao.aspx" target="_blank">neste site</a>, digite seu cnpj e confira.<br> 
    </div></div>
</div>
<!--FIM DO BALLOOM OPTANTE -->

<div></div>
<div class="minHeight">
  <div style="clear:both; height:80px"></div>
  
<div style="float:left; margin-top:40px; width: 20%">
<img src="images/boneca4.png" alt=""/> 
</div>
					
<div class="bubble_left" style="width:75%; max-width:280px; margin-left:14px; float:left;"> 
<div style="padding:20px"> 
<div class="saudacao">Registre-se para Continuar</div>
<div>Tudo bem, você pode ver toda a área restrita do portal por 30 dias gratuitamente, basta se cadastrar. Não é preciso informar dados de pagamento e o acesso é imediato.<br>
  <br>
  Se você já é cliente, faça o login.
</div>
</div> 
</div> 
  
   
   <div style="float:left; width:458px">
<form name="frmAssinatura" id="frmAssinatura" method="post" action="assinatura_gravar.php">
    <input type="hidden" name="hidPass" id="hidPass">
<table border="0" cellpadding="0" cellspacing="3" style="background:none" class="formTabela">
  <tr>
    <td align="right" valign="top" class="formTabela">Nome:</td>
    <td valign="top" class="formTabela"><input name="txtAssinante" type="text" id="txtAssinante" style="width:300px; margin-bottom:0px" maxlength="200"  alt="Nome"  /></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="formTabela">E-mail:</td>
    <td valign="top" class="formTabela"><input type="text" name="txtEmailAssina" id="txtEmailAssina" style="width:300px" maxlength="200" alt="E-mail" /></td>
    </tr>  
    <tr>
    <td align="right" valign="top" class="formTabela">Telefone:</td>
    <td valign="top" class="formTabela"><div style="float:left; margin-right:3px; margin-top:3px; font-size:12px">(</div>
      <div style="float:left">
        <input name="txtPrefixoTelefoneCobranca" type="text" id="txtPrefixoTelefoneCobranca" style="width:30px" maxlength="2" alt="Prefixo do Telefone para Cobrança" />
        </div>
      <div style="float:left; margin-left:3px; margin-right:3px; margin-top:3px; font-size:12px">)</div>
      <div style="float:left">
        <input name="txtTelefoneCobranca" type="text" id="txtTelefoneCobranca" style="width:75px" maxlength="9" alt="Telefone para Cobrança" />
      </div></td>
    </tr>
  <tr>
  	
  	<td align="right" valign="top" class="formTabela">Defina uma Senha:</td>
    <td valign="top" class="formTabela">
    	<input type="password" name="txtSenhaAssina" id="txtSenhaAssina" style="width:90px" maxlength="10" alt="Senha" />
      <span style="font-size:10px">Entre 8 a  10 caracteres.</span>
    </td>

    <!-- <td colspan="2" align="left" valign="top" class="formTabela">Defina uma Senha:
      <input type="password" name="txtSenhaAssina" id="txtSenhaAssina" style="width:90px" maxlength="10" alt="Senha" />
      <span style="font-size:10px">Entre 8 a  10 caracteres.</span></td> -->
    </tr>
  <tr>

  		<td align="right" valign="top" class="formTabela">Confirme a Senha:</td>
	    <td valign="top" class="formTabela">
	    	<input type="password" name="txtConfirmaSenhaAssina" id="txtConfirmaSenhaAssina" style="width:90px" maxlength="10" alt="Confirmação de Senha" />
	      <span style="font-size:10px">Entre 8 a  10 caracteres.</span>
	    </td>
    </tr>
     <tr>
    <td align="right" valign="middle" class="formTabela">Ramo de Atividade:</td>
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
  <tr>
    <td colspan="2" valign="bottom" class="formTabela" height="20px">
      
  <label><input type="checkbox" name="cheTermos" id="cheTermos" /></label> 
  Li  e concordo com os <a href="javascript:abreDiv('contrato')">termos e condições</a> do serviço.</td>
    </tr>

  <tr>
    <td colspan="2" align="center" class="formTabela" height="30" valign="bottom"><input type="button" value="Prosseguir" id="btProsseguir" /></td>
  </tr>
</table>
</form>
</div>
  <div style="clear:both">
     
</div>

<div style="clear:both"></div>
</div>
<? unset($_SESSION["email_aviso_enviado"]) ?>


<?php include 'rodape.php' ?>

<?php 
if(isset($_GET['erro']) || isset($_GET['erro'])) { ?>
<script>
javascript:abreDiv('entrar');
</script>
<?php ; } ?>
