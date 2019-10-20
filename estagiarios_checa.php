<?
require_once 'conect.php' ;


// CONSULTA QUE CHECA A EXISTENCIA DE OUTRO PAGAMENTO PARA O MESMO ESTAGIARIO NO MES
$sql = "SELECT * FROM estagiarios WHERE id_login='" . $_GET['id'] . "' AND id_estagiario = '" . $_GET['est'] . "' AND MONTH(data_pagto) = '" . substr($_GET['data'],3,2) . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());

if(mysql_num_rows($resultado) > 0){
	echo '1';
}else{	
	echo '0';
}

?>