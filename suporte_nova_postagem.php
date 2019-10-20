<?php
include "conect.php";
session_start();
$id = $_SESSION["id_userSecaoMultiplo"];
$Codigo = $_POST["hidCodigo"];
$data = date("Y-m-d H:i:s");
$Titulo = $_POST["hidTitulo"];
$Nome = $_POST["hidNome"];
$Mensagem = urlencode($_POST["txtMensagem"]);
//$Mensagem = preg_replace("/(\\r)?\\n/i", "<br/>", $_POST["txtMensagem"]);

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
		
		$ext = strtolower(strrchr($_FILES['arqAnexo']['name'],"."));

		
//		echo 		$arquivo_minusculo . "<BR>";

		$arquivo_minusculo = iconv('UTF-8','ISO-8859-1//TRANSLIT',$arquivo_minusculo);

//		echo 		$arquivo_minusculo . "<BR>";

//		echo 		utf8_encode($arquivo_minusculo) . "<BR>";
		
//		$caracteres = array("ç","~","^","]","[","{","}",";",":","´",",",">","<","-","/","|","@","$","%","ã","â","á","à","ã","é","è","í","ì","ó","ò","õ","ú","ü","+","=","*","&","(",")","!","#","?","`"," ","©");
		$caracteres = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ.";
//		$caracteres_replace = array("c","","","","","","","","","","","","","","","","","","","a","a","a","a","a","e","e","i","i","o","o","o","u","u","","","","","","","","","","","","");
		$caracteres_replace = "aaaaeeiooouucAAAAEEIOOOUUC-";
//		$arquivo_tratado = str_replace($caracteres,$caracteres_replace,$arquivo_minusculo);
//		$arquivo_tratado = urlencode($arquivo_tratado);
		$arquivo_tratado = (strtr($arquivo_minusculo,$caracteres,$caracteres_replace));

		$arquivo_tratado = str_replace("?","",$arquivo_tratado);

		
//		$arquivo_tratado = iconv('UTF-8','ISO-8859-1//TRANSLIT',$arquivo_minusculo);
		
//		echo 		($arquivo_tratado). "<BR>";
		
		$caminho="upload/helpdesk/";
		//$caminho=$caminho.((string)time())."_".$arquivo_tratado;
		$caminho=$caminho.((string)time()).$ext;
//		echo 		$caminho. "<BR>";
 
//exit;
 
		/* Defina aqui o tipo de arquivo suportado */
		if (!(eregi(".php$", $_FILES['arqAnexo']['name']))) {
			move_uploaded_file($arquivo,$caminho) or
				die("<script> alert('Erro ao enviar o arquivo. Tente novamente!'); window.history.go(-1); </script>");
		}

		$str_insere_arquivo = ", anexo = '" . $caminho . "'";

	}
		
}

/*Insere a resposta no banco de dados*/
$sql = "INSERT INTO suporte SET
id = '$id'
, idPergunta = '$Codigo'
, data = '$data'
, tipoMensagem = 'resposta'
, titulo = '$Titulo'
, nome = '$Nome'
" . $str_insere_arquivo . "
, mensagem = '" . mysql_escape_string($Mensagem) . "'"
;
$resultado = mysql_query($sql)
or die (mysql_error());


$sql = "UPDATE suporte SET status='Em análise', ultimaResposta='$data' WHERE idPostagem='$Codigo'";
$resultado = mysql_query($sql)
or die (mysql_error());

$log_suporte = mysql_query("INSERT INTO `log_suporte`(`id`, `id_postagem`, `id_usuario`, `id_usuario_pai`, `nome`, `tipo`, `id_pergunta`, `date`) VALUES ( '','','".$_SESSION["id_userSecao"]."','".$_SESSION["id_userSecaoMultiplo"]."','".$Nome."','pergunta','$Codigo','".$data ."' ) ");
$objeto=mysql_fetch_array($log_suporte);

//Pesquisar dados do usuário para enviar no e-mail. 
$sql = "SELECT * FROM login WHERE id='" . $id . "' AND id = idUsuarioPai LIMIT 0, 1";	
$resultado = mysql_query($sql)
or die (mysql_error());



