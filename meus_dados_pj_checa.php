<?php 
include "conect.php";

$cnpj = $_GET["cnpj"];
$ID = $_GET["id"];
$IDLogin = $_GET["idLogin"];

// A VARIAVEL IDLOGIN VEM DO FORMULÁRIO DE CADASTRO DA PAGINA DE PAGAMENTOS SERVE PARA CHECAR SE O USUÁRIO JÁ TEM UMA EMPRESA FORNECEDORA CADASTRADA COM O MESMO CNPJ
if(isset($ID) && $ID != ''){
	$sql="SELECT * FROM dados_pj WHERE REPLACE(REPLACE(REPLACE(cnpj,'.',''),'-',''),'/','')='" . trim(str_replace("/","",str_replace("-","",str_replace(".","",$cnpj)))) . "' AND id_login = " . $IDLogin . " AND id <> " . $ID . " LIMIT 0, 1";
}else{
	$sql="SELECT * FROM dados_pj WHERE REPLACE(REPLACE(REPLACE(cnpj,'.',''),'-',''),'/','')='" . trim(str_replace("/","",str_replace("-","",str_replace(".","",$cnpj)))) . "' AND id_login = " . $IDLogin . " LIMIT 0, 1";
}
$resultado = mysql_query($sql)
or die (mysql_error());

echo (mysql_num_rows($resultado) != 0 ? "1" : "0");
?>