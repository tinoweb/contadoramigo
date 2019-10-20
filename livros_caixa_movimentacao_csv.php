<?php 
session_start();
include 'conect.php';
//include 'session.php';
//include 'check_login.php' ;



$user_agent = getenv("HTTP_USER_AGENT");

if(strpos($user_agent, "Win") !== FALSE){
	$os = "Win";
	$charset = "UTF-8";
}elseif(strpos($user_agent, "Mac") !== FALSE){
	$os = "Mac";
	$charset = "WINDOWS-1252";
}
$os = "Mac";
	$charset = "WINDOWS-1252";

if($os == "Win"){
	
	function encode($text)
	{	
		$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
		return iconv($enc, "UTF-8", $text);	
	}
	
	$fileName = "livroCaixa_" . date('Ymd', strtotime($_GET["dataInicio"])) . "_a_" . date('Ymd', strtotime($_GET["dataFim"]));
	
	//header("Content-Type: application/ms-excel; charset=utf-8");
	//header("Content-type: application/octet-stream");
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	echo 'Empresa: '.($_SESSION["nome_userSecao"]) . "\n";
	
	echo ("Período") . ': '.date('d/m/Y', strtotime($_GET["dataInicio"])).' a '.date('d/m/Y', strtotime($_GET["dataFim"])) . "\n";
	
	echo 'Data;'.("Documento nº").';'.("Descrição").';'.("Categoria").';Entrada;'.("Saída").';Saldo' . "\n";
	
	$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data < '" . $_GET["dataInicio"] . "' ORDER BY data, id ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	while ($linha=mysql_fetch_array($resultado)) {
		$entrada = $linha["entrada"];
		$saida = $linha["saida"];
		$saldo = $saldo + $entrada - $saida;
	}
	
	$sqlL = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '" . $_GET["dataInicio"] . "' AND '" . $_GET["dataFim"] . "' ORDER BY data, id ASC";
	$resultadoL = mysql_query($sqlL)
	or die (mysql_error());
	while ($linhaL=mysql_fetch_array($resultadoL)) {
		
		$descricao = trataProlabore($linhaL["categoria"], $linhaL["descricao"]);
		
		$saldo = $saldo + $linhaL["entrada"] - $linhaL["saida"];
	echo ''.date('d/m/Y', strtotime($linhaL["data"])).';'.(str_replace(";","-",$linhaL["documento_numero"])).';'.(str_replace(";","-",$descricao)).';'.($linhaL["categoria"]).';'.number_format($linhaL["entrada"],2,",",".").';'.number_format($linhaL["saida"],2,",",".").';'.number_format($saldo,2,",",".") . "\n";
	}
	
} else {
	
	

	
	function encode($text)
	{	
		$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
		return iconv($enc, "ISO-8859-1", $text);	
	}
		
	$fileName = "livroCaixa_" . date('Ymd', strtotime($_GET["dataInicio"])) . "_a_" . date('Ymd', strtotime($_GET["dataFim"]));
	

	
	header("Content-Type: application/csv; charset=" . $charset . "");
	//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	echo 'Empresa: '.encode($_SESSION["nome_userSecao"]) . "\n";
	
	echo encode("Período") . ': '.date('d/m/Y', strtotime($_GET["dataInicio"])).' a '.date('d/m/Y', strtotime($_GET["dataFim"])) . "\n";
	
	echo 'Data;'.encode("Documento nº").';'.encode("Descrição").';'.encode("Categoria").';Entrada;'.encode("Saída").';Saldo' . "\n";
	
	$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data < '" . $_GET["dataInicio"] . "' ORDER BY data, id ASC";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	while ($linha=mysql_fetch_array($resultado)) {
		$entrada = $linha["entrada"];
		$saida = $linha["saida"];
		$saldo = $saldo + $entrada - $saida;
	}
	
	$sqlL = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '" . $_GET["dataInicio"] . "' AND '" . $_GET["dataFim"] . "' ORDER BY data, id ASC";
	$resultadoL = mysql_query($sqlL)
	or die (mysql_error());
	while ($linhaL=mysql_fetch_array($resultadoL)) {
		
		$descricao = trataProlabore($linhaL["categoria"], $linhaL["descricao"]);
		
		$saldo = $saldo + $linhaL["entrada"] - $linhaL["saida"];
	echo ''.date('d/m/Y', strtotime($linhaL["data"])).';'.encode(str_replace(";","-",$linhaL["documento_numero"])).';'.encode(str_replace(";","-",$descricao)).';'.encode($linhaL["categoria"]).';'.number_format($linhaL["entrada"],2,",",".").';'.number_format($linhaL["saida"],2,",",".").';'.number_format($saldo,2,",",".") . "\n";
	}
	
}

// Método criado para realizar o tratamento da categoria caso seja um Pró-labore, pois e necessario verificar o id do Sócio. 01-03-2017.
function trataProlabore($categoria, $descricao){
	
	if( $categoria == 'Pró-Labore' && is_numeric($descricao)){
														
		$qry = "SELECT * FROM dados_do_responsavel WHERE idSocio = '".$descricao."' AND id = '".$_SESSION['id_empresaSecao']."' ";
		
		$consulta = mysql_query($qry);
		
		$objeto_consulta = mysql_fetch_array($consulta);
		return $objeto_consulta['nome'];
	} else {
		return $descricao;
	}
}





