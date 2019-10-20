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

	print "\ninserir contatos\n";

	// Campos disponíveis: bairro,cep,cidade,datadenascimento,departamento,email,empresa,endereco,estado
	//                     htmlemail,nome,sexo,sobrenome
	//
	// Todos os campos são opcionais com a exceção do campo email:
	// array_push($contatos, array('bairro'=>'',"cep"=>"", "cidade"=>"", "datadenascimento"=>"",
	//							"departamento"=>"","email"=>"campo obrigatorio","empresa"=>"","endereco"=>"",
	//							"estado"=>"", "htmlemail"=>"","nome"=>"","sexo"=>"","sobrenome"=>""));

	$contatos = array();
	array_push($contatos, array('email'=>'guilherme@vad.com.br', 'nome'=>'Guilherme Silva'));
	array_push($contatos, array('email'=>'vitor@vad.com.br', 'nome'=>'Vitor Maradei'));

	//Inserir contato na lista
	$repositorio->importar($contatos, array(38414));

?>