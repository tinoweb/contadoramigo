<?php 
include '../conect.php';
include '../session.php';
include 'check_login.php';

$linha = $_GET["linha"];
$codigo = $_GET["codigo"];

$sql = "SELECT anexo FROM suporte WHERE idPostagem='" . $linha . "'";
$resultado = mysql_query($sql)
or die (mysql_error());
if($arquivo = mysql_fetch_array($resultado)){
	if($arquivo['anexo'] != ''){
		@unlink('../' . $arquivo['anexo']);
	}
}

$sql = "DELETE FROM suporte WHERE idPostagem='" . $linha . "'";
$resultado = mysql_query($sql)
or die (mysql_error());

$sql = "SELECT * FROM suporte WHERE idPergunta='$codigo' ORDER BY idPergunta DESC LIMIT 0,1";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);

$data = $linha["data"];

$sql = "UPDATE suporte SET ultimaResposta='$data' WHERE idPostagem='$codigo'";
$resultado = mysql_query($sql)
or die (mysql_error());

header('Location: suporte_visualizar.php?codigo=' . $codigo );
?>