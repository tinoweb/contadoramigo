<?php
session_start();

if (!isset($_SESSION["id_userSecao"])){
	
	
	if(!isset($_COOKIE["contadoramigoHTTPS"]) || $_COOKIE["contadoramigoHTTPS"]==""){

		header('Location: '.$dominio.'assinatura_pagina_restrita.php' );
		exit;

	}else{

		header('Location: '.$dominio.'auto_login.php?login&cookie');
		exit;	
	
	}
	
}



// CONEXAO
//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//$db = mysql_select_db("contadoramigo");
//mysql_query("SET NAMES 'utf8'");
//mysql_query('SET character_set_connection=utf8');
//mysql_query('SET character_set_client=utf8');
//mysql_query('SET character_set_results=utf8');

// inclui o arquivo de conexão.
require_once "conect.php";

//CHECK LOGIN
if(($_SESSION['status_userSecao']=='inativo') or ($_SESSION['status_userSecao']=='demoInativo') or ($_SESSION['status_userSecao']=='cancelado')) {
	if((substr($_SERVER['REQUEST_URI'],0,16) == '/minha_conta.php') or (substr($_SERVER['REQUEST_URI'],0,24) == '/minha_conta_cancela.php') or (substr($_SERVER['REQUEST_URI'],0,12) == '/suporte.php') or (substr($_SERVER['REQUEST_URI'],0,8) == '/suporte') or (substr($_SERVER['REQUEST_URI'],0,23) == '/suporte_visualizar.php')) { 
	
	//lista de páginas que o usuário inativo possui acesso		  
	} else {
		header('Location: '.$dominio_seguro.'minha_conta.php' );
		exit;
	}
}

$id_user = $_GET['id'];

$rsDadosLogin			= mysql_query("SELECT l.id, l.nome, l.assinante, l.email, l.status, l.info_preliminar, l.id_plano, l.idUsuarioPai, emp.id, razao_social, cnpj, nome_fantasia, ativa, data_desativacao  FROM dados_da_empresa emp INNER JOIN login l ON emp.id = l.id  WHERE l.id = '" . $id_user . "' AND emp.ativa = 1");

if($rsDadosLogin){
	
	if(mysql_num_rows($rsDadosLogin) == 1){
	
		$dados								= mysql_fetch_row($rsDadosLogin);
		
		$nome_user 							= $dados[1]; //Variável que armazenará o nome da Empresa.
		$assinante_user					=	explode(" ", $dados[2]); //Variável que armazenará o nome do Assinante/usuário.
		$email_user 						= $dados[3]; //Variável que armazenará o email do usuário.
		$status_user						=	$dados[4]; //Variável que verificará o status do usuário.		
		$info_preliminar				=	$dados[5]; //Variável que verificará se o usuario esta acessando pela primeira vez.
		$id_plano								=	$dados[6]; //Variável que verificará o plano do usuario
		$idUsuarioMultiplo			=	$dados[7]; //Variável que guardará o id do usuario multiplo
		$empresa_ativa					= $dados[12];
	
	}else{
	
		$nome_user 							= $dados[1]; //Variável que armazenará o nome da Empresa.
		$assinante_user					=	explode(" ", $dados[2]); //Variável que armazenará o nome do Assinante/usuário.
		$email_user 						= $dados[3]; //Variável que armazenará o email do usuário.
		$status_user						=	$dados[4]; //Variável que verificará o status do usuário.		
		$info_preliminar				=	$dados[5]; //Variável que verificará se o usuario esta acessando pela primeira vez.
		$id_plano								=	$dados[6]; //Variável que verificará o plano do usuario
		$idUsuarioMultiplo			=	$dados[7]; //Variável que guardará o id do usuario multiplo
		$empresa_ativa					= $dados[12];
	}
	
			
	
	
	$_SESSION["nome_userSecao"]					= $nome_user; //Armazena o nome da empresa em uma sessão.
	$_SESSION["nome_assinanteSecao"]		= $assinante_user[0]; //Armazena o nome do assinante/usuário em uma sessão.
	$_SESSION["id_userSecaoMultiplo"]		= $idUsuarioMultiplo; //Armazena o id do usuário em uma sessão.
	$_SESSION["id_userSecao"]						= $id_user; //Armazena o id da empresa em uma sessão.
	$_SESSION["id_empresaSecao"]				= $id_user; //CONTROLA SE O USUARIO SELECIONOU UMA EMPRESA PARA GERENCIAR.
	
	$_SESSION['statusEmpresaSelecionada']	= $empresa_ativa;
	
	$_SESSION['timeout']								= time();  //Armazena hora atual uma sessão para ser efetuado o timeout na "session.php".


	header('Location: index_restrita.php');
	exit;

}else{

//	if(($_SESSION["id_empresaSecao"] != '' && isset($_SESSION["id_empresaSecao"])) && ($_SESSION['statusEmpresaSelecionada'] == 0)){ 
		header('Location: '.$dominio_seguro.'gerenciar_empresa.php' );
		exit;
//	}
	
}


?>