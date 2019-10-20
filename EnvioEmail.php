<?php 
/**
 * Classe criada para realizar o envio e email pelo phpmailer
 */

/* ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL); */

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
		private function enviarEmail(){

			$mail = new PHPMailer();

			$mail->IsSMTP();

			$mail->AddReplyTo($this->reply);

			$mail->From = $this->emailRemetente;

			$mail->FromName = $this->nomeRemetente;

			$mail->Subject= $this->assunto;

			$mail->MsgHTML($this->mensagem);

			$mail->AddAddress($this->emailDestino);

			$mail->IsHTML(true); 

			return @$mail->Send();
		}
	
		// Método criador para montar a mensagem com os dados do cliente.
		private function MontaMensagemDemoRecemIniciado($array) {
			
			$msg = '<html>
					<head>
						<title></title>
					</head>
					<body>
					<div style="border: #ccc 1px solid; background-color: #ffff99; width: 600px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">
					<div style="padding:15px"><img alt="Contador Amigo" height="40" src="https://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" />
					<hr size="1px" style="solid;width:100%;height: 1px;border: 0;border-top: 1px solid #ccc;margin: 1em 0;padding: 0;border-top:1px solid #143c62; margin-top:10px;" />
					<p><br />
					Ol&aacute;, [nome]!<br />
					<br />
					Parab&eacute;ns, voc&ecirc; acaba de inciar seu per&iacute;odo de avalia&ccedil;&atilde;o do <b>Contador Amigo</b> e poder&aacute; utilizar&nbsp;nossa ferramenta gratuitamente por 30 dias. Queremos que voc&ecirc; tenha a melhor experi&ecirc;ncia poss&iacute;vel e, para tal, gostar&iacute;amos de<strong> agendar um bate-papo ao telefone, ou pelo Skype</strong>, para demonstrarmos todas as funcionalidades do Portal e explicar o que voc&ecirc; precisa fazer para manter sua empresa regularizada.<br />
					<br />
					&Eacute; um papo r&aacute;pido, cerca de 15 minutos, e lhe trar&aacute; <strong>boas dicas sobre como gastar menos com impostos e cuidar de sua obriga&ccedil;&otilde;es fiscais</strong>.<br />
					<br />
					Ligue no telefone (11) 3434-6631, ou no Skype: <strong><em>contadoramigo</em>&nbsp;</strong>de segunda a sexta, das 10 &agrave;s 17h e solicite uma<strong> sess&atilde;o explorat&oacute;ria.</strong> Ser&aacute; um prazer conhec&ecirc;-lo. Na ocasi&atilde;o voc&ecirc; poder&aacute; aproveitar para tirar outras d&uacute;vidas relativas &agrave; sua empresa.<br />
					<br />
					Esperamos sua liga&ccedil;&atilde;o!<br />
					<br />
					<strong>Vitor Maradei</strong><br />
					CEO e fundador do Contador Amigo<br />
					<a href="https://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></p>
					</div>
					</div>
					</body>
					</html>';

			// Loop criado para realizar as alteração nas chaves pelos testos informado.
			foreach( $array as $key=>$val ){
				$msg = str_replace('['.$key.']', $val, $msg);
			}
			
			return $msg;
		}
	
		// Método criador para montar a mensagem com os dados do cliente.
		private function MontaMensagemContratacaoServico($array) {
			
			$msg = '<html>
					<head>
						<title></title>
					</head>
					<body>
					<div style="border: #ccc 1px solid; background-color: #ffff99; width: 400px; color:#666666; font-family:arial, helvetica, sans-serif; font-size:12px">
					<div style="padding:15px"><img alt="Contador Amigo" height="40" src="https://www.contadoramigo.com.br/images/logo_email.png" title="Contador Amigo" width="235" />
					<hr size="1px" style="solid;width:100%;height: 1px;border: 0;border-top: 1px solid #ccc;margin: 1em 0;padding: 0;border-top:1px solid #143c62; margin-top:10px;" />
					<p><br/>
					Olá, [nome]!<br/><br/>
					Informamos que o serviço de <b>[servico]</b> foi contratado com sucesso. Nosso contador parceiro entrará em contato no primeiro dia últil após a confirmação de seu pagamento.<br/>
					Se isto não ocorrer, entre em contato conosco pelo telefone <span color="color:#113b63;">(11) 3434-6631</span>, ou no Skype: <i>contadoramigo</i>.<br/><br/>
					Estamos desde jà à sua disposição para mais esclarecimentos.<br/><br/>			
					<b>Contador Amigo</b><br/>
					<a href="https://www.contadoramigo.com.br" style="color: rgb(51, 102, 153);">www.contadoramigo.com.br</a></br></p>
					</div>
					</div>
					</body>
					</html>';
			
			// Loop criado para realizar as alteração nas chaves pelos testos informado.
			foreach( $array as $key=>$val ){
				$msg = str_replace('['.$key.']', $val, $msg);
			}
			
			return $msg;
		}
	
		// Método criador para montar a mensagem com os dados do cliente.
		private function MontaMensagemDadosClienteRegularizacaoEmpresa($array) {
			
			$msg = '<html>
						<head>
							<title></title>
						</head>
						<body>
						<table>
							<tr>
								<td><b>DADOS DA EMPRESA A SER REGULARIZADA</b></td>
							</tr>
							<tr>
								<td><br><b>CNPJ:</b> </CNPJ/></td>
							</tr>
							<tr>
								<td><b>NF-e:</b> </NFE/></td>
							</tr>
							<tr>
								<td><b>Sócio Responsável:</b> </SocioResponsavel/></td>
							</tr>							
							<tr>
								<td><b>CPF:</b> </CPF/></td>
							</tr>							
							<tr>
								<td><b>Data de Nascimento:</b> </dataNascimento/></td>
							</tr>							
							<tr>
								<td><b>Tel. para contato:</b> </fone/></td>
							</tr>							
							<tr>
								<td><b>WhatsApp:</b> </WhatsApp/></td>
							</tr>							
							<tr>
								<td><b>E-mail:</b> </Email/></td>
							</tr>
							<tr>
								<td><br><b>Tem um certificado digital E-CNPJ?</b></td>
							</tr>
							<tr>
								<td></resposta1/></td><br>
							</tr>
							<tr>
								<td><br><b>O sócio responsável e possui o recibo de entrega de suas 2 últimas declarações de IR pessoa física?</b></td>
							</tr>
							<tr>
								<td></resposta2/></td>
							</tr>	
							<tr>
								<td>
									<br><b>Nº recibo 1:</b> </recibo1/> <b>Ano:</b> </ano1/>
								</td>
							</tr>
							<tr>
								<td>
									<b>Nº recibo 2:</b> </recibo2/> <b>Ano:</b> </ano2/> 
								</td>
							</tr>
							<tr>
								<td><br><b>Optante pelo Simples?</b></td>
							</tr>
							<tr>
								<td></resposta3/></td>
							</tr>	
							<tr>
								<td><br><b>A regularização envolve funcionários?</b></td>
							</tr>	
							<tr>
								<td></resposta4/></td><br>
							</tr>	
							<tr>
								<td><br><b>Qual o tipo de atividade desenvolvida?</b></td>
							</tr>
							<tr>
								<td></resposta5/></td><br>
							</tr>														
						</table>			
					</html>';
			
			// Loop criado para realizar as alteração nas chaves pelos testos informado.
			foreach( $array as $key=>$val ){
				$msg = str_replace('</'.$key.'/>', $val, $msg);
			}
			
			return $msg;
		}	
	
	
		// Método criador para montar a mensagem com os dados do cliente.
		private function MontaMensagemDadosIRPF($array) {
			
			$msg = '<html>
						<head>
							<title></title>
						</head>
						<body>
						<table>
							<tr>
								<td><b>Imposto de Renda Pessoa Física</b></td>
							</tr>
							<tr>
								<td><b>Nome:</b> </Nome/></td>
							</tr>
							<tr>
								<td><b>CPF:</b> </CPF/></td>
							</tr>							
							<tr>
								<td><b>Data de Nascimento:</b> </dataNascimento/></td>
							</tr>							
							<tr>
								<td><b>Tel. para contato:</b> </fone/></td>
							</tr>							
							<tr>
								<td><b>WhatsApp:</b> </WhatsApp/></td>
							</tr>							
							<tr>
								<td><b>E-mail:</b> </Email/></td>
							</tr>
							<tr>
								<td><br><b>Rendas que possui:</b></td>
							</tr>
							<tr>
								<td><br><b>Apenas as retiradas de pró-lore e distribuição de lucros da minha empresa</b></td>
							</tr>
							<tr>
								<td></resposta1/></td><br>
							</tr>
							<tr>
								<td><br><b>Honorários de profissional liberal (sem empresa)</b></td>
							</tr>
							<tr>
								<td></resposta2/></td>
							</tr>	
							<tr>
								<td><br><b>Salário CLT?</b></td>
							</tr>
							<tr>
								<td></resposta3/></td>
							</tr>	
							<tr>
								<td><br><b>Renda de Alugueis</b></td>
							</tr>	
							<tr>
								<td></resposta4/></td><br>
							</tr>	
							<tr>
								<td><br><b>Outras rendas</b></td>
							</tr>
							<tr>
								<td></resposta5/></td><br>
							</tr>						
							<tr>
								<td><br><b>Possui dependentes com rendas?</b></td>
							</tr>							
							<tr>
								<td></resposta6/></td><br>
							</tr>							
						</table>			
					</html>';
			
			// Loop criado para realizar as alteração nas chaves pelos testos informado.
			foreach( $array as $key=>$val ){
				$msg = str_replace('</'.$key.'/>', $val, $msg);
			}
			
			return $msg;
		}	
	
		// Construtor.
		public function PreparaEnvioEmail($nome, $email, $destino, $assunto, $tipoMensagem, $arrayOutros = array()){

			$MensagemMetodo = 'MontaMensagem'.$tipoMensagem;
			
			$nomeRemetente = 'Contador Amigo';
			$remetente = 'secretaria@contadoramigo.com.br';

			$arrayOutros['nome'] = $nome;
			
			$mensagemHTML = $this->$MensagemMetodo($arrayOutros);
	
			// Passa os dados do cliente para os atributos utilizados para fazer o envio,
			$this->setemailDestino($destino);
			$this->setemailRemetente($remetente);
			$this->setnomeRemetente($nomeRemetente);
			$this->setassunto($assunto);
			$this->setmensagem($mensagemHTML);
			$this->setreply($email);
			
			return $this->enviarEmail();
		}	
	
}

?>