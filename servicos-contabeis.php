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
	<div style="background-color: #a61d00; padding: 10px; color: white; line-height: 100%; margin-bottom: 0px; margin-bottom: 20px; font-size: 150%; width: 20%">Serviços Avulsos</div>
  
  
  
 <H1 style="color: #a61d00">Contrate o serviço que precisa, na hora e sem complicação </H1>
  

   <div style="font-size: 120%">Excepcionalmente você pode precisar de serviços que requeiram a assinatura de um contador, ou simplesmente prefirir que determinada tarefa seja realizada por um profissional da área. Para casos como este, o Contador Amigo dispõe de uma rede de contabilistas parceiros, seguindo uma tabela de preços fixos, que você pode contratar diretamente aqui pelo nosso site. Veja a seguir quais são os serviços disponíveis e contrate-os agora mesmo.



</div>




<?php include 'rodape.php' ?>
