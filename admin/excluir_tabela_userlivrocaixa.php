<?php 
	
	$requestURI = explode("/", $_SERVER['REQUEST_URI']);

	if($requestURI[1] == 'admin') {
		require_once('../Model/LivroCaixa/LivroCaixaData.php');	
	} else {
		require_once('Model/LivroCaixa/LivroCaixaData.php');	
	}
	
	$allUser = new LivroCaixaData();
	
	$tablesExcluidas = $allUser->ExcluiAsTabelasVazias();
	
	header("Location: /admin/tabelasLivroCaixa.php");
?>