<?php
include "../conect.php";

$Nome = $_POST["txtNome"];
$Mensagem = $_POST["txtMensagem"];
$Linha = $_POST["hidLinha"];
$Codigo = $_POST["hidCodigo"];

$sql = "SELECT anexo FROM suporte WHERE idPostagem='" . $Linha . "'";
$resultado = mysql_query($sql)
or die (mysql_error());
if($arquivo = mysql_fetch_array($resultado)){
	if($arquivo['anexo'] != ''){
		@unlink('../' . $arquivo['anexo']);
	}
	
	if(isset($_FILES['arqAnexo'])){
	
		if ($_FILES['arqAnexo'][size] > 1024 * 1024) {
			$tamanho = round(($_FILES[arquivo][size] / 1024 / 1024), 2);
			$med = "MB";
		} else if ($_FILES['arqAnexo'][size] > 1024) {
			$tamanho = round(($_FILES['arqAnexo'][size] / 1024), 2);
			$med = "KB";
		} else {
			$tamanho = $_FILES['arqAnexo'][size];
			$med = "Bytes";
		}
	 
		/* Defina aqui o tamanho máximo do arquivo em bytes: */
	 
		if($_FILES['arqAnexo'][size] > 2097152) { //Limite: 2MB
			print "<script> alert('Tamanho: $tamanho $med! Seu arquivo não poderá ser maior que 2MB!'); window.history.go(-1); </script>\n";
			exit;
		}
	
		if (is_file($_FILES['arqAnexo']['tmp_name'])) {
	
			$arquivo = $_FILES['arqAnexo']['tmp_name'];
	
			$arquivo_minusculo = strtolower($_FILES['arqAnexo']['name']);
			$caracteres = array("ç","~","^","]","[","{","}",";",":","´",",",">","<","-","/","|","@","$","%","ã","â","á","à","ã","é","è","í","ì","ó","ò","õ","ú","ü","+","=","*","&","(",")","!","#","?","`"," ","©");
			$caracteres_replace = array("c","","","","","","","","","","","","","","","","","","","a","a","a","a","a","e","e","i","i","o","o","o","u","u","","","","","","","","","","","","");
			$arquivo_tratado = str_replace($caracteres,$caracteres_replace,$arquivo_minusculo);
			
			$caminho="upload/helpdesk/";
			$caminho=$caminho.((string)time())."_".$arquivo_tratado;
	 
			/* Defina aqui o tipo de arquivo suportado */
			if (!(eregi(".php$", $_FILES['arqAnexo']['name']))) {
				move_uploaded_file($arquivo,$caminho) or
					die("<script> alert('Erro ao enviar o arquivo. Tente novamente!'); window.history.go(-1); </script>");
			}
	
			$str_insere_arquivo = ", anexo = '" . $caminho . "'";
	
		}
			
	}
	
}

$sql = "UPDATE suporte SET nome='$Nome', mensagem='$Mensagem' WHERE idPostagem='$Linha'";

$resultado = mysql_query($sql)
or die (mysql_error());

if($_POST['hidCadastrarFAQ'] == '1'){
	$sql = "INSERT INTO faq SET pergunta = '" . nl2br(mysql_real_escape_string($_POST['hidPerguntaFAQ'])) . "', resposta = '" . (mysql_real_escape_string($Mensagem)) . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}


header('Location: suporte_visualizar.php?codigo=' . $Codigo );
?>