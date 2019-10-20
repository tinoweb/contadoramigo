<?php include 'header_restrita.php' ?>

<!-- segunda pergunta -->
<div class="principal" style="display:block">

	<span class="titulo">Impostos e Obrigações</span><br /><br />
	<div style="margin-bottom:20px" class="tituloVermelho">Apuração do Simples</div>
	O Simples é o imposto único (engloba o ISS, IR, CSLL, Cofins, PIS e CPP) calculado com base no faturamento e tipo de atividade da empresa. Para fazer a apuração e emitir a guia de pagamento (DAS) você precisará ter um <strong>certificado digital </strong> ou obter um <strong>código de acesso</strong>. <br>
	<br>
	O certificado digital é mais conveniente, pois lhe permitirá também fazer o envio da Gfip. Assinantes do Contador Amigo podem adquiri-lo pela Valid Certificadora com um super desconto: apenas R$ 189,75, em 3 x sem juros. <a href="http://www.validcertificadora.com.br/e-CNPJ-A1.htm/388C546B-98DA-4A0E-B8F3-89BBA8FB5FEE/RD007797" target="_blank">Solicite agora mesmo o seu</a>.<br>
	<br>
	Se você não pretende adquirir um certificado agora, <a href="http://www8.receita.fazenda.gov.br/SIMPLESNACIONAL/controleAcesso/GeraCodigo.aspx" target="_blank">gere aqui seu código de acesso</a>. Durante o processo será necessário informar o número do recibo de entrega de sua última declaração do Imposto de Renda como <strong>pessoa física</strong>. Se você não tiver, não poderá gerar seu código de acesso. <br>
	<br>
	Após adquirir seu certificado ou o código de acesso, responda a pergunta abaixo para prosseguir. Você pode usar este tutorial <strong>também para quitar pagamentos em atraso</strong>.
	<div class="quadro_passos" style="text-align: center">
		<div class="perguntas_simples" style="text-align: left;">
			Houve faturamento em sua empresa no período?
			<div style="display: inline-block; margin-left: 10px;">
				<div class="opcao" id="status_faturamento_sim" data-status="0">Sim</div>
				<div class="opcao" id="status_faturamento_nao" data-status="0">Não</div>
				<input type="hidden" id="status_faturamento" val="" />	
			</div>
			<br>
			<br>			
			<span style="display: inline-block;">O período de apuração e o de 2018?</span>
			<div style="display: inline-block; margin-left: 10px;">
				<div class="opcao" id="status_2018_sim" data-status="0">Sim</div>
				<div class="opcao" id="status_2018_nao" data-status="0">Não</div>
				<input type="hidden" id="status_2018" val="" />
			</div>
			<br>
			<br>
			<div class="bt_opcao_quadro bt_cursor" style="width:120px; padding:5px 0;" id="bt_continuar">Continuar</div>		
		</div>
	</div>
</div>
<!--fim do div principal -->

<script>
	
	jQuery(document).ready(function($){
	
		$('#status_faturamento_sim').click(function(){
			
			$(this).css("background-color", "#a61d00");
			$(this).attr('data-status', 1);
			
			$('#status_faturamento_nao').css("background-color", "#024a68");
			$('#status_faturamento_nao').attr('data-status', 0);
			$('#status_faturamento').val('sim');
		});
		
		$('#status_faturamento_nao').click(function(){
			
			$('#status_faturamento_nao').css("background-color", "#024a68");
			$('#status_faturamento_sim').attr('data-status', 0);
			
			$(this).css("background-color", "#a61d00");
			$(this).attr('data-status', 1);
			$('#status_faturamento').val('nao');
		});		
				
		$('#status_2018_sim').click(function(){
			$(this).css("background-color", "#a61d00");
			$(this).attr('data-status', 1);
			
			$('#status_2018_nao').css("background-color", "#024a68");
			$('#status_2018_nao').attr('data-status', 0);
			$('#status_2018').val('sim');
		});
		
		$('#status_2018_nao').click(function(){
			
			$('#status_2018_sim').css("background-color", "#024a68");
			$('#status_2018_sim').attr('data-status', 0);
			
			$(this).css("background-color", "#a61d00");
			$(this).attr('data-status', 1);
			
			$('#status_2018').val('nao');
		});

		$('#bt_continuar').click(function(){
			
			// realiza a validação dos botões
			if(validacao()){
				
				var status_faturamento = $('#status_faturamento').val();
				var status_2018 = $('#status_2018').val();
				
				if(status_faturamento == 'sim' && status_2018 == 'sim') {
					window.location.assign("simples_orientacoes_2_opcoes.php");
				} else if(status_faturamento == 'sim' && status_2018 == 'nao') {
					window.location.assign("simples_orientacoes_opcoes.php");
				} else {
					window.location.assign("simples_orientacoes_sem_movimento.php");
				}
			}			
		});
		
		function validacao() {

			if($('#status_faturamento_sim').attr('data-status') == 0 && $('#status_faturamento_nao').attr('data-status') == 0){
				alert('Por favor, informe se houve faturamento em sua empresa no período?');
				return false;
			}
						
			if($('#status_2018_sim').attr('data-status') == 0 && $('#status_2018_nao').attr('data-status') == 0){
				alert('Por favor, informe se o período de apuração e a partir de 2018?');
				return false;
			}
			
			return true;
		}
		
		$(".bt_cursor").mouseenter(function() {
			$(this).css("background-color", "#a61d00");
		}).mouseleave(function(){
			$(this).css("background-color", "#024a68");
		});		
	});
</script>

<?php include 'rodape.php' ?>