$usuario=mysql_fetch_array($resultado);
$nomeUsuario = $usuario["assinante"];
$emailUsuario = $usuario["email"];

/* 
** ENVIO DA MENSAGEM - RETIRADA EM 27/01/2015 
*
*


//Pesquisar dados da empresa para enviar no e-mail. 
//$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $id . "' LIMIT 0, 1";	
//$resultado = mysql_query($sql)
//or die (mysql_error());

//$linha=mysql_fetch_array($resultado);

* Montando a mensagem a ser enviada no corpo do e-mail. *
$mensagemHTML = '<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="margin-top:30px; margin-bottom:30px"><tr><td align="center"><table width="700" border="0" cellspacing="0" cellpadding="15" bgcolor="#F5F6F7" bordercolor="#CCCCCC" style="border:1px solid #CCC"><tr><td>
<a href="http://www.contadoramigo.com.br/"><img src="http://www.contadoramigo.com.br/images/logo_email.png" alt="Contador Amigo" width="235" height="40" border="0" title="Contador Amigo"></a><br /><img src="http://www.contadoramigo.com.br/images/barra_email.gif" width="671" height="1" border="0" style="margin-top:4px"><br /><br />
<font face="Tahoma, Geneva, sans-serif" color="#666666" style="font-size:14px"><strong>Dados do assinante: <br />
Nome: ' . $nomeUsuario . '<br />
Email: ' . $emailUsuario . '<br /><br />
O usuário ' . $Nome . ' da empresa ' . $linha["razao_social"] . ' (CNPJ: ' . $linha["cnpj"] . ') respondeu o chamado no suporte:</strong><br />
<strong>Assunto:</strong> ' . $Titulo . '<br />'
. $Mensagem . '</font></td></tr></table></td></tr></table>';
 
* Medida preventiva para evitar que outros domínios sejam remetente da sua mensagem. *
if (eregi('tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$', $_SERVER[HTTP_HOST])) {
        $emailsender='Contador_Amigo<webmaster@contadoramigo.com.br>'; // Substitua essa linha pelo seu e-mail@seudominio
} else {
        $emailsender = "Contador_Amigo<webmaster@contadoramigo.com.br>";
        //    Na linha acima estamos forçando que o remetente seja 'webmaster@seudominio',
        // Você pode alterar para que o remetente seja, por exemplo, 'contato@seudominio'.
}
 
* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  *
if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
else $quebra_linha = "\n"; //Se "nÃ£o for Windows"
 
// Passando os dados obtidos pelo formulário para as variáveis abaixo
$nomeremetente     = 'Contador Amigo';
$emailremetente    = $emailsender;
$emaildestinatario = 'vitor@vad.com.br';
$comcopia          = $_POST['comcopia'];
$comcopiaoculta    = $_POST['comcopiaoculta'];
$assunto           = 'Mensagem enviada pelo suporte';
$mensagem          = $_POST['mensagem'];
 
* Montando o cabeÃ§alho da mensagem *
$headers = "MIME-Version: 1.1" .$quebra_linha;
$headers .= "Content-type: text/html; charset=utf-8" .$quebra_linha;
// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
$headers .= "From: " . $emailsender.$quebra_linha;
$headers .= "Cc: " . $comcopia . $quebra_linha;
$headers .= "Bcc: " . $comcopiaoculta . $quebra_linha;
$headers .= "Reply-To: " . $emailUsuario . $quebra_linha;
// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)
 
* Enviando a mensagem *

//É obrigatório o uso do parâmetro -r (concatenação do "From na linha de envio"), aqui na Locaweb:

if(!mail($emaildestinatario, $assunto, $mensagemHTML, $headers ,"-r".$emailsender)){ // Se for Postfix
    $headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
    mail($emaildestinatario, $assunto, $mensagemHTML, $headers );
}
 
/* Redirecionando para a página final*/
header("Location: suporte_visualizar.php?codigo=$Codigo");
?>