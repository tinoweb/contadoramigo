<?php
include '../conect.php';
include '../session.php';
include 'check_login.php';

// $cliente = $_GET["linha"];

// $sql = "DELETE FROM login WHERE id='" . $cliente . "'";
// $resultado = mysql_query($sql)
// or die (mysql_error());

// $sql = "DELETE FROM dados_cobranca WHERE id='" . $cliente . "'";
// $resultado = mysql_query($sql)
// or die (mysql_error());

// $sql = "DELETE FROM dados_da_empresa WHERE id='" . $cliente . "'";
// $resultado = mysql_query($sql)
// or die (mysql_error());

// $sql = "DELETE FROM dados_da_empresa_codigos WHERE id='" . $cliente . "'";
// $resultado = mysql_query($sql)
// or die (mysql_error());

// $sql = "DELETE FROM dados_da_empresa_codigo_prefeitura WHERE id='" . $cliente . "'";
// $resultado = mysql_query($sql)
// or die (mysql_error());

// $sql = "DELETE FROM dados_do_responsavel WHERE id='" . $cliente . "'";
// $resultado = mysql_query($sql)
// or die (mysql_error());
/*
$sql = "DELETE FROM historico_cobranca WHERE id='" . $cliente . "'";
$resultado = mysql_query($sql)
or die (mysql_error());

$sql = "DELETE FROM relatorio_cobranca WHERE id='" . $cliente . "'";
$resultado = mysql_query($sql)
or die (mysql_error());

$sql = "DELETE FROM suporte WHERE id='" . $cliente . "'";
$resultado = mysql_query($sql)
or die (mysql_error());
*/
// $sql = "DROP TABLE user_" . $cliente . "_livro_caixa";
// $resultado = mysql_query($sql)
// or die (mysql_error());

header('Location: clientes_lista.php' );
?>