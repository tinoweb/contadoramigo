<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 17/03/2017
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../DataBaseMySQL/DadosContador.php');
	require_once('../Model/DadosContador/vo/DadosContadorVo.php');
} elseif($requestURI[1] == 'admin') {	
	require_once('../DataBaseMySQL/DadosContador.php');
	require_once('../Model/DadosContador/vo/DadosContadorVo.php');
} else {
	require_once('DataBaseMySQL/DadosContador.php');
	require_once('Model/DadosContador/vo/DadosContadorVo.php');
}

class DadosContadorData {
	
	// Pega os dados do contador.
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
	
	// Pega os dados do contador.
	public function GetNameContador($contadorId){
		
		$dadosContador = new DadosContador();
		
		// Pega os dados do usuário pelo Id.
		$dados = $dadosContador->pegaNomeContador($contadorId);
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$contadorVo = new DadosContadorVo();
		
			// Passa os dados para objeto.
			$contadorVo->setNome($dados['nome']);
		}
		
		return $contadorVo;
	}
		
	// Pega a lista com os dados do contador.
	public function GetListDadosContador() {
		
		$out = false;
		
		$dadosContador = new DadosContador();
		
		// Pega os dados do usuário pelo Id.
		$dados = $dadosContador->pegaListaComDadosContador();
	
		// Verifica se houve dados de retorno.
		if($dados){
			
			foreach($dados as $val) {
			
				// Instância a classe de requisição de dados do banco. 
				$contadorVo = new DadosContadorVo();
			
				// Passa os dados para objeto.
				$contadorVo->setId($val['id']);
				$contadorVo->setNome($val['nome']);
				$contadorVo->setCRC($val['crc']);
				$contadorVo->setEndereco($val['endereco']);
				$contadorVo->setBairro($val['bairro']);
				$contadorVo->setCidade($val['cidade']);
				$contadorVo->setUF($val['uf']);
				$contadorVo->setCEP($val['cep']);
				$contadorVo->setEstado($val['estado']);
				$contadorVo->setIdUser($val['id_user']);
				$contadorVo->setTipoDoc($val['tipoDoc']);
				$contadorVo->setDocumento($val['documento']);
				$contadorVo->setDocumento2($val['documento2']);
				$contadorVo->setUserId($val['userId']);
				$contadorVo->setSex($val['sexo']);
				$contadorVo->setEmail($val['email']);
				
				$out[] = $contadorVo;
			
			}
		}
		
		return $out;
	}
	
	// Pega os dados do contador de acordo com o código do cliente.
	public function PegaContadorDeAcordoCliente($idUser) {
		
		$contadorVo = false;
		
		$dadosContador = new DadosContador();
		
		// Pega os dados do usuário pelo Id.
		$dados = $dadosContador->PegaContadorDeAcordoClienteId($idUser);
		
		// Verifica se houve dados de retorno.
		if($dados){

			// Instância a classe de requisição de dados do banco. 
			$contadorVo = new DadosContadorVo();

			// Passa os dados para objeto.
			$contadorVo->setId($dados['id']);
			$contadorVo->setNome($dados['nome']);
			$contadorVo->setRazaoSocial($dados['razao_social']);
			$contadorVo->setCRC($dados['crc']);
			$contadorVo->setEndereco($dados['endereco']);
			$contadorVo->setBairro($dados['bairro']);
			$contadorVo->setCidade($dados['cidade']);
			$contadorVo->setUF($dados['uf']);
			$contadorVo->setCEP($dados['cep']);
			$contadorVo->setEstado($dados['estado']);
			$contadorVo->setTipoDoc($dados['tipoDoc']);
			$contadorVo->setDocumento($dados['documento']);
			$contadorVo->setDocumento2($dados['documento2']);
			$contadorVo->setUserId($dados['userId']);
			$contadorVo->setSex($dados['sexo']);
			$contadorVo->setEmail($dados['userEmail']);
			$contadorVo->setPrefTelefone($dados['pref_telefone']);
			$contadorVo->setTelefone($dados['telefone']);
		}
		
		return $contadorVo;
	}
}

