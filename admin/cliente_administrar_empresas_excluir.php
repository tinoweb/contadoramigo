<?
include '../conect.php';
include '../session.php';
include 'check_login.php';

$empresa = $_GET["empresa"];

if($_GET["acao"] == "desativar"){
	// pegando ID da empresa do sócio selecionado
	$sql = "UPDATE dados_da_empresa SET ativa = 0, data_desativacao = '".date("Y-m-d")."' WHERE id=" . $empresa . " LIMIT 1";
}else{
	$sql = "UPDATE dados_da_empresa SET ativa = 1, data_desativacao = '' WHERE id=" . $empresa . " LIMIT 1";
}

$resultado = mysql_query($sql) or die (mysql_error());

echo json_encode(array('status'=>$resultado));
?>