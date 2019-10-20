<?php 
include "conect.php";

$CNPJ = $_GET["cnpj"];
$ID = $_GET["id"];

//Formatar CNPJ
$CNPJFormatado = $CNPJ;//substr($CNPJ,0,2) . "." . substr($CNPJ,2,3) . "." . substr($CNPJ,5,3) . "/" . substr($CNPJ,8,4) . "-" . substr($CNPJ,12,2);

if(isset($_GET['id']) && $ID != ''){
	$sql="SELECT * FROM dados_da_empresa WHERE cnpj='" . $CNPJFormatado . "' and id <> " . $ID . " LIMIT 0,1";//  LIMIT 0, 1";
} else {
	$sql="SELECT * FROM dados_da_empresa WHERE cnpj='" . $CNPJFormatado . "' LIMIT 0,1";// and id <> " . $ID . " LIMIT 0, 1";
}
//echo $sql;
$resultado = mysql_query($sql)
or die (mysql_error());

echo (mysql_num_rows($resultado) != 0 ? "1" : "0");
?>