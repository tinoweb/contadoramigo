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
$EMAIL = $_GET["email"];

//Formatar CNPJ
$CNPJFormatado = $CNPJ;//substr($CNPJ,0,2) . "." . substr($CNPJ,2,3) . "." . substr($CNPJ,5,3) . "/" . substr($CNPJ,8,4) . "-" . substr($CNPJ,12,2);

$sql="SELECT * FROM dados_da_empresa WHERE cnpj='" . $CNPJFormatado . "' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
echo mysql_num_rows($resultado) . "|";

$rsIdEmpresa = mysql_fetch_array($resultado);
$dadosLoginCNPJ = mysql_fetch_array(mysql_query("SELECT status FROM login WHERE id='" . $rsIdEmpresa['id'] . "' LIMIT 0, 1"));

$sql="SELECT * FROM login WHERE email='" . $EMAIL . "' AND status != 'servico-avulso' LIMIT 0, 1";
$resultado = mysql_query($sql) or die (mysql_error());
echo mysql_num_rows($resultado). "|";

$dadosLoginEMAIL = mysql_fetch_array($resultado);

echo $dadosLoginCNPJ['status'] . "|";

echo $dadosLoginEMAIL['status'];
?>