<?php 
include "conect.php";

$tabela 	= $_REQUEST['tb'];
$id 		= $_REQUEST['id'];
$retorno 	= $_REQUEST['retorno'];

if((isset($id) && $id != '') && (isset($tabela) && $tabela != '') && (isset($retorno) && $retorno != '') && ($tabela != 'login')){

	$sql="SELECT ".$retorno." FROM ".$tabela." WHERE id='" . trim($id) . "' LIMIT 0, 1";


	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$result = mysql_fetch_array($resultado);
	
	echo (mysql_num_rows($resultado) != 0 ? json_encode($result) : "");

}else{

	echo "";

}
?>