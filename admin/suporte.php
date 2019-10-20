<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php include 'header.php' ?>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Suporte</div>
 
 <?php include 'suporte_menu.php' ?>
 
<div style="float:right; width:820px; padding-left:20px; border-left:#113b63 solid 1px"><span class="tituloVermelho">Chamados Pendentes</span><br />
  <br />

<table border="0" cellpadding="4" cellspacing="2">
  <tr>
  	<th width="140">Última Interação</th>
    <th width="439">Assunto</th>
    <th width="140">Início do chamado</th>
    <th width="60">Ação</th>
  </tr>
<?php 
//Paginação
$paginaAtual = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
$quantidadeResultados = 100;
$camposExibidos = ($paginaAtual*$quantidadeResultados) - $quantidadeResultados; 


$sql = "SELECT * FROM suporte WHERE tipoMensagem='pergunta' AND status='Em análise' ORDER BY ultimaResposta DESC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) { 


	$consultar_rascunho = mysql_query("SELECT * FROM suporte_rascunho WHERE codigo = '".$linha["idPostagem"]."' ");
	$objeto_rascunho=mysql_fetch_array($consultar_rascunho);

?>  
<tr class="guiaTabela">
	<td>
  <?php
if ($linha["ultimaResposta"] == $linha["data"]) {
	echo date('d/m/Y', strtotime($linha["data"])).', às '.date('H:i', strtotime($linha["data"]));
}
else {
	echo date('d/m/Y', strtotime($linha["ultimaResposta"])) . ", às " . date('H:i', strtotime($linha["ultimaResposta"]));
}
?></td>
	
    <td style="<?php if( isset($objeto_rascunho['id']) && $objeto_rascunho['id'] != '' ) echo 'color:red' ?>"><?=$linha["titulo"]?></td>
    <td><?=date('d/m/Y', strtotime($linha["data"]))?>, às <?=date('H:i', strtotime($linha["data"]))?></td>
    <td><a href="suporte_visualizar.php?codigo=<?=$linha["idPostagem"]?>">Visualizar</a></td>
  </tr>
<?php } ?>
</table>
</div>
<div style="clear:both"> </div>
<div style="clear:both;text-align:right">
<?php
//Consulta sem o limite para produzir o número de páginas
$sql = "SELECT * FROM suporte WHERE  tipoMensagem='pergunta' AND status='Em análise'";	

$resultado = mysql_query($sql)
or die (mysql_error());

$totalPesquisado = mysql_num_rows($resultado);

if($totalPesquisado > $quantidadeResultados) {
	echo "<br>";
	
	if($paginaAtual == 1) {
		echo 'anterior | ';
	} else {
		echo '<a href="'.$_SERVER['PHP_SELF'].'?pagina=' . ($paginaAtual - 1) . '">anterior</a> |';
	}
		
	for($i = 1; $i <= ceil($totalPesquisado / $quantidadeResultados); $i++) { 
		if($i == $paginaAtual) {
			echo ' '.$i.' |';
		} else {
			echo ' <a href="'.$_SERVER['PHP_SELF'].'?pagina=' . $i . '">' . $i . '</a> |';
		} 
	}
	
	if($paginaAtual == ceil($totalPesquisado / $quantidadeResultados)) {
		echo ' próxima';
	} else {
		echo ' <a href="'.$_SERVER['PHP_SELF'].'?pagina=' . ($paginaAtual + 1) . '">próxima</a>';
	}
}
?>
</div>
</div>


<?php include '../rodape.php' ?>
