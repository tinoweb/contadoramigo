<?
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

session_start();

//if($_SERVER['HTTPS'] != on){
//if($_SERVER['SERVER_NAME'] != 'contadoramigo.websiteseguro.com'){
//	header ('location: https://www.contadoramigo.com.br/admin/index.php');
//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
//include '../conect.php';

$ContAmi = explode(" ", $_COOKIE["ContAmi"]);
$contAdmMail = $ContAmi[0];
$contAdmPass = $ContAmi[1];
$manter = $ContAmi[2];

if(isset($_COOKIE["ContAmi"]) && $contAdmMail != '' && !isset($_SESSION['mensERRO']) && isset($_SESSION["IsAdmAuthorized"]) || $manter == 1){
	header("Location: /admin/login.php");
}
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contador Amigo - Sistema Administrativo</title>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<LINK REL="SHORTCUT ICON" HREF="../icon.ico" type="image/x-icon"/>
<script type="text/javascript" src="../scripts/meusScripts.js"></script>
<script language="JavaScript">
function validaForm() {	
	if (document.getElementById('txtEmail').value==''){
		alert('Digite o Email.');
		document.getElementById('txtEmail').focus();
		return false;
	}
	if (document.getElementById('txtSenha').value==''){
		alert('Digite a Senha.');
		document.getElementById('txtSenha').focus();
		return false;
	}

	document.body.style.cursor = 'wait';
	document.getElementById('btnLogin').value = "Carregando... ";
	document.getElementById('btnLogin').disabled = true;
	//document.cookie = 'ContAmi=' + document.getElementById('txtEmail').value + ' ' + document.getElementById('txtSenha').value + ' ' + 0 '; expires=Wed, 1 Jan 2025 20:47:11 UTC; path=/';
	//document.getElementById('login').submit();

}
</script>
<?
if(($_SESSION["mensERRO"]) != '')
{
//	$menssagemErro = "<div style='margin-bottom:5px; margin-top:-10px'>" . $_SESSION["mensERRO"] . "</div>";
	echo("<script>alert('".$_SESSION["mensERRO"]."')</script>");
	unset($_SESSION["mensERRO"]);
}
?>
<style>
	.campoLogin {
		background: #FFFF99 none repeat scroll 0 0;
		border-radius: 3px;
		display: inline-block;
		margin-top: 100px;
		padding: 10px;
		width: 300px;
		border: 1px solid #CCC;
	}
</style>	
</head>
<body>

<div style="width:966px; margin:0 auto; margin-bottom:20px">
<div style="float:left"><img src="../images/logo.png" alt="Contador Amigo" width="400" height="68" border="0" style="margin-bottom:5px; float:left" /></div>
<div style="float:right; margin-top:46px; font-size:14px; color: #cc0000;">Sistema Administrativo</div>
<div style="clear:both"></div>
<div class="menu"></div>
<div style="text-align:center" class="minHeight"> 
<br />
<br />
<br />
<div class="campoLogin">
    <form name="login" id="login" action="login.php" method="post" onsubmit="return validaForm()">
    <!-- onkeypress="if (event.keyCode == 13) validaForm()"-->
    <table border="0" cellpadding="2" cellspacing="0" align="center">
    <tr><td colspan="2" align="left"><div class="tituloPeq" style="font-size:16px; margin-bottom:10px">Login:</div></td></tr>
    <tr><td align="left">Email: </td><td><input type="text" name="txtEmail" id="txtEmail" value="<?=$contAdmMail?>" maxlength="60"  style="width:130px" /></td></tr> 
    <tr><td align="left">Senha: </td><td> <input type="password" name="txtSenha" id="txtSenha" value="<?=$contAdmPass?>" maxlength="60" style="width:130px" /></td></tr>
    <tr><td colspan="2"><input name="manter" id="manter" value="1" type="checkbox"> Manter-me conectado</td></tr>
    <tr><td colspan="2" valign="bottom"><input type="submit" value="Entrar" name="button" id="btnLogin"></td></tr>
    </table>
    </form>
</div>

<br />
<?=$menssagemErro;?>
</div>
</div>


<?php include '../rodape.php' ?>
