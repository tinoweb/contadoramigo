<?php
include "../conect.php";
$ID = $_POST["hidID"];
$Status = $_POST["selStatus"];

$sql3 = "SELECT * FROM login WHERE id='" . $ID . "' ORDER BY id ASC LIMIT 0,1";
		$resultado3 = mysql_query($sql3)
		or die (mysql_error());
		$linha3=mysql_fetch_array($resultado3);		


// tirar da lista de ativos do emkt
error_reporting(E_ALL);
require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
// Esses valores podem ser obtidos na página de configurações do
// Email Marketing

$hostName = 'emailmkt7';
$login 	  = 'contadoramigo1';
$chaveApi = 'd32c4b2b6955e2e69691347258ed2378';
$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

		
if($Status == 'demo'){

				//Atualizar status em login.
				$sql = "UPDATE login SET status='$Status' WHERE id='$ID'";
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

				$contatos = array();
				array_push($contatos, array('email'=> $linha3["email"], 'nome'=>$linha3["assinante"]));

				//Caso queira remover de listas, informar os IDs desta no 2o parametro.
				$repositorio->desativar($contatos,array(26587)); // ativos				
				$repositorio->desativar($contatos, array(26588)); // inativos
				$repositorio->importar($contatos,array(38414)); // trial
}

else if($Status == 'ativo'){

				$sql_checa_pagamentos = "SELECT * FROM historico_cobranca WHERE id='" . $ID . "' AND status_pagamento='pendente' ORDER BY idHistorico DESC LIMIT 0,1";
				$resultado_checa_status = mysql_query($sql_checa_pagamentos) or die (mysql_error());
				$linha_checa_resultado=mysql_fetch_array($resultado_checa_status);
				
				if(date('Ymd',(mktime(0,0,0,date('m',strtotime($linha_checa_resultado["data_pagamento"])),date('d',strtotime($linha_checa_resultado["data_pagamento"]))+5,date('Y',strtotime($linha_checa_resultado["data_pagamento"]))))) <= date('Ymd')){
					echo "Não foi possível alterar o status deste usuário. Este possui um pagamento pendente há mais de 5 dias.";
					exit;
					
				}

				//Atualizar status em login.
				$sql = "UPDATE login SET status='$Status' WHERE id='$ID'";
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

				$contatos = array();
				array_push($contatos, array('email'=> $linha3["email"], 'nome'=>$linha3["assinante"]));

				//Caso queira remover de listas, informar os IDs desta no 2o parametro.							
				$repositorio->desativar($contatos, array(26588)); // inativos
				$repositorio->desativar($contatos,array(38414)); // trial
				$repositorio->importar($contatos,array(26587)); // ativos	
}


else if($Status == 'inativo' or 'demoinativo'){

				//Atualizar status em login.
				$sql = "UPDATE login SET status='$Status' WHERE id='$ID'";
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

				$contatos = array();
				array_push($contatos, array('email'=> $linha3["email"], 'nome'=>$linha3["assinante"]));

				//Caso queira remover de listas, informar os IDs desta no 2o parametro.				
				$repositorio->desativar($contatos,array(38414)); // trial
				$repositorio->desativar($contatos,array(26587)); // ativos
				$repositorio->importar($contatos, array(26588)); // inativos
}




echo "Status alterado com sucesso!";


//header('Location: cliente_administrar.php?id=' . $ID );
?>