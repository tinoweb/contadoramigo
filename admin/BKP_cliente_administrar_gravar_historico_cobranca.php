<?php
include "../conect.php";
$ID = $_POST["hidID"];
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
	
	$sql = "UPDATE historico_cobranca SET status_pagamento='$Status' WHERE idHistorico='$IDHistorico'";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	$sql3 = "SELECT * FROM dados_cobranca WHERE id='" . $ID . "' ORDER BY id ASC LIMIT 0,1";
	$resultado3 = mysql_query($sql3)
	or die (mysql_error());
	$linha3=mysql_fetch_array($resultado3);
	
	$sqlx = "UPDATE dados_cobranca SET forma_pagameto='Boleto', numero_cartao=NULL, codigo_seguranca=NULL, nome_titular=NULL, data_validade=NULL WHERE id='. $ID . '";
	$resultadox = mysql_query($sqlx)
	or die (mysql_error());
	
	//if($linha3['forma_pagameto'] == "boleto"){


		// Checando se o novo status é pago 
		if(($Status == "pago") || ($Status == "perdoado")){
	
			$sql2 = "SELECT * FROM historico_cobranca WHERE id='" . $ID . "' ORDER BY idHistorico DESC LIMIT 0,1";
			$resultado2 = mysql_query($sql2)
			or die (mysql_error());
			$linha2=mysql_fetch_array($resultado2);
				
			// INSERINDO CASO NÃO EXISTA UM REGISTRO NO RELATORIO DE COBRANCA
			$sqlRel = "SELECT * FROM relatorio_cobranca WHERE id = '" . $ID . "' AND data = '" . $linha2["data_pagamento"] . "' AND resultado_acao IN ('1.2','2.1') ORDER BY idRelatorio DESC LIMIT 0,1";
			echo $sqlRel;
			
			// Só vai inserir caso este seja o último status
			if($linha2['idHistorico'] == $IDHistorico){
				$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha2["data_pagamento"]))+1,date('d',strtotime($linha2["data_pagamento"])),date('Y',strtotime($linha2["data_pagamento"])))));
				
				$sql = "INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento, tipo_cobranca) VALUES ('$ID', '$dataPagamento', 'pendente', 'boleto')";
				$resultado = mysql_query($sql)
				or die (mysql_error());
			}
		}
		
		
		
		// Se o status escolhido for "pendente" deve checar se há algum pendente mais recente para removê-lo
		if($Status == "pendente"){
	
			$sql2 = "SELECT * FROM historico_cobranca WHERE id='" . $ID . "' ORDER BY idHistorico DESC LIMIT 0,1";
			$resultado2 = mysql_query($sql2)
			or die (mysql_error());
			$linha2=mysql_fetch_array($resultado2);
			
			// Só vai inserir caso este seja o último status
			if($linha2['idHistorico'] > $IDHistorico && $linha2['status_pagamento'] == "pendente"){
				
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