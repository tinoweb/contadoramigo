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
	<div style="background-color: #024a68; padding: 10px; color: white; line-height: 100%; margin-bottom: 0px; margin-bottom: 20px; font-size: 150%">Plano Premium</div>
  
  <img src="#" style="width: 300px; height: 250px; float: right; margin-left: 20px; background-color: black"><H1 style="color: #024a68">Um contador gera as guias e declarações para você</H1>
  
<div style="font-size: 120%">0 <strong>Plano Premium</strong> é ideal para você que não tem tempo para nada, mas <strong>não quer abrir mão da economia</strong>. A contabilidade de sua empresa é gerenciada a distância por um contador profissional, registrado no CRC. <br>
<br>

Mensalmente você <strong>receberá as guias dos impostos por e-mail</strong>. Seu único trabalho será cadastrar mensalmente no <strong>Contador Amigo</strong> o pró-labore, pagamentos a autônomos (se houver) e manter o livro-caixa da empresa atualizado. 
<br><br>

Mas <strong>não queremos que você se precipite</strong> na escolha do plano. <strong>Vamos lhe dar 30 dias de acesso grátis ao plano Standard</strong>. Aproveite para explorar o portal e avaliar se é fácil cuidar sozinho da sua própria contabilidade, ou se prefere mesmo que um contador faça tudo para você.<br>
<br>

Que tal assim? Então vamos lá! Comece agora mesmo. <strong>Você tem 1 mês grátis para testar o nosso portal</strong>. A qualquer momento poderá fazer sua opção por um dos planos e confirmar sua assinatura.<br><br>

Vale lembrar que <strong>Contador Amigo</strong> funciona também como uma <strong>excelente ferramenta de gestão</strong>, com tabelas e gráficos para você acompanhar o desempenho de seu negócio. <br>
<br><br>

 <a class="nulo" href="faca-voce-mesmo-a-contabilidade-de-sua-empresa.php"><div style="text-align: center; font-family:'Varela Round', sans-serif; -o-border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 10px; background-color: #024a68; border: 0; cursor: pointer; color: #FFF; padding: 10px 20px 5px 20px; min-height: 25px; width: 35%; margin: auto; font-size: 130%">Acesse grátis e veja como funciona</div></a>




</div>




<?php include 'rodape.php' ?>
