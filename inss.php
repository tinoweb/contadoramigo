<?php include 'header_restrita.php'; ?>

<div class="principal">

<!--Dica Certificado eletronico-->

<h1>Impostos e Obrigações</h1>

<h2>Recolhimento do INSS</h2>

<!-- passo 1 -->
  <div id="passo1" style="display:block"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />
Antes de mais nada, leia as seguintes observações:
<ol>    
<li>A GPS, guia para recolhimento do INSS, só pode ser emitida após o <a href="gfip_orientacoes.php">envio da Gfip</a> do mês.<br />
</li>
<li>Se você enviou uma Gfip<strong> sem movimento</strong>, isto é, sem retirada de pró-labore ou salários, não tem nada a recolher ao INSS. </li>
<li>Veja como <a href="inss_atraso.php">Pagar INSS em atraso</a> ou <a href="inss_compensacao.php">Compensar valores pagos indevidamente ou a maior</a>

</ol>
Se enviou Gfip com movimento, deverá agora fazer o recolhimento referente aos pró-labore/salários declarados.<br />
Proceda da seguinte forma: abra novamente o programa Sefip. Vá ao menu superior e clique em<strong> Relatórios/Movimento/GPS.</strong><br />
    <br />
    <img src="images/sefip22.png" width="75%" height="60%" />
  </div>
    

<!-- passo 2 -->
<div id="passo2" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br />   
  
Uma janela se abrirá, escolha a opção <strong>Gerar PDF</strong> e salve o arquivo em algum local fácil de encontrar. Sua GPS está pronta! Abra o PDF e imprima a guia.<br />
    <br />
    Você pode estar se perguntando se poderia imprimir diretamente a guia, sem gerar o pdf. Em tese sim, mas os drivers de impressão do Sefip não funcionam muito bem com as novas gerações de impressoras. Dessa forma, é melhor gerar o PDF e imprimir depois, a partir dele. Você terá menos problemas. <strong><br />
  </strong><br />
  <img src="images/sefip23.png" width="75%" height="60%" />
  </div>
  
  <!-- passo 3 -->
<div id="passo3" style="display:none"> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
  <br />

  Repita a operação anterior para imprimir a <strong>RE - Relação de Trabalhadores</strong> e o <strong>Comprovante de Declaração à Previdência</strong>.
<br />
Muito bem. Você terá em mãos os seguintes documentos:
<ul>
<li>A guia do GPS para pagamento.</li>
<li>A RE - Relação de Trabalhadores (com cerca de 4 páginas).</li>
<li>O Comprovante de Declaração à Previdência.</li>
<li>O Protocolo de envio da Gfip (gerado quando você transmitir a Gfip pelo Conectividade Social).</li>
</ul>

ATENÇÃO: O pagamento da GPS deve ser feito <strong>até o dia 20 do mês subsequente ao movimento</strong>. Se cair em feriado, antecipe.<br />
Se o pagamento da GPS estiver em atraso, clique <a href="inss_atraso.php">aqui</a> para efetuar os cálculos de juros e multa e gerar uma nova guia <br />
(Neste caso a guia gerada no Sefip deve ser desconsiderada). <br />
<br />
Depois de pagar a GPS, junte todos os documentos e arquive-os. Missão cumprida!<br />
    <br />
    <img src="images/sefip22b.png" width="75%" height="60%" /><br />
    <br />
    <br />
  
  

</div>
</div>

<?php include 'rodape.php' ?>