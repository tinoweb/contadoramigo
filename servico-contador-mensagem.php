<?php
	
	session_start();

	// Apaga a Sessão usadas para gerar o boleto.
	unset($_SESSION['tipo']);
	unset($_SESSION['valor']);
	unset($_SESSION['data']);
	unset($_SESSION['id_user']);

	if (isset($_SESSION["id_userSecao"])) {
		$header = 'header_restrita.php';					  
	} else {
		$header = 'header.php';
	} 

	if(isset($_SESSION['servico_erro'])) {
		$mensagem = $_SESSION['servico_erro'];
		unset($_SESSION['servico_erro']);
	} else {
		header('Location: /servico-contador.php');
	}

	require_once($header);
?>

<div class="principal minHeight">

  <div class="titulo" style="margin-bottom:10px;">Serviços com Contador</div>
  <div class="tituloVermelho"><?php echo $mensagem;?></div>
  <div style="margin-bottom:20px"></div>
 
 </div>

<?php include 'rodape.php' ?>
