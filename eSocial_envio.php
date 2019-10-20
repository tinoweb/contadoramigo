<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// Verifica se o grupo de evento foi informado via get
if(isset($_GET['grupo_de_eventos'])){

	// Verifica se o arquivo como o nome do grupo de evento existe.
	if(file_exists('eSocial_'.$_GET['grupo_de_eventos'].'.php')){
		
		require_once('eSocial_'.$_GET['grupo_de_eventos'].'.php');
		
	} // Se o arquivo com o nome de grupo de evento não encontrado mostra a página não encontrada.
	else {
		require_once('eSocial_erro.php');
	}
	
} // Se o grupo de evento não foi informado cai como pagina não encontrada.
else {
	
	require_once('eSocial_erro.php');
}
?>