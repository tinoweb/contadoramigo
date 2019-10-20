<?php
include "conect.php";
include "session.php";

$ID = $_GET["vt"];

$sql="DELETE FROM dados_transporte_funcionario WHERE idTransporte = " . $ID . "";

$resultado = mysql_query($sql)
or die (mysql_error());

$_SESSION['aviso'] = 'Dados excluídos com sucesso!';


header('Location: ' . basename($_SERVER['HTTP_REFERER']) );

?>