<?php
session_start();
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
//Caso não exista uma sessão ele sera redirecionada para tela de serviços
if(!isset($_SESSION['tarifaPadrao']) || ($_SESSION['tarifaPadrao'] != 'sim' && $_SESSION['tarifaPadrao'] != 'nao')){
	header('Location: /servico-contador.php');
}

//inclui o cabecalho
if (isset($_SESSION["id_userSecao"])) {include 'header_restrita.php';}
else {
	$nome_meta = "index";
	include 'header.php';} 
?>



<div class="principal minHeight">
  

  	<H1>Serviços Avulsos</H1>
  	<img id="ilustracao" src="images/regularizacao_ilustra2.png" width="350" height="350" alt="Regularize-se" style="margin-bottom: 10px; margin-left: 20px; float: right"/><H2>Regularização de Empresa</H2>
	<div style="font-size: 14px">
<?php if($_SESSION['tarifaPadrao'] == 'nao'):
	unset($_SESSION['tarifaPadrao']); ?>
		
 		Seus dados foram enviados com sucesso!<br>
 		<br>
 		Em até 24 horas enviaremos um orçamento sem compromisso para a regularização de sua emrpresa.<br>
 		<br>
 		Em caso de dúvida, não hesite em nos contatar.<br><br>
<?php /*else: 
	unset($_SESSION['tarifaPadrao']); 
	
	Seus dados foram enviados com sucesso!<br>
	<br> */?>
		
			
<?php endif; ?>
			
<strong>Telefone:</strong> (11) 3434 6631<br>
<strong>WhatsApp:</strong> (11) 9 9783 2475<br>
<strong>E-mail:</strong> <a href="mailto:servicos@contadoramigo.com.br">servicos@contadoramigo.com.br</a></div>





</div>

<?php include 'rodape.php' ?>
