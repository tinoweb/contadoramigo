<?php 
include "conect.php";

$cpf = $_GET["cpf"];
$ID = $_GET["id"];
$IDLogin = $_GET["idLogin"];

// A VARIAVEL IDLOGIN VEM DO FORMULÁRIO DE CADASTRO DA PAGINA DE PAGAMENTOS SERVE PARA CHECAR SE O USUÁRIO JÁ TEM UM ESTAGIARIO CADASTRADO COM O MESMO CPF E/OU PIS
if(isset($ID) && $ID != ''){
	$sql="SELECT * FROM estagiarios WHERE cpf='" . $cpf . "' AND id <> " . $ID . " AND id_login = " . $IDLogin . " LIMIT 0, 1";
}else{
	$sql="SELECT * FROM estagiarios WHERE cpf='" . $cpf . "' AND id_login = " . $IDLogin . " LIMIT 0, 1";
}

$resultado = mysql_query($sql)
or die (mysql_error());

echo (mysql_num_rows($resultado) != 0 ? "1" : "0");
?>