<?php 

session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {
	$nome_meta = "contabilidade-online";
	include 'header.php';} 
?>

<script language="JavaScript">

</script>
<div class="principal">
	<H1>Contabilidade Online</H1>
  
  
  
  <H2>Afinal o que é, como funciona e para que serve a contabilidade online?</H2>
  
<div style="float:right; margin-left:10px; margin-bottom:10px; width: 50%; max-width: 304px"><img src="images/vitor.jpg" width="100%" alt="Vitor Maradei - CEO e Fundador" title= "Vitor Maradei - CEO e Fundador do Contador Amigo" style="margin-bottom:5px; border-style:solid; border-color:#CCC; border-width:1px"/><br />
<em><a href="https://www.linkedin.com/in/vitormaradei/" target="_blank">Vitor Maradei</a> - CEO e fundador do Contador Amigo</em></div>

Um dos grandes papéis da tecnologia é transformar processos complexos, que antes demandavam muitos profissionais e horas de pesquisa, em atividades corriqueiras ao alcance de todos. Não é à tôa que, o <strong>Contador Amigo</strong>, <strong>a primeira contabilidade online do Brasil</strong>, surgiu não por inicativa de profissionais da área contábil, mas por profissionais de TI, cansados da burocracia.<br>
<br>


O nosso portal foi criado em 2012 com a proposta de sistematizar o pagamento de impostos e a entrega de declarações de forma que o próprio microempreendedor pudesse cumprir com suas obrigações fiscais. A iniciativa teve grande repercussão na mídia e não tardou em chamar a atenção do Conselho Regional de Contabilidade e de outros organismos da classe contábil que se manifestaram radicamente contra o projeto. Estas entidades entraram com diversas ações judiciais tentando impedir a disseminação da <strong>contabilidade online</strong>.<br>
<br>
Depois de uma longa contenda jurídica, o Contador Amigo finalmente saiu vencedor e o site pode continuar com sua missão de desburocratizar a vida do microempreendedor brasileiro, proporcionando economia, praticidade e, principalmente, transparência eu suas questões tributárias. A sistematização do pagamento de impostos, porém, é um processo que exige um acompanhamento permanente da legislação, bem como o desenvolvimento contínuo de tutoriais e aplicativos capazes de analisar as particularidades de cada empresa e dialogar com os sistemas online dos órgãos Federal estadual e municipal. Por isso nosso portal está em constante modificação.<br>
<br>

 Com o sucesso alcançado pelo <strong>Contador Amigo</strong>, logo apareceram novos sites, também auto-intitulados de contabilidade online. Porém apenas disponibilizam um mecanismo de venda online de serviços contábeis e utilizam ferramentas de comunicação à distância, a fim de massificar o atendimento. Por trás desta interface "tecnológica" está ainda o mesmo escritório contábil de sempre, com contadores executando manualmente o serviço.<br>
<br>
Embora o próprio <strong>Contador Amigo</strong> hoje também disponha do plano Premium, em que um contador parceiro gera as guais e declarações para o cliente, o site segue fiel à sua proposta de sistematizar a legilação tributária brasileira e permitir que, mesmo uma uma pessoa comum sem qualquer conhecimento contábil, consiga gerar as guias de seus impostos, fazer alterações contratuais ou preencher suas declarações.<br>
<br>
	<div class="tituloVermelho">Saiba Mais</div>
	<ul>
	<li><a href="saiba_mais.php">Serviços oferecidos pelo Contador Amigo</a></li>
	<li><a href="duvidas-frequentes.php">Entenda como funciona a contabilidade online do Contador Amigo e tire suas dúvidas</a></li>
	<li><a href="midia.php">Mídia sobre o Contator Amigo desde o seu lançamento</a></li>
	</ul>
</div>


<?php include 'rodape.php' ?>
