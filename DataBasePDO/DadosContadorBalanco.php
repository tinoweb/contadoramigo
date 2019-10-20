<?php
/**
 * Classe para manipular os dados da tabela do Dados Contador Balanco.
 * Autor: Átano de Farias 
 * Data: 30/03/2017 
 */	
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../conect.PDO.php');
} else {
	require_once('conect.PDO.php');
}

class DadosContadorBalanco extends AccessDB {
	
	// Método para pegar os dados do contador
	public function PegaDadosContadorBalanco($userId) {
		
		// Define o select para pegar os dados de cobranca.
		$query = $this->PDO->prepare('SELECT * FROM `dados_contador_balanco` WHERE userId = '.$userId);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
		 
	}
}


