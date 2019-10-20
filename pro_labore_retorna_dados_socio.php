<?
session_start();

require_once 'conect.php' ;

$sql = "SELECT idSocio id, nome, dependentes, 0 aliquota_ISS, perc_pensao FROM dados_do_responsavel WHERE idSocio = '" . $_GET['soc'] . "' LIMIT 0, 1";

$resultado = mysql_query($sql) or die (mysql_error());

if(mysql_num_rows($resultado) > 0){

	
	
	// TRAZENDO OS DADOS DO AUTONOMO PARA SEREM USADOS NO VALUE DO COMBO AUTONOMOS
	$dados = mysql_fetch_array($resultado);
	echo $dados['id'] . '|' . $dados['nome'] . '|' . $dados['dependentes'] . '|' . $dados['aliquota_ISS'] . '|' . $dados['perc_pensao'] ;
}else{
	echo '0';
}
?>