<?php
	
	$Assinante = ("Fábio Ribeiro");
	$AssinanteEmPartes = explode(" ",$Assinante);

	$Email = "fabio.ribeiro@gmail.com";
		
	$assuntoMail = "1 Inscrição confirmada";
	$emailuser = $Email;

	$AssinanteExplode = explode(" ", $Assinante);

$nomeAssinante = $AssinanteExplode[0];

	include('classes/phpmailer.class.php');

	/* Montando a mensagem a ser enviada no corpo do e-mail. */
	include "mensagens/cartao_confirmado.php";
	include 'mensagens/componente_envio_novo.php';

/*
	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/conta_inativa.php";
	include 'mensagens/componente_envio_novo.php';


	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/conta_reativada.php";
	include 'mensagens/componente_envio_novo.php';


	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/conta_reativada_boleto.php";
	include 'mensagens/componente_envio_novo.php';


	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/demo_a_vencer.php";
	include 'mensagens/componente_envio_novo.php';


	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/demo_inativo.php";
	include 'mensagens/componente_envio_novo.php';

	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/indica_amigo.php";
	include 'mensagens/componente_envio_novo.php';

	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/inscricao_confirmada.php";
	include 'mensagens/componente_envio_novo.php';
	
	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/lembrete_senha.php";
	include 'mensagens/componente_envio_novo.php';

	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/novo_assinante.php";
	include 'mensagens/componente_envio_novo.php';

	/* Montando a mensagem a ser enviada no corpo do e-mail. *
	include "mensagens/pagamento_boleto_confirmado.php";
	include 'mensagens/componente_envio_novo.php';
	*/
	
?>