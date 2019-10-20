<?php
//mandar automaticamente os erros para o console
require_once('./classes/class.consoleLog.php');
require_once('./classes/class.util.php');
require_once('./classes/phpmailer.class.php');
 
$arquivo = "nome_arquivo.txt";

$ext = strtolower(strrchr($arquivo,"."));
 
 
echo $ext;

/*
 
$console = new consoleLog();
 
$remetente = array('webmaster@contadoramigo.com.br', 'Nome do Remetente');
$destinatario = array('fabio.ribeiro@gmail', 'Nome do Destinatário');
$assunto = 'Email de Teste';
$template = 'mensagens/cartao_erro.php';
$variaveis = array(
	'nome' => 'Rafael Santos Sá',
	'mensagem' => 'Lorem Ipção!'
	);
 
if( Util::enviar_email($remetente, $destinatario, $assunto, $template, $variaveis) ){
	echo 'email enviado';	
}else{
	echo 'email não enviado';
}
*/