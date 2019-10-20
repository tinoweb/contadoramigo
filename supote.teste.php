<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL); 

echo "envio - inicio";

// Realiza a requisição do arquivo que tem a classe phpmailer
require_once("classes/phpmailer.class.php");

	$mail = new PHPMailer();

	$mail->IsSMTP();
	$mail->SMTPDebug = 1;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Host = 'smtp.gmail.com';	// SMTP utilizado
	$mail->Port = 587;  		// A porta 587 deverá estar aberta em seu servidor	
	
	$mail->Username = "atano.jacinto@gmail.com";
	$mail->Password = "onata@draco@12";	
	
	$mail->AddReplyTo("atano.jacinto@gmail.com");
	$mail->From = "atano.jacinto@gmail.com";
	$mail->FromName = "Atano";
	
	$mail->Subject= 'Assunto';
	$mail->MsgHTML("mensagem");
	$mail->AddAddress("atano.jacinto@gmail.com");

	$mail->IsHTML(true); 

	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
	} else {
		$error = 'Mensagem enviada!';
	}
	
	print_r($error);

	phpinfo();
	
?>