<?php 

	include 'header_restrita.php';
	
	if( $_SESSION['status_userSecao']=='demo'){
		if( substr($_SERVER['REQUEST_URI'],0,23) == '/boleto_certificado.php' ){
			$_SESSION['erro_certificado'] = 'true';

			echo "<script>$( document ).ready(function() {location.href='minha_conta.php'	;});</script>";
			
		}
		return;
	}

	function geraTimestamp($data) {
		$partes = explode('-', $data);
		return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
	}

	function getBotaoPagar(){
	
	   	return "<iframe style='width:960px;height:1000px;border: 0;' src='https://www.contadoramigo.com.br/boleto.class.php?tipo=certificado&valor=140,00&data=".date("Y-m-d")."&id_user=".$_SESSION["id_userSecaoMultiplo"]."'></iframe>";

	}

	echo '<br>';
	echo '<br>';
	echo getBotaoPagar();

	include '../rodape.php';

?>