<?php 
include '../conect.php';

include '../session.php';

include('../classes/phpmailer.class.php');
	
include 'check_login.php';

include 'header.php';

$sql_configuracoes = "SELECT * FROM configuracoes WHERE configuracao = 'mensalidade'";
$rsConfiguracoes = mysql_fetch_array(mysql_query($sql_configuracoes));

$mensalidade = $rsConfiguracoes['valor'];

$mensalidade_formatada = number_format($mensalidade,2,",",".");


function retornaNumeroCartaoMascara($strNumeroCartao){
	$mascaraCartao = "";
	for($i=0; $i<strlen($strNumeroCartao); $i++){
		if($i>=6 && $i<=11){
			$mascaraCartao .= "*";
		}else{
			$mascaraCartao .= substr($strNumeroCartao, $i, 1);
		}
	}
	return $mascaraCartao;
}

/*
CONSULTA ULTIMOS A VENCER VENCIDOS E NAO PAGOS
SELECT log.id
, log.assinante
, log.status
, his.status_pagamento
, DATEDIFF(his.data_pagamento,DATE(now())) diferenca
, MAX(his.data_pagamento) data_avencer 
FROM `login` log 
INNER JOIN dados_cobranca cob 
  ON log.id = cob.id 
INNER JOIN historico_cobranca his 
  ON log.id = his.id 
WHERE log.status IN ('ativo','inativo') AND cob.forma_pagameto = 'boleto' AND his.status_pagamento in ('a vencer', 'vencido','não pago')
GROUP BY 1,2,3,4,5
ORDER BY 3 DESC
*/


/*
funções de processamento da cobrança
*/
// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5

// ########################################################################################################
ini_set('allow_url_fopen', 1); // Ativa a diretiva 'allow_url_fopen'


	function trataTxt($var) {
	
		$str = $var; 
		
		$str = str_replace("á","a",$str);	
		$str = str_replace("à","a",$str);	
		$str = str_replace("â","a",$str);	
		$str = str_replace("ã","a",$str);	
		$str = str_replace("Á","A",$str);	
		$str = str_replace("À","A",$str);	
		$str = str_replace("Â","A",$str);	
		$str = str_replace("Ã","A",$str);	
	
		$str = str_replace("é","e",$str);	
		$str = str_replace("è","e",$str);	
		$str = str_replace("ê","e",$str);	
		$str = str_replace("É","E",$str);	
		$str = str_replace("È","E",$str);	
		$str = str_replace("Ê","E",$str);	
	
		$str = str_replace("í","i",$str);	
		$str = str_replace("ì","i",$str);	
		$str = str_replace("î","i",$str);	
		$str = str_replace("Í","I",$str);	
		$str = str_replace("Ì","I",$str);	
		$str = str_replace("Î","I",$str);	
	
		$str = str_replace("ó","o",$str);	
		$str = str_replace("ò","o",$str);	
		$str = str_replace("ô","o",$str);	
		$str = str_replace("õ","o",$str);	
		$str = str_replace("Ó","O",$str);	
		$str = str_replace("Ò","O",$str);	
		$str = str_replace("Ô","O",$str);	
		$str = str_replace("Õ","O",$str);	
	
		$str = str_replace("ú","u",$str);	
		$str = str_replace("ù","u",$str);	
		$str = str_replace("û","u",$str);	
		$str = str_replace("ü","u",$str);	
		$str = str_replace("Ú","U",$str);	
		$str = str_replace("Ù","U",$str);	
		$str = str_replace("Û","U",$str);	
		$str = str_replace("Ü","U",$str);	
	
		$str = str_replace("ñ","n",$str);	
		$str = str_replace("Ñ","N",$str);	
	
		$str = str_replace("ç","c",$str);
		$str = str_replace("Ç","C",$str);

		$str = str_replace("&","E",$str);
		
		return $str;
	}


// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
$sqlEmail = "SELECT assinante, email, status FROM login WHERE id='1581' LIMIT 0,1";
//					printf("<br>".$sqlEmail."<BR>");
$resultadoEmail = mysql_query($sqlEmail)
or die (mysql_error());

$linhaEmail = mysql_fetch_array($resultadoEmail);

//Envio de e-mail alertando o pagamento do boleto.
$statusUser = $linhaEmail["status"];
$Assinante = $linhaEmail["assinante"];
//printf($Assinante . "<BR><BR>");

