<?php 

 ini_set('display_errors',1);
 ini_set('display_startup_erros',1);
 error_reporting(E_ALL);

	ob_start();
	require_once '../conect.php';
	include '../session.php';
	include 'check_login.php';
	include '../emkt.api.class.php';
	
// Abre a conexão com o banco de dados.
function AbreConexaoBanco($conexao){
	if(!$conexao) {
		require_once('../conect.php');
	}
}

// Fecha a conexão com o banco de dados.
function FechaConexaoBanco($conexao){
	mysql_close($conexao);
}

if(isset($_POST['mensagemSucesso'])) {
	// atualizando os status de contatos que foram agendados recentemente
	$sql_update = "UPDATE envio_emails_cobranca SET status = 1 WHERE status = 2";
	mysql_query($sql_update);
}

if(isset($_POST['RestartMensagens'])) {
	// atualizando os status de contatos que tiveram problemas no envio.
	$sql_update = "UPDATE envio_emails_cobranca SET status = 0 WHERE status = 2";
	mysql_query($sql_update);
}

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

if(isset($_REQUEST['agendar'])){
	
	$arrAgendamentoEfetivado = array();
	
	$mensagensOK = "";
	
	$user_vencidos 					 	 = array();
	$arr_boleto_compensado 				 = array();
	$arr_boleto_a_vencer				 = array();
	$arr_demo_a_vencer					 = array();
	$arr_cartao_autorizado				 = array();
	$arr_cartao_nao_autorizado			 = array();
	$arr_assinatura_inativa				 = array();
	$arr_demo_inativo					 = array();
	$arr_assinatura_reativada			 = array();
	
	$servico_contratado              = array();
	$premium_contratado				 = array();

	$demo_info				 = array();
	$demo_info_15				 = array();
	$demo_inativo_info				 = array();
	
	$contatosLimpar									 = array();
	
	$sql_agendamentos = "	SELECT
													nome
													, email
													, tipo_mensagem
												FROM envio_emails_cobranca
												WHERE
													status = 0
												ORDER BY
													tipo_mensagem
											 ";
	$rs = mysql_query($sql_agendamentos);
	
	while($arrDados = mysql_fetch_assoc($rs)){
		extract($arrDados);
		switch ($tipo_mensagem){
			case 'assinatura_inativa':
				array_push($arr_assinatura_inativa,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'assinatura_reativada':
				array_push($arr_assinatura_reativada,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'boleto_a_vencer':
				array_push($arr_boleto_a_vencer,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'boleto_compensado':
				array_push($arr_boleto_compensado,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'user_vencidos':
				array_push($user_vencidos,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'cartao_autorizado':
				array_push($arr_cartao_autorizado,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'cartao_nao_autorizado':
				array_push($arr_cartao_nao_autorizado,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'demo_a_vencer':
				array_push($arr_demo_a_vencer,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'demo_inativo':
				array_push($arr_demo_inativo,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'demo_info':
				array_push($demo_info,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'demo_info_15':
				array_push($demo_info_15,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'demo_inativo_info':
				array_push($demo_inativo_info,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'servico_contratado':
				array_push($servico_contratado,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;
			case 'premium_contratado':
				array_push($premium_contratado,array('nome'=>utf8_decode($nome),'email'=>$email));
			break;			
				
		}

		$existe_email_inserir = true;

	}
	error_reporting(E_ALL);
	
	FechaConexaoBanco($conexao);
	
	//Cria um objeto do EMKT e zera as listas existentes no servidor
	$emkt = new APi_EMKT();
	if( $existe_email_inserir )
		$emkt->resetarListasCobranca();
	
	/*
	#######################################################################
	ASSINATURA INATIVA
	#######################################################################
	*/
	if(count($arr_assinatura_inativa) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "assinatura_inativa";
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_assinatura_inativa); $i++) { 
			$aux = $arr_assinatura_inativa[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){	
			$arrAgendamentoEfetivado['assinatura_inativa'] = 'agendadas';//count($arr_assinatura_inativa);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'assinatura_inativa'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
		}
			
	}
	
	
	/*
	#######################################################################
	ASSINATURA REATIVADA
	#######################################################################
	*/
	if(count($arr_assinatura_reativada) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "assinatura_reativada";			

		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_assinatura_reativada); $i++) { 
			$aux = $arr_assinatura_reativada[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['assinatura_reativada'] = 'agendadas';//count($arr_assinatura_reativada);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'assinatura_reativada'";
			mysql_query($sql_update);			
			
			FechaConexaoBanco($conexao);
		}

	}
	
	/*
	#######################################################################
	BOLETO A VENCER
	#######################################################################
	*/
	if(count($arr_boleto_a_vencer) > 0){
	
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "boleto_a_vencer";
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_boleto_a_vencer); $i++) { 
			$aux = $arr_boleto_a_vencer[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['boleto_a_vencer'] = 'agendadas';//count($arr_boleto_a_vencer);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'boleto_a_vencer'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
			
			
		}
	}
	
	/*
	#######################################################################
	BOLETO CONFIRMADO
	#######################################################################
	*/
	if(count($arr_boleto_compensado) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "boleto_compensado";
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_boleto_compensado); $i++) { 
			$aux = $arr_boleto_compensado[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		// echo $result;
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['boleto_compensado'] = 'agendadas';//count($arr_boleto_compensado);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'boleto_compensado'";
			mysql_query($sql_update);	
			
			FechaConexaoBanco($conexao);
		}
	}
	

	/*
	#######################################################################
	USER VENCIDO
	#######################################################################
	*/
	if(count($user_vencidos) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "user_vencidos";
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($user_vencidos); $i++) { 
			$aux = $user_vencidos[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}

		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		// echo $result;

		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['user_vencidos'] = 'agendadas';//count($user_vencidos);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'user_vencidos'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
		}
		
	}
/*
	#######################################################################
	Contratação Serviço
	#######################################################################
	*/
	if(count($servico_contratado) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "servico_contratado";			

		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($servico_contratado); $i++) { 
			$aux = $servico_contratado[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}

		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)

		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['servico_contratado'] = 'agendadas';//count(servico_contratado);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'servico_contratado'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
		}
	}
	
	/*
	#######################################################################
	Contratação Premium
	#######################################################################
	*/
	if(count($premium_contratado) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "premium_contratado";			
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($premium_contratado); $i++) { 
			$aux = $premium_contratado[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}

		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['premium_contratado'] = 'agendadas';//count($premium_contratado);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'premium_contratado'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
			
		}
		
	}
	
	

	/*
	#######################################################################
	CARTAO CONFIRMADO
	#######################################################################
	*/
	if(count($arr_cartao_autorizado) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "cartao_autorizado";			
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_cartao_autorizado); $i++) { 
			$aux = $arr_cartao_autorizado[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['cartao_autorizado'] = 'agendadas';//count($arr_cartao_autorizado);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'cartao_autorizado'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
		}
	}
	
	/*
	#######################################################################
	CARTAO COM ERRO
	#######################################################################
	*/
	
	if(count($arr_cartao_nao_autorizado) > 0){
		//Linha 475 agendarMensagem
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "cartao_nao_autorizado";			
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_cartao_nao_autorizado); $i++) { 
			$aux = $arr_cartao_nao_autorizado[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['cartao_nao_autorizado'] = 'agendadas';//count($arr_cartao_nao_autorizado);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'cartao_nao_autorizado'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
		}
	}
	
	/*
	#######################################################################
	DEMO A VENCER
	#######################################################################
	*/
	if(count($arr_demo_a_vencer) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "demo_a_vencer";			
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_demo_a_vencer); $i++) { 
			$aux = $arr_demo_a_vencer[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)

		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['demo_a_vencer'] = 'agendadas';//count($arr_demo_a_vencer);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'demo_a_vencer'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
		}

	}

	/*
	#######################################################################
	DEMO PARA INATIVO
	#######################################################################
	*/
	if(count($arr_demo_inativo) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "demo_inativo";			
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
	   	##########################################################################################################################################
		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($arr_demo_inativo); $i++) { 
			$aux = $arr_demo_inativo[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)

		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['demo_inativo'] = 'agendadas';//count($arr_demo_inativo);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'demo_inativo'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);			
		
		}

	}

	//Info para os demos
	if(count($demo_info) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "demo_info";			
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($demo_info); $i++) { 
			$aux = $demo_info[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}

		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['demo_info'] = 'agendadas';//count($demo_info);
			sleep(1);

			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'demo_info'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);			
		}

	}


	//Info para os demos de 15 dias
	if(count($demo_info_15) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "demo_info_15";
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($demo_info_15); $i++) { 
			$aux = $demo_info_15[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['demo_info_15'] = 'agendadas';//count($demo_info_15);
			sleep(1);

			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'demo_info_15'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);
			
		}

	}


	//Info para os demos de 15 dias
	if(count($demo_inativo_info) > 0){
		
		//Define o tipo de mensgem a ser enviada
		$tipo_mensagem = "demo_inativo_info";			
					
		// Criar uma lista no Emkt
		$id_da_lista = $emkt->criarListaEMKT($tipo_mensagem,""); // function criarListaEMKT($nome,$descricao)
		##########################################################################################################################################

		##########################################################################################################################################
		//Insere um array de contatos em uma lista 		
		$contatos = array();
		for ($i=0; $i < count($demo_inativo_info); $i++) { 
			$aux = $demo_inativo_info[$i];
			if ( filter_var(strtolower($aux['email']), FILTER_VALIDATE_EMAIL)) {
				$contatos[] = strtolower($aux['email']);
			}

		}
		$emkt->addContatoLista($contatos,$id_da_lista);
		##########################################################################################################################################

		##########################################################################################################################################		
		// Agendar uma mensagem // O chamada do método $emkt->agendarMensagem foi comentado para evitar o agendamento do email. 
		$result = $emkt->agendarMensagem( $tipo_mensagem , $id_da_lista ); // function agendarMensagem($tipo_mensagem,$id_lista)
		// echo $result;
		
		if( floatval($result) > 0 ){
			$arrAgendamentoEfetivado['demo_inativo_info'] = 'agendadas';//count($demo_inativo_info);
			sleep(1);
			
			AbreConexaoBanco($conexao);
			
			$sql_update = "UPDATE envio_emails_cobranca SET status = 2 WHERE status = 0 AND tipo_mensagem = 'demo_inativo_info'";
			mysql_query($sql_update);
			
			FechaConexaoBanco($conexao);			

		}

	}
	
}



