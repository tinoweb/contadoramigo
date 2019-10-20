<?php 
include "conect.php";

$ID = $_POST['hidID'];
$Motivo = $_POST['txtMotivo'];
$Satisfacao = $_POST['rdbSatisfacao'];
switch ($Satisfacao){
	case '1':
		$txt_satisfacao = "Muito Satisfeito";
	break;
	case '2':
		$txt_satisfacao = "Satisfeito";
	break;
	case '3':
		$txt_satisfacao = "Insatisfeito";
	break;
	case '4':
		$txt_satisfacao = "Muito Insatisfeito";
	break;
}

// LOG DE ACESSOS
mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $ID . ",'MINHA CONTA: USUARIO SOLICITOU CANCELAMENTO DA CONTA')");
//echo "insert into log_acessos (id_user, acao) VALUES (" . $ID . ",'MINHA CONTA: USUARIO SOLICITOU CANCELAMENTO DA CONTA')" . "<BR>";

mysql_query("UPDATE login SET status = 'cancelado' WHERE idUsuarioPai = '".$ID."'");
//mysql_query("UPDATE login SET status = 'cancelado' WHERE id = '".$ID."'");
//echo "UPDATE login SET status = 'cancelado' WHERE id = '".$ID."'" . "<BR>";

// Define a forma de pagamento como boleto.
mysql_query("UPDATE dados_cobranca SET forma_pagameto = 'boleto' WHERE id = '".$ID."'");

if($ID != 1581 && $ID != 9){
	// INSERE NA TABELA DE METRICAS
	mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $ID . ",'cancelado','" . date('Y-m-d') . "')");
}


mysql_query("INSERT INTO dados_cancelamentos SET id_empresa = '" . $ID . "', satisfacao = '" . $Satisfacao . "', motivo = '" . $Motivo . "'");

header('Location: auto_login.php?killCookie');

?>