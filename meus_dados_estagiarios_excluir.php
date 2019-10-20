<?php 
include "conect.php";
include 'session.php';

$ID = $_GET["excluir"];

// CHECANDO SE HÁ PAGAMENTOS PARA O ESTAGIARIO A SER EXCLUIDO
$pagamentos = mysql_fetch_array(mysql_query("SELECT id_pagto FROM dados_pagamentos WHERE id_estagiario = '" . $ID . "'"));

if(is_array($pagamentos)){

	$_SESSION["mensagem_altera_estagiarios"] = "Este estagiário não pôde ser removido pois há pagamentos registrados em nome dele.";

}else{
	
	$sql="DELETE FROM estagiarios WHERE id = " . $ID . "";

	$resultado = mysql_query($sql)
	or die (mysql_error());
	
}

header('Location: meus_dados_estagiarios.php' );
?>