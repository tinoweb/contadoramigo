<?php 
include "conect.php";

$codigo = $_GET['codigo'];

$sql = "SELECT * FROM codigos_prefeitura WHERE codigo_prefeitura='$codigo' LIMIT 0,1";
		$resultado = mysql_query($sql)
		or die (mysql_error());
		$linha=mysql_fetch_array($resultado);

if(mysql_num_rows($resultado) == 0) {
	echo '<input type="hidden" id="pesquisaCampoPrefeitura'.$_GET['campo'].'" name="pesquisaCampoPrefeitura'.$_GET['campo'].'" value="erro" />';
} else {
	echo $linha["denominacao"] . '<input type="hidden" id="pesquisaCampoPrefeitura'.$_GET['campo'].'" name="pesquisaCampoPrefeitura'.$_GET['campo'].'" value="ok" />';
}
?>