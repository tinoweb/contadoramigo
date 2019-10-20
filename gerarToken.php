<?php 
	
	include 'conect.php';
	require_once "class/bean.php";
	require_once "class/cielo.php";
	require_once 'class/pagamento-cartao.php';


	$consulta = mysql_query("SELECT * FROM dados_cobranca where forma_pagameto != 'boleto' AND forma_pagameto != '' AND numero_cartao != '' ");
	while( $objeto=mysql_fetch_array($consulta) ){
		
		$ID = $objeto['id'];
		$NomeTitular = $objeto['nome_titular'];
		$NumeroCartao = $objeto['numero_cartao'];
		$aux = explode('-', $objeto['data_validade']);
		$Datavalidade = $aux[0].$aux[1];
		$Codigo = $objeto['setCodigo_seguranca'];
		$FormaPagamento = $objeto['forma_pagameto'];

		//Cria um objeto com os dados do cartao
		$cartao = new Dados_cartao();

		$cartao->setNome($NomeTitular);//nome do assinante como está no cartão
		$cartao->setNumero_cartao($NumeroCartao);//Número atual é o numero do ambiente de teste
		$cartao->setValidade($Datavalidade);//Validade no formato AAAAMM
		$cartao->setCodigo_seguranca($Codigo);//Código de segurança,
		// $cartao->setValor("59");//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
		// $cartao->setBandeira("visa");//Seta a bandeira 

		//Cria um objeto para o pagamento
		$pagamento = new Pagamento_cartao();
		//Gera o token de pagamento
		$pagamento->gerarToken($cartao);
		
		//MAL Essa parte esta destinada a gerar o token, enviando os dados do cartão para a cielo e armazenando apenas o token, todo o código acima desse será refeito
		$token = $pagamento->getToken();


		//Se não retornar o token, ocorreu um erro, então informa o usuário no else
		if( $token != '' ){
			
			//Pega o nnumero do cartao, que é retornado de forma truncada
			$numero_cartao = $pagamento->getNumeroTruncado();

			//Verifica se existe um token criado para o usuário
			$sql = "SELECT id FROM `token_pagamento` WHERE id_user = ".$ID." ";
			$resultado = mysql_query($sql);
			$token_atual = mysql_fetch_array($resultado);
			//Caso exista um token para o usuário, faz updat do novo token e atualiza os dados referentes
			if( isset( $token_atual['id'] ) && $token_atual['id'] != '' ):
				$sql = "UPDATE `token_pagamento` SET `token`='".$token."',`numero_cartao`='".$numero_cartao."',`nome_titular`='".strtolower($NomeTitular)."',`bandeira`='".$FormaPagamento."',`data_criacao`='".date("Y-m-y H:m:s")."' WHERE id = '".$token_atual['id']."' ";
				$resultado = mysql_query($sql);
			else:
				//Se é o primeiro cadastro de token, insere os dados 
				$sql = "INSERT INTO `token_pagamento`(`id`, `id_user`, `token`, `numero_cartao`, `nome_titular`,`bandeira`, `data_criacao`) VALUES ( '','".$ID."','".$token."','".$numero_cartao."','".strtolower($NomeTitular)."','".$FormaPagamento."','".date("Y-m-y H:m:s")."' )";
				$resultado = mysql_query($sql);
			endif;
		}
		else{
			echo $ID.'<br>';
		}
	}


?>