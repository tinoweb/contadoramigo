<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// incluir o arquivo que manipula os dados do arquivo livro caixa. 
require_once "DataBaseMySQL/UserLivroCaixa.php";

// Inclui o arquivo que manipula os dados do login.
require_once "DataBaseMySQL/Login.php";

// Verifica se o id foi informado.
if(isset($_SESSION['id_userSecao']) && !empty($_SESSION['id_userSecao'])) {
	
	$dados = new Login();

	$login = $dados->pegaDadosusuarioPai($_SESSION['id_userSecao']);
	
	if(isset($login['status']) && $login['status'] == 'ativo' || $login['status'] == 'demo'){
		
		// Páginas para criar tabela. 
		$paginas[] = '/pro_labore.php';
		$paginas[] = '/distribuicao_de_lucros.php';
		$paginas[] = '/pagamento_pj.php';
		$paginas[] = '/pagamento_autonomos.php';
		$paginas[] = '/estagiarios.php';
		
		$paginas[] = '/livros_caixa_movimentacao.php';
		$paginas[] = '/livros_caixa_graficos.php';
		$paginas[] = '/livros_caixa_fluxo.php';
	
		// Verifica a página para poder criar  
		if(in_array( $_SERVER['SCRIPT_URL'], $paginas)){
	
			// Instancia a classe que manipula os dados do livro caixa.	
			$userLivroCaixa = new UserLivroCaixa();
	
			// Verifica ser a tabela do user livro caixa foi criada.		
			$userLivroCaixa->CreateTableLivroCaixa($_SESSION['id_userSecao']);
		}
	}
}