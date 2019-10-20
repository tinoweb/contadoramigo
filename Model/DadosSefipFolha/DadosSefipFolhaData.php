<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 17/03/2017
 */

require_once('DataBaseMySQL/DadosSefipFolha.php');
require_once('Model/DadosSefipFolha/vo/DadosSefipFolhaVo.php');

class DadosSefipFolhaData {
	
	// Pega os dados do contador pelo codigo do usuário.
	public function GetDataDadosContador($contadorId){
		
		$dadosContador = new DadosContador();
		
		// Pega os dados do usuário pelo Id.
		$dados = $dadosContador->pegaDadosContador($contadorId);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$contadorVo = new DadosContadorVo();
		
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
			$contadorVo->setEmail($dados['email']);
		}
		
		return $contadorVo;
	}
}

