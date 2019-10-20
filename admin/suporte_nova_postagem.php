<?php
include "../conect.php";
session_start();


$id = $_SESSION["idAdm_userSecao"];
$Nome = $_POST["txtNome"];
//$Mensagem = preg_replace("/(\\r)?\\n/i", "<br/>", $_POST["txtMensagem"]);
$Mensagem = urlencode($_POST["txtMensagem"]);
$Status = $_POST["radStatus"];
$Codigo = $_POST["hidCodigo"];
$Titulo = $_POST["hidTitulo"];
$NomeDestinatario = $_POST["hidNomeDestinatario"];
$IdDestinatario = $_POST["hidIdDestinatario"];
$data = date("Y-m-d H:i:s");


$consulta = mysql_query("DELETE FROM suporte_rascunho WHERE codigo = '".$Codigo."' ");
$objeto=mysql_fetch_array($consulta);


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
			move_uploaded_file($arquivo,"../" . $caminho) or
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


if($_POST['hidCadastrarFAQ'] == '1'){
	$sql = "INSERT INTO faq SET pergunta = '" . nl2br(mysql_real_escape_string($_POST['hidPerguntaFAQ'])) . "', resposta = '" . (mysql_real_escape_string($Mensagem)) . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
}


/*Modifica o status do chamado e a hora da última resposta*/
$sql = "UPDATE suporte SET status='$Status', ultimaResposta='$data' WHERE idPostagem='$Codigo'";
$resultado = mysql_query($sql)
or die (mysql_error());

$log_suporte = mysql_query("INSERT INTO `log_suporte`(`id`, `id_postagem`, `id_usuario`, `id_usuario_pai`, `nome`, `tipo`, `id_pergunta`, `date`) VALUES ( '','','1','1','suporte','resposta','$Codigo','".$data ."' ) ");
$objeto=mysql_fetch_array($log_suporte);


/*Pega o endereço de e-mail a ser enviada a resposta*/
$sql = "SELECT * FROM login WHERE id='$IdDestinatario'";
$resultado = mysql_query($sql)
or die (mysql_error());

$linha=mysql_fetch_array($resultado);



$email = $linha['email'];





	$NomePessoal = $NomeDestinatario;
	$emailPessoal = "secretaria@contadoramigo.com.br";

	$emailuser = 'secretaria@contadoramigo.com.br';
	$assuntoMail = 'Resposta do Help Desk';


/* Montando a mensagem a ser enviada no corpo do e-mail. */
$mensagemHTML = '<table width="340" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="margin-top:30px; margin-bottom:30px"><tr><td width="340" align="center"><table width="340" border="0" cellspacing="0" cellpadding="15" bgcolor="#ff9" bordercolor="#CCCCCC" style="border:1px solid #CCC"><tr><td width="340"><a href="https://www.contadoramigo.com.br/"><img src="https://www.contadoramigo.com.br/images/logo_email.png" alt="Contador Amigo" width="235" height="40" border="0" title="Contador Amigo"></a><br /><img src="https://www.contadoramigo.com.br/images/barra_email.gif" width="340" height="1" border="0" style="margin-top:4px"><br /><br />
<font face="arial, helvetica, sans-serif" color="#666666" style="font-size:12px">Olá <b>' . $NomeDestinatario . '</b>,<br><br>
 Seu chamado intitulado <strong>&quot;</strong><b>' . $Titulo . '</b><strong>&quot;</strong> acaba de ser respondido. <a style="color: rgb(51, 102, 153);"  href="https://www.contadoramigo.com.br/suporte_visualizar.php?codigo=' . $Codigo . '">Visualize aqui</a> a resposta.<br><br><span style="color:#000"><strong>Contador Amigo</strong></span><br /><a href="http://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></div></font></td></tr></table></td></tr></table>';


	$emailsender = "secretaria@contadoramigo.com.br"; 
	$nomeSender  = "Contador Amigo"; 

	// Passando os dados obtidos pelo formulário para as variáveis abaixo
	$nomeremetente     = 'Contador Amigo';
	$emailremetente    = $emailsender;

	if($emailPessoal!=''){
		$emailremetente = $emailPessoal;
	}

	$emaildestinatario = $emailuser;

	// instanciado a classe phpmailer
	require("../classes/phpmailer.class.php");
	$mail = new PHPMailer();

	$mail->IsSMTP(); // telling the class to use SMTP
		
	$mail->AddReplyTo($emailremetente);

	$mail->From = $emailsender;

	$mail->FromName = $nomeSender;

	$mail->Subject= $assuntoMail;

	$mail->MsgHTML($mensagemHTML);

	$mail->AddAddress($email);

	if($comcopia != ""){
		$mail->AddCC($comcopia);
	}
	if($comcopiaoculta != ""){
		$mail->AddBCC($comcopiaoculta);
	}

	$mail->IsHTML(true); 

	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	$testeSendMail = $mail->Send();
	 
	echo '<script>

		window.location = "suporte_visualizar.php?codigo='.$Codigo.'";

	</script>';



?>

