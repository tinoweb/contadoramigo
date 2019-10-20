<?php include '../conect.php';
include '../session.php';
include 'check_login.php' ?>
<?php include 'header.php' ?>

<div class="principal">

  <div class="titulo" style="margin-bottom:10px;">Suporte</div>
 
 <?php include 'suporte_menu.php' ?>
 
<div style="float:right; width:820px; padding-left:20px; border-left:#113b63 solid 1px"><span class="tituloVermelho">Chamados Encerrados</span><br />
  <br />

<table border="0" cellpadding="4" cellspacing="2">
  <tr>
  	<th width="140">Início do chamado</th>
    <th width="439">Assunto</th>
    <th width="140">Última Resposta em</th>
    <th width="60">Ação</th>
  </tr>
<?php 
$sql = "SELECT * FROM suporte WHERE tipoMensagem='pergunta' AND status='Encerrado' ORDER BY ultimaResposta DESC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) { 
?>  
<tr class="guiaTabela">
	<td><?=date('d/m/Y', strtotime($linha["data"]))?>, às <?=date('H:i', strtotime($linha["data"]))?></td>
    <td><?=$linha["titulo"]?></td>
    <td>
  <?php
if ($linha["ultimaResposta"] == $linha["data"]) {
	echo 'Sem Resposta';
}
else {
	echo date('d/m/Y', strtotime($linha["ultimaResposta"])) . ", às " . date('H:i', strtotime($linha["ultimaResposta"]));
}
?></td>
    <td><a href="suporte_visualizar.php?codigo=<?=$linha["idPostagem"]?>">Visualizar</a></td>
  </tr>
<?php } ?>
</table>
</div>
<div style="clear:both"> </div>
</div>


<?php include '../rodape.php' ?>
