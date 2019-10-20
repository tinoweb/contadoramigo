<?php include 'header_restrita.php' ?>

<div class="principal">
<div style="width:75%">

<h1>Impostos e Obrigações</h1>

<h2>Simples Nacional</h2>
Você precisa declarar mensalmente seu faturamento  à Receita Federal. Esta declaração deve ser transmitida mesmo que sua empresa não tenha movimento no período. Com base nela será calculado o imposto devido, o chamado <strong>Simples Nacional</strong>. Tanto a declaração como a guia de pagamento do Simples Nacional podem ser geradas pela Internet<span style="margin-bottom:5px">. O acesso se dá através do <a href="javascript:abreJanela('https://cav.receita.fazenda.gov.br/eCAC/publico/login.aspx', 'DASN', 'width=800, height=600, top=150, left=150, scrolling=yes')">E-CAC</a> (Centro Virtual de Atendimento) e o pagamento pode ser feito</span> nos <a href="javascript:abreJanela('http://www.receita.fazenda.gov.br/Pagamentos/RedeArrecadadora/BancosRedeArrecadadora.htm', 'Bancos_Credenciados','width=800, height=600, top=150, left=150, scrolling=yes')">bancos credenciados</a>.
<br />
<ul>
<li><a href="simples_orientacoes.php">Orientações para transmissão e pagamento</a></li>
</ul>
<br />

<h2>Previdência Social (GFIP e INSS)</h2>
Você precisa enviar mensalmente à Previdência Social, a Gfip (um relatório com informações previdenciárias de sócios e funcionários) e efetuar o recolhimento do INSS. Tanto a Gfip como a guia para recolhimento do INSS são gerados através do Programa <strong>Sefip</strong> (que você já deve ter instalado em seu computador nos <a href="procedimentos_iniciais.php">procedimentos inicias</a>) . Depois de gerada, a Gfip é enviada à Previdência Social pela Internet. <br />
<ul>
<li><a href="gfip_orientacoes.php">Orientações para geração e envio da Gfip</a></li>
<li><a href="inss.php">Orientações para recolhimento do INSS</a></li>
<li><a href="inss_atraso.php">Orientações para recolhimento do INSS em atraso</a></li>
<li><a href="javascript:abreJanela('https://conectividade.caixa.gov.br', 'CONECTIVIDADE','width=800, height=600, top=150, left=150, scrollbars=yes, resizable=yes')">Conectividade Social</a></li>
</ul>
<br />

<h2>Imposto de Renda na Fonte</h2>
O DARF serve para o recolhimento da retenção de Imposto de Renda nos casos em que esta for devida. Nem toda empresa precisará pagar o DARF. O recolhimento deve ser feito somente quando o valor do pró-labore do sócio estiver acima do limite de isenção de IR (R$
<?= $Limite_Insencao ?>
), quando são contratados profissionais autônomos para serviços avulsos, ou no pagamento de serviços prestados por pessoas jurídicas à sua empresa (quando estas não forem também optantes pelo Simples).
<ul>
<li><a href="darf_orientacoes.php">Orientações para geração e envio do Darf</a></li>
</ul>
 <br />
 
  <h2>Taxas municipais</h2>
    
  Estas taxas e obrigações variam um pouco de acordo com o município. Normalmente é possível efetuar todos os procedimentos  pela internet, mas cada cidade possui seu próprio site e regulamentação própria para recebê-las. As obrigações exigidas são:<br />
    <ul>
    
    <li><strong>DES - Declaração Eletrônica de Serviços</strong><br />
    Trata-se de uma declaração que você transmite para a sua prefeitura, informando as notas de serviços prestadas e tomados no período. Com o advento da Nota Fiscal Eletrônica, em muitas cidades a DES foi extinta, como por exemplo em São Paulo, mas ainda permanece em  outros. Procure no site da prefeitura de sua cidade, se a DES é devida e como transmiti-la. </li>
    <br />
    
    <li><strong>NFTS - Nota Fiscal do Tomador de Serviços</strong><br />
    As cidades que não exigem a DES, muitas vezes pedem a
    Nota Fiscal do Tomador de Serviços. Nela  você informa apenas os serviços tomados de empresas de outras cidades. A NFTS é exigida por São Paulo e pode ser emitida no mesmo site em que você emite a nota eletrônica de serviços. <a href="https://contadoramigo.websiteseguro.com/nota_fiscal_tomador.php">Veja o tutorial</a>. </li>
    <br />
    
    <li><strong>TFE - Taxa de Fiscalização do Estabelecimento</strong><br />
      Esta taxa não existe em muitas cidades. Em outras  assume diferentes nomes. Normalmente  chega pelo correio no endereço de sua empresa. Se isto não tem ocorrido, procure se informar no site de sua prefeitura. Em São Paulo, é possível <a href="tfe_orientacoes.php">emitir as guias em atraso pela internet</a>.<br />
      <br />
    </li>
    <li><strong>TFA - 
      A Taxa de Fiscalização de Anúncio</strong><br />
      É devida por todas as empresas  que coloquem anúncio ou placas de identificação de sua atividade. Pode assumir diferentes nomes dependendo do município</li>
    <br />
    
    <li><strong>Alvará de funcionamento</strong><br />
      O Alvará  é obrigatório para qualquer tipo de estabelecimento. Para os estabelecimentos que elaborarem/comercializem produtos  alimentícios tais como Bares, Restaurantes, Lanchonetes, Hotéis e Similares, há  também a obrigatoriedade do Alvará  de Vigilância Sanitária.</li>
    </ul>

  Não seria possível montar um tutorial individualizado dessas obrigações  para cada um dos 5.564 municípios do Brasil, até porque os sites das prefeituras estão sempre em transformação. Mas são em geral obrigações simples de cumprir e se tiver alguma dificuldade, entre em contato conosco pelo Help Desk, para que possamos ajudá-lo.
  <br />
  <br />
