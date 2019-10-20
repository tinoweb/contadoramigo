<?php 
include "conect.php";

$cei = $_GET["cei"];
$ID = $_GET["id"];
$IDLogin = $_GET["idLogin"];

// A VARIAVEL IDLOGIN VEM DO FORMULÁRIO DE CADASTRO DA PAGINA DE PAGAMENTOS SERVE PARA CHECAR SE O USUÁRIO JÁ TEM UM AUTONOMO CADASTRADO COM O MESMO CPF E/OU PIS
if(isset($ID) && $ID != ''){
	$sql="SELECT * FROM dados_tomadores WHERE (cei='" . $cei . "') AND id <> " . $ID . " LIMIT 0, 1";
}else{
	$sql="SELECT * FROM dados_tomadores WHERE (cei='" . $cei . "') AND id_login = " . $IDLogin . " LIMIT 0, 1";
}

$resultado = mysql_query($sql)
or die (mysql_error());

echo (mysql_num_rows($resultado) != 0 ? "1" : "0");
?>