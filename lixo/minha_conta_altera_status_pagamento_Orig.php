<?php 
// SESSION
session_start();
//ini_set('session.cookie_secure',true);

// CONEXAO
include 'conect.php';



$sql_meus_dados = "
				SELECT 
					l.id
					, l.status
					, l.email
					, l.assinante
				FROM 
					login l
				WHERE 
					l.id='" . $_SESSION["id_userSecaoMultiplo"] . "'
					AND l.id = l.idUsuarioPai
				";

$resultado_meus_dados = mysql_query($sql_meus_dados)
or die (mysql_error());
$linha_meus_dados = mysql_fetch_array($resultado_meus_dados);



//09/06//2016
//INICIO

$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$_SESSION["id_userSecaoMultiplo"]."' order by data_pagamento DESC ");

//Insere todos os meses como perdoados, no intervalo em que o cliente esteve cancelado
$objeto=mysql_fetch_array($consulta);

$data_nova =  date($objeto['data_pagamento'], (mktime(0, 0, 0, date('m') + 1, date('d') , date('Y'))));

while ( $data_nova < date("Y-m-d") ) {
	
	$data_nova = explode('-', $data_nova);
	$dia = $data_nova[2];
	$mes = $data_nova[1];
	$ano = $data_nova[0];
	$data_nova =  date("Y-m-d", (mktime(0, 0, 0, $mes + 1, $dia , $ano)));
	
	if( $data_nova < date("Y-m-d") )
		mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '" . $data_nova . "', 'perdoado')");
}
//FIM


// LOG DE ACESSOS
mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: BOLETO GERADO ".$dataPagamento."')");

if($linha_meus_dados['status'] == 'cancelado'){
	
		// EM 30/01/2014 - CANCELADO deve virar INATIVO...
		//mysql_query("UPDATE login SET status = 'inativo' WHERE id='" . $linha_meus_dados["id"] . "'");
		mysql_query("UPDATE login SET status = 'inativo' WHERE idUsuarioPai='" . $linha_meus_dados["id"] . "'");
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO PASSOU A SER inativo')");

}

// EM 08/11/2013 - DEMO INATIVO deve perdoar os pagamentos anteriores ...
mysql_query("UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento = 'não pago'");
// EM 08/11/2013 - ... e gerar um novo para a data corrente
// CHECANDO SE JÁ EXISTE HISTÓRICO VENCIDO
$sqlChecaVencido = "SELECT * FROM historico_cobranca WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento='vencido' LIMIT 0,1";
$resultadoChecaVencido = mysql_query($sqlChecaVencido)
or die (mysql_error());					
					
		
$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m'),date('d'),date('Y'))));

if(mysql_num_rows($resultadoChecaVencido) <= 0){
	// INSERE UM NOVO VENCIDO NA DATA DE HOJE PARA EFETUAR A COBRANÇA
	mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha_meus_dados['id'] . "', '" . $dataPagamento . "', 'vencido')");

	// LOG DE ACESSOS
	mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: NOVO PAGAMENTO ".$dataPagamento." GERADO')");

}
/*
echo html_entity_decode($_GET['url']);
exit;
*/
if( isset( $_GET['url'] ) ):
	
	header('location: ' . html_entity_decode($_GET['url']));

endif;

?>