<?php 

/**
 * Classe para realizar a conexao com o banco de dados.
 * Autor: Átano de Farias
 * Data: 17/02/2017 
 */
class AccessDB {
	
	protected $PDO;
	
	private $HostName;
	private 	$UserName;
	private 	$Password;
	private 	$DataBase; 
	
	public function __construct() {
		
		// Chama o metodo que verifica se a conexão é do contador amigo.
		$this->DadosBancoContadorAmigo();
		
		// Chama o metodo que verifica se a conexão é do ambiente de teste
		$this->DadosBancoTeste();
		
		// PEGA OS DADOS.
		$hostname = $this->HostName;
		$username = $this->UserName;
		$password = $this->Password;
		$database = $this->DataBase;
		
		// TRATA POSSIVEIS ERROS. 
		try {
			
			// REALIZA A CONEXÃO COM O BANCO DE DADOS.
			$this->PDO = new PDO("mysql:host=".$hostname.";dbname=".$database.";charset=utf8", $username, $password,
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}
		catch(PDOException $e){
			throw new Exception($e->getMessage());
		}
	} 
	
	// MÉTODO COM OS DADOS DE CONEXAO DO CONTADOR AMIGO.
	private function DadosBancoContadorAmigo(){
	
		if($_SERVER['SERVER_NAME'] == 'www.contadoramigo.com.br' || $_SERVER['SERVER_NAME'] == 'contadoramigo.com.br') {
			$this->HostName = '177.153.16.160';
			$this->UserName = 'contadoramigo';
			$this->Password = 'ttq231kz';
			$this->DataBase = 'contadoramigo';
		}
	}

	// MÉTODO COM OS DADOS DE CONEXAO DO AMBIENTE DE TESTE.
	private function DadosBancoTeste(){
		
		if($_SERVER['SERVER_NAME'] == 'www.ambientedeteste2.hospedagemdesites.ws' || $_SERVER['SERVER_NAME'] == 'ambientedeteste2.hospedagemdesites.ws') {
			$this->HostName = '177.153.16.160';
			$this->UserName = 'contadoramig15';
			$this->Password = 'ttq231kz1';
			$this->DataBase = 'contadoramig15';
		}
		
	}
}
