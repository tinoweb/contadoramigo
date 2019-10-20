<?php
	# Banco de Dados
	
    if( $_SERVER['SERVER_NAME'] === 'localhost'){
		$dbname="ambuplay";
		$usuario= "root"; 
		$password="";
		$servidor = "localhost"; 
	}
	else{
		$dbname="contadoramig15";
		$usuario= "contadoramig15"; 
		$password="ttq231kz1";
		$servidor = "177.153.16.160 "; 
	}

	$base = "http://".$_SERVER['SERVER_NAME'];
	$url_painel = $base.'/admin';

	# Geral
	$logotipo = "../assets/images/logo.png";
	
	error_reporting(0); //--> RETIRE ESTE COMENTÁRIO QUANDO TERMINAR AS EDIÇÕES NO PAINEL
?>