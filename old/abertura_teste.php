<!doctype html>
<html>

<head>
<meta name="viewport" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="globalsign-domain-verification" content="5z-sY9RW0W_520nZ-_B_zVQqNcEbkeFOr2n9WqD2R0" />
<title>Contador Amigo: abertura de empresa</title>

<META NAME="description" CONTENT="Abra sua empresa de graça. Siga nosso tutorial e faça sozinho a abertura de sua empresa.">
<META NAME="keywords" CONTENT="abertura de empresa, abrir empresa, abrir microempresa, registrar empresa, registrar ME">
<META NAME="expires" CONTENT="never">
<META NAME="language" CONTENT="pt-br">
<META NAME="revisit-after" content="07 days">
<META name="author" content="VAD - Estúdio Multimídia">
<META NAME="copyright" CONTENT="VAD - Estúdio Multimídia">

<meta property="og:locale" content="pt_BR" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Contador Amigo: abertura de empresa" />
<meta property="og:description" content="Abra sua empresa de graça. Siga nosso tutorial e faça sozinho a abertura de sua empresa." />
<meta property="og:url" content="https://www.contadoramigo.com.br" />
<meta property="og:site_name" content="Contador Amigo" />
<meta property="og:image" content="https://www.contadoramigo.com.br/images/share_facebook.jpg" />
<meta property="fb:app_id" content="156991511034067" />

<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@contadoramigo" />
<meta name="twitter:title" content="Contador Amigo: abertura de empresa" />
<meta name="twitter:description" content="Abra sua empresa de graça. Siga nosso tutorial e faça sozinho a abertura de sua empresa." />
<meta name="twitter:domain" content="Contador Amigo"/>
<meta name="twitter:image:src" content="https://www.contadoramigo.com.br/images/share_facebook.jpg"/>

<link rel="canonical" href="https://www.contadoramigo.com.br/abertura_empresa.php" />

<link href="estilo.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="screen" href="ballon.css?v"><!--estilo ballon CSS -->
<link rel="icon" href="favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
<link rel="apple-touch-icon" href="logo_ipad.png" />
<link rel="stylesheet" media="screen" href="estilo/font-awesome.min.css?v"><!--estilo font-awesome-->
<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="scripts/meusScripts.js"></script>
<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript" src="scripts/jquery.maskedinput.js"></script>
<script type="text/javascript" src="scripts/jquery.flash.js"></script>

