<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 15/03/2017
 */
require_once('DataBaseMySQL/DadosCobranca.php');
require_once('Model/DadosCobranca/vo/DadosCobrancaVo.php');

class DadosCobrancaData {
	
	// Pega os dados de cobranca do usuário.
	public function GetDataDadosCobranca($id_login){
		
		$dadosCobranca = new DadosCobranca();
		
		// Pega os dados do usuário pelo Id.
		$dados = $dadosCobranca->pegaDadosCobranca($id_login);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$cobrancaVo = new DadosCobrancaVo();
			
			// Passa os dados para objeto.
			$cobrancaVo->setId($dados['id']);
			$cobrancaVo->setAssinante($dados['assinante']);
			$cobrancaVo->setPrefTelefone($dados['pref_telefone']);
			$cobrancaVo->setTelefone($dados['telefone']);	
			$cobrancaVo->setEndereco($dados['endereco']);
			$cobrancaVo->setNumero($dados['numero']);
			$cobrancaVo->setComplemento($dados['complemento']);
			$cobrancaVo->setBairro($dados['bairro']);
			$cobrancaVo->setCEP($dados['cep']);
			$cobrancaVo->setCidade($dados['cidade']);
			$cobrancaVo->setUF($dados['uf']);
		}

		return $cobrancaVo;
	}
	
	// Pega os dados de cobranca e de login do usuário.
	public function GetDataDadosUsuario($id_login){
		
		$dadosCobranca = new DadosCobranca();
		
		// Pega os dados do usuário pelo Id.
		$dados = $dadosCobranca->pegaDadosLogin($id_login);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$cobrancaVo = new DadosCobrancaVo();
			
			// Passa os dados para objeto.
			$cobrancaVo->setId($dados['id']);
			$cobrancaVo->setAssinante($dados['assinante']);
			$cobrancaVo->setPrefTelefone($dados['pref_telefone']);
			$cobrancaVo->setTelefone($dados['telefone']);	
			$cobrancaVo->setEndereco($dados['endereco']);
			$cobrancaVo->setNumero($dados['numero']);
			$cobrancaVo->setComplemento($dados['complemento']);
			$cobrancaVo->setBairro($dados['bairro']);
			$cobrancaVo->setCEP($dados['cep']);
			$cobrancaVo->setCidade($dados['cidade']);
			$cobrancaVo->setUF($dados['uf']);
			$cobrancaVo->setTipo($dados['tipo']);
			$cobrancaVo->setNome($dados['sacado']);
			$cobrancaVo->setEmail($dados['email']);
		}
		
		return $cobrancaVo;
	}
}

