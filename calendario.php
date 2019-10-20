<?php include 'header_restrita.php' ?>
<?php include 'calendario.class.php' ?>
<?php 
	
	if( isset($_GET['ano']) )
		$ano = $_GET['ano'];
	else
		$ano = date('Y');

?>

<div class="principal">
	<h1>Calendário de Obrigações <?php echo $ano ?></h1>
	
	<?php 
		
		$calendario = new Calendario();
	?>
	<!-- Janeiro -->
	<div class="item_calendario" style="">
		<?php 	
			$calendario->mostrarCalendario(1,$ano);
			$calendario->getItensMes(1);
		 ?>
	</div>
	<!-- Fevereiro -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(2,$ano);
			$calendario->getItensMes(2);
		 ?>
	</div>
	<!-- Março -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(3,$ano);
			$calendario->getItensMes(3);
		 ?>
	</div>
	<!-- Abril -->
	<div class="item_calendario" style="margin-right:0px;">
		<?php 	
			$calendario->mostrarCalendario(4,$ano);
			$calendario->getItensMes(4);
		 ?>
	</div>
	<div style="clear:both; height:20px;"> </div>
	<!-- Maio -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(5,$ano);
			$calendario->getItensMes(5);
		 ?>
	</div>
	<!-- Junho -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(6,$ano);
			$calendario->getItensMes(6);
		 ?>
	</div>
	<!-- Julho -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(7,$ano);
			$calendario->getItensMes(7);
		 ?>
	</div>
	<!-- Agosto -->
	<div class="item_calendario" style="margin-right:0px;">
		<?php 	
			$calendario->mostrarCalendario(8,$ano);
			$calendario->getItensMes(8);
		 ?>
	</div>
	<div style="clear:both; height:20px;"> </div>
	<!-- Setembro -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(9,$ano);
			$calendario->getItensMes(9);
		 ?>
	</div>
	<!-- Outubro -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(10,$ano);
			$calendario->getItensMes(10);
		 ?>
	</div>
	<!-- Novembro -->
	<div class="item_calendario">
		<?php 	
			$calendario->mostrarCalendario(11,$ano);
			$calendario->getItensMes(11);
		 ?>
	</div>
	<!-- Dezembro -->
	<div class="item_calendario" style="margin-right:0px;">
		<?php 	
			$calendario->mostrarCalendario(12,$ano);
			$calendario->getItensMes(12);
		 ?>
	</div>
	<div style="clear:both; height:20px;"> </div>

</div>
<style type="text/css" media="screen">
	.item_calendario{
		width:219px; margin-right:30px; float:left;
	}
	#dia_branco{
		background-color: #e5e5e5;
	}
</style>

<?php include 'rodape.php'; ?>