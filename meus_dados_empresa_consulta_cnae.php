<?php 
include "conect.php";
session_start();

$codigo = str_replace(array("/","-","."),"",$_GET['codigo']);

$campoCheck = str_replace(array("/","-","."),"",$_GET['campoCheck']);

$idEmpresa = $_GET['idEmpresa'];

if(isset($_GET['acao']) && $_GET['acao'] != '' && $idEmpresa != ''){
	// procurando cnae já cadastrado para a empresa
	$check = mysql_query("SELECT * FROM dados_da_empresa_codigos WHERE id='" . $idEmpresa . "' AND REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$codigo' AND REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','') <> '$campoCheck' ");
	if(mysql_num_rows($check) > 0){
		// se retornou, bloqueia o cadastro novo
		echo json_encode(array("status"=>false,"mensagem"=>"CNAE já cadastrado!"));
		exit;
	}
}



$sql = "SELECT * FROM cnae WHERE REPLACE(REPLACE(REPLACE(cnae,'.',''),'-',''),'/','')='$codigo' LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		$linha=mysql_fetch_array($resultado);

if(mysql_num_rows($resultado) == 0) {
	echo json_encode(array("status"=>false,"mensagem"=>"CNAE não localizado!"));
	exit;
	echo '<input type="hidden" id="pesquisaCampo'.$_GET['campo'].'" name="pesquisaCampo'.$_GET['campo'].'" value="erro" />';
} else {
	echo json_encode(array("status"=>true,"mensagem"=>$linha["denominacao"]));
	exit;
	echo $linha["denominacao"] . '<input type="hidden" id="pesquisaCampo'.$_GET['campo'].'" name="pesquisaCampo'.$_GET['campo'].'" value="ok" />';
}
?>