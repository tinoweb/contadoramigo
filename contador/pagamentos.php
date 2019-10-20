<?php

	// ini_set('display_errors',1);
//	 ini_set('display_startup_erros',1);
//	 error_reporting(E_ALL);

	require_once 'header.php'; 
	require_once 'pagamentos-Controller.php';
?>
	<style>
		.tablePagamento td, .tablePagamento th{border: 1px solid #f5f6f7;}
		.tablePagamento .linhaStatus td {border-top:1px solid #f5f6f7;background: #d3d3d3; color:#676767; border-left: none; border-right:none;}	
    </style>
<div class="minHeight"> 
    <div style="float:right; margin-top: 15px;">
		<?php echo gerafiltro(); ?>       
    </div>
    <div class="titulo" style="text-align:left; margin-bottom:10px;">Pagamentos</div>    
    <div class="campoClientes">
		<?php echo geraTabelasPagamento();?>  
    </div>
    <div style="margin: 10px 0;"></div>
</div>    
<script>
	$(function() {
		
		$('.linkNFE').click(function(){
			
			// Pega o id ta tabela de cobranca.
			var idCobranca = $(this).attr('data-id');
				
				// oculta o link.
				$(this).hide();
				
				// oculta o link.
				$('#linkNF_'+idCobranca).hide();
				
				// Mosta o input.
				$('#edit_'+idCobranca).show();
				
				// inclui foco.
				$('#edit_'+idCobranca).focus();
	
	
		});
		
		// Realiza a acão de mudar o focus quando apertar o ENTER. 
		$('.inputOutEdit').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				
				$('#selAno').focus();
			}
		});
		
		$('.inputOutEdit').focusout(function(){
			// Pega o id ta tabela de cobranca.
			var idCobranca = $(this).attr('data-id');
			
			AlteraLinkNFE(idCobranca);
		});
		
		function AlteraLinkNFE(idCobranca) {
	
			var AuxlinkNFE = $('#edit_'+idCobranca).val();
	
			// Monta os parametos.
			var dados = {method:'AtualizaLinkNFE', cobrancaContadorId:idCobranca, linkNFE:AuxlinkNFE}
	
			var linkOK = false;
	
	
			// Ajax para realiza a atualização.
			$.ajax({
				type: "post",
				url: 'ajaxContador.php',
				dataType: "json",
				data: dados,
				async: true,
				cache: false,
				beforeSend: function() {
					// oculta o todos os campos e apresenta apenas o loading.
					$('#linkNF_'+idCobranca).hide();
					$('#edit_'+idCobranca).hide();
					$('#btAlt_'+idCobranca).hide();
					$('#loading_'+idCobranca).show();
				},
				success: function(data) {
					
					var linkRetorno = data['linkNFE'];
					
					// adiciona o link da NFE
					$('#edit_'+idCobranca).val(linkRetorno);
	
					if(linkRetorno.length > 0){

						$('#linkNF_'+idCobranca).attr('href', linkRetorno);
						
						linkOK = true;
					}
					
					$('#loading_'+idCobranca).hide();
					$('#btAlt_'+idCobranca).show();
					
					if(linkOK){
						$('#linkNF_'+idCobranca).show();
					}
				},
				error: function(error) { 
					alert('Não foi possível realizar a alteração');
				},
				complete: function() {}
			});	
		}
	});

</script>    
    
    
    
    
    
<?php require_once '../rodape.php'; ?>