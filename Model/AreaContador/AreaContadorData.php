<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 21/02/2017
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'admin') {
	require_once('../DataBasePDO/AreaContador.php');
	require_once('../Model/AreaContador/vo/AreaContadorClientesVo.php');
	require_once('../Model/AreaContador/vo/AreaContadorEmpresaVo.php');
} elseif($requestURI[1] == 'contador') {
	require_once('../DataBasePDO/AreaContador.php');
	require_once('../Model/AreaContador/vo/AreaContadorClientesVo.php');
	require_once('../Model/AreaContador/vo/AreaContadorEmpresaVo.php');
} else {
	require_once('DataBasePDO/AreaContador.php');
	require_once('Model/AreaContador/vo/AreaContadorClientesVo.php');
	require_once('Model/AreaContador/vo/AreaContadorEmpresaVo.php');
}

class AreaContadorData {
	
	// Pega os clientes da conta Premio.
	public function ListaClientesPremio($page = 1, $quantidade = 10, $filtro = false, $valor = false){
	
		$areaContador = new  AreaContador();
		
		// Realiza a o calculo para pegar a Posição.
		$posicao = ($quantidade * $page) - $quantidade;
	
		// pega lista Cliente.
		$lista = $areaContador->PegaClientePremioSemContador($posicao, $quantidade, $filtro, $valor);
	
		$out = false;
		
		// Pega a quantidade do cliente.
		$qunatidadeItens = $areaContador->pegaQuantidadeClinete($filtro, $valor);
		if($qunatidadeItens){
			$out['QuantidadeItemLista'] = $qunatidadeItens['numberRows'];
		}

		if($lista) {
			
			foreach($lista as $val) {
				
				$contadorVo = new AreaContadorClientesVo();

				$contadorVo->setClienteId($val['id']);
				$contadorVo->setNome($val['nome']); 
				$contadorVo->setAssinante($val['assinante']); 
				$contadorVo->setEmail($val['email']); 
				$contadorVo->setStatus($val['status']); 
				$contadorVo->setDataInclusao($val['data_inclusao']); 
				$contadorVo->setPrefTelefone($val['pref_telefone']);
				$contadorVo->setTelefone($val['telefone']);
				$contadorVo->setDocumento($val['documento']);
	
				// Chamada da quantidade de empresas
				$quantidadeEmpresa = $areaContador->PegaQuantidadeEmpresa($val['id']);			
				$contadorVo->setNumeroDeEmpresa($quantidadeEmpresa['numberRows']);
		
				$out[] = $contadorVo;
			}
		}
		return $out;
	}
	
	// Pega os cliente da carteira do contador.
	public function listaCarteiraClienteContador($contadorId, $page = 1, $quantidade = 10, $filtro = false, $valor = false, $status = '', $opcoes = '', $ordem = ''){
		
		
		$areaContador = new  AreaContador();
	
		// Realiza a o calculo para pegar a Posição.
		$posicao = ($quantidade * $page) - $quantidade;	
	
		// pega lista Cliente.
		$lista = $areaContador->PegaClientePremioDoContador($contadorId, $posicao, $quantidade, $filtro, $valor, $status, $opcoes, $ordem);
		
		//faz a verificação para definir a ordenação da tabela
		switch($ordem){
			case '1':
				
				$lista = $areaContador->PegaClientePremioDoContadorOrdemData($contadorId, $posicao, $quantidade, $filtro, $valor, $status, $opcoes);
				
				break;
				
			case '2':
				
				$lista = $areaContador->PegaClientePremioDoContador($contadorId, $posicao, $quantidade, $filtro, $valor, $status, $opcoes, $ordem);
				
				break;
				
			default:
				$lista = $areaContador->PegaClientePremioDoContador($contadorId, $posicao, $quantidade, $filtro, $valor, $status, $opcoes, $ordem);break;
		}
	
		$out = false;
		
		// Pega a quantidade do cliente.
		$qunatidadeItens = $areaContador->pegaQuantidadeDoContador($contadorId, $filtro, $valor, $status, $opcoes, $ordem);
		if($qunatidadeItens){
			$out['QuantidadeItemLista'] = $qunatidadeItens['numberRows'];
		}
	
		if($lista) {
				
			foreach($lista as $val) {
				
				$contadorVo = new AreaContadorClientesVo();
							
				$contrato = $areaContador->PegaDataContatoPremio($val['id']);
				$contadorVo->setDataContrato(date('d/m/Y', strtotime($contrato['data'])));
				
				$contadorVo->setClienteId($val['id']);
				$contadorVo->setNome($val['nome']); 
				$contadorVo->setAssinante($val['assinante']); 
				$contadorVo->setEmail($val['email']); 
				$contadorVo->setStatus($val['status']); 
				$contadorVo->setDataInclusao($val['data_inclusao']); 
				$contadorVo->setPrefTelefone($val['pref_telefone']);
				$contadorVo->setTelefone($val['telefone']);
				$contadorVo->setDocumento($val['documento']);
				$contadorVo->setSacado($val['sacado']);
				
				// Chamada da quantidade de empresas
				$quantidadeEmpresa = $areaContador->PegaQuantidadeEmpresa($val['id']);			
				$contadorVo->setNumeroDeEmpresa($quantidadeEmpresa['numberRows']);
		
				$out[] = $contadorVo;
			}
		}	
	
		return $out;
	}	
	
	// Pega as empresas do cliente.
	public function listaEmpresasCliente($clienteId, $contadorId){

		$areaContador = new AreaContador();
	
		// pega lista Cliente.
		$lista = $areaContador->PegaEmpresaCliente($clienteId, $contadorId);
	
		$out = false;
	
		if($lista) {
						
			foreach($lista as $val) {
		
				$contadorVo = new AreaContadorEmpresaVo();
				
				$contadorVo->setEmpresaId($val['id']);
				$contadorVo->setRazaoSocial($val['razao_social']); 
				$contadorVo->setDoc($val['cnpj']);
				$contadorVo->setNomeFantasia($val['nome_fantasia']); 
				$contadorVo->setAtiva($val['ativo']);
				$contadorVo->setDataDesativacao($val['data_desativacao']);
				$contadorVo->setDataInclusao($val['data_inclusao']);
		
				$out[] = $contadorVo;
			}
		}
	
		return $out;		
	}
}

