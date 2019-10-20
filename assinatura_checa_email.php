<?php 
//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//$db = mysql_select_db("contadoramigo");
//mysql_query("SET NAMES 'utf8'");
//mysql_query('SET character_set_connection=utf8');
//mysql_query('SET character_set_client=utf8');
//mysql_query('SET character_set_results=utf8');

// inclui o arquivo de conexão.
require_once "conect.php";

//$sql="SELECT * FROM login WHERE email='" . $_GET["email"] . "' AND senha='" . $_GET["senha"] . "' LIMIT 0, 1";
$sql="SELECT count(*) total FROM login WHERE email='" . $_GET["email"] . "'";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha = mysql_fetch_array($resultado);

echo $linha['total'];
?>