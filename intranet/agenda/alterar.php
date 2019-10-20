<?
session_start();

include ('../checa.php');

include ('../conect.php');


if($_REQUEST["id"] != ""){
	$ID_CONTATO = $_REQUEST["id"];
}

switch($_REQUEST["acao"]){



	case "alterar":
		$sql = "UPDATE T_CONTATOS SET 
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
					OUTRAS ='" . $_REQUEST["txt_outras"] . "' 
					WHERE CONTATO_ID=" . $ID_CONTATO ;
		mysql_query($sql);
	break;
	case "excluir":
		$sql = "DELETE FROM T_CONTATOS WHERE CONTATO_ID=" . $ID_CONTATO ;
		mysql_query($sql);
		header("location: visualizar_resultado.php");
	break;

}

$sql_dados = "SELECT * FROM T_CONTATOS WHERE CONTATO_ID = " . $ID_CONTATO;
$resultado = mysql_query($sql_dados);

if($dados = mysql_fetch_array($resultado, MYSQL_ASSOC)){

	$NOME = $dados["NOME"];
	$DDD = $dados["DDD"];
	$FONE = $dados["FONE"];
	$RAZAO = $dados["RAZAO"];
	$CNPJ = $dados["CNPJ"];
	$ENDERECO = $dados["ENDERECO"];
	$CIDADE = $dados["CIDADE"];
	$UF = $dados["UF"];
	$CEP = $dados["CEP"];
	$FAX = $dados["FAX"];
	$EMAIL = $dados["EMAIL"];
	$OUTRAS = $dados["OUTRAS"];
	$CONTATO_ID = $dados["CONTATO_ID"];

}

mysql_free_result($resultado);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Agenda 1.0 - Alterar Contato</title>
<SCRIPT LANGUAGE="JavaScript1.1">

function alterar() {
 if (document.form1.txt_nome.value == ""){
  alert("Digite o Nome");
  document.form1.txt_nome.focus;
 }else{
  document.form1.submit();
 }
} 

function excluir() { 
 if (confirm("Tem certeza que deseja excluir o contato?")){
 document.form2.submit();
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
<Form action="alterar.php" method="post" name="form1">
<table border="0" cellspacing="0" cellpadding="2">
<tr> 
<td> <font size="-1" face="verdana">Nome</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_nome" size="55" maxlength="55" value="<?=$NOME?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Fone</font><b> </b></td>
<td> <font size="2" face="verdana">0 xx</font><b> <font face="verdana"> 
<input type="text" name="txt_ddd" size="4" maxlength="4" value="<?=$DDD?>" class=borda>
- 
<input type="text" name="txt_fone" size="20" maxlength="20" value="<?=$FONE?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Fax</font><b> </b></td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_fax" size="20" maxlength="20" value="<?=$FAX?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">E-Mail</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_email" size="40" maxlength="40" value="<?=$EMAIL?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Raz&atilde;o</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_razao" size="55" maxlength="55" value="<?=$RAZAO?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">CNPJ</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_cnpj" size="20" maxlength="20" value="<?=$CNPJ?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Endere&ccedil;o</font><b> </b></td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_endereco" size="50" maxlength="50" value="<?=$ENDERECO?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">Cidade</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_cidade" size="20" maxlength="20" value="<?=$CIDADE?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">UF</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_uf" size="2" maxlength="2" value="<?=$UF?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td> <font size="-1" face="verdana">CEP</font> </td>
<td> <b><font face="verdana"> 
<input type="text" name="txt_cep" size="10" maxlength="10" value="<?=$CEP?>" class=borda>
</font></b></td>
</tr>
<tr> 
<td colspan="2" align="left"> <font face="verdana"><font size="-1">Outras 
Informa&ccedil;&otilde;es</font><b><br>
<textarea name="txt_outras" rows="10" cols="60" class=borda><?=$OUTRAS?></textarea>
</b></td>
</tr>
</table>
<br>
<table ALIGN="left"><tr><td>
<input type="button" value="Alterar" onClick="alterar()" class=borda>
<input type="hidden" value="alterar" name="acao">
<input type="hidden" value= <?=$CONTATO_ID?> name="id"> 
</form>
</td>
<td>
<form action="alterar.php" method="post" name="form2">
<input type="button" value="Excluir" onClick="excluir()" class=borda>
<input type="hidden" value="excluir" name="acao">
<input type="hidden" value= <?=$CONTATO_ID?> name="id">
</form>
</td>
</tr>
</table>
</body>
</html>