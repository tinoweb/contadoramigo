<?
require_once 'conect.php' ;


// CONSULTA QUE CHECA A EXISTENCIA DE OUTRO PAGAMENTO PARA O MESMO AUTONOMO NO MES
$sql = "SELECT * FROM dados_pagamentos WHERE id_login='" . $_GET['id'] . "' AND id_autonomo = '" . $_GET['aut'] . "' AND MONTH(data_pagto) = '" . substr($_GET['data'],3,2) . "' AND YEAR(data_pagto) = '" . substr($_GET['data'],6,4) . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());

if(mysql_num_rows($resultado) > 0){
	echo '1';
}else{	
	echo '0';
}

?>