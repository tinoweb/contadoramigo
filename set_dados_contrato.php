<?
include 'session.php';
include 'conect.php' ;
/*
	echo "## QUERY DELETE: delete from dados_alteracao_contrato WHERE id = " . $_SESSION["id_userSecao"] . "<BR>";
	
	foreach($_REQUEST as $campo => $valor){
		echo "#_# QUERY INSERT: insert into dados_alteracao_contrato (id, $campo) VALUES (" . $_SESSION["id_userSecao"] . ",'" . $valor . "')<BR>";
		if($campo == 'txtOrgaoExpedidorTest2'){
			break;
		}
	}
*/
mysql_query("delete from dados_alteracao_contrato WHERE id = " . $_SESSION["id_userSecao"])
or die (mysql_error());

foreach($_REQUEST as $campo => $valor){
	if(strlen($valor) > 0){
		if(strpos($campo,'txtDistribuicaoAtualSocio') !== false || strpos($campo,'totalCotasAtual') !== false){
			$valor = str_replace('.','',$valor);
/*			echo "insert into dados_alteracao_contrato (id, nome_campo, valor_campo) VALUES (" . $_SESSION["id_userSecao"] . ",'" . $campo . "','" . $valor . "')";
			exit;*/
		}
		if(strpos($campo,'totalReaisAtual') !== false){
			$valor = str_replace('.',',',$valor);
/*			echo "insert into dados_alteracao_contrato (id, nome_campo, valor_campo) VALUES (" . $_SESSION["id_userSecao"] . ",'" . $campo . "','" . $valor . "')";
			exit;*/
		}
		mysql_query("insert into dados_alteracao_contrato (id, nome_campo, valor_campo) VALUES (" . $_SESSION["id_userSecao"] . ",'" . $campo . "','" . $valor . "')") or die (mysql_error());
	}
	
	if($campo == 'txtOrgaoExpedidorTest2'){
		break;
	}
}

echo 'ok';
exit;
?>