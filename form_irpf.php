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

require_once('conect.php');
// Realiza a requisição para verificar se é uma pessoa fisica 
require_once('DataBaseMySQL/DadosCobranca.php');

class IRPF{
		
	public function __construct() {
		
		if(isset($_POST['nome'])){
			
			 // Verifica se devera gera o boleto com a tarifa padrão.
			if( isset($_POST['pro_lore']) && isset($_POST['dependentes']) && $_POST['dependentes'] == 'Não' ) {
				
				//Sessao iniciada caso o serviço seja contratado
				$_SESSION['tarifaPadrao'] = 'nao';
											
				$data = date("d/m/Y");
					
				$userId = '';
				
				// Realiza o envio do email.
				$this->EnviaDados('IRPF Contratada');
				
				if (isset($_SESSION["id_userSecaoMultiplo"])) {
					
					$dadosCobranca = new DadosCobranca();
					
					$dadosLogin = $dadosCobranca->PegaDadosCobrancaPessoaFisica($_SESSION["id_userSecaoMultiplo"]);					
					
					if($dadosLogin){
						$userId = $_SESSION['id_userSecaoMultiplo'];
						$action = "/servico_form_dados_usuario.php?contratoId=20&valor=300&id_user=".$userId."&data=".$data."&tipo=IRPF";
					} else {
						$_SESSION['IRPF_Juridica'] = 'pessoaJuridica';
						$_SESSION['nome'] = $_POST['nome'];
						$_SESSION['cpf_resp'] = $_POST['cpf_resp'];
						$_SESSION['ddd'] = $_POST['ddd'];
						$_SESSION['tel_contato'] = $_POST['tel_contato'];
						$_SESSION['e-mail'] = $_POST['e-mail'];
						
						$action = "/servico_informar_dados_contratar.php?contratoId=20&valor=300&id_user=".$userId."&data=".$data."&tipo=IRPF";
					}					
										
				} else {
					$_SESSION['IRPF_Juridica'] = 'pessoaJuridica';
					$_SESSION['nome'] = $_POST['nome'];
					$_SESSION['cpf_resp'] = $_POST['cpf_resp'];
					$_SESSION['ddd'] = $_POST['ddd'];
					$_SESSION['tel_contato'] = $_POST['tel_contato'];
					$_SESSION['e-mail'] = $_POST['e-mail'];
					
					$action = "/servico_informar_dados_contratar.php?contratoId=20&valor=300&id_user=".$userId."&data=".$data."&tipo=IRPF";			
				}
				
				// Redireciona para parte de cadastro e pagamento do serviço.
				header('Location: '.$action);				
				
			} else {
								
				// Realiza o envio do email.
				$this->EnviaDados('IRPF Solicitada');
				
				//Sessao iniciado caso seja enviado a mensagem de orçamento
				$_SESSION['tarifaPadrao'] = 'nao';
				// redireciona o cliente 
				header('Location: /enviaremos_orcamento2.php');				
			}
			
		} else {
			// redireciona o cliente 
			header('Location: /servico-contador.php');
		}
	}
	
	private function EnviaDados($assunto) {
				
		// Realiza a chamada da classe para dar inicio ao envio do email.
		$envioEmail = new EnvioEmail();

		$nome = 'Contador Amigo';
		$email = $_POST['e-mail'];
		$destino = 'servicos@contadoramigo.com.br';	
		//$assunto = 'Regularização';
		$tipoMensagem = 'DadosIRPF';

		// Pega os dados e normaliza os nome para substituir 
		$dados['resposta1'] = isset($_POST['pro_lore']) ? "Sim" : "Não" ;
		$dados['resposta2'] = isset($_POST['honorarios']) ? "Sim" : "Não" ;
		$dados['resposta3'] = isset($_POST['clt']) ? "Sim" : "Não" ;
		$dados['resposta4'] = isset($_POST['alugueis']) ? "Sim" : "Não" ;
		$dados['resposta5'] = isset($_POST['quais_outras_rendas_ativa']) ? 'Sim - ' . $_POST['quais_outras_rendas'] : "Não" ;
		$dados['resposta6'] = isset($_POST['dependentes']) && $_POST['dependentes'] == 'Sim' ? 'Sim - ' . $_POST['dependentes_quais'] : "Não" ;
		
		$dados['Nome'] = $_POST['nome'];	
		
		$dados['CPF'] = $_POST['cpf_resp'];
		$dados['dataNascimento'] = $_POST['nascimento'];
		$dados['fone'] = '('.$_POST['ddd'].')'.$_POST['tel_contato'];
		$dados['WhatsApp'] = $_POST['numeroWhatsapp'];
		$dados['Email'] = $_POST['e-mail'];

		// Realiza achamada do envio.
		$envioEmail->PreparaEnvioEmail($nome, $email, $destino, $assunto, $tipoMensagem, $dados);	
		
	}
}
// Realiza a instancia da classe.
$irpf = new IRPF();