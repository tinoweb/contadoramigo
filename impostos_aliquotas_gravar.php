<?php 
session_start();

//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//$db = mysql_select_db("contadoramigo");
//mysql_query("SET NAMES 'utf8'");
//mysql_query('SET character_set_connection=utf8');
//mysql_query('SET character_set_client=utf8');
//mysql_query('SET character_set_results=utf8');

// inclui o arquivo de conexão.
require_once "conect.php";

mysql_query("DELETE FROM dados_impostos_aliquotas WHERE id = ".$_POST['id']);

mysql_query("OPTIMIZE TABLE dados_impostos_aliquotas");

//for($i=0; $i < count($_POST['CNAE']); $i++){
	mysql_query("INSERT INTO dados_impostos_aliquotas (id, faixa_faturamento, fator_r) VALUES (".$_POST['id'].",'".$_POST['faixa']."','".$_POST['fator']."')");
//	echo $sql;
//	echo "<BR>";
//}

echo '1';

?>