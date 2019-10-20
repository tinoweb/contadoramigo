<?php include 'header.php'; ?>
<?php include 'agenda.class.php'; ?>
<?php include '../show.class.php'; ?>
<?php include '../des.class.php'; ?>
<?php 
	
	$agenda = new Agenda();
	$des = new DES();

?>
<div class="principal">
	<div style="width:100%" class="minHeight">

		<div class="titulo">Agenda</div>
		<!-- <a href="manter-item.php">Cadastrar Novo Item</a> -->
		<br>

		<?php for ($i=1; $i <= 12; $i++) { ?>
		<?php $itens = $agenda->listarItens($i); ?>
		<div class="item_mes_agenda" style="width: auto;float: left;margin-right: 30px;margin-bottom: 30px;">
			<div class="tituloVermelho" style="margin-bottom: 0;margin-left: 2px;"><?php echo $agenda->getTextoMes($i); ?></div>
			<table border="0" cellpadding="4" cellspacing="2">
				<thead>
					<tr>
						<th width="30">Dia</th>
						<th width="200">Tipo</th>
						<th width="30"></th>
					</tr>
				</thead>
				<tbody class="mes<?php echo $i; ?>">
					<?php while( $item = mysql_fetch_array($itens) ){ ?>
					<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);">
						<td><input style="width:20px;text-align: center;" type="text" class="editar_item" id="<?php echo $item['id']; ?>" tabela="agenda_index" campo="dia" value="<?php echo $item['dia']; ?>"></td>
						<td>
							<select name="tipo" style="width:200px" class="editar_item" alt="Mês" id="<?php echo $item['id']; ?>" tabela="agenda_index" campo="tipo">
								<option value="" <?php if( $item['tipo'] == "" ) echo 'selected="selected"'; ?>>Selecione</option>
								<?php $tipos = $agenda->getTipos(); ?>
								<?php while ( $tipo=mysql_fetch_array($tipos) ){ ?>
								<option value="<?php echo $tipo['id'] ?>" <?php if( $tipo['id'] == $item['tipo'] ) echo 'selected="selected"'; ?>><?php echo $tipo['frase']; ?></option>
								<?php } ?>
							</select>
						</td>
						<td align="center">
							<a href="#" onclick="if(confirm('Você tem certeza que deseja excluir este Item?')){location.href='manter-item.php?excluir&id=<?php echo $item['id']; ?>'};">
								<i class="fa fa-trash-o" style="font-size: 16px;color: #024a68;"></i>
							</a>
						</td>
					</tr>
					<?php } ?>
					<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);">
						<?php 
							$consulta = mysql_query("SELECT * FROM agenda_index_feriados WHERE mes = '".$i."' ");
							$objeto_consulta = mysql_fetch_array($consulta);
						?>
						<td colspan="2">
							<input type="text" class="atualizarFeriados" mes="<?php echo $i; ?>" value="<?php echo $objeto_consulta['string']; ?>" size="20" style="margin-left:5px;margin-top:5px"> Feriados
						</td>
						<td colspan="1" align="center">
							<span style="cursor:pointer;" class="adicionar_outro" mes="<?php echo $i; ?>"><i class="fa fa-plus" aria-hidden="true"></i></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php if( $i % 3 == 0 ) echo '<div style="clear:both"> </div>'; ?>
	   <?php } ?>
	   <input name="Alterar" type="button" class="publicar" value="Publicar" style="margin-top:10px;margin-right:10px">
	</div>
</div>
<script>

	
	$( document ).ready(function() {
	    $(".atualizarFeriados").change(function() {
	    	var mes = $(this).attr("mes");
	    	var valor = $(this).val();
			$.ajax({
				url:'ajax.php'
				, data: 'atualizarFeriados=atualizarFeriados&mes='+mes+'&valor='+valor
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
				}
			});
	    });
		$(".publicar").click(function() {
			var mes = $(this).attr("mes");
			$.ajax({
				url:'ajax.php'
				, data: 'publicarAgenda=publicarAgenda'
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
				}
			});
		});
	    
	});

</script>

<div class="principal" style="display:none">
	<div style="width:100%" class="minHeight">
		<br><br>
		<div class="titulo">Tipos</div>

		<table border="0" cellpadding="4" cellspacing="2">
			<tbody>
				<tr>
					<th width="50">Frase</th>
					<th width="200">Texto</th>
					<!-- <th width="30">Página</th> -->
				</tr>
				<?php $tipos = $agenda->getTipos(); ?>
				<?php while ( $tipo=mysql_fetch_array($tipos) ){ ?>
				<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);">
					<td>
						<input style="width:200px;" type="text" class="editar_item" id="<?php echo $tipo['id']; ?>" tabela="itens_agenda" campo="frase" value="<?php echo $tipo['frase'] ?>">
					</td>
					<td>
						<textarea style="width:690px;" type="text" class="editar_item" id="<?php echo $tipo['id']; ?>" tabela="itens_agenda" campo="texto"><?php echo $tipo['texto'] ?></textarea>
					</td>
					<!-- <td><input style="width:185px;" type="text" class="editar_item" id="<?php echo $tipo['id']; ?>" tabela="itens_agenda" campo="pagina" value="<?php echo $tipo['pagina'] ?>"></td> -->
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
	</div>
</div>

<script>
	
	$(".adicionar_outro").click(function() {
		// console.log("aki");
		var mes = $(this).attr("mes");
		var id = 0;
		$.ajax({
		  url:'ajax.php'
		  , data: 'inserirNovaLinhaMes=true&mes='+mes
		  , type: 'post'
		  , async: true
		  , cache:false
		  , success: function(retorno){
		  	id = retorno;
		  	$(".mes"+mes).prepend('<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);"><td><input style="width:20px;text-align: center;" type="text" class="editar_item'+id+'" id="'+id+'" tabela="agenda_index" campo="dia" value=""></td><td><select name="tipo" style="width:200px" class="editar_item'+id+'" alt="Mês" id="'+id+'" tabela="agenda_index" campo="tipo">			<option value="" <?php if( $item['tipo'] == "" ) echo 'selected="selected"'; ?>>Selecione</option><?php $tipos = $agenda->getTipos(); ?><?php while ( $tipo=mysql_fetch_array($tipos) ){ ?><option value="<?php echo $tipo['id'] ?>" <?php if( $tipo['id'] == $item['tipo'] ) echo 'selected="selected"'; ?>><?php echo $tipo['frase']; ?></option><?php } ?></select></td><td align="center"><a href="#" onclick="if(confirm(\'Você tem certeza que deseja excluir este Item?\')){location.href=\'manter-item.php?excluir&amp;id='+id+'\'};"><i class="fa fa-trash-o" style="font-size: 16px;"></i></a></td></tr>');	
		  	
		  	$(".editar_item"+id).change(function() {
			
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

		  }
		});

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

	$(".novo_item_des").click(function() {
		location.href="agenda.php?novo-des";
	});

</script>

<?php include 'rodape.php' ?>




