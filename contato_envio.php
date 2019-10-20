<?php

// Classe que envia email via phpmailer
class Email
{
	
	public $emailDestino;
	public $emailRemetente;
	public $nomeRemetente;
	public $assunto;
	public $mensagem;
	public $copia;
	public $copiaoculta;
	public $reply;


	public function setemailDestino($string){
		$this->emailDestino = $string;
	}
	public function setemailRemetente($string){
		$this->emailRemetente = $string;
	}
	public function setnomeRemetente($string){
		$this->nomeRemetente = $string;
	}
	public function setassunto($string){
		$this->assunto = $string;
	}
	public function setmensagem($string){
		$this->mensagem = $string;
	}
	public function setcopia($string){
		$this->copia = $string;
	}
	public function setcopiaoculta($string){
		$this->copiaoculta = $string;
	}
	public function setreply($string){
		$this->reply = $string;
	}

	public function enviarEmail(){

		require("classes/phpmailer.class.php");
		$mail = new PHPMailer();

		$mail->IsSMTP(); // telling the class to use SMTP

		$mail->AddReplyTo($this->reply);

		$mail->From = $this->emailRemetente;

		$mail->FromName = $this->nomeRemetente;

		$mail->Subject= $this->assunto;

		$mail->MsgHTML($this->mensagem);

		$mail->AddAddress($this->emailDestino);

		// if($this->copia != ""){
		// 	$mail->AddCC($this->copia);
		// }
		// if($this->copiaoculta != ""){
		// 	$mail->AddBCC($this->copiaoculta);
		// }

		$mail->IsHTML(true); 

		$testeSendMail = @$mail->Send();

	}
	
}

$NomePessoal = $_POST["NomePessoal"];
$emailPessoal = $_POST["emailPessoal"];
$telefone = "(" . $_POST["DDDPessoal"] . ") " . $_POST["telPessoal"];
$Mensagem = preg_replace("/(\\r)?\\n/i", "<br/>
", $_POST["Mensagem"]);
$paginaAtual = $_POST["paginaAtual"];

$emailuser = 'secretaria@contadoramigo.com.br';
$assuntoMail = 'Mensagem enviada pelo site';

$mensagemHTML = '<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="margin-top:30px; margin-bottom:30px"><tr><td align="center"><table width="700" border="0" cellspacing="0" cellpadding="15" bgcolor="#F5F6F7" bordercolor="#CCCCCC" style="border:1px solid #CCC"><tr><td>
<a href="http://www.contadoramigo.com.br/"><img src="http://www.contadoramigo.com.br/images/logo_email.png" alt="Contador Amigo" width="235" height="40" border="0" title="Contador Amigo"></a><br /><img src="http://www.contadoramigo.com.br/images/barra_email.gif" width="671" height="1" border="0" style="margin-top:4px"><br /><br />
<font face="Tahoma, Geneva, sans-serif" color="#666666" style="font-size:14px"><strong>Nome:</strong> ' . $NomePessoal . '<br />
<strong>Email:</strong> ' . $emailPessoal . '<br>
<strong>Telefone:</strong> ' . $telefone . '<br>
<br>
' . $Mensagem . '</font></td></tr></table></td></tr></table>';



/* Montando a mensagem a ser enviada no corpo do e-mail. */
// $mensagemHTML = '';

$email = new Email();

$email->setemailDestino("secretaria@contadoramigo.com.br");
// $email->setcopia("stakacmtstrm@hotmail.com");
// $email->setcopiaoculta("cmtstrm010@gmail.com");

$email->setemailRemetente('secretaria@contadoramigo.com.br');
$email->setnomeRemetente($NomePessoal);
$email->setassunto($assuntoMail);
$email->setmensagem($mensagemHTML);
$email->setreply($emailPessoal);

if( isset( $_POST['id_contato'] ) && $_POST['id_contato'] == '' ){
	$email->enviarEmail();
}

// echo $mensagemHTML;

if( $_POST["NomePessoal"] == 'testemal' )
	exit();
 
/* Redirecionando para a página de sucesso */
if($paginaAtual == "") {
	$paginaAtual = "index.php";
}



################################################################################################################################################	
//Inserir no emkt
include 'emkt.api.class.php';

$emkt = new APi_EMKT();

$id_da_lista = $emkt->getIdLista("Prospects");

$emkt->inserirContatoEMKTsemCadastro($emailPessoal,$NomePessoal,$id_da_lista);

#####################################################


if( isset($_GET['mobile']) )
	echo '<script>window.location="contato_mobile.php?sucesso"</script>';
else
	echo '<script>window.location="' . $paginaAtual . '?email_enviado"</script>';
?>