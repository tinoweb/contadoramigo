<?php 
session_start();
$nome_meta = "midia";
?>
<?php if(isset($_SESSION["id_userSecao"])){?>
<?php include 'header_restrita.php' ?>
<?php } else { ?>
<?php include 'header.php' ?>
<?php } ?>
<script language="JavaScript">
function validaForm(form) {	
	if (form.txtEmail.value==''){
		alert('Digite o Email.');
		form.txtEmail.focus();
	}else{		
		if (form.txtSenha.value==''){
			alert('Digite a Senha.');
			form.txtSenha.focus();
		}else{		
			form.submit();
		}
	}
}
</script>
<div class="principal">


<div class="titulo">Repercussão na mídia</div>
<h1 class="tituloVermelho" style="margin-bottom:20px">
O conceito "faça você mesmo" de contabilidade online chamou atenção da mídia.</h1>

<h2><a href="http://www1.folha.uol.com.br/mercado/2015/07/1658935-site-que-ensina-empresario-a-pagar-imposto-sozinho-e-legal-diz-justica.shtml?cmpid=compfb" target="_blank" class="linkMidia">Site que ensina empresário a pagar imposto sozinho é legal, diz justica</a></h2>
<div style="margin-bottom:5px; color:#666"><strong>Folha de São Paulo - 22/07/2015</strong></div>
<div style="margin-bottom:20px">O site Contador Amigo, que oferece ferramentas e instruções para que microempresários preencham e enviem obrigações fiscais por conta própria, poderá continuar funcionando e manter o nome, de acordo com decisão judicial. </div>

<h2><a href="http://www1.folha.uol.com.br/colunas/helioschwartsman/1262242-destruicao-criadora.shtml" target="_blank" class="linkMidia">Destruição criadora</a></h2>
<div style="margin-bottom:5px;color:#666"><strong> Folha - colunas - 14/04/2013</strong></div>
<div style="margin-bottom:20px">Deu na Folha que o site Contador Amigo, que ajuda microempresários a fazer sozinhos a contabilidade de suas firmas. </div>
 
<h2><a href="http://www1.folha.uol.com.br/mercado/1258872-site-que-promete-substituir-contador-e-alvo-de-processos.shtml" target="_blank" class="linkMidia">Site que promete substituir contador é alvo de processos</a></h2>
<div style="margin-bottom:5px;color:#666"><strong> Folha - Mercado - 08/04/2013</strong></div>
<div style="margin-bottom:20px">Com a promessa de levar os microempresários a realizar a contabilidade da empresa sozinhos, o site Contador Amigo se tornou alvo de quatro ações, promovidas por entidades representativas da classe contábil</div>

<h2><a href="https://www.youtube.com/watch?feature=player_embedded&v=tw7M-IZ1BqM#!" target="_blank" class="linkMidia">Assessoria gratuita - Via Legal</a></h2>
<div style="margin-bottom:5px; color:#666"><strong> Tv Cultura - 14/06/2013</strong></div>
<div style="margin-bottom:20px">Toda empresa deve ter um contador, certo? Nem sempre. </div>
 

<h2><a href="http://tv.pme.estadao.com.br/videos,site-cuida-da-sua-contabilidade,153713,,0.htm" target="_blank" class="linkMidia">Site cuida da sua contabilidade</a></h2>
<div style="margin-bottom:5px;color:#666"><strong> Estadão PME - 01/12/2011 </strong></div>
<div style="margin-bottom:20px">Vitor Maradei criou ferramenta que permite ao pequeno empresário cuidar da própria contabilidade. </div>

<h2><a href="http://tvuol.uol.com.br/video/microempresarios-podem-gerenciar-suas-proprias-contas-04028C1C3670D8892326/" target="_blank" class="linkMidia">Microempresários podem gerenciar suas próprias contas</a></h2>
<div style="margin-bottom:5px;color:#666"><strong> Uol - 11/10/2011 </strong></div>
<div style="margin-bottom:20px">Uma das grandes dificuldades do microempresário é organizar as contas de seu negócio. Por isso, muitas pessoas recorrem a um contador para colocar tudo em dia. Mas isso tem um custo e pode ser muito grande para sua empresa. </div>
 
