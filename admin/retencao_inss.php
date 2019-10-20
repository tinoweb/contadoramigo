<?php 
/**
 * Data: 20/07/2017
 * Auntor: Átano de FariasJacinto
 */
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

 // Relaiza a requisição do arquivo de conexão
 require_once('../conect.php');

 // Relaiza a requisição dos arquivos de controle de sessão.
 require_once('../session.php');
 require_once('check_login.php');

 // Realiza a requisição do arquivo de header
 require_once('header.php');  

 // Realiza a requisição dos dados que realiza o controle do INSS. 	
 require_once('retencao_inss-Controller.php');

 $retencINSS = new RetencaoINSSController();

?>
<style>

	td{
		background-color: #FFFFFF;	
	}
	
</style>	
<div class="principal">

	<div class="titulo" style="margin-bottom:10px;">INSS</div>

		<div style="display: block; margin-bottom:20px;">
			
			<label class="tituloVermelho" style="display: block; margin-top:20px; margin-bottom:20px;">Tabela INSS</label>
			
			<form id="formFiltro" action="retencao_inss.php" method="get">
				<label style="margin-right:5px;">Ano da tabela do INSS: </label>
				<select id="anoFiltro" name="ano" style="margin-right: 10px;">
					<?php echo $retencINSS->SelectAnoFiltro();?>							
				</select>
			</form>
			<br/>
			<form method="post" action="retencao_inss_grava.php">
				<table border="0" cellspacing="2" cellpadding="4" style="font-size:12px" width="430">
					<tr>
						<th colspan="2" align="center"><b>Salário de Contribuição</b></th>
						<th align="center"><b>Alíquota</b></th>
					</tr>	
					<?php echo $retencINSS->GeraLinhaTabelaINSS(); ?>
				</table>
				
				<br/>
				<input value="Salvar" type="submit">
			</form>	
		</div>
</div>	

<script>

	$('#anoFiltro').change(function(){
		$('#formFiltro').submit();
	});
	
</script>

<?php include '../rodape.php' ?>
