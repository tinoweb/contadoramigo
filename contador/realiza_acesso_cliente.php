<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// inicia a sessão. 
session_start(); 

require_once('../DataBasePDO/login.php');

class GeraDadosDeAcesso {
	
	private $email;
	private $senha;
	
	public function __construct($id) {
	
		// pega os dados do usuario. 
		$this->getDataUser($id);
	
		// Gera a Sessão e o Cookie.
		$this->geraDadosAcesso($this->email, $this->senha);
	}
	
	private function getDataUser($id){
	
		$login = new Login();
	
		$dadosLogin = $login->PegaTodosUsuarios($id);
	
		$this->email = $dadosLogin['email'];
		$this->senha = md5($dadosLogin['senha']);
	}
	
	private function geraDadosAcesso($email, $passwd) {
		setcookie('contadoramigoHTTPS','', time()-(120*120*24*30));
		setcookie('contadoramigoHTTPS','', time()-(120*120*24*30), '/', str_replace('www.', '', $_SERVER['SERVER_NAME']), 0);
		unset($_COOKIE['contadoramigoHTTPS']);
	
		setcookie('contadoramigoADMIN', $email . " " . $passwd, time()+(120*120*24*30), '/', str_replace('www.', '', $_SERVER['SERVER_NAME']), 0);
		
		// Recria o cookie de acesso do contador.
		if(isset($_SESSION['DadosContador'])) {
			
			$DadosContador = $_SESSION['DadosContador'];
			
			// Cria o novo cookie para durar duas horas
			setcookie('DadosContador', $DadosContador, (time() + (2 * 3600)));
		}
	 
		//realiza o redirecionamento para a tela de login. 
		header("location: ../auto_login.php?admin");
	}	
}

// Verificar se e valido a conexao.
if(isset($_POST['clienteid']) && isset($_POST['sessionId'])) {
	
	$session = json_decode($_SESSION['DadosContador']);
	
	if($session->idsession == $_POST['sessionId']) {
		$acesso = new GeraDadosDeAcesso($_POST['clienteid']);
	} 
}