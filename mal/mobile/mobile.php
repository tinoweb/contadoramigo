<?php

/**
* 
*/
class ClassName extends AnotherClass
{
	
	function __construct(argument)
	{
		# code...
	}
}

$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true): /*Se este dispositivo for portátil, faça/escreva o seguinte */ 
	
	$pagina_atual = "quem_somos.php";
	
	//$pagina_atual = $_SERVER['REQUEST_URI'];

	$pagina = explode( '.php' , $pagina_atual );
	$pagina = $pagina[0];
	echo $pagina;
	include '../'.$pagina.'_mobile.php';
	

endif; ?>