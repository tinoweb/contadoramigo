<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
</head>
<body>
<form method="post" target="">
<input type="text" name="cnae" id="cnae" />
<input type="submit" />
</form>
<?php
if($_POST) {
	include "../conect.php";
	$sql = "SELECT * FROM cnae WHERE cnae='" . $_POST['cnae'] . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	$total = mysql_num_rows($resultado);
	
	if ($total == "0") {
		echo "O CNAE " . $_POST['cnae'] . " não foi encontrado.";
	}
	
	/*else {
		$sql = "UPDATE cnae SET retencao='2' WHERE cnae='" . $_POST['cnae'] . "'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	
		echo "O CNAE " . $_POST['cnae'] . " foi atualizado.";
	}*/
	
}
?>
</body>
</html>