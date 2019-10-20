﻿<?php

// Realiza a requisição do arquivo responsavel pela a conexão com o banco.
require_once("conect.php");

// Realiza a requisição do arquivo responsavel por fazer o cadastro.
require_once('DataBaseMySQL/AssinanteCadastro.php');

// Realiza a requisição do arquivo responsavel por fazer o envio do email.
require_once('EnvioEmail.php');

if(isset($_POST["txtAssinante"])){
	
	$alteracaoAvulsoAssinante = '';
	
	$Assinante = mysql_real_escape_string($_POST["txtAssinante"]);
	$AssinanteEmPartes = explode(" ",$Assinante);
	$AssinantePrimeiroNome = $AssinanteEmPartes[0];
	$Email = $_POST["txtEmailAssina"];
	$Senha = $_POST["txtSenhaAssina"];
	$PrefixoTelefoneCobranca = $_POST["txtPrefixoTelefoneCobranca"];
	$TelefoneCobranca = $_POST["txtTelefoneCobranca"];
	$DataInclusao = date("Y-m-d");
	$dataPagamento = date('Y-m-d',(mktime(0,0,0,date('m'),date('d')+30,date('Y'))));
	
	
	// Instancia da classe que manipulas os dados de login, cobrança, historico de cobranca.
	$assinanteCadastro = new AssinanteCadastro();
	
	// Chama o método que verifica se existe usuario avulso com o email informado pelo cliente.
	$dadosLogin = $assinanteCadastro->VerificaEmailServicoAvulsoExiste($Email); 
	
	// Verifica se houve retorno.
	if($dadosLogin){
		
		$id = $dadosLogin['id']; 
						
		//Atualiza dados em login.
		$assinanteCadastro->AtualizaDadosLogin($id, $RazaoSocial,$Assinante,$Email,$Senha,'demo','1',$id);
		
		$loginAlterado = 'alert("Verificamos que você já realizou uma solicitação de serviço conosco e por isso o sistema estara utilizando os dados de cobrança informado");';
		
	} else {
		
		//Gravar dados em login.
		$id = $assinanteCadastro->IncluirDadosLogin($RazaoSocial,$Assinante,$Email,$Senha,'demo','1',0);
		
		//Atualiza o id pai
		$assinanteCadastro->AtualizaIdUsuarioPaiLogin($id);
		
		//Gravar dados em dados de cobrança.
		$assinanteCadastro->IncluiDadosCobranca($id, $Assinante, $PrefixoTelefoneCobranca, $TelefoneCobranca, '', NULL, NULL, NULL, NULL, '1');
	}
	
	// Confirma se o id foi informado.
	if($id) {
		
		//Define que o novo usuario ja aceitou o contrato
		$assinanteCadastro->GravaContratoAceito($id);

		// LOG DE ACESSOS
		$assinanteCadastro->GravalogsAcesso($id, 'ASSINATURA: NOVO ASSINANTE MULTIPLO');
		
		// INSERE NA TABELA DE METRICAS
		$assinanteCadastro->GravaMetricasConversao($id,'assinatura');

		//Gravar dados em histórico de cobrança.
		$assinanteCadastro->GravaMetricasConversao($id);
		
		//Gravar dados em dados de cobrança.
		$assinanteCadastro->GravaHistoricoCobranca($id, $dataPagamento, 'a vencer');
				
		// Realiza o envio de email para o cliente informado que o cadastro dele foi realizado com sucesso e ele podera inciar seu período de avaliação.
		// Pega os dados do cliente.
		$nome = $Assinante;
		$email = $destino = $Email;		
		$assunto = 'Bem-vindo ao Contador Amigo';
		$tipoMensagem = 'DemoRecemIniciado';

		// Realiza a chamada da classe para dar inicio ao envio do email.
		$envioEmail = new EnvioEmail();
		
		// Realiza o cadastro demo recém iniciado.
		$insert = "INSERT INTO `demo_recem_iniciado` (`dateInsert`, `user_id`, `email`, `nome`, `status`) VALUES (NOW(), '".$id."', '".$email."', '".$nome."', 'aguardando')";
		mysql_query($insert);
		$demoRecemIniciadoId = mysql_insert_id();
		
		// Realiza achamada do envio.
		$statusEnvio = @$envioEmail->PreparaEnvioEmail($nome, $email, $destino, $assunto, $tipoMensagem);
		
		// Grava a confirmação de email enviado
		if($statusEnvio){
			$insert = "UPDATE `demo_recem_iniciado` SET `status` = 'enviado' WHERE `demo_recem_iniciado`.`id` = '".$demoRecemIniciadoId."'";
			mysql_query($insert);
		}	
	}
	
	################################################################################################################################################	
	//Inserir no emkt
	include 'emkt.api.class.php';

	$emkt = new APi_EMKT();

	$id_lista = $emkt->getIdLista("Total Demos");
	$emkt->inserirContatoEMKT($id,$id_lista); // function inserirContatoEMKT($id_user,$id_lista)

	################################################################################################################################################

	/* Logando e Redirecionando para a página restrita inicial */	
	
	session_destroy();
	session_start();
	$_SESSION['emailAssinatura'] = $_POST["txtEmailAssina"];
	$_SESSION['senhaAssinatura'] = md5($_POST["txtSenhaAssina"]);
	
	// Redireciona para o google analitics 	
	echo '<script>'.$loginAlterado.' window.location="assinatura_sucesso.html";</script>';
	//echo '<script>'.$loginAlterado.' window.location="auto_login.php?login";</script>';
}else{
	header('Location: assinatura.php');
}
?>