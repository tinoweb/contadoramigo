<?php
include "../conect.php";
$idPagto = $_POST["id_pagto"];
$status = ($_POST["valor"]);

//Atualizar dados em dados da empresa.
$sql = "UPDATE relatorio_cobranca SET resultado_acao = '2.1', emissao_NF = CASE WHEN numero_NF = '0' OR numero_NF = '' THEN '0' ELSE emissao_NF END WHERE idRelatorio=" . $idPagto;
$resultado = mysql_query($sql) or die (mysql_error());

/* NOVO TRECHO */
$relatorio = mysql_fetch_array(mysql_query("SELECT * FROM relatorio_cobranca WHERE idRelatorio=" . $idPagto));

if($linha = mysql_fetch_array(mysql_query("SELECT * FROM historico_cobranca WHERE id = '" . $relatorio['id'] . "' AND MONTH(data_pagamento) = '" . date('m',strtotime($relatorio['data'])) . "' AND YEAR(data_pagamento) = '" . date('Y',strtotime($relatorio['data'])) . "'"))){

	// LOG DE ACESSOS
	mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $relatorio['id'] . ": FOI ALTERADO O STATUS PARA \"cartão com sucesso\" VIA PÁGINA DE COBRANÇA')");
	mysql_query("UPDATE historico_cobranca SET status_pagamento='pago' WHERE id='" . $relatorio['id'] . "' AND data_pagamento = '" . $relatorio['data'] . "'");
	
}else{
	// LOG DE ACESSOS
	mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $relatorio['id'] . ": ERRO AO ALTERAR STATUS PARA \"cartão com sucesso\" - ERRO AO LOCALIZAR O historico de cobrança PARA ESTE USUARIO NA DATA " . $relatorio['data'] . "')");
}
/* NOVO TRECHO */


echo $status;
?>