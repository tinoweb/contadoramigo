<?php 
	
	class Mobile{
		
		function execute(){
			$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
			$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
			$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
			$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
			$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
			$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
			$symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
			
			if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true): /*Se este dispositivo for portátil, faça/escreva o seguinte */ 
				// $pagina_atual = "index.php";
				$pagina_atual = $_SERVER['REQUEST_URI'];
				$pagina_atual = explode('/', $pagina_atual);
				$pagina_atual = $pagina_atual[1];
				$pagina = explode( '.php' , $pagina_atual );
				$pagina = $pagina[0];
				if(strlen($pagina < 3))
					return 'index_mobile.php';
				else
					return $pagina.'_mobile.php';
			else:
				return 0;
			endif; 

		}
			
	}

?>