<?php

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	require_once ('header.php'); 
	require_once ('altera_contador_do_servico-Controller.php');
	
	$alteraContadorServicos = new AlteraContadorServicos();
?>

<div class="minHeight"> 
   	   	
  	<div style="margin-bottom: 25px; text-align: left;">
		<span class="titulo"  style="margin-right: 10px;">Altera o contador</span>
		<a href='/admin/altera_contador_do_cliente.php'>Premium</a> &nbsp;&nbsp; <a href='/admin/altera_contador_do_servico.php'>Avulsos</a>
   	</div>
   	 	
	<div class="tituloVermelho" style="text-align: left; margin-bottom: 15px;">
		Avulsos
	</div>   	
  	
   	<div style="text-align: right;">
		<!--span><?php echo $alteraContadorServicos->QuantidadeServicos()?></span-->
	</div>
   	
    <div class="campoClientes">
    	<?php echo $alteraContadorServicos->MontaTabelaComServicos(); ?>
    </div>
    
</div>

<script>
	
	$(function(){
		
		$('.btAlterar').click(function(){
			
			// Pega o id do form.
			var ref = $(this).attr('data-ref');
			
			// Realiza o envio do form.
			$(ref).submit();
		});
		
	});
	
</script>

<?php require_once '../rodape.php'; ?>
