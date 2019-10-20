<?php
include "conect.php";

$ID = $_POST["hidID"];
$FormaPagamento = $_POST["radFormaPagamento"];
$NumeroCartao = $_POST["txtNumeroCartao"];
$Codigo = $_POST["txtCodigo"];
$NomeTitular = $_POST["txtNomeTitular"];
$mes = substr($_POST["txtDataValidade"], 0, 2);
$ano = substr($_POST["txtDataValidade"], 3, 4);
$Datavalidade = date('Y-m-d',mktime(0,0,0,$mes,1,$ano));

$Datavalidade = explode("-", $Datavalidade);

$Datavalidade = $Datavalidade[0].$Datavalidade[1];

$Status = $_POST['hddStatus'];

mysql_query("UPDATE dados_cobranca SET plano = 'semestral' WHERE id = '$ID' AND plano = 'anual' ");

$sql = "SELECT * FROM dados_cobranca WHERE id='$ID'";
$resultado = mysql_query($sql);

$dados_cobranca=mysql_fetch_array($resultado);

if(isset($dados_cobranca['endereco']) &&  $dados_cobranca['endereco'] == ''){
	header('Location: minha_conta.php?error_dados_cobranca=vazio');
	exit();
}

//Atualizar dados em dados de cobrança.
$sql = "UPDATE dados_cobranca SET forma_pagameto='$FormaPagamento' WHERE id='$ID'";
$resultado = mysql_query($sql)
or die (mysql_error());

if ($FormaPagamento == "boleto") {
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $ID . ",'MINHA CONTA: USUARIO ALTEROU FORMA DE PAGAMENTO PARA BOLETO')");

	$consulta = mysql_query("DELETE FROM token_pagamento WHERE id_user = '".$ID."' ");
	$objeto=mysql_fetch_array($consulta);

}
else {
		
	#########################################################################################
	############### Trecho para criar o Token ###############################################
	
	require_once"class/bean.php";
	require_once"class/cielo.php";
	require_once'class/pagamento-cartao.php';
	

	#########################################################################################
	############### Trecho para criar o Token ###############################################

	function Normaliza($string) {
		$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
		'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
		'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
		'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
		return strtr($string, $table);
	}


	//Cria um objeto com os dados do cartao
	$cartao = new Dados_cartao();
	$NomeTitular = strtolower(Normaliza($NomeTitular));//Normaliza o nome, deixando sem acentos e em caixa baixa

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
			$sql = "UPDATE `token_pagamento` SET `token`='".$token."',`numero_cartao`='".$numero_cartao."',`nome_titular`='".$NomeTitular."',`bandeira`='".$FormaPagamento."',`data_criacao`='".date("Y-m-y H:m:s")."' WHERE id = '".$token_atual['id']."' ";
			$resultado = mysql_query($sql);
		else:
			//Se é o primeiro cadastro de token, insere os dados 
			$sql = "INSERT INTO `token_pagamento`(`id`, `id_user`, `token`, `numero_cartao`, `nome_titular`,`bandeira`, `data_criacao`) VALUES ( '','".$ID."','".$token."','".$numero_cartao."','".$NomeTitular."','".$FormaPagamento."','".date("Y-m-y H:m:s")."' )";
			$resultado = mysql_query($sql);
		endif;
	}
	else{

		//Informa o usuário que aconteceu algum erro durante a geração do token
		header('Location: minha_conta3.php?erro_cartao=token');
		
		die();
	}
	
	################ Fim do trecho para criar o token de pagamento ##########################
	#########################################################################################


}



// Em 08/11/2013 - CHECANDO SE O USUARIO QUE ALTEROU OS DADOS PARA PAGAMENTO POR CARTÃO TEM ALGUMA PENDENCIA
$sql_checa_pendencias_cartao = "SELECT * FROM historico_cobranca WHERE id = '" . $ID . "' AND status_pagamento IN ('vencido','não pago')";
$total_pendencias_cartao = mysql_num_rows(mysql_query($sql_checa_pendencias_cartao));
if($total_pendencias_cartao > 0){
	
	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $ID . ",'MINHA CONTA F. PAGTO: USUARIO CONTEM ".$total_pendencias_cartao." PENDENCIAS')");

	$aviso_pendencias = "?aviso=1";
	// aviso 1 = Você possui pendencias financeiras em sua conta
}







header('Location: minha_conta3.php' . $aviso_pendencias );
?>