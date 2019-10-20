<!doctype html>
<?php
	//$dominio_seguro = "https://www.contadoramigo.com.br/";
	$URI = explode($_SERVER['SCRIPT_URL'], $_SERVER['SCRIPT_URI']);
	
	$dominio_seguro = $URI[0].'/';
	$dominio = $dominio_seguro;
	
	// CONEXAO
	include 'conect.php';

    die($dominio);
	
	// MENSALIDADE
	$sql_configuracoes = "SELECT * FROM configuracoes WHERE configuracao = 'mensalidade'";
	$rsConfiguracoes = mysql_fetch_array(mysql_query($sql_configuracoes));
	$mensalidade = $rsConfiguracoes['valor'];
	
	if(isset($_COOKIE["contadoramigoHTTPS"]) && ($_COOKIE["contadoramigoHTTPS"] != '' && !is_null($_COOKIE["contadoramigoHTTPS"]))){
	
		header ('Location:'.$dominio.'auto_login.php?login&cookie');
		exit;
	}
	session_start();
?>

<html>
    <head>
        <meta id="view_port" name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include 'meta_'.$nome_meta.'.php'; ?>
        <link rel="manifest" href="/manifest.json"><!--NOTIFICACOES-->
        <link rel="stylesheet" href="estilo.css?v>"  type="text/css" />
        <link rel="stylesheet" media="screen" href="ballon.css?v"><!--estilo ballon CSS -->
        
        <!--icons-->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
       <!--fim dos icons-->
        
        <link rel="stylesheet" media="screen" href="estilo/font-awesome.min.css?v>">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans|Varela+Round" rel="stylesheet">
        
        <script type="text/javascript" src="scripts/meusScripts.js"></script>
        <script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
        <script type="text/javascript" src="scripts/jquery.min.js"></script>
        <script type="text/javascript" src="scripts/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="scripts/jquery.flash.js"></script>
		
 
   <!--SCRIPT NOTIFICAÇÕES--> 
  <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async='async'></script>
  <script>
    var OneSignal = window.OneSignal || [];
    OneSignal.push(["init", {
      appId: "05627450-971c-401f-b149-5de1e1e0118b",
      autoRegister: true, 
      notifyButton: {enable: false},	
	  welcomeNotification: {"title": "Contador Amigo","message": "Obrigado pela sua inscrição!"},
	  
	  /*promptOptions: {
      actionMessage: "Gostaríamos de enviar notificações sobre seus impostos e obrigações",
      acceptButtonText: "ÓTIMO!",
      cancelButtonText: "AGORA NÃO"
    				},*/
	
		
	}]);
	  