<script>

	$(document).ready(function(e) {
		$('.campoData').mask('99/99/9999');
		$('.campoDataMesAno').mask('99/9999');
		$('.campoDDDTelefone').mask('99');
		$('.campoTelefone').mask('999999999');
		$('.campoCNPJ').mask('99.999.999/9999-99');
		$('.campoCPF').mask('999.999.999-99');
		$('.campoCEP').mask('99999-999');
		$('.campoNIRE').mask('9999999999-9');
		$('.campoCNAE').mask('9999-9/99');
		
		
        $('#emailPessoal').blur(function(){
			if($(this).val()!=''){
				if(!$.validateEmail($(this).val())){
					$(this).focus();
					window.alert('E-mail inválido!');
					return false;
				}
			}
		});
		
		
		(function($, window) {
	
	
		// FUNÇÃO QUE CRIA O BOTÃO DA CAIXA DE VISUALIZAÇÃO
		  $.fn.botaoXVisualizacao = function(valueBotao) {
			var obj = $(this);
	
			var div_botao = $('<div></div>');
			div_botao.css({
				'position':'absolute'
				, 'top':'10px'
				, 'right':'10px'
				, 'display':'block'
			});
					
			var elementoButton = $('<img src="images/x.png" />');
			elementoButton.css({
				'width':'8px'
				, 'height':'9px'
				, 'border':'0px'
				, 'cursor':'pointer'
			});
			
			div_botao.append(elementoButton);
	
			obj.first('div').prepend(div_botao);
	
			elementoButton.bind('click',function(){
	//			alert($(this).val());
	
				if(obj.find('video').length){
					var video_html5 = obj.find('video');
					video_html5.load();
				}
				
				if($('.check_caixa_visualizacao').length){
					
					var nome_pagina = location.href.substr(location.href.lastIndexOf("/") + 1,location.href.length);
					
					var checado = $('.check_caixa_visualizacao').attr('checked');
					
					//if(checado){
						$.ajax({
						  url:'marca_alerta_paginas.php'
						  , data: 'id_login=&nome_pagina=' + nome_pagina + '&status='+checado
						  , type: 'post'
						  , async: true
						  ,	cache: false
						  , success: function(retorno){
						  }
						});
					//}
				}
	
				//obj.css('display',(obj.css('display') == 'block' ? 'none' : 'block'));
				obj.toggle();
				
			});
		  }
		  
		  
		// FUNÇÃO QUE CRIA O BOTÃO DA CAIXA DE VISUALIZAÇÃO
		  $.fn.botaoVisualizacao = function(valueBotao) {
			var obj = $(this);
	
			var div_botao = $('<div></div>');
			div_botao.css({
				'margin' : '10px auto 0 auto'
				, 'position':'relative'
				, 'text-align':'center'
				, 'clear' : 'both'
				//, 'border':'1px solid #0F0'
			});
					
			var elementoButton = $('<input type="button" />');
			elementoButton.css({
			});
			elementoButton.attr("name","bt_caixa_visualizacao");
			elementoButton.attr("id","bt_caixa_visualizacao");
			elementoButton.attr("value",valueBotao);
			
			div_botao.append(elementoButton);
	
			obj.last('div').append(div_botao);
	
			elementoButton.bind('click',function(){
	//			alert($(this).val());

				if(obj.find('video').length){
					var video_html5 = obj.find('video');
					video_html5.load();
				}
				
				if($('.check_caixa_visualizacao').length){
					
					var nome_pagina = location.href.substr(location.href.lastIndexOf("/") + 1,location.href.length);
					
					var checado = $('.check_caixa_visualizacao').attr('checked');
										
					$.ajax({
						url:'marca_alerta_paginas.php'
						, data: 'id_login=&nome_pagina=' + nome_pagina + '&status='+checado
						, type: 'post'
						, async: true
						, cache: false
						, success: function(retorno){
						}
					});	
					

				}
	
				//obj.css('display',(obj.css('display') == 'block' ? 'none' : 'block'));
				obj.toggle();
				
			});
		  }
		
		  
		// FUNÇÃO QUE CRIA O CHECKBOX DA CAIXA DE VISUALIZAÇÃO
		  $.fn.checkCaixaVisualizacao = function(checado) {
	
			var obj = $(this);
	
			var div_checkbox = $('<div></div>');
			div_checkbox.css({
				'position':'relative'
				, 'clear' : 'both'
				, 'margin-top':'10px'
				, 'width': 'auto'
				, 'display':'table'
				//, 'border':'1px solid #F00'
			});
			var elementoCheck = $('<input>');
			elementoCheck.css({
				'position':'relative'
				, 'margin': '0 5px 0 0'
				, 'float': 'left'
			});
			elementoCheck.attr("type","checkbox");
			elementoCheck.attr("checked",checado);
			elementoCheck.attr("name","check_caixa_visualizacao");
			elementoCheck.attr("class","check_caixa_visualizacao");
			elementoCheck.attr("value","1");
	
			var elementoLabel = $('<label>NÃO EXIBIR NOVAMENTE</label>');
			elementoLabel.css({
				'position':'relative'
				, 'float': 'left'
				, 'padding': '-5px 0 0 0'
				, 'font-size' : '80%'
			});
	
	//alert(obj.first('div').css('margin-bottom'));
	
			div_checkbox.append(elementoCheck).append(elementoLabel);//.append(elementoButton);
			obj.first('div').children().append(div_checkbox);
	
		  };
	
		})($, window);
	
		$('.box_visualizacao').each(function(index, element) {
		});

		$('.check_visualizacao').each(function(index, element) {
		   $(this).checkCaixaVisualizacao(''); // acrescenta o checkbox na caixa de visualização
		});

		$('.botao_visualizacao').each(function(index, element) {
		   $(this).botaoVisualizacao('Fechar'); // acrescenta o botão na caixa de visualização 
		});

		$('.x_visualizacao').each(function(index, element) {
		   $(this).botaoXVisualizacao(); // acrescenta o botão na caixa de visualização 
		});
		
		
    });
			
	$(function(){
		// checa se o email foi escrito corretamente
		$.validateEmail = function(email)
		{
			er = /^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2}/;
			if(er.exec(email))
				return true;
			else
				return false;
		};
	});
	
	

