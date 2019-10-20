<?php
include "../conect.php";
$ID = $_POST["hidIDStatus"];
$Status = $_POST["selStatus"];
$movimentacao = '';

$sql3 = "SELECT * FROM login WHERE id='" . $ID . "' ORDER BY id ASC LIMIT 0,1";
		$resultado3 = mysql_query($sql3)
		or die (mysql_error());
		$linha3=mysql_fetch_array($resultado3);		


// tirar da lista de ativos do emkt
//error_reporting(E_ALL);
//require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
//// Esses valores podem ser obtidos na página de configurações do
//// Email Marketing

//$hostName = 'emailmkt7';
//$login 	  = 'contadoramigo1';
//$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
//$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

		
if($Status == 'demo'){
				
				//Atualizar status em login.
				$sql = "UPDATE login SET status='$Status' WHERE idUsuarioPai='$ID'";
				$resultado = mysql_query($sql)
				or die (mysql_error());
				
/*				//tirar da lista de ativos do emkt
				error_reporting(E_ALL);
				require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
				// Esses valores podem ser obtidos na página de configurações do
				// Email Marketing

				$hostName = 'emailmkt7';
				$login 	  = 'contadoramigo1';
				$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
				$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);
*/

//				$contatos = array();
//				array_push($contatos, array('email'=> $linha3["email"], 'nome'=>$linha3["assinante"]));

//				//Caso queira remover de listas, informar os IDs desta no 2o parametro.
//				$repositorio->desativar($contatos,array(57888,26588)); // ativos e inativos
////				$repositorio->desativar($contatos, array(26588)); // inativos
//				$repositorio->importar($contatos,array(38414)); // trial
}

else if($Status == 'ativo'){

				$sql_checa_pagamentos = "SELECT * FROM historico_cobranca WHERE id='" . $ID . "' AND status_pagamento IN ('vencido','não pago') ORDER BY idHistorico DESC LIMIT 0,1";

				$resultado_checa_status = mysql_query($sql_checa_pagamentos) or die (mysql_error());
				
				if($linha_checa_resultado = mysql_fetch_array($resultado_checa_status)){
					if(date('Ymd',(mktime(0,0,0,date('m',strtotime($linha_checa_resultado["data_pagamento"])),date('d',strtotime($linha_checa_resultado["data_pagamento"]))+5,date('Y',strtotime($linha_checa_resultado["data_pagamento"]))))) <= date('Ymd')){
						echo "Não foi possível alterar o status deste usuário pois possui um pagamento vencido há mais de 5 dias.";
						exit;
					}
				}

				if($ID != 1581 && $ID != 9){ 
					
					switch($linha3['status']){
						case 'cancelado':
							$movimentacao = 'recuperado';
						break;	
						case 'inativo':
							$movimentacao = 'recuperado';
						break;	
						case 'demo':
							$movimentacao = 'ativado';
						break;	
						case 'demoInativo':
							$movimentacao = 'ativado';
						break;	
					}
					
					// INSERE NA TABELA DE METRICAS
					mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $ID . ",'".$movimentacao."','" . date('Y-m-d') . "')");
				}

				//Atualizar status em login.
				$sql = "UPDATE login SET status='$Status' WHERE idUsuarioPai='$ID'";
				//$sql = "UPDATE login SET status='$Status' WHERE id='$ID'";
				$resultado = mysql_query($sql)
				or die (mysql_error());
				

/*
				//tirar da lista de ativos do emkt
				error_reporting(E_ALL);
				require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
				// Esses valores podem ser obtidos na página de configurações do
				// Email Marketing

				$hostName = 'emailmkt7';
				$login 	  = 'contadoramigo1';
				$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
				$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);
*/

//				$contatos = array();
	//			array_push($contatos, array('email'=> $linha3["email"], 'nome'=>$linha3["assinante"]));

//				//Caso queira remover de listas, informar os IDs desta no 2o parametro.							
//				$repositorio->desativar($contatos, array(26588,38414)); // inativos e trial
////				$repositorio->desativar($contatos,array(38414)); // trial
//				$repositorio->importar($contatos,array(57888)); // ativos	
}


else if($Status == 'inativo' or 'demoinativo'){

				if($ID != 1581 && $ID != 9){
					
					switch($linha3['status']){
						case 'ativo':
							$movimentacao = 'abandonou';
						break;	
						case 'demo':
							$movimentacao = 'não convertido';
						break;	
					}
					
					// INSERE NA TABELA DE METRICAS
					mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $ID . ",'".$movimentacao."','" . date('Y-m-d') . "')");
					
					// LOG DE ACESSOS
					mysql_query("insert into log_acessos (id_user, acao) VALUES (" . $ID . ",'DADOS USUARIO: STATUS DO USUARIO ALTERADO MANUALMENTE (DE: " . $linha3['status'] . " PARA: " . $Status . ")')");

				}
				
				//Atualizar status em login.
				$sql = "UPDATE login SET status='$Status' WHERE idUsuarioPai='$ID'";
				//$sql = "UPDATE login SET status='$Status' WHERE id='$ID'";
				$resultado = mysql_query($sql)
				or die (mysql_error());

/*
				//tirar da lista de ativos do emkt
				error_reporting(E_ALL);
				require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
				// Esses valores podem ser obtidos na página de configurações do
				// Email Marketing
		
				$hostName = 'emailmkt7';
				$login 	  = 'contadoramigo1';
				$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
				$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);
*/

//				$contatos = array();
//				array_push($contatos, array('email'=> $linha3["email"], 'nome'=>$linha3["assinante"]));

//				//Caso queira remover de listas, informar os IDs desta no 2o parametro.				
//				$repositorio->desativar($contatos,array(38414,57888)); // trial e ativos
////				$repositorio->desativar($contatos,array(57888)); // ativos
//				$repositorio->importar($contatos, array(26588)); // inativos
}




echo "Status alterado com sucesso!";


//header('Location: cliente_administrar.php?id=' . $ID );
?>