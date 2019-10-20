<div id="menu_mobile">
<div style="margin-bottom:20px;margin-top:-15px;"><a href="index.php"><img src="images/logo.png" alt="Contador Amigo" width="85%" title="Contador Amigo" border="0"></a></div>


	<!--BALLOOM LOGIN -->
	<div id="entrar2" class="bubble_top box_visualizacao x_visualizacao" style="top:115px; left:5px; position:absolute; display:none; z-index:10;">

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

	

	<style type="text/css" media="screen">
		.bubble_top:after{
			left: 37px !important;
		}
		.bubble_top:before{
			left:37px !important;
		}
	</style>
	<!--FIM DO BALLON -->
</div>
<div style="float:left; margin-right:5px">

<div class="menu-login" style="padding:3px 4px 3px 4px">
	<a href="javascript:abreDiv('menu')" alt="Início/Menu" title="Início/Menu"><i class="fa fa-bars" style="color:#fff; font-size:1em; "></i></a>
</div>

</div>

<div style="float:left"><a class="menu-login" href="javascript:abreDiv('entrar2')">LOGIN</a></div>

<div style="text-align:right; float:right; width:65%; font-size:1.7em; line-height:1.0em;padding-right:10px">
<a href="https://www.youtube.com/c/ContadoramigoBrasil" alt="Canal Contador Amigo no Youtube" title="Canal Contador Amigo no Youtube" target="_blank"><i class="fa fa-youtube-square"></i></a>
<a href="https://www.facebook.com/contadoramigoBrasil" alt="Página do Contador Amigo no Facebook" title="Página do Contador Amigo no Facebook" target="_blank"><i class="fa fa-facebook-square"></i></a>
<a href="https://plus.google.com/+ContadoramigoBrasil/posts" alt="Página do Contador Amigo no Google Plus" title="Página do Contador Amigo no Google Plus" target="_blank"><i class="fa fa-google-plus-square"></i></a>
<a href="https://www.linkedin.com/company/contador-amigo" alt="Página do Contador Amigo no Linked In" title="Página do Contador Amigo no Linked In" target="_blank"><i class="fa fa-linkedin-square"></i></a>
<a href="https://twitter.com/contadoramigo" alt="Página do Contador Amigo no Twitter" title="Página do Contador Amigo no Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a>
<a href="http://contadoramigo.blogspot.com.br/" alt="Blog do Contador Amigo" title="Blog do Contador Amigo" target="_blank"><i class="fa fa-wordpress"></i></a>
</div> 

<div style="clear:both; height:20px"></div>

<div id="menu" style="margin-bottom:20px; display:none">
<a class="menu" href="index.php">INíCIO</a>
<a class="menu" href="quem_somos.php">QUEM SOMOS</a>
<a class="menu" href="saiba_mais.php">SAIBA MAIS</a>
<a class="menu" href="assinatura.php">ACESSE GRÀTIS</a>
<a class="menu" href="duvidas_frequentes.php">DÚVIDAS FREQUENTES</a>
<a class="menu" a href="contato.php">CONTATO</a>
<a class="menu" href="atendimento_mei.php">CENTRAL DO MEI</a>
</div>
</div>
<!-- </div> -->