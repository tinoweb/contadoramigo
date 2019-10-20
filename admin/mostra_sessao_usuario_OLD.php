<?php

setcookie('contadoramigoHTTPS','', time()-(120*120*24*30));
setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.com.br', 0);
setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.websiteseguro.com', 0);
unset($_COOKIE['contadoramigoHTTPS']);


session_start();
//include '../conect.php';
//include '../session.php';
//include 'check_login.php';

	$emailx = $_REQUEST['email'];
	$senhax = $_REQUEST['senha'];

//	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30));
//	setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', 'contadoramigo.com.br', 0);

	setcookie('contadoramigoADMIN', $emailx . " " . $senhax, time()+(120*120*24*30), '/', 'contadoramigo.com.br', 0);

//setcookie("contadoramigo", $emailx . " " . md5($senhax), time()+(60*60*24*30), "/", "", 0);

//echo $_COOKIE['contadoramigo'];
//exit;
//<form name="login" id="autologin" action="../auto_login.php?login&cookie" method="post" style="display:inline;">
//<input type="hidden" value="$emailx" name="txtEmail" id="txtEmail">
//<input type="hidden" value="md5($senhax)" name="txtSenha" id="txtSenha">
//</form>
//<script> document.getElementById('autologin').submit();/script>

//header("location: ../auto_login.php?login&cookie");
header("location: ../auto_login.php?admin");

?>