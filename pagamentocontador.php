<?php
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

	require_once 'header.php'; 
	require_once 'pagamentocontador-Controller.php';
?>
	<style> 
		a.iconeSalva {color: #FFF; font-weight: bold;}
		a.iconeSalva:link {color: #FFF;}
		a.iconeSalva:hover {color: #FF0;}	
    </style>
    <div style="float:right; margin-top: 15px;">
		<?php echo gerafiltro(); ?> 
        <br/>  
    </div>
    <div class="titulo" style="text-align:left; margin-bottom:10px;">Pagamentos - <?php echo pegaNomeContador();?></div>    
    <div class="campoClientes">
		<?php echo geraTabelasPagamento();?>  
    </div>
    <div style="margin: 10px 0;"></div>
	
	<div class="quadro_branco" id="campoConfirmacao" style="padding: 0px; width: 380px; height: 127px; position: absolute; left: 50%; margin-left: -223px; margin-top: -120px; top: 50%; display: none; z-index: 999; background: rgb(245, 246, 247) none repeat scroll 0% 0%;">
		<div style='padding: 15px 15px;'>
			Verifique se os dados estão corretos e clique em confirmar.
			<b id="infoMesAno" style="display: block; width: 100%; text-align: left; padding-left: 10px; margin-top: 8px; margin-bottom: 8px;"></b>
			<b id="infoValorPago" style="display: block; width: 100%; text-align: left; padding-left: 10px; margin-bottom: 9px;"></b>
			<button id="btConfirma" data-form="">Confirmar</button>
			<button id="btCancelarAcao" >Cancelar</button>
		</div>    
	</div>
	
	<script>
		
		$(function(){
			
			$(".gravaValor").click(function(){
				
				var dataForm = $(this).attr('data-mes');
				
				// passa o parametro do form para o button
				$('#btConfirma').attr('data-form', dataForm);
				
				// passa o mes e o ano.
				$('#infoMesAno').html('Mês: '+$('#formGrava_'+dataForm+' #mesAno').val());
				
				// passa o valor.
				$('#infoValorPago').html('Valor Pago: R$ '+$('#formGrava_'+dataForm+' #valorPagamento').val());
				
				// abre a janela de confirmaçao.
				$("#campoConfirmacao").show();
			});
			
			$("#btCancelarAcao").click(function(){
				// Fecha a janela de confirmaçao.
				$("#campoConfirmacao").hide();
			});
			
			$('#btConfirma').click(function(){
				
				var formGrava = '#formGrava_' + $(this).attr('data-form');
		
				// realiza o envio dos dados.
				$(formGrava).submit();
			});
			
		});
	
	</script>
	
<?php require_once '../rodape.php'; ?>