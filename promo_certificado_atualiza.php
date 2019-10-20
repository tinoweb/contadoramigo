<?php
include "conect.php";

$FormaPagamento = $_POST["radFormaPagamento"];
$tipo 			= $_POST['rdbTipo'] == 'J' ? 'PJ' : 'PF';
$sacado			= $_POST['boleto_sacado'];
$documento 		= $tipo == 'PJ' ? $_POST['boleto_cnpj'] :  $_POST['boleto_cpf'];
$endereco 		= $_POST['boleto_endereco'];
$bairro 			= $_POST['boleto_bairro'];
$arrUF			= explode(';',$_POST['selEstado']);
$idUF			= $arrUF[0];
$siglaUF		= $arrUF[1];
$cidade 			= $_POST['txtCidade'];
$cep 			= $_POST['boleto_cep'];
$ID             = $_POST["hidID"];


//Atualizar dados em dados de cobrança.
$sql = "UPDATE dados_cobranca SET forma_pagameto='$FormaPagamento', sacado='$sacado', documento='$documento', endereco='$endereco', bairro='$bairro', uf='$siglaUF', cep='$cep', cidade='$cidade', tipo='$tipo' , plano ='semestral' WHERE id='$ID'";


$resultado = mysql_query($sql)
or die (mysql_error());

header('Location: promo_certificado.php');

?>