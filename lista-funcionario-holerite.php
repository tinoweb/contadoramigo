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

<div class="principal" style="margin-top: -17px;">
	<div style="width:966px" class="minHeight">

        <div class="titulo">Holerite Funcionarios</div>
        
        <div style="margin-top:30px;">
			<?php echo $tabela;?>
        </div>
	</div>
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