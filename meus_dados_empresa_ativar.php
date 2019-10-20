<?php 
include 'conect.php';
include 'session.php';
include 'check_login.php';

$empresa = $_GET["empresa"];

// pegando ID da empresa do sócio selecionado
$sql = "UPDATE dados_da_empresa SET ativa = 1, data_desativacao = '' WHERE id=" . $empresa . " LIMIT 1";

$resultado = mysql_query($sql)
or die (mysql_error());

$_SESSION['n_empresasVinculadas']++;

header('Location: meus_dados_empresa.php' );
?>