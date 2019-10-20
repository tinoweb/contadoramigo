<?php include 'header_restrita.php' ?>

<div class="principal">

  <h1>Alíquotas de impostos</h1>
  <h2>Faturamento dos últimos 12 meses</h2>
  
  <div style="margin-bottom:20px">
  Para conhecer com extatidão o faturamento de sua empresa nos últimos 12 meses, acesse o <a href="https://cav.receita.fazenda.gov.br" target="_blank">E-Cac</a>, com seu certificado digital. Vá em Simples Nacional, clique no item &quot;PGDAS-D - a partir de 01/2012&quot; e proceda da seguinte forma:
  </div>
  
<!-- passo 1 -->
<div id="passo1" style="display:block"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /></a>
<br />
<br />
Clique em <strong>DAS/Consultar Extrato</strong>. Aparecerá um campo para você digitar o ano. Digite o ano atual. 
<br />
<br />
<img src="images/fator_r1.png" width="100%" height="70%" />
</div>
  

<!-- passo 2 -->
<div id="passo2" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />   
Uma lista com os extratos do ano será exibida. Clique naquele do mês mais recente.
<br />
<br />
<img src="images/fator_r2.png" width="100%" height="70%" />
</div>

<!-- passo 3 -->
<div id="passo3" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
O PDF do extrato será baixado em seu micro. Abra-o e procure o <strong>o faturamento dos últimos 12 meses</strong>, no local indicado na figura abaixo.<br />
Após localizá-lo, retorne à <a href="impostos_aliquotas_calcula.php">página de cálculo das alíquotas dos impostos</a> para informá-lo. <br />
<br />
<img src="images/faturamento_12meses.png" width="70%" height="40%" style="border-color:#CCC; border-width:1px; border-style:solid" />
</div>
  


</div>

<?php include 'rodape.php' ?>