$AssinanteExplode = explode(" ", $Assinante);
$emailuser = 'fabio.ribeiro@gmail.com';//$linhaEmail["email"];
//printf($emailuser . "<BR><BR>");
$valorEmail = 50;
$assuntoMail = "Pagamento recebido";
include '../mensagens/pagamento_boleto_confirmado.php';
//include '../mensagens/componente_envio.php';
include '../mensagens/componente_envio_novo.php';
//printf($mensagemHTML . "<BR><BR>");




/*
funções de processamento da cobrança
*/
	

	/*
	####################################################################
	ENVIANDO EMAILS COM OS BOLETOS PARA USUARIOS COM PAGAMENTOS HÁ 5 DIAS DE VENCER
	####################################################################
	*/
	$sql_checa_boletos_a_vencer = "select 
										l.id
										, l.email
										, l.assinante
										, l.status
										, h.data_pagamento
										, h.idHistorico
									FROM 
										login l INNER JOIN historico_cobranca h ON l.id = h.id 
												INNER JOIN dados_cobranca d ON h.id = d.id 
									WHERE 
										DATEDIFF(h.data_pagamento, DATE(now())) between 0 AND 5 
										AND h.status_pagamento IN ('a vencer') 
										AND d.forma_pagameto = 'boleto'
										AND h.envio_email <> 'enviado'
										AND (l.status <> 'cancelado' AND l.status <> 'demo')
									";
	$rsBoletos_a_vencer = mysql_query($sql_checa_boletos_a_vencer)
	or die (mysql_error());


	while($boletos_a_vencer=mysql_fetch_array($rsBoletos_a_vencer)){


		//Componente de Envio de e-mail.
		$Assinante = $boletos_a_vencer["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = date('d/m/Y',strtotime($boletos_a_vencer["data_pagamento"]));
		$emailuser = 'fabio.ribeiro@gmail.com';//$boletos_a_vencer["email"];
		$assuntoMail = "Boleto a vencer";
		
		// VARIAVEIS QUE SÃO UTILIZADAS NO CORPO DO EMAIL
		
		$sql_dados_empresa = "SELECT * FROM dados_da_empresa WHERE id = " . $boletos_a_vencer["id"];
		$linha_dados_empresa = mysql_fetch_array(mysql_query($sql_dados_empresa));
		
		$id_usuario_boleto = $boletos_a_vencer["id"];
		$valor_boleto = $mensalidade_formatada;
		$mes_boleto = date('m',strtotime($boletos_a_vencer["data_pagamento"]));
		$data_pagamento_boleto = date('m',strtotime($boletos_a_vencer["data_pagamento"]));
		
		
		if(($boletos_a_vencer["status"] == 'ativo') || ($boletos_a_vencer["status"] == 'inativo')){
			include '../mensagens/boleto_a_vencer.php';
		}
		//include '../mensagens/componente_envio.php';
		include '../mensagens/componente_envio_novo.php';

	}

	/*
	####################################################################
	ENVIANDO EMAILS COM OS BOLETOS PARA USUARIOS COM PAGAMENTOS HÁ 5 DIAS DE VENCER
	####################################################################
	*/


	
	/*
	####################################################################
	ENVIANDO EMAILS COM INSTRUÇÕES PARA ATIVAÇÃO DOS DEMO
	####################################################################
	*/
	// A QUERY ABAIXO RETORNA DADOS DE USUARIOS DEMO QUE ESTÃO COM STATUS DE PAGAMENTO A VENCER DAQUI A 5 DIAS
	$sql_checa_demos_a_vencer = "select 
										l.id
										, l.email
										, l.assinante
										, l.status
										, h.data_pagamento
										, h.idHistorico
										, DATEDIFF(h.data_pagamento, DATE(now())) diferenca
										, DAY(h.data_pagamento) dia
									FROM 
										login l INNER JOIN historico_cobranca h ON l.id = h.id 
												INNER JOIN dados_cobranca d ON h.id = d.id 
									WHERE 
										DATEDIFF(h.data_pagamento, DATE(now())) between 0 AND 5 
										AND h.status_pagamento IN ('a vencer') 
										AND h.envio_email <> 'enviado'
										AND l.status = 'demo'
									";
	$rsDemos_a_vencer = mysql_query($sql_checa_demos_a_vencer)
	or die (mysql_error());


	while($demos_a_vencer=mysql_fetch_array($rsDemos_a_vencer)){


		//Componente de Envio de e-mail.
		$dia_vencimento = $demos_a_vencer["dia"];
		$dias_a_vencer = $demos_a_vencer["diferenca"];
		$Assinante = $demos_a_vencer["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = date('d/m/Y',strtotime($demos_a_vencer["data_pagamento"]));
		$emailuser = 'fabio.ribeiro@gmail.com';//$demos_a_vencer["email"];		
		$assuntoMail = "Período de avaliação prestes a vencer";
		include '../mensagens/demo_a_vencer.php';
		//include '../mensagens/componente_envio.php';
		include '../mensagens/componente_envio_novo.php';
	

	}

	/*
	####################################################################
	ENVIANDO EMAILS COM INSTRUÇÕES PARA ATIVAÇÃO DOS DEMO
	####################################################################
	*/
	
	
			
	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS JUBILADOS
	####################################################################
	*/
	
		//$sqlUpdateJubilados = "UPDATE `historico_cobranca` SET status_pagamento = 'jubilado' WHERE DATEDIFF(data_pagamento, DATE(now())) < -90 AND status_pagamento IN ('não pago')";
		//mysql_query($sqlUpdateJubilados);

	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS JUBILADOS
	####################################################################
	*/
	
	





	/*
	####################################################################
	QUERY QUE RETORNA OS IDS DOS CLIENTES QUE DEVERÃO SER COBRADOS POR CARTÃO
	####################################################################
	*/
	$sql = "SELECT 
				distinct l.id idUser, l.status, d.forma_pagameto
			FROM 
			   	login l
			INNER JOIN 
			   	historico_cobranca h ON l.id = h.id
			INNER JOIN 
				dados_cobranca d ON h.id = d.id 
			WHERE 
			   (l.status <> 'cancelado' AND l.status <> 'demoInativo')
			   AND h.status_pagamento IN ('vencido')
			";
// 				EM 30/09/2013 - foi solicitado que fizesse a cobrança do cartão somente para pagamentos vencidos - AND h.status_pagamento IN ('vencido','não pago')
//			   	AND h.tipo_cobranca IN ('visa','mastercard')
	$resultado = mysql_query($sql)
	or die (mysql_error());
	/*
	####################################################################
	QUERY QUE RETORNA OS IDS DOS CLIENTES QUE DEVERÃO SER COBRADOS POR CARTÃO
	####################################################################
	*/

	while ($linha=mysql_fetch_array($resultado)) {
		
		// PEGANDO O ULTIMO NAO PAGO OU VENCIDO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
		$linhaData = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='" . $linha["idUser"] . "' AND status_pagamento IN ('vencido', 'não pago') ORDER BY idHistorico  DESC LIMIT 0,1"));

		$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linhaData["data_pagamento"]))+1,date('d',strtotime($linhaData["data_pagamento"])),date('Y',strtotime($linhaData["data_pagamento"])))));

				
		$acaoRealizada = "";
		$sql_pagamentos_cartao = "SELECT 
									h.id
									, h.status_pagamento
									, h.idHistorico
									, h.data_pagamento
									, d.forma_pagameto
									, d.numero_cartao
									, l.email
									, l.assinante
								FROM 
									login l
								INNER JOIN 
									historico_cobranca h ON l.id = h.id
								INNER JOIN 
									dados_cobranca d ON h.id = d.id 
								WHERE 
								   l.id='" . $linha["idUser"] . "'
									 AND h.status_pagamento IN ('vencido')
				";

				// EM 30/09/2013 - foi solicitado que fizesse a cobrança do cartão somente para pagamentos vencidos - AND h.status_pagamento IN ('vencido','não pago')
				//AND d.forma_pagameto IN ('visa','mastercard')
		$rs_pagamentos_cartao = mysql_query($sql_pagamentos_cartao)
		or die (mysql_error());

	
		// TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
		$total_devendo = mysql_num_rows($rs_pagamentos_cartao);

	
		// SE HOUVER SOMENTE UM PAGAMENTO A SER FEITO
		if($total_devendo == 1){

			// DEFININDO O TOTAL A SER COBRADO PADRÃO
			$pagamentos_cartao = mysql_fetch_array($rs_pagamentos_cartao);
			$valor_a_cobrar = $mensalidade . "00";
			$valor_pago = (int)($mensalidade);

			// MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
			$idHistoricoAtualizar = "('" . $pagamentos_cartao['idHistorico'] .  "')";

			$numeroCartao = ($pagamentos_cartao['numero_cartao'] ? retornaNumeroCartaoMascara($pagamentos_cartao['numero_cartao']) : '');
			$forma_pagamento_assinante = $pagamentos_cartao['forma_pagameto'];
			$assinante = $pagamentos_cartao['assinante'];
			$email_assinante = $pagamentos_cartao['email'];

			
		}else{

			// CALCULANDO O TOTAL A SER COBRADO COM O TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
			$valor_a_cobrar = (string)((int)($mensalidade) * $total_devendo) . "00";
			$valor_pago = (int)((int)($mensalidade) * $total_devendo);

			// MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
			$idHistoricoAtualizar = "('";
			while($pagamentos_cartao = mysql_fetch_array($rs_pagamentos_cartao)){

				$forma_pagamento_assinante = $pagamentos_cartao['forma_pagameto'];
				$numeroCartao = ($pagamentos_cartao['numero_cartao'] ? retornaNumeroCartaoMascara($pagamentos_cartao['numero_cartao']) : '');
				$assinante = $pagamentos_cartao['assinante'];
				$email_assinante = $pagamentos_cartao['email'];

				$idHistoricoAtualizar .= $pagamentos_cartao['idHistorico'] . "','";
				
			}
			$idHistoricoAtualizar .= "')";
			
			$idHistoricoAtualizar  = str_replace(",''","",$idHistoricoAtualizar);
			
		}
	

		if(($forma_pagamento_assinante == 'visa') || ($forma_pagamento_assinante == 'mastercard')){	
	
			$sql_cobranca_erro_mesmo_dia = "SELECT count(*) total FROM relatorio_cobranca WHERE id = '" . $linha["idUser"] . "' AND data = '" . date('Y-m-d') . "' AND resultado_acao IN ('2.2','2.3')";	
			
			$rs_cobranca_erro_mesmo_dia = mysql_query($sql_cobranca_erro_mesmo_dia)
			or die (mysql_error());
			
			$linha_cobranca_erro_mesmo_dia = mysql_fetch_array($rs_cobranca_erro_mesmo_dia);



		
							//Envio de e-mail alertando a cobrança em seu cartão.
							$Assinante = $assinante;
							$AssinanteExplode = explode(" ", $Assinante);
							$emailuser = 'fabio.ribeiro@gmail.com';//$email_assinante;
							
							$valor_boleto = $mensalidade_formatada;
				
							$assuntoMail = "Aviso de cobrança de assinatura";
							include '../mensagens/cartao_confirmado.php';
							//include '../mensagens/componente_envio.php';
							include '../mensagens/componente_envio_novo.php';
			
					
							//Componente de Envio de e-mail.
							$Assinante = $assinante;
							$AssinanteExplode = explode(" ", $Assinante);
							$emailuser = 'fabio.ribeiro@gmail.com';//$email_assinante;
							$assuntoMail = "Erro ao cobrar sua assinatura";
							include '../mensagens/cartao_erro.php';
							//include '../mensagens/componente_envio.php';	
							include '../mensagens/componente_envio_novo.php';
			

		
			/*
			####################################################################
			FIM DO TRATAMENTO DO RETORNO DE ERRO DO COMPONENTE DE PAGAMENTO
			####################################################################
			*/		
	
		}
		/*
		####################################################################
		 FIM DA COBRANÇA POR CARTÃO
		####################################################################
		*/		


		
		// CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
		$sqlChecaAVencer = "SELECT * FROM historico_cobranca WHERE id='" . $linha["idUser"] . "' AND status_pagamento='a vencer'
							LIMIT 0,1";
		$resultadoChecaAVencer = mysql_query($sqlChecaAVencer)
		or die (mysql_error());
