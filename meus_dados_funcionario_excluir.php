<?php
include "conect.php";
include "session.php";


$ID = $_GET["func"];

$sql="DELETE FROM dados_do_funcionario WHERE idFuncionario = " . $ID . "";
$resultado = mysql_query($sql)
or die (mysql_error());

$sql="DELETE FROM dados_dependentes_funcionario WHERE idFuncionario = " . $ID . "";
$resultado = mysql_query($sql)
or die (mysql_error());

$sql="DELETE FROM dados_transporte_funcionario WHERE idFuncionario = " . $ID . "";
$resultado = mysql_query($sql)
or die (mysql_error());


$_SESSION['aviso'] = 'Dados excluídos com sucesso!';


header('Location: ' . basename($_SERVER['HTTP_REFERER']) );

?>