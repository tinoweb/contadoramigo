<?php
//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);	

	session_start();
	require_once('conect.php');
	require_once('DataBaseMySQL/DadosCobranca.php');
	
	if($_GET) {
	
		$dadosCobranca = new DadosCobranca();
		
		$dados = $dadosCobranca->pegaDadosCobranca($_GET['id_user']);
		
		if($_GET['plano'] != $dados['plano']) {
			
			$_SESSION['erroPlano'] = 'Plano_Diferente';
			
			header("location: /minha_conta.php");
			
		} else {
			
			// inclui o cabeçalho.
			include 'header_restrita.php';
		
			$link = explode('?', $_SERVER['REQUEST_URI']);
	
			// Define a variável que recebera o código do contador.
			$contadorId = 0;
			
			// Cria os parametros para gerar o boleto.
			$id_historico = $_GET['id_historico'];
			$tipo = $_GET['tipo'];
			$id_user = $_GET['id_user'];
			$tipo_plano = $_GET['tipo_plano'];
			$via = $_GET['via'];
			
			// Pega o código do contador
			if($_GET['tipo_plano'] == 'P') {
				$contadorId = mysql_fetch_array(mysql_query("SELECT `contadorId` FROM `contratos_aceitos` WHERE user = ".$id_user." AND contratoId = 2 order by id DESC LIMIT 1;"));
				$contadorId = $contadorId['contadorId'];
			}
	
			// Imprime o iframe.
			echo '<iframe src="boleto.class.php?id_historico='.$id_historico.'&tipo='.$tipo.'&id_user='.$id_user.'&tipo_plano='.$tipo_plano.'&via='.$via.'&contadorId='.$contadorId.'" style="width: 666px;border: 0;height: 1000px;"></iframe>';
	
		}
	}
	
	include 'rodape.php';
?>
<script type="text/javascript">

    $(document).ready(function() {
        document.title = 'Boleto-Contador-Amigo';
    });

</script>