//		printf("<br>".$sqlChecaAVencer."<BR>");

		// CHECANDO SE JÁ EXISTE HISTÓRICO PAGO
		$sqlChecaPago = "SELECT * FROM historico_cobranca WHERE id='" . $linha["idUser"] . "' AND status_pagamento='pago'
							LIMIT 0,1";
		$resultadoChecaPago = mysql_query($sqlChecaPago)
		or die (mysql_error());
//		printf("<br>".$sqlChecaAVencer."<BR>");
		
		// CHECANDO SE HÁ PAGAMENTO COM STATUS DE vencido PARA BLOQUEAR A INCLUSÃO DE UM NOVO A VENCER
		// EM 21/01/2014 - FOI TIRADA ESSA CHECAGEM POIS DEVE SER INSERIDO UM NOVO a vencer SOMENTE SE NÃO HOUVER NENHUM a vencer SOMENTE
		//$sqlChecaVencidos = "SELECT * FROM historico_cobranca WHERE id='" . $linha["idUser"] . "' AND status_pagamento='vencido'";
		//$resultadoChecaVencidos = mysql_query($sqlChecaVencidos)
		//or die (mysql_error());
		
		
	
	

	}
	/*
	####################################################################
	LOOP PARA PROCEDER COM A COBRANÇA
	####################################################################
	*/		




	/*
	####################################################################
	DESATIVAÇÃO DE USUARIOS
	####################################################################
	*/

	// CHECANDO NO HISTORICO DE COBRANCA SE HÁ REGISTRO NÃO PAGO HA MAIS DE 5 DIAS PARA DETERMINAR SE DEVE DESATIVAR O USUARIO
	$sql_checa_desativacao = "
						SELECT 
							DISTINCT 
							l.id
							, l.email
							, l.assinante
							, l.status
							, d.forma_pagameto
						FROM 
							historico_cobranca h
						INNER JOIN 
							login l ON h.id = l.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
						WHERE 
							(SELECT count(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento = 'não pago') > 0
							AND l.status <>  'cancelado'
	";
	$rs_checa_desativado = mysql_query($sql_checa_desativacao);
	while($checa_desativacao = mysql_fetch_array($rs_checa_desativado)){
				
		switch($checa_desativacao['status']){
			
			case 'ativo':
	
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $checa_desativacao['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = 'fabio.ribeiro@gmail.com';//$checa_desativacao['email'];
				$assuntoMail = "Conta Inativa";
				include '../mensagens/conta_inativa.php';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';
	
							
				// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$novo_status = 'inativo';
	
				// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$acao_relatorio = '3';
				
				$atualiza_login = true;
				$insere_relatorio = true;

		
			break;
			
			case 'demo':
	
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $checa_desativacao['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = 'fabio.ribeiro@gmail.com';//$checa_desativacao['email'];
				$assuntoMail = "Período de avaliação expirado";
				include '../mensagens/demo_inativo.php';
				//include '../mensagens/componente_envio.php';
				include '../mensagens/componente_envio_novo.php';

							
				// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$novo_status = 'demoInativo';
	
				// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$acao_relatorio = '4';
				
				$atualiza_login = true;
				$insere_relatorio = false;

			break;
			
			case 'inativo':

				$atualiza_login = false;
				$insere_relatorio = false;

			
			break;

			case 'demoInativo':

				$atualiza_login = false;
				$insere_relatorio = false;
			
			break;
								
		}
		
	}
	/*
	####################################################################
	DESATIVAÇÃO DE USUARIOS
	####################################################################
	*/



	/*
	####################################################################
	ATIVAÇÃO DE USUARIOS
	####################################################################
	*/

	// CHECANDO USUARIOS PARA DETERMINAR SE DEVE ATIVAR
	$sql_checa_ativacao = "
						SELECT 
							DISTINCT 
							l.id
							, l.email
							, l.assinante
							, l.status
							, d.forma_pagameto
						FROM 
							historico_cobranca h
						INNER JOIN 
							login l ON h.id = l.id
						INNER JOIN 
							dados_cobranca d ON h.id = d.id 
						WHERE 
							0 = (SELECT count(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento IN ('não pago'))
							AND 0 = (SELECT count(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento IN ('vencido'))
							AND l.status <>  'cancelado'
	";
/*					CLAUSULA WHERE ANTERIOR - 30/01/2014 foi alterada	
						WHERE 
							0 = (SELECT count(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento IN ('não pago'))
							AND (SELECT count(*) FROM historico_cobranca WHERE id = l.id AND status_pagamento IN ('pago')) > 0
							AND l.status <>  'cancelado'
*/
	$rs_checa_ativado = mysql_query($sql_checa_ativacao);
	while($checa_ativacao = mysql_fetch_array($rs_checa_ativado)){

		switch($checa_ativacao['status']){
			
			case 'ativo':
	
				$atualiza_login = false;
				$insere_relatorio = false;
				
			break;
			
			case 'demo':
	
				// CHECANDO SE O USUARIO DEMO NÃO TEM PAGAMENTOS FEITOS - SE HOUVER, O STATUS DELE DEVE IR PARA ATIVO
				$sql_checa_demo = "
									SELECT 
										count(*) total
									FROM
										historico_cobranca
									WHERE 
										id = " . $checa_ativacao['id'] . "
										AND status_pagamento = 'pago'
				";
				$qtd_checa_demo = mysql_fetch_array(mysql_query($sql_checa_demo));
				if((int)$qtd_checa_demo['total'] > 0){

					// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
					$novo_status = 'ativo';
		
					// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
					$acao_relatorio = '5';
					
					$atualiza_login = true;
					$insere_relatorio = false;
	
				}else{

					$atualiza_login = false;
					$insere_relatorio = false;
					
				}
				
			break;
			
			case 'inativo':

				$sql_retorna_valor = "
						SELECT
							valor_pago 
						FROM
							relatorio_cobranca
						WHERE id = " . $checa_ativacao['id'] . "
						AND data = '" . date('Y-m-d') . "'
						AND resultado_acao IN ('1.2','2.1')
				";
				$rs_retorna_valor = mysql_query($sql_retorna_valor);
				
				if($linha_valor = mysql_fetch_array($rs_retorna_valor)){
					$retorno_valor = (int)$linha_valor['valor_pago'] . "00";
					
					//Envio de e-mail alertando o usuário sobre a inatividade.
					$Assinante = $checa_ativacao['assinante'];
					$AssinanteExplode = explode(" ", $Assinante);
					$emailuser = 'fabio.ribeiro@gmail.com';//$checa_ativacao['email'];
					$assuntoMail = "Reativação de conta";
					include '../mensagens/conta_reativada.php';
					//include '../mensagens/componente_envio.php';
					include '../mensagens/componente_envio_novo.php';
		
					// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
					$novo_status = 'ativo';
		
					// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
					$acao_relatorio = '5';
					
					$atualiza_login = true;
					$insere_relatorio = false;
	
				}else{

					$atualiza_login = false;
					$insere_relatorio = false;
					// LOG DE ACESSOS
					//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - inativo NÃO POSSUI PAGAMENTOS')");
					
					
				}
			break;

			case 'demoInativo':

				$sql_retorna_valor = "
						SELECT
							valor_pago 
						FROM
							relatorio_cobranca
						WHERE id = " . $checa_ativacao['id'] . "
						AND data = '" . date('Y-m-d') . "'
						AND resultado_acao IN ('1.2','2.1')
				";
				$rs_retorna_valor = mysql_query($sql_retorna_valor);

				if($linha_valor = mysql_fetch_array($rs_retorna_valor)){
					$retorno_valor = (int)$linha_valor['valor_pago'] . "00";

					//Envio de e-mail alertando o usuário sobre a inatividade.
					$Assinante = $checa_ativacao['assinante'];
					$AssinanteExplode = explode(" ", $Assinante);
					$emailuser = 'fabio.ribeiro@gmail.com';//$checa_ativacao['email'];
					$assuntoMail = "Reativação de conta";
					include '../mensagens/conta_reativada.php';
					//include '../mensagens/componente_envio.php';
					include '../mensagens/componente_envio_novo.php';
					
		
								
					// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
					$novo_status = 'ativo';
		
					// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
					$acao_relatorio = '5';
					
					$atualiza_login = true;
					$insere_relatorio = false;
	
				}else{

					$atualiza_login = false;
					$insere_relatorio = false;
					// LOG DE ACESSOS
					//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - demoInativo NÃO POSSUI PAGAMENTOS')");
					
				}
				
			break;
								
		}

		
	}
	/*
	####################################################################
	ATIVAÇÃO DE USUARIOS
	####################################################################
	*/

	/*
	####################################################################
	LOOP PARA ALTERAÇÃO DE STATUS DE LOGINS
	####################################################################
	*/		
	


?>
<br />
<br />