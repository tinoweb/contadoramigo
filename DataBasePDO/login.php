<?php
/**
 * Autor: Átano de Farias
 * Data: 15/02/2017 
 */	
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'admin') {
	require_once('../conect.PDO.php');
} elseif($requestURI[1] == 'contador') {
	require_once('../conect.PDO.php');
} else {
	require_once('conect.PDO.php');
}

class Login extends AccessDB {
	
	// Pega Todos usuarios.
	public function PegaTodosUsuarios($id) {
		
		$query = $this->PDO->prepare('SELECT email, senha FROM `login` WHERE id = :id ;');
		
		$params = array('id' => $id);
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
		 
	}
}


