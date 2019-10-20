<?php
	session_start();

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