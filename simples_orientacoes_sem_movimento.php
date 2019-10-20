<?php include 'header_restrita.php' ?>
<script>
function listar() {
	var linkFinal = '';
	var total = 0;
	for (i=1;i<=document.getElementById('hidTotalLinhas').value;i++) {
		if(document.getElementById('atividade' + i).checked) {
			if(document.getElementById('cheRetencao' + i + 'sim').checked && document.getElementById('cheRetencao' + i + 'nao').checked) {
				var selRetencao = 'ambos';
			} else if (document.getElementById('cheRetencao' + i + 'sim').checked) {
				var selRetencao = 'sim';
			} else if (document.getElementById('cheRetencao' + i + 'nao').checked) {
				var selRetencao = 'nao';
			} else {
				window.alert('Selecione se algum cliente fez retenção dos impostos antes de pagar sua nota.');
				return false;
			}
			linkFinal = linkFinal + '&atividade' + i + '=' + selRetencao;
			total = total + 1;
		}
	}
	if (total == 0) {
		window.alert('Selecione quais atividades exerceu no período.');
		return false;
	}
	consultaBanco('simples_orientacoes_retencao.php?id=<?=$_SESSION["id_empresaSecao"]?>&totalLinhas=' + document.getElementById('hidTotalLinhas').value + linkFinal, 'divResultado');
}

function iss() {
	if (!document.getElementById('cheIss1').checked && !document.getElementById('cheIss2').checked && !document.getElementById('cheIss3').checked) {
		abreDiv2('divIss1');
	} else {
		fechaDiv('divIss1');
	} 
	if (document.getElementById('cheIss1').checked) {
		abreDiv2('divIss2');
	} else {
		fechaDiv('divIss2');
	} 
	if (document.getElementById('cheIss2').checked || document.getElementById('cheIss3').checked) {
		abreDiv2('divIss3');
	} else {
		fechaDiv('divIss3');
	}
	abreDiv2('divIss4');
}
</script>


<div class="principal">

<h1>Impostos e Obrigações - Simples Nacional</h1>

  
  <!--passo 1 -->
<div id="passo1" style="display:block"> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 1 de 7</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
<br />
  Acesse <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/Servicos/Grupo.aspx?grp=5" target="_blank">esta página</a> do Portal do Simples Nacional e vá na opção <strong>PGDAS-D e Defis - a partir de 01/2012</strong>. Clique em <strong>código de acesso</strong> ou em <strong>certificado digital</strong>, para entrar. Se você não tiver nenhum dos dois, <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/controleAcesso/GeraCodigo.aspx" target="_blank">gere agora mesmo seu código de acesso</a>.<br />
  <br />
  <img src="images/simples2/entrada_no_simples.png" width="960" height="628" />

</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Clique em <strong>Apuração/Calcular Valor Devido</strong>.<br />
Se aparecer uma mensagem solicitando que você informe primeiramente o regime de apuração de receitas a ser adotado, veja <a href="regime_de_apuracao.php">aqui</a> como fazê-lo.<br />
<br />
<img src="images/simples_passo3.jpg" width="957" height="551" border="1" />
</div>

<!-- passo 3 -->
<div id="passo3" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Informe o Período de Apuração (em pagamentos regulares,  o mês imediatamente anterior). Atente para o formato da data (MMAAAA), onde MM é o número do mês e AAAA é o ano. Clique em <strong>Continuar</strong><br />
  <br />
  <img src="images/simples_passo4.jpg" width="961" height="591" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>

<!-- passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border="0" alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border="0" alt="Avançar" /> </a>
<br />
<br />
No primeiro campo, informe a <strong>receita </strong> do período, exceto exportações  (isto é, a soma das notas fiscais emitidas no período, excetuando-se aquelas emitidas para empresas no exterior). No segundo campo, informe a receita com exportações (a soma das notas emitidas para empresas no exterior).<br />
  <br />
  <img src="images/simples_passo5.jpg" width="966" height="622" style="border-width:1px; border-color:#CCC; border-style:solid"/>
</div>


<!-- passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br /> 
<br />
		<?php if( $user->getCidade() == 'São Paulo' ){ ?>
			Na cidade de São Paulo, entre as empresas optantes pelo Simples, apenas os escritórios contábeis podem optar pelo recolhimento de valores fixos de ISS. Esta opção é feita quando a empresa é aberta e <a href="https://dsup.prefeitura.sp.gov.br/" target="_blank">renovada anualmente</a>. Se for este o caso de sua empresa, preencha abaixo os valores fixos de ISS ou ICMS. Caso contrário, deixe em branco e prossiga.
			Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.
		<?php }else if( $user->getCidade() == 'Brasília' ){ ?>
			Em Brasília, as microempresas optantes pelo Simples com faturamento bruto no ano-calendário de até R$ 120 mil, recolhem o ISS (prestadores de serviços) ou o ICMS (comércio) fixo. Se este for o seu caso, preencha os valores fixos de ISS ou ICMS, caso contrário, deixe em branco e prossiga. Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.
		<?php }else{ ?>
			Alguns municípios possibilitam a determinadas categorias de empresas optarem pelo recolhimento de valores fixos de ISS (prestadores de serviços) ou ICMS (comércio). Isto ocorre geralmente com sociedades <a href="javascript:abreDiv('uniprof')">uniprofissionais</a>. Esta opção é feita quando a empresa é aberta e renovada anualmente.  Se este for o seu caso, preencha os valores fixos de ISS ou ICMS, definidos por seu município. Caso contrário, deixe em branco e prossiga. Na dúvida, consulte nosso <a href="suporte.php">help desk</a>.
<?php } ?>
<br />
<br />
<span class="destaque">Se esta tela não apareceu para você,  vá para o próximo passo. </span><br />
<br />
<img src="images/simples2/iss_icms_fixos.png" width="900" height="210" />
</div>


<!-- passo 6 -->
<div id="passo6" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Confira os dados e clique no botão <strong>Salvar</strong>.<br />
<br />
<img src="images/simples_sm_2.png" width="972" height="533" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>

<!-- passo 7 --
<div id="passo7" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Confira os dados e clique no botão <strong>Salvar</strong>.<br />
<br />
<img src="images/simples_sm_2.png" width="972" height="533" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>
!-- passo 7 -->




<!-- passo 7-->
<div id="passo7" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 7</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Uma tela de confirmação do envio será apresentada. Clique em Gerar DAS.<br />
<br />
<img src="images/simples_sm_3.png" width="961" height="544" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>





</div>
<br />
<br />
<?php include 'rodape.php' ?>

