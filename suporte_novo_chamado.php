<?php
	//ini_set('display_errors',1);
	//ini_set('display_startup_erros',1);
	//error_reporting(E_ALL);
include "conect.php";
session_start();
$id = $_SESSION["id_userSecaoMultiplo"];
$data = date("Y-m-d H:i:s");
$Assunto = $_POST["txtAssunto"];
$Nome = $_POST["txtNome"];
$Mensagem = urlencode($_POST["txtMensagem"]);
$contaCaracterMensagem = strlen(urlencode($_POST["txtMensagem"]));
//$Mensagem = preg_replace("/(\\r)?\\n/i", "<br/>", $_POST["txtMensagem"]);


	function remove_accent($str) 
	{ 
	  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
	  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
	  return str_replace($a, $b, $str); 
	} 
	
	function url_amigavel($str) 
	{ 
	  return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), 
	  array('', '-', ''), remove_accent($str))); 
	} 
/*Faz a verificação se a mensagem contem mais que 2800 caracteres */
if($contaCaracterMensagem > 1400){
	echo "<script> alert('Sua mensagem atingiu o limite máximo de 1.400 caracteres. Tente reduzir o texto.');window.history.go(-1); </script>";
	exit;
}
		
if(isset($_FILES['arqAnexo'])){
	if ($_FILES['arqAnexo']['size'] > 1024 * 1024) {
		$tamanho = round(($_FILES['arqAnexo']['size'] / 1024 / 1024), 2);
		$med = "MB";
	} else if ($_FILES['arqAnexo']['size'] > 1024) {
		$tamanho = round(($_FILES['arqAnexo']['size'] / 1024), 2);
		$med = "KB";
	} else {
		$tamanho = $_FILES['arqAnexo']['size'];
		$med = "Bytes";
	}
 
	/* Defina aqui o tamanho máximo do arquivo em bytes: */
 
	if($_FILES['arqAnexo']['size'] > 2097152) { //Limite: 2MB
		echo "<script> alert('Tamanho: $tamanho $med! Seu arquivo não poderá ser maior que 2MB!'); window.history.go(-1); </script>\n";
		exit;
	}
	
	/*Arquivo maior que 2MB*/
	/*if($_FILES['arqAnexo']['error'] == 1){
		echo "<script> alert('Erro ao enviar arquivo: O arquivo não pode ser superior a 2Mb');
		window.history.go(-1);</script>";
		exit;
	}*/

	if (is_file($_FILES['arqAnexo']['tmp_name'])) {
 
		$arquivo = $_FILES['arqAnexo']['tmp_name'];

//		$arquivo_minusculo = strtolower($_FILES['arqAnexo']['name']);
		//$arquivo_minusculo = strtolower($_FILES['arqAnexo']['name']);
		$arquivo_minusculo = ($_FILES['arqAnexo']['name']);
		$ext = strtolower(strrchr($arquivo_minusculo,"."));
		$arquivo_minusculo = str_replace($ext,"",$arquivo_minusculo);
		$arquivo_tratado = url_amigavel($arquivo_minusculo);


		//$arquivo_minusculo = iconv('UTF-8','ISO-8859-1//TRANSLIT',$arquivo_minusculo);

//		$caracteres = array("ç","~","^","]","[","{","}",";",":","´",",",">","<","-","/","|","@","$","%","ã","â","á","à","ã","é","è","í","ì","ó","ò","õ","ú","ü","+","=","*","&","(",")","!","#","?","`"," ","©");
		$caracteres = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ.";
//		$caracteres_replace = array("c","","","","","","","","","","","","","","","","","","","a","a","a","a","a","e","e","i","i","o","o","o","u","u","","","","","","","","","","","","");
		$caracteres_replace = "aaaaeeiooouucAAAAEEIOOOUUC-";
//		$arquivo_tratado = str_replace($caracteres,$caracteres_replace,$arquivo_minusculo);
//		$arquivo_tratado = urlencode($arquivo_tratado);
		//$arquivo_tratado = urlencode($arquivo_minusculo);//(strtr($arquivo_minusculo,$caracteres,$caracteres_replace));


		//$arquivo_tratado = str_replace("?","",$arquivo_tratado);
		
		$caminho="upload/helpdesk/";
		//$caminho=$caminho.((string)time())."_".$arquivo_tratado.$ext;
		$caminho=$caminho.((string)time()).$ext;
 
		/* Defina aqui o tipo de arquivo suportado */
		if (!(eregi(".php$", $_FILES['arqAnexo']['name']))) {
			move_uploaded_file($arquivo,$caminho) or
				die("<script> alert('Erro ao enviar o arquivo. Tente novamente!'); window.history.go(-1); </script>");
			//print "<h3><center>Arquivo enviado com sucesso usando " . $_POST['radio'] . "(), " . $tamanho . ' ' . $med . "!</center></h3>";
//		} else {
//			print "<h3><center>Arquivo n&atilde;o enviado!</center></h3>\n";
//			print "<h4><font color='#FF0000'><center>Caminho ou nome de arquivo Inv&aacute;lido!</center></font></h4>";
		}

		$str_insere_arquivo = ", anexo = '" . $caminho . "'";

	}

		
}

$sql = "INSERT INTO suporte SET
id = '$id'
, data = '$data'
, ultimaResposta = '$data'
, tipoMensagem = 'pergunta'
, titulo = '$Assunto'
, nome = '$Nome'
" . $str_insere_arquivo . "
, mensagem = '" . mysql_escape_string($Mensagem) . "'
, status = 'Em análise'";
$resultado = mysql_query($sql)
or die (mysql_error());



$log_suporte = mysql_query("INSERT INTO `log_suporte`(`id`, `id_postagem`, `id_usuario`, `id_usuario_pai`, `nome`, `tipo`, `id_pergunta`, `date`) VALUES ( '','".mysql_insert_id()."','".$_SESSION["id_userSecao"]."','".$_SESSION["id_userSecaoMultiplo"]."','".$Nome."','pergunta','','".$data ."' ) ");
$objeto=mysql_fetch_array($log_suporte);

//echo $sql;

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
O usuário ' . $Nome . ' da empresa ' . $linha["razao_social"] . ' (CNPJ: ' . $linha["cnpj"] . ') enviou o seguinte chamado no suporte:</strong><br />
<strong>Assunto:</strong> ' . $Assunto . '<br />'
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
$emaildestinatario = 'secretaria@contadoramigo.com.br';
//$emaildestinatario = 'fabio.ribeiro@gmail.com';
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

header('Location: suporte.php' );
?>