/*	  OneSignal.push(function() {
  OneSignal.showHttpPrompt();
});*/
	  
  </script>
   <!--FIM DO SCRIPT NOTIFICACOES--> 
             
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
				$('.campoDataAno').mask('9999');
		//		$('.campoDDDTelefone').mask('99');
		//		$('.campoTelefone').mask('999999999');				
				$('.campoCNAE').mask('9999-9/99');
				$('.campoCNAE2').mask('9999-9/99');
				$('.campoNIT').mask('999.99999.99-9');
				$('.campoCBO').mask('9999-99');
				$('.campoCEI').mask('99.999.99999/99');
				$('.campoRECIBO').mask('99.99.99.99.99-99');
                
                
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
            // if (document.getElementById('EmpresaProf').value=="") {alert('Preencha o campo Empresa'); document.getElementById('EmpresaProf').focus(); return false}
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
			
        function enterLoginMobile(){  
        
            if( validElement('txtEmailMobile', msg1) == false){return false}
            if( validElement('txtSenhaMobile', msg1) == false){return false}
            
            if(document.getElementById('cheConectado').checked){
                document.cookie = 'contadoramigoHTTPS=' + document.getElementById('txtEmailMobile').value + ' ' + MD5(document.getElementById('txtSenhaMobile').value) + '; expires=Wed, 27 Jan 2021 20:47:11 UTC; path=/'
            }
            document.getElementById('loginMobile').submit();
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

<!--********************************************MENU DESKTOP*******************************************************-->

<div class="principal" id="menu_desktop" style="display: block">

<div style="float:left; width:396px; height:73px">
<a href="<?=$dominio?>index.php"><img src="images/logo.png" alt="Contador Amigo" title="Contador Amigo" width="396" height="68" border="0" style="margin-bottom:5px; float:left"></a></div><div style="float:left; margin-top:45px; font-family:'Varela Round', sans-serif; width:100px; color:#024a68; font-size:11px">Desde 2011</div>
<div style="float:left; margin-left:140px; margin-top:20px"><a href="http://www.desenvolvesp.com.br/" target="_blank" border="0"><img src="images/desenvolve_sp.png" alt="Apoio: Desenvolve SP" width="80" title="Desenvolve SP"/></a></div>

<div style="float:right; margin-top:40px; text-align:right; font-size:24px">
<a href="https://www.youtube.com/ContadoramigoBrasil?sub_confirmation=1" alt="Canal Contador Amigo no Youtube" title="Canal Contador Amigo no Youtube" target="_blank"><i class="fa fa-youtube-square" style="color:#003d62"></i></a>
<a href="https://www.facebook.com/contadoramigoBrasil" alt="Página do Contador Amigo no Facebook" title="Página do Contador Amigo no Facebook" target="_blank"><i class="fa fa-facebook-square" style="color:#003d62"></i></a>
<a href="https://plus.google.com/+ContadoramigoBrasil/posts" alt="Página do Contador Amigo no Google Plus" title="Página do Contador Amigo no Google Plus" target="_blank"><i class="fa fa-google-plus-square" style="color:#003d62"></i></a>
<a href="https://www.linkedin.com/company/contador-amigo" alt="Página do Contador Amigo no Linked In" title="Página do Contador Amigo no Linked In" target="_blank"><i class="fa fa-linkedin-square" style="color:#003d62"></i></a>
<a href="https://twitter.com/contadoramigo" alt="Página do Contador Amigo no Twitter" title="Página do Contador Amigo no Twitter" target="_blank"><i class="fa fa-twitter-square" style="color:#003d62"></i></a>
<a href="https://contadoramigo.blogspot.com.br/" alt="Blog do Contador Amigo" title="Blog do Contador Amigo" target="_blank"><i class="fa fa-wordpress" style="color:#003d62"></i></a>
</div> 

<div style="clear:both; border-bottom-color:#113b63; border-bottom-style:solid; 
	border-bottom-width:1px;; height:10px; width:100%"></div>

<div id="menu">
<ul >
	<li><a class="linkMenu" href="contabilidade-online.php">Contabilidade Online</a></li>
	<li><a class="linkMenu" href="abrir-me.php">Abrir ME</a></li>
	<li><a class="linkMenu" href="alterar-empresa.php">Alterar Empresa</a></li>
	<li><a class="linkMenu" href="desenq_mei.php">Desenquadrar MEI</a></li>
	
	<li><a class="linkMenu" href="situacao_fiscal.php">Dívidas e Regularização</a></li>
	<li><a class="linkMenu" href="servico-contador.php">Serviços Avulsos</a></li>
	<li><a class="linkMenu" href="assinatura.php">Assinatura</a></li>
	<li><a class="linkMenu" href="fale-conosco.php">Contato</a></li>
	</ul>
</div>

<div style="float:right; text-align:right; padding-top: 3px">
	<a class="menu-login" href="javascript:abreDiv('entrar')">LOGIN</a>
</div>

<div style="clear:both; height: 20px"> </div>
</div>

<!--quando a pessoa digita a senha errada, o ballon abre automaticmaente e o ballom varia se for mobile ou desktop-->

<?php if(isset($_GET['erro']) || isset($_GET['erro'])) { ?>
<script>
	$(function() {
		
		var w = screen.width;
        var h = screen.height;

		if(w < 800){
			abreDiv('entrarMobile');
		} else {
			abreDiv('entrar');
		}
	});
</script>
<?php ; } ?>

</div>
<!--****************************************************FIM DO MENU DESKTOP******************************************-->

<!--******************************************************MENU MOBILE************************************************-->

<div id="menu_mobile" class="principal" style="display: none">

<div style="margin-bottom:20px"><a href="index.php"><img src="images/logo.png" alt="Contador Amigo" title="Contador Amigo" border="0" style="width: 70%; max-width:396px"></a></div>

<div class="menu-login" style="padding:3px 4px 3px 4px; float: left; margin-right: 5px">
<a href="javascript:abreDiv('menu-internas')" alt="Menu" title="Menu"><i class="fa fa-bars" style="color:#fff; font-size:1.4em; line-height: 1em "></i></a>
</div>

<div style="float:left"><a class="menu-login"  href="javascript:abreDiv('entrarMobile')" style="font-size:1em; float:right">LOGIN</a></div>
	




<div style="font-size:2.3em; line-height:1.0em;padding-right:10px; float: right">
<a href="https://www.youtube.com/c/ContadoramigoBrasil" alt="Canal Contador Amigo no Youtube" title="Canal Contador Amigo no Youtube" target="_blank"><i class="fa fa-youtube-square"></i></a>
<a href="https://www.facebook.com/contadoramigoBrasil" alt="Página do Contador Amigo no Facebook" title="Página do Contador Amigo no Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>
<a href="https://plus.google.com/+ContadoramigoBrasil/posts" alt="Página do Contador Amigo no Google Plus" title="Página do Contador Amigo no Google Plus" target="_blank"><i class="fa fa-google-plus-square"></i></a>
<a href="https://www.linkedin.com/company/contador-amigo" alt="Página do Contador Amigo no Linked In" title="Página do Contador Amigo no Linked In" target="_blank"><i class="fa fa-linkedin-square"></i></a>
<a href="https://twitter.com/contadoramigo" alt="Página do Contador Amigo no Twitter" title="Página do Contador Amigo no Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>
<a href="http://contadoramigo.blogspot.com.br/" alt="Blog do Contador Amigo" title="Blog do Contador Amigo" target="_blank"><i class="fa fa-wordpress"></i></a>
</div> 

<div style="clear:both; height:20px"></div>

<div id="menu-internas" style="margin-bottom:20px; display:none; font-size: 120%">

<a class="menu" href="contabilidade-online.php">Contabilidade Online</a>
<a class="menu" href="abrir-me.php">Abrir ME</a>
<a class="menu" href="alterar-empresa.php">Alterar Empresa</a>
<a class="menu" href="desenq_mei.php">Desnquadrar MEI</a>
<a class="menu" href="situacao_fiscal.php">Dívidas e Regularização</a>
<a class="menu" href="servico-contador.php">Serviços Avulsos</a>
<a class="menu" href="assinatura.php">Assinatura</a>
<a class="menu" href="fale-conosco.php">Fale Conosco</a>
</div>

</div>

<!--***********************************************FIM DO MENU MOBILE**************************************-->

<!--BALLOOM LOGIN  DO DESKTOP-->

<div id="entrar" class="bubble_top_right box_visualizacao x_visualizacao" style="top:128px; left:50%; margin-left: 280px; position:absolute; display:none; z-index:10;">

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
				if($_GET['erro'] == 'erro1') { 
					//echo "<div height=\"25\" style=\"color:#C00\"><br>Desculpe, o Contador Amigo está<br> temporariamente indisponível.</div>";
					echo "<div height=\"25\" style=\"color:#C00\"><br>E-mail ou senha não conferem.</div>";
				} 
				if($_GET['erro'] == 'erro2') { echo "<div height=\"25\" style=\"color:#C00\">Conta Inativa</div>"; } 
			}
		?>
		</form>
	</div>

