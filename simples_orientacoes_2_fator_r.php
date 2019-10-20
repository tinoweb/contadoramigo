<?php

session_start();

// inclui o arquivo de conexão com o banco.
require_once('conect.php');

// Faz a rquisição do cabeçalho.
require_once('header_restrita.php');

?>

<div class="principal minHeight">

	<h1>Impostos e Obrigações</h1>
	
	<h2>Apuração do Simples</h2>

	<div class="quadro_passos" style="display: table; width: 95%">
		
		<form id="formPergunta" action="simples_orientacoes_2_retencao.php" method="post">
		
			<div style="font-size:20px; margin-top:30px; margin-bottom:20px;" class="perguntas_simples2">
				A folha de pagamento da empresa (pró-labores + salários + trabalhadores avulsos), nos 12 meses que atecedem o período de apuração:
			</div>

			<div style="margin-bottom:5px; font-size:15px" class="perguntas_simples2">
				<label>
					<input id="maiorIgual28" type="radio" name="porc28" value="maiorIgual">&nbsp;&nbsp;
					é igual ao superior a 28% do faturamento bruto do mesmo período
				</label>
			</div>

			<div style="margin-bottom:5px; font-size:15px" class="perguntas_simples2">
				<label>
					<input id="menor28" type="radio" name="porc28" value="menor">&nbsp;&nbsp;
					é inferior a 28% do faturamento bruto do mesmo período
				</label>
			</div>
			
			<div style="margin-top:20px; font-size:15px" class="perguntas_simples2">
				<span>Obs.: quando a folha de pagamento for igual ao superior a 28%, você pagará uma alíquota bem menor de imposto! <!--Calcule aqui relação faturamento x folha de pagamento em sua empresa. --></span>
			</div>
			
			<div style="margin: 25px auto 30px auto; display: table;">
				<div class="navegacao" id="btnContinuar" style="margin-left: 10px;">Continuar</div>
			</div>
			
		</form>
		
	</div>
</div>	

<script>
	
	$(function(){
		
		$('#btnContinuar').click(function(){
	
			var status = false;
						
			$("input[name='porc28']").each(function() {
				
				if($(this).is(":checked")){
					status = true;	
				}
				
			});
			
			if(status){
				$('#formPergunta').submit();
			}else{
				alert('Por favor, selecione uma resposta');
			}		
		});
		
	});
	
</script>

<?php include 'rodape.php' ?>