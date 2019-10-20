<?php 
	
	session_start();
	include 'conect.php';
	
	include 'livro-diario-razao.class.php';
	
	$livro_diario = new Livro_diario();
	$plano_contas = new Plano_de_contas();
	$intervalo_inicio = getAnoInicio();
	$intervalo_fim = getAnoFim();
	$tipo = getTipo();
	
	if( $tipo == 'diario' ){
		$fileName = 'Livro Diário';

	}
	if( $tipo == 'razao' ){
		$fileName = 'Livro Razão';		
	}

	
	// $resultado_balanco .= executeDRECSV($ano);

	

	function encode($text)
	{	
		$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
		return iconv($enc, "ISO-8859-1", $text);	
	}



	// header("Content-Type: application/csv; charset=WINDOWS-1252,UTF-8");
	// //header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	// header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
	// header("Expires: 0");
	// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	// header("Cache-Control: private",false);
	// echo encode($resultado_balanco);

?>
