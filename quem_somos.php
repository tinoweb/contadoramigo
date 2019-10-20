<?php 

session_start();
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {
	$nome_meta = "quem_somos";
	include 'header.php';} 
?>

<script language="JavaScript">

</script>
<div class="principal">
	<H1>Quem Somos</H1>
  
  
  
  <H2>Um sistema contábil realizado por microempreendedores cansados da burocracia.</H2>
  
<div style="float:right; margin-left:10px; margin-bottom:10px; width: 50%"><img src="images/vitor.jpg" width="100%" alt="Vitor Maradei - CEO e Fundador" title= "Vitor Maradei - CEO e Fundador do Contador Amigo" style="margin-bottom:5px; border-style:solid; border-color:#CCC; border-width:1px"/><br />
<span style="font-size:80%; font-style:italic">Vitor Maradei - CEO e fundador do Contador Amigo</span></div>O <strong>Contador Amigo</strong> é produzido e administrado pela <a href="http://www.vad.com.br">VAD - Estúdio Multimídia Ltda</a>, uma das agências pioneiras em projetos para a web no Brasil. Depois de 15 anos desenvolvendo sites corporativos, portais, intranets, sistemas de e-learning, e-commerce, para clientes nacionais e internacionais, a VAD, nesta iniciativa, cria seu primeiro projeto próprio, voltado diretamente ao mercado.<br />
      <br />
      A ideia  surgiu  das dificuldades enfrentadas pela própria agência no cumprimento de suas obrigações tributárias.   Embora com uma grande bagagem profissional, a <strong>VAD</strong> é também uma empresa de pequeno porte e sofre na pele os problemas vividos por seu segmento. O novo sistema tem por objetivo dar ao microempresário o controle sobre sua situação fiscal e a possibilidade de realizar por conta própria a contabilidade de seu negócio.<br /><br>

      
	<h3>Foco na usabilidade</h3>
     
      A equipe responsável pela gestão do sistema  é composta por jornalistas, comunicadores e especialistas em usabilidade. Este grupo, assessorado por contadores e advogados, tem a incumbência de traduzir o conteúdo  jurídico-contábil, para uma linguagem  mais clara e fácil de entender, vasculhar os meandros das leis fiscais e desembaraçar os nós burocráticos para mostrar a você como cumprir suas obrigações tributárias de forma rápida, simples e segura. <br />
      <br />
      Para isso, visitamos pessoalmente os órgãos municipais, estaduais e federais. Verificamos na prática as dificuldades possíveis para cada tipo de demanda fiscal: pagamentos de taxas em atrasos, obtenção de certidões negativas, alterações contratuais, envio de relatórios, etc. Uma verdadeira maratona, cheia de idas e vindas, até alcançarmos a resposta necessária.<br />
      <br />
    Trata-se, porém, de um trabalho permanente, que inclui o acompanhando das mudanças constantes nas leis e as transformações nos processos administrativos. O assinante é também parte fundamental nesse processo. Sempre que uma necessidade sua  não estiver  prevista no sistema, nossa equipe se mobilizará para fornecer-lhe a resposta no menor tempo possível, através do <em>Help Desk</em> e incluirá essa informação no sistema dali para frente. Esperamos com isso contribuir para dar maior transparência à legislação fiscal do país e auxiliar a todos na construção de um país mais moderno e eficiente, em todos os sentidos. Junte-se a nós!
   


</div>




<?php include 'rodape.php' ?>
