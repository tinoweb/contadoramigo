<?


// if( isset($_SESSION['NOME_USER_VAD']) && $_SESSION['NOME_USER_VAD'] == "Vitor Maradei"){
// 	header("location: http://vad.com.br/intranet/agenda/index.php");
// }

if(isset($_POST['logoff'])){
	
	// Apaga a sessão.
	unset($_SESSION['NOME_USER_VAD']);	
	unset($_SESSION['NOME_USER_VAD']);
	
	setcookie('contador_amigo_intranet','', time()-(120*120*24*30), '/', str_replace('www.', '', $_SERVER['SERVER_NAME']), 0);
		
	header("location: http://www.google.com.br");
}

if(!isset($_SESSION['NOME_USER_VAD']) || $_SESSION['NOME_USER_VAD'] == ''){
	header("location: ../index.php");
}

?>