<br />

  <h2>Contribuição Sindical</h2>
Segundo a portaria nº 10 de 06.01.2011 do ministério do trabalho, a<strong> Contribuição Sindical Patronal  é facultativa para empresas optantes pelo Simples</strong>. Ela, porém pode ser útil para prestar auxílio jurídico e para os processos de contratação e demissão de funcionários. Se você optar por pagá-la, deve escolher um sindicato que se adeque a sua atividade e solicitar o cadastramento. As contribuições são pagas em janeiro, ou no momento em que sua empresa é filiada e tem um custo relativamente baixo, proporcional ao número de funcionários.<br />
<br />
  A Contribuição Sindical Patronal não deve ser confundida com a<strong> Contribuição Associativa</strong>, que é paga mensalmente e é atribuída somente aqueles que optaram por tornarem-se sócios ativos 
  no sindicato, com direito a voto e participação em assembleias. Estes sócios também devem arcar com mais duas contribuições:
  <ul>
  <li><strong>Contribuição Confederativa</strong>, geralmente cobrada em agosto, e  destinada a financiar as confederações de sindicatos nos diferentes estados.</li>
  <li><strong>Contribuição Assistencial</strong>, cobrada por alguns sindicatos que por participarem de negociações coletivas têm despesas adicionais e precisam rateá-la com os associados</li>
  </ul>
<br />

<h2>Rais</h2>
Empresas optantes pelo Simples que não têm funcionários devem enviar a RAIS negativa. A Rais é um relatório que informa ao Ministério do Trabalho o giro de funcionários dentro de sua empresa. Ela deve ser enviada anualmente, mesmo que sua empresa não tenha funcionários registrados. A RAIS negativa do ano-base (o ano imediatamente anterior ao vigente) pode ser preenchida e enviada diretamente pela Internet. <br />
<br />
Se a sua empresa não entregou a RAIS dos anos anteriores, estará sujeita a multa.
<ul>
<li><a href="http://www.rais.gov.br/RAIS_SITIO/neg_gerenciador.asp" target="_blank">Envio da RAIS Negativa do ano-base</a></li>
<li><a href="rais_atrasada_orientacoes.php" target="_blank">Envio  de RAIS Negativa em atraso</a></li>
<li><a href="rais_retificacao_orientacoes.php" target="_blank">Retificação de RAIS enviada</a></li>
<li><a href="rais_recibo.php" target="_blank">Emissão do recibo de entrega da RAIS</a></li>
</ul>
<br />

<h2>Defis (Declaração de Informações Socioeconômicas e Fiscais)</h2>
A Defis veio substituir a DASN, extinta em 2012. Ela é gerada e enviada pela Internet, no site do Simples Nacional. O mesmo que você usa mensalmente para gerar o Documento de Arrecadação do Simples (DAS). O acesso se dá através do <a href="javascript:abreJanela('https://cav.receita.fazenda.gov.br/Scripts/CAV/CavExtCert.dll?Identificar?Sistema=05041', 'DASN', 'width=800, height=600, top=150, left=150, scrolling=yes')">E-CAC</a> (Centro Virtual de Atendimento).<br />
<ul>
<li><a href="defis_orientacoes.php">Orientações para geração e envio da Defis</a></li>
</ul>
<br />

<h2>Dirf (Declaração do Imposto de Renda Retido na Fonte)</h2>
Você só precisa enviar a <strong>DIRF</strong> se a sua empresa fez no último ano pelo menos uma  retenção de IR nos pagamentos de pró-labore,  remuneração a trabalhadores autônomos, pessoas jurídicas, ou no pagamento do aluguel. Neste documento você informará à Receita Federal os valores de cada uma das retenções realizadas e para quem foram feitas. Para preencher a DIRF, faça o download e instale o <a href="http://www.receita.fazenda.gov.br/PessoaJuridica/DIRF/2014/pgd2014.htm" target="_blank">programa gerador da DIRF 2014</a>. Baixe e instale também o <a href="http://www.receita.fazenda.gov.br/pessoafisica/receitanet/recnet.htm" target="_blank">Receitanet</a> (usado para transmitir o documento). Atente para  a versão adequada ao seu sistema operacional. <br />
<ul>
<li><a href="dirf_orientacoes.php">Orientações para geração e envio da Dirf</a></li>
</ul>
</div>
</div>
</div>
<?php include 'rodape.php' ?>

