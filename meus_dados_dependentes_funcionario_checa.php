<?php 
include "conect.php";

$cpf = $_GET["cpf"];
$ID = $_GET["id"];

// A VARIAVEL IDLOGIN VEM DO FORMULÁRIO DE CADASTRO DA PAGINA DE PAGAMENTOS SERVE PARA CHECAR SE O USUÁRIO JÁ TEM UM AUTONOMO CADASTRADO COM O MESMO CPF E/OU PIS
if(isset($ID) && $ID != '' && $cpf != ''){

	$sql="SELECT * FROM dados_dependentes_funcionario WHERE (cpf='" . $cpf . "') AND idFuncionario = " . $ID . " LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	echo (mysql_num_rows($resultado) != 0 ? "a1" : "a0");

}else{

	$sql="SELECT idDependente FROM dados_dependentes_funcionario WHERE idFuncionario = " . $ID . "";
	$resultado = mysql_query($sql)
	or die (mysql_error());
//echo $sql;
	echo (mysql_num_rows($resultado));

}


?>