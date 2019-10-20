<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL); 

	session_start();

	// Pega o id do usuario para poder fazer o envio do email.
	$userId = $_SESSION['id_user'];
	$tipo = $_SESSION['tipo'];

	// Apaga a Sessão usadas para gerar o boleto.
	unset($_SESSION['tipo']);
	unset($_SESSION['valor']);
	unset($_SESSION['data']);
	unset($_SESSION['id_user']);
	unset($_SESSION['contratoId']);
	
	if (isset($_SESSION["id_userSecao"])) {
		$header = 'header_restrita.php';					  
	} else {
		$header = 'header.php';
	}

	if(isset($_SESSION['pagamento'])) {
		$tipoPagamento = $_SESSION['pagamento'];
		unset($_SESSION['pagamento']);
	} else {
		unset($_SESSION['LinkBoleto']);
		header('Location: /servico-contador.php');
	}

	require_once($header);

	// Realiza a requisição dos dados do login
	require_once('Model/Login/LoginData.php');

	// Realiza a requisição do arquivo responsavel peloas dados de serviço avulso. 
	require_once("Model/DadosServicosAvulso/DadosServicosAvulsoData.php");

	// Realiza a requisição do arquivo responsavel por fazer o envio do email.
	require_once('EnvioEmail.php');

?>

<div class="principal minHeight">

  <div class="titulo" style="margin-bottom:10px;">Serviços com Contador</div>
  <div class="tituloVermelho">Contratação efetuada com sucesso!</div>
  <div style="margin-bottom:20px">
  </div>
<div style="font-size: 14px; margin-bottom: 20px;">

<?php if($tipoPagamento == 'Cartao'):?>

	Nosso contador parceiro entrará em contato por telefone para discutir detalhes relativos ao serviço.<br>

Em caso de dúvida, contate-nos pelo <a href="suporte.php">Help desk.</a>
<?php else:?>

	Tão logo seu boleto seja compensado, nosso contador parceiro entrará em contato por telefone para discutir detalhes relativos ao serviço.<br>

Em caso de dúvida, contate-nos pelo <a href="suporte.php">Help desk.</a>

<?php endif;?>

</div>

<?php
	// verifica se pode ser impresso o boleto.
	if(isset($_SESSION['LinkBoleto']) && !empty($_SESSION['LinkBoleto'])){
		$linkBoletoJS = $_SESSION['LinkBoleto'];
		unset($_SESSION['LinkBoleto']);
		
		echo '<iframe width="966" height="1030" src="'.$linkBoletoJS.'" frameborder="0" allowfullscreen></iframe>';
		
		//echo "<script>abreJanela('".$linkBoletoJS."','_blank','width=700,height=600,top=150,left=150,scrollbars=yes,resizable=yes,marginwidth=0,marginheigth=0');</script>";
	}

	// Realiza a chamada da classe para dar inicio ao envio do email.
	$envioEmail = new EnvioEmail();
	$login = new LoginData();
	
	// Instancia a classe que manipula os dados.
	$dadosServicosAvulso = new DadosServicosAvulsoData();

	// Pega os dados do serviço.
	$servicoNome = $dadosServicosAvulso->PegaNomeServicosTipo($tipo);
	
	// Pega os dados do usuário pelo Id.
	$dadosLogin = $login->GetDataLogin($userId);
	
	$nome = $dadosLogin->getAssinante();
	$email = $dadosLogin->getEmail();
	$destino = $dadosLogin->getEmail();
	$assunto = 'Serviço avulso contratado';
	$tipoMensagem = 'ContratacaoServico';

	// Define o texto como caixa baixa e remove tag html. 
	$servico = array('servico'=>strtolower(strip_tags($servicoNome->getNome())));

	// Realiza achamada do envio.
	$statusEnvio = @$envioEmail->PreparaEnvioEmail($nome, $email, $destino, $assunto, $tipoMensagem, $servico);	

 ?>

</div>




<?php include 'rodape.php' ?>
