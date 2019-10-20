<?php include 'header_restrita.php' ?>
<?php include 'livros_caixa_fluxo_class.php' ?>

<?php

if (($_GET['ano'] != '') & (is_numeric($_GET['ano'])))
{
	$ano = $_GET['ano'];
}
else
{
	$ano = date("Y");
}

function exibirFluxo($tipo, $ano) {
	
	// Define a categorias se for entrada.
	if($tipo == "entrada") {
		$categoriaEmprestimo = "Valor a Repassar','Empréstimos','Empréstimo sócio x empresa";	
		
		// Monta um arra quebrando por (',').
		$categoriaArray = explode("','",$categoriaEmprestimo);
	}
	
	// Define a categorias se for saida.
	if($tipo == "saida"){
		$categoriaEmprestimo = "Repasse a terceiros','Empréstimo (amortização)','Empréstimo empresa x sócio";
		
		// Monta um arra quebrando por (',').
		$categoriaArray = explode("','",$categoriaEmprestimo);
	}
		
	$fluxoCaixa = new fluxoCaixa($_SESSION['id_empresaSecao']);

	echo '<table width="100%" border="0" cellpadding="4" cellspacing="2" style="font-size: 11px; margin-bottom: 20px;">';
	echo '<thead>';
	echo '<tr>';
	echo '<th></th>';
	for ($i = 1; $i <= 12 ; $i++)
	{
		echo '<th width="52">' . $fluxoCaixa->exibirMes($i) . '</th>';		
	}
	echo '<th width="65" style="background-color:#ccc; color:#000;font-weight:bold;">Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';	
	
	$categoria = $fluxoCaixa->listarCategorias($tipo, $ano, $categoriaEmprestimo);
	while($categoria_rows = mysql_fetch_array($categoria))
	{
		echo '<tr>';
		echo '<td class="td_calendario">' . $categoria_rows['categoria'] . '</td>';		
		$valores = $fluxoCaixa->listarValores($tipo, $ano, $categoria_rows['categoria'], $categoriaEmprestimo);		
		$total_rows = mysql_num_rows($valores);
		$i = 1;

		$valores_array = array();
		for ($i=0; $i < 13; $i++) { 
			$valores_array[$i] = 0;
		}
		while($valores_row = mysql_fetch_array($valores)){
			$valores_array[$valores_row['mes']] = $valores_row['total'];
		}
		
		// if( $_SESSION['id_empresaSecao'] == 1581 ){
		// 	echo '<pre>';
		// 	var_dump($valores_array);
		// 	echo '</pre>';
		// }
		// while($valores_row = mysql_fetch_array($valores))
		for ($i=1; $i < 13; $i++) 
		{	
			if ($i < $total_rows)
			{				
				$valor_aux = number_format($valores_array[$i],2,',','.');
				if( $valores_array[$i] == 0)
					$valor_aux = '-';
				echo '<td teste="'.$valores_row['mes'].'" class="td_calendario" style="text-align:right">' . $valor_aux . '</td>';	
			}
			// $i = $i + 1;
		}
		echo '<td class="td_calendario" style="background-color:#ccc; color:#000;text-align:right;font-weight:bold;">' . number_format($valores_array[0],2,',','.') . '</td>';
		echo '</tr>';
	}

	$total = $fluxoCaixa->totalCategoria($tipo, $ano, $categoriaEmprestimo, "");
	
	if ($total > 0)
	{
		echo '<tr>';
		echo '<td class="td_calendario" style="background-color:#ccc; color:#000;">Subtotal</td>';
		$valores = $fluxoCaixa->listarValores($tipo, $ano, "", $categoriaEmprestimo);
		$total_rows = mysql_num_rows($valores);
		$i = 1;
		while($valores_row = mysql_fetch_array($valores))
		{
			if ($i < $total_rows)
			{				
				echo '<td class="td_calendario" style="text-align:right;background-color:#ccc;">' . $fluxoCaixa->exibirValor($valores_row['total']) . '</td>';	
			}
			else
			{
				echo '<td class="td_calendario" style="background-color:#ccc;color:#000;text-align:right;font-weight:bold;">' . $fluxoCaixa->exibirValor($valores_row['total']) . '</td>';
			}
			$i = $i + 1;
		}
		echo '</tr>';

		// Monta a linaha apos o subtotal. 
		foreach($categoriaArray as $categoria){
			
			if($fluxoCaixa->totalCategoria($tipo, $ano, $categoria, "") > 0){
				
				echo '<tr>';
				echo '<td class="td_calendario" style="background-color:#ffcccc;">' . $categoria . '</td>';		
				$i = 1;
				$valoresEmprestimos = $fluxoCaixa->listarValores($tipo, $ano, $categoria, "");
				while($valores_row = mysql_fetch_array($valoresEmprestimos))
				{	
					if ($i < $total_rows)
					{				
						echo '<td class="td_calendario" style="text-align:right;background-color:#ffcccc">' . $fluxoCaixa->exibirValor($valores_row['total']) . '</td>';	
					}
					else
					{
						echo '<td class="td_calendario" style="background-color:#ffcccc; color:#000;text-align:right;font-weight:bold;">' . $fluxoCaixa->exibirValor($valores_row['total']) . '</td>';
					}
					$i = $i + 1;
				}
				echo '</tr>';
			}
		}
	}
	
	echo '<tr>';
	echo '<td class="td_calendario" style="background-color:#ccc;font-weight:bold;color:#000;">Total</td>';
	$valores = $fluxoCaixa->listarValores($tipo, $ano, "", true);

	$valores_array = array();
	for ($i=0; $i < 13; $i++) { 
		$valores_array[$i] = 0;
	}
	while($valores_row = mysql_fetch_array($valores)){
		$valores_array[$valores_row['mes']] = $valores_row['total'];
	}

	// while($valores_row = mysql_fetch_array($valores))
	for ($i=1; $i < 13; $i++)
	{	
		$valor_aux = number_format($valores_array[$i],2,',','.');
		if( $valores_array[$i] == 0)
			$valor_aux = '-';
		echo '<td class="td_calendario" style="text-align:right;background-color:#ccc;color:#000;font-weight:bold;">' . $valor_aux . '</td>';
	}
	echo '<td class="td_calendario" style="background-color:#ccc;color:#000;text-align:right;font-weight:bold;">' . $fluxoCaixa->exibirValor($valores_array[0]) . '</td>';
	echo '</tr>';	

	echo '</tbody>';
	echo '</table>';
}

