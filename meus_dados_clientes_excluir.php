<?php 
include "conect.php";
include 'session.php';

$ID = $_GET["excluir"];

// CHECANDO SE HÁ PAGAMENTOS PARA O PJ A SER EXCLUIDO
//$pagamentos = mysql_fetch_array(mysql_query("SELECT id_pagto FROM dados_pagamentos WHERE id_pj = '" . $ID . "'"));
//
//if(is_array($pagamentos)){
//
//	$_SESSION["mensagem_altera_clientes"] = "Este cliente não pôde ser removido pois há pagamentos registrados em nome dela.";
//
//}else{


	$sql="DELETE FROM dados_clientes WHERE id = " . $ID . "";
	
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
//	$sql="DELETE FROM dados_pagamentos WHERE id_cliente = " . $ID . "";
//	$resultado = mysql_query($sql)
//	or die (mysql_error());
	
	$_SESSION['mensagem_altera_clientes'] = 'Dados excluídos com sucesso!';

//}

header('Location: meus_dados_clientes.php' );
?>