<?php
// CÓDIGO para fazer o cookie funcionar no IE
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');


//	include '../session.php';
//	unset($_COOKIE['ContAmi']);
//	setcookie("ContAmi", '', time()-(60*60*24*30));

//	session_regenerate_id(true);
//	$_SESSION["nomeAdm_userSecao"]="";
//	$_SESSION["emailAdm_userSecao"]="";
//	$_SESSION["idAdm_userSecao"]="";
//	$_SESSION['IsAdmAuthorized']="";
//	$mensagem = "";

	// Remove os dados de login do cliente.
	setcookie('ContAmi','', time()+(120*120*24*30),"/", str_replace('www.', '', $_SERVER['SERVER_NAME']),0);
	
	//unset($_COOKIE['ContAmi']);

	session_start();

	$_SESSION = array();

	session_destroy();

	header('Location: index.php' );
	exit;

?>