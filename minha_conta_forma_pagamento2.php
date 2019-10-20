<?php
include "conect.php";

$ID = $_POST["hidID"];
$FormaPagamento = $_POST["radFormaPagamento"];
$NumeroCartao = $_POST["txtNumeroCartao"];
$Codigo = $_POST["txtCodigo"];
$NomeTitular = $_POST["txtNomeTitular"];
$mes = substr($_POST["txtDataValidade"], 0, 2);
$ano = substr($_POST["txtDataValidade"], 3, 4);
$DataValidade = date('Y-m-d',mktime(0,0,0,$mes,1,$ano));
$Status = $_POST['hddStatus'];

//Atualizar dados em dados de cobrança.
$sql = "UPDATE dados_cobranca SET forma_pagameto='$FormaPagamento' WHERE id='$ID'";

$resultado = mysql_query($sql)
or die (mysql_error());


if ($FormaPagamento == "boleto") {

	
	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $ID . ",'MINHA CONTA: USUARIO ALTEROU FORMA DE PAGAMENTO PARA BOLETO')");

	
}

else {
	
	$sql = "SELECT * FROM dados_cobranca WHERE id='$ID' LIMIT 0, 1";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	$linha=mysql_fetch_array($resultado);

	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $ID . ",'MINHA CONTA F. PAGTO: USUARIO ALTEROU FORMA DE PAGAMENTO PARA CARTÃO')");
	
	$NumeroCartaoExistente = '************' . substr($linha["numero_cartao"],-4,4);
	
	if($NumeroCartao == $NumeroCartaoExistente) {
		$sql = "UPDATE dados_cobranca SET forma_pagameto='$FormaPagamento', codigo_seguranca='$Codigo', nome_titular='$NomeTitular', data_validade='$DataValidade' WHERE id='$ID'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	}
	
	else {
		$sql = "UPDATE dados_cobranca SET forma_pagameto='$FormaPagamento', numero_cartao='$NumeroCartao', codigo_seguranca='$Codigo', nome_titular='$NomeTitular', data_validade='$DataValidade' WHERE id='$ID'";
		$resultado = mysql_query($sql)
		or die (mysql_error());
	}
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

function gerarToken($dados){
	return "bloJA44iie2edtcJTEKqY3sZ+8yO9GcLhp4WdJaGmGM=";
}


//MAL Essa parte esta destinada a gerar o token, enviando os dados do cartão para a cielo e armazenando apenas o token, todo o código acima desse será refeito
$token = gerarToken($_POST);

$sql = "SELECT id FROM `token_pagamento` WHERE id_user = ".$ID." ";
		$resultado = mysql_query($sql)
		or die (mysql_error());

$token_atual = mysql_fetch_array($resultado);
if( isset( $token_atual['id'] ) && $token_atual['id'] != '' ):
	
	$sql = "UPDATE `token_pagamento` SET `token`='".$token."',`data_criacao`='".date("Y-m-y H:m:s")."' WHERE id = '".$token_atual['id']."' ";
	$resultado = mysql_query($sql)
	or die (mysql_error());
else:

	$sql = "INSERT INTO `token_pagamento`(`id`, `id_user`, `token`, `data_criacao`) VALUES ( '','".$ID."','".$token."','".date("Y-m-y H:m:s")."' )";
	$resultado = mysql_query($sql)
	or die (mysql_error());


endif;




header('Location: minha_conta.php' . $aviso_pendencias );
?>