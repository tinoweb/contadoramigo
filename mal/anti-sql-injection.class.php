<?php 

	// Classe responsavel por evitar sql injection atraves do GET, POST ou COOKIES
	class Anti_Sql_Injection{
		
		private function sql_injection($sql) {
			//Remove comandos SQL da string
		    $sql = preg_replace(sql_regcase("/(from|select|insert|delete|union|where| OR |OR | OR|drop table|show tables|--|\\\\)/"),"",$sql);
		    //Remove espacos do inicio o  fim da string
		    $sql = trim($sql);
		    //Remove tags html da string
		    $sql = strip_tags($sql);
		    //Adiciona \ (barra invertida) antes de ' ou "
		    $sql = addslashes($sql);
		    return $sql;
		}

		function execute(){
			$this->securityGET();
			$this->securityPOST();
			$this->securityCOOKIE();
		}
		private function securityGET(){
			foreach ($_GET as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do GET
				$_GET[$key] = $this->sql_injection($_GET[$key]);
			}
		}
		private function securityPOST(){
			foreach ($_POST as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do POST
				$_POST[$key] = $this->sql_injection($_POST[$key]);
			}
		}
		private function securityCOOKIE(){
			foreach ($_COOKIE as $key => $value) {
				//Aplica Anti SQL injection em todos os indices do COOKIE
				$_COOKIE[$key] = $this->sql_injection($_COOKIE[$key]);
			}
		}

	}

	//$security = new Anti_Sql_Injection();
	//$security->execute();

?>