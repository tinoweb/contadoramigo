<?php 
	
	// ini_set('display_errors',1);
	// ini_set('display_startup_erros',1);
	// error_reporting(E_ALL);

	// Relaiza a requizição do arquivo com o topo da página. 
	require_once('header_restrita.php');

	// Arquivo para controlar ações dos dados na página. 
	//require_once('Controller/simples_nacional-controller.php');

	//$simplesNascinal = new SimplesNascinal();
	
?>

<div class="principal">

<h1>Obrigações</h1>
<h2>Simples Nacional</h2>


<!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Acesse o <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/Servicos/Grupo.aspx?grp=5" target="_blank">Portal do Simples Nacional</a> e vá na opção <strong>PGDAS-D e Defis 2018</strong>. Clique em <strong>código de acesso</strong> ou em <strong>certificado digital</strong>, para entrar. Se você não tiver nenhum dos dois, <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/controleAcesso/GeraCodigo.aspx" target="_blank">gere agora mesmo seu código de acesso</a>.<br />
<br />
<br />
<img src="images/simples2018/01.png" width="100%" height="60%" alt=""/> 
</div>


<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Se, no passo anterior, você clicou em certificado digital, cairá na página de entrada do Portal do E-CAC (imagem 1). Clique no ícone <strong>Certificado Digital</strong>. Se clicou em <strong>código de acesso</strong>, aparecerá uma tela para preenchimento dos dados de acesso (imagem 2).<br><br>
<strong>IMAGEM 1</strong><br><br>
<img src="images/simples2018/02.png" width="100%" height="50%" alt=""/><br><br>
<strong>IMAGEM 2</strong><br><br>
<img src="images/simples2018/02b.png" width="100%" height="50%" alt=""/><br><br>
</div>


<!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em <strong>Declaração Mensal</strong> e depois em <strong>Declarar/Retificar</strong>.<br><br>
<img src="images/simples2018/03.png" width="100%" height="50%" alt=""/>
</div>


<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Informe o mês e o ano que irá apurar e clique no botão <strong>Salvar</strong>.<br><br>
<img src="images/simples2018/04.png" width="100%" height="50%" alt=""/>
</div>


<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
No primeiro campo, informe a <strong>receita </strong> do período, exceto exportações  (se não houve receita deve ser informado R$ 0,00). No segundo campo, informe a receita com exportações (igualmente, não havendo receita, deve ser informado R$ 0,00). Em seguida, clique no botão <strong>Salvar</strong>.<br><br>
<img src="images/simples2018/05.png" width="100%" height="35%" alt=""/>
</div>

<!--passo 6-->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Alguns municípios possibilitam a determinadas categorias de empresas optarem pelo recolhimento de valores fixos de ISS (prestadores de serviços) ou ICMS (comércio). Isto ocorre geralmente com sociedades <a href="javascript:abreDiv('uniprof')">uniprofissionais</a>. Esta opção é feita quando a empresa é aberta e renovada anualmente.  Se este for o seu caso, preencha os valores fixos de ISS ou ICMS, definidos por seu município. Caso contrário, deixe em branco e prossiga, clicando no botão <strong>Calcular</strong>. Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.<br><br>
<img src="images/simples2018/09.png" width="100%" height="50%" alt=""/>
</div>

<!--passo 7-->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Verifique se o valor do tributo e clique em <strong>Transmitir</strong>. <br><br>
<img src="images/simples2018/10.png" width="100%" height="45%" alt=""/>
</div>

<!--passo 8-->
<div id="passo8" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Pronto, sua apuração foi enviada! Para imprimir a guia de pagamento, clique no botão <strong>Gerar DAS</strong>.<br><br>
<img src="images/simples2018/11.png" width="100%" height="45%" alt=""/> </div>


<!--passo 9-->
<div id="passo9" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Para imprimir a guia, clique novamente no botão <strong>Gerar DAS</strong>. Se nada acontecer, desative o bloqueador de pop ups (veja como no <a href="https://support.google.com/chrome/answer/95472?hl=pt" target="_blank">Chrome</a>, <a href="https://support.mozilla.org/pt-BR/kb/configuracoes-do-bloqueador-de-popups-excecoes-solucoes-problemas" target="_blank">Firefox</a>, <a href="http://windows.microsoft.com/pt-BR/windows-vista/Internet-Explorer-Pop-up-Blocker-frequently-asked-questions?26470dc0" target="_blank">Internet Explorer</a>) e tente novamente.<br>
<br>
A guia será gerada com a data de vencimento normal. Se precisar pagar em outro dia (acrescido de juros) selecione a opção <strong>Consolidar para Outra Data</strong>. Pronto missão cumprida!<br>
<br>

<img src="images/simples2018/12.png" width="100%" height="60%" alt=""/> </div>

</div>
<?php include 'rodape.php' ?>