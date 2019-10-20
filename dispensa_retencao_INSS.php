<?php include 'header_restrita.php' ?>

<?
$Teto_Previdenciario = ((string)number_format((float)str_replace(',','.',str_replace('.','',$Teto_Previdenciario)) * 2, 2,',','.'));
?>

<div class="principal">

  <h1>Cessão de Mão-de-Obra ou Empreitada</h1>
  <h2>Dispensa da Retenção de INSS</h2>
  <div style="margin-bottom:20px">
Não será necessário fazer a retenção do INSS para as atividades em que há cessão de mão-de-obra ou empreitada nos seguintes casos:
  <ul>
<li>Quando o valor da retenção for inferior R$ 10<br />
  <br />
</li>

<li>Se o serviço for prestado pessoalmente pelo titular ou sócio e o seu faturamento do mês anterior for igual ou inferior a R$ <?=$Teto_Previdenciario?>. Neste caso, é preciso enviar à empresa contratante <a href="downloads/declaracao_nao_possui_empregados.docx">esta declaração</a>. <br />
  <br />
</li>

<li>Quando a contratação envolver somente serviços profissionais relativos ao exercício de profissão regulamentada por legislação federal (dentre outros, administradores, advogados, aeronautas, aeroviários, agenciadores de propaganda, agrônomos, arquitetos, arquivistas, assistentes sociais, atuários, auxiliares de laboratório, bibliotecários, biólogos, biomédicos, cirurgiões dentistas, contabilistas, economistas domésticos, economistas, enfermeiros, engenheiros, estatísticos, farmacêuticos, fisioterapeutas, terapeutas ocupacionais, fonoaudiólogos, geógrafos, geólogos, guias de turismo, jornalistas profissionais, leiloeiros rurais, leiloeiros, massagistas, médicos, meteorologistas, nutricionistas, psicólogos, publicitários, químicos, radialistas, secretárias, taquígrafos, técnicos de arquivos, técnicos em biblioteconomia, técnicos em radiologia e tecnólogos). Neste caso é preciso enviar à empresa contratante <a href="downloads/declaracao_profissao_tecnica.docx">esta declaração</a>.<br />
  <br />
</li>

<li>Serviços de treinamento prestados pessoalmente pelos sócios, sem o envolvimento de empregados ou outros contribuintes individuais<br />
  <br />
</li>

<li>Serviços derivados da construção civil, tais como:
<ul>
<li>Administração, fiscalização, supervisão ou gerenciamento de obras</li>
<li>Assessoria ou Consultoria técnica</li>
<li>Controle de qualidade materiais</li>
<li>Fornecimento de concreto usinado, de massa asfáltica ou de argamassa usinada ou preparada</li>
<li>Jateamento ou hidrojateamento</li>
<li>Perfuração de poço artesiano</li>
<li>Elaboração de projeto da construção civil</li>
<li>Ensaios geotécnicos de campo ou de laboratório (sondagens de solo, provas de carga, ensaios de resistência, amostragens, testes em laboratório de solos ou outros serviços afins)</li>
<li>Serviços de topografia</li>
<li>Instalação de antena coletiva</li>
<li>Instalação de aparelhos de ar condicionado, de refrigeração, de ventilação, de aquecimento, de calefação ou de exaustão</li>
<li>Instalação de sistemas de ar condicionado, de refrigeração, de ventilação, de aquecimento, de calefação ou de exaustão, quando a venda for realizada com emissão apenas da nota fiscal de venda mercantil </li>
<li>Instalação de estrutura metálica, de equipamento ou de material, quando a venda for realizada com emissão apenas da nota fiscal mercantil</li>
<li>Locação de máquinas, de ferramentas, de equipamentos ou de outros utensílios sem fornecimento de mão-de-obra</li>
<li>Fundações especiais<br />
  <br />
</li>
</ul>

<li>Serviços de vigilância ou segurança prestados por meio de monitoramento eletrônico</li>
</ul>
Para maiores detalhes, consulte os artigos 145, 148 e 170 da <a href="http://www3.dataprev.gov.br/sislex/paginas/38/MPS-SRP/2005/3completa.htm" target="_blank">Instrução Normativa MPS/SRP Nº 3 de 14/07/2005</a> </div>
  

</div>


<?php include 'rodape.php' ?>
