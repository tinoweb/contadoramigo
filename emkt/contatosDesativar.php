<?php 
include '../session.php';
include '../admin/check_login.php';
?>


<?php
	error_reporting(E_ALL);
	require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
	// Esses valores podem ser obtidos na página de configurações do
	// Email Marketing

	$hostName = 'emailmkt7';
	$login 	  = 'contadoramigo1';
	$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
	$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

	print "\n desativar contatos\n";

	$contatos = array();
	array_push($contatos, array('email'=>'vitor@vad.com.br'));
	array_push($contatos, array('email'=>'guilherme@vad.com.br'));

	//Caso queira remover de listas, informar os IDs desta no 2o parametro.
	$repositorio->desativar($contatos,array(38414));
?>