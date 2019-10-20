<?php 
	
	include '../conect.php';

	$id = $_GET['file'];

	$consulta = mysql_query("SELECT * FROM arquivo_remessa WHERE id = '".$id."' ");
	$objeto=mysql_fetch_array($consulta);

	$file_name = 'files/'.$objeto['nome'];

	$consulta = mysql_query("UPDATE arquivo_remessa SET baixado = 1 WHERE id = '".$id."' ");

	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename="'.date("Y-m-d").'.rem'.'"');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($file_name));
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Expires: 0');
	// Envia o arquivo para o cliente
	readfile($file_name);

?>