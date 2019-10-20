<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 03/05/2017 
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'admin') {
	require_once('../DataBasePDO/ServicoAvulso.php');
	require_once('../Model/ServicoAvulso/vo/ServicoAvulsoVo.php');
} elseif($requestURI[1] == 'contador') {
	require_once('../DataBasePDO/ServicoAvulso.php');
	require_once('../Model/ServicoAvulso/vo/ServicoAvulsoVo.php');
} else {
	require_once('/DataBasePDO/ServicoAvulso.php');
	require_once('/Model/ServicoAvulso/vo/ServicoAvulsoVo.php');
}

class ServicoAvulsoData {
	
	// Pega uma lista de serviços
	public function listaServicoAvulso($contadorId, $idUser = 0,  $data1 = '', $data2 = '', $assinante = '', $status = ''){

		$servico = new ServicoAvulso();
		//$login = new Login();
		
		// pega lista Cliente.
		$lista = $servico->ListaServicoAvulso($contadorId, $idUser, $data1, $data2, $assinante, $status);
	
		$out = false;
	
		if($lista) {
						
			foreach($lista as $val) {
				
				$vo = new ServicoAvulsoVo();
				$vo->setId($val['id']);
				$vo->setIdUser($val['id_user']);
				$vo->setUserName($val['assinante']);
				$vo->setContadorId($val['contadorId']);
				$vo->setServicoName($val['servico_name']);
				$vo->setData($val['data']);
				$vo->setValor($val['valor']);
				$vo->setStatus($val['status']);
				$vo->setLoginStatus($val['loginStatus']);
				$vo->setCobrancaContadorId($val['cobrancaContadorId']);
				$vo->setEmail($val['email']);
				$vo->setPrefTelefone($val['pref_telefone']);
				$vo->setTelefone($val['telefone']);
				$vo->setObservacao($val['observacao']);
				$vo->setStatusBola($val['status_bola']);

				$out[] = $vo;
			}
		}
	
		return $out;		
	}
}

