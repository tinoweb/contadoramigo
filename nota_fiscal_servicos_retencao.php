<?php include 'header_restrita.php' ?>

<div class="principal">

  <h1>Nota Fiscal de Serviços</h1>
  <h2>Como gerar notas com retenção de ISS</h2>

  <div style="margin-bottom:20px">
    Cada um dos 5.570 municípios da federação tem sua própria nota fiscal eletrônica. Seria um tanto difícil montar um tutorial para cada um deles. Mas o preenchimento  segue um padrão semelhante. Assim, apresentamos abaixo o <strong>modelo de preenchimento da Nota Fiscal Paulistana</strong>, que poderá ser aproveitado também para assinantes de outras cidades. Em caso de dúvida específica sobre preenchimento para seu município, contate nosso <a href="suporte.php">Help Desk</a>. <br />
</div> 
  
  
  
  <!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 2</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Acesse o site da Nota Fiscal Eletrônica usando seu certificado digital ou senha web. No menu lateral, clique em<strong> Emissão de NF-e</strong>. Logo na primeria tela que se abre, marque a opção <strong>Tributado fora de São Paulo</strong>. Selecione então o <strong>Estado</strong> e a <strong>cidade</strong> para a qual prestou o serviço. Finalmente digite o CNPJ do cliente (para quem a nota será emitda) e clique em <strong>Avançar</strong>.<br />
<br />
<img src="images/nota_servicos/1.png" width="100%" height="70%" />
</div>


  <!--passo 2 -->
<div id="passo2" style="display:none">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
    <span class="tituloVermelho">Passo 2 de 2</span> 
    <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
  <br /> 
    Preencha 
    o formulário da seguinte forma:
    
    <ul>
  <li><strong>Código do Serviço</strong>:   nem sempre  os códigos e atividades da  prefeitura  são  iguais aos   do CNPJ, selecione o   mais parecido.</li>
  <li><strong>Valor da Nota</strong>: digite o valor bruto.</li>
    <li><strong>ISS retido pelo Tomador</strong>: marque <strong>Sim</strong>.</li>
    <li><strong>Alíqu.(%)</strong>: informe e o percentual da alíquota do ISS correspondente à sua atividade. Para saber qual é, acesse a página <a href="impostos_aliquotas_calcula.php">alíquota dos impostos</a>.</li>
    <li><strong>Discriminação dos Serviços:</strong> descreva o serviço prestado. Lembre-se que ele deve estar relacionado com o código do serviço selecionado. <br />
      Use este campo também para colocar outras informações que julgar necessárias, tais como a data de vencimento e os dados para pagamento.<br /> 
    Para atender a lei da transparência fiscal, é preciso ainda informar o<strong> valor das alíquotas dos impostos incidentes na nota</strong>. Para isso, acesse a página <a href="impostos_aliquotas_calcula.php">alíquota dos impostos</a>, copie (ctrl + C) e cole (ctrl +V) os valores.</li>
 </ul>
    Finalmente, clique em <strong>Emitir </strong>e confirme! Pronto a nota está gerada. Envie por email ao seu cliente e imprima uma cópia para  arquivar. A nota deverá ser lançada no livro caixa apenas quando cliente efetivamente lhe pagar.<br />
    <br />
    <img src="images/nota_servicos/2.png" width="100%" height="100%" />
</div>


</div>


<?php include 'rodape.php' ?>