<h2><a href="http://exame.abril.com.br/pme/noticias/site-permite-que-pequenos-facam-propria-contabilidade" target="_blank" class="linkMidia">Site permite que pequenos façam própria contabilidade</a></h2>
<div style="margin-bottom:5px;color:#666"><strong> Exame - 07/10/2011 </strong></div>
<div style="margin-bottom:20px">Apoiar os pequenos empreendedores para que eles possam fazer sua própria contabilidade. Essa é a proposta do site Contador Amigo, criado pelo empreendedor Vitor Maradei. </div>

<h2><a href="http://classificados.folha.com.br/negocios/978847-empreendedores-enfrentam-problemas-com-contadores.shtml" target="_blank" class="linkMidia">Empreendedores enfrentam problemas com contadores</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Folha - Negócios – 22/09/2011</strong></div>
<div style="margin-bottom:20px">Em 1996, Vitor Maradei abriu um estúdio multimídia e, desde então, relata, enfrentou dores de cabeça com contadores.<br />"Não pagavam impostos ou pagavam valores a mais, erravam o código da minha empresa. Já tive todo tipo de problema que você pode imaginar", afirma. </div>

<h2><a href="http://www.jornalempresasenegocios.com.br/negociosempauta_20_09_2011.html" target="_blank" class="linkMidia">Contador Amigo</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Empresas &amp; Negócios – 22/09/2011</strong></div>
<div style="margin-bottom:20px">Acaba de ser lançado em São Paulo o portal Contador Amigo, que permite ao microempresário gerir por conta própria pela internet a contabilidade de sua empresa. Consegue quitar impostos e cumprir com as obrigações fiscais, sem levantar da cadeira. O site possui tutoriais e aplicativos fáceis de usar que interpretam o complicado emaranhado de leis, normas e códigos do sistema tributário e informam ao usuário o que fazer, de maneira clara e objetiva.</div>

<h2><a href="http://www.jornalcontabil.com.br/v2/Contabilidade-News/1315.html" target="_blank" class="linkMidia">São Paulo sai na frente e lança portal Contador Amigo</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Portal Jornal Contábil – 22/09/2011</strong></div>
<div style="margin-bottom:20px">Acaba de ser lançado em São Paulo o portal Contador Amigo, que permite ao microempresário gerir por conta própria pela internet a contabilidade de sua empresa. Em poucos minutos, ele consegue quitar seus impostos e cumprir com todas suas obrigações fiscais, sem levantar da cadeira e, o que é melhor, gastando muito pouco. </div>

<h2><a href="http://www.fehospar.com.br/news_det.php?cod=6165" target="_blank" class="linkMidia">Microempresa já pode cuidar de sua própria contabilidade pela internet</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>FEHOSPAR – 22/09/2011</strong></div>
<div style="margin-bottom:20px">Uma ferramenta para ajudar o microempresário a gerir, por conta própria e pela internet, a contabilidade de sua empresa. Assim, a VAD – Estúdio Multimídia define o portal Contador Amigo, que acaba de colocar no ar.</div>

<h2><a href="http://documentmanagement.com.br/portal/2011/09/22/portal-e-desenvolvido-para-microempresarios-e-relacionamento-de-negocios/" target="_blank" class="linkMidia">Portal é desenvolvido para microempresários e relacionamento de negócios</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Portal Document Management – 22/09/2011</strong></div>
<div style="margin-bottom:20px">Acaba de ser lançado em São Paulo o portal Contador Amigo, que permite ao microempresário gerir por conta própria pela internet a contabilidade de sua empresa. Em poucos minutos, ele consegue quitar seus impostos e cumprir com todas suas obrigações fiscais, sem levantar da cadeira e, o que é melhor, gastando muito pouco.</div>

<h2><a href="http://www.sincopecas-go.com.br/iframe_noticias.php?id=16553" target="_blank" class="linkMidia">Empreendedores enfrentam problemas com contadores</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Portal Sinco Peças GO – 22/09/2011</strong></div>
<div style="margin-bottom:20px">Em 1996, Vitor Maradei abriu um estúdio multimídia e, desde então, relata, enfrentou dores de cabeça com contadores.<br />
"Não pagavam impostos ou pagavam valores a mais, erravam o código da minha empresa. Já tive todo tipo de problema que você pode imaginar", afirma.</div>

