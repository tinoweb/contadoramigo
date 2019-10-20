<?php 
	include 'header.php';
	include 'demos.class.php'; 
	
	$demos = new Demos();

	#echo $demos->getPaginacao();

	$itens = $demos->getDemos();

?>
<div class="principal">
	<div style="width:960px" class="minHeight">

	<div class="titulo" style="float:left">Demos</div>

		<select class="filtro" style="float:right">
			<option value="demo"<?php if($_GET['filtro'] == 'demo') echo 'selected="selected"' ?>>Ativos</option>
			<option value="demoInativo" <?php if($_GET['filtro'] == 'demoInativo') echo 'selected="selected"' ?>>Inativos</option>
			<option value="todos" <?php if($_GET['filtro'] == 'todos') echo 'selected="selected"' ?>>Todos</option>
		</select>
		<br>
		<table border="0" cellpadding="4" cellspacing="2" style="width:100%">
			<tbody>
				<tr>
					<!-- <th width="50">uf</th> -->
					<th width="300" >Nome</th>
					<th width="40">Dias</th>
					<th width="150" align="center">Telefone</th>
					<th width="400">Observação</th>
					<th width="30"></th>
				</tr>
				<?php while( $item = mysql_fetch_array($itens) ){ ?>
				<tr class="guiaTabela" style="background-color: rgb(234, 239, 245);" id="item4">
					<td>
						<a href="cliente_administrar.php?id=<?php echo $item['id'] ?>" target="blank" title="">
							<?php echo $item['assinante'] ?>	
						</a>
					</td>
					<td>
						<?php echo $demos->diferencaData(date("Y-m-d"),$item['data_inclusao']); ?>			
					</td>
					<td width="150" align="center">
						<?php echo $demos->getTelefone($item['id']) ?>
					</td>
					<td>
						<textarea style="width:98%;height:35px;" name="" class="editar_item_demo" id="<?php echo $item['id'] ?>" tabela="obs_demos" campo="texto" ><?php echo $demos->getObs($item['id']) ?></textarea>
					</td>
					<td align="center">
						<?php echo $demos->temEmpresa($item['id']); ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<br>
		<?php 

			$totalPesquisado = $demos->getPaginacao();
			$quantidadeResultados = 100;
			$paginaAtual = $demos->getPagina();
			$qtdPaginasAntesEDepois = 5;

			$parametros = '';
			if( isset( $_GET['filtro'] ) ):
				
				$parametros = "&filtro=".$_GET['filtro'];
			
			endif;

			if($totalPesquisado > $quantidadeResultados) {
				echo "<div style=\"width: 49%; float: left; text-align: left;\">";
				if($paginaAtual == 1) {
					echo 'anterior | ';
				} else {
					echo '<a href="demos.php?pagina=' . ($paginaAtual - 1) . $parametros.'">anterior</a> |';
				}
				
				for($i = ($paginaAtual-$qtdPaginasAntesEDepois); $i <= $paginaAtual-1; $i++) { 
					// Se o número da página for menor ou igual a zero, não faz nada 
					// (afinal, não existe página 0, -1, -2..) 
					if($i > 0) { 
						echo '<a href="demos.php?pagina=' . $i .$parametros. '">' . $i . '</a> |';
					}
				}

				echo ' ' . $paginaAtual . ' |';

				for($i = $paginaAtual+1; $i <= $paginaAtual+$qtdPaginasAntesEDepois; $i++) { 
					// Verifica se a página atual é maior do que a última página. Se for, não faz nada. 
					if($i <= ceil($totalPesquisado / $quantidadeResultados)) { 
						echo '<a href="demos.php?pagina=' . $i .$parametros. '">' . $i . '</a> |';
					}
				}

			/*	for($i = 1; $i <= ceil($totalPesquisado / $quantidadeResultados); $i++) { 
					if($i == $paginaAtual) {
						echo ' '.$i.' |';
					} else {
						echo ' <a href="demos.php?pagina=' . $i . '">' . $i . '</a> |';
					} 
				}*/
				
				if($paginaAtual == ceil($totalPesquisado / $quantidadeResultados)) {
					echo ' próxima';
				} else {
					echo ' <a href="demos.php?pagina=' . ($paginaAtual + 1) .$parametros. '">próxima</a>';
				}
				echo "</div>";
			}


		?>



	</div>
</div>

<script>
	
	
	$(".editar_item_demo").focus(function() {
		$(this).css("height","260px");
		var tds = $(this).parent().parent().find('td');
		for (var i = 0; i < tds.length; i++) {
			td = tds[i];
			$(td).attr("valign","top");
		};
	});
	$(".editar_item_demo").focusout(function() {
		$(this).css("height","35px");
		var tds = $(this).parent().parent().find('td');
		for (var i = 0; i < tds.length; i++) {
			td = tds[i];
			$(td).removeAttr("valign");
		};
	});

	$(".filtro").change(function() {
		location.href="demos.php?pagina=1&filtro="+$(this).val();
	});

	$(".editar_item_demo").change(function() {

		var valor = $(this).val();
		var id = $(this).attr("id");
		var tabela = $(this).attr("tabela");
		var campo = $(this).attr("campo");

		$.ajax({
		  url:'../ajax.php'
		  , data: 'editar_item_demos=editar_item_demos&id='+id+'&valor='+valor+'&campo='+campo+'&tabela='+tabela
		  , type: 'post'
		  , async: true
		  , cache:false
		  , success: function(retorno){
		  	console.log(retorno);
		  }
		}); 

	});

</script>
<br><br><br>
<?php include '../rodape.php' ?>
