<?php 

/**
 * Classe para realizar a conexao com o banco de dados.
 * Autor: Átano de Farias
 * Data: 17/02/2017 
 */
class AccessDB {
	
	protected $PDO;
	
	public function __construct() {
		
		$hostname = '177.153.16.160';
		$username = 'contadoramigo';
		$password = 'ttq231kz';
		$database = 'contadoramigo';
		 
		try {
			$this->PDO = new PDO("mysql:host=".$hostname.";dbname=".$database.";charset=utf8", $username, $password,
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}
		catch(PDOException $e){
			throw new Exception($e->getMessage());
		}
	} 
}
