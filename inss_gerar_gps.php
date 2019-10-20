<?php include 'header_restrita.php' ?>

<div class="principal">

<!--BALLOM Sefip -->
<div style="width:310px; position:absolute; margin-left:170px; margin-top:100px; display:none" id="sefip">
<div style="width:8px; position:absolute; margin-left:280px; margin-top:12px"><a href="javascript:fechaDiv('sefip')"><img src="images/x.png" width="8" height="9" border="0" /></a></div>
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><img src="images/balloon_topo.png" width="310" height="19" /></td>
    </tr>
    <tr>
      <td background="images/balloon_fundo_esq.png" valign="top" width="18"><img src="images/ballon_ponta.png" width="18" height="58" /></td>
      <td width="285" bgcolor="#ffff99" valign="top"><div style="width:245px; margin-left:20px; font-size:12px">
      <strong>Sefip</strong> é um programa disponibilizado gratuitamente pela Caixa Econômica Federal para geração da Gfip. Veja em <a href="procedimentos_iniciais.php">procedimentos iniciais</a> como baixá-lo e instalá-lo em seu computador</div></td>
      <td background="images/balloon_fundo_dir.png" width="7"></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/balloon_base.png" width="310" height="27" /></td>
    </tr>
  </table>
</div>
<!--FIM DO BALLOOM CCM -->

<h1>Impostos e Obrigações</h1>
<h2>Recolhimento do INSS</h2>

 <!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />
Acesse a <a href="http://www3.dataprev.gov.br/cws/contexto/captchar/index_salEmpresa2.html" target="_blank">página de cálculo de contribuições</a> da Receita Federal. Informe seu CNPJ e digite o código de segurança, conforme pedido.<br />
Em seguida clique no botão <strong>Confirmar</strong> .<br /><br /><img src="images/inss1.png" width="70%" height="45%" /><br /><br />
</div>


 <!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />
Confira seus dados e confirme novamente.<br />
<br /><img src="images/inss2.png" width="70%" height="50%" /><br /><br />
</div>

 <!--passo 3 -->
<div id="passo3" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />
Em <strong>Código de Pagamento</strong> digite <span style="color:#a61d00; font-weight:bold;"><?=$_SESSION['INSS_variaveis']['cod_pagamentos']?></span>.<br />
Em <strong>Competência</strong> informe o mês referente ao recolhimento.<br />
Em <strong>Valor INSS</strong> informe o valor <span style="color:#a61d00; font-weight:bold;">R$ <?=$_SESSION['INSS_variaveis']['valor']?></span>.<br />
Deixe em branco o campo <strong>Valor Outras Entidades</strong>.
<br />
Clique no botão <strong>Adicionar Contribuição</strong>.<br /><br />
<img src="images/inss3.png" width="85%" height="65%" /><br /><br />
</div>

<!--passo 4 -->
<div id="passo4" style="display:none"> 
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />   
Em <strong>Data de Pagamento</strong>, informe a data em que efetivamente pretende pagar a guia e clique em <strong>Confirmar</strong>.<br /><br />
<img src="images/inss4.png" width="90%" height="60%" /><br /><br />
</div>
    
    
<!-- passo 5 -->
<div id="passo5" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br />
No campo <strong>Seleção de competências</strong> marque a linha referente ao recolhimento e clique no botão <strong>Gerar GPS</strong>.<br /><br />
<img src="images/inss5.png" width="90%" height="40%" /><br /><br />
</div>

<!-- passo 6 -->
<div id="passo6" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br /><br /> 
Pronto! Agora você pode imprimir e efetuar o pagamento de sua GPS.
Note que essa guia não possui código de barras. A Receita ainda não disponibiliza guias em código de barras. Mas você pode pagá-la mesmo assim pelo Internet Banking. Procure no site de seu banco a área para pagamento de GPS e informe os dados presentes na guia gerada.<br /><br />
<img src="images/inss6.png" width="70%" height="45%" /><br /><br />
</div>

 
</div>
</div>

<?php include 'rodape.php' ?>

