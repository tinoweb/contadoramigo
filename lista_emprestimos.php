<?php

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	require_once('header_restrita.php');
	require_once('Controller/lista_emprestimos-controller.php');

	$classEmprestimo = new ListaEmprestimosController();
?>
<style>
	.linha_entrada td {
		background-color: #ffcccc;
	}	
</style>

<div class="principal minHeight">
		
<div style="margin-bottom:20px;">
	<h1 class="titulo">Escrituração</h1>
	<h2>Empréstimos</h2>
	<div style='float: right; display:inline-block;'>
		<b>Ano:</b>&nbsp;&nbsp;&nbsp;<?php echo $classEmprestimo->PegaSelectAno();?>
	</div>	
</div>		
<div style="clear: both; height:10px;"></div>
	
			
<?php 
	// Mostra a(s) tabela(s) com do(s) emprestimo(s). 
	echo $classEmprestimo->GeraTabelasEmprestimos();
?>

</div>

<script>

	$(function(){
		
		$('#ano').change(function(){
			if($(this).val().length > 0){
				window.location.href = "lista_emprestimos.php?ano="+$(this).val();
			}
		});
		
	});

</script>

<?php include 'rodape.php'; ?>