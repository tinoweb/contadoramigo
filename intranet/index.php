<?

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

session_start();

include ('conect.php');

// include ('checa.php');
if(isset($_COOKIE['contador_amigo_intranet']) && !isset($_SESSION['NOME_USER_VAD'])){
	
	$dadosCookie = json_decode($_COOKIE['contador_amigo_intranet']);
	
	$name_user = $dadosCookie->NOME_USER_VAD;
	$id_user = $dadosCookie->ID_USER_VAD;
	$mater = $dadosCookie->STATUS_USER_VAD;
	
	if($mater) {
		$_SESSION["NOME_USER_VAD"] = $name_user;
		$_SESSION["ID_USER_VAD"] = $id_user;
	}
}

if( isset($_SESSION['NOME_USER_VAD']) && $_SESSION['NOME_USER_VAD'] == "Vitor Maradei"){
	header("location: https://www.contadoramigo.com.br/intranet/agenda/index.php");
}

$sql = "SELECT * FROM T_CONTATOS LIMIT 0,10";
$resultado = mysql_query($sql);

if($_REQUEST["action"] == "enviar"){
	
	$SQL = "SELECT USER_ID, LOGIN, NOME, SENHA, TIPO FROM T_USUARIOS WHERE LOGIN='" . $_REQUEST["txt_login"] . "' AND SENHA='" . $_REQUEST["txt_senha"] . "'";

	if($dados = mysql_fetch_array(mysql_query($SQL))){

		$_SESSION["NOME_USER_VAD"] = $dados["NOME"];
		$_SESSION["ID_USER_VAD"] = $dados["USER_ID"];
		
		$mater = isset($_POST['manter']) && $_POST['manter'] == 1 ? 1 : 0;
		
		setcookie('contador_amigo_intranet','', time()-(120*120*24*30), '/', str_replace('www.', '', $_SERVER['SERVER_NAME']), 0);
		
		$valCookie = json_encode(array("NOME_USER_VAD"=>$dados["NOME"], "ID_USER_VAD"=>$dados["USER_ID"], "STATUS_USER_VAD"=>$mater));

		setcookie('contador_amigo_intranet', $valCookie, time()+(120*120*24*30), '/', str_replace('www.', '', $_SERVER['SERVER_NAME']), 0);	
				
		switch($_REQUEST['tipo']){
			case 'agenda':
				header('location: https://www.contadoramigo.com.br/intranet/agenda/index.php');
			break;	
		}
	}else{
		$erro_login = "erro";
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Intranet VAD - Agenda</title>
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
    FONT-SIZE: 10 px;
}
</style>
<SCRIPT LANGUAGE="JavaScript1.1">
function submeter() 
{
 if (document.forms[0].txt_login.value == ""){
  alert("Digite o Login");
  document.forms[0].txt_login.focus();
 }else{
  if (document.forms[0].txt_senha.value == ""){
   alert("Digite a Senha");
   document.forms[0].txt_senha.focus(); 
  }else{
   document.forms[0].submit()   
  }
 }
}
<?
if($erro_login <> ""){
?>
	alert("Login ou senha inválido.");
<?
}
?>
</SCRIPT>
</head>

<body bgcolor="#cccccc" text="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<form name="form1" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" value="enviar" name="action">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td><table width="780" height="434" border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
<td bordercolor="#cccccc"><table width="270" border="1" align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#000000">
<tr>
<td bordercolor="#cccccc"><table width="190" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="30%"><b><font face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF" size="-1">Login: 
</font></b></td>
<td><font face="Verdana, Arial, Helvetica, sans-serif">
<input type="text" placeholder="login" name="txt_login" size="12" maxlength="15" class=borda>
</font></td>
</tr>
<tr>
<td width="30%"><b><font face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF" size="-1">Senha: 
</font></b></td>
<td><font face="Verdana, Arial, Helvetica, sans-serif">
<input type="password" placeholder="senha" name="txt_senha" size="12" maxlength="8" class=borda>
</font></td>
</tr>
<tr>
	<td colspan="2">
		<input name="manter" id="manter" value="1" type="checkbox">
		<font face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF" size="-1"> Manter-me conectado</font>
	</td>	
</tr>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
<td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">
</font>

<table width="100%" border="0" cellpadding="3" cellspacing="0">
<!--
<tr> 
<td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
<input name="tipo" type="radio" value="1" checked>
Portfolio</font> </td>
<td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
<input type="radio" name="tipo" value="3">
Jobs</font></td>
</tr>
-->
<tr> 
<td style= "display:none"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
<input type="radio" name="tipo" value="agenda" checked="checked" >
Agenda</font></td>
<!--
<td><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
<input type="radio" name="tipo" value="4">
Boleto</font></td>
-->
</tr>
</table>

</td>
</tr>
<tr> 
<td align="center"><br> <input type="button" name="Submit" value="Entrar" onClick="submeter()" class=borda style="WIDTH: 100px"></td>
</tr>
</table></td>
</tr>
</table>
</td>
</tr>
</table></td>
</tr>
</table>
</form>

</body>
</html>
