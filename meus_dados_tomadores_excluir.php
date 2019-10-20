<?php 
include "conect.php";

$ID = $_GET["excluir"];

$sql="DELETE FROM dados_tomadores WHERE id = " . $ID . "";

$resultado = mysql_query($sql)
or die (mysql_error());

$_SESSION['mensagem_altera_tomadores'] = 'Dados excluídos com sucesso!';

header('Location: meus_dados_tomadores.php' );
?>