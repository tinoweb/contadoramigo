<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 07/07/2017
 */

// Classe criada para manipular os do funcionário no banco de dados.
class EnvioEmailsCobranca {
	
	// Retorna uma lista de funcionários de acordo com a empresa.
	public function GravaDadosEnvioEmail($contadorNome, $email, $tipo_mensagem){
		
		// INSERINDO OS DADOS DO ASSINANTE COM BOLETO A VENCER NA TABELA DE ENVIO DE MENSAGENS
		$qry = "INSERT INTO envio_emails_cobranca SET tipo_mensagem = '".$tipo_mensagem."', nome = '".$contadorNome."', email = '".$email."', status = 0, data = NOW()";
	
		// Executa a inclusão;
		mysql_query($qry);
	
		// Retorna o código do envio de email.
		return mysql_insert_id();
	}		
}