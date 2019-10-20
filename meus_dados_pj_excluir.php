<?php 
include "conect.php";
include 'session.php';

$ID = $_GET["excluir"];

// CHECANDO SE HÁ PAGAMENTOS PARA O PJ A SER EXCLUIDO
$pagamentos = mysql_fetch_array(mysql_query("SELECT id_pagto FROM dados_pagamentos WHERE id_pj = '" . $ID . "'"));

if(is_array($pagamentos)){

	$_SESSION["mensagem_altera_pj"] = "Esta empresa não pôde ser removida pois há pagamentos registrados em nome dela.";

}else{


	$sql="DELETE FROM dados_pj WHERE id = " . $ID . "";
	
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$sql="DELETE FROM dados_pagamentos WHERE id_pj = " . $ID . "";
	
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$_SESSION['mensagem_altera_pj'] = 'Dados excluídos com sucesso!';

}

header('Location: meus_dados_pj.php' );
?>