<?php 
	// Classe que envia email via phpmailer
	class Email	{
		
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
	class Central_duvidas{

		private $nome;
		private $email;
		private $nome_empresa;
		private $cnpj;
		private $pref_telefone;
		private $telefone;
		private $estado;
		private $cidade;
		private $mensagem;

		function __construct(){
			$this->setnome($_POST['nome']);
			$this->setemail($_POST['email']);
			$this->setnome_empresa($_POST['nome_empresa']);
			$this->setcnpj($_POST['cnpj']);
			$this->setpref_telefone($_POST['pref_telefone']);
			$this->settelefone($_POST['telefone']);
			$this->setestado($_POST['estado']);
			$this->setcidade($_POST['cidade']);
			$this->setmensagem($_POST['mensagem']);
		}
		function enviarEmail(){

			$email = new Email();

			$email->setemailDestino("secretaria@contadoramigo.com.br");
			// $email->setcopia("stakacmtstrm@hotmail.com");
			// $email->setcopiaoculta("cmtstrm010@gmail.com");

			$email->setemailRemetente('secretaria@contadoramigo.com.br');
			$email->setnomeRemetente($this->getnome());
			$email->setassunto('Mensagem enviada pelo site');
			$mensagemHTML = '
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="margin-top:30px; margin-bottom:30px"><tr><td align="center"><table width="700" border="0" cellspacing="0" cellpadding="15" bgcolor="#F5F6F7" bordercolor="#CCCCCC" style="border:1px solid #CCC"><tr><td>
				<a href="http://www.contadoramigo.com.br/"><img src="http://www.contadoramigo.com.br/images/logo_email.png" alt="Contador Amigo" width="235" height="40" border="0" title="Contador Amigo"></a><br /><img src="http://www.contadoramigo.com.br/images/barra_email.gif" width="671" height="1" border="0" style="margin-top:4px"><br /><br />
				<font face="Tahoma, Geneva, sans-serif" color="#666666" style="font-size:14px">
				<strong>Nome:</strong> '.$this->getnome().'<br>
				<strong>Email:</strong> '.$this->getemail().'<br>'
				.( !empty($this->getnome_empresa()) ? '<strong>Nome da empresa:</strong> '.$this->getnome_empresa().'<br>' : '')
				.( !empty($this->getcnpj()) ? '<strong>CNPJ:</strong> '.$this->getcnpj().'<br>' : '') 
				.'<strong>Telefone:</strong> ('.$this->getpref_telefone().') '.$this->gettelefone().'<br> '
				.( !empty($this->getestado()) ? '<strong>Estado:</strong> '.$this->getestado().'<br>' : '') 
				.( !empty($this->getcidade()) ? '<strong>Cidade:</strong> '.$this->getcidade().'<br>' : '') 
				.'<strong>Mensagem:</strong> '.$this->getmensagem().'<br>
				</font></td></tr></table></td></tr></table>';

			$email->setmensagem($mensagemHTML);
			$email->setreply($this->getemail());

			if( isset( $_POST['tag'] ) && $_POST['tag'] == '' ){
				$email->enviarEmail();
				if( isset($_GET['mobile']) )
					echo "<script>location = 'atendimento_mei_mobile.php?sucesso';</script>";
				else
					echo "<script>location = 'fale-conosco.php?sucesso';</script>";
				return;
			}
		}
		function getnome(){
			return $this->nome;
		}
		function setnome($string){
			$this->nome = $string;
		}
		function getemail(){
			return $this->email;
		}
		function setemail($string){
			$this->email = $string;
		}
		function getnome_empresa(){
			return $this->nome_empresa;
		}
		function setnome_empresa($string){
			$this->nome_empresa = $string;
		}
		function getcnpj(){
			return $this->cnpj;
		}
		function setcnpj($string){
			$this->cnpj = $string;
		}
		function getpref_telefone(){
			return $this->pref_telefone;
		}
		function setpref_telefone($string){
			$this->pref_telefone = $string;
		}
		function gettelefone(){
			return $this->telefone;
		}
		function settelefone($string){
			$this->telefone = $string;
		}
		function getestado(){
			$aux = explode( ';' , $this->estado );
			return $aux[2];
		}
		function setestado($string){
			$this->estado = $string;
		}
		function getcidade(){
			return $this->cidade;
		}
		function setcidade($string){
			$this->cidade = $string;
		}
		function getmensagem(){
			return $this->mensagem;
		}
		function setmensagem($string){
			$this->mensagem = $string;
		}

	}

	$central_duvidas = new Central_duvidas();

	$central_duvidas->enviarEmail();

	################################################################################################################################################	
	//Inserir no emkt
	include 'emkt.api.class.php';

	$emkt = new APi_EMKT();

	$id_lista = $emkt->getIdLista("Atendimento MEI");

	$emkt->inserirContatoEMKTsemCadastro($central_duvidas->getemail(),$central_duvidas->getnome(),$id_lista);

	################################################################################################################################################

?>