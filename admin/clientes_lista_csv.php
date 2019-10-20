<?
session_start();
include '../conect.php';
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

$fileName = "clientesLista_" . date('Ymd-His');



if($os == "Win"){

	
	function encode($text)
	{	
		$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
		//return iconv($enc, "ISO-8859-1", $text);	
		return iconv($enc, "UTF-8", $text);	
	}
	
	function encode2($text)
	{	
		//$enc = mb_convert_encoding($text,'WINDOWS-1252');
		$enc = mb_convert_encoding($text, 'UTF-16LE', 'UTF-8');
		return ($enc);	
	}
	
	//header("Content-Type: application/ms-excel; charset=utf-8");
	//header("Content-type: application/octet-stream");
	//header("Content-Type:   application/vnd.ms-excel; charset=UTF-8");
	//header("Content-Type: text/csv; charset=" . $charset . "");
	header("Content-Type: application/x-download");
	
	//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	echo 'Lista de clientes gerada em '.(date("d/m/Y - H:i:s")) . "\n";
	
	$sql = $_SESSION['SQLLISTA'];
	$resultado = mysql_query($sql) or die (mysql_error());
	
	echo "Dados dos Clientes" . "\n";
	echo "\n";
	
	if($_SESSION['SQLLISTATIPOCONSULTA'] == "empresa"){ 
	
		echo ("CNPJ;Razão Social;Assinante (nº de empresas);Email") . "\n";
	
	} else { 
	
		echo ("Assinante (nº de empresas);Telefone;Email") . "\n";
	
	}
	
	
	while ($linha=mysql_fetch_array($resultado)) {
		
		$qtdEmpresas = mysql_num_rows(mysql_query("SELECT id FROM dados_da_empresa WHERE id IN (SELECT id FROM login WHERE idUsuarioPai = " . $linha["id"] . ")"));	
		
		if($_SESSION['SQLLISTATIPOCONSULTA'] == "empresa"){ 
			
			echo $linha["cnpj"] . ";" . ($linha["razao_social"]) . ";" . ($linha["assinante"]) . " (" . $qtdEmpresas . ");" . $linha["email"] . ";" . "\n";
		
		} else { 
			$telefone = "";
			if($linha["telefone"] != ""){
				if(strlen($linha["telefone"]) == 8){
					$telefone = "(" . $linha["pref_telefone"] . ") " . substr($linha["telefone"],0,4) . "-" . substr($linha["telefone"],-4);
				}else{
					$telefone = "(" . $linha["pref_telefone"] . ") " . substr($linha["telefone"],0,5) . "-" . substr($linha["telefone"],-4);
				}
			}
		
			echo ($linha["assinante"]) . " (" . $qtdEmpresas . ");" . $telefone . ";" . $linha["email"] . ";" . "\n";
		
		}
	}
}else{
	function encode($text)
	{	
		$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
		return iconv($enc, "ISO-8859-1", $text);	
//		return iconv($enc, "UTF-8", $text);	
	}
	
	function encode2($text)
	{	
		//$enc = mb_convert_encoding($text,'WINDOWS-1252');
		$enc = mb_convert_encoding($text, 'UTF-16LE', 'UTF-8');
		return ($enc);	
	}
	
	//header("Content-Type: application/ms-excel; charset=utf-8");
	//header("Content-type: application/octet-stream");
	//header("Content-Type:   application/vnd.ms-excel; charset=UTF-8");
	//header("Content-Type: text/csv; charset=" . $charset . "");
	header("Content-Type: application/csv; charset=" . $charset . "");
	
	//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	echo 'Lista de clientes gerada em '.(date("d/m/Y - H:i:s")) . "\n";
	
	$sql = $_SESSION['SQLLISTA'];
	$resultado = mysql_query($sql) or die (mysql_error());
	
	echo "Dados dos Clientes" . "\n";
	echo "\n";
	
	if($_SESSION['SQLLISTATIPOCONSULTA'] == "empresa"){ 
	
		echo ("CNPJ;Razão Social;Assinante (nº de empresas);Email") . "\n";
	
	} else { 
	
		echo encode("Assinante (nº de empresas);Telefone;Email") . "\n";
	
	}
	
	
	while ($linha=mysql_fetch_array($resultado)) {
		
		$qtdEmpresas = mysql_num_rows(mysql_query("SELECT id FROM dados_da_empresa WHERE id IN (SELECT id FROM login WHERE idUsuarioPai = " . $linha["id"] . ")"));	
		
		if($_SESSION['SQLLISTATIPOCONSULTA'] == "empresa"){ 
			
			echo $linha["cnpj"] . ";" . ($linha["razao_social"]) . ";" . ($linha["assinante"]) . " (" . $qtdEmpresas . ");" . $linha["email"] . ";" . "\n";
		
		} else { 
			$telefone = "";
			if($linha["telefone"] != ""){
				if(strlen($linha["telefone"]) == 8){
					$telefone = "(" . $linha["pref_telefone"] . ") " . substr($linha["telefone"],0,4) . "-" . substr($linha["telefone"],-4);
				}else{
					$telefone = "(" . $linha["pref_telefone"] . ") " . substr($linha["telefone"],0,5) . "-" . substr($linha["telefone"],-4);
				}
			}
		
			echo encode($linha["assinante"]) . " (" . $qtdEmpresas . ");" . $telefone . ";" . $linha["email"] . ";" . "\n";
		
		}
	}
	
}

?>