<?php include 'header_restrita.php' ?>

<div class="principal">

  <div class="titulo">Nota Fiscal de Serviços</div>
  <div class="tituloVermelho">Como gerar notas para clientes do exterior</div>

  <div style="margin-bottom:20px">
    Cada um dos 5.570 municípios da federação tem sua própria nota fiscal eletrônica. Seria um tanto difícil montar um tutorial para cada um deles. Mas o preenchimento segue um padrão semelhante. Assim, apresentamos abaixo o <strong>modelo de preenchimento da Nota Fiscal Paulistana</strong>, que poderá ser aproveitado também para assinantes de outras cidades. Em caso de dúvida específica sobre preenchimento para seu município, contate nosso <a href="suporte.php">Help Desk</a>. <br />
</div> 
  
  
  
  <!--passo 1 -->
<div id="passo1" style="display:block">
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 1 de 2</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Acesse o site da Nota Fiscal Eletrônica usando seu certificado digital ou senha web. No menu lateral, clique em<strong> Emissão de NF-e</strong>. Logo na primeria tela que se abre, marque a opção <strong>Exportação de Serviços</strong>. Deixe em branco o campo CNPJ  e clique em <strong>Avançar</strong>.<br />
<br />
<img src="images/nota_servicos/3.png" width="964" height="651" />
</div>


  <!--passo 2 -->
<div id="passo2" style="display:none">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
    <span class="tituloVermelho">Passo 1 de 2</span> 
    <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
  <br /> 
    Preencha 
    o formulário da seguinte forma:
    
    <ul>
  <li>Clique em <strong>Tomador de Serviços</strong> para preencher os dados de seu cliente no exterior. Como o formulário não é adequado a uma emrpesa estrangeira<strong>,</strong> use o campo <strong>Nome</strong> para incluir, além da Razão Social do cliente, o número IVA dele. Preencha o endereço normalmente, sem informar o CEP e no campo <strong>Bairro</strong> escreva a cidade e o país<strong>.<br />
    Código do Serviço</strong>:   nem sempre  os códigos e atividades da  prefeitura  são  iguais aos   do CNPJ, selecione a   mais parecida.</li>
  <li><strong>Valor da Nota</strong>: digite o valor bruto.</li>
    <li><strong>Discriminação dos Serviços:</strong> Descreva o serviço prestado. Lembre-se que ele deve estar relacionado com o código do serviço selecionado. <br />
      Use este campo também para colocar outras informações que julgar necessárias, tais como o valor do serviço na moeda usada pelo seu cliente e seus dados bancários.<br /> 
      Para atender a lei da transparência fiscal, é preciso ainda informar o<strong> valor das alíquotas dos impostos incidentes na nota</strong>. Para isso, acesse a página <a href="impostos_aliquotas_calcula.php">alíquota dos impostos</a>, copie (ctrl + C) e cole (ctrl +V) os valores.</li>
 </ul>
    Finalmente, clique em <strong>Emitir </strong>e confirme! Pronto a nota está gerada. Envie por email ao seu cliente e imprima uma cópia para  arquivar. A nota deverá ser lançada no livro caixa apenas quando for paga. Como se trata de um serviço para o exterior, é conveniente que você tenha também um contrato de serviços firmado entre as partes.<br />
    <br />
    <img src="images/nota_servicos/4.png" width="964" height="1068" />
</div>


</div>


<?php include 'rodape.php' ?>
