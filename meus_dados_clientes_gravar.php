<?php
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

include "conect.php";

$acao 					= $_POST["acao"];
$id 					= $_POST["hidID"];
$idlogin				= $_POST["hidID2"];
$apelido				= mysql_real_escape_string($_POST["Apelido"]);
$apelidoAntigo			= mysql_real_escape_string($_POST["ApelidoAntigo"]);
$nome 					= mysql_real_escape_string($_POST["Nome"]);
$email					= mysql_real_escape_string($_POST["Email"]);
$cnpj					= $_POST["CNPJ"];
$cpf					= $_POST["CPF"];
$endereco				= mysql_real_escape_string($_POST["Endereco"]);
$cep					= $_POST["CEP"];
$cidade					= mysql_real_escape_string($_POST["Cidade"]);
// alterado em 27/11/2014 - adequando todos os cadastros para combo de cidades
$arrEstado = explode(';',$_POST['Estado']);
$estado = $arrEstado[1];
//$estado					= $_POST["Estado"];

$pref_telefone	= $_POST["pref_telefone"] != "" ? "'".$_POST["pref_telefone"]."'" : "null";
$telefone				= $_POST["telefone"] != "" ? "'".$_POST["telefone"]."'" : "null";

if($acao == 'inserir'){
	$SQL = "INSERT INTO dados_clientes (
			id
			, id_login
			, apelido
			, nome
			, email
			, cnpj
			, cpf
			, endereco
			, cep
			, cidade
			, estado
			, pref_telefone
			, telefone
		) VALUES (
			NULL
			, ".$idlogin."
			, '".$apelido."'
			, '".$nome."'
			, '".$email."'
			, '".$cnpj."'
			, '".$cpf."'
			, '".$endereco."'
			, '".$cep."'
			, '".$cidade."'
			, '".$estado."'
			, ".$pref_telefone."
			, ".$telefone."
		)";
	
}else{
	
	// Atualiza os dados do Cliente.
	$SQL = " UPDATE dados_clientes
		SET nome 				= '".$nome."'
			, apelido 			= '".$apelido."'
			, email 			= '".$email."'
			, cnpj 				= '".$cnpj."'
			, cpf 				= '".$cpf."'
			, endereco 			= '".$endereco."'
			, numero 			= ''
			, complemento 		= ''
			, cep 				= '".$cep."'
			, cidade 			= '".$cidade."'
			, estado 			= '".$estado."'
			, pref_telefone 	= ".$pref_telefone."
			, telefone	 		= ".$telefone."
		WHERE id = ".$id."
		AND id_login = ".$idlogin."; ";
	
	// Atualiza as categorias conforme ao apelido.	
	if( $apelidoAntigo != $apelido ) {
		atualizaCategoria($idlogin, $apelido, $apelidoAntigo);	
	}
}

//Atualizar dados em dados do autonomo.
$resultado = mysql_query($SQL)
or die (mysql_error());

function atualizaCategoria($idlogin, $apelido, $apelidoAntigo) {
	
	$qry = " UPDATE user_".$idlogin."_livro_caixa "
			." SET categoria = '".$apelido."' "   
			." WHERE categoria = '".$apelidoAntigo."'; " ;
	
	$resultado = mysql_query($qry)
	or die (mysql_error());

}

session_start();
if($acao == 'inserir'){
	$_SESSION['mensagem_altera_clientes'] = 'Dados cadastrados com sucesso!';
	header('Location: meus_dados_clientes.php' );
}else{
	$_SESSION['mensagem_altera_clientes'] = 'Dados alterados com sucesso!';
	header('Location: meus_dados_clientes.php?editar='.$id );
}
?>