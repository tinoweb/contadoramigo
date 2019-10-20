<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

	if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) { 
		$contadorId = $_GET['contadorId'];
	}

	require_once 'header.php'; 
	require_once 'pagamentocontador-Controller.php';
?>
	<style>
		.tablePagamento td, .tablePagamento th{border: 1px solid #f5f6f7;}
		.tablePagamento .linhaStatus td {border-top:1px solid #f5f6f7;background: #d3d3d3; color:#676767; border-left: none; border-right:none;}
		a.iconeSalva {color: #666; font-weight: bold;}
		a.iconeSalva:link {color: #666;}
		a.iconeSalva:hover {color: #00F;}
		a.linkMenu {display: inline-block; margin-left: 5px; margin-right: 5px;}
		a.linkMenuinicio {display: inline-block; margin-left: 25px; margin-right: 5px; text-decoration: none; color: #024a68;}
    </style>
    <div style="float:right; margin-top: 2px;">
		<?php echo gerafiltro(); ?> 
        <br/>  
    </div>
    <div style="text-align:left; margin-top:-15px;">
    	<span class="titulo">Pagamentos - <?php echo pegaNomeContador();?></span> 
		<a class="linkMenuinicio" href="clientes_premium_contador.php?contadorId=<?php echo $contadorId;?>">Premium</a>
		<a class="linkMenu" href="clientes_avulso_contador.php?contadorId=<?php echo $contadorId;?>">Avulsos</a>
		<a class="linkMenu" href="pagamentocontador.php?contadorId=<?php echo $contadorId;?>">Pagamentos</a>
   		<a class="linkMenu" href="listapagamentocontador.php?contadorId=<?php echo $contadorId;?>">Pagamentos Novo</a>
    </div>   
    <div class="campoClientes">
		<?php echo geraTabelasPagamento();?>  
    </div>
    
    <div style="margin: 10px 0;"></div>
	
	<script>
		
		$(function(){
			
			$(".gravaValor").click(function(){
				
				var formGrava = '#formGrava_' + $(this).attr('data-mes');
				
				// realiza o envio dos dados.
				$(formGrava).submit();
			});
			
		});
	
	</script>
	
<?php require_once '../rodape.php'; ?>