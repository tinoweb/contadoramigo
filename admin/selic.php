<?php include 'header.php' ?>
<?php include 'agenda.class.php' ?>
<?php include '../show.class.php'; ?>
<?php include '../des.class.php'; ?>
<?php 
	
	

?>
<script src="../jquery.maskMoney.js" type="text/javascript"></script>
<div class="principal">

	<?php 
		
		include 'selic.class.php';

		$selic = new Selic();
		$anos = $selic->getAnos();
		$dados = $selic->getDadosAno();

	?>

	<div class="titulo">Tabela SELIC</div>
		
	<div style="width:100%" class="minHeight">
		
		Selecione o ano 
		<select name="filtro" class="filtro">
			<?php $first = true; ?>			
			<?php while( $ano = mysql_fetch_array($anos) ) { ?>
				<?php if( $first == true ) {?>
				<option value="<?php echo $ano['ano'] ?>" <?php if( (isset($_GET['filtro']) && $_GET['filtro'] == $ano['ano'] ) || !isset($_GET['filtro']) ) echo 'selected'; ?>><?php echo $ano['ano'] ?></option>
				<?php $first = false; ?>
				<?php }else{ ?>
				<option value="<?php echo $ano['ano'] ?>"  <?php if( (isset($_GET['filtro']) && $_GET['filtro'] == $ano['ano'])) echo 'selected'; ?>><?php echo $ano['ano']; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
		<br>
		<br>
		<table border="0" cellpadding="4" cellspacing="2" class="mes1">
			<tbody>
				<tr>
					<th width="100">Mês</th>
					<th width="70">Taxa</th>
				</tr>
				<?php while( $dado = mysql_fetch_array($dados) ) { ?>
					<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);">
						<td><?php echo $selic->getMes($dado['mes']) ?></td>
						<td>
							<input type="text" class="editar_item currency" id="<?php echo $dado['id'] ?>" tabela="selic" campo="valor" value="<?php echo $dado['valor'] ?>" placeholder="" size="5"> %
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>			
	</div>
</div>
<script>
		
	$( document ).ready(function() {
		
		$(function() {
		    $('.currency').maskMoney();
		})
		
		$(".filtro").change(function() {
			location.href="selic.php?filtro="+$(this).val();
		});	

		$(".editar_item").change(function() {
				
			var valor = $(this).val();
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");

			$.ajax({
			  url:'ajax.php'
			  , data: 'editar_item=true&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  	console.log(retorno);
			  }
			}); 

		});	
	    
	});

</script>
<?php include '../rodape.php' ?>