function exibirAno($selected)
{	
	$fluxoCaixa = new fluxoCaixa($_SESSION['id_empresaSecao']);
	$anos = $fluxoCaixa->listarAnos();

	if(mysql_num_rows($anos) > 0)
	{	
		while($anos_rows = mysql_fetch_array($anos))
		{
			if ($selected == $anos_rows['ano'])
			{
				echo "<option selected>" . $anos_rows['ano'] . "</option>";	
			}
			else
			{
				echo "<option>" . $anos_rows['ano'] . "</option>";
			}			
		}
	}
	else
	{
		echo "<option>" . date("Y") . "</option>";
	}
}
?>	

<script type="text/javascript">
	$(document).ready(function() 
	{
		$("#btnFiltar").click(function() 
		{	
			var ano = $("#cmbAno option:selected").text();
			window.location = '<?php echo basename($_SERVER['PHP_SELF']) ?>?ano=' + ano;
		})

		$("#btnCSV").click(function() 
		{	
			var ano = $("#cmbAno option:selected").text();
			window.location = 'livros_caixa_fluxo_csv.php?ano=' + ano;
		})
	});
</script>

<div class="principal">		
	<h1>Fluxo de Caixa</h1>
	<div  style="margin-bottom:25px;">
		Período de: <select id="cmbAno"><?php echo (exibirAno($ano))?></select>
		<button id="btnFiltar">Filtrar</button>
	</div>
  	<h2>Entradas <?php echo $ano?></h2>
	<?php exibirFluxo("entrada", $ano); ?>
	<h2>Saídas <?php echo $ano?></h2>
	<?php exibirFluxo("saida", $ano); ?>
	<center><button id="btnCSV">Exportar para Excel (CSV)</button></center>
</div>
<?php include 'rodape.php' ?>