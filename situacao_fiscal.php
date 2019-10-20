<?php 
$nome_meta = "situacao_fiscal";
session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {include 'header.php';} 
?>

<div class="principal">


	<h1>Dívidas e Regularização</h1>
	
	<h2>Verifique se a sua empresa possui impostos pendentes e veja como regularizá-los.</h2>
Muito bem: você quer regularizar os impostos da empresa, mas não sabe como está sua situação fiscal. Você não sabe se há impostos em atraso ou se está tudo em dia e teme que isso vire uma bola de neve, causando grandes prejuízos no futuro. Como ter certeza de que não deve nada? O primeiro passo é consultar sua situação fiscal,  conforme tutorial abaixo. Através dela você conhecerá todas as suas pendências junto à União e à Previdência. <br>
<br>
As pendências mais comuns são a falta do <a href="gfip_orientacoes.php">envio da Gfip</a> em algum mês, ou  do <a href="https://www.contadoramigo.com.br/inss.php">pagamento do INSS</a> (GPS) e também da <a href="simples_orientacoes.php">apuração do Simples Nacional</a>. Estas pendências, caso existam, poderão ser resolvidas através dos tutoriais do Contador Amigo.<br>
<br>
Se estiver tudo em ordem com sua situação fiscal, já é motivo para comemorar! Restará saber ainda se existe alguma pendência junto ao estado ou ao município e se você está devendo alguma declaração, o que também pode gerar multas. Para resolver isso, siga nosso <a href="checkup.php">tutorial para obtenção das certidões negativas de débitos</a>. <br>
<br>
<!--passo 1 -->

<div id="passo1" style="display:block">

<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo1')"><img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong></a>&nbsp;&nbsp;&nbsp; 
<span class="tituloVermelho">Passo 1 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /></a>
<br />
<br />    

Acesse o <a href="https://cav.receita.fazenda.gov.br" target="_blank">Site do E-CAC</a>, usando o Certificado Digital E-CNPJ. Se não possuir, você pode gerar com um código de acesso para poder entrar.<br />
<br />
<img src="images/situacao_fiscal/1.png" width="100%" alt=""/><br />
<br />   
</div>


  <!--passo 2 -->
<div id="passo2" style="display:none">

<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Clique em <strong>Certidões e Situação Fiscal</strong> e, no quadro que se abrirá logo abaixo, vá na opção <strong>Consulta Pendências - Situação Fiscal</strong>.<br />
  <br />
  <img src="images/situacao_fiscal/2.png" width="100%" alt=""/><br />
<br />
</div>

  <!--passo 3 -->
<div id="passo3" style="display:none">

  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
    <span class="tituloVermelho">Passo 3 de 5</span> 
    <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo3')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
  <br />
    As possíveis mensagens são as seguintes:<br>
  <br>
  <strong>&quot;Não foram detectadas irregularidades nos controles da Receita Federal e da Procuradoria-Geral da Fazenda Nacional</strong>.&quot;<br>
  Alegre-se! Isto significa que está tudo em ordem com sua empresa.<br>
  <br>
  <strong>"Consulte aqui o Relatório Complementar de situação fiscal para detalhamento das pendências/elegibilidades suspensas</strong>.&quot;<br>
  É sinal de problemas, possivelmente junto à Previdência Social. Clique no link  para saber do que se trata.<br>
    <br>
    <strong>"Selecione as opções para visualizar as informações cadastrais e fiscais recuperadas nos controles da Receita Federal e Procuradoria-Geral da Fazenda Nacional"</strong> . Também é sinal de problemas. Clique no sinal &quot;+&quot; (conforme imagem abaixo) para abrir a guia <strong>Diagnóstico Fiscal</strong>. Vá clicando nos símbolos de &quot;+&quot; das sub-guias que forem aparecendo, para conhecer as pendências.<br />
    <br />
    <img src="images/situacao_fiscal/3.png" width="100%" alt="" />
    <br />
    <br />
  
</div>

 <!--passo 4 -->
<div id="passo4" style="display:none">

<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo4')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 4 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo5','passo4')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
No exemplo a seguir, vemos que o sistema da Receita acusou a <strong>Ausência de Declarações</strong>. Clicando nesse item, é possível obter mais detalhes.<br />
  <br />
  <img src="images/situacao_fiscal/4.png" width="100%" alt="" />
<br />
<br />
</div>

<!--passo 5 -->
<div id="passo5" style="display:none">

<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo4','passo5')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 5 de 5</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo5')"> &nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Neste exemplo os detalhes mostram que faltam as DCTF do segundo semestre de 2006 e primeiro semestre de 2007. Ao lado, há normalmente o link <strong>Regularizar</strong> que lhe dará as instruções de como proceder. <br />
  <br />
  <img src="images/situacao_fiscal/5.png" width="100%" alt="simples nacional 7" />
</div>

</div>

<?php include 'rodape.php' ?>

