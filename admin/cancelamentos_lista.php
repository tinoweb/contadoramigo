<?php 
	include 'header.php';
	
	include '../conect.php';
	include '../session.php';
	include 'check_login.php';
	
	function selected( $value, $prev ){
	   return $value==$prev ? ' selected="selected"' : ''; 
	};
?>


<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Cancelamentos dos Clientes</div>
  <div style="clear:both"> </div>
  <table border="0" cellspacing="2" cellpadding="4" style="width:100%;font-size:12px">
    <tr>
    <th align="center" width="25%">Razão Social</th>
    <th align="center" width="60%">Motivo</th>
    <th align="center" width="15%">Satisfação</th>
    </tr>
<?php

//Paginação
$paginaAtual = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
$quantidadeResultados = 100;
$camposExibidos = ($paginaAtual*$quantidadeResultados) - $quantidadeResultados; 

/*$sql = "SELECT 
e.id
, e.razao_social
, c.motivo
, case 
		when c.satisfacao = '1' then 'Muito Satisfeito'
		when c.satisfacao = '2' then 'Satisfeito'
		when c.satisfacao = '3' then 'Insatisfeito'
		when c.satisfacao = '4' then 'Muito Insatisfeito'
	end satisfacao
FROM dados_cancelamentos c
INNER JOIN dados_da_empresa e ON c.id_empresa = e.id 
ORDER BY c.id DESC , c.data DESC
LIMIT " . $camposExibidos . ", " . $quantidadeResultados;	
*/
$sql = "SELECT 
l.id
, l.assinante
, c.motivo
, case 
		when c.satisfacao = '1' then 'Muito Satisfeito'
		when c.satisfacao = '2' then 'Satisfeito'
		when c.satisfacao = '3' then 'Insatisfeito'
		when c.satisfacao = '4' then 'Muito Insatisfeito'
	end satisfacao
FROM dados_cancelamentos c
INNER JOIN login l ON c.id_empresa = l.id 
ORDER BY c.data DESC
LIMIT " . $camposExibidos . ", " . $quantidadeResultados;	

$resultado = mysql_query($sql)
or die (mysql_error());

$corLinha = "#FFF";

while ($linha=mysql_fetch_array($resultado)) {
?>
<tr class="guiaTabela" style="background-color:<?=$corLinha?>">
    <td><a href="cliente_administrar.php?id=<?=$linha["id"]?>"><?=($linha['assinante'] != '' ? $linha['assinante'] : 'Não localizado')?></a></td>
		<td><?=$linha["motivo"]?></td>
    <td><?=$linha["satisfacao"]?></td>
</tr>
<?php
	if ($corLinha == "#FFF") {
		$corLinha = "#E5E5E5";
	} else {
		$corLinha = "#FFF";
	} 
}?>
</table>
<?php
//Consulta sem o limite para produzir o número de páginas
/*$sql = "SELECT 
e.id
, e.razao_social
, c.motivo
, case 
		when c.satisfacao = '1' then 'Muito Satisfeito'
		when c.satisfacao = '2' then 'Satisfeito'
		when c.satisfacao = '3' then 'Insatisfeito'
		when c.satisfacao = '4' then 'Muito Insatisfeito'
	end satisfacao
FROM dados_cancelamentos c
INNER JOIN dados_da_empresa e ON c.id_empresa = e.id ";	*/

$sql = "SELECT 
l.id
, l.assinante
, c.motivo
, case 
		when c.satisfacao = '1' then 'Muito Satisfeito'
		when c.satisfacao = '2' then 'Satisfeito'
		when c.satisfacao = '3' then 'Insatisfeito'
		when c.satisfacao = '4' then 'Muito Insatisfeito'
	end satisfacao
FROM dados_cancelamentos c
INNER JOIN login l ON c.id_empresa = l.id ";

$resultado = mysql_query($sql)
or die (mysql_error());

$totalPesquisado = mysql_num_rows($resultado);

if($totalPesquisado > $quantidadeResultados) {
	echo "<br>";
	
	if($paginaAtual == 1) {
		echo 'anterior | ';
	} else {
		echo '<a href="cancelamentos_lista.php?pagina=' . ($paginaAtual - 1) . '">anterior</a> |';
	}
		
	for($i = 1; $i <= ceil($totalPesquisado / $quantidadeResultados); $i++) { 
		if($i == $paginaAtual) {
			echo ' '.$i.' |';
		} else {
			echo ' <a href="cancelamentos_lista.php?pagina=' . $i . '">' . $i . '</a> |';
		} 
	}
	
	if($paginaAtual == ceil($totalPesquisado / $quantidadeResultados)) {
		echo ' próxima';
	} else {
		echo ' <a href="cancelamentos_lista.php?pagina=' . ($paginaAtual + 1) . '">próxima</a>';
	}
}
?>
</div>

<?php include '../rodape.php' ?>
