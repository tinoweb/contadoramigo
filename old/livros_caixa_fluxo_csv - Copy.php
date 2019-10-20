<?php 
session_start();
include 'conect.php';
include 'livros_caixa_fluxo_class.php';

if (($_GET['ano'] != '') & (is_numeric($_GET['ano'])))
{
	$ano = $_GET['ano'];
}
else
{
	$ano = date("Y");
}

$fileName = "livroCaixa_Fluxo_" . $ano;

header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

echo 'Empresa: '. encode($_SESSION["nome_userSecao"]) . "\n";

echo encode("Ano") . ':' . $ano . "\n". "\n";

echo encode("Entradas"). "\n;";
exibirFluxo("entrada", $ano, "Empréstimos");

echo "\n\n" . encode("Saídas"). "\n;";	
exibirFluxo("saida", $ano, "Empréstimo (amortização)");

function encode($text)
{	
	$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
	return iconv($enc, "UTF-8", $text);	
}

function exibirFluxo($tipo, $ano, $categoriaEmprestimo)
{
	$fluxoCaixa = new fluxoCaixa($_SESSION['id_userSecao']);

	for ($i = 1; $i <= 12 ; $i++)
	{
		echo encode($fluxoCaixa->exibirMes($i)) . ';';		
	}
	echo "Total;\n";

	$categoria = $fluxoCaixa->listarCategorias($tipo, $ano, $categoriaEmprestimo);
	while($categoria_rows = mysql_fetch_array($categoria))
	{
		echo encode($categoria_rows['categoria']) . ";";
		$valores = $fluxoCaixa->listarValores($tipo, $ano, $categoria_rows['categoria'], $categoriaEmprestimo);
		while($valores_row = mysql_fetch_array($valores))
		{
			echo $fluxoCaixa->exibirValor($valores_row['total']) . ';';
		}

		echo "\n";
	}
	
	$total = $fluxoCaixa->totalCategoria($tipo, $ano, $categoriaEmprestimo, "");
	if ($total > 0)
	{		
		echo "Subtotal;";
		$valores = $fluxoCaixa->listarValores($tipo, $ano, "", $categoriaEmprestimo);
		while($valores_row = mysql_fetch_array($valores))
		{
			echo $fluxoCaixa->exibirValor($valores_row['total']) . ';';
		}
		echo "\n";

		echo encode($categoriaEmprestimo) . ";";
		$valores = $fluxoCaixa->listarValores($tipo, $ano, $categoriaEmprestimo, "");
		while($valores_row = mysql_fetch_array($valores))
		{
			echo $fluxoCaixa->exibirValor($valores_row['total']) . ';';
		}
		echo "\n";	
	}

	echo encode("Total") . ";";
	$valores = $fluxoCaixa->listarValores($tipo, $ano, "", "");
	while($valores_row = mysql_fetch_array($valores))
	{
		echo $fluxoCaixa->exibirValor($valores_row['total']) . ';';
	}
}