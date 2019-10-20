<?php include 'header.php' ?>
<div class="principal">
	<div style="width:100%" class="minHeight">

		<div class="titulo">Cnaes - Livro diário e Livro Razão</div>
		<a href="#" class="adicionarCnae">Adicionar Novo</a>
		<br><br>
		<table border="0" cellpadding="4" cellspacing="2" class="mes1">
			<thead>
				<tr>
					<th width="50">Cnae</th>
					<th width="70">Categoria</th>
					<th width="70">Tipo</th>
					<th width="0">Ação</th>
				</tr>
			</thead>
			<tbody id="tabela">
				<?php 

					if( isset($_GET['delete-item']) )
						mysql_query("DELETE FROM livro_diario_custo_despesa WHERE id = '".$_GET['delete-item']."' ");

					function selected($string1,$string2){
						if( $string1 == $string2 ){
							return 'selected="selected"';
						}
					}

					$itens = mysql_query("SELECT * FROM livro_diario_custo_despesa ORDER BY categoria ASC ");
					while( $objeto_itens = mysql_fetch_array($itens) ){

				?>
				<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);">
					<td>
						<input size="10" type="text" class="campoCNAE editar_item" name="" tabela="livro_diario_custo_despesa" campo="cnae" id="<?php echo $objeto_itens['id']; ?>" value="<?php echo $objeto_itens['cnae']; ?>">						
					</td>
					<td>
						<select class="editar_item" name="" tabela="livro_diario_custo_despesa" campo="categoria" id="<?php echo $objeto_itens['id']; ?>">
							<option value="" selected="selected">Selecione</option>
							<?php 
								$categorias = mysql_query("SELECT * FROM livro_diario_custo_despesa GROUP BY categoria ORDER BY categoria ASC ");
								$options = '';
								while( $objeto_categorias = mysql_fetch_array($categorias) )
									echo '<option value="'.$objeto_categorias['categoria'].'" '.selected($objeto_categorias['categoria'],$objeto_itens['categoria']).'>'.$objeto_categorias['categoria'].'</option>';
							?>
						</select>
					</td>
					<td>
						Custo
					</td>
					<td align="center">
						<a href="#item48" onclick="if(confirm('Você tem certeza que deseja excluir este Item?')){location.href='cnaes-livro-diario-razao.php?delete-item=<?php echo $objeto_itens['id'] ?>'};">
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

	
	$( document ).ready(function() {
	    
	    var options = "<?php 
								$categorias = mysql_query('SELECT * FROM livro_diario_custo_despesa GROUP BY categoria ORDER BY categoria ASC ');
								$options = '';
								while( $objeto_categorias = mysql_fetch_array($categorias) )
									echo '<option value=\"'.$objeto_categorias['categoria'].'\" >'.$objeto_categorias['categoria'].'</option>';
							?>";


		$(".editar_item").change(function() {
			var valor = $(this).val();
			var id = $(this).attr("id");
			var tabela = $(this).attr("tabela");
			var campo = $(this).attr("campo");
			$.ajax({
			  url:'../ajax.php'
			  , data: 'editar_item=editar_item&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
			  , type: 'post'
			  , async: true
			  , cache:false
			  , success: function(retorno){
			  		console.log(retorno);
			  }
			}); 
		});


		$(".adicionarCnae").click(function() {
			$.ajax({
				url:'ajax.php'
				, data: 'inserirCnaeLivroDiarioRazao=inserirCnaeLivroDiarioRazao'
				, type: 'post'
				, async: true
				, cache:false
				, success: function(retorno){
					location = 'cnaes-livro-diario-razao.php';
				}
			});
			// $("#tabela").prepend('<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);"><td><input size="10" type="text" name="" value="" tabela="livro_diario_custo_despesa" campo="cnae" id=""></td><td><select name="">'+options+'</select></td><td>Custo</td></tr>');
		});
	    
	});

</script>