<?php 
//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
//$db = mysql_select_db("contadoramigo");
//mysql_query("SET NAMES 'utf8'");
//mysql_query('SET character_set_connection=utf8');
//mysql_query('SET character_set_client=utf8');
//mysql_query('SET character_set_results=utf8');

// inclui o arquivo de conexão.
require_once "conect.php";

$CNPJ = $_GET["cnpj"];

//Formatar CNPJ
//$CNPJFormatado = substr($CNPJ,0,2) . "." . substr($CNPJ,2,3) . "." . substr($CNPJ,5,3) . "/" . substr($CNPJ,8,4) . "-" . substr($CNPJ,12,2);
//$CNPJ;//

$sql="SELECT * FROM dados_da_empresa WHERE REPLACE(REPLACE(REPLACE(cnpj,'.',''),'-',''),'/','') = '" . $CNPJ . "'";

$resultado = mysql_query($sql)
or die (mysql_error());

echo mysql_num_rows($resultado);
?>