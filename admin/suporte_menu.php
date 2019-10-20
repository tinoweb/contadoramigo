<div style="float:left; width:125px">
<?php
$sql = "SELECT * FROM suporte WHERE tipoMensagem='pergunta' AND status='Em análise' ORDER BY ultimaResposta DESC";
$resultado = mysql_query($sql)
or die (mysql_error());
$pendente = mysql_num_rows($resultado);

$sql = "SELECT * FROM suporte WHERE tipoMensagem='pergunta' AND status='Respondido' ORDER BY ultimaResposta DESC";
$resultado = mysql_query($sql)
or die (mysql_error());
$respondido = mysql_num_rows($resultado);
?>

<a href="suporte.php">Pendente (<?=$pendente?>)</a><br />
<a href="suporte_respondido.php">Respondido (<?=$respondido?>)</a><br />
<!--<a href="busca.php">Busca</a><br />-->
</div>