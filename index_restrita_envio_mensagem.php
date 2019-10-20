<?php
include 'session.php';
include 'conect.php' ;

$sql = "SELECT * FROM dados_da_empresa WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";	
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);


//Pesquisar dados do usuário para enviar no e-mail. 
$sql = "SELECT * FROM login WHERE id='" . $_SESSION["id_userSecao"] . "' LIMIT 0, 1";	
$resultado = mysql_query($sql)
or die (mysql_error());

$usuario=mysql_fetch_array($resultado);
$nomeUsuario = $usuario["assinante"];
$emailUsuario = $usuario["email"];

$txtMensagem = preg_replace("/(\\r)?\\n/i", "<br/>
", $_POST["txtMensagem"]);

/* Montando a mensagem a ser enviada no corpo do e-mail. */
$mensagemHTML = '<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="margin-top:30px; margin-bottom:30px"><tr><td align="center"><table width="700" border="0" cellspacing="0" cellpadding="15" bgcolor="#F5F6F7" bordercolor="#CCCCCC" style="border:1px solid #CCC"><tr><td>
<a href="http://www.contadoramigo.com.br/"><img src="http://www.contadoramigo.com.br/images/logo_email.png" alt="Contador Amigo" width="235" height="40" border="0" title="Contador Amigo"></a><br /><img src="http://www.contadoramigo.com.br/images/barra_email.gif" width="671" height="1" border="0" style="margin-top:4px"><br /><br />
<font face="Tahoma, Geneva, sans-serif" color="#666666" style="font-size:14px"><strong>Dados do assinante: <br />
Nome: ' . $nomeUsuario . '<br />
Email: ' . $emailUsuario . '<br /><br />
O usuário da empresa ' . $linha["razao_social"] . ' (CNPJ: ' . $linha["cnpj"] . ') enviou a seguinte mensagem:</strong><br />'
. $txtMensagem . '</font></td></tr></table></td></tr></table>';
 
/* Medida preventiva para evitar que outros domínios sejam remetente da sua mensagem. */
if (eregi('tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$', $_SERVER[HTTP_HOST])) {
        $emailsender='Contador_Amigo<webmaster@contadoramigo.com.br>'; // Substitua essa linha pelo seu e-mail@seudominio
} else {
        $emailsender = "Contador_Amigo<webmaster@contadoramigo.com.br>";
        //    Na linha acima estamos forçando que o remetente seja 'webmaster@seudominio',
        // Você pode alterar para que o remetente seja, por exemplo, 'contato@seudominio'.
}
 
/* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  */
if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
else $quebra_linha = "\n"; //Se "nÃ£o for Windows"
 
// Passando os dados obtidos pelo formulário para as variáveis abaixo
$nomeremetente     = 'Contador Amigo';
$emailremetente    = $emailsender;
$emaildestinatario = 'secretaria@contadoramigo.com.br';
$comcopia          = $_POST['comcopia'];
$comcopiaoculta    = $_POST['comcopiaoculta'];
$assunto           = 'Mensagem enviada pelo site';
$mensagem          = $_POST['mensagem'];
 
/* Montando o cabeÃ§alho da mensagem */
$headers = "MIME-Version: 1.1" .$quebra_linha;
$headers .= "Content-type: text/html; charset=utf-8" .$quebra_linha;
// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
$headers .= "From: " . $emailsender.$quebra_linha;
$headers .= "Cc: " . $comcopia . $quebra_linha;
$headers .= "Bcc: " . $comcopiaoculta . $quebra_linha;
$headers .= "Reply-To: " . $emailUsuario . $quebra_linha;
// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)
 
/* Enviando a mensagem */

//É obrigatório o uso do parâmetro -r (concatenação do "From na linha de envio"), aqui na Locaweb:

if(!mail($emaildestinatario, $assunto, $mensagemHTML, $headers ,"-r".$emailsender)){ // Se for Postfix
    $headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
    mail($emaildestinatario, $assunto, $mensagemHTML, $headers );
}
 
/* Redirecionando para a página de sucesso */
echo '<script>alert("Mensagem enviada com sucesso!");
window.location="index_restrita.php"</script>';
?>