if(isset($_REQUEST['atualiza'])){
	
	$emkt = new APi_EMKT();	

	$IDLista = array($_REQUEST['IDLista']);//26587
	//	$IDLista = array(57888,26588,38414,56454,50557);//26587
	$id_da_lista = 0;
	switch($_REQUEST['IDLista']){
		case '57888':
			$status_login = array("'ativo'");
			$emkt->resetarLista("Total Ativos");
			$id_da_lista = $emkt->criarListaEMKT("Total Ativos",""); // function criarListaEMKT($nome,$descricao)
			$mensagemOK = "Lista de Ativos Atualizada com Sucesso";
		break;
		case '26588':
			$status_login = array("'inativo'");
			$emkt->resetarLista("Total Inativos");
			$id_da_lista = $emkt->criarListaEMKT("Total Inativos",""); // function criarListaEMKT($nome,$descricao)
			$mensagemOK = "Lista de Inativos Atualizada com Sucesso";
		break;
		case '38414':
			$status_login = array("'demo'");
			$emkt->resetarLista("Total Demos");
			$id_da_lista = $emkt->criarListaEMKT("Total Demos",""); // function criarListaEMKT($nome,$descricao)
			$mensagemOK = "Lista de Demos Atualizada com Sucesso";
		break;
		case '56454':
			$status_login = array("'demoInativo'");
			$emkt->resetarLista("Total Demos Inativos");
			$id_da_lista = $emkt->criarListaEMKT("Total Demos Inativos",""); // function criarListaEMKT($nome,$descricao)
			$mensagemOK = "Lista de Demos Inativos Atualizada com Sucesso";
		break;
		case '50557':
			$status_login = array("'cancelado'");
			$emkt->resetarLista("Total Cancelados");
			$id_da_lista = $emkt->criarListaEMKT("Total Cancelados",""); // function criarListaEMKT($nome,$descricao)
			$mensagemOK = "Lista de Cancelados Atualizada com Sucesso";
		break;
	}
	
	// if( $existe_email_inserir )
	// $emkt->resetarListasCobranca();

	AbreConexaoBanco($conexao);
	
	for($i=0; $i<count($IDLista); $i++){
					
		// PEGANDO OS DADOS DO BANCO PARA O STATUS SELECIONADO DE ACORDO COM O BOTÃO CLICADO / INSERINDO OS NOVOS	
		// DEMOS TESTE 6857, 6905, 6958, 6835, 6858, 6764, 7072, 6870, 7077, 7075, 7004, 7045, 6963, 6869, 6963, 6787, 6614, 6576, 6742, 6613
		$contatos_inserir = mysql_query("SELECT assinante, email FROM login WHERE STATUS IN (" . $status_login[$i] . ") AND NOT ISNULL(email) AND email <> '' AND email <> '1' AND email like '%@%' AND id NOT IN (9,1581, 4093, 6857, 6905, 6958, 6835, 6858, 6764, 7072, 6870, 7077, 7075, 7004, 7045, 6963, 6869, 6963, 6787, 6614, 6576, 6742, 6613) AND id = idUsuarioPai "); 
		
		$contatosCadastrar = array();
		$aux = 0;
		while($rsContatos = mysql_fetch_array($contatos_inserir)){
			if($rsContatos['email'] <> ''){
				if (filter_var(strtolower($rsContatos['email']), FILTER_VALIDATE_EMAIL)) {
			    	$contatosCadastrar [] = strtolower($rsContatos['email']);
			    }
				
			}
		}
		// mysqli_query($conexao, 'SET SESSION wait_timeout = 700');

		if( count($contatosCadastrar) > 0 )
			$emkt->addContatoLista($contatosCadastrar,$id_da_lista);

		$mensagemOK = "Listas atualizadas com sucesso em " . date('d/m/Y H:i:s');

		echo '<script>location.href="listas_emailmarketing.php?mensagemOkListas='.$mensagemOK.'"</script>';
		
	}

	
}

