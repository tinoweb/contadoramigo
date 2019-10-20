<?php 
	
	session_start();
	include 'conect.php';
	include 'datas.class.php';
	include 'balanco.class.php'; 
	
	$balanco_patrimonial = new Balanco_patrimonial();

	$id = $_GET["id"];
	
	$ano = $_GET['ano'];

	$fileName = 'Balanço Patrimonial encerrado em 31/12/'.$ano;

	$consulta = mysql_query("SELECT * FROM balanco_patrimonial WHERE id_user = '".$id."' AND ano = '".$ano."' ");
	$objeto=mysql_fetch_array($consulta);

	$balanco_patrimonial->setDados($objeto,$id,$ano);
	$resultado_balanco = "";

	// $resultado_balanco .= executeDRECSV($ano);

	$resultado_balanco .= $balanco_patrimonial->gerarTabelaCSV();	

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
