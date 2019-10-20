<?php include 'header.php' ?>
<?php include 'agenda.class.php' ?>
<?php include '../show.class.php'; ?>
<?php include '../des.class.php'; ?>
<?php 
	
	$agenda = new Agenda();
	$des = new DES();

?>

<div class="principal">
	<div style="width:100%" class="minHeight">
		<br><br>
		<div class="titulo" id="cadastroNovoDes">DES</div>
		<a href="manter-des.php?novo-des">Cadastrar Novo Item</a>
		<br><br>
		<table border="0" cellpadding="4" cellspacing="2">
			<tbody>
				<tr>
					<!-- <th width="50">uf</th> -->
					<th >Estado</th>
					<th >Município</th>
					<th >Vencimento</th>
					<th width="400">Link</th>
					<th >Ação</th>
					<!-- <th width="30">Página</th> -->
				</tr>
				<?php $itens = $des->listarDados(); ?>
				<?php while ( $item=mysql_fetch_array($itens) ){ ?>
				<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);" id="item<?php echo $item['id']; ?>">
					<td>
						<?php echo $item['uf']; ?>
					</td>
					<td>
						<?php echo $item['municipio']; ?>
					</td>
					<td>
						<?php echo $item['vencimento']; ?>
					</td>
					<td>
						<a href="<?php echo $item['link']; ?>" target="_blank"><?php echo $item['link']; ?></a>
					</td>
					<td>
					<a href="manter-des.php?editar-des&id=<?php echo $item['id']; ?>"><i class="fa fa-pencil-square-o" style="font-size: 16px;"></i></a> | 
					<a href="#item<?php echo $item['id']; ?>" onclick="if(confirm('Você tem certeza que deseja excluir este Item?')){location.href='agenda.php?delete-des=<?php echo $item['id']; ?>&url=cadastroNovoDes'};">
						<i class="fa fa-trash-o" style="font-size: 16px;"></i>
					</a>	
					</td>
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
		  	$(".mes"+mes).append('<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);"><td><input style="width:20px;text-align: center;" type="text" class="editar_item'+id+'" id="'+id+'" tabela="agenda_index" campo="dia" value=""></td><td><select name="tipo" style="width:200px" class="editar_item'+id+'" alt="Mês" id="'+id+'" tabela="agenda_index" campo="tipo">			<option value="" <?php if( $item['tipo'] == "" ) echo 'selected="selected"'; ?>>Selecione</option><?php $tipos = $agenda->getTipos(); ?><?php while ( $tipo=mysql_fetch_array($tipos) ){ ?><option value="<?php echo $tipo['id'] ?>" <?php if( $tipo['id'] == $item['tipo'] ) echo 'selected="selected"'; ?>><?php echo $tipo['frase']; ?></option><?php } ?></select></td><td align="center"><a href="#" onclick="if(confirm(\'Você tem certeza que deseja excluir este Item?\')){location.href=\'manter-item.php?excluir&amp;id='+id+'\'};"><i class="fa fa-trash-o" style="font-size: 16px;"></i></a></td></tr>');	
		  	
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
		location.href="des.php?novo-des";
	});

</script>

<?php include 'rodape.php' ?>




