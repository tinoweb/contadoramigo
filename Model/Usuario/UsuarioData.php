<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 17/02/2017
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../DataBasePDO/User.php');
	require_once('../Model/Usuario/vo/UsuarioVo.php');
} else {
	require_once('DataBasePDO/User.php');
	require_once('Model/Usuario/vo/UsuarioVo.php');
}

class UsuarioData {
	
	// Realiza a verificação para validaçao do usuario.
	public function ValidacaoDoUsusario($loginName, $password){
	
		$user = new User();
		
		$usuarioVo = '';
		$dataUser = '';
	
		// Verifica login por Nome
		$dataUser = $user->PegaUsuarioNome($loginName, md5($password));
	
		// Relaza o retorno caso encontre o usuario.
		if(!$dataUser) {
			// Verifica login por Email.
			$dataUser = $user->PegaUsuarioEmail($loginName, md5($password));
		}
	
		// Verificar se existe dados.
		if($dataUser){
		
			$usuarioVo = new UsuarioVo();
			$usuarioVo->setUserId($dataUser['userId']);
			$usuarioVo->setUserName($dataUser['userName']);
			$usuarioVo->setUserEmail($dataUser['userEmail']); 
			$usuarioVo->setUserActive($dataUser['userActive']);
			$usuarioVo->setUserType($dataUser['userType']);

		}
		
		return $usuarioVo;
	}
	
	// Realiza a verificação para validaçao do usuario.
	public function pegaDadosUsusario($idUser){
	
		$user = new User();

		// Verifica login por Nome
		$dataUser = $user->PegaUsuarios($idUser);
	
		// Verificar se existe dados. 
		if($dataUser){
		
			$usuarioVo = new UsuarioVo();
			$usuarioVo->setUserId($dataUser['userId']);
			$usuarioVo->setUserName($dataUser['userName']);
			$usuarioVo->setUserNoMaskKey($dataUser['userNoMaskKey']); 
		}
		
		return $usuarioVo;
	}	
	
}

