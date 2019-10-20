<?php 
// SESSION
session_start();
//ini_set('session.cookie_secure',true);

// CONEXAO
include 'conect.php';

// Cria a table livro caixa do usuário.
$sql = "CREATE TABLE IF NOT EXISTS user_" . $_SESSION["id_userSecaoMultiplo"] . "_livro_caixa ( "
		."	id int(30) NOT NULL AUTO_INCREMENT UNIQUE, "
		."	data date NOT NULL, "
		."	entrada decimal(50,2) NOT NULL, "
		."	saida decimal(50,2) NOT NULL, "
		."	documento_numero varchar(20) NOT NULL, " 
		."	descricao varchar(200) NOT NULL,  "
		."	categoria varchar(125),  "
		."	PRIMARY KEY (id) "
		." )";
$resultado = mysql_query($sql) 
or die (mysql_error());

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

if($linha_meus_dados['status'] == 'cancelado' || $linha_meus_dados['status'] == 'demoInativo'){
	$_SESSION['AtivacaoContaDesativada'] = 'AtivacaoContaDesativada'; 
}

if($linha_meus_dados['status'] == 'cancelado'){
	
		// EM 30/01/2014 - CANCELADO deve virar INATIVO...
		//mysql_query("UPDATE login SET status = 'inativo' WHERE id='" . $linha_meus_dados["id"] . "'");
		mysql_query("UPDATE login SET status = 'inativo' WHERE idUsuarioPai='" . $linha_meus_dados["id"] . "'");
		// LOG DE ACESSOS
		mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $linha_meus_dados["id"] . ",'MINHA CONTA: USUARIO PASSOU A SER inativo')");

}

// VERIFICA SE O CLIENTE E DEMO OU DEMO INATIVO.
if($linha_meus_dados['status'] == 'demoInativo' || $linha_meus_dados['status'] == 'demo'){
	
	// VERIFICA SE A DATA DE PAGAMENTO DO HISTORICO DE COBRANCA E MENOR QUE A DATA ATUAL.
	$qry1 = mysql_query("SELECT * FROM historico_cobranca WHERE id= '".$linha_meus_dados["id"]."' AND status_pagamento='vencido' AND data_pagamento < DATE(NOW()) ORDER BY idHistorico DESC LIMIT 0,1");
	
	// DEFINE O STATUS VENCIDO COMO PERDOADO ASSIM POSSIBILITANDO GERAR UM NOVO BOLETO. 
	if(mysql_num_rows($qry1) > 0) {
		mysql_query(" UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id='" . $linha_meus_dados['id'] . "' AND status_pagamento='vencido' ");
	}
	
} 

// EM 08/11/2013 - DEMO INATIVO deve perdoar os pagamentos anteriores ...
mysql_query("UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento = 'não pago'");

// EM 08/11/2013 - ... e gerar um novo para a data corrente
// CHECANDO SE JÁ EXISTE HISTÓRICO VENCIDO
$sqlChecaVencido = "SELECT * FROM historico_cobranca WHERE id='" . $linha_meus_dados["id"] . "' AND status_pagamento='vencido' LIMIT 0,1";
$resultadoChecaVencido = mysql_query($sqlChecaVencido)
or die (mysql_error());					
					
		
$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m'),date('d'),date('Y'))));

if(mysql_num_rows(  ) <= 0) {

	// Exclui o vencimento que estar a vencer para poder normalizar quando for inserir o vencido.
	mysql_query("DELETE FROM `historico_cobranca` WHERE id=".$linha_meus_dados['id']." AND status_pagamento='A Vencer'");	
	
	// Perdoa o vencimento
	mysql_query(" UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id='" . $linha_meus_dados['id'] . "' AND status_pagamento='vencido' ");
	
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