</div>
<!--FIM DO BALLON LOGIN DO DESKTOP-->
	
	

<!--BALLOOM LOGIN MOBILE-->
	<div id="entrarMobile" class="bubble_top box_visualizacao x_visualizacao" style="top:115px; left:5px; position:absolute; display:none; z-index:10;">

		<div style="padding:20px;">

			<div class="tituloVermelho" style="margin-bottom:10px">Login</div>
			<form name="login" id="loginMobile" action="<?=$dominio?>auto_login.php?login" method="post" onKeyPress="if (event.keyCode == 13) enterLoginMobile()" style="display:inline"/>
				<div style="margin-bottom:3px">
		        	<span style="color:#336699; margin-right:5px">Email: </span>
		            <input type="text" name="txtEmail" value="" maxlength="60"  style="width:140px; height:20px; border-style:solid; border-width:1px; border-color:#cccccc" id="txtEmailMobile" alt="Email" />
		        </div>
				<div style="margin-bottom:11px">
		        	<span style="color:#336699">Senha: </span>
		            <input type="password" name="txtSenha" value="" maxlength="60" id="txtSenhaMobile" style="width:65px; margin-right:5px; height:20px;  border-style:solid; border-width:1px; border-color:#cccccc" alt="Senha" /> <a class="linkCinza" href="envio_senha.php" style="font-size:11px">Não lembro</a>
		        </div>
				<div style="margin-bottom:8px">
		        	<input name="cheConectado" type="checkbox" id="cheConectado" value="" checked="checked"/> Manter-me conectado
				</div>
				<div style="text-align:center">
		        	<input type="button" value="Entrar" onClick="enterLoginMobile()" name="button" />
				</div>
				<?php 
					if(isset($_GET['erro'])){
						if($_GET['erro'] == 'erro1') { echo "<div height=\"25\" style=\"color:#C00\">Usuário ou senha inválidos</div>"; } 
						if($_GET['erro'] == 'erro2') { echo "<div height=\"25\" style=\"color:#C00\">Conta Inativa</div>"; } 
					}
				?>
			</form>
		</div>

	

	<style type="text/css" media="screen">
		.bubble_top:after{
			left: 37px !important;
		}
		.bubble_top:before{
			left:37px !important;
		}
	</style>
	
</div>
<!--FIM DO BALLON LOGIN MOBILE-->	