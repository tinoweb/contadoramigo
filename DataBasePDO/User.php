<?php
/**
 * Autor: Átano de Farias
 * Data: 15/02/2017 
 */	
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../conect.PDO.php');
} else {
	require_once('conect.PDO.php');
}

class User extends AccessDB {
	
	// Pega Todos usuarios.
	public function PegaTodosUsuarios() {
		
		$query = $this->PDO->prepare('SELECT * FROM `user` ORDER BY userId DESC;');
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
		 
	}
	
	// Pega o usuáraio informado pelo ID
	public function PegaUsuarios($userId) {
		
		$query = $this->PDO->prepare('SELECT * FROM `user` WHERE userId = :userId');
		
		$params = array( 'userId' => $userId );

		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	// Verifica se o usuario existe pelo Nome - Metodo auxiliar para realizar login
	public function PegaUsuarioNome($userName, $userPassword) {
	
		$query = $this->PDO->prepare(" SELECT * FROM `user` WHERE userName = :userName AND userPassword = :userPassword AND userActive = 'Y' AND (userType = 'C' OR userType = 'T' ) ");
		
		$params = array('userName' => $userName, 'userPassword' => $userPassword);
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
		 
	}
	
	// Verifica se o usuario existe pelo email - Metodo auxiliar para realizar login
	public function PegaUsuarioEmail($userEmail, $userPassword) {
		
		$query = $this->PDO->prepare(" SELECT * FROM `user` WHERE userEmail = :userEmail AND userPassword = :userPassword AND userActive = 'Y' AND (userType = 'C' OR userType = 'T' ) ");
		
		$params = array('userEmail' => $userEmail, 'userPassword' => $userPassword);
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
		 
	}		

	// Realiza a Inclusão de Dados
	public function InserUser($model) {
		
		$query = $this->PDO->prepare(" INSERT INTO `user` (userName, userPassword, userNoMaskKey, userSecurityKey, userEmail, userDate, userActive, userType) " 
			." VALUES (:userName, :userPassword, :userNoMaskKey, :userSecurityKey, :userEmail, NOW(), :userActive, :userType)");
		
		$params = array(
			'userName' => $model->getUserName, 
			'userPassword' => $model->getUserPassword, 
			'userNoMaskKey' => $model->getUserNoMaskKey, 
			'userSecurityKey' => $model->getUserSecurityKey, 
			'userEmail' => $model->getUserEmail,  
			'userActive' => $model->getUserActive,
		 	'userType' => $model->getUserType
		 );
		 
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $this->PDO->lastInsertId();
	}
	
	// Realiza a Alteração dos Dados 
	public function editUser($model) {
		
		$query = $this->PDO->prepare(
			 " UPDATE `user` "
			." SET userName = :userName, "
			."     userPassword = :userPassword, "
			."     userNoMaskKey, "
			."     userSecurityKey, "
			."     userEmail, "
			."     userDate, "
			."     userActive, "
		 	."     userType "
			." WHERE userId = :userId "
		);
		
		$params = array(
			'userId' => $model->getUserId,
			'userName' => $model->getUserName, 
			'userPassword' => $model->getUserPassword, 
			'userNoMaskKey' => $model->getUserNoMaskKey, 
			'userSecurityKey' => $model->getUserSecurityKey, 
			'userEmail' => $model->getUserEmail, 
			'userActive' => $model->getUserActive,
		 	'userType' => $model->getUserType
		 );
		 
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
	
	// Realiza a Exclusão dos Dados
	public function deleteUser($userId) {
		
		$query = $this->PDO->prepare('DELETE FROM `user` WHERE userId = :userId');
		
		$params = array( 'userId' => $userId );
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
}


