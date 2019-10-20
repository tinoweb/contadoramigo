<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 17/03/2017
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	// inclusão dos arquivos de buscar e auxiliar dados da contador.  
	require_once('../DataBaseMySQL/DadosContador.php');
	require_once('../Model/DadosContratoContadorCliente/vo/DadosContratoContadorVo.php');
	
	// inclusão dos arquivos de buscar e auxiliar dados da crobanca. 
	require_once('../DataBaseMySQL/DadosCobranca.php');
	require_once('../Model/DadosContratoContadorCliente/vo/DadosContratoClienteVo.php');
} elseif($requestURI[1] == 'admin') {	
	// inclusão dos arquivos de buscar e auxiliar dados da contador.  
	require_once('../DataBaseMySQL/DadosContador.php');
	require_once('../Model/DadosContratoContadorCliente/vo/DadosContratoContadorVo.php');
	
	// inclusão dos arquivos de buscar e auxiliar dados da crobanca. 
	require_once('../DataBaseMySQL/DadosCobranca.php');
	require_once('../Model/DadosContratoContadorCliente/vo/DadosContratoClienteVo.php');
} else {
	// inclusão dos arquivos de buscar e auxiliar dados da contador.  
	require_once('DataBaseMySQL/DadosContador.php');
	require_once('Model/DadosContratoContadorCliente/vo/DadosContratoContadorVo.php');
	
	// inclusão dos arquivos de buscar e auxiliar dados da crobanca. 
	require_once('DataBaseMySQL/DadosCobranca.php');
	require_once('Model/DadosContratoContadorCliente/vo/DadosContratoClienteVo.php');
}
class DadosContratoContadorClienteData {
	
	// Pega os dados do contador de acordo com o estado.
	public function GetDataDadosContadorUF($UF){
		
		$contadorVo = '';
		
		$dadosContador = new DadosContador();
		
		// Pega os dados do usuário pelo Id.
		$dados = $dadosContador->pegaDadosContadorUF($UF);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$contadorVo = new DadosContratoContadorVo();
			
			// Passa os dados para objeto.
			$contadorVo->setId($dados['id']);
			$contadorVo->setNome($dados['nome']);
			$contadorVo->setCRC($dados['crc']);
			$contadorVo->setEndereco($dados['endereco']);
			$contadorVo->setBairro($dados['bairro']);
			$contadorVo->setCidade($dados['cidade']);
			$contadorVo->setUF($dados['uf']);
			$contadorVo->setCEP($dados['cep']);
			$contadorVo->setEstado($dados['estado']);
			$contadorVo->setIdUser($dados['id_user']);
			$contadorVo->setTipoDoc($dados['tipoDoc']);
			$contadorVo->setDocumento($dados['documento']);
			$contadorVo->setDocumento2($dados['documento2']);
			$contadorVo->setUserId($dados['userId']);
			$contadorVo->setSex($dados['sexo']);
		}
		
		return $contadorVo;
	}
	
	// Pega os dados do contador pelo id.
	public function GetDataDadosContadorId($id){
		
		$contadorVo = '';
		
		$dadosContador = new DadosContador();
		
		// Pega os dados do contador.
		$dados = $dadosContador->pegaDadosContador($id);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$contadorVo = new DadosContratoContadorVo();
			
			// Passa os dados para objeto.
			$contadorVo->setId($dados['id']);
			$contadorVo->setNome($dados['nome']);
			$contadorVo->setCRC($dados['crc']);
			$contadorVo->setEndereco($dados['endereco']);
			$contadorVo->setBairro($dados['bairro']);
			$contadorVo->setCidade($dados['cidade']);
			$contadorVo->setUF($dados['uf']);
			$contadorVo->setCEP($dados['cep']);
			$contadorVo->setEstado($dados['estado']);
			$contadorVo->setIdUser($dados['id_user']);
			$contadorVo->setTipoDoc($dados['tipoDoc']);
			$contadorVo->setDocumento($dados['documento']);
			$contadorVo->setDocumento2($dados['documento2']);
			$contadorVo->setUserId($dados['userId']);
			$contadorVo->setSex($dados['sexo']);
		}
		
		return $contadorVo;
	}	
	
	// Pega os dados de cobranca e de login do usuário.
	public function GetDataDadosCliente($id_login){
		
		$clienteVo = '';
		
		$dadosCobranca = new DadosCobranca();
	
		// Pega os dados do usuário pelo Id.
		$dados = $dadosCobranca->pegaDadosLogin($id_login);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$clienteVo = new DadosContratoClienteVo();
			
			// Passa os dados para objeto.
			$clienteVo->setId($dados['id']);
			$clienteVo->setAssinante($dados['assinante']);
			$clienteVo->setPrefTelefone($dados['pref_telefone']);
			$clienteVo->setTelefone($dados['telefone']);	
			$clienteVo->setEndereco($dados['endereco']);
			$clienteVo->setNumero($dados['numero']);
			$clienteVo->setComplemento($dados['complemento']);
			$clienteVo->setBairro($dados['bairro']);
			$clienteVo->setCEP($dados['cep']);
			$clienteVo->setCidade($dados['cidade']);
			$clienteVo->setUF($dados['uf']);
			$clienteVo->setTipo($dados['tipo']);
			$clienteVo->setNome($dados['sacado']);
			$clienteVo->setEmail($dados['email']);
			$clienteVo->setDocumento($dados['documento']);
			$clienteVo->setFormaPagameto($dados['forma_pagameto']);
			
		}
		
		return $clienteVo;
	}	
}