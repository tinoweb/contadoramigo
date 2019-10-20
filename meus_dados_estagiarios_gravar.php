<?php
include "conect.php";

$acao 					= $_POST["acao"];
$id 					= $_POST["hidID"];
$idlogin				= $_POST["hidID2"];
$nome 					= mysql_real_escape_string($_POST["nome"]);
$cpf					= $_POST["CPF"];
$rg						= $_POST["RG"];	
$email					= mysql_real_escape_string($_POST["Email"]);
$endereco				= mysql_real_escape_string($_POST["Endereco"]);
$cep					= $_POST["CEP"];
$cidade					= mysql_real_escape_string($_POST["Cidade"]);
// alterado em 27/11/2014 - adequando todos os cadastros para combo de cidades
$arrEstado = explode(';',$_POST['Estado']);
$estado = $arrEstado[1];
//$estado					= $_POST["Estado"];


$tipo_conta				= $_POST["tipo_conta"] != "" ? "'".$_POST["tipo_conta"]."'" : "null";
$id_banco				= $_POST["id_banco"] != "" ? "".$_POST["id_banco"]."" : "0";
$num_agencia			= $_POST["agencia"] != "" ? "'".$_POST["agencia"]."'" : "null";
$dig_agencia			= $_POST["dig_agencia"] != "" ? "'".$_POST["dig_agencia"]."'" : "null";
$num_conta				= $_POST["conta"] != "" ? "'".$_POST["conta"]."'" : "null";
$dig_conta				= $_POST["dig_conta"] != "" ? "'".$_POST["dig_conta"]."'" : "null";

$pref_telefone	= $_POST["pref_telefone"] != "" ? "'".$_POST["pref_telefone"]."'" : "null";
$telefone				= $_POST["telefone"] != "" ? "'".$_POST["telefone"]."'" : "null";

$tipo				= $_POST["tipo"];


//Formatar CPF
$CPFFormatado = $cpf;//substr($cpf,0,3) . "." . substr($cpf,3,3) . "." . substr($cpf,6,3) . "-" . substr($cpf,9,2);

//Formatar CEP
$CEPFormatado = $cep;//substr($cep,0,5) . "-" . substr($cep,5,3);

if($acao == 'inserir'){
	$SQL = "INSERT INTO estagiarios (
			id
			, id_login
			, nome
			, cpf
			, rg
			, email
			, endereco
			, cep
			, cidade
			, estado
			, tipo_conta
			, id_banco
			, agencia
			, dig_agencia
			, conta
			, dig_conta
			, pref_telefone
			, telefone
			, tipo
		) VALUES (
			NULL
			, ".$idlogin."
			, '".$nome."'
			, '".$cpf."'
			, '".$rg."'
			, '".$email."'
			, '".$endereco."'
			, '".$cep."'
			, '".$cidade."'
			, '".$estado."'
			, ".$tipo_conta."
			, ".$id_banco."
			, ".$num_agencia."
			, ".$dig_agencia."
			, ".$num_conta."
			, ".$dig_conta."
			, ".$pref_telefone."
			, ".$telefone."
			, '".$tipo."'
		)";
	
}else{
	$SQL = "UPDATE estagiarios 
			SET nome 				= '".$nome."'
				, cpf 					= '".$cpf."'
				, rg 					= '".$rg."'
				, email 				= '".$email."'
				, endereco 				= '".$endereco."'
				, cep 					= '".$cep."'
				, cidade 				= '".$cidade."'
				, estado 				= '".$estado."'
				, tipo_conta 			= ".$tipo_conta."
				, id_banco	 			= ".$id_banco."
				, agencia	 			= ".$num_agencia."
				, dig_agencia			= ".$dig_agencia."
				, conta		 			= ".$num_conta."
				, dig_conta 			= ".$dig_conta."
				, pref_telefone = ".$pref_telefone."
				, telefone	 		= ".$telefone."
				, tipo	 		= '".$tipo."'
			WHERE id = ".$id."
				AND id_login = ".$idlogin."";
}

//Atualizar dados em dados do autonomo.
$resultado = mysql_query($SQL)
or die (mysql_error());

session_start();
if($acao == 'inserir'){
	$_SESSION['mensagem_altera_estagiarios'] = 'Dados cadastrados com sucesso!';
	header('Location: meus_dados_estagiarios.php' );
}else{
	$_SESSION['mensagem_altera_estagiarios'] = 'Dados alterados com sucesso!';
	header('Location: meus_dados_estagiarios.php?editar='.$id );
}
?>