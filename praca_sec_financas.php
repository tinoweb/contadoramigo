<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
session_start(); 
if(isset($_SESSION["mensERRO"]))
{
	$menssagemErro = "<div style='margin-bottom:5px; margin-top:-10px'>" . $_SESSION["mensERRO"] . "</div>";
	unset($_SESSION["mensERRO"]);
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contador Amigo - Faça você mesmo sua própria contabilidade</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/meusScripts.js"></script>
</head>
<body style="margin:10px">
<div style="width:600px; text-align:left">
<strong>Praça de Atendimento da Secretaria de Finanças</strong><br />
Vale do Anhangabaú, 206, ao lado da Galeria Prestes Maia, <br />
de segunda à sexta-feira, das 8h às 18h.<br />
<br />
Confira  no mapa: <br />
<br />
<div style="border:1px; border-style:solid; border-color:#999; width:600px">
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d457.19642364643755!2d-46.63739698561357!3d-23.54791347861014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xcfd10cc32b5d7300!2sSecretaria+Municipal+de+Finan%C3%A7as-Sf!5e0!3m2!1spt-BR!2sbr!4v1413894135403" width="600" height="450" frameborder="0" style="border:0"></iframe>
</div>
<small><a href="https://goo.gl/maps/CzKDD" style="color:#0000FF;text-align:left">Exibir mapa ampliado</a></small>
</div>
</body>
</html>