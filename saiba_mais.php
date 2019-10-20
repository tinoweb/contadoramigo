<?php 
session_start();
$nome_meta = "saiba_mais";
?>
<?php if(isset($_SESSION["id_userSecao"])){?>
<?php include 'header_restrita.php' ?>
<?php } else { ?>
<?php include 'header.php' ?>
<?php } ?>


<div class="principal">


  <h1>Saiba Mais</h1>
  
  <h2 style="margin-bottom:30px">Principais serviços e obrigações fiscais atendidas pelo Contador Amigo.</h2>
  
 
  
<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/calendario.png" alt="Calendário Fiscal" title="Calendário Fiscal"width="71" height="71" style="float:left; margin-right:10px"  />
<div style="float:left; width:215px">
<h3 style="margin-bottom:0px">Calendário Fiscal</h3>
O calendário fiscal informa o vencimento de seus impostos e obrigações. Você receberá automaticamente lembretes  por e-mail  para   não esquecer nenhum compromisso.</div>
</div>
    
<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/imposto.png" alt="Impostos e Obrigações"  title="Impostos e Obrigações" width="71" height="71"  style="float:left; margin-right:10px"/>
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Impostos e Obrigações</h3>
Aqui você poderá gerar todas as suas guias para pagamento de  impostos e enviar os relatórios  exigidos pelos órgãos federais (GFIP, RAIS e DEFIS). Tudo online, sem complicações, inclusive para impostos em atraso.</div>
</div>   

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/lucros.png" alt="Distribuição de Lucros" title="Distribuição de Lucros" width="71" height="71"  style="float:left; margin-right:10px" />
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Distribuição de Lucros</h3>
Saiba em segundos o quanto pode retirar como distribuição de lucro presumido de sua empresa. Este é um rendimento isento de imposto de renda, uma excelente alternativa ao pró-labore.</div>
</div>
    
 
<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/checkup.png" alt="Check Up Fiscal" title="Check Up Fiscal" width="71" height="71"   style="float:left; margin-right:10px"/>
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Check up Fiscal</h3>
Você pode consultar diretamente  os órgãos federais, estaduais e municipais e saber se está com todos os seus impostos e obrigações em dia. Poderá também emitir as certidões negativas de débito que atestarão sua regularidade fiscal.</div>
</div>
  
<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/livro-caixa.png" alt="Livro Caixa"  title="Livro Caixa" width="71" height="71"   style="float:left; margin-right:10px"/>
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Livro Caixa</h3>
O Aplicativo de Livro Caixa do <strong>Contador Amigo </strong>manterá os registros contábeis de sua empresa  organizados. Você pode imprimi-lo sempre que quiser,  pesquisar  sua movimentação e visualizar gráficos de suas receitas e despesas.</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/contrato.png" alt="Alteração de Contrato" title="Alteração de Contrato" width="71" height="71"   style="float:left; margin-right:10px" />
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Alteração de Contrato</h3>
Através do Aplicativo de Alteração Contratual, você  poderá gerar seu novo contrato completo e consolidado em poucos minutos, ganhando tempo e evitando erros. Basta escolher o tipo de alteração, desejada.</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/graficos.png" title="Gráficos de entradas e saídas" alt="Gráficos de entradas e saídas" width="71" height="71"  style="float:left; margin-right:10px" />
<div style="float:left; width:215px">
  <h3 style="margin-bottom:10px">Gráficos de entradas e saídas</h3>
  Gráficos de movimentação financeira, segmentados por atividade, mostram de forma clara o desempenho de sua empresa ao longo do tempo. Isso permite que você faça projeções e planeje melhor o futuro.</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/prolabore.png" title="Recibo de Pró-labore" alt="Recibo de Pró-labore" width="71" height="71" style="float:left; margin-right:10px" />
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Recibo de Pró-labore</h3>
Emita facilmente seu recibo de pró-labore com os cálculos referentes ao INSS e Imposto de Renda Retido na Fonte, inclusive para aqueles que pagam pensão alimenticia.
</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/autonomo.png" title="Pagamento de Autônomos" alt="Pagamento de Autônomos" width="71" height="71" style="float:left; margin-right:10px"/>
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Pagamento de Autônomos</h3>
O aplicativo para pagamento de autônomos calcula automaticamente os impostos e retenções devidos e emite o RPA (recibo de prestação de autônomo) já preenchido, pronto para assinatura do trabalhador.

</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/helpdesk.png" alt="Help Desk" title="Help Desk" width="71" height="71"  style="float:left; margin-right:10px" />
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Help Desk</h3>
Se ainda houver algum serviço ou informação não contemplada no portal, você poderá recorrer ao Help Desk. Nossa equipe de pesquisadores buscará sua resposta de maneira ágil e precisa, para que você não fique nunca sem resposta.</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/estagiario.png" title="Contratação e pagamento de estagiários" alt="Contratação e pagamento de estagiários" width="71" height="71" style="float:left; margin-right:10px" />
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Contratação e pagamento de estagiários</h3>
Saiba como contratar legalmente um estagiário para sua microempresa. Emita os recibos de bolsa-auxílio e conheça melhor todos os detalhes da legislação.</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/aliquotas.png" title="Alíquotas de Impostos" alt="Alíquotas de Impostos" width="71" height="71" style="float:left; margin-right:10px" />
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Alíquotas de Impostos</h3>
Agora todas as empresas precisam discriminar em suas notas fiscais os impostos embutidos na composição do preço. Para ajudá-lo nesse processo, o Contador Amigo  calcula automaticamente as alíquotas incidentes sobre cada uma de suas atividades.</div>
</div>

<div style="float:left;  margin-bottom:30px; margin-right:15px; width:300px; height: 170px">
<img src="images/saibamais/outros.png" alt="Outros Serviços" title="Outros Serviços" width="71" height="71" style="float:left; margin-right:10px"/>
<div style="float:left; width:215px">
<h3 style="margin-bottom:10px">Outros Serviços</h3>
Em Outros Serviços você pode  emitir certidões negativas, efetuar alterações cadastrais junto aos órgão federais, estaduais e municipais, fazer consultas de regularidade no FGTS, INSS, na dívida ativa, emitir certificados de CNPJ, CCM e muito mais</div>
</div>

 <div style="clear:both; width:100%"> </div>
</div>






<?php include 'rodape.php' ?>
