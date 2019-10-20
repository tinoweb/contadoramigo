<?php 
include 'session.php';
include 'conect.php' ;
include 'check_login.php' ;
?>



<?php include 'header_restrita.php' ?>

<div class="principal">

<h1>Alteração das Empresas</h1>

<h2>Via Rápida Empresa - Iniciar processo de alteração da empresa</h2>

<div id="passo1" style="display:block">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 1 de 10</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
<br />
Acesse  o site <a href="https://www.jucesp.sp.gov.br/VRE/" target="_blank">Via Rápida Empresa</a> da Junta Comercial, com o navegador <strong>Internet Explorer</strong>. Em seguida, clique em Registro de atos mercantis<br>
<br>
<b>ATENÇÃO: </b>antes de iniciar o processo, vá em <b>Ferramentas/Configurações do Modo de Exibição de Compatibilidade</b> e inclua o site <b>sp.gov.br</b>, caso contrário alguns campos de preenchimento não funcionarão!<br>
<br />
<img src="images/junta/alteracao_sociedade1.png" width="100%" height="90%" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Use seu certificado digital. Clique em <strong>Acessar</strong> e siga em frente.<strong><br />
</strong><br />
<img src="images/junta/alteracao_sociedade2.png" width="65%" height="75%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

<!--passo 3 -->
<div id="passo3" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em<strong> Alteração de Matriz</strong> e selecione a opção referente ao tipo de sua empresa: Empresário (firma individual), Sociedade Limitada ou EIRELE (Empresa Individual de Responsabilidade Limitada). <br />
</strong><br />
<img src="images/junta/alteracao_sociedade3.png" width="100%" height="75%" style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione a opção <strong>Empresa com NIRE</strong> <a href="javascript:abreDiv('nire')"><img src="images/dica.gif" width="13" height="14" border="0" align="texttop"/></a> e digite o número. Em seguida clique em <strong>Seleção de Atos</strong>. <br />
<br />
<img src="images/junta/alteracao_sociedade4.png" width="100%" height="70%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />



<!--BALLOM NIRE -->

<div style="width:310px; position:relative; margin-top:-650px; margin-left:253px; display:none; z-index:10" id="nire" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px;">
        <strong>NIRE</strong> é o Número de Inscrição no Registro de Empresas, atribuído no momento em que sua empresa foi registrada pela primeira vez na Junta Comercial. <br />
        <br />
        É provável que você o encontre em seu contrato social, mas poderá também obtê-lo facilmente no site da Junta Comercial a partir de sua razão social. <br />
        <br />
        <img src="images/avancar_azul.png" width="7" height="8" border="0" /> <a href="obter_nire.php">Veja como descobrir seu NIRE</a>
	</div>
    
</div>

<!--FIM DO BALLOOM NIRE -->






</div>

<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione as alterações que pretende fazer em sua empresa. Se você for uma sociedade e estiver fazendo qualquer alteração no contrato social, deve selecionar também o item "consolidação contratual". Em seguida, role a tela para baixo e clique em <strong>Próximo</strong>.
<br />
</strong><br />
<img src="images/junta/alteracao_sociedade5.png" width="100%" height="75%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

<!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
De acordo com a alteração selecionada, será exibido um formulário. Se você selecionou mais de uma alteração, após preencher este primeiro formulário, será exibido outro e assim sucessivamente, até que todas as alterações tenham sido informadas. A cada formulário, clique no botão <strong>Próximo</strong>. Na tela abaixo exibimos, como exemplo, o   formulário  para alteração de endereço.<br />
<br />
<img src="images/junta/alteracao_sociedade6.png" width="100%" height="75%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

<!--passo 7-->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
      <br />
      Após preencher os formulários, será exibida uma tela como abaixo. Digite a data do documento (pode ser a data atual) e clique em <strong>Gravar</strong>. O sistema fará uma validação. Se algum campo dos formulários anteriores não tiver sido preenchido, ou se houver alguma inconsistência, será exibido um aviso. Corrija o campo e tente gravar novamente até que a operação seja bem sucedida.<br />
<br />
      <img src="images/junta/alteracao_sociedade7.png" width="100%" height="75%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>


<!--passo 8-->
<div id="passo8" style="display:none"> 
  <p><a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
    <span class="tituloVermelho">Passo 8 de 10</span> 
    <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
    <br />
    Em <strong>ATOS DO PROCESSO</strong>, se aparecer o ícone <img src="images/validado.png" width="16" height="16" alt=""/> (Validado), ao lado de cada ato, <strong>pule para para o próximo passo deste tutorial</strong>. <br>
    <br>
    Se aparecer <img src="images/junta/edicao.png" alt="" width="13" height="15" />, significa que o ato necessita passar por uma análise de viabilidade e você deverá fornecer algumas informações sobre a sede da empresa. Isto ocorre na alteração de endereço ou atividade para municípios conveniados. Clique em <img src="images/junta/edicao.png" alt="" width="13" height="15" /> e preencha o formulário. Se, depois disso, o ícone virar <img src="images/validado.png" width="16" height="16" alt=""/>, <strong>você já poderá pular para o próximo passo deste tutorial</strong>.  Se aparecer <img src="images/ampulheta.png" width="12" height="16" alt=""/>, deverá aguardar a análise  de viabilidade, que pode levar alguns dias.  <br>
    <br>
    Veja, posteriormente, como <a href="abertura_contrato_junta_sp_retorno_viabilidade.php">retornar ao processo para verificar liberação de viabilidade</a>.<br />
    <br />
    <img src="images/junta/alteracao_sociedade8.png" width="100%" height="85%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
  </p>


</div>

<!--passo 9-->
<div id="passo9" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 9 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo10','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
      <br />
      Em <strong>DADOS REQUERIDOS</strong>, clique no ícone <img src="images/junta/edicao.png" width="13" height="15" />, ao lado da frase Dados de Reaproveitamento de Processo com Exigências. Uma janela se abrirá indagando se você está cumprindo exigências. Selecione <strong>Não</strong>. Depois clique no botão <b>Validar</b> e em <b>Gravar</b>. O cumprimento de exigências ocorre somente se, após submeter o processo à Junta, o mesmo retorne com alguma exigência, isto é, solicitando a apresentação de algum documento ou informação faltante.<br />
      <br />
    <img src="images/junta/alteracao_sociedade9.png" width="100%" height="85%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>


<!--passo 10-->
<div id="passo10" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo10')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 10 de 10</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo10')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a><br>
<br> 
Em<strong> DADOS REQUERIDOS</strong> clique nos ícones <img src="images/junta/edicao.png" alt="" width="13" height="15" /> para completar as informações. Os ícones mudarão para <img src="images/validado.png" width="16" height="16" alt=""/> e você então estará apto para imprimir as guias.<br>
<br>
Em<strong> IMPRESSÃO DE DOCUMENTOS</strong>, clique nos ícones <img src="images/impressora.png" alt="" width="14" height="16" /> e imprima as guias do DARE, DARF e a página para colar as guias. Os demais documentos do processo somente serão liberados após o pagamento das taxas no banco. Depois de pagas, veja como <a href="https://www.contadoramigo.com.br/alteracao_contrato_junta_sp_retorno.php">retornar ao processo para impressão dos demais documentos</a>.<br />
      <br />
<img src="images/junta/alteracao_sociedade10.png" width="100%" height="85%"  style="border-width:1px; border-color:#CCC; border-style:solid" /><br />
</div>

</div>
<?php include 'rodape.php' ?>


