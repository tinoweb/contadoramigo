<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 17/02/2017
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../DataBasePDO/DadosContadorBalanco.php');
	require_once('../Model/DadosContadorBalanco/vo/DadosContadorBalancoVo.php');
} else {
	require_once('DataBasePDO/DadosContadorBalanco.php');
	require_once('Model/DadosContadorBalanco/vo/DadosContadorBalancoVo.php');
}

class DadosContadorBalancoData {
	
	public function PegaDadosContadorBalancoData($userId){
	
		// Instância da class que pega os dados do contador
		$dados = new DadosContadorBalanco();
		
		// Pega os dados do contador de acordo com código do usuário.
		$contador = $dados->PegaDadosContadorBalanco($userId);
		
		// Verificar se existe dados. 
		if($contador){
			
			$vo = new DadosContadorBalancoVo();

			$vo->setId($contador['id']);
			$vo->setNome($contador['nome']);
			$vo->setCRC($contador['crc']);
			$vo->setEndereco($contador['endereco']);
			$vo->setCidade($contador['cidade']);
			$vo->setCEP($contador['cep']);
			$vo->setDocumento($contador['documento']);
			$vo->setDocumento2($contador['documento2']);
			$vo->setUF($contador['uf']);
			$vo->setUserId($contador['userId']);
			$vo->setBairro($contador['bairro']);
			$vo->setAtivo($contador['ativo']);
			$vo->setTipoDoc($contador['tipoDoc']);
			$vo->setSexo($contador['sexo']);
		}
		
		return $vo;
	}
}

