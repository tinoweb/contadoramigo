<?php
include "conect.php";

$acao 					= $_POST["acao"];
$id 					= $_POST["hidID"];
$idlogin				= $_POST["hidID2"];
$nome 					= mysql_real_escape_string($_POST["nome"]);
$cei					= $_POST["CEI"];
$endereco				= mysql_real_escape_string($_POST["Endereco"]);
$cep					= $_POST["CEP"];
$cidade					= mysql_real_escape_string($_POST["Cidade"]);
// alterado em 27/11/2014 - adequando todos os cadastros para combo de cidades
$arrEstado = explode(';',$_POST['Estado']);
$estado = $arrEstado[1];
//$estado					= $_POST["Estado"];

$bairro					= $_POST["Bairro"];


//Formatar CPF
$CEIFormatado = $cei;//substr($cpf,0,3) . "." . substr($cpf,3,3) . "." . substr($cpf,6,3) . "-" . substr($cpf,9,2);

//Formatar CEP
$CEPFormatado = $cep;//substr($cep,0,5) . "-" . substr($cep,5,3);

if($acao == 'inserir'){
	$SQL = "INSERT INTO dados_tomadores (
			id
			, id_login
			, nome
			, cei
			, endereco
			, cep
			, cidade
			, estado
			, bairro
		) VALUES (
			NULL
			, ".$idlogin."
			, '".$nome."'
			, '".$cei."'
			, '".$endereco."'
			, '".$cep."'
			, '".$cidade."'
			, '".$estado."'
			, '".$bairro."'
		)";
	
}else{
	$SQL = "UPDATE dados_tomadores 
			SET nome 				= '".$nome."'
				, cei 					= '".$cei."'
				, endereco 				= '".$endereco."'
				, cep 					= '".$cep."'
				, cidade 				= '".$cidade."'
				, estado 				= '".$estado."'
				, bairro 				= '".$bairro."'
			WHERE id = ".$id."
				AND id_login = ".$idlogin."";
}

//Atualizar dados em dados do autonomo.
$resultado = mysql_query($SQL)
or die (mysql_error());

session_start();
if($acao == 'inserir'){
	$_SESSION['mensagem_altera_tomadores'] = 'Dados cadastrados com sucesso!';
	header('Location: meus_dados_tomadores.php' );
}else{
	$_SESSION['mensagem_altera_tomadores'] = 'Dados alterados com sucesso!';
	header('Location: meus_dados_tomadores.php?editar='.$id );
}
?>