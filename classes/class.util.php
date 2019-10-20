<?php
class Util{
	
	/**
	 * Função para aplicar um template no conteúdo de um email
	 *
	 * @param string $path_to_file Caminho do template html
	 * @param array $variaveis Variáveis que serão impressas no email
	 * @return string Template formatado
	 *
	 */	
	public static function converter_template_var($path_to_file, $variaveis){
		if(!file_exists($path_to_file))
		{
			trigger_error("Template de email {$path_to_file} não foi encontrado.", E_USER_ERROR);
			return;
		}
	
		$final_content = file_get_contents($path_to_file);
	
		foreach($variaveis as $nome => $valor){
			$final_content = str_replace('{{'.$nome.'}}', $valor, $final_content);
		}
	
		return $final_content;
	}

	/**
	 * Função para enviar emails via SMTP
	 *
	 * @param array $remetente Remetente ex: array('email@servidor.com', 'Nome da Pessoa')
	 * @param array $destinatario Destinatário ex: array('email@servidor.com', 'Nome da Pessoa')
	 * @param string $assunto Assunto
	 * @param string $template Caminho do arquivo HTML
	 * @param array $variaveis Variáveis que serão impressas no email
	 * @return boolean Resultado do envio
	 *
	 */
	public static function enviar_email($remetente, $destinatario, $assunto, $template, $variaveis){	
		$mail = new PHPMailer();

		$mail->IsSMTP();

		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
		$mail->Host       = "smtp.contadoramigo.com.br";      // sets GMAIL as the SMTP server
		$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
	
		$mail->Username   = "secretaria@contadoramigo.com.br";  // GMAIL username
		$mail->Password   = "ttq231kz";        // GMAIL password
	
		$mail->AddAddress($destinatario[0], $destinatario[1]); //campo será ignorado quando o envio for feito via SMTP autenticado
		$mail->SetFrom($remetente[0], $remetente[1]);
		$mail->AddReplyTo($destinatario[0], $destinatario[1]);
	
		$mail->Subject = $assunto;
	
		$mail->MsgHTML( self::converter_template_var($template, $variaveis) );
		
		if(!$mail->Send()){
			return false;
		}
	
		return true;	
	}

}
