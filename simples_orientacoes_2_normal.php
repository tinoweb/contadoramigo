<?php 
	
	// ini_set('display_errors',1);
	// ini_set('display_startup_erros',1);
	// error_reporting(E_ALL);

	// Relaiza a requizição do arquivo com o topo da página. 
	require_once('header_restrita.php');

	// Arquivo para controlar ações dos dados na página. 
	require_once('Controller/simples_nacional-controller.php');

	$simplesNascinal = new SimplesNascinal();
	
?>

<div class="principal">

<h1>Obrigações</h1>
<h2>Simples Nacional</h2>


<!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Acesse o <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/Servicos/Grupo.aspx?grp=5" target="_blank">Portal do Simples Nacional</a> e vá na opção <strong>PGDAS-D e Defis 2018</strong>. Clique em <strong>código de acesso</strong> ou em <strong>certificado digital</strong>, para entrar. Se você não tiver nenhum dos dois, <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/controleAcesso/GeraCodigo.aspx" target="_blank">gere agora mesmo seu código de acesso</a>.<br />
<br />
<br />
<img src="images/simples2018/01.png" width="966" height="589" alt=""/> 
</div>


<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Se, no passo anterior, você clicou em certificado digital, cairá na página de entrada do Portal do E-CAC (imagem 1). Clique no ícone <strong>Certificado Digital</strong>. Se clicou em <strong>código de acesso</strong>, aparecerá uma tela para preenchimento dos dados de acesso (imagem 2).<br><br>
<strong>IMAGEM 1</strong><br><br>
<img src="images/simples2018/02.png" width="966" height="468" alt=""/><br><br>
<strong>IMAGEM 2</strong><br><br>
<img src="images/simples2018/02b.png" width="966" height="548" alt=""/><br><br>
</div>


<!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em <strong>Declaração Mensal</strong> e depois em <strong>Declarar/Retificar</strong>.<br><br>
<img src="images/simples2018/03.png" width="966" height="523" alt=""/>
</div>


<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Informe o mês e o ano que irá apurar.<br><br>
<img src="images/simples2018/04.png" width="966" height="521" alt=""/>
</div>


<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
No primeiro campo, informe a <strong>receita </strong> do período, exceto exportações  (isto é, a soma das notas fiscais emitidas no período, excetuando-se aquelas emitidas para empresas no exterior). No segundo campo, informe a receita com exportações (a soma das notas emitidas para empresas no exterior, se houver).<br><br>
<img src="images/simples2018/05.png" width="966" height="361" alt=""/>
</div>


<!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Nesta tela você informará detalhes importantes sobre sua apuração que irão definir o valor de sua alíquota. Muita atenção no preenchimento. Baseado em seus dados cadastrais e nas respostas fornecidas anteriormente, o Contador Amigo identificou o(s) item(s) que você deve selecionar. São eles:

<?php echo $simplesNascinal->PegaEstruturaAtividadesEconomicas();?>

<img src="images/simples2018/06.png" width="966" height="969" alt=""/>
</div>

<!--passo 7 -->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br>
<br>
<?php echo $simplesNascinal->PegaEstruturaReceitaDoQuadro();?>

<img src="images/simples2018/07.png" width="966" height="495" alt=""/>
</div>

<!--passo 8 -->
<div id="passo8" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Preencha o valor bruto da sua folha de salários, isto é as retiradas de pró-labore, pagamentos a funcionários e autônomos, para cada um dos meses solicitados, incluindo encargos e o 13º salário. <strong>Se esta tela não aparecer, pule para o próximo passo</strong>.<br><br>
ATENÇÃO: Como encargos da folha entende-se a CPP - Contribuição Previdênciária Patronal e o FGTS (para as empresas com funcionários). Não entra o INSS, pois este não é um encargo da empresa, mas sim do sócio ou do funcionário. A CPP normalmente vem embutida na DAS, para saber o valor pago, clique em "consultar declarações" no menu lateral e abra as declarações do Simples relativas aos meses solicitados. <!--Para empresas tributadas pelo Anexo IV (Atividades ligadas à construção civil, paisagismo, decoração de interiores, advocacia e serviços de manutenção e limpeza) a CPP não é embutida DAS, mas sim na GPS. Neste caso para saber o valor você deverá olhar o relatório analítico da GPS, gerado junto com a Gfip.--> <br><br>
<div class="destaqueAzul">Por que preciso informar a folha de salários?</div>
Algumas atividades pagarão uma alíquota de 15,5% (ou mais) ao invés de 6% se a folha de salários dos últimos 12 meses for inferior a 28% do faturamento bruto no mesmo período. <br>
<br>


<img src="images/simples2018/08.png" width="966" height="521" alt=""/>
</div>


<!--passo 9 -->
<div id="passo9" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Alguns municípios possibilitam a determinadas categorias de empresas optarem pelo recolhimento de valores fixos de ISS (prestadores de serviços) ou ICMS (comércio). Isto ocorre geralmente com sociedades <a href="javascript:abreDiv('uniprof')">uniprofissionais</a>. Esta opção é feita quando a empresa é aberta e renovada anualmente.  Se este for o seu caso, preencha os valores fixos de ISS ou ICMS, definidos por seu município. Caso contrário, deixe em branco e prossiga. Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.<br><br>
<img src="images/simples2018/09.png" width="966" height="519" alt=""/>
</div>

<!--passo 10 -->
<div id="passo10" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo10')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Verifique se o valor do tributo e clique em <strong>Transmitir</strong>. <br><br>
<img src="images/simples2018/10.png" width="966" height="440" alt=""/>
</div>

<!--passo 11 -->
<div id="passo11" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo11')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 11 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo12','passo11')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Pronto, sua apuração foi enviada! Para imprimir a guia de pagamento, clique no botão <strong>Gerar DAS</strong>.<br><br>
<img src="images/simples2018/11.png" width="966" height="450" alt=""/> </div>


<!--passo 12-->
<div id="passo12" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo11','passo12')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 12 de 12</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo12')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Para imprimir a guia, clique novamente no botão <strong>Gerar DAS</strong>. Se nada acontecer, desative o bloqueador de pop ups (veja como no <a href="https://support.google.com/chrome/answer/95472?hl=pt" target="_blank">Chrome</a>, <a href="https://support.mozilla.org/pt-BR/kb/configuracoes-do-bloqueador-de-popups-excecoes-solucoes-problemas" target="_blank">Firefox</a>, <a href="http://windows.microsoft.com/pt-BR/windows-vista/Internet-Explorer-Pop-up-Blocker-frequently-asked-questions?26470dc0" target="_blank">Internet Explorer</a>) e tente novamente.<br>
<br>
A guia será gerada com a data de vencimento normal. Se precisar pagar em outro dia (acrescido de juros) selecione a opção <strong>Consolidar para Outra Data</strong>. Pronto missão cumprida!<br>
<br>

<img src="images/simples2018/12.png" width="966" height="585" alt=""/> </div>

</div>
<?php include 'rodape.php' ?>