<h2><a href="http://www.metaanalise.com.br/inteligenciademercado/index.php?option=com_content&view=article&id=5639:contador-amigo-permite-autogestao-contabil-para-microempresarios&catid=11:estrategias&Itemid=360" target="_blank" class="linkMidia">Contador Amigo permite autogestão contábil para microempresários</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>metaAnálise – 22/09/2011</strong></div>
<div style="margin-bottom:20px">Portal oferece acesso gratuito por 30 dias.<br />
Acaba de ser lançado em São Paulo o portal Contador Amigo, que permite ao microempresário gerir por conta própria pela internet a contabilidade de sua empresa. O serviço promete ao usuário quitar impostos e cumprir obrigações fiscais de forma prática e barata.</div>

<!--<h2><a href="http://www.revistafator.com.br/ver_noticia.php?not=173813" target="_blank" class="linkMidia">São Paulo sai na frente e lança portal contador amigo</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Portal Fator Brasil – 21/09/2011</strong></div>
Microempresário paulista agora pode fazer sozinho a contabilidade da sua empresa. Acaba de ser lançado em São Paulo o portal Contador Amigo, que permite ao microempresário gerir por conta própria pela internet a contabilidade de sua empresa. </div>-->

<h2><a href="http://mercadoemacao.blogspot.com/2011/09/microempresa-ja-pode-cuidar-de-sua.html" target="_blank" class="linkMidia">Contador Amigo permite autogestão contábil para microempresários</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Mercado Em Ação – 21/09/2011</strong></div>
<div style="margin-bottom:20px">Uma ferramenta para ajudar o microempresário a gerir, por conta própria e pela internet, a contabilidade de sua empresa. Assim, a VAD – Estúdio Multimídia define o portal Contador Amigo, que acaba de colocar no ar. A novidade está disponível, inicialmente, para prestadoras de serviços localizadas em São Paulo, mas a expectativa e, em pouco tempo, expandir seu raio de abrangência e atender também clientes de outros Estados.</div>

<h2><a href="http://ramonritter.posterous.com/microempresa-ja-pode-cuidar-de-sua-propria-co" target="_blank" class="linkMidia">Microempresa já pode cuidar de sua própria contabilidade pela internet</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Portal Cognos Tech – 21/09/2011</strong></div>
<div style="margin-bottom:20px">Uma ferramenta para ajudar o microempresário a gerir, por conta própria e pela internet, a contabilidade de sua empresa. Assim, a VAD – Estúdio Multimídia define o portal Contador Amigo, que acaba de colocar no ar.</div>

<!--<h2><a href="http://www.executivosfinanceiros.com.br/noticias_mostra.asp?id=83645" target="_blank" class="linkMidia">Microempresário paulista agora pode fazer sozinho a contabilidade da sua empresa </a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Portal Executivos Financeiros – 21/09/2011</strong></div>
Foi lançado em São Paulo o portal Contador Amigo, que permite ao microempresário gerir por conta própria pela internet a contabilidade de sua empresa. Em poucos minutos, ele consegue quitar seus impostos e cumprir com todas suas obrigações fiscais, sem levantar da cadeira e, o que é melhor, gastando muito pouco.</div>-->

<h2><a href="http://blog.lideraonline.com.br/" target="_blank" class="linkMidia">Portal ajuda microempresários a fazer contabilidade da empresa</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Blog Liderança – 20/09/2011</strong></div>
<div style="margin-bottom:20px">Microempresário paulista agora pode fazer sozinho a contabilidade da sua empresa Acaba de ser lançado em São Paulo o portal Contador Amigo, que permite ao microempresário gerir por conta própria pela internet a contabilidade de sua empresa.</div>

<h2><a href="http://itweb.com.br/voce-informa/sao-paulo-sai-na-frente-e-lanca-portal-contador-amigo/#ir" target="_blank" class="linkMidia">São Paulo sai na frente e lança portal Contador Amigo</a></h2>
<div style="margin-bottom:5px;color:#666"><strong>Portal It Web – 20/09/2011</strong></div>
<div style="margin-bottom:20px">Microempresário paulista agora pode fazer sozinho a contabilidade da sua empresa.</div>


</div>

<?php include 'rodape.php' ?>
