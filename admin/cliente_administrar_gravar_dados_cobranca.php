<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

include "../conect.php";
$ID = $_POST["hidID"];
$Assinante = $_POST["txtAssinante"];
$Email = $_POST["txtEmail"];
$Senha = $_POST["txtSenha"];
$PrefixoTelefone = $_POST["txtPrefixoTelefone"];
$Telefone = $_POST["txtTelefone"];
$Endereco = $_POST["txtEndereco"];
$Numero = $_POST["txtNumero"];
$Complemento = $_POST["txtComplemento"];
$Cidade = $_POST["txtCidade"];
$CEP = $_POST["txtCEP"];
$Estado = $_POST["selEstado"];
$FormaPagamento = $_POST["cheFormaPagamento"];

//$Status = $_POST['hddStatus'];


$tipo 				= $_POST['rdbTipo'] == 'J' ? 'PJ' : 'PF';
$sacado				= $_POST['boleto_sacado'];
$documento 		= $tipo == 'PJ' ? $_POST['boleto_cnpj'] :  $_POST['boleto_cpf'];
$endereco 		= $_POST['boleto_endereco'];
$bairro 			= $_POST['boleto_bairro'];
$arrUF				= explode(';',$_POST['selEstado']);
$idUF					= $arrUF[0];
$siglaUF			= $arrUF[1];
$cidade 			= $_POST['txtCidade'];
$cep 					= $_POST['boleto_cep'];

$descontoMesalidade = str_replace(',','.',str_replace('.','',$_POST['descontoMesalidade']));

// Pega o valor do Standard.
$valorStandardMensal = str_replace(',','.',str_replace('.','',$_POST['desconto_S_mensal']));
$valorStandardTrimestral = str_replace(',','.',str_replace('.','',$_POST['desconto_S_trimestral']));
$valorStandardSemestral = str_replace(',','.',str_replace('.','',$_POST['desconto_S_semestral']));
$valorStandardAnual = str_replace(',','.',str_replace('.','',$_POST['desconto_S_anual']));

// Pega o valor do premium.
$valorpremiumMensal = str_replace(',','.',str_replace('.','',$_POST['desconto_P_mensal']));
$valorpremiumTrimestral = str_replace(',','.',str_replace('.','',$_POST['desconto_P_trimestral']));
$valorpremiumSemestral = str_replace(',','.',str_replace('.','',$_POST['desconto_P_semestral']));
$valorpremiumAnual = str_replace(',','.',str_replace('.','',$_POST['desconto_P_anual']));

$sqlFormaPagto = "";

if ($FormaPagamento == "boleto") {

	$sqlFormaPagto = ", forma_pagameto='$FormaPagamento'";
	
}

//Atualizar dados em dados de cobrança.
$sql = " UPDATE dados_cobranca SET assinante='$Assinante'
			, pref_telefone='$PrefixoTelefone'
			, telefone='$Telefone'
			, sacado='$sacado'
			, documento='$documento'
			, endereco='$endereco'
			, bairro='$bairro'
			, uf='$siglaUF'
			, cep='$cep'
			, cidade='$cidade'
			, tipo='$tipo'" . $sqlFormaPagto . "
			, desconto_mesalidade='$descontoMesalidade'
			, desconto_S_mensal = '$valorStandardMensal'
			, desconto_S_trimestral = '$valorStandardTrimestral'
			, desconto_S_semestral = '$valorStandardSemestral' 
			, desconto_S_anual = '$valorStandardAnual'
			, desconto_P_mensal = '$valorpremiumMensal'
			, desconto_P_trimestral = '$valorpremiumTrimestral'
			, desconto_P_semestral = '$valorpremiumSemestral' 
			, desconto_P_anual = '$valorpremiumAnual'			
		WHERE id='$ID' ";
	
$resultado = mysql_query($sql)
or die (mysql_error());

//Atualizar dados em login.
if(($Email != "") and ($Senha != "")) { 
$sql = "UPDATE login SET email='$Email', senha='$Senha' WHERE idUsuarioPai='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());
}

//Modificar a forma de pagamento para boleto.
//if ($FormaPagamento == "boleto") {
//	$sql = "UPDATE dados_cobranca SET forma_pagameto='$FormaPagamento', numero_cartao=NULL, codigo_seguranca=NULL, nome_titular=NULL, data_validade=NULL WHERE id='$ID'";
//	$resultado = mysql_query($sql)
//	or die (mysql_error());
//}


header('Location: cliente_administrar.php?id=' . $ID );
?>