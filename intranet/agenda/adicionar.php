<?
session_start();

include ('../checa.php');

include ('../conect.php');

switch($_REQUEST["acao"]){

	case "adicionar":
		$sql = "INSERT INTO T_CONTATOS SET
					NOME ='" . $_REQUEST["txt_nome"] . "', 
					RAZAO ='" . $_REQUEST["txt_razao"] . "', 
					CNPJ ='" . $_REQUEST["txt_cnpj"] . "', 
					ENDERECO ='" . $_REQUEST["txt_endereco"] . "', 
					CIDADE ='" . $_REQUEST["txt_cidade"] . "', 
					UF ='" . $_REQUEST["txt_uf"] . "', 
					CEP ='" . $_REQUEST["txt_cep"] . "', 
					DDD ='" . $_REQUEST["txt_ddd"] . "', 
					FONE ='" . $_REQUEST["txt_fone"] . "', 
					FAX ='" . $_REQUEST["txt_fax"] . "', 
					EMAIL ='" . $_REQUEST["txt_email"] . "', 
					OUTRAS ='" . $_REQUEST["txt_outras"] . "'";
		mysql_query($sql);
		
		header('location: visualizar_resultado.php');
	break;

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Agenda 1.0 - Alterar Contato</title>
<SCRIPT LANGUAGE="JavaScript1.1">

function submeter() {
 if (document.forms[0].txt_nome.value == ""){
  alert("Digite o Nome.");
  document.forms[0].txt_nome.focus;
 }else{
  document.forms[0].submit()
 }
} 

function limpar(){
 for (var i=0; i<= 19; i++){
  document.forms[0].elements[i].value="";
 }
}

</SCRIPT>
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
</head>

<body bgcolor="#FFFFFF">
<Form action="adicionar.php" method="post" name="form1">
<table border="0" cellspacing="0" cellpadding="2">
<tr> 
<td> <font size="-1" face="verdana">Nome</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_nome" size="55" maxlength="55" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Fone</font><b> </b></td>
<td> <font size="2" face="verdana">0 xx</font><b> <font face="verdana"> 
<input type="text" name="txt_ddd" size="4" maxlength="4" value="11" class=borda>
- 
<input type="text" name="txt_fone" size="20" maxlength="20" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Fax</font><b> </b></td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_fax" size="20" maxlength="20" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">E-Mail</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_email" size="40" maxlength="40" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Raz&atilde;o</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_razao" size="55" maxlength="55" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">CNPJ</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_cnpj" size="20" maxlength="20" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Endere&ccedil;o</font><b> </b></td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_endereco" size="50" maxlength="50" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Cidade</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_cidade" size="20" maxlength="20" value="S&atilde;o Paulo" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">UF</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_uf" size="2" maxlength="2" value="SP" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">CEP</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_cep" size="10" maxlength="10" value="" class=borda>
</font></b></td>
</tr>
<tr> 
<td colspan="2" align="left"> <font face="verdana"><font size="-1">Outras 
Informa&ccedil;&otilde;es</font><b><br>
<textarea name="txt_outras" rows="10" cols="60" class=borda></textarea>
</b></td>
</tr>
</table>
<br>
<table ALIGN="left"><tr><td>
<input type="button" value="Adicionar" onClick="submeter()" class=borda>
<input type="hidden" value="adicionar" name="acao">
</form>
</td>
</tr>
</table>
</body>
</html>