<?php 
include 'conect.php';
include 'session.php';
include 'check_login.php';

$empresa = $_GET["empresa"];

// pegando ID da empresa do sócio selecionado
$sql = "UPDATE dados_da_empresa SET ativa = 0, data_desativacao = '".date("Y-m-d")."' WHERE id=" . $empresa . " LIMIT 1";

$resultado = mysql_query($sql)
or die (mysql_error());

$_SESSION['n_empresasVinculadas']--;

$_SESSION["id_empresaSecao"] = '';
unset($_SESSION["id_empresaSecao"]);

header('Location: meus_dados_empresa.php' );
?>