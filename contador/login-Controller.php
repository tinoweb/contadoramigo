<?php

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

require_once("../Model/Usuario/UsuarioData.php");
require_once("../Model/DadosContadorBalanco/DadosContadorBalancoData.php");

class Login {
    
	// Realiza o acesso via POST
    public function toaccess() {
        
		// Pega o usuario.
        $userName = (isset($_POST['user']) ? $_POST['user']: "");
        
		// Pega a senha.        
        $userPassword = (isset($_POST['passwd']) ? $_POST['passwd']: "");
		
		// Verifica se é para manter o usuario logado.
		$manter = (isset($_POST['manter']) ? $_POST['manter'] : 0);
		
		// Instancia a classe UsuarioData
        $usuarioData = new UsuarioData(); 
        
        $user = $usuarioData->ValidacaoDoUsusario($userName , $userPassword);

		// Vifica se os dados do usuário foram informados.
        if($user){
			
			// Pega os dados do contador.
			$data = new DadosContadorBalancoData();
			
			// Pega os dados do contador.	
			$contador = $data->PegaDadosContadorBalancoData($user->getUserId());
			
			// Verifica se o usuario esta ativado e se possui relacionamento com dados do contador.
			if($user->getUserActive() == 'Y' && $contador) {
	
				// Monata o json de autenticação.
				$array = json_encode(array('iduser'=>$user->getUserId(),'contadorId'=>$contador->getId(),'name'=>$contador->getNome(),'idsession'=>md5(date("YmdHi")),'manter'=>$manter));
	
				// Cria a seção
				$_SESSION['DadosContador'] = $array;
	
				// Cria o novo cookie para durar duas horas
				setcookie('DadosContador', $array, (time() + 3600*24*30));
			  
				Header( "Location: /contador");
	
			} else {
				$_SESSION['MESSAGE_USER'] = "Usuário invalido";
			}
			
        } else {
			$_SESSION['MESSAGE_USER'] = "Usuário ou senha e invalido";
        }
    }

    public function getout() {

        // Limpa a seção
        $_SESSION['DadosContador'] = "";
		
		setcookie('DadosContador', "");
		
        Header( "Location: /contador/login.php");
    }
  
    public function checkLogin() {

        // Variável para pegar os dados da cookie . 
        $getSC = array();

        // Define a variavel de saída como array. 
        $out = false;
		
		// Pega os dados do cookie;
		if(isset($_COOKIE['DadosContador'])) {
			$cookie = json_decode(str_replace("\\","",$_COOKIE['DadosContador']));
		}
		
		// Verifica se a sessão e o cookie foi definido.
		if(isset($_COOKIE['DadosContador']) && isset($_SESSION['DadosContador'])) {

			// Pega o cookie com os dados do usuário.	 
			$getSC['cookie'] = json_decode(str_replace("\\","",$_COOKIE['DadosContador']));

			// Pega a sessão com os dados do usuário.		
			$getSC['session'] = json_decode($_SESSION['DadosContador']);

			// verifica se o id e o mesmo.
			if(isset($getSC['cookie']->idsession) && isset($getSC['session']->idsession) && $getSC['cookie']->idsession == $getSC['session']->idsession) {
				$out = true;
			}
		} 
		// Verifica se o cookie se esta sendo mantido para realizar o login automatico.
		elseif( isset($_COOKIE['DadosContador']) && isset($cookie->manter) && $cookie->manter == 1 ){
			
			// Pega os dados do usuario.
			$iduser = $cookie->iduser;
			
			$manter = $cookie->manter;
			
			// Instancia a classe UsuarioData
        	$usuarioData = new UsuarioData(); 
        
			// Pega os dados do usuário.
        	$user = $usuarioData->pegaDadosUsusario($iduser);
			
			// verifica se existe dados.
			if($user) {
				
				// Realiza o acesso de login.
				$this->toAccessUser($user->getUserName(), $user->getUserNoMaskKey(), $manter);
			}
		}

        // Retorno
        return $out;
    }
	
	// Realiza o acesso via POST
    public function toAccessUser($userName, $userPassword, $manter) {
		
		// Instancia a classe UsuarioData
        $usuarioData = new UsuarioData(); 
        
        $user = $usuarioData->ValidacaoDoUsusario($userName , $userPassword);

		// Vifica se os dados do usuário foram informados.
        if($user){
			
			// Pega os dados do contador.
			$data = new DadosContadorBalancoData();
			
			// Pega os dados do contador.	
			$contador = $data->PegaDadosContadorBalancoData($user->getUserId());
			
			// Verifica se o usuario esta ativado e se possui relacionamento com dados do contador.
			if($user->getUserActive() == 'Y' && $contador) {
	
				// Monata o json de autenticação.
				$array = json_encode(array('iduser'=>$user->getUserId(),'contadorId'=>$contador->getId(),'name'=>$contador->getNome(),'idsession'=>md5(date("YmdHi")),'manter'=>$manter));
	
				// Cria a seção
				$_SESSION['DadosContador'] = $array;
	
				// Cria o novo cookie para durar duas horas
				setcookie('DadosContador', $array, (time() + 3600*24*30));
			  
				Header( "Location: /contador");
	
			} else {
				$_SESSION['MESSAGE_USER'] = "Usuário invalido";
			}
			
        } else {
			$_SESSION['MESSAGE_USER'] = "Usuário ou senha e invalido";
        }
    }

	// pega a sessão e o cookie
    public function getCookieSessionLogin() {

        // Define a variavel de saída como array. 
        $out = array();

        // Verifica se a sessão e o cookie foi definido.
        if(isset($_COOKIE['DadosContador']) && isset($_SESSION['DadosContador'])) {

            // Pega o cookie com os dados do usuário.	 
            $out['cookie'] = json_decode(str_replace("\\","",$_COOKIE['DadosContador']));

            // Pega a sessão com os dados do usuário.		
            $out['session'] = json_decode($_SESSION['DadosContador']);

            // Retorno
            return $out;
        }
    }
    
	
	// Pega usuario logado.
    public function getUserLogin() {

        $out = "";

        // Verifica se a sessão e o cookie foi definido.
        if(isset($_SESSION['DadosContador'])) {

            $DadosContador = json_decode($_SESSION['DadosContador']);

            $out = $DadosContador->name;
                    
            return $out;
        }
    } 
}
