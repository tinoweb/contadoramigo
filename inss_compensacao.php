<?php include 'header_restrita.php'; ?>

<div class="principal">

<!--Dica Certificado eletronico-->

<h1>Impostos e Obrigações</h1>

<h2>Compensação do INSS.</h2>

    
      <!-- passo 1 -->
<div id="passo1" style="display:block"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Pois bem, você fez recolhimentos indevidos de INSS em meses anteriores e deseja agora compensar os valores pagos a mais. Tudo bem isto é possível e o valor pode inclusive ser corrigido. <img class="imagemDica" src="images/dica.gif" width="13" height="14" border="0" align="texttop" div="correcao" /><br>
<br>
Mas para que a compensação seja feita há algumas condições:
<ul>
<li>A Gfip indevida ou a maior não pode ter sido paga com atraso;
<li>os valores indevidos ou a maior devem ser relativos apenas aos recolhimentos do INSS;</li>
 <li>você precisa estar em dia com as contribuições normais, inclusive as decorrentes de parcelamento;</li>
 <li>os recolhimentos errados tenham sido feitos há menos de 5 anos.</li>
 </ul>

Para efetuar a compensação, siga o <a href="gfip_orientacoes.php">tutorial 
para geração e envio da Gfip</a>, como se fosse fazer seu próximo recolhimento normalmente. No <strong>Passo 4</strong> (ou <strong>Passo 6</strong> se estiver enviando a gfip pela primeira vez), antes de clicar no botão Executar <img src="images/ico_executar.gif" width="11" height="13" />, Clique no ícone <img src="images/ico_nova_empresa.gif" width="12" height="15" /> onde está escrito o  nome da empresa, em seguida clique em <strong>Informações Complementares</strong> e finalmente em <strong>Dados do Movimento</strong>.<br />
<br />
    <img src="images/compensacao1.png" width="75%" height="60%" />  </div>
    

<!-- passo 2 -->
<div id="passo2" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
No campo <strong>Compensação</strong>, digite o valor a compensar. Em <strong>período início e período fim</strong>, informe a competência inicial e competência final que originou o pagamento ou recolhimento indevido ou a maior. Por exemplo se você pagou a mais a GPS de janeiro de 2013, informe período início: 01/2013 e período fim 01/2013. Em seguida clique em <strong>Salvar</strong> e depois  no ícone da calculadora <img src="images/ico_calculadora.gif" width="16" height="12" /> para voltar à tela incial do Sefip.<br />
  <br />
    <img src="images/compensacao2.png" width="75%" height="60%" />
  </ol>
</div>

<div id="passo3" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Pronto, siga com o seu tutorial normalmente. Quando você clicar no botão <strong>Executar</strong> <img src="images/ico_executar.gif" width="11" height="13" />, pode aparecer a  tela abaixo. Isto se estiver compensando um valor superior a 30% da GPS total. Não tem problema. Há alguns anos este limite foi abolido. Clique <strong>Sim</strong> e siga em Frente. Ao final o processo, vá em <strong>Relatórios/Movimento</strong> e clique em <strong>GPS</strong> para conferir se o valor da guia veio com a redução. Se você não conseguiu compensar tudo de uma só vez, poderá utilizar o saldo restante para compensar a próxima Gfip.<br />
  <br />
    <img src="images/compensacao_30.png" width="80%" height="50%" />
  </ol>
</div>

<!--BALLOM VALOR CORRIGIDO -->
<div style="width:280px; position:absolute; display:none;" id="correcao" class="bubble_left box_visualizacao x_visualizacao">

	<div style="padding:20px; padding-bottom:10px">
     Você pode corrigir o valor a ser<br />compensado da seguinte forma:
      
      <ul>
<li>Some 1% para o mês em que a GPS foi paga indevidamente ou a maior;</li>
<li>Mais 1% para o mês em que será paga a GPS compensada;</li>
<li>e a <a href="http://idg.receita.fazenda.gov.br/orientacao/tributaria/pagamentos-e-parcelamentos/taxa-de-juros-selic" target="_blank">taxa SELIC</a> nos meses intermediários entre o pagamento indevido e a efetiva compensação.</li>
</ul>  
    </div>
    
</div>
<!--FIM DO VALOR CORRIGIDO -->
 

</div>
</div>
<?php include 'rodape.php' ?>