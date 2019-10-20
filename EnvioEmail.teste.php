<?php 
/**
 * Classe criada para realizar o envio e email pelo phpmailer
 */

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// Realiza a requisição do arquivo que tem a classe phpmailer
require_once("classes/phpmailer.class.php");

// Classe que envia email via 
class EnvioEmail {
		
		/** Define os atributos **/
		private $emailDestino;
		public function setemailDestino($string){
			$this->emailDestino = $string;
		}
	
		private $emailRemetente;
		public function setemailRemetente($string){
			$this->emailRemetente = $string;
		}
	
		private $nomeRemetente;
		public function setnomeRemetente($string){
			$this->nomeRemetente = $string;
		}
	
		private $assunto;
		public function setassunto($string){
			$this->assunto = $string;
		}
	
		private $mensagem;
		public function setmensagem($string){
			$this->mensagem = $string;
		}
	
		private $copia;
		public function setcopia($string){
			$this->copia = $string;
		}
	
		private $copiaoculta;
		public function setcopiaoculta($string){
			$this->copiaoculta = $string;
		}
	
		private $reply;
		public function setreply($string){
			$this->reply = $string;
		}
	
		// Método criado para pegar os dados e realizar o envio de email pelo phpMailer.
		public function enviarEmail(){

			$mail = new PHPMailer();

			$mail->IsSMTP();

			$mail->AddReplyTo($this->reply);

			$mail->From = $this->emailRemetente;

			$mail->FromName = $this->nomeRemetente;

			$mail->Subject= $this->assunto;

			$mail->MsgHTML($this->mensagem);

			$mail->AddAddress($this->emailDestino);

			$mail->IsHTML(true); 

			$testeSendMail = @$mail->Send();
			
			if($testeSendMail == 1){
				echo 'sucesso';
			}
			
		}
	
		// Método criador para montar a mensagem com os dados do cliente.
		private function MontaMensagemDemoRecemIniciado($nome) {
			
			$msg = '<html>'
				.'	<head>'
				.'		<title></title>'
				.'	</head>'
				.'	<body>'
				.'		<div style="border: #ccc 1px solid; background-color: #ffff99; width: 600px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">'
				.'		<div style="padding:15px"><img alt="Contador Amigo" height="40" src="https://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" />'
				.'		<hr size="1px" style="solid;width:100%;height: 1px;border: 0;border-top: 1px solid #ccc;margin: 1em 0;padding: 0;border-top:1px solid #143c62; margin-top:10px;" />'
				.'		<p><br />'
				.'		Ol&aacute;, [NOME]!<br />'
				.'		<br />'
				.'		Parab&eacute;ns, voc&ecirc; acaba de inciar seu per&iacute;odo de avalia&ccedil;&atilde;o do Contador Amigo e poder&aacute; utilizar&nbsp;nossa ferramenta gratuitamente por 30 dias. Queremos que voc&ecirc; tenha a melhor experi&ecirc;ncia poss&iacute;vel e, para tal, gostar&iacute;amos de<strong> agendar um bate-papo ao telefone, ou pelo Skype</strong>, para que possamos demonstrar todas as funcionalidades do Portal e&nbsp;o que voc&ecirc; precisa fazer para manter sua empresa regularizada.<br />'
				.'		<br />'
				.'		&Eacute; um papo r&aacute;pido, cerca de 15 minutos, e lhe trar&aacute; <strong>boas dicas sobre como gastar menos com impostos</strong>.<br />'
				.'		<br />'
				.'		Ligue no telefone (11) 3434-6631, ou no Skype: <em>contadoramigo</em>&nbsp;de segunda a sexta, das 10 &agrave;s 17h e solicite uma<strong> sess&atilde;o explorat&oacute;ria.</strong> Ser&aacute; um prazer conhec&ecirc;-lo. Na ocasi&atilde;o voc&ecirc; poder&aacute; aproveitar para tirar outras d&uacute;vidas relativas &agrave; sua empresa.<br />'
				.'		<br />'
				.'		Esperamos sua liga&ccedil;&atilde;o!<br />'
				.'		<br />'
				.'		<br />'
				.'		<strong>Contador Amigo</strong><br />.'
				.'		<a href="https://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></p>'
				.'		</div>'
				.'		</div>'
				.'	</body>'
				.'</html>';

			return str_replace('[NOME]', $nome, $msg);
		}
		
		// Construtor.
		public function __construct($nome, $email, $destino, $assunto, $tipoMensagem){

			$MensagemMetodo = 'MontaMensagem'.$tipoMensagem;

			$mensagemHTML = $this->$MensagemMetodo($nome);
	
			// Passa os dados do cliente para os atributos utilizados para fazer o envio,
			$this->setemailDestino($destino);
			$this->setemailRemetente('secretaria@contadoramigo.com.br');
			$this->setnomeRemetente($nome);
			$this->setassunto($assunto);
			$this->setmensagem($mensagemHTML);
			$this->setreply($email);
			
			$this->enviarEmail();
		}	
	
}
		$nome = 'atano farias';
		$email = $destino = 'a@hotmail.com';		
		$assunto = 'Mensagem enviada pelo site';
		$tipoMensagem = 'DemoRecemIniciado';

		// Realiza a chamada da classe para dar inicio ao envio do email.
		$email = new EnvioEmail($nome, $email, $destino, $assunto, $tipoMensagem);

?>