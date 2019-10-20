<?php 
include "conect.php";

$pis = $_GET["pis"];
$cpf = $_GET["cpf"];
$ID = $_GET["id"];
$IDLogin = $_GET["idLogin"];

// A VARIAVEL IDLOGIN VEM DO FORMULÁRIO DE CADASTRO DA PAGINA DE PAGAMENTOS SERVE PARA CHECAR SE O USUÁRIO JÁ TEM UM AUTONOMO CADASTRADO COM O MESMO CPF E/OU PIS
if(isset($ID) && $ID != ''){
	$sql="SELECT * FROM dados_autonomos WHERE (REPLACE(REPLACE(cpf,'.',''),'-','')='" . str_replace("-","",str_replace(".","",$cpf)) . "' OR REPLACE(pis,'.','')='" . str_replace(".","",$pis) . "') AND id <> " . $ID . "  AND id_login = " . $IDLogin . " LIMIT 0, 1";
}else{
	$sql="SELECT * FROM dados_autonomos WHERE (REPLACE(REPLACE(cpf,'.',''),'-','')='" . str_replace("-","",str_replace(".","",$cpf)) . "' OR REPLACE(pis,'.','')='" . str_replace(".","",$pis) . "') AND id_login = " . $IDLogin . " LIMIT 0, 1";
}
//echo $sql;
//exit;


$resultado = mysql_query($sql)
or die (mysql_error());

//echo (mysql_num_rows($resultado) != 0 ? "1" : "0");
if(mysql_num_rows($resultado) > 0){
	echo mysql_num_rows($resultado);
}
?>