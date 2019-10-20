<?php
include "conect.php";

$acao 					= $_POST["acao"];
$id 					= $_POST["hidID"];
$idlogin				= $_POST["hidID2"];
$nome 					= mysql_real_escape_string($_POST["nome"]);
$cbo					= $_POST["CBO"];
$cpf					= $_POST["CPF"];
$rg						= $_POST["RG"];	
$orgao_emissor			= $_POST["orgao_emissor"];
$pis					= $_POST["PIS"];
$dependentes			= $_POST["NumeroDep"];
$email					= mysql_real_escape_string($_POST["Email"]);
$endereco				= mysql_real_escape_string($_POST["Endereco"]);
$cep					= $_POST["CEP"];
$cidade					= mysql_real_escape_string($_POST["Cidade"]);

$tipo 	 				= $_POST['tipo'];

// alterado em 27/11/2014 - adequando todos os cadastros para combo de cidades
$arrEstado = explode(';',$_POST['Estado']);
$estado = $arrEstado[1];
//$estado					= $_POST["Estado"];

$tipo_servico			= $_POST["tipo_servico"];
$pensao					= ($_POST["pensao"] == '1' ? '1' : '0');
$perc_pensao			= ($_POST["PercentPensao"] != '' ? number_format($_POST['PercentPensao'],2,'.','') : 0);
$inscr_municipal		= $_POST["inscricao_municipal"];
$aliquota_ISS			= str_replace(",",".",str_replace(".","",str_replace("%","",$_POST['AliquotaISS'])));

$tipo_conta				= $_POST["tipo_conta"] != "" ? "'".$_POST["tipo_conta"]."'" : "null";
$id_banco				= $_POST["id_banco"] != "" ? "".$_POST["id_banco"]."" : "0";
$num_agencia			= $_POST["agencia"] != "" ? "'".$_POST["agencia"]."'" : "null";
$dig_agencia			= $_POST["dig_agencia"] != "" ? "'".$_POST["dig_agencia"]."'" : "null";
$num_conta				= $_POST["conta"] != "" ? "'".$_POST["conta"]."'" : "null";
$dig_conta				= $_POST["dig_conta"] != "" ? "'".$_POST["dig_conta"]."'" : "null";

$pref_telefone	= $_POST["pref_telefone"] != "" ? "'".$_POST["pref_telefone"]."'" : "null";
$telefone				= $_POST["telefone"] != "" ? "'".$_POST["telefone"]."'" : "null";



//Formatar CPF
$CPFFormatado = $cpf;//substr($cpf,0,3) . "." . substr($cpf,3,3) . "." . substr($cpf,6,3) . "-" . substr($cpf,9,2);

//Formatar CEP
$CEPFormatado = $cep;//substr($cep,0,5) . "-" . substr($cep,5,3);

if($acao == 'inserir'){
	$SQL = "INSERT INTO dados_autonomos (
			id
			, id_login
			, nome
			, cbo
			, tipo_servico
			, cpf
			, rg
			, orgao_emissor
			, pis
			, dependentes
			, email
			, endereco
			, cep
			, cidade
			, estado
			, pensao
			, perc_pensao
			, inscr_municipal
			, aliquota_ISS
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
			, '".$cbo."'
			, '".$tipo_servico."'
			, '".$cpf."'
			, '".$rg."'
			, '".$orgao_emissor."'
			, '".$pis."'
			, ".$dependentes."
			, '".$email."'
			, '".$endereco."'
			, '".$cep."'
			, '".$cidade."'
			, '".$estado."'
			, ".$pensao."
			, ".$perc_pensao."
			, '".$inscr_municipal."'
			, ".$aliquota_ISS."
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
	$SQL = "UPDATE dados_autonomos 
			SET nome 				= '".$nome."'
				, cbo 					= '".$cbo."'
				, tipo_servico 			= '".$tipo_servico."'
				, cpf 					= '".$cpf."'
				, rg 					= '".$rg."'
				, orgao_emissor 		= '".$orgao_emissor."'
				, pis 					= '".$pis."'
				, dependentes 			= ".$dependentes."
				, email 				= '".$email."'
				, endereco 				= '".$endereco."'
				, cep 					= '".$cep."'
				, cidade 				= '".$cidade."'
				, estado 				= '".$estado."'
				, pensao 				= ".$pensao."
				, perc_pensao 			= ".$perc_pensao."
				, inscr_municipal 		= '".$inscr_municipal."'
				, aliquota_ISS 			= ".$aliquota_ISS."
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
echo $SQL;
//Atualizar dados em dados do autonomo.
$resultado = mysql_query($SQL)
or die (mysql_error());

session_start();
if($acao == 'inserir'){
	$_SESSION['mensagem_altera_autonomos'] = 'Dados cadastrados com sucesso!';
	header('Location: meus_dados_autonomos.php' );
}else{
	$_SESSION['mensagem_altera_autonomos'] = 'Dados alterados com sucesso!';
	header('Location: meus_dados_autonomos.php?editar='.$id );
}
?>