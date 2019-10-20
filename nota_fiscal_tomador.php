<?php include 'header_restrita.php' ?>

<div class="principal">

<div class="titulo" style="margin-bottom:10px;">Pagamentos</div>

<div class="tituloVermelho">Notas Fiscal do Tomador de Serviço</div>

<div style="margin-bottom:20px">Você deverá registrar apenas as notas fiscais de serviços emitidas contra sua empresa por prestadores <strong>com sede em outros municípios</strong>. <br />
  <br />
  A emissão  deve ser feita <strong>até o dia 10  do mês subsequente ao da prestação do serviço</strong>, nos casos em que houver a obrigatoriedade de retenção e recolhimento do ISS  ou até o dia 30, nos demais casos. Para emitir a nota Fiscal do Tomador de Serviço, proceda da seguinte forma: </div>

<!--passo 1 -->
<div id="passo1" style="display:block">
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo1')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
  <span class="tituloVermelho">Passo 1 de 3</span> 
  <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo1')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
  <br />
<br />
Acesse o site da <a href="http://nfpaulistana.prefeitura.sp.gov.br/index.asp" target="_blank">Nota fiscal Paulistana</a> com seu certificado digital ou senha. No menu lateral, clique em <strong>Emissão de NFTS</strong>.<br />
<br />
<img src="images/tomador_sp/tomador1.png" width="961" height="826" style="border-width:1px; border-color:#CCC; border-style:solid" />
</div>

<!--passo 2 -->
<div id="passo2" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo2')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 2 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo3','passo2')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /></a>
<br />
<br /> 
Digite o <strong>CNPJ</strong> da empresa que lhe prestou o serviço.<br />
<br />
<img src="images/tomador_sp/tomador2.png" width="961" height="826" /><br />
</div>


<!--passo 3 -->
<div id="passo3" style="display:none">
 <a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo2','passo3')"> <img src="images/retroceder_azul.png" width="7" height="8" border=0 alt="Voltar" /> <strong>ANTERIOR</strong>&nbsp;&nbsp;&nbsp; </a>
<span class="tituloVermelho">Passo 3 de 3</span> 
<a class="linkMenu" style="font-size:10px" href="javascript:abreDivFechaOutro('passo1','passo3')">&nbsp;&nbsp;&nbsp;<strong>PRÓXIMO</strong> <img src="images/avancar_azul.png" width="7" height="8" border=0 alt="Avançar" /> </a>
<br />
<br /> 
Preencha o formulário com as seguintes informações:
<ul>
<li>Em <strong>Data da Prestação do Serviço</strong>, informe a mesma constante na nota fiscal ou recibo emitido pelo prestador.<br />
</li>
<li>Em <strong>Documento Fiscal</strong>, marque a opção &quot;Com emissão de documento fiscal autorizado pelo município&quot;, se o prestador for uma empresa, ou &quot;Dispensados da emissão de documento fiscal&quot; em se tratando de prestador autônomo. Se houve emissão de nota fiscal por parte do prestador, informar o número e série da mesma.</li>
<li>Em <strong>Código do Serviço</strong>, selecione o serviço prestado.</li>
<li>Em <strong>ISS Retido pelo Tomador</strong>, se a opção estiver marcada &quot;Não&quot;, prossiga normalmente. Se estiver marcada &quot;Sim&quot; é porque o prestador não está registrado no CPOM (Cadastro de Prestadores de Outros Municípios) e portanto você terá que reter o ISS dele. <br>

É possível, porém, que ele esteja registrado, mas o Código de Serviço que você selecionou anteriormente não esteja associado ao cadastro dele. Vá no link &quot;Em caso de dúvidas, clique aqui para verificar se o ISS deverá ser retido&quot;. Veja se  o prestador está cadastrado no CPOM e quais atividades estão associadas a ele. Se o serviço prestado se encaixar em uma dessas atividades, altere o campo <strong>Código de Serviço</strong> para uma delas. Dessa forma, o item ISS Retido pelo Tomador mudará para &quot;Não&quot; e você não precisará mais reter o imposto. <br>

Agora se o prestador não estiver cadastrado, não tem jeito. Será preciso fazer a retenção. Nesse caso, vc poderá descontar o   ISS do valor a ser pago ao prestador. O cálculo do imposto será feito automaticamente pelo sistema da Prefeitura ao gerar a Nota Fiscal de Tomador de Serviços.</li>
<li>Em <strong>Discriminação dos Serviços</strong>, informe a mesma descricão constante na nota fiscal ou recibo emitido pelo prestador.</li>
<li>Em <strong>Simples Nacional</strong>, informe se o prestador é ou não optante pleo Simples.</li>
<li>Em <strong>Valor Total da Nota</strong>, infome o valor cobrado pelo seu prestador na nota fiscal, sem descontos.</li>
<li>Clique no botão <strong>Emitir</strong>.</li>
</ul>
Pronto, sua nota está emitida!<br />
<br />
<img src="images/tomador_sp/tomador3.png" width="961" height="1185" /><br />
</div>


</div>
</div>
<?php include 'rodape.php' ?>

