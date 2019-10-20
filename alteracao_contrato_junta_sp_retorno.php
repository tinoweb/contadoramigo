<?php 
include 'session.php';
include 'conect.php' ;
include 'check_login.php' ;
?>



<?php include 'header_restrita.php' ?>

<div class="principal minHeight">

<h1>Alteração de empresa</h1>

<h2>Via Rápida Empresa - Retornar ao processo após pagar as taxas.</h2>

<!--passo 1 -->
<div id="passo1" style="display:block">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 1 de 9</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
<br />
Muito bem, você já pagou as taxas a agora quer seguir com o processo de abertura ou alteração da sua empresa. Acesse novamente o <a href="https://www.jucesp.sp.gov.br/VRE" target="_blank">Via Rápida Empresa</a> e selecione a opção Processo Integrado de Viabilidade e Registro.<br />
<br />
<img src="images/junta/verifica_viabilidade1.png" width="100%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/> </div>

<!--passo 2 -->
<div id="passo2" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Clique na aba <strong>Consultas</strong><br />
</strong><br />
<img src="images/junta/verifica_viabilidade2.png" width="80%" height="70%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>

<!--passo 3 -->
<div id="passo3" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Entre em <strong>Meus Processos</strong>.<br />
</strong><br />
<img src="images/junta/verifica_viabilidade3.png" width="65%" height="60%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>

<!--passo 4 -->
<div id="passo4" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Use seu certificado digital. Clique em <strong>Acessar</strong> e siga em frente.  <br />
<br />
<img src="images/junta/verifica_viabilidade4.png" width="65%" height="65%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>

<!--passo 5 -->
<div id="passo5" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo5')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Selecione a opção <strong>Processos Enviados</strong>. Em seguida clique em <strong>Pesquisar</strong>. Não é necessário preencher os demais campos.<br />
<br />
<img src="images/junta/verifica_viabilidade5.png" width="90%" height="75%" alt=""/><br />
</div>


<!--passo 6 -->
<div id="passo6" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo6')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 6 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo6')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Todos os seus processos serão listados. Localize aquele que você deseja consultar e clique no ícone da impressora.
 <img src="images/impressora.png" width="14" height="16" alt=""/>.<br />
<br />
<img src="images/junta/verifica_viabilidade6.png" width="90%" height="80%" alt=""/><br />
</div>


<!--passo 7 -->
<div id="passo7" style="display:none">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo6','passo7')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 7 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo7')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
      A tela em que você havia parado o processo anteriormente será exibida. Em <strong>ATOS DO PROCESSO</strong>, Confira se, em cada ato, o ícone da <img src="images/ampulheta.png" width="12" height="16" alt=""/> já mudou para <img src="images/validado.png" width="16" height="16" alt=""/>. Se mudou, significa que você já pode prosseguir, caso contrário, deve aguardar mais uns dias e retornar a esta tela em outro momento.<br><br>

Para prosseguir, desça para<strong> DADOS REQUERIDOS</strong> e clique no ícone <img src="images/junta/edicao.png" width="12" height="16" alt=""/> ao lado da frase Dados de Reaproveitamento de Processo com Exigências. Uma janela se abrirá indagando se você está cumprindo exigências. Selecione <strong>Não</strong>. Depois clique no botão <b>Validar</b> e em <b>Gravar</b>. O cumprimento de exigências ocorre somente se, após submeter o processo à Junta, o mesmo retorne com alguma exigência, isto é, solicitando a apresentação de algum documento ou informação faltante.<br />
<br />
<img src="images/junta/verifica_viabilidade7.png" width="80%" height="75%" alt=""/><br />
</div>


<!--passo 8-->
<div id="passo8" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo7','passo8')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 8 de 9</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo9','passo8')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
      <br /> 
      
Em<strong> DADOS REQUERIDOS</strong> agora aparecem os formulários a serem preenchidos. Clique nos ícones <img src="images/junta/edicao.png" alt="" width="13" height="15" /> para completar as informações. Os ícones mudarão para <img src="images/validado.png" width="16" height="16" alt=""/> e você então estará apto para imprimir a documentação para apresentar à Junta.<br>
<br>
Em<strong> IMPRESSÃO DE DOCUMENTOS</strong>, clique nos ícones <img src="images/impressora.png" alt="" width="14" height="16" /> e imprima todos os formulários disponíveis.<br />
      <br />
<img src="images/junta/retorno8.png" width="80%" height="100%" alt="" style="border-width:1px; border-color:#CCC; border-style:solid"/><br />
</div>


<!--div 9-->
<div id="passo9" style="display:none">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo8','passo9')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 9 de 9</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo9')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br /><br /> 
Agora só falta fazer o Documento Básico de Entrada (DBE) no Portal da RedeSIM. Nele você estará informando à Receita Federal as alterações da Empresa, para que conste no seu CNPJ. Acesse nosso <a href="orientacoes_dbe.php">tutorial para geração do DBE.</a> <br />
<br />
Finalmente, quando estiver com todos os documentos em mãos, dirija-se à Junta Comercial para dar entrada ao pedido. Consulte os <a href="http://www.institucional.jucesp.sp.gov.br/institucional_locais.php" target="_blank">locais e horários de atendimento</a>. Se sua empresa for uma sociedade limitada, não esqueça de anexar também  3 vias da <a href="https://www.contadoramigo.com.br/alteracao_contrato.php">alteração do Contrato Social</a>. Em caso de alteração do quadro societário, junte também cópia autenticada do documento de identidade de todos os sócios admitidos ou administradores. <br />
<br />
Aguarde alguns dias e <a href="alteracao_consulta.php">consulte se o processo já foi analisado pela Junta</a>.<br>
	<br></div>



</div>
<?php include 'rodape.php' ?>
