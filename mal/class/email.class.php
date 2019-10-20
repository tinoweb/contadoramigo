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

		public function enviarEmail(){

			require("../classes/phpmailer.class.php");
			$mail = new PHPMailer();

			$mail->IsSMTP(); // telling the class to use SMTP

			$mail->AddReplyTo($this->emailRemetente);

			$mail->From = $this->emailRemetente;

			$mail->FromName = $this->nomeRemetente;

			$mail->Subject= $this->assunto;

			$mail->MsgHTML($this->mensagem);

			$mail->AddAddress($this->emailDestino);

			if($this->copia != ""){
				$mail->AddCC($this->copia);
			}
			if($this->copiaoculta != ""){
				$mail->AddBCC($this->copiaoculta);
			}

			$mail->IsHTML(true); 

			$testeSendMail = @$mail->Send();

		}
		
	}

	/* Montando a mensagem a ser enviada no corpo do e-mail. */
	// $mensagemHTML = '';

	// $email = new Email();

	// $email->setemailDestino("mauricio.silva.ufscar@gmail.com");
	// $email->setcopia("stakacmtstrm@hotmail.com");
	// $email->setcopiaoculta("cmtstrm010@gmail.com");
	// $email->setemailRemetente("secretaria@contadoramigo.com.br");
	// $email->setnomeRemetente("Teste Email");
	// $email->setassunto("Teste Classe");
	// $email->setmensagem($mensagemHTML);

	// $email->enviarEmail();
	 
	
?>