// checa se há campos vazios na mensagem de contato
function valida_msg_contato() {
	
	if (document.getElementById('NomePessoal').value=="") {alert('Preencha o campo Nome'); document.getElementById('NomePessoal').focus(); return false}
	if (document.getElementById('EmpresaProf').value=="") {alert('Preencha o campo Empresa'); document.getElementById('EmpresaProf').focus(); return false}
	if (document.getElementById('emailPessoal').value=="") {alert('Preencha o campo E-mail'); document.getElementById('emailPessoal').focus(); return false}
	if (document.getElementById('DDDPessoal').value=="") {alert('Preencha o campo DDD'); document.getElementById('DDDPessoal').focus(); return false}
	if (document.getElementById('telPessoal').value=="") {alert('Preencha o campo Telefone'); document.getElementById('telPessoal').focus(); return false}
	if (document.getElementById('Mensagem').value=="") {alert('Escreva sua mensagem'); document.getElementById('Mensagem').focus(); return false}
	
	else {document.getElementById('form').submit()}
}


// checa se há campos vazios no formulário de seja avisado
function valida_msg_aviso() {
	
	if (document.getElementById('avisoNomePessoal').value==""){
		alert('Preencha o campo Nome');
		document.getElementById('avisoNomePessoal').focus();
		return false;
	}
	if (document.getElementById('avisoEmailPessoal').value==""){
		alert('Preencha o campo E-mail');
		document.getElementById('avisoEmailPessoal').focus();
		return false;
	} else {
		if(!$.validateEmail(document.getElementById('avisoEmailPessoal').value)){
			document.getElementById('avisoEmailPessoal').focus();
			window.alert('E-mail inválido!');
			return false;
		}
	}

	document.getElementById('form_aviso').submit();
}


	
var msg1 = 'É necessário preencher o campo';
var msg2 = 'É necessário selecionar ';

	function validElement(idElement, msg){
		var Element=document.getElementById(idElement);

		if(Element.value == "" || Element.value == false && Element.value != 0 ){
			window.alert(msg+' '+$(Element).attr('alt')+'.');
			Element.focus();
			return false;
		}
	}


function enterLogin(){  

	if( validElement('txtEmail', msg1) == false){return false}
	if( validElement('txtSenha', msg1) == false){return false}
	
	if(document.getElementById('cheConectado').checked){
		document.cookie = 'contadoramigoHTTPS=' + document.getElementById('txtEmail').value + ' ' + MD5(document.getElementById('txtSenha').value) + '; expires=Wed, 27 Jan 2021 20:47:11 UTC; path=/'
	}
	document.getElementById('login').submit();
}

function enterLoginExtra(){  

	if( validElement('txtEmail2', msg1) == false){return false}
	if( validElement('txtSenha2', msg1) == false){return false}
	
	if(document.getElementById('cheConectado2').checked){
		document.cookie = 'contadoramigoHTTPS=' + document.getElementById('txtEmail2').value + ' ' + MD5(document.getElementById('txtSenha2').value) + '; expires=Wed, 27 Jan 2021 20:47:11 UTC; path=/'
	}
	document.getElementById('login2').submit();
}
</script>

<!--google analitics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28088679-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!--fim google analitics -->
</head>
<body>
<div class="principal" style="position: relative; margin-bottom:20px;">

<div style="float:left; width:550px; height:73px">
<a href="https://www.contadoramigo.com.br/index.php"><img src="images/logo.png" alt="Contador Amigo" title="Contador Amigo" width="396" height="68" border="0" style="margin-bottom:5px; float:left"></a><img src="images/selo.png" width="53" height="54" style="margin-top:10px" alt="4º ano"title="4º ano"/>
</div>

