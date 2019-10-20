<?
session_start();

include ('../conect.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Untitled Document</title>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#336699" vlink="#336699" alink="#336699" style="margin-top:20px;">

<?

$sql = "SELECT T_CONTATOS.CONTATO_ID, T_CONTATOS.NOME FROM T_CONTATOS";
if($_REQUEST["txt_nome"] != ""){
	$sql .= " WHERE (((T_CONTATOS.NOME) Like '%" . $_REQUEST["txt_nome"] . "%'))";
}
$sql .= " ORDER BY T_CONTATOS.NOME";
$resultado = mysql_query($sql);

$total = mysql_num_rows($resultado);

if($total > 0){
	// echo '<div style="float:right"><font face="Arial, Helvetica, sans-serif"><a href="../sair.php" title="">Sair</a><b><font color="#990000"></font></b><br></div>';
	echo "<font face='Arial, Helvetica, sans-serif' size='-1'>Total:<b>" . $total . "<br><font color='#990000'></font></b><br>";

	while($dados = mysql_fetch_array($resultado, MYSQL_ASSOC)){
		echo "<A HREF='alterar.php?id=" . $dados["CONTATO_ID"] . "' targer='principal'>" . $dados["NOME"] . "</A><BR>";			
	}
}else{
	echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='2'>N&atilde;o foi encontrado ocorr&ecirc;ncias para '<b> " . $_REQUEST["txt_nome"] . " </b>'</font>";
}

mysql_free_result($resultado);
?>

</body>
</html>
