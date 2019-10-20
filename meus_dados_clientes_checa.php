<?php 

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

include "conect.php";

$outroClienteApelido = 0;

$documento = $_GET["documento"];
$tipo = $_GET["tipo"];
$ID = $_GET["id"];
$IDLogin = $_GET["idLogin"];
$apelido = $_GET['apelido'];
$apelidoAntigo = $_GET['apelidoAntigo'];

// A VARIAVEL IDLOGIN VEM DO FORMULÁRIO DE CADASTRO DA PAGINA DE PAGAMENTOS SERVE PARA CHECAR SE O USUÁRIO JÁ TEM UMA EMPRESA FORNECEDORA CADASTRADA COM O MESMO CNPJ
if(isset($ID) && $ID != ''){
	$sql="SELECT * FROM dados_clientes WHERE ".$tipo."='" . trim($documento) . "' AND id <> " . $ID . " LIMIT 0, 1";
}else{
	$sql="SELECT * FROM dados_clientes WHERE ".$tipo."='" . trim($documento) . "' AND id_login = " . $IDLogin . " LIMIT 0, 1";
}
$resultado = mysql_query($sql)
or die (mysql_error());

// Verifica se o apelido já esta sendo usado por outro cliente.
$sql3 = " SELECT * FROM dados_clientes WHERE apelido = '". $apelido ."' AND id <> ". $ID ." AND id_login = ". $IDLogin ."; ";
$resultado2 = mysql_query($sql3)
or die (mysql_error());

// verifica se houve alteracão do apelido
$query = " SELECT * FROM dados_clientes WHERE apelido = '". $apelido ."' AND id = ". $ID ."; ";
if(mysql_num_rows(mysql_query($query)) == 0) {
	
	// Verificar se existe lançamento para o apelido.
	$sql2 = "SELECT * FROM user_".$IDLogin."_livro_caixa WHERE categoria = '".$apelidoAntigo."';";
	$resultado3 = mysql_query($sql2)
	or die (mysql_error());
}

// Verificar se o apelido e uma palavra restrita da categoria.
$sql4 = "SELECT * FROM categoria WHERE categoriaNome = '".$apelido."';";
$resultado4 = mysql_query($sql4)
or die (mysql_error());

//echo (mysql_num_rows($resultado) != 0 ? "1" : "0");

if( mysql_num_rows($resultado) > 0 ){
	echo 1;
} elseif(  mysql_num_rows($resultado2) > 0 ) {
	echo 2;
} elseif( mysql_num_rows($resultado3) > 0 ) {
	echo 3;
} elseif( mysql_num_rows($resultado4) > 0 ) {
	echo 4;
} else {
	echo 0;
}

?>