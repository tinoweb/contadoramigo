<?php

$emailsender = "secretaria@contadoramigo.com.br"; 
$nomeSender  = "Contador Amigo"; 

// Passando os dados obtidos pelo formulário para as variáveis abaixo
$nomeremetente     = 'Contador Amigo';
$emailremetente    = $emailsender;

if($emailPessoal!=''){
	$emailremetente = $emailPessoal;
}

$emaildestinatario = $emailuser;

session_start();

// instanciado a classe phpmailer
require("classes/phpmailer.class.php");
$mail = new PHPMailer();

$mail->IsSMTP(); // telling the class to use SMTP
	
$mail->AddReplyTo($emailremetente);

$mail->From = $emailsender;

$mail->FromName = $nomeSender;

$mail->Subject= $assuntoMail;

$mail->MsgHTML($mensagemHTML);

$mail->AddAddress($emaildestinatario);

if($comcopia != ""){
	$mail->AddCC($comcopia);
}
if($comcopiaoculta != ""){
	$mail->AddBCC($comcopiaoculta);
}

$mail->IsHTML(true); 

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

$testeSendMail = @$mail->Send();
?>