<div style="float:right; margin-top:40px; text-align:right; font-size:24px">
<a href="https://www.youtube.com/c/ContadoramigoBrasil" alt="Canal Contador Amigo no Youtube" title="Canal Contador Amigo no Youtube" target="_blank"><i class="fa fa-youtube-square"></i></a>
<a href="https://www.facebook.com/contadoramigoBrasil" alt="Página do Contador Amigo no Facebook" title="Página do Contador Amigo no Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>
<a href="https://plus.google.com/+ContadoramigoBrasil/posts" alt="Página do Contador Amigo no Google Plus" title="Página do Contador Amigo no Google Plus" target="_blank"><i class="fa fa-google-plus-square"></i></a>
<a href="https://www.linkedin.com/company/contador-amigo" alt="Página do Contador Amigo no Linked In" title="Página do Contador Amigo no Linked In" target="_blank"><i class="fa fa-linkedin-square"></i></a>
<a href="https://twitter.com/contadoramigo" alt="Página do Contador Amigo no Twitter" title="Página do Contador Amigo no Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>
<a href="https://contadoramigo.blogspot.com.br/" alt="Blog do Contador Amigo" title="Blog do Contador Amigo" target="_blank"><i class="fa fa-wordpress"></i></a>
</div> 

<div style="clear:both"></div>

<div class="menu">
<div style="float:left; width:700px">
<a class="linkMenu" href="index.php">Início</a> |
<a class="linkMenu" href="quem_somos.php">Quem Somos</a> |
<a class="linkMenu" href="saiba_mais.php">Saiba Mais</a> |
<a class="linkMenu" href="abertura_empresa.php">Abra sua Empresa</a> |
<a class="linkMenu" href="assinatura.php">Assinatura</a> |
<a class="linkMenu" href="duvidas_frequentes.php">Dúvidas Frequentes</a> |
<a class="linkMenu" href="midia.php">Nós na mídia</a> |
<a class="linkMenu" href="javascript:abreDiv('contato')">Contato</a>
</div>

<div style="float:right; text-align:right">
<a href="javascript:abreDiv('entrar')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Imagem1','','images/login2.png',1)" class="botao_login">
	LOGIN
</a>
</div>

<div style="clear:both"> </div>
</div>

<!--BALLOM CONTATO -->
<div style="width:365px; position:absolute; margin-top:15px; margin-left:450px; display:none; z-index:10" id="contato" class="bubble_top box_visualizacao x_visualizacao">

<div style="padding:20px;">

<div class="titulo" style="margin-bottom:10px">Contato</div>
  <form action="contato_envio.php" method="post" name="form" id="form" style="display:inline">
  	<div style="width: 100%;">
    	<div style="float: left;width: 20%;">
		    <label for="NomePessoal" id="labelNomePessoal" style="float: left; width: 100%;">Nome: </label>
        </div>
    	<div style="float: left;width: 80%;">
		    <input name="NomePessoal" maxlength="256" id="NomePessoal" value="" type="text" style="padding: 2px; margin: 0; width:100%; margin-bottom:5px"/>
        </div>
    </div>
    <div style="clear: both;"></div>

  	<div style="width: 100%;">
    	<div style="float: left;width: 20%;">
		    <label for="EmpresaProf" id="labelEmpresaProf" style="float: left; width: 60px;">Empresa:</label>
        </div>
    	<div style="float: left;width: 80%;">
		    <input name="EmpresaProf" maxlength="30" id="EmpresaProf" value="" type="text" style="padding: 2px; margin: 0; width:100%; margin-bottom:5px"/>
        </div>
    </div>
    <div style="clear: both;"></div>
    
  	<div style="width: 100%;">
    	<div style="float: left;width: 20%;">
		    <label for="emailPessoal" id="labelEmailPessoal" style="float: left; width: 60px;"> E-mail: </label>
        </div>
    	<div style="float: left;width: 80%;">
		    <input name="emailPessoal" maxlength="100" id="emailPessoal" value="" type="text" style="padding: 2px; margin: 0; width:100%; margin-bottom:5px"/>
        </div>
    </div>
    <div style="clear: both;"></div>
    
  	<div style="width: 100%;">
    	<div style="float: left;width: 20%;">
		    <label for="DDDPessoal" id="labelTelPessoal" style="float: left; width: 60px;"> Telefone: </label>
        </div>
    	<div style="float: left;width: 80%;">
		    <input name="DDDPessoal" maxlength="4" id="DDDPessoal" value="" type="text" size="1"/>
		    <input name="telPessoal" maxlength="20" id="telPessoal" value="" type="text" style="width:80px; margin-right:25px"/>    
        </div>
    </div>
    <div style="clear: both;height: 30px;"></div>
    <div>Mensagem: </div>
    <textarea name="Mensagem" rows="10" id="Mensagem" style="width:325px; margin-bottom:10px"></textarea>
    <input type="hidden" name="paginaAtual" id="paginaAtual" value="abertura_empresa.php" />
    <input type="button" value="Enviar" name="Submit" style="margin-left:130px" onClick="valida_msg_contato()" />
  </form>
