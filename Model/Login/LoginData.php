<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 14/03/2017
 */
require_once('DataBaseMySQL/Login.php');
require_once('Model/Login/vo/LoginVo.php');

class LoginData {
	
	// Realiza a verificação para validaçao do usuario.
	public function GetDataLogin($id_login){
		
		$login = new Login();
		
		// Pega os dados do usuário pelo Id.
		$dados = $login->pegaDadosUsuario($id_login);

		// Verifica se houve dados de retorno.
		if($dados){
			
			// Instância a classe de requisição de dados do banco. 
			$loginVo = new LoginVo();
			
			// Passa os dados para objeto.
			$loginVo->setId($dados['id']);
			$loginVo->setNome($dados['nome']);
			$loginVo->setAssinante($dados['assinante']);
			$loginVo->setEmail($dados['email']);
			$loginVo->setSenha($dados['senha']);
			$loginVo->setStatus($dados['status']);
			$loginVo->setInfoPreliminar($dados['info_preliminar']);
			$loginVo->setDataInclusao($dados['data_inclusao']);
			$loginVo->setIdPlano($dados['id_plano']);
			$loginVo->setSessionID($dados['sessionID']);
			$loginVo->setIdUsuarioPai($dados['idUsuarioPai']);
		}	
		
		return $loginVo;
	}
}

