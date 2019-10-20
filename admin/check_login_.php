<?php
	session_start();
// var_dump(strpos($_SERVER['SCRIPT_URL'],'/adminds'));
//	echo("a");
//echo($_SESSION["idAdmSecao"]);
//exit;
$string = '';
foreach ($_GET as $key => $value)
	$string .= $key.'='.$value;
if ( !isset($_SESSION["idAdmSecao"]) ){
	if(!isset($_COOKIE['ContAmi'])){
		$_SESSION['mensERRO'] = "Área de acesso restrito." . mysql_error();
		if( strlen($_SERVER['SCRIPT_URL']) > 8 && strpos($_SERVER['SCRIPT_URL'],'/admin') >= 0 ){
			if( $string != '' )
				setcookie('lastPage', $_SERVER['SCRIPT_URL'].'?'.$string);	
			else
				setcookie('lastPage', $_SERVER['SCRIPT_URL']);
		}
		header('Location: /admin/index.php' );
		exit;
	}else{
//		$ContAmi = explode(" ", $_COOKIE["ContAmi"]);
//		$contAdmMail = $ContAmi[0];
//		$contAdmPass = $ContAmi[1];
		if( strlen($_SERVER['SCRIPT_URL']) > 8 && strpos($_SERVER['SCRIPT_URL'],'/admin') >= 0 ){
			if( $string != '' )
				setcookie('lastPage', $_SERVER['SCRIPT_URL'].'?'.$string);	
			else
				setcookie('lastPage', $_SERVER['SCRIPT_URL']);
		}
		header("Location: /admin/login.php");
		exit;
	}
}
?>