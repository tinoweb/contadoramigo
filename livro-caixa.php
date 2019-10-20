<?php 
	
	$consulta_dados_empresa_capa = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$_SESSION['id_empresaSecao']."' ");
	$objeto_capa=mysql_fetch_array($consulta_dados_empresa_capa);
	
	$data_inicio_capa = explode('-' ,$_GET['dataInicio'] );
	$data_fim_capa = explode('-' ,$_GET['dataFim']);

	$data_inicio_capa = $data_inicio_capa[2].'/'.$data_inicio_capa[1].'/'.$data_inicio_capa[0];
	$data_fim_capa = $data_fim_capa[2].'/'.$data_fim_capa[1].'/'.$data_fim_capa[0];

?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>	
	<div class="capa" style="position: relative;float: none;height: 1060px;border: 1px solid #024a68;margin-bottom:50px;">
		<center>
			<div style="width:100%;max-width:2480px;height:100vh;max-height:3508px;font-family: 'Open Sans', sans-serif;color:#024a68">
				<div style="font-size:50px;margin-top: 220px;margin-bottom: 150px;width:475px;">
					<div style="float: none;width: 475px;border: 1px solid #024a68;border-radius: 30px;padding-top: 10px;padding-bottom: 10px;">
						CAIXA
					</div>
				</div>
				<div style="position: relative;width:475px;">
					<div style="position: relative;float: none;width: 100%;margin-bottom:15px;">
						<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 15px;">Empresa</label>
						<input type="text" name="" value="<?php echo $objeto_capa['razao_social'] ?>" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
					</div>
					<div style="position: relative;float: none;width: 100%;margin-bottom:15px;">
						<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 15px;">Endereço</label>
						<input type="text" name="" value="<?php echo $objeto_capa['endereco'] ?>" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
					</div>
					<div style="position: relative;float: none;width: 100%;margin-bottom: 85px;">
						<div style="width:75%;float: left;position:relative;">
							<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 15px;">Cidade</label>
							<input type="text" name="" value="<?php echo $objeto_capa['cidade'] ?>" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
						</div>
						<div style="width:20%;float: right;margin-left:5%;position:relative;">
							<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 15px;">UF</label>
							<input type="text" name="" value="<?php echo $objeto_capa['estado'] ?>" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
						</div>
					</div>
					<!-- <div style="position: relative;float: none;width: 100%;margin-bottom:15px;">
						<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 20px;">Inscr. Est.</label>
						<input type="text" name="" value="" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
					</div> -->
					<div style="position: relative;float: none;width: 100%;margin-bottom:15px;">
						<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 13px;">CNPJ</label>
						<input type="text" name="" value="<?php echo $objeto_capa['cnpj'] ?>" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
					</div>
					<div style="position: relative;float: none;width: 100%;margin-bottom: 65px;">
						<!-- <div style="width:75%;float: left;position:relative;">
							<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 13px;">LIVRO Nº</label>
							<input type="text" name="" value="" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
						</div> -->
						<div style="position: relative;float: none;width: 100%;margin-bottom:15px;">
							<label style="position: absolute;top: -12px;padding-left: 4px;padding-right: 8px;;background: #ffffff;margin-left: 10px;font-size: 13px;">Período</label>
							<input type="text" name="" value="<?php echo $data_inicio_capa.' até '.$data_fim_capa ?>" style="padding-left: 15px;font-size: 17px;width: 100%;height: 50px;border-radius: 8px;border: 1px solid #024a68;">
						</div>
					</div>
				</div>
				<img src="http://ambientedeteste2.hospedagemdesites.ws/images/logo.png" style="width:140px;margin-top: 180px;">
			</div>
		</center>
	</div>
<style type="text/css" media="screen">
	@media print {
	    .capa {page-break-after: always;}
	}
</style>