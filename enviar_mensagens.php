<?
	//Componente de Envio de e-mail.
	$nomeAssinante = $_REQUEST['nomeMensagem'];
	$emailuser = $_REQUEST['emailMensagem'];
	$assuntoMail = $_REQUEST['assuntoMensagem'];
		
	include 'mensagens/' . $_REQUEST['arquivoMensagem'] . '.php';
	
	//$mensagemHTML = 'teste';
	include 'mensagens/componente_envio_novo.php';

	//echo $emaildestinatario . " - " . $assunto . " - " . $headers  . " - " .  "-r".$emailsender;
	echo "Mensagem Enviada!";

?>