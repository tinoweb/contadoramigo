<?php
//ini_set('session.cookie_secure',true);
//$dominio = "http://www.contadoramigo.com.br/";
//$dominio_seguro = "https://contadoramigo.websiteseguro.com/";
$dominio_seguro = "https://www.contadoramigo.com.br/";
$dominio = $dominio_seguro;

// CONEXAO
include 'conect.php';

// MENSALIDADE
$sql_configuracoes = "SELECT * FROM configuracoes WHERE configuracao = 'mensalidade'";
$rsConfiguracoes = mysql_fetch_array(mysql_query($sql_configuracoes));
$mensalidade = $rsConfiguracoes['valor'];



//var_dump($_COOKIE["contadoramigoHTTPS"]);
//exit;

//	setcookie("contadoramigo", "", -3600, "/", "contadoramigo.com.br", 0);
if(isset($_GET['erro'])){
	//unset($_COOKIE["contadoramigo"]);
	//setcookie("contadoramigo","",time()-3600);
}

if(isset($_COOKIE["contadoramigoHTTPS"]) && ($_COOKIE["contadoramigoHTTPS"] != '' && !is_null($_COOKIE["contadoramigoHTTPS"]))){
//	$dadosLogin = $_COOKIE["contadoramigoHTTPS"];
//	print_r($_COOKIE);
//	exit;
//	$dadosSeparados = explode(" ", $dadosLogin);
//	$emailx = $dadosSeparados[0];
//	$senhax = $dadosSeparados[1];
	header ('Location:'.$dominio.'auto_login.php?login&cookie');
	exit;
}
session_start();?>
<!doctype html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'meta_'.$nome_meta.'.php'; ?>

<link href="estilo_mobile.css?v" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="screen" href="ballon.css?v"><!--estilo ballon CSS -->
<link rel="icon" href="favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
<link rel="apple-touch-icon" href="logo_ipad.png" />
<link rel="stylesheet" media="screen" href="estilo/font-awesome.min.css?v"><!--estilo font-awesome-->
<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,600italic' rel='stylesheet' type='text/css'>

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
						  , data: 'id_login=<?=$_SESSION["id_userSecao"]?>&nome_pagina=' + nome_pagina + '&status='+checado
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
						, data: 'id_login=<?=$_SESSION["id_userSecao"]?>&nome_pagina=' + nome_pagina + '&status='+checado
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
		   $(this).checkCaixaVisualizacao('<?=$checado?>'); // acrescenta o checkbox na caixa de visualização
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

<!--BALLOOM LOGIN -->

<div id="entrar" class="bubble_top box_visualizacao x_visualizacao" style="top:115px; left:5px; position:absolute; display:none; z-index:10;">

	<div style="padding:20px;">

		<div class="tituloVermelho" style="margin-bottom:10px">Login</div>
		<form name="login" id="login" action="<?=$dominio?>auto_login.php?login" method="post" onKeyPress="if (event.keyCode == 13) enterLogin()" style="display:inline"/>
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
		<?php 
			if(isset($_GET['erro'])){
				if($_GET['erro'] == 'erro1') { echo "<div height=\"25\" style=\"color:#C00\">Usuário ou senha inválidos</div>"; } 
				if($_GET['erro'] == 'erro2') { echo "<div height=\"25\" style=\"color:#C00\">Conta Inativa</div>"; } 
			}
		?>
		</form>
	</div>

</div>
<!--FIM DO BALLON -->

<div style="margin-bottom:20px"><a href="index_mobile.php"><img src="images/logo.png" alt="Contador Amigo" width="85%" title="Contador Amigo" border="0"></a></div>

