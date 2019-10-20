<?php 
include 'session.php';
include 'conect.php' ;
include 'check_login.php' ;
?>



<?php include 'header_restrita.php' ?>

<div class="principal">

<div class="titulo" style="margin-bottom:10px;">Abertura de Empresa</div>
<br />
<div style="margin-bottom:20px"><span class="tituloVermelho">Via Rápida Empresa - Verificação da análise de viabilidade (municípios conveniados)</span><br />
<br />

<!--passo 1 --></div>
<div id="passo1" style="display:block">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 1 de 6</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
<br />
Para verificar se a análise de viabilidade de seu processo já foi aprovada. Acesse novamente o  <a href="https://www.jucesp.sp.gov.br/VRE/" target="_blank">Via Rápida Empresa</a>  e selecione a opção<strong> Retornar a um processo previamente inciado</strong>.<br />
<br />
<img src="images/junta/retorno1.png" width="100%" height="60%" alt=""/>
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Use seu certificado digital. Clique em <strong>Acessar</strong> e siga em frente.<strong><br />
</strong><br />
<img src="images/junta/retorno2.png" width="100%" height="50%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

<!--passo 3 -->
<div id="passo3" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
No menu superior, selecione a opção <strong>Consulta de Processos</strong> e clique em <strong>Lista de Processos</strong>.<br />
</strong><br />
<img src="images/junta/retorno3.png" width="100%" height="50%" alt=""/><br />
</div>

<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione a opção <strong>Processos Enviados</strong>. Em seguida clique em <strong>Pesquisar</strong>. Não é necessário preencher os demais campos. <br />
<br />
<img src="images/junta/retorno4.png" width="100%" height="50%" alt=""/><br />
</div>

<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Os processos iniciados por você aparecerão listados. Clique no ícone <img src="images/impressora.png" width="14" height="16" alt="Impressão"/>. <br />
<br />
<img src="images/junta/retorno5b.png" width="100%" height="50%" alt=""/><br />
</div>


<!--passo 6-->
<div id="passo6" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 6</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
      <br /> 
      A tela em que você havia parado o processo será exibida. Em <strong>ATOS DO PROCESS</strong>O, Verifique se, em cada ato, o ícone da <img src="images/ampulheta.png" width="12" height="16" alt=""/> já mudou para <img src="images/validado.png" width="16" height="16" alt=""/>.<br><br>

Depois que todos os atos estiverem validados, desça para<strong> DADOS REQUERIDOS</strong> e clique nos ícones <img src="images/junta/edicao.png" alt="" width="13" height="15" /> para completar as informações. Os ícones mudarão para <img src="images/validado.png" width="16" height="16" alt=""/> e você então estará apto para imprimir as guias.<br>
<br>
Em<strong> IMPRESSÃO DE DOCUMENTOS</strong>, clique nos ícones <img src="images/impressora.png" alt="" width="14" height="16" /> e imprima as guias do DARE e DARF. Os demais documentos do processo somente serão liberados após o pagamento das taxas no banco. Depois de pagas, veja como <a href="abertura_contrato_junta_sp_retorno.php">retornar ao processo para impressão dos demais documentos</a>.<br />
      <br />
<img src="images/junta/alteracao_sociedade9.png" width="100%" height="90%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>






</div>
<?php include 'rodape.php' ?>
