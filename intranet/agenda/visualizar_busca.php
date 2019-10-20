<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Agenda</title>
<style>
.borda
{
    BACKGROUND-COLOR: #ffffff;
    BORDER-BOTTOM: #000000 1px solid;
    BORDER-LEFT: #000000 1px solid;
    BORDER-RIGHT: #000000 1px solid;
    BORDER-TOP: #000000 1px solid;
    COLOR: #000000;
    FONT-FAMILY: verdana;
    FONT-SIZE: 10 px
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body style="background: url(../images/topo_novo.png) no-repeat #cccccc;    background-size: 733px 110px;" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr> 
			<td width="130" align="left">&nbsp;</td>
		<td>
		<div align="right"> 
			<form name="form1" method="post" action="visualizar_resultado.php" Target="mainFrame">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr align="right"> 
				<td> <font size="-1" face="verdana">Nome</font> <font face="verdana">
				<input type="text" name="txt_nome" size="10" class=borda style="WIDTH: 140px"><font size="-2" face="verdana"><img src="../images/trasnp.gif" width="5" height="15"><input type="submit" name="Button" value="Buscar" class=borda></font></font></td><td width="80"> <font face="verdana" size="-1"><a href="adicionar.php" target="mainFrame">Adicionar</a></font></td>
				</tr>
				</table>
			</form>
			<form name="form1" method="post" action="index.php" Target="_parent">
				<input type="hidden" name="logoff" />
				<button type="submit" style="cursor: pointer; background: none;border: 1px solid #AAA;margin-right:  5px;color: #FFF;margin-top: 1px; padding: 1px 10px;">Sair</button>
			</form>
		</div>
		</td>
		</tr>
	</table>
</body>
</html>

