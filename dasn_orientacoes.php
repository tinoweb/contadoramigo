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
	consultaBanco('simples_orientacoes_retencao_nova.php?id=<?=$_SESSION["id_empresaSecao"]?>&totalLinhas=' + document.getElementById('hidTotalLinhas').value + linkFinal, 'divResultado');
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

<h2>Orientações para pagamento</h2>

<!--passo 1 -->
<div id="passo1" style="display:block"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 2</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Conecte-se ao  <a href="javascript:abreJanela('https://cav.receita.fazenda.gov.br/scripts/CAV/login/login.asp', 'DAS','width=800, height=600, top=150, left=150, scrollbars=yes, resizable=yes')">E-CAC</a> usando seu certificado digital e em seguida clique no link <strong>Simples Nacional</strong>. É possivel que você tenha que descer a tela para visualizá-lo.<br /><br />
<img src="images/simples_cac1.jpg" width="100%" height="80%" style="border-width:1px; border-color:#CCC; border-style:solid"/>
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 2</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Clique na opção <strong>Declaração Anual do Simples Nacional-Ano-Calendário até 2011 </strong><br /><br />
<img src="images/dasn_01.png" width="100%" height="60%" style="border-width:1px; border-color:#CCC; border-style:solid"/>
</div>




</div>

<br />
<br />
<?php include 'rodape.php' ?>

