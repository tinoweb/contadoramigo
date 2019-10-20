<?php 
include "conect.php";
include 'session.php';

$ID = $_GET["excluir"];

// CHECANDO SE HÁ PAGAMENTOS PARA O AUTONOMO A SER EXCLUIDO
$pagamentos = mysql_fetch_array(mysql_query("SELECT id_pagto FROM dados_pagamentos WHERE id_autonomo = '" . $ID . "'"));

if(is_array($pagamentos)){

	$_SESSION["mensagem_altera_autonomos"] = "Este autônomo não pôde ser removido pois há pagamentos registrados em nome dele.";

}else{

	$sql="DELETE FROM dados_autonomos WHERE id = " . $ID . "";
	
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$_SESSION['mensagem_altera_autonomos'] = 'Dados excluídos com sucesso!';
	
}


header('Location: meus_dados_autonomos.php' );
?>