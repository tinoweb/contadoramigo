<?
session_start();

include ('../checa.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Agenda 1.0 - <?=$_SESSION["NOME_USER_VAD"]?></title>
</head>
<frameset rows="50,*" frameborder="NO" border="0" framespacing="0" cols="*" > 
  <frame name="topFrame" scrolling="NO" src="visualizar_busca.php" >
  <frame name="mainFrame" src="visualizar_resultado.php">
</frameset>
<noframes> 
<body bgcolor="#FFFFFF" text="#000000"></body>
</noframes> 
</html>