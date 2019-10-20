<?php

setcookie('contadoramigoHTTPS','', time()-(120*120*24*30));
//setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.com.br', 0);
//setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.websiteseguro.com', 0);

// PEGA O NOME DO DOMINIO PELO SERVER NAME E REMOVER 'WWW.'.
setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', str_replace('www.', '', $_SERVER['SERVER_NAME']), 0);

unset($_COOKIE['contadoramigoHTTPS']);

session_start();

$emailx = $_REQUEST['email'];
$senhax = $_REQUEST['senha'];

//setcookie('contadoramigoADMIN', $emailx . " " . $senhax, time()+(120*120*24*30), '/', 'contadoramigo.com.br', 0);

// PEGA O NOME DO DOMINIO PELO SERVER NAME E REMOVER 'WWW.'.
setcookie('contadoramigoADMIN', $emailx . " " . $senhax, time()+(120*120*24*30), '/', str_replace('www.', '', $_SERVER['SERVER_NAME']), 0);

header("location: ../auto_login.php?admin");

?>