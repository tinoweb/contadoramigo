<?php 
session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {
	$nome_meta = "pert";
	include 'header.php';} 
?>

<div class="principal">

	<h1>PERT-SN <span style="font-size: 16px">(O Refis do Simples)</span></h1>
	
	<h2>Programa Especial de Regularização Tributária das PME optantes pelo Simples Nacional</h2>
 
  Se a sua empresa está cheia de dívidas, este é o momento para regularizá-la! Através do PERT-SN é possível liquidar à vista ou parceladamente seus débitos no Simples Nacional, inclusive os da dívida ativa, e obter ainda um significativo abatimento da multa e juros de mora. Confira as condições:<br><br>


  <span class="tituloAzul">Modalidades de parcelamento</span><br><br>
  
  
    <!--ballon-->
<div style="float: right; margin-left: 20px">
<div style="float:right"><img src="images/boneca-assinatura.png" alt="Nosso contador faz a abertura para você" width="110" title="Nosso contador faz a abertura para você."/></div>    
<div class="bubble_right_top" style="width:200px; float:right">
<div style="padding:15px">
<div class="tituloAzul">Precisa de ajuda?</div>
Contrate nosso <a href="https://www.contadoramigo.com.br/regularizacao.php">Serviço Avulso de Regularização</a> para deixar sua empresa em ordem e solicitar a adesão ao PERT-SN. 
</div>
</div>
<div style="clear: both"></div>
</div>
	
<!--fim do ballon-->
  
  
  
  
	A empresa devedora  deverá pagar inicialmente 5% do valor da dívida total, sem reduções, em até cinco parcelas mensais e sucessivas. O restante poderá ser quitado em uma das das seguintes formas:
	<ol>
		<li class="#"> Pagamento à Vista, com redução de 90% dos juros e 70% das multas</li>
		<li  class="#"> Pagamento em  até 145 parcelas mensais e sucessivas, com redução de 80% dos juros e 50% das multas</li>
		<li class="#">Pagamento em até 175 parcelas mensais e sucessivas, com redução de 50% dos juros  e 25% das multas</li>
	</ol>
	
	<strong>Observações Importantes:</strong><br>
  <ul>
	<li>Só poderão ser negociadas as dívidas vencidas em 2017</li>
	<li>A regularização não inclui dívidas previdenciárias (GPS) </li>
	<li>A parcela mínima das mensalidades deve ser de R$ 300</li>
	<li>Todas as apurações do Simples já precisam estar feitas e as DAS geradas para que a dívida possa ser incluída
  </ul>
<br>

<span class="tituloAzul">Como aderir ao PERT-SN</span><br><br>

Por enquanto está regulamentada apenas o parcelamento dos débidos <strong>já incluídos na dívida ativa</strong>:<br><br>


A adesão ao Pert-SN ocorrerá mediante requerimento a ser realizado exclusivamente por meio do <a href="https://www2.pgfn.fazenda.gov.br/ecac/contribuinte/login.jsf" target="_blank">Portal da PGFN</a>, opção “Programa Especial de Regularização Tributária - Simples Nacional”, disponível na opção “adesão ao parcelamento”, no período de 02/05/2018 até as 21h00 do dia 09/07/2018.<br>
<ul>
	<li>No momento da adesão, você deverá indicar as inscrições em Dívida Ativa da União que serão incluídas no parcelamento.</li>
	<li>Serão necessariamente incluídas no Pert-SN todas as competências dos débitos que compõem as inscrições em Dívida Ativa da União indicadas.</li>
	<li>O deferimento do pedido de adesão ao Pert-SN fica condicionado ao pagamento do valor à vista ou da primeira prestação, conforme o caso, o que deverá ocorrer até o último dia útil do mês do requerimento de adesão.</li>
</ul>
Se você precisar de ajuda para fazer a regularização de sua empresa e solicitar a adesão ao PERT-SN, contrate nosso <a href="https://www.contadoramigo.com.br/regularizacao.php">serviço avulso de regularização</a><br>
<br>

 
	<div class="quadro_branco"> <span class="destaque">Debitos ordinários:</span> Os débitos não presentes na dívida ativa poderão ser parcelados assim que a Receita Federal regulamentar o processo e disponibilizar o sistema para envio. A previsão é que ocorra no início de junho/2018.</div>
	<br>
<br>

<span class="tituloAzul">Desistência de parcelamentos anteriores</span><br><br>
Se você já têm parcelamento em curso, mas quer aproveitar o corte dos juros e multas oferecidos pelo Pert-SN, deverá formalizar a desistência do parcelamento anterior. Isso só pode ser feito pelo <a href="https://www2.pgfn.fazenda.gov.br/ecac/contribuinte/login.jsf" target="_blank">Portal da PGFN</a>, opção “Desistência de Parcelamentos”. Cuidado! Este não é o E-CAC onde você efetuou o parcelamento. Trata-se de outro site. Se o pedido de adesão ao Pert-SN, por alguma razão, não puder ser concluído, não será possível restabelcer o parcelamento atual.<br>
<br><br>



<span class="tituloAzul">Débitos em discussão judicial</span><br><br>
Para incluir no Pert-SN débitos que se encontrem em discussão judicial, a empresa deverá desistir previamente das ações judiciais que tenham por objeto os débitos que serão quitados; renunciar a quaisquer alegações de direito sobre as quais se fundem as ações judiciais; e protocolar requerimento de extinção do processo com resolução do mérito. <br><br><br>


Para mais informações acesse a <a href="http://normas.receita.fazenda.gov.br/sijut2consulta/link.action?visao=anotado&idAto=91726" target="_blank">Portaria PGFN nº 38</a> ou consulte nosso <a href="suporte.php">help Desk</a>. 

</div>
<?php include 'rodape.php' ?>
