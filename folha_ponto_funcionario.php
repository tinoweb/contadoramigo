<?php 

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

require_once('header_restrita.php');

// Realiza a requisição da chamada do controle.
require_once('Controller/lista-funcionario-holerite_contador-controller.php');
$LinhasGrid = new ServicosAssinaturaContador();
$empresaId = $_SESSION['id_empresaSecao'];
$tabela = $LinhasGrid->itensTabela($empresaId);
?>

<style>
	.linha1 td {
		background-color: #FFF;	
		padding: 5px 20px;
	}
	body {
	    text-align: left;
	}
</style>

<div class="principal minHeight">

        <h1>RH</h1>
		<h2>Folha de Ponto</h2>
       A folha de ponto é um documento importante para comprovar as faltas, atrasos e horas extras dos seus funcionários. Existem vários aparelhos e softwares no mercado para este fim, porém os custos destes sistemas não se justificam para uma microempresa com poucos funcionários. Assim, elaboramos esta folha de ponto simples em PDF. Imprima-a mensalmente e deixe com seu funcionário para que ele preencha as horas de entrada, saída, intervalos para almoço e assine diariamente. Terminado o mês recolha o documento e arquive-o.<br>
<br>

        
        
			<?php echo $tabela;?>
        
	</div>


<script>
	
	$(function(){
		
		$('.geraFolhaPonto').click(function(){
			
			var funcionarioId = $(this).attr('data-funcId');
			var empresaId = <?php echo $empresaId;?>;
			var mesAno = $('#'+funcionarioId).val();
			var status = true;
					
			mesAno = mesAno.split("/");
			var ano = mesAno[1];
			var mes = mesAno[0];
			
			var dataPgtoFomat = ano+'/'+mes+'/01';

			var checkDataPgto = new Date(dataPgtoFomat);
			
			if(checkDataPgto == 'Invalid Date') {
				alert('informe a data correta.');
				$('#'+funcionarioId).focus();
				status = false;
			}
			
			if(status) {
				
				window.open('gerar_folha_ponto.php?funcionarioId='+funcionarioId+'&empresaId='+empresaId+'&ano='+ano+'&mes='+mes, '_blank');
				
			}
			
		});
		
		$('.mskData').keypress(function(){
			
			var valor = '';
			
			valor = $(this).val().replace(/\D/g,"");
			valor = valor.replace(/(\d{2})(\d)/,"$1/$2");
			valor = valor.replace(/(\d{2})(\d{2})$/,"$1$2");
			
			$(this).val(valor);
		});

	});
	
</script>	

<?php include 'rodape.php' ?>