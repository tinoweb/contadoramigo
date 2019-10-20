<?php
include "../conect.php";
$ID = $_POST["hidIDUserHistorico"];
$IDHistorico = $_POST["hidIDHistorico"];

if (isset($_POST["txtDataCobranca"]) && $_POST["txtDataCobranca"] != ""){
	$dia = substr($_POST["txtDataCobranca"], 0, 2);
	$mes = substr($_POST["txtDataCobranca"], 3, 2);
	$ano = substr($_POST["txtDataCobranca"], 6, 4);
	$Data = date('Y-m-d',mktime(0,0,0,$mes,$dia,$ano));
	
	$sql = "UPDATE historico_cobranca SET data_pagamento='$Data' WHERE idHistorico='$IDHistorico'";

	$resultado = mysql_query($sql)
	or die (mysql_error());

}





if (isset($_POST["selStatusCobranca"]) && $_POST["selStatusCobranca"] != ""){
	$Status = $_POST["selStatusCobranca"];	
	
	// QUERY QUE ALTERA O STATUS - SE FOR A VENCER DEVE TIRAR O TIPO DE COBRANÇA
	$sqlSelect = "SELECT * FROM historico_cobranca WHERE idHistorico='$IDHistorico'";
	$sql = "UPDATE historico_cobranca SET status_pagamento='$Status' " . ($Status != "pago" ? ", tipo_cobranca = ''" : "") . " WHERE idHistorico='$IDHistorico'";
	$resultado = mysql_query($sql)
	or die (mysql_error());
	
	
	$linhaHistorico_Cobranca=mysql_fetch_array(mysql_query($sqlSelect));
	

	$sql3 = "SELECT * FROM dados_cobranca WHERE id='" . $ID . "' ORDER BY id ASC LIMIT 0,1";
	$resultado3 = mysql_query($sql3)
	or die (mysql_error());
	$linha3=mysql_fetch_array($resultado3);
	
	$sqlx = "UPDATE dados_cobranca SET forma_pagameto='boleto', numero_cartao=NULL, codigo_seguranca=NULL, nome_titular=NULL, data_validade=NULL WHERE id='. $ID . '";
	$resultadox = mysql_query($sqlx)
	or die (mysql_error());
	
	//if($linha3['forma_pagameto'] == "boleto"){


		// Checando se o novo status é pago 
		if(($Status == "pago") || ($Status == "perdoado") || ($Status == "pendente")){
	
			$sql2 = "SELECT * FROM historico_cobranca WHERE id='" . $ID . "' ORDER BY idHistorico DESC LIMIT 0,1";
			$resultado2 = mysql_query($sql2)
			or die (mysql_error());
			$linha2=mysql_fetch_array($resultado2);
			
			// Só vai inserir caso este seja o último status
			if($linha2['idHistorico'] == $IDHistorico){
				$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"]))+1,date('d',strtotime($linha2["data_pagamento"])),date('Y',strtotime($linha2["data_pagamento"])))));
				
				$sql = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento, tipo_cobranca) VALUES ('$ID', '$dataPagamento', 'a vencer', 'boleto')";
				$resultado = mysql_query($sql)
				or die (mysql_error());
			}


/* NOVO TRECHO */

/* EM 11/08/2015 - TIRAMOS ESSA FUNCIONALIDADE

			if($Status == "pago"){
//				$rsRelatorio = mysql_query("SELECT * FROM relatorio_cobranca WHERE id='" . $ID . "' AND MONTH(data) = '".date('m',strtotime($linhaHistorico_Cobranca["data_pagamento"]))."' AND YEAR(data) = '".date('Y',strtotime($linhaHistorico_Cobranca["data_pagamento"]))."'");
				

//				if(mysql_num_rows($rsRelatorio) > 0){

//					$relatorio=mysql_fetch_array($rsRelatorio);
//					mysql_query("UPDATE relatorio_cobranca SET resultado_acao = '1.2' WHERE idRelatorio = '" . $relatorio['idRelatorio'] . "'");

//					// LOG DE ACESSOS
//					mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO: " . $ID . " - ATUALIZANDO (VIA cadastro de cliente) resultado_acao PARA BOLETO COM SUCESSO (" . mysql_real_escape_string("UPDATE relatorio_cobranca SET resultado_acao = '1.2' WHERE idRelatorio = '" . $relatorio['idRelatorio'] . "'") . ")'");

//				}else{
					mysql_query("INSERT INTO relatorio_cobranca SET id='".$ID."',data='".date('Y-m-d')."',valor_pago=55.00,tipo_cobranca='boleto',data_pagamento='".date('Y-m-d')."', resultado_acao = '1.2'");

//					// LOG DE ACESSOS
					mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO: " . $ID . " - INSERINDO (VIA cadastro de cliente) NA resultado_acao UM REGISTRO BOLETO COM SUCESSO (" . mysql_real_escape_string("INSERT INTO relatorio_cobranca SET id='".$ID."',data='".date('Y-m-d')."',tipo_cobranca='boleto',data_pagamento='".date('Y-m-d')."', resultado_acao = '1.2'") . ")'");
//
//				}
				
			}
/* NOVO TRECHO */


		}
		
		
		
		// Se o status escolhido for "pendente" deve checar se há algum pendente mais recente para removê-lo
		if($Status == "a vencer"){
	
			$sql2 = "SELECT * FROM historico_cobranca WHERE id='" . $ID . "' ORDER BY idHistorico DESC LIMIT 0,1";
			$resultado2 = mysql_query($sql2)
			or die (mysql_error());
			$linha2=mysql_fetch_array($resultado2);
			
			// Só vai inserir caso este seja o último status
			if($linha2['idHistorico'] > $IDHistorico && $linha2['status_pagamento'] == "a vencer"){
				
				$sql = "DELETE FROM historico_cobranca WHERE idHistorico = " . $linha2['idHistorico'];
				$resultado = mysql_query($sql)
				or die (mysql_error());
			}
		}
	//}
	
}

header('Location: cliente_administrar.php?id=' . $ID );

echo $Data;
?>