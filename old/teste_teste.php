<?php 
	
	include 'conect.php';

	$consulta_boleto = mysql_query("SELECT * FROM boleto");
	while( $objeto_consulta_boleto = mysql_fetch_array($consulta_boleto) ){

		$consulta_cobranca = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$objeto_consulta_boleto['user']."' ");
		$objeto_consulta_cobranca = mysql_fetch_array($consulta_cobranca);
		// echo $objeto_consulta_boleto['sacado_get'];
		// echo ' - ';
		// echo $objeto_consulta_cobranca['sacado'];
		// echo '<br>';
		mysql_query("UPDATE boleto SET sacado_get = '".$objeto_consulta_cobranca['sacado']."' WHERE id = '".$objeto_consulta_boleto['id']."' ");
	}

?>