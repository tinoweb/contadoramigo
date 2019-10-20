<?php
session_start(); 
include 'conect.php' ;

$query = "SELECT p.pagtoId
			,p.*
			,f.nome AS funcionarioNome
			FROM dados_pagamentos_funcionario p 
			JOIN dados_do_funcionario f ON f.idFuncionario = p.funcionarioId
			WHERE p.empresaId = '".$_SESSION['id_empresaSecao']."' 
			AND YEAR(p.data_pagto) = '".$_REQUEST['ano']."'
			AND MONTH(p.data_pagto) = '".$_REQUEST['mes']."'";

$consulta = mysql_query($query);

if( mysql_num_rows($consulta) > 0){
	echo utf8_decode('pagto-funcionario');
	exit;
} else {
	echo utf8_decode('ok');
}
?>