<?
session_start();

require_once 'conect.php' ;

$sql = "SELECT id, nome, dependentes, aliquota_ISS, perc_pensao, inscr_municipal, cidade FROM dados_autonomos WHERE id = '" . $_GET['aut'] . "' LIMIT 0, 1";

$resultado = mysql_query($sql) or die (mysql_error());

if(mysql_num_rows($resultado) > 0){
	
	// PEGANDO DADOS DA EMPRESA ONDE O AUTONOMO TRABALHA E RETEM INSS TAMBEM
	$sql = "SELECT * FROM dados_pagamentos_empresas_anteriores WHERE id_trabalhador = " . $_GET['aut'];
	$resultado2 = mysql_query($sql) or die (mysql_error());
	$dados2 = mysql_fetch_array($resultado2);

	// TRAZENDO A SOMA DOS INSS QUE ESTE AUTONOMO TEVE NO MÊS
	$sql = "SELECT SUM(INSS) soma_inss FROM dados_pagamentos WHERE id_autonomo = " . $_GET['aut'] . " AND MONTH(data_pagto) = " . (int)substr($_GET['dtPagto'],3,2);
	$resultado3 = mysql_query($sql) or die (mysql_error());
	$dados3 = mysql_fetch_array($resultado3);
	
	
	// TRAZENDO OS DADOS DO AUTONOMO PARA SEREM USADOS NO VALUE DO COMBO AUTONOMOS
	$dados = mysql_fetch_array($resultado);
	echo $dados['id'] . '|' . $dados['nome'] . '|' . $dados['dependentes'] . '|' . $dados['aliquota_ISS'] . '|' . $dados['perc_pensao'] . '|' . $dados2['nome_empresa'] . '|' . $dados2['cidade_empresa'] . '|' . $dados3['soma_inss'] . '|' . $dados['inscr_municipal'] . '|' . $dados['cidade'];
}else{
	echo '0';
}
?>