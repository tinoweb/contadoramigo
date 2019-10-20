<?php

include 'session.php';
	session_destroy(); 
	session_start();
	$_SESSION["nome_userSecao"]="";
	$_SESSION["email_userSecao"]="";
	$_SESSION["id_userSecao"]="";
	$_SESSION['IsAuthorized']="";
	$mensagem = "";

	eraseCookie('contadoramigo');

	//header('Location: http://www.contadoramigo.com.br/index.php' );
	//header('Location: http://www.contadoramigo.com.br/auto_login.php?logout' );
	$URI = explode($_SERVER['SCRIPT_URL'], $_SERVER['SCRIPT_URI']);
    header('Location: '.$URI[0].'/auto_login.php?logout' );

?>