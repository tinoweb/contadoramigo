<?
$valor = $_REQUEST['valorCookie'];

if($valor == 'false'){
	$valor = '';
}

setcookie($_REQUEST['nomeCookie'], $valor, (time() + (5 * 24 * 3600)));

/*
setcookie('cookiesPreenchidos', str_replace($_REQUEST['nomeCookie'],'',$_COOKIE['cookiesPreenchidos']));

setcookie('cookiesPreenchidos',$_REQUEST['nomeCookie'] . ';' . $_COOKIE['cookiesPreenchidos']);

echo $_COOKIE['cookiesPreenchidos'] . '->' . str_replace($_REQUEST['nomeCookie'],'###',$_COOKIE['cookiesPreenchidos']);



echo $_COOKIE['cookiesPreenchidos'];
*/
?>