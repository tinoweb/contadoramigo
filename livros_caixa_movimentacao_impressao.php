<?php 
include 'conect.php';
include 'session.php';
include 'check_login.php' ;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Livro Caixa - Movimentação</title>
<script type="text/javascript" src="./scripts/jquery.min.js"></script>
<style type="text/css">
body {
	margin:0px;
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	text-align:center;
}
th {
	text-align:center;
	font-weight:bold;
	border:#000 solid 1px
}
td {
	border:#000 solid 1px
}
table {
	font-size:12px;
	border:#000 solid 1px;
}
</style></head>

<body id="conteudo">

<?php include 'livro-caixa.php'; ?>

<div style="width:750px; text-align:left; margin:0 auto 10px">
<div style="float:left"><strong>Empresa:</strong> <?=$_SESSION["nome_userSecao"]?></div>
<div style="float:right"><strong>Período:</strong> <?=date('d/m/Y', strtotime($_GET["dataInicio"]))?> a <?=date('d/m/Y', strtotime($_GET["dataFim"]))?></div>
<div style="clear:both"> </div>
</div>
<table width="750" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <th width="70">Data</th>
    <th width="98">Documento nº</th>
    <th width="150">Categoria</th>
    <th width="150">Descrição</th>
    <th width="80">Entrada</th>
    <th width="80">Saída</th>
    <th width="80">Saldo</th>
  </tr>
<?php
$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data < '" . $_GET["dataInicio"] . "' ORDER BY data, id ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) {
	$entrada = $linha["entrada"];
	$saida = $linha["saida"];
	$saldo = $saldo + $entrada - $saida;
}

$sql = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE data BETWEEN '" . $_GET["dataInicio"] . "' AND '" . $_GET["dataFim"] . "' ORDER BY data, id ASC";
$resultado = mysql_query($sql)
or die (mysql_error());

while ($linha=mysql_fetch_array($resultado)) {
$saldo = $saldo + $linha["entrada"] - $linha["saida"];

$descricao = trataProlabore($linha["categoria"], $linha["descricao"]);

?>
  <tr>
    <td valign="top"><?=date('d/m/Y', strtotime($linha["data"]))?>&nbsp;</td>
    <td valign="top"><?=str_replace(",",", ",$linha["documento_numero"])?>&nbsp;</td>
    <td valign="top"><?=$linha["categoria"]?>&nbsp;</td>
    <td valign="top"><?=$descricao?>&nbsp;</td>
    <td align="right" valign="top"><?=number_format($linha["entrada"],2,",",".")?>&nbsp;</td>
    <td align="right" valign="top"><?=number_format($linha["saida"],2,",",".")?>&nbsp;</td>
    <td align="right" valign="top"><?=number_format($saldo,2,",",".")?>&nbsp;</td>
  </tr>
<?php } 


// Método criado para realizar o tratamento da categoria caso seja um Pró-labore, pois e necessario verificar o id do Sócio. 01-03-2017.
function trataProlabore($categoria, $descricao){
	
	if( $categoria == 'Pró-Labore' && is_numeric($descricao)){
														
		$qry = "SELECT * FROM dados_do_responsavel WHERE idSocio = '".$descricao."' AND id = '".$_SESSION['id_empresaSecao']."' ";
		
		$consulta = mysql_query($qry);
		
		$objeto_consulta = mysql_fetch_array($consulta);
		return $objeto_consulta['nome'];
	} else {
		return $descricao;
	}
}

?>
</table>
<blockquote>&nbsp;</blockquote>
<script type="text/javascript">

$(window).load(function() {
  window.print();
});


</script>
</body>
</html>