﻿<?
if(($_SESSION['status_userSecao']=='inativo') or ($_SESSION['status_userSecao']=='demoInativo')) {
	if((substr($_SERVER['REQUEST_URI'],0,16) == '/minha_conta.php') or (substr($_SERVER['REQUEST_URI'],0,12) == '/suporte.php') or (substr($_SERVER['REQUEST_URI'],0,23) == '/suporte_visualizar.php')) { //lista de páginas que o usuário inativo possui acesso		  
	} else {
		
	$URI = explode($_SERVER['SCRIPT_URL'], $_SERVER['SCRIPT_URI']);
	header('Location: '.$URI[0].'/minha_conta.php' );
	
	}
}

if (!isset($_SESSION["idSecao"])){
	$_SESSION['url'] = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	
	$URI = explode($_SERVER['SCRIPT_URL'], $_SERVER['SCRIPT_URI']);
	header('Location: '.$URI[0].'/index.php' );
}

$painelLogin = "Conectado a: <b>" . $_SESSION["nome_userSecao"] . "</b> | <a class=\"linkCinza\" href=\"auto_login.php?logout\">Sair</a>";
?>