</div>

</div>
<!-- FIM DO BALLON CONTATO -->



<!--BALLOOM LOGIN -->

<div id="entrar" class="bubble_top_right box_visualizacao x_visualizacao" style="top:108px; right:0px; position:absolute; display:none; z-index:10;">

	<div style="padding:20px;">

		<div class="tituloVermelho" style="margin-bottom:10px">Login</div>
		<form name="login" id="login" action="https://www.contadoramigo.com.br/auto_login.php?login" method="post" onKeyPress="if (event.keyCode == 13) enterLogin()" style="display:inline"/>
		<div style="margin-bottom:3px">
        	<span style="color:#336699; margin-right:5px">Email: </span>
            <input type="text" name="txtEmail" value="" maxlength="60"  style="width:140px; height:20px; border-style:solid; border-width:1px; border-color:#cccccc" id="txtEmail" alt="Email" />
        </div> 
		<div style="margin-bottom:11px">
        	<span style="color:#336699">Senha: </span>
            <input type="password" name="txtSenha" value="" maxlength="60" id="txtSenha" style="width:65px; margin-right:5px; height:20px;  border-style:solid; border-width:1px; border-color:#cccccc" alt="Senha" /> <a class="linkCinza" href="envio_senha.php" style="font-size:11px">Não lembro</a>
        </div>
		<div style="margin-bottom:8px">
        	<input name="cheConectado" type="checkbox" id="cheConectado" value="" checked="checked"/> Manter-me conectado
		</div>
		<div style="text-align:center">
        	<input type="button" value="Entrar" onClick="enterLogin()" name="button" />
		</div>
				</form>
	</div>

</div>
<!--FIM DO BALLON -->



</div>

<div class="principal">
<div class="minHeight" style="width:780px">
<div class="titulo" style="margin-bottom:20px;">Abertura de empresa</div>

<div class="tituloVermelho">Considerações inciais</div>
Inicialmente vale ressaltar que este tutorial é voltado  para o registro de empresas <b>prestadoras de serviços sediadas no Estado de São Paulo</b>. Se você pretende abrir um comércio ou uma indústria, este tutorial não o guiará na obtenção da inscrição estadual. <b><br>
 <br>
 Empresas de outros estados</b> também podem utilizar este tutorial, mas não terão um passo-a-passo para registro na Junta em seu Estado (Estamos ainda trabalhando nesse sentido). <b><br>
 <br>
 Escritórios de Advocacia</b> devem observar que a OAB não permite a abertura de sociedades empresariais na Junta Comercial. Assim, não será possível  seguir esta parte do tutorial. A nova empresa deverá ser registrada em cartório, como Sociedade Simples.
    <br>
    <br>
    Bem, isto posto, vamos lá! Antes de iniciar o processo de abertura propriamente dita, é preciso definir algumas questões: <br>
    <br>
    
    <div class="tituloAzul">Tipo de empresa</div>
   
    Se você pretende ser o único dono da empresa, deve escolher o tipo <b>Empresário Individual</b>. Se terá sócios, deve abrir uma <b>Sociedade Empresária Limitada</b>. Alguém pode lhe sugerir abrir uma empresa tipo <b>EIRELI</b> - Empresa Individual de Responsabilidade Limitada. A deferença para o empresário individual é que os bens da pessoa física ficam separados dos bens da empresa. Se você falir, vai ser mais difícil irem atrás dos seus bens pessoais. Em compensação, para abrir uma EIRELI o capital social mínimo precisa ser de 100 salários mínimos. Nossa recomendação é que vc deixe a opção de EIRELI de lado, pois no final os credores avançariam em seus bens pessoais de qualquer forma.<br>
    <br>
    <div class="tituloAzul">Sociedades Uniprofissionais</div>

    Se você for um profissional liberal (médico, advogado, engenheiro, dentista, etc) e estiver montando uma empresa sozinho, ou com outros colegas da mesma profissão, você pode se enquadrar como Sociedade Uniprofissional - SUP. A vantagem é que, na grande maioria dos municípios, o ISS é menor. Ao invés de  um percentual sobre o  faturamento  (normalmente 2%), a empresa pagaria   uma taxa fixa mensal, que é bem menor. Para ter certeza de que sua empresa pode ser enquadrada como Sociedade Profissional, acesse este <a href="https://dsup.prefeitura.sp.gov.br/SimularDeclaracao/SelecaoCodigoServico" target="_blank">questionário</a> preparado pela Prefeitura de São Paulo. <span class="minHeight" style="width:780px">Ele vale oficialmente apenas para a</span> Capital, pois cada município tem sua legislação, mas a normas são  muito parecidades e São Paulo é das mais exigentes. Assim, se você puder ser enquadrado como Sociedade Profisisonal em São Paulo, é quase certo que poderá enquadrar-se em seu município.<br>
    <br>
<div class="tituloAzul">Nome (Razão Social)</div>

O nome da empresa, também conhecido como Razão Social, pode ser escolhido livremente em se tratando de sociedade Empresária, devnedo termianr com a palavra Ltda. Já o empresário Individual precisa usar seu próprio nome na empresa, podendo,  acrescentar palavras que informem o tipo de atividade desenvolvida, por exemplo <b>José da Silva  Representações</b>. Mas no registro é possível definir também o <b>Nome Fantasia</b>, para usar em seus imprtessos e cartazes. Este você pode escolher livrmente.<br>
<br> 
No caso das Sociedades
para não correr o risco de registrar um nome de empresa já existente, você deve fazer uma <a href="https://gru.inpi.gov.br/pePI/jsp/marcas/Pesquisa_classe_basica.jsp" target="_blank">pesquisa no INPI</a> .<br>
<br>
<div class="tituloAzul">Capital Social</div>
O abrir a sua empresa, você deverá registrar um capital social. Defina um valor correspondente ao investimento inicial para a abertura da empresa (Some os valores dos móveis e equipamentos e o dinheiro que colocará na conta da empresa ao abri-la. Lembre que no caso de EIRELI o capital social mínimo é de 100 salários mínimos.<br>
<br>
<div class="tituloAzul">Regime de Tributação</div> 
No Brasil é possível escolher entre três regimes de tributação: 
<b>Lucro Real</b>, <b>Lucro Presumido</b> ou <b>Simples Nacional</b>. O mais conveniente para pequenas empresas recém-abertas é o Simples Nacional. Nele as alíquotas dos impostos são quase sempre mais baixas e há menos burocracia. Para optar pelo Simples Nacional, você precisará encaminhar o pedido de enquadramento pela Internet em até 30 dias a partir do registro da empresa no CNPJ.<br>
<br>
<div class="tituloAzul">Atividades</div>
Durante o processo de abertura da empresa, você precisará informar <b>o código (CNAE)</b> das atividades a serem desenvolvidas. Faça uma pesquisa no site do <a href="http://www.cnae.ibge.gov.br/" target="_blank">IBGE/Concla</a> para defini-los. Pode ser apenas uma ou mais atividades. Nesse caso uma delas deverá ser a principal. Evite usar muitos códigos, para não arcar com obrigações desnecessárias. Você poderá acrescentar novos  no futuro. <br>
<br>
Como 
as alíquotas  variam de acordo com a atividade, procure encaixar sua empresa códigos que pagam menos impostos. Para isso, acesse nossa <a href="abertura_selecao_atividades.php">página seleção de atividades</a>.<br>
<br>
      <div class="tituloVermelho">Processo de Abertura</div>
    Definidas estas questões, você está pronto para iniciar a abertura de sua empresa. O primeiro passo é dar entrada com o processo na Junta Comercial. Como você já deve saber, cada estado tem uma<span class="minHeight" style="width:780px"> com  normas e regulamentos próprios, mas quase todas já dispõem de mecanismos online para geração dos  formulários, documentos e guias de impostos necessários à abertura de uma empresa. </span><br>
      <br>
<span class="minHeight" style="width:780px"><span class="minHeight" style="width:780px">Empresas do Estado de São Paulo fazem os processos de abertura e alteração no site <b>Via Rápida Empresa</b>. <span class="minHeight" style="width:780px">O preenchimento pode ser um tanto tortuoso e o site não é são muito amigável.</span> Pensando nisso, preparamos para você um <a href="abertura_junta_sp_geral.php">tutorial do Via Rápida Empresa</a>, que o guiará até o final do processo. Se você não é de São Paulo, procure no site da Junta Comercial de seu Estado o local onde efetuar o preenchimento</span>.</span> <br>
<br>
Você deverá  anexar ainda a toda essa documentação o <strong>DBE - Documento Básico de Entrada</strong>, também gerado pela Internet. Acesse o <a href="abertura_dbe.php">tutorial para geração do DBE</a> (vale para empresas de todos os estados). Você notará que ele contém informações semelhantes ao requerimento da Junta. O primeiro será usado para cadastrar seus dados no sistema da Receita Federal e gerar seu CNPJ e o outro para cadastrá-lo junto ao Estado.<br>
<br>
Se você estiver abrindo uma sociedade, precisará elaborar também o <b>Contrato Social</b> da empresa. Baixe este <a href="http://www.jucespciesp.com.br/modelo/Modelo%20Contrato%20Social.doc" target="_blank">modelo básico de Contrato Social</a>. Basta alterá-lo com os dados dos sócios. Se quiser pode acrescentar cláusulas específicas, que atentam suas necessidades.<br />
  <br />
  Leve toda essa papelada até a Junta Comercial. A documentação será analisada e, se estiver  em ordem, a alteração será homologada. Se houver alguma irregularidade, o processo retorna, para que você o complemente com a exigência solicitada. <br />
  <br />
  Depois de homologado,
  sua empresa já estará aberta perante o Estado e a Receita Federal. Ficará ainda faltando efetuar o cadastro junto à Prefeitura (inscrição municipal). A maioria das cidades já possibilitam o preenchimento online. Verifique se esta facilidade  já está disponível na sua cidade. <span class="minHeight" style="width:780px">Se você é de São Paulo Capital, acesse o <a href="abertura_ccm.php" target="_blank">tutorial de Inscrição no CCM</a></span>.<br />
  <br />
  Depois disso sua empresa estará aberta, mas antes de comemorar <a href="https://www.contadoramigo.com.br/enquadramento_simples.php">solicite sua adesão ao Simples Nacional</a>, para que você já comece a gozar dos benefícios fiscais. <b>Empresas recém-abertas têm até 30 dias para fazê-lo, </b>a contar do último deferimento de inscrição (municipal ou estadual). Se não o fizer, você será automaticamente enquadrado no Regime de Lucro Presumido e só poderá solicitar seu enquadramento no Simples em janeiro do ano seguinte.<br>
  <br>
  Uma vez deferido seu enquadramento no Simples, pode abrir a champanhe. Sua nova empresa estará pronta para começar!<br>
  <br>
<div class="tituloVermelho">Cadastro na Nota Fiscal Eletrônica</div>

Muito bem. Agora, que sua empresa está aberta, é hora de configurar o seu perfil no site da Nota Fiscal Paulistana, para que você já possa emitir as notas fiscais. Usando sua <b>Senha Web</b>, ou o <b>Certificado Digital E-CNPJ</b> (caso já o tenha adquirido), acesse o <a href="http://nfpaulistana.prefeitura.sp.gov.br/index.asp" target="_blank">site da Nota Fiscal Paulistana.</a> No menu lateral, vá em Configuração de Perfil. Complete os dados cadastrais. <b>Em regime de tributação, coloque Simples Nacional</b> e grave. Pronto! Missão cumprida. Para imprimir suas notas fiscais, entre em <b>Emissão de NFS-e</b>.<br>
<br>
</div>
</div>
﻿<div class="rodape no-print" style="width:966px">
<div style="padding-top:3px">&copy; Contador Amigo - Contabilidade Online para microempresas optantes pelo Simples Nacional | Av. das Nações Unidas, 8501 - 17º andar - São Paulo - SP - 05425-070 - tel: 11 3434-6631</div>
</div>
</div>
</body>
</html>