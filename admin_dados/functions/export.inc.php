<?php 
    ob_start();
    include "../seguranca.php"; // Inclui o arquivo com o sistema de segurança
    protegePagina(); // Chama a função que protege a página

    require '../editar/configuracoes.inc.php';
    require '../editar/paginas.class.php';
    require 'db_connect.inc.php';
    require 'crud.inc.php';
	require 'functions.inc.php';
    include 'variaveis.inc.php';
	$base = "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')+1);

	switch ($_GET['tipo']):
		case 'csv':
			# Pega os dados necessários
			$tabela = $_GET['tabela'];
			$campo = $_GET['campo'];
			$valores_csv = array();

			# Pega todos os emails
			$sql = mysql_query("SELECT * FROM $tabela WHERE $campo <> ''");
			while($row = mysql_fetch_array($sql)):
				if (filter_var($row[$campo], FILTER_VALIDATE_EMAIL) && $row[$campo] <> ''):
					$valores_csv[] = array($row[$campo]);
				endif;
			endwhile;

			# Debug
			# var_dump($valores_csv);

			# Envia para o navegador
			download_send_headers("resources/emails_".$tabela."_" . date("Y-m-d") . ".csv");
			echo array2csv($valores_csv);
			die();
		break;
	endswitch;

	# Funções
	function array2csv($array) {
	   if(count($array) == 0):
	     return null;
	   endif;
	   ob_start();
	   $df = fopen("php://output", 'w');
	   #fputcsv($df, array_keys(reset($array)));
	   foreach ($array as $row):
	      	fputcsv($df, $row);
	   endforeach;
	   fclose($df);
	   return ob_get_clean();
	}

	# Download
	function download_send_headers($filename) {
	    # Desabilita o Cache
	    $now = gmdate("D, d M Y H:i:s");
	    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	    header("Last-Modified: {$now} GMT");

	    # Força o download
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");

	    # Envia
	    header("Content-Disposition: attachment;filename={$filename}");
	    header("Content-Transfer-Encoding: binary");
	}

    ob_end_flush();
?>