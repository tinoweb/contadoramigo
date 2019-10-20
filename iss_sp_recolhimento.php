<?php 
include 'session.php';
include 'conect.php' ;
include 'check_login.php' ;
?>



<?php include 'header_restrita.php' ?>

<div class="principal">

<h1>Pagamentos</h1>

<h2>Pessoa Jurídica - Retenção de ISS</h2>

<div style="margin-bottom:20px">A<strong> Retenção de ISS em São Paulo</strong> deve ser feita<strong> até o dia 10 do mês subsequente aos serviços prestados</strong>. Você fará todas as retenções de serviços prestados no mês anterior de uma só vez, usando uma única guia. Para fazer a retenção do ISS, você deve primeiro <a href="nota_fiscal_tomador.php">emitir as Notas Fiscais de Tomador de Serviço</a> (NFTS), referentes aos serviços tomados no período (uma nota para cada serviço tomado). Depois de emitidas, proceda da seguinte forma:</div>

<!--passo 1 -->
<div id="passo1" style="display:block">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 1 de 3</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
<br />
Acesse o <a href="http://nfpaulistana.prefeitura.sp.gov.br/index.asp" target="_blank">Site da Nota fiscal Paulistana</a> com seu certificado digital ou senha. No menu lateral, clique em <strong>Guias de Pagamento</strong>.<br />
<br />
<img src="images/ISS_sao_paulo/iss_1.png" width="94%" height="80%" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>


<!--passo 2 -->
<div id="passo2" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione a opção <strong>Guias Pendentes</strong>.<br />
</strong><br />
<img src="images/ISS_sao_paulo/iss_2.png" width="94%" height="80%" /><br />
</div>


<!--passo 3 -->
<div id="passo3" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Selecione o número de guia referente ao mês que deseja pagar. Imprima o boleto e quite-o na rede bancária.<strong></strong><br />
</strong><br />
<img src="images/ISS_sao_paulo/iss_3.png" width="94%" height="80%" /><br />
</div>

</div>
<?php include 'rodape.php' ?>


