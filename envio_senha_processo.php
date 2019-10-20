<?php 

	// Classe responsavel por evitar sql injection atraves do GET, POST ou COOKIES
	class Anti_Sql_Injection{
		
		private function sql_injection($sql) {
			//Remove comandos SQL da string
		    $sql = preg_replace(sql_regcase("/(from|select|insert|delete|union|where| OR |OR | OR|drop table|show tables|#|--|\\\\)/"),"",$sql);
		    //Remove espacos do inicio o  fim da string
		    $sql = trim($sql);
		    //Remove tags html da string
		    $sql = strip_tags($sql);
		    //Adiciona \ (barra invertida) antes de ' ou "
		    $sql = addslashes($sql);
		    return $sql;
		}

		function execute(){
			// $this->securityGET();
			$this->securityPOST();
			// $this->securityCOOKIE();
		}
		private function securityGET(){
			foreach ($_GET as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do GET
				$_GET[$key] = $this->sql_injection($_GET[$key]);
			}
		}
		private function securityPOST(){
			foreach ($_POST as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do POST
				$_POST[$key] = $this->sql_injection($_POST[$key]);
			}
		}
		private function securityCOOKIE(){
			foreach ($_COOKIE as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do COOKIE
				$_COOKIE[$key] = $this->sql_injection($_COOKIE[$key]);
			}
		}

	}

	$security = new Anti_Sql_Injection();
	$security->execute();

?>
<?php

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

				require("classes/phpmailer.class.php");
				$mail = new PHPMailer();

				$mail->IsSMTP(); // telling the class to use SMTP

				$mail->AddReplyTo("contadoramigo@contadoramigo.com.br");

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

include 'conect.php';

$email = $_POST['txtEmailSenha'];	
	
$sql = "SELECT * FROM login WHERE email='" . $email . "' AND id = idUsuarioPai";
$resultado = mysql_query($sql)
or die (mysql_error());

if (mysql_num_rows($resultado) == 0) {
echo "<script>window.alert('Não há nenhum usuário cadastrado com este endereço de e-mail. Verifique se o e-mail está correto e tente novamente.');
history.back();
</script>";
die();
}
else {

	while($linha=mysql_fetch_array($resultado)) {
		$empresa = $linha["nome"];
		$senha = $linha["senha"];
		$id = $linha["id"];
		$Assinante = $linha["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);

		
		// Classe que envia email via phpmailer
		

		$mensagemHTML = '<table border="0" cellpadding="15" cellspacing="0" style="border-bottom: #ccc 1px solid; border-left: #ccc 1px solid; background-color: #ffff99; width: 340px; border-top: #ccc 1px solid; border-right: #ccc 1px solid">
			<tbody>
				<tr>
					<td>
						<img alt="Contador Amigo" height="55" src="http://www.contadoramigo.com.br/images/logo_email_linha.png" title="Contador Amigo" width="310" />
						<div style="text-align: left; font-family: arial, helvetica, sans-serif; color: #666; margin-left: 0px; font-size: 12px">
							Olá ' . $AssinanteExplode[0] . ', <br />
							<br />
							Essa mensagem foi gerada através do pedido de recuperação de senha efetuado em nosso portal.<br />
			<br />
			Sua senha de acesso é: <strong>' . $senha . '</strong><br />
			No campo email, digite o mesmo  endereço usado <br />
			para o envio desta mensagem. <br />
			<br />
								<strong>Contador Amigo</strong><br />
						  <a href="http://www.contadoramigo.com.br" style="color: #369">www.contadoramigo.com.br</a></div>
						</td>
					</tr>
				</tbody>
			</table>';

		/* Montando a mensagem a ser enviada no corpo do e-mail. */
		// $mensagemHTML = '';

		$email = new Email();

		$email->setemailDestino($_POST['txtEmailSenha']);
		// $email->setcopia("stakacmtstrm@hotmail.com");
		// $email->setcopiaoculta("cmtstrm010@gmail.com");
		$email->setemailRemetente("noreply@contadoramigo.com.br");
		$email->setnomeRemetente("Contador Amigo");
		$email->setassunto("Recuperação de Senha");
		$email->setmensagem($mensagemHTML);
		$email->enviarEmail();
		echo '<script>window.location="envio_senha_sucesso.php"</script>';		
	}




/* Redirecionando para a página de sucesso */

}
?>