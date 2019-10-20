<?php
/**
 * Data: 28/02/2018
 * Autor: Atano de Farias Jacinto.
 */
session_start();
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// Realiza a requisição do arquivo responsavel por fazer o envio do email.
require_once('EnvioEmail.php');

class RegularizacaoEmpresa{
		
	public function __construct() {
		
		
		if(isset($_POST['cnpj'])){
			
			// Realiza o envio do email.
			$this->EnviaDados();
			

			// Verifica se devera gera o boleto com a tarifa padrão para referente a regularização de empresa e/ou baixa
			if($_POST['certificado'] == 'sim' && $_POST['optante_simples'] == 'sim' && $_POST['funcionarios'] == 'nao'){
				
				//Sessao iniciada caso o serviço seja contratado
				$_SESSION['tarifaPadrao'] = 'nao';
				header('Location: /enviaremos_orcamento.php');

				// Redireciona para parte de cadastro e pagamento do serviço.
				//header('Location: servico_form_dados_usuario.php');
			} else {
				
				//Sessao iniciado caso seja enviado a mensagem de orçamento
				$_SESSION['tarifaPadrao'] = 'nao';
				// redireciona o cliente 
				header('Location: /enviaremos_orcamento.php');
			}
			
		} else {
			// redireciona o cliente 
			header('Location: /servico-contador.php');
		}
	}
	
	private function EnviaDados() {
				
		// Realiza a chamada da classe para dar inicio ao envio do email.
		$envioEmail = new EnvioEmail();

		$nome = 'Contador Amigo';
		$email = $_POST['e-mail'];
		$destino = 'servicos@contadoramigo.com.br';
		$assunto = 'Regularização';
		$tipoMensagem = 'DadosClienteRegularizacaoEmpresa';

		// Pega os dados e normaliza os nome para substituir 
		$dados['resposta1'] = $_POST['certificado'];
		$dados['resposta2'] =  $_POST['recibo_IR'];
		$dados['recibo1'] =  $_POST['numero_recibo1'];
		$dados['ano1'] =  $_POST['ano_recibo1'];
		$dados['recibo2'] =  $_POST['numero_recibo2'];
		$dados['ano2'] =  $_POST['ano_recibo2'];
		$dados['resposta3'] = $_POST['optante_simples'];
		$dados['resposta4'] = $_POST['funcionarios'];
		$dados['resposta5'] = $_POST['tipo'];
		$dados['CNPJ'] = $_POST['cnpj'];
		$dados['NFE'] = $_POST['nfe'];
		$dados['SocioResponsavel'] = $_POST['socio_resp'];
		$dados['CPF'] = $_POST['cpf_resp'];
		$dados['dataNascimento'] = $_POST['nascimento_socio_resp'];
		$dados['fone'] = '('.$_POST['ddd'].')'.$_POST['tel_contato'];
		$dados['WhatsApp'] = $_POST['numero_whatsapp'];
		$dados['Email'] = $_POST['e-mail'];

		// Realiza achamada do envio.
		@$envioEmail->PreparaEnvioEmail($nome, $email, $destino, $assunto, $tipoMensagem, $dados);	
		
	}
}

// Realiza a instancia da classe.
$regularizacaoEmpresa = new RegularizacaoEmpresa();