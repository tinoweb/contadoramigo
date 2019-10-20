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


$tipo 				= $_POST['rdbTipo'] == 'J' ? 'PJ' : 'PF';
$sacado				= $_POST['boleto_sacado'];
$documento 		= $tipo == 'PJ' ? $_POST['boleto_cnpj'] :  $_POST['boleto_cpf'];
$endereco 		= $_POST['boleto_endereco'];
$bairro 			= $_POST['boleto_bairro'];
$arrUF				= explode(';',$_POST['selEstado']);
$idUF					= $arrUF[0];
$siglaUF			= $arrUF[1];
$cidade 			= $_POST['txtCidade'];
$cep 					= $_POST['boleto_cep'];


//Atualizar dados em dados de cobrança.
$sql = "UPDATE dados_cobranca SET forma_pagameto='$FormaPagamento', sacado='$sacado', documento='$documento', endereco='$endereco', bairro='$bairro', uf='$siglaUF', cep='$cep', cidade='$cidade', tipo='$tipo' WHERE id='$ID'";

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



header('Location: minha_conta.php' . $aviso_pendencias );
?>