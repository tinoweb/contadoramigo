<?php 
	session_start();
	include 'conect.php';
	include 'dre.class.php'; 

	

	$id = $_GET["id"];
	
	$ano = $_GET['ano'];

	$fileName = 'Demonstração do Resultado do Exercício de '.$ano;

	$dre = new Gerar_DRE();
	$dre->setano($ano);
	$dre->gerarDre();		
	$resultado_balanco = "";

	$resultado_balanco .= $dre->gerarTabelaCSV();

	// $resultado_balanco .= $balanco_patrimonial->gerarTabelaCSV();	

	function encode($text)
	{	
		$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
		return iconv($enc, "ISO-8859-1", $text);	
	}



	header("Content-Type: application/csv; charset=WINDOWS-1252,UTF-8");
	//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	echo encode($resultado_balanco);

?>