echo "<pre>";
	print_r($conexao);
echo "</pre>";


if($conexao == false){
	//$conexao = mysql_connect("177.153.16.160", "contadoramigo", "ttq231kz");
	require_once '../conect.php';
}

include 'header.php';

if(isset($mensagemOK)){
	echo "<script>alert('".$mensagemOK."')</script>";
}
if(isset($_GET['mensagemOkListas'])){
	echo "<script>alert('".$_GET['mensagemOkListas']."')</script>";
}
?>

<div class="principal minHeight">

<?
// CONSULTA AO BANCO DE DADOS PARA MOSTRAR AS QUANTIDADES SEPARADAS POR STATUS
$totais = mysql_fetch_array(mysql_query("
SELECT SUM(CASE WHEN STATUS = 'ativo' THEN 1 ELSE 0 END) ativos
, SUM(CASE WHEN STATUS = 'inativo' THEN 1 ELSE 0 END) inativos
, SUM(CASE WHEN STATUS IN ('demo') THEN 1 ELSE 0 END) demos
, SUM(CASE WHEN STATUS IN ('demoInativo') THEN 1 ELSE 0 END) demosInativos
, SUM(CASE WHEN STATUS IN ('cancelado') THEN 1 ELSE 0 END) cancelados
FROM login 
WHERE STATUS IN ('ativo', 'inativo', 'demo', 'demoInativo', 'cancelado')
AND NOT ISNULL(email) AND email <> '' AND email <> '1' AND email like '%@%'
AND id NOT IN (9,1581, 4093, 6857, 6905, 6958, 6835, 6858, 6764, 7072, 6870, 7077, 7075, 7004, 7045, 6963, 6869, 6963, 6787, 6614, 6576, 6742, 6613) AND id = idUsuarioPai")); 

?>

  <div class="titulo" style="margin-bottom:10px;">Enviar Emkt</div>
  <div style="clear:both;"> </div>
  
  
  <table border="0" cellspacing="2" cellpadding="4" style="margin-top: 20px;" width="100%">
      <tr>
        <th width="25%" align="center">Ativos</th>
        <th width="25%" align="center">Inativos</th>
        <th width="25%" align="center">Demos</th>
        <th width="25%" align="center">Demos Inativos</th>
        <!-- <th width="20%" align="center">Cancelados</th> -->
      </tr> 
			<tr class="guiaTabela">
      	<td align="right"><?=$totais['ativos']?></td>
      	<td align="right"><?=$totais['inativos']?></td>
      	<td align="right"><?=$totais['demos']?></td>
      	<td align="right"><?=$totais['demosInativos']?></td>
      	<!-- <td align="right"><?=$totais['cancelados']?></td> -->
      </tr>

			<tr>
      	<td align="center">
      		<input type="button" value="Atualizar Lista" onClick="location.href='listas_emailmarketing.php?atualiza&IDLista=57888'">
      	</td>
      	<td align="center">
      		<input type="button" value="Atualizar Lista" onClick="location.href='listas_emailmarketing.php?atualiza&IDLista=26588'">
      	</td>
      	<td align="center">
      		<input type="button" value="Atualizar Lista" onClick="location.href='listas_emailmarketing.php?atualiza&IDLista=38414'">
      	</td>
      	<td align="center">
      		<input type="button" value="Atualizar Lista" onClick="location.href='listas_emailmarketing.php?atualiza&IDLista=56454'">
      	</td>
      	<!-- <td align="center"><input type="button" value="Atualizar Lista" onClick="location.href='listas_emailmarketing.php?atualiza&IDLista=50557'"></td> -->
      </tr>
		</table>

    <!--<div style="width: 100%; text-align:center; margin: 20px auto 0 auto;"><input type="button" value="Atualizar Listas" onClick="location.href='listas_emailmarketing.php?atualiza'"></div>-->

  <div style="clear:both"> </div>
  


<?
// CONSULTA AO BANCO DE DADOS PARA MOSTRAR AS QUANTIDADES SEPARADAS POR STATUS

$sql_mensagens = "
SELECT
	tipo_mensagem,
	status,
	case 
		when tipo_mensagem = 'assinatura_inativa' then 'ASSINATURA INATIVA'
		when tipo_mensagem = 'assinatura_reativada' then 'ASSINATURA REATIVADA'
		when tipo_mensagem = 'boleto_a_vencer' then 'BOLETO A VENCER'
		when tipo_mensagem = 'boleto_compensado' then 'BOLETO COMPENSADO'
		when tipo_mensagem = 'user_vencidos' then 'CONTA VENCIDA'
		when tipo_mensagem = 'cartao_autorizado' then 'CARTÃO AUTORIZADO'
		when tipo_mensagem = 'cartao_nao_autorizado' then 'CARTÃO NÃO AUTORIZADO'
		when tipo_mensagem = 'demo_a_vencer' then 'DEMO A VENCER'
		when tipo_mensagem = 'demo_inativo' then 'DEMO INATIVO'
		when tipo_mensagem = 'demo_info' then 'DEMO 7 DIAS'
		when tipo_mensagem = 'demo_info_15' then 'DEMO 15 DIAS'
		when tipo_mensagem = 'demo_inativo_info' then 'DEMO INATIVO 15 DIAS'
		when tipo_mensagem = 'premium_contratado' then 'CONTRATAÇÃO DO PREMIUM'
		when tipo_mensagem = 'servico_contratado' then 'CONTRATAÇÃO DE SERVIÇO'
		end txt_tipo_mensagem
	, CONCAT(YEAR(data),'-',MONTH(data),'-',DAY(data)) data
	, count(*) total
FROM 
	envio_emails_cobranca
WHERE
	status IN (0,2)
GROUP BY 
	1,2
ORDER BY
	tipo_mensagem
";
$rs_mensagens = mysql_query($sql_mensagens);

?>
  <div style="clear:both;margin-bottom:20px;"> </div>

  <div class="titulo" style="margin-bottom:10px;">Agendamento de mensagens</div>
  <div style="clear:both;"> </div>

<?

	if(mysql_num_rows($rs_mensagens) > 0){
?>
  <table border="0" cellspacing="2" cellpadding="4" style="margin-top: 20px; width: 65%;">
      <tr>
        <th width="50%" align="center">Tipo Mensagem</th>
        <th width="10%" align="center">Qtd</th>
        <th width="15%" align="center">Data</th>
        <th width="10%" align="center">Status</th>
        <?
			//if(isset($arrAgendamentoEfetivado)){
	        //echo '<th width="10%" align="center">Status</th>';
			//}
		?>
      </tr> 
<?
      while($totais = mysql_fetch_array($rs_mensagens)){
		  
		  $statusMsg = "";
		  
		  if($totais['status'] == 2) {
			  $statusMsg = "Agendadas";
		  }
		   
?>
       
       
       
        <tr class="guiaTabela">
          <td><?=$totais['txt_tipo_mensagem']?></td>
          <td align="right"><?=$totais['total']?></td>
          <td align="right"><?=date("d/m/Y",strtotime($totais['data']))?></td>
          <td align="right" style="color: #f00;"><?=$statusMsg;?></td>
          <?
//          if(isset($arrAgendamentoEfetivado)){
//            echo '<td align="right" style="color: #f00;">' . $arrAgendamentoEfetivado[$totais['tipo_mensagem']] . '</td>';
//          }
          ?>
        </tr>
<?        
      }
?>
		</table>

    <div style="width: 100%; text-align:left; margin: 20px auto 0 auto;"><!--<input type="button" value="Agendar Envios" onClick="location.href='listas_emailmarketing.php?agendar'">-->
		<input type="button" value="Agendar Envios" onClick="location.href='listas_emailmarketing.php?agendar'">

		<form action="listas_emailmarketing.php" method="post" style="display: inline-block;">
			<input type="submit" value="Confirmar Agendamento" />
			<input type="hidden" name="mensagemSucesso" value="1" />
		</form>
		<form action="listas_emailmarketing.php" method="post" style="display: inline-block;">
			<input type="submit" value="Reset" />
			<input type="hidden" name="RestartMensagens" value="1" />
		</form>
    </div>
<?
	} else {
?>
		Não há mensagens pendentes de agendamento

<? 
	}
?>
  <div style="clear:both"> </div>


</div>


<?php include '../rodape.php' ?>
