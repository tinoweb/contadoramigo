<?php 

// inclui o arquivo de conexão.
require_once "conect.php";

if(isset($_POST["email"]) && !empty($_POST["email"])) {

	//$sql="SELECT * FROM login WHERE email='" . $_GET["email"] . "' AND senha='" . $_GET["senha"] . "' LIMIT 0, 1";
	$sql="SELECT count(*) total FROM login WHERE email='" . $_POST["email"] . "' AND status != 'servico-avulso'";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	$linha = mysql_fetch_array($resultado);

	echo $linha['total'];
	
} else {
	echo 'Post não informado';
}