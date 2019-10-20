<?php
session_start();

include "conect.php";

$acao 					= $_POST["acao"];
$id 					= $_POST["hidID"];
$idlogin				= $_POST["hidID2"];
$nome 					= mysql_real_escape_string($_POST["nome"]);
$cnpj					= $_POST["CNPJ"];
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

$pref_telefone			= $_POST["pref_telefone"] != "" ? "'".$_POST["pref_telefone"]."'" : "null";
$telefone				= $_POST["telefone"] != "" ? "'".$_POST["telefone"]."'" : "null";

//$op_simples				= $_POST["radSimples"] != "" ? "".$_POST["radSimples"]."" : "0";

$mei					= $_POST["radMei"] != "" ? "".$_POST["radMei"]."" : "0";

$op_simples				= ($_POST["radMei"] == "1" ? "1" : "".$_POST["radSimples"]."");


$CPOM					= $_POST["radCPOM"] != "" ? "".$_POST["radCPOM"]."" : "0";


if($_SESSION['paginaOrigem'] != $_POST["paginaOrigem"]){
	$_SESSION['paginaOrigem'] = $_POST["paginaOrigem"];
}


if($acao == 'inserir'){
	$SQL = "INSERT INTO dados_pj (
			id
			, id_login
			, nome
			, cnpj
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
			, op_simples
			, mei
			, cpom
		) VALUES (
			NULL
			, ".$idlogin."
			, '".$nome."'
			, '".$cnpj."'
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
			, ".$op_simples."
			, ".$mei."
			, ".$CPOM."
		)";
	
}else{
	$SQL = "UPDATE dados_pj
			SET nome 					= '".$nome."'
				, cnpj 					= '".$cnpj."'
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
				, pref_telefone 		= ".$pref_telefone."
				, telefone	 			= ".$telefone."
				, op_simples	 		= ".$op_simples."
				, mei	 				= ".$mei."
				, cpom	 				= ".$CPOM."
			WHERE id = ".$id."
				AND id_login = ".$idlogin."";
}

//Atualizar dados em dados do autonomo.
$resultado = mysql_query($SQL)
or die (mysql_error());

session_start();
if($acao == 'inserir'){
	$_SESSION['mensagem_altera_pj'] = 'Dados cadastrados com sucesso!';
	header('Location: meus_dados_pj.php' );
}else{
	$_SESSION['mensagem_altera_pj'] = 'Dados alterados com sucesso!';
	header('Location: meus_dados_pj.php?editar='.$id );
}
?>