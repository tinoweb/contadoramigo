<?php 
session_start();

include('../classes/phpmailer.class.php');
include('../classes/config.php');
include('../classes/pagamento.php');
include('cobranca.class.php');
include 'check_login.php';
include 'header.php';

include 'gerar-boletos-antecipados.class.php';

if($_GET['toggleNF'] != '')
{
	$url = str_replace("?toggleNF","#",str_replace("&toggleNF","#",$_SERVER['REQUEST_URI']));
	
	// TIRANDO O ID DO PAGAMENTO DA URL
	$arrURI = explode('#',$url);

	mysql_query('UPDATE `relatorio_cobranca` SET emissao_NF = case when `emissao_NF` = 0 then 1 else 0 end WHERE idRelatorio = ' . $_GET['toggleNF'] . ' LIMIT 1');

	header('location:'.$arrURI[0]);
}

//RECUPERA VALOR DA MENSALIDADE
$Config = new Config();
$mensalidade = $Config->verValor("mensalidade");
$mensalidade_formatada = number_format($mensalidade,2,",",".");

ob_start();

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
funções de processamento da cobrança
*/
// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5

// ########################################################################################################
ini_set('allow_url_fopen', 1); // Ativa a diretiva 'allow_url_fopen'

set_time_limit(0);// to infinity for example

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
	
// Pega o plano incluso nos boletos registrados.
function pegaPlanoBoletosRegistrados($nossoNumero){
	
	$qry = "SELECT tipo, tipo_plano, plano FROM `boletos_registrados` WHERE nosso_numero = '".$nossoNumero."'";
	
	$resultado = mysql_query($qry) or die (mysql_error());
	
	return $resultado;
}	

function ProcessaArquivoRetorno($vTemp_name, $vNome_arquivo, $ignora_upload, $vMensalidade, $vShow_log){	
	$retorno = "";
	
	// ATRIBUINDO O ARQUIVO À VARIÁVEL
	$arquivo = file($vNome_arquivo);		
	$total_linhas_arquivo = (int)(substr($arquivo[count($arquivo)-1],23,6));
	$total_pagamentos = (($total_linhas_arquivo - 4)/2);

	// PROCESSANDO O CABEÇALHO (LINHAS 1 E 2)
	$data_pagamento = (substr($arquivo[0],143,8));

	$data_pagto = date('Y-m-d',strtotime((substr($data_pagamento,4,4)) . "-" . substr($data_pagamento,2,2) . "-" . substr($data_pagamento,0,2)));
	$data_hoje = date('Y-m-d');
	
	// VARIAVEL QUE CONTROLA QUANTOS PAGAMENTOS FORAM PROCESSADOS PARA CHECAGEM FINAL DA QUANTIDADE DE PAGAMENTOS PELA QUANTIDADE DE PAGAMENTOS PROCESSADOS
	$pagamentos_processados = 0;
	
	// ############################## LOG ###############################
	if($vShow_log == true)
	{
		echo "PROCESSANDO ARQUIVO DE RETORNO " . ($vNome_arquivo) . "<BR><BR>";
			ob_flush();
	}
	// ############################## LOG ###############################

	// ############################## LOG ###############################
	if($vShow_log == true)
	{
		echo "Início do loop para processar baixa de Boleto <BR><BR>";
			ob_flush();
	}
	// ############################## LOG ###############################	

	// PERCORRENDO O ARQUIVO - TIRANDO AS 2 PRIMEIRAS (CABEÇALHO) E AS 2 ULTIMAS (RODAPE) LINHAS PARA TRAZER DADOS REFERENTES AO PAGAMENTO
	for($i=0; $i<=($total_linhas_arquivo); $i++)
	{
		$enviarEmail = false;

		
		// CHEGANDO SE A LINHA EM QUESTÃO É DE PAGAMENTO (POSSUI O "T" NA POSIÇÃO 13)
		if(substr($arquivo[$i],13,1) == "T"){
			//*******MUDANCA PROVISORIA PARA QUE ID COM 5 ALGARISMOS POSSAM SER LIDOS. TIRAMOS 2 ALGARISMOS DO ANO PRA SOBRAR 6 ALGARISMOS NO ID			
				
			if(substr($arquivo[$i+1],13,1) == "U"){
				$valor_real_pago = floatval(substr($arquivo[$i+1],77,15));
			}

			$status_boleto = (int)(substr($arquivo[$i],15,2));

			//ira sumir
			$id_user = (int)(substr($arquivo[$i],44,6));
			//ira sumir
			$mes_boleto = (string)(substr($arquivo[$i],50,2));
			//ira sumir
			$ano_boleto = (string)(substr($arquivo[$i],52,2));
			//ira sumir
			$ano_boleto = '20'.$ano_boleto;
			//Pega o valor que realmente foi pago, incluindo os juros e multa
			$valor_pago = $valor_real_pago;
			// $valor_pago = (int)(substr($arquivo[$i],81,15));
			$valor_pago = floatval($valor_pago / 100);
			$valor_total_pago = $valor_pago;
			$data_historico_aux = $ano_boleto.$mes_boleto;

			//Pega o nosso número
			$nosso_numero = (string)(substr($arquivo[$i],37,17));
			
			// Pega os dados do plano registrados no boleto.
			$planoAssinado = mysql_fetch_array(pegaPlanoBoletosRegistrados($nossoNumero));
			$tipoBoleto = (isset($planoAssinado['tipo']) && !empty($planoAssinado['tipo']) ? $planoAssinado['tipo'] : 'mensalidade');
			$tipo_plano = (isset($planoAssinado['tipo_plano']) && !empty($planoAssinado['tipo_plano']) ? $planoAssinado['tipo_plano'] : 'S');
			$plano_assinado = (isset($planoAssinado['plano']) && !empty($planoAssinado['plano']) ? $planoAssinado['plano'] : 'mensalidade');

			//Condição provisoria para identificar os boletos antigos, retirar quando 
			if( true ){
				//Itens com status 6 ou 17 sao pagamentos que foram registrados na conta
				if( ( $status_boleto == 6 || $status_boleto == 17 ) ){
					$boleto_cobranca = new Cobranca_boleto();
					//Define o Nosso Número do boleto
					$boleto_cobranca->setnosso_numero($nosso_numero);
					//Define o valor pago no boleto
					$boleto_cobranca->setvalor_pago($valor_total_pago);
					//Valida o boleto
					$boleto_cobranca->validarBoleto();
				}
				else if( $status_boleto == 3 || $status_boleto == 2 || $status_boleto == 9 ){

					$boleto_cobranca = new Cobranca_boleto();
					//Define o Nosso Número do boleto
					$boleto_cobranca->setnosso_numero($nosso_numero);
					// //Define o valor pago no boleto
					$boleto_cobranca->setarStatusRemessaRetorno($status_boleto);
				}
			}//Todo o conteudo do else ira sumir
			else{
				//Status 6 indica LIQUIDAÇÃO DE BOLETO, status 17 indica LIQUIDAÇÃO DE BOLETO NÃO REGISTRADO.
				//Ignorar status 02 - Entrada confirmada da remessa, 03 - Entrada Rejeitada da remessa.
				
				// if( ( $status_boleto == 6 || $status_boleto == 17 ) && $nosso_numero_validade  ){
				if( ( $status_boleto == 6 || $status_boleto == 17 ) ){
				
					// PEGANDO DADOS DO USUARIO NO BANCO DE DADOS
					$sql_dados_usuario = "SELECT login.status, dados_cobranca.plano FROM login INNER JOIN dados_cobranca ON login.id = dados_cobranca.id WHERE login.id = '" . $id_user . "'";

					$rs_dados_usuario = mysql_query($sql_dados_usuario);
					$linha_dados_usuario = mysql_fetch_array($rs_dados_usuario);
					$status_usuario = $linha_dados_usuario['status'];
					$plano_usuario = $linha_dados_usuario['plano'];
					$loop_pagamentos = (int)($valor_pago / $vMensalidade);
					
					$Config = new Config(); //OBJETO DE CONFIGURAÇÃO

					$Pagamento = new Pagamento(); //OBJETO DOS PAGAMENTOS

					//Carrega o número de meses do plano
					$plano_meses = $Config->verMeses($plano_usuario);

					if ($plano_meses > 1)
					{				
						//Recupera total de empresas
						$rsTotalEmpresas = mysql_fetch_array(mysql_query("SELECT COUNT(*) total_empresas FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = '" . $id_user . "' AND e.ativa = 1"));
						$total_empresas = $rsTotalEmpresas['total_empresas'];

						//RECEBE VALOR DO PLANO
						$plano_valor = $Config->verValor($plano_usuario);

						//CALCULA VALOR QUE CLIENTE DEVERÁ PAGAR
						$valor_pago_calc = $Config->calcularValorEmpresas($plano_valor, $total_empresas);				

						//VERIFICA SE VALOR DO BOLETO É O DO VALOR PAGO DO BOLETO. ISSO EVITA QUE CLIENTE BURLE O SISTEMA PAGANDO UM VALOR INFERIOR AO DO PLANO
						if ( false )
						{
							echo ("A");
							exit;
										
							// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTA INCONSISTENCIA
							$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id,idHistorico , data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, observacao, tipo_plano, plano) VALUES ('" . $id_user . "', '".$data_historico_aux."' ,'" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', '', " . $valor_pago . ",'Boleto com valor diferente do plano', '".$tipo_plano."', '".$plano_assinado."')";
							$resultadoup = mysql_query($sqlRelCobranca)
							or die (mysql_error());
							
							// Pega o ultimo código do relatorio de cobrança.
							$idRelatorio = mysql_insert_id();
							
							// Verifica se a conta e prêmio.
							if($tipo_plano == 'P') {
								$qryUpdate = " UPDATE cobranca_contador cc "
										." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
										." SET idRelatorio = ".$idRelatorio 
										." WHERE br.nosso_numero = ".$nosso_numero."; ";
								
								mysql_query($qryUpdate);
							}
						}
						else
						{		
							

							// VERIFICA SE HÁ PAGAMENTOS EM ATRASO
							$sql_Atrasados = "SELECT idHistorico FROM historico_cobranca WHERE id = '" . $id_user . "' AND status_pagamento NOT IN ('pago','perdoado','a vencer') ORDER BY idHistorico";
							$rs_Atrasados  = mysql_query($sql_Atrasados);
							$totalAtrasados = mysql_num_rows($rs_Atrasados);

							//CALCULA QUANTOS MESES SERÃO SALVOS NO PRÓXIMO PAGAMENTO
							$meses_a_somar = $Pagamento->calcularMesesSomarBoleto($plano_meses, $totalAtrasados);
			

							if ($totalAtrasados > 0)
							{
								$totalAtrasadosCount = 0;
								while($linha=mysql_fetch_array($rs_Atrasados))
								{
									if ($totalAtrasadosCount <= $plano_meses)
									{							
										//ATUALIZA O HISTORICO DE COBRANCA
									 	$sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='boleto', envio_email='enviado' WHERE idHistorico='" . $linha['idHistorico'] . "'";
									 	$resultadoup = mysql_query($sqlup)
										or die (mysql_error());								
									}
									$totalAtrasadosCount++;
								}
							}
							
							if ($meses_a_somar > 0)
							{
								// CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
								$sqlChecaAVencer = "SELECT idHistorico FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento='a vencer' LIMIT 0,1";
								$resultadoChecaAVencer = mysql_query($sqlChecaAVencer)
								or die (mysql_error());

								if(mysql_num_rows($resultadoChecaAVencer) <= 0)
								{
									// SE NÂO FOR LOCALIZADO PAGAMENTO a vencer E NENHUM vencido, INSERE NO HISTORICO COM UM MES PARA FRENTE
									// EM 07/11/2013 - foi estipulado que o usuário Demo Inativo deve ter um tratamento diferente - a próxima data de pagamento sempre 30 dias a mais do plano
									if($status_usuario == "demoInativo")
									{								
										// ATUALIZA TODOS OS PAGAMENTOS QUE ESTARIAM COM NÃO PAGO PARA PERDOADO
										mysql_query("UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id = '" . $id_user . "' AND status_pagamento = 'não pago'");
										$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m')+$meses_a_somar,date('d'),date('Y'))));
									}
									else
									{
										// PEGANDO O ULTIMO PAGO OU VENCIDO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
										$linha = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento NOT IN ('a vencer') ORDER BY idHistorico  DESC LIMIT 0,1"));
										$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha["data_pagamento"]))+$meses_a_somar,date('d',strtotime($linha["data_pagamento"])),date('Y',strtotime($linha["data_pagamento"])))));
									}
								}
								else
								{
									//Recebe linha do pagamento a vencer
									$linhachecaAVencer = mysql_fetch_array($resultadoChecaAVencer);

									//FOI LOCALIZADO UM VENCIMENTO EM ABERTO, CLIENTE PAGOU ADIANTADO. REALIZANDO BAIXA DESSSE PAGAMENTO	
									$sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='boleto', envio_email='enviado' WHERE idHistorico='" . $linhachecaAVencer['idHistorico'] . "'";
								 	$resultadoup = mysql_query($sqlup)
									or die (mysql_error());

									// PEGANDO O ULTIMO PAGO OU VENCIDO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
									$linha = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento NOT IN ('a vencer') ORDER BY idHistorico  DESC LIMIT 0,1"));
									$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha["data_pagamento"]))+$meses_a_somar,date('d',strtotime($linha["data_pagamento"])),date('Y',strtotime($linha["data_pagamento"])))));														
								}

								//INSERINDO NOVO PAGAMENTO A VENCER
								mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $id_user . "', '" . $dataPagamento . "', 'a vencer')");

								// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTE PAGAMENTO
								$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, idHistorico , data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, tipo_planno, plano) VALUES ('" . $id_user . "', '".$data_historico_aux."' , '" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', 'enviado', " . $valor_pago . ", '".$tipo_plano."', '".$plano_assinado."')";
								$resultadoup = mysql_query($sqlRelCobranca)
								or die (mysql_error());
								
								// Pega o ultimo código do relatorio de cobrança.
								$idRelatorio = mysql_insert_id();
								
								// Verifica se a conta e prêmio.
								if($tipo_plano == 'P') {
									$qryUpdate = " UPDATE cobranca_contador cc "
											." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
											." SET idRelatorio = ".$idRelatorio 
											." WHERE br.nosso_numero = ".$nosso_numero."; ";
									
									mysql_query($qryUpdate);
								}
									
								// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
								$sqlEmail = "SELECT assinante, email, status FROM login WHERE id='" . $id_user . "' LIMIT 0,1";
								$resultadoEmail = mysql_query($sqlEmail)
								or die (mysql_error());
								
								$linhaEmail = mysql_fetch_array($resultadoEmail);
								
								//Envio de e-mail alertando o pagamento do boleto.
								$statusUser = $linhaEmail["status"];
								$Assinante = $linhaEmail["assinante"];
								$AssinanteExplode = explode(" ", $Assinante);
								$emailuser = $linhaEmail["email"];
								$valorEmail = $valor_pago;
								
								// INSERINDO OS DADOS DO ASSINANTE QUE FEZ O PAGAMENTO DO BOLETO NA TABELA DE ENVIO DE MENSAGENS
								mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'boleto_compensado', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");						
							}
						}								
					}
					else
					{
						if($mes_boleto == '00')
						{					
							// PEGANDO OS ULTIMOS PAGAMENTOS VENCIDOS OU NAO PAGOS - DEPENDE DO VALOR PAGO PARA PEGAR OS x ULTIMOS
							$sql = "SELECT * FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento NOT IN ('pago','perdoado') ORDER BY idHistorico LIMIT 0,".$loop_pagamentos;
							$resultado = mysql_query($sql)
							or die (mysql_error());

							$pagamentos_localizados = mysql_num_rows($resultado);

							// ############################## LOG ###############################
							if($vShow_log == true){
								echo "Boleto contendo mais de um pagamento. Foram localizados " . $pagamentos_localizados . " pagamento(s) - USUARIO " . ($id_user) . " - VALOR R$ " . $valor_pago . ",".$valor_decimal_pago."<BR><BR>";
									ob_flush();
							}
							// ############################## LOG ###############################
							
							/*
							FICOU DEFINIDO QUE SE O BOLETO SE REFERIR A MAIS DE UM PAGAMENTO E HOUVER MENOS PAGAMENTOS PASSÍVEIS DE ALTERAÇÃO DE STATUS DE PAGAMENTO, DEVE SER INSERIDO UM REGISTRO NO RELATORIO DE COBRANÇA INFORMANDO A INCONSISTÊNCIA OCORRIDA
							*/
							if($loop_pagamentos < $pagamentos_localizados)
							{						
								//Mensagem a exibir no relatório final.
								// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTA INCONSISTENCIA
								// EM 19/01/2015 - FOI SOLICITADO QUE FOSSE TIRADO OS STATUS 9.1 e 9.2 E COLOCASSE EM UMA COLUNA DE OBSERVAÇÃO
								$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, idHistorico ,data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, observacao, tipo_plano, plano) VALUES ('" . $id_user . "','".$data_historico_aux."' ,'" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', '', " . $valor_pago . ",'Boleto valor menor', '".$tipo_plano."', '".$plano_assinado."')";
								$resultadoup = mysql_query($sqlRelCobranca)
								or die (mysql_error());
								
								// Pega o ultimo código do relatorio de cobrança.
								$idRelatorio = mysql_insert_id();
								
								// Verifica se a conta e prêmio.
								if($tipo_plano == 'P') {
									$qryUpdate = " UPDATE cobranca_contador cc "
											." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
											." SET idRelatorio = ".$idRelatorio 
											." WHERE br.nosso_numero = ".$nosso_numero."; ";
									
									mysql_query($qryUpdate);
								}

								// ############################## LOG ###############################
								if($vShow_log == true){
									echo "###ATENÇÃO### - Foram localizados menos pagamentos que o valor que consta no boleto - USUARIO " . ($id_user) . "<BR><BR>";
										ob_flush();
								}
								// ############################## LOG ###############################
							}
							else
							{						
								if($loop_pagamentos > mysql_num_rows($resultado))
								{
			
									//Mensagem a exibir no relatório final.
									// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTA INCONSISTENCIA
									$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id,idHistorico ,data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, observacao, tipo_plano, plano) VALUES ('" . $id_user . "', '".$data_historico_aux."' ,'" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', '', " . $valor_pago . ",'Boleto valor maior', '".$tipo_plano."', '".$plano_assinado."')";
									$resultadoup = mysql_query($sqlRelCobranca)
									or die (mysql_error());	
									
									// Pega o ultimo código do relatorio de cobrança.
									$idRelatorio = mysql_insert_id();
									
									// Verifica se a conta e prêmio.
									if($tipo_plano == 'P') {
										$qryUpdate = " UPDATE cobranca_contador cc "
												." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
												." SET idRelatorio = ".$idRelatorio 
												." WHERE br.nosso_numero = ".$nosso_numero."; ";
										
										mysql_query($qryUpdate);
									}									

									// ############################## LOG ###############################
									if($vShow_log == true)
									{
										echo "###ATENÇÃO### - Foram localizados mais pagamentos do que o valor que consta no boleto - USUARIO " . ($id_user) . "<BR><BR>";
											ob_flush();
									}
									// ############################## LOG ###############################
								}
								else
								{
									$arrIdsHistorico = array();
									while($linha=mysql_fetch_array($resultado))
									{
								
										// ATUALIZA O HISTORICO DE COBRANCA
										 $sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='boleto', envio_email='enviado' WHERE idHistorico='" . $linha['idHistorico'] . "'";
					
										array_push($arrIdsHistorico,$linha['idHistorico']);

										$resultadoup = mysql_query($sqlup)
										or die (mysql_error());
									}

									// ############################## LOG ###############################
									if($vShow_log == true)
									{
										echo "Atualizando pagamentos (" . implode(", ",$arrIdsHistorico) . ") com status de PAGO - USUARIO " . ($id_user) . "<BR><BR>";
											ob_flush();
									}
									// ############################## LOG ###############################							
									
									//Mensagem a exibir no relatório final.
									// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTE PAGAMENTO
									$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id,  idHistorico, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, tipo_plano, plano) VALUES ('" . $id_user . "', '".$data_historico_aux."' ,'" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', 'enviado', " . $valor_pago . ", '".$tipo_plano."', '".$plano_assinado."')";
									$resultadoup = mysql_query($sqlRelCobranca)
									or die (mysql_error());
									
									// Pega o ultimo código do relatorio de cobrança.
									$idRelatorio = mysql_insert_id();
									
									// Verifica se a conta e prêmio.
									if($tipo_plano == 'P') {
										$qryUpdate = " UPDATE cobranca_contador cc "
												." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
												." SET idRelatorio = ".$idRelatorio 
												." WHERE br.nosso_numero = ".$nosso_numero."; ";
										
										mysql_query($qryUpdate);
									}									
									
									$enviarEmail = true;							

									// ############################## LOG ###############################
									if($vShow_log == true)
									{
										echo "Inserindo no Relatório de Cobrança - USUARIO " . ($id_user) . "<BR><BR>";
											ob_flush();
									}
									// ############################## LOG ###############################						
								}
							}
						}
						else
						{
							$sql_checaMesPago = "SELECT * FROM historico_cobranca WHERE MONTH(data_pagamento) = '" . $mes_boleto . "' AND YEAR(data_pagamento) = '" . $ano_boleto . "' AND id = '" . $id_user . "' AND status_pagamento IN ('pago') ORDER BY idHistorico";
							$rs_checaMesPago = mysql_query($sql_checaMesPago);
							
							// ############################## LOG ###############################
							if($vShow_log == true){
								echo "Checando se há pagamento baixado no mês " . $mes_boleto . "-" . $ano_boleto . " - USUARIO " . ($id_user) . "<BR><BR>";
									ob_flush();
							}
							// ############################## LOG ###############################

							// SE NÃO TEM BOLETO PAGO NO MES
							if(mysql_num_rows($rs_checaMesPago) <= 0)
							{
								$sql_checamesboleto = "SELECT * FROM historico_cobranca WHERE MONTH(data_pagamento) = '" . $mes_boleto . "' AND YEAR(data_pagamento) = '" . $ano_boleto . "' AND id = '" . $id_user . "' AND status_pagamento NOT IN ('pago','perdoado') ORDER BY idHistorico";
								$rs_checaMesBoleto = mysql_query($sql_checamesboleto);
								// CHECANDO SE LOCALIZA O MES A SER QUITADO
								if(mysql_num_rows($rs_checaMesBoleto) > 0)
								{
									// ############################## LOG ###############################
									if($vShow_log == true){
										echo "Dando baixa no pagamento de " . $mes_boleto . "-" . $ano_boleto . " - USUARIO " . ($id_user) . "<BR><BR>";
											ob_flush();
									}
									// ############################## LOG ###############################
			
									$checaMesBoleto = mysql_fetch_array($rs_checaMesBoleto);	
									$sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='boleto', envio_email='enviado' WHERE MONTH(data_pagamento) = '" . $mes_boleto . "' AND YEAR(data_pagamento) = '" . $ano_boleto . "' AND id = '" . $id_user . "'";
									$resultadoup = mysql_query($sqlup)
									or die (mysql_error());
				
									//Mensagem a exibir no relatório final.
									// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTE PAGAMENTO
									$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, idHistorico , data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, tipo_plano, plano) VALUES ('" . $id_user . "', '".$data_historico_aux."' , '" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', 'enviado', " . $valor_pago . ", '".$tipo_plano."', '".$plano_assinado."')";
									$resultadoup = mysql_query($sqlRelCobranca)
									or die (mysql_error());
									
									// Pega o ultimo código do relatorio de cobrança.
									$idRelatorio = mysql_insert_id();
									
									// Verifica se a conta e prêmio.
									if($tipo_plano == 'P') {
										$qryUpdate = " UPDATE cobranca_contador cc "
												." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
												." SET idRelatorio = ".$idRelatorio 
												." WHERE br.nosso_numero = ".$nosso_numero."; ";
										
										mysql_query($qryUpdate);
									}
									
									$enviarEmail = true;
								}
								else
								{
									
									// Resultado da acao definida de acordo com o boleto.
									switch($tipoBoleto) {
										
										case 'AbertAltEmpresa':
												$resultadoAcao = '9.4';
											break;
										case 'AbertAltSociedade':		
												$resultadoAcao = '9.5';
											break;
										case 'decore':
												$resultadoAcao = '9.6';
											break;
											
										case 'DBE':
												$resultadoAcao = '9.7';
											break;
											
										default:
												$resultadoAcao = '9.3';
											break;
									}

									//Mensagem a exibir no relatório final.
									// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ERRO NA DATA DO BOLETO DESTE PAGAMENTO
									$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, idHistorico , data, data_pagamento,tipo_cobranca, resultado_acao, envio_email, valor_pago, tipo_plano, plano) VALUES ('" . $id_user . "','".$data_historico_aux."' , '" . $data_hoje . "','" . $data_hoje . "', 'boleto', '".$resultadoAcao."', '', " . $valor_pago . ", '".$tipo_plano."', '".$plano_assinado."')";
									$resultadoup = mysql_query($sqlRelCobranca)
									or die (mysql_error());
									$idRelatorio = mysql_insert_id();
									
									$arrayAcao = array('9.4','9.5','9.6','9.7');
									
									// Verifica o resultado da ação para poder gravar os dados na tabela de contador cobranca.
									if(in_array($resultadoAcao, $arrayAcao)) {
										
										$qryUpdate = " UPDATE cobranca_contador cc "
												." JOIN boletos_registrados br ON cc.boletosRegistradosId = br.id "
												." SET idRelatorio = ".$idRelatorio 
												." WHERE br.nosso_numero = ".$nosso_numero."; ";
										
										mysql_query($qryUpdate);
									}

									// ############################## LOG ###############################
									if($vShow_log == true)
									{
										echo "###ATENÇÃO### - Não foi localizado boleto para esta data " . $mes_boleto . "-" . $ano_boleto . " - USUARIO " . ($id_user) . "<BR><BR>";
											ob_flush();
									}
									// ############################## LOG ###############################	
								}

								if($enviarEmail == true)
								{
									// PEGANDO O EMAIL E O NOME DO USUARIO PARA ENVIO DE EMAIL
									$sqlEmail = "SELECT assinante, email, status FROM login WHERE id='" . $id_user . "' LIMIT 0,1";
									$resultadoEmail = mysql_query($sqlEmail)
									or die (mysql_error());
									
									$linhaEmail = mysql_fetch_array($resultadoEmail);
									
									//Envio de e-mail alertando o pagamento do boleto.
									$statusUser = $linhaEmail["status"];
									$Assinante = $linhaEmail["assinante"];
									$AssinanteExplode = explode(" ", $Assinante);
									$emailuser = $linhaEmail["email"];
									$valorEmail = $valor_pago;
									
									// INSERINDO OS DADOS DO ASSINANTE QUE FEZ O PAGAMENTO DO BOLETO NA TABELA DE ENVIO DE MENSAGENS
									mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'boleto_compensado', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");
								}
								
								// CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
								$sqlChecaAVencer = "SELECT * FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento='a vencer' LIMIT 0,1";
								$resultadoChecaAVencer = mysql_query($sqlChecaAVencer)
								or die (mysql_error());

								// CHECANDO SE HÁ PAGAMENTO COM STATUS DE vencido PARA BLOQUEAR A INCLUSÃO DE UM NOVO A VENCER
								$sqlChecaVencidos = "SELECT * FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento='vencido'";
								$resultadoChecaVencidos = mysql_query($sqlChecaVencidos)
								or die (mysql_error());

								if(mysql_num_rows($resultadoChecaAVencer) <= 0)
								{
									// EM 07/11/2013 - foi estipulado que o usuário Demo Inativo deve ter um tratamento diferente - a próxima data de pagamento será a do dia de hoje mais 30 dias
									if($status_usuario == "demoInativo")
									{
										$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m')+1,date('d'),date('Y'))));
										// ATUALIZA TODOS OS PAGAMENTOS QUE ESTARIAM COM NÃO PAGO PARA PERDOADO
										mysql_query("UPDATE historico_cobranca SET status_pagamento = 'perdoado' WHERE id = '" . $id_user . "' AND status_pagamento = 'não pago'");
									}
									else
									{
										// PEGANDO O ULTIMO PAGO OU VENCIDO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
										$linha = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento NOT IN ('a vencer') ORDER BY idHistorico  DESC LIMIT 0,1"));
										$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linha["data_pagamento"]))+1,date('d',strtotime($linha["data_pagamento"])),date('Y',strtotime($linha["data_pagamento"])))));
									}							
									mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $id_user . "', '" . $dataPagamento . "', 'a vencer')");
								}
							}										
						}
					}
				}//Fim verificação do status do boleto	
			}		

			$pagamentos_processados++;

		}
	}

	// ############################## LOG ###############################
	if($vShow_log == true)
	{
		echo "Fim do loop para processar baixa de Boleto <BR><BR>";
			ob_flush();
	}
	// ############################## LOG ###############################

	// FIM DO FOR - PERCORRENDO O ARQUIVO - TIRANDO AS 2 PRIMEIRAS (CABEÇALHO) E AS 2 ULTIMAS (RODAPE) LINHAS PARA TRAZER DADOS REFERENTES AO PAGAMENTO
	
	// CHECAGEM FINAL 
	if($total_pagamentos === $pagamentos_processados)
	{
		// ATUALIZANDO OS DADOS DO ARQUIVO NO BANCO
		mysql_query("UPDATE arquivos_retorno_banco SET data_processamento = '" . date('Y-m-d H:i:s') . "', status = 'processado' WHERE nome = '" . str_replace('arquivos_retorno/','',$vNome_arquivo) . "'");
	}
	else
	{
		mysql_query("UPDATE arquivos_retorno_banco SET data_processamento = '" . date('Y-m-d H:i:s') . "', status = 'processado com erro' WHERE nome = '" . str_replace('arquivos_retorno/','',$vNome_arquivo) . "'");
	}
}

/*
funções de processamento da cobrança
*/
	
// VARIAVEIS QUE CONTROLAM O FILTRO
$tid = $_GET["tid"];

$id = $_GET["id"];

//$get_assinante = $_GET["assinante"];
$get_email = $_GET["email"];

$data_inicio_filtro = '';
$data_fim_filtro = '';


$dataInicio = $_GET["dataInicio"];
if ($dataInicio == "") {
	$dataInicio = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));
}
else{
	$data_inicio_filtro = explode('-',$dataInicio);
	$data_inicio_filtro = $data_inicio_filtro[2].'/'.$data_inicio_filtro[1].'/'.$data_inicio_filtro[0];
}

$dataFim = $_GET["dataFim"];
if ($dataFim == "") {
	$dataFim = date("Y-m-d");
}
else{
	$data_fim_filtro = explode('-',$dataFim);
	$data_fim_filtro = $data_fim_filtro[2].'/'.$data_fim_filtro[1].'/'.$data_fim_filtro[0];
}

$resultadoAcao = $_GET["acao"];
if ($resultadoAcao == "") {
	$resultadoAcao = "todos";
}

$tipoData = $_GET["tipoData"];
if ($tipoData == "") {
	$tipoData = "data";
}

function selected( $value, $prev ){
   return $value==$prev ? ' selected="selected"' : ''; 
};

?>
<script>
	(function() {

			$('#formUpload').ajaxForm({
				
					beforeSend: function() {
						if($('input[name="myfile"]').val() == ''){
							alert('Selecione o arquivo');
							return false;
						}else{
							$('#btSubmitUpload').val('Processando...').attr('disabled',true);
						}
					},
					uploadProgress: function(event, position, total, percentComplete) {
					},
					success: function(ret) {
					},
					complete: function(xhr) {
						location.href="cobranca.php";
					}
		
			}); 

	})();       


	function alterarPeriodo() {
		var dataInicio = document.getElementById('DataInicio').value;
		var anoInicio = dataInicio.substr(6,4);
		var mesInicio = dataInicio.substr(3,2);
		var diaInicio = dataInicio.substr(0,2);
		var dataFim = document.getElementById('DataFim').value;
		var anoFim = dataFim.substr(6,4);
		var mesFim = dataFim.substr(3,2);
		var diaFim = dataFim.substr(0,2);
		var tid = document.getElementById('tid').value;
		var id = document.getElementById('id').value;
	//	assinante = document.getElementById('assinante').value;
		var email = document.getElementById('email').value;
		var acao = document.getElementById('selAcao').value;
		var tipoData = document.getElementById('tipoData').value;
		
		window.location='cobranca.php?dataInicio='+anoInicio+'-'+mesInicio+'-'+diaInicio+'&dataFim='+anoFim+'-'+mesFim+'-'+diaFim+'&acao='+acao+'&tid='+tid+'&id='+id+'&email='+email+'&tipoData='+tipoData;
	}

	/*
	function enviaArquivo(){
		if(document.form.arquivo.value==''){
			alert('Selecione um arquivo');
			document.form.arquivo.focus();
			return false;
		}
	}*/

    
	$(document).ready(function(e) {

		var esquerda = ($('#email').offset().left);
		var topo = ($('#email').offset().top);
		var altura = ($('#email').innerHeight());
				
				
		//$('#btCarregaArquivo').bind('click',function(){
		//	$('#divFormUpload').toggle();
		//});
		
		//$('#btFecharUploadCielo').bind('click',function(e){
		//	e.preventDefault();
		//	location.href="cobranca.php";
		//});
		
		
		$('#email').keyup(function(){
			if($(this).val() != ''){
				$.ajax({
					url:'preenchecampobuscaCobranca.php',
					type: 'POST',
					data: 'valor='+$('#email').val(),
					async: true,
					cache:false,
					success: function(result){
						if(result != ''){
							$('#preenchimentoBusca').css({
								'height':'auto'
								,'display': 'block'
								, 'top': topo + altura + 3
								, 'left': esquerda
							}).fadeIn('fast');
							$('#preenchimentoBusca').html(result);
						} else {
							$('#preenchimentoBusca').html('').css('display','none');
						}
					}						
				});
				
				$('.selResultBusca').live('click',function(){
					$('#email').val($(this).html());
					$('#hddIdUser').val($(this).attr('iduser'));
					$('#preenchimentoBusca').fadeOut('fast');
				});
			}else{
				$('#preenchimentoBusca').fadeOut('fast');
			}
		});
		
		var optionsNF = { 
			target:   '#output',   // target element(s) to be updated with server response 
			beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,  // post-submit callback 
			uploadProgress: OnProgress, //upload progress callback 
			resetForm: true        // reset the form after successful submit 
		}; 
				
		var optionsCielo = { 
			target:   '#output2',   // target element(s) to be updated with server response 
			beforeSubmit:  beforeSubmit2,  // pre-submit callback 
			success:       afterSuccess2,  // post-submit callback 
			uploadProgress: OnProgress2, //upload progress callback 
			resetForm: true        // reset the form after successful submit 
		}; 
				
		 $('#MyUploadForm').submit(function() { 
			$(this).ajaxSubmit(optionsNF);            
			return false; 
		});
				
		 $('#MyUploadForm2').submit(function() { 
			$(this).ajaxSubmit(optionsCielo);            
			return false; 
		});


		$('#btIniciarCobranca').bind('click',function(e){
			e.preventDefault();
			<?
			$checkStatusAgendamento = (mysql_query("SELECT * FROM envio_emails_cobranca WHERE status = 0 LIMIT 0,1"));
			if(mysql_num_rows($checkStatusAgendamento) > 0){
			?>
				alert('Existem mensagens pendentes de agendamento. É necessário enviá-las para rodar a cobrança novamente!');
			<?
			}else{
			?>
				if(confirm('Deseja realmente iniciar a cobrança?')){
					location.href='cobranca.php?iniciarCobranca&dataFim=<?=$dataFim?>&dataInicio=<?=$dataInicio?>'
				}
			<?
			}
			?>
		});
    });
	
	
	function afterSuccess(){
		//$('#btReload').bind('click',function(){
			location.href = 'cobranca.php<?=($_GET['dataInicio'] != '' || $_GET['dataFim'] != '' || $_GET['acao'] != '' || $_GET['tid'] != '' || $_GET['email'] != '' || $_GET['tipoData'] != '' ? '?dataInicio=' . $_GET['dataInicio'] . '&dataFim=' . $_GET['dataFim'] . '&acao=' . $_GET['acao'] . '&tid=' . $_GET['tid'] . '&email=' . $_GET['email'] . '&tipoData=' . $_GET['tipoData'] : '')?>';
		//});
	//		$('#output').show();
	//		$('#progressbox').hide();
		}


		function afterSuccess2(){
			//$('#btReload').bind('click',function(){
	//			location.href = 'cobranca.php';
			//});
			//$('#output2').show();
			//$('#resultado2').show();
			//if($('#output2').html() == ''){
				location.href = 'cobranca.php<?=($_GET['dataInicio'] != '' || $_GET['dataFim'] != '' || $_GET['acao'] != '' || $_GET['tid'] != '' || $_GET['email'] != '' || $_GET['tipoData'] != '' ? '?dataInicio=' . $_GET['dataInicio'] . '&dataFim=' . $_GET['dataFim'] . '&acao=' . $_GET['acao'] . '&tid=' . $_GET['tid'] . '&email=' . $_GET['email'] . '&tipoData=' . $_GET['tipoData'] : '')?>';
			//}
			//$('#progressbox2').hide();
		}



	function beforeSubmit(){
	   //check whether client browser fully supports all File API
	   if (window.File && window.FileReader && window.FileList && window.Blob)
		{
		   var fsize = $('#FileInput')[0].files[0].size; //get file size
		   var ftype = $('#FileInput')[0].files[0].type; // get file type
			//allow file types 
		  switch(ftype)
			   {
				case 'text/plain':
				break;
				default:
				 $("#output").html("<b>"+ftype+"</b> Tipo de arquivo inválido!");
			 return false;
			   }
		
		   //Allowed file size is less than 5 MB (1048576 = 1 mb)
		   if(fsize>5242880) 
		   {
			 alert("<b>"+fsize +"</b> Arquivo muito grande! <br />Ele teve ter menos de 5 MB.");
			 return false;
		   }
			}
			else
		{
		   //Error for older unsupported browsers that doesn't support HTML5 File API
		   alert("Por favor, atualize seu navegador. Algumas funcionalidades não são suportadas pela versão que possui!");
			   return false;
		}
	}

	function beforeSubmit2(){
	   //check whether client browser fully supports all File API
	   if (window.File && window.FileReader && window.FileList && window.Blob)
		{
		   var fsize = $('#FileInput2')[0].files[0].size; //get file size
		   var ftype = $('#FileInput2')[0].files[0].type; // get file type
			//allow file types 
			//alert($('#FileInput2')[0].files[0].type);
		   switch(ftype)
			   {
				case 'application/vnd.ms-excel':
				case 'text/csv':
				break;
				default:
				 $('#resultado2').show();
				 $("#output2").html("<b>"+ftype+"</b> Tipo de arquivo inválido!");
			 return false;
			   }
		
		   //Allowed file size is less than 5 MB (1048576 = 1 mb)
		   if(fsize>5242880) 
		   {
			 alert("<b>"+fsize +"</b> Arquivo muito grande! <br />Ele teve ter menos de 5 MB.");
			 return false;
		   }
			}
			else
		{
		   //Error for older unsupported browsers that doesn't support HTML5 File API
		   alert("Por favor, atualize seu navegador. Algumas funcionalidades não são suportadas pela versão que possui!");
			   return false;
		}
	}
	
	function OnProgress(event, position, total, percentComplete)
	{
		//Progress bar
		$('#output').html('').hide();
		$('#progressbox').show();
		$('#progressbar').width(percentComplete + '%'); //update progressbar percent complete
		$('#statustxt').html(percentComplete + '%'); //update status text
		if(percentComplete>50)
			{
				$('#statustxt').css('color','#000'); //change status text to white after 50%
			}
	}
	
	function OnProgress2(event, position, total, percentComplete)
	{
		//Progress bar
		$('#resultado2').show();
		$('#output2').html('').hide();
		$('#progressbox2').show();
		$('#progressbar2').width(percentComplete + '%'); //update progressbar percent complete
		$('#statustxt2').html(percentComplete + '%'); //update status text
		//if(percentComplete>50)
		//	{
		//		$('#statustxt2').css('color','#000'); //change status text to white after 50%
		//	}
	}

</script>

<div class="principal">
<?



?>
<div id="divFormUpload" class="bubble_top_right box_visualizacao x_visualizacao" style="position: absolute; text-align: center; display: none;">
	<div style="padding: 30px 20px 20px 20px;">
        <form action="processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
        <input name="FileInput" id="FileInput" type="file" />
        <input type="submit"  id="submit-btn" value="Upload" />
        <img src="../images/loading.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
        </form>
        <div id="progressbox" style="display: none;">
            <div id="progressbar" style="backbround-color: #00a600"></div >
            <div id="statustxt">0%</div>
        </div>
        <div id="output" style="color: #a61d00"></div>
	</div>
</div>

<div id="divFormUpload2" class="bubble_top_right box_visualizacao x_visualizacao" style="position: absolute; text-align: center; display: none;">
	<div style="padding: 30px 20px 20px 20px;">
        <form action="processupload2.php" method="post" enctype="multipart/form-data" id="MyUploadForm2">
        <input name="FileInput" id="FileInput2" type="file" />
        <input type="submit"  id="submit-btn2" value="Upload" />
        <img src="../images/loading.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
        </form>
        <div id="progressbox2" style="display: none;">
            <div id="progressbar2" style="backbround-color: #00a600"></div >
            <div id="statustxt2">0%</div>
        </div>
        <div id="resultado2" style="position:relative; width: 100%; display:none;">
	        <div id="output2" style="color: #a61d00"></div>
            <!--<a href="#" id="btFecharUploadCielo">Fechar</a>-->
        </div>
	</div>
</div>

<div class="titulo" style="margin-bottom:10px; float:left">Cobrança</div>
<div style="float: left;margin-left: 10px;margin-top: 3px;">
    <input type="button" value="Iniciar Cobrança" id="btIniciarCobranca" style="">
</div>
<div style="clear:both"> </div>

<div style="position: relative; font-size: 11px;margin-top:10px;">
	<div style="float: left;">
    <form method="post" action="Javascript:alterarPeriodo()">
    TID  
    <input name="tid" id="tid" type="text" value="<?=($tid)?>" maxlength="35" style="width:100px; font-size: 11px;" class="campoTID" />  ou 
    E-mail 
    <input type="text" name="email" id="email" maxlength="220" style="font-size: 11px;width:100px;" />  ou 
    ID  
    <input name="id" id="id" type="text" value="<?=($id)?>" maxlength="10" style="width:50px; font-size: 11px;" class="" />  ou 
    <!-- <div id="preenchimentoBusca" style="position: absolute;"></div> -->
    Exibir 
    <select name="selAcao" id="selAcao" style="font-size: 11px; width: 150px;"> 
    <?
    /*
    1: Envio de boleto bancário
    1.2: Pagamento de Boleto com sucesso
    2.1: Cobrança de cartão com sucesso
    2.2: Cobrança de cartão com erro
    2.3: Cobrança de cartão com erro (não autorizado)
    2.4: TID em branco ou nulo
		2.5: ERRO retorno cobrança
    3: Desativação de conta
    4: Demo expirado
    5: Reativação de conta
    6: Cancelamento de conta
    7: Reativação de conta
    8: Notificação demo a vencer
    <!--9: Inconsistência da cobrança de boleto -->
    9.1: Inconsistência - boleto com valor menor
    9.2: Inconsistência - boleto com valor maior
    9.3: Inconsistência - não foi localizada data do boleto
    */
    ?>
    
    
    <option value="todos" <?php echo selected( 'todos', $resultadoAcao ); ?>>Todos</option>
    <option value="1" <?php echo selected( '1', $resultadoAcao ); ?>>Boleto</option>
    <option value="2" <?php echo selected( '2', $resultadoAcao ); ?>>Cartão</option>
    
    <!-- <option value="1" <?php echo selected( '1', $resultadoAcao ); ?>>1: Envio de boleto</option> -->
    <!-- <option value="1.2" <?php echo selected( '1.2', $resultadoAcao ); ?>>1.2: Boleto com sucesso</option> -->
    <!-- <option value="2.1" <?php echo selected( '2.1', $resultadoAcao ); ?>>2.1: Cartão com sucesso</option> -->
    <!-- <option value="2.2" <?php echo selected( '2.2', $resultadoAcao ); ?>>2.2: Cartão com erro</option> -->
    <!-- <option value="2.3" <?php echo selected( '2.3', $resultadoAcao ); ?>>2.3: Cartão não autorizado</option> -->
    <!-- <option value="2.4" <?php echo selected( '2.4', $resultadoAcao ); ?>>2.4: TID em branco ou nulo</option> -->
    <!-- <option value="3" <?php echo selected( '3', $resultadoAcao ); ?>>3: Desativação de conta</option> -->
    <!-- <option value="4" <?php echo selected( '4', $resultadoAcao ); ?>>4: Demo expirado</option> -->
    <!-- <option value="5" <?php echo selected( '5', $resultadoAcao ); ?>>5: Reativação de conta</option> -->
    <!-- <option value="6" <?php echo selected( '6', $resultadoAcao ); ?>>6: Conta cancelada</option> -->
    <!-- <option value="7" <?php echo selected( '7', $resultadoAcao ); ?>>7: Conta reativada</option> -->
    <!-- <option value="8" <?php echo selected( '8', $resultadoAcao ); ?>>8: Demo a vencer</option> -->
    <!-- <option value="9" <?php echo selected( '9', $resultadoAcao ); ?>>9: Inconsistência boleto</option> -->
    <!-- <option value="9.1" <?php echo selected( '9.1', $resultadoAcao ); ?>>9.1: Boleto valor menor</option> -->
    <!-- <option value="9.2" <?php echo selected( '9.2', $resultadoAcao ); ?>>9.2: Boleto valor maior</option> -->
    <!-- <option value="9.3" <?php echo selected( '9.3', $resultadoAcao ); ?>>9.3: Data do boleto</option> -->
    <option value="3" <?php echo selected( '3', $resultadoAcao ); ?>>Venda cancelada</option>
    <option value="4" <?php echo selected( '4', $resultadoAcao ); ?>>Certificado Digital</option>
    </select>
    por
    <select name="tipoData" id="tipoData" style="font-size: 11px; width: 70px;"> 
    <option value="data" <?php echo selected( 'data', $tipoData ); ?>>Venda</option>
    <option value="data_pagamento" <?php echo selected( 'data_pagamento', $tipoData ); ?>>Pagamento</option>
    </select>
    entre  
      <input name="DataInicio" id="DataInicio" type="text" value="<?php echo $data_inicio_filtro; ?>" maxlength="10"  style="width:75px;font-size: 11px;" class="campoData"/> 
      até 
      <input name="DataFim" id="DataFim" type="text" value="<?php echo $data_fim_filtro; ?>" maxlength="10"  style="width:75px;font-size: 11px;margin-right:10px;" class="campoData" />
      <input name="Alterar" type="submit" value="Filtrar" />
    </form>
    </div>

	

</div>
<div style="clear:both; margin-bottom: 20px;"></div>

<?



function enviarEmailDemosSeteDias(){
	//Pega os demos inscritos  a mais de 7 dias para enviar um email
	$consulta_demo_info = mysql_query("SELECT *,DATEDIFF(data_pagamento, DATE(now())) from historico_cobranca WHERE DATEDIFF(data_pagamento, DATE(now())) <= 23 AND DATEDIFF(data_pagamento, DATE(now())) >= 21");
	
	while ( $objeto_demo_info = mysql_fetch_array($consulta_demo_info) ) {
		
		$consulta_email_login = mysql_query("SELECT * FROM login WHERE status = 'demo' AND idUsuarioPai = id AND idUsuarioPai = '".$objeto_demo_info['id']."' LIMIT 1");
		$objeto_email_login = mysql_fetch_array($consulta_email_login);

		if( $objeto_email_login['email'] != "" ){

			$consulta_email_enviado = mysql_query("SELECT * FROM envio_emails_cobranca WHERE tipo_mensagem = 'demo_info' AND email = '".$objeto_email_login['email']."' ");
			$objeto_email_enviado = mysql_fetch_array($consulta_email_enviado);

			if( $objeto_email_enviado['email'] == "" )
				mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'demo_info', nome = '".$objeto_email_login['assinante']."', email = '" . $objeto_email_login['email'] . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");

		}

	}
}


function enviarEmailDemosQuinzeDias(){
	//Pega os demos inscritos  a mais de 7 dias para enviar um email
	$consulta_demo_info = mysql_query("SELECT *,DATEDIFF(data_pagamento, DATE(now())) from historico_cobranca WHERE DATEDIFF(data_pagamento, DATE(now())) <= 15 AND DATEDIFF(data_pagamento, DATE(now())) >= 10");
	
	while ( $objeto_demo_info = mysql_fetch_array($consulta_demo_info) ) {
		
		$consulta_email_login = mysql_query("SELECT * FROM login WHERE status = 'demo' AND idUsuarioPai = id AND idUsuarioPai = '".$objeto_demo_info['id']."' LIMIT 1");
		$objeto_email_login = mysql_fetch_array($consulta_email_login);

		if( $objeto_email_login['email'] != "" ){

			$consulta_email_enviado = mysql_query("SELECT * FROM envio_emails_cobranca WHERE tipo_mensagem = 'demo_info_15' AND email = '".$objeto_email_login['email']."' ");
			$objeto_email_enviado = mysql_fetch_array($consulta_email_enviado);

			if( $objeto_email_enviado['email'] == "" )
				mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'demo_info_15', nome = '".$objeto_email_login['assinante']."', email = '" . $objeto_email_login['email'] . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");

		}

	}
}


function enviarEmailDemosInativosQuinzeDias(){
	//Pega os demos Inativos inscritos  a mais de 15 dias para enviar um email
	$consulta_demo_info = mysql_query("SELECT *,DATEDIFF(data_pagamento, DATE(now())) from historico_cobranca WHERE DATEDIFF(data_pagamento, DATE(now())) >= -20 AND DATEDIFF(data_pagamento, DATE(now())) <= -15");
	
	while ( $objeto_demo_info = mysql_fetch_array($consulta_demo_info) ) {
		
		$consulta_email_login = mysql_query("SELECT * FROM login WHERE status = 'demoInativo' AND idUsuarioPai = id AND idUsuarioPai = '".$objeto_demo_info['id']."' LIMIT 1");
		$objeto_email_login = mysql_fetch_array($consulta_email_login);

		if( $objeto_email_login['email'] != "" ){

			$consulta_email_enviado = mysql_query("SELECT * FROM envio_emails_cobranca WHERE tipo_mensagem = 'demo_inativo_info' AND email = '".$objeto_email_login['email']."' ");
			$objeto_email_enviado = mysql_fetch_array($consulta_email_enviado);

			if( $objeto_email_enviado['email'] == "" )
				mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'demo_inativo_info', nome = '".$objeto_email_login['assinante']."', email = '" . $objeto_email_login['email'] . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");

		}

	}
}

if(isset($_GET["iniciarCobranca"])) { 
	$show_log = false;

	//mysql_query("DELETE envio_emails_cobranca 


	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'INICIO DA COBRANÇA')");

	/*
	DEVE SER ALTERADO O STATUS DE PAGAMENTO ANTES DE TUDO PARA NÃO CORRER O RISCO DE EFETIVAR PAGAMENTOS FUTUROS (STATUS A VENCER)
	
	
	####################################################################
	ATUALIZANDO PAGAMENTOS VENCIDOS E NAO PAGOS
	####################################################################
	*/

	//MAL - Atualiza para vencidos quando esta a vencer e data e maior que data do pagamento{
		$sqlUpdateVencidos = "
			UPDATE 
				historico_cobranca h 
			INNER JOIN 
				login l ON h.id = l.id 
			SET 
				h.status_pagamento = (CASE 
										WHEN l.status = 'demo' THEN 'não pago' 
										ELSE 'vencido' 
									END) 
			WHERE status_pagamento IN ('a vencer','pendente') 
			AND DATEDIFF(data_pagamento, DATE(now())) < 0
			AND DATE(now()) NOT IN (SELECT feriadoData FROM `feriado` WHERE feriadoData >= concat(YEAR(DATE(NOW())),'-01-01'))
			AND WEEKDAY(DATE(now())) <> 5 
			AND WEEKDAY(DATE(now())) <> 6;
			";
		$rsAtualizaVencidos = mysql_query($sqlUpdateVencidos);

		//Veririca se existe um a vencer no historico do usuario
		function ifExisteAVencer($id){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE status_pagamento = 'a vencer' AND id = '".$id."' ");
			if( mysql_num_rows($consulta) > 0 )
				return true;
			else
				return false;		
		}
		//Pega a maior data do historico para inserir o a vencer
		function getMaxDataHistorico($id){
			$consulta = mysql_query("SELECT max(data_pagamento) as data FROM historico_cobranca WHERE id = '".$id."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data'];
		}
		function getStatusUser($id){
			
			$consulta = mysql_query("SELECT * FROM login WHERE idUsuarioPai = '".$id."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['status'];
		
		}
		function verificarNaoPagosEVencidos(){
			$rodarNovamente = false;
			include 'datas.class.php';
			$datas = new Datas();
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE status_pagamento = 'não pago' group by id order by data_pagamento DESC ");
			while( $objeto_consulta = mysql_fetch_array($consulta) ){
				if( !ifExisteAVencer($objeto_consulta['id']) ){
					// echo $objeto_consulta['id'].'<br>';
					$data = $datas->somarMes(getMaxDataHistorico($objeto_consulta['id']),1);
					// echo $data.'<br>';
					// echo getStatusUser($objeto_consulta['id']).'<br>';
					if( getStatusUser($objeto_consulta['id']) == 'ativo' || getStatusUser($objeto_consulta['id']) == 'inativo' ){				
						mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $objeto_consulta['id'] . "', '" . $data . "', 'a vencer')");
						$rodarNovamente = true;
					}

				}
			}
			return $rodarNovamente;		
		}
		//Envia email para os demos a mais de sete dias cadastrados
		enviarEmailDemosSeteDias();
		// enviarEmailDemosQuinzeDias();
		// enviarEmailDemosInativosQuinzeDias();

	// MAL }

	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS VENCIDOS
	####################################################################
	*/

	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS NAO PAGOS
	####################################################################
	*/

	$sqlUpdateNaoPago = "UPDATE `historico_cobranca` SET status_pagamento = 'não pago' WHERE status_pagamento IN ('vencido') AND DATEDIFF(data_pagamento, DATE(now())) <= -6";
	$rsAtualizaNaoPago = mysql_query($sqlUpdateNaoPago);

		

	/*
	####################################################################
	ATUALIZANDO PAGAMENTOS NAO PAGOS
	####################################################################
	*/



	// LOOP PARA CANCELAMENTO DE USUARIOS - PARA SEGUNDA - quanquer não pago há mais de 90 dias
	
	// CHECANDO NO HISTORICO DE COBRANCA SE HÁ REGISTRO NÃO PAGO HA MAIS DE 3 MESES PARA DETERMINAR SE DEVE CANCELAR O USUARIO
	$sql_checa_cancelamento = "
								SELECT 
									distinct h.id 
								FROM login l
								INNER JOIN 
									historico_cobranca h ON l.id = h.id
								WHERE 
									DATEDIFF(h.data_pagamento, DATE(NOW())) < -90 
									AND h.status_pagamento =  'não pago'
									AND l.status <> 'cancelado' 
							";
	$rs_checa_cancelamento = mysql_query($sql_checa_cancelamento);

	// ############################## LOG ###############################
	if($show_log == true){
		echo "foram localizados " . mysql_num_rows($rs_checa_cancelamento) . " usuários com pagamentos não pagos há mais de 3 meses<BR><BR>";
			ob_flush();
	}
	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'FORAM LOCALIZADOS " . mysql_num_rows($rs_checa_cancelamento) . " USUARIOS COM PAGAMENTOS não pagos HÁ MAIS DE 3 MESES')");
	// ############################## LOG ###############################


	while($checa_cancelamento = mysql_fetch_array($rs_checa_cancelamento)){
/*		mysql_query("	UPDATE 
							login 
						SET 
							status = 'cancelado' 
						WHERE id = " . $checa_cancelamento['id'] . " AND status <> 'cancelado'"
					);*/
			mysql_query("	UPDATE 
							login 
						SET 
							status = 'cancelado' 
						WHERE idUsuarioPai  = " . $checa_cancelamento['id'] . " AND status <> 'cancelado'"
					);
		
		// INSERE NA TABELA DE METRICAS
		mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $checa_cancelamento['id'] . ",'cancelado','" . date('Y-m-d') . "')");


		if(mysql_affected_rows() > 0){
			// ############################## LOG ###############################
			if($show_log == true){
				echo "Usuário " . $checa_cancelamento['id'] . " cancelado<BR><BR>";
			ob_flush();
			}
			// ############################## LOG ###############################
	
			// LOG DE ACESSOS
			//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'CANCELANDO USUARIO: " . $checa_cancelamento['id'] . "')");

			
		}
				
	}	
	
	//Coloca um a vencer apos o mais recente nao pago caso nao exista um
	$rodar = verificarNaoPagosEVencidos();
	while ( $rodar ){
		$sqlUpdateVencidos = "
		UPDATE 
			historico_cobranca h 
		INNER JOIN 
			login l ON h.id = l.id 
		SET 
			h.status_pagamento = (CASE 
									WHEN l.status = 'demo' THEN 'não pago' 
									ELSE 'vencido' 
								END) 
		WHERE  status_pagamento IN ('a vencer','pendente') 
			AND DATEDIFF(data_pagamento, DATE(now())) < 0
			AND DATE(now()) NOT IN (SELECT feriadoData FROM `feriado` WHERE feriadoData >= concat(YEAR(DATE(NOW())),'-01-01'))
			AND WEEKDAY(DATE(now())) <> 5 
			AND WEEKDAY(DATE(now())) <> 6;
		";
		$sqlUpdateNaoPago = "UPDATE `historico_cobranca` SET status_pagamento = 'não pago' WHERE status_pagamento IN ('vencido') AND DATEDIFF(data_pagamento, DATE(now())) <= -6";
		$rsAtualizaNaoPago = mysql_query($sqlUpdateNaoPago);
		$rsAtualizaVencidos = mysql_query($sqlUpdateVencidos);
		$rodar = verificarNaoPagosEVencidos();
	}


	
	/*
	####################################################################
	PROCESSANDO OS ARQUIVOS DE RETORNO DO BANCO
	####################################################################
	*/
	
		// PROCESSA 1 ARQUIVO
		if(isset($_REQUEST['arq']) && $_REQUEST['arq'] != ''){

			$nome_arquivo = ('arquivos_retorno/'.$_REQUEST['arq']);
			$nome_arquivo_proc = ('arquivos_retorno/proc_'. $_REQUEST['arq']);
			// CHECANDO SE O ARQUIVO EXISTE PARA PROCESSAR
			ProcessaArquivoRetorno('',$nome_arquivo, true, $mensalidade, $show_log);
			// ############################## LOG ###############################
			if($show_log == true){
				echo "ARQUIVO RETORNO " . $nome_arquivo . " processado<BR><BR>";
			ob_flush();
			}
			// ############################## LOG ###############################


		} else {
			
		// PROCESSA TODOS OS ARQUIVOS
			$rsArquivos = mysql_query("SELECT * FROM arquivos_retorno_banco WHERE status = 'carregado' ORDER BY data_carga");
			if (mysql_num_rows($rsArquivos) > 0) {
				while($arquivos=mysql_fetch_object($rsArquivos)){
	
					$nome_arquivo = ('arquivos_retorno/'.$arquivos->nome);
					$nome_arquivo_proc = ('arquivos_retorno/proc_'. $arquivos->nome);
					
					
					ProcessaArquivoRetorno('',$nome_arquivo, true, $mensalidade, $show_log);

					// ############################## LOG ###############################
					if($show_log == true){
						echo "ARQUIVO RETORNO " . $nome_arquivo . "processado<BR><BR>";
			ob_flush();
					}
					// ############################## LOG ###############################
					
				}
			}else{
				// ############################## LOG ###############################
				if($show_log == true){
					echo "ARQUIVO RETORNO de boletos já processado<BR><BR>";
		ob_flush();
				}
				// ############################## LOG ###############################
				
			}
			
		}
	/*
	####################################################################
	PROCESSANDO OS ARQUIVOS DE RETORNO DO BANCO
	####################################################################
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


	// ############################## LOG ###############################
	if($show_log == true){
		echo "checando se há pagamentos de boletos a vencer (retornou " . mysql_num_rows($rsBoletos_a_vencer) . ")<BR><BR>";
			ob_flush();
	}
	// ############################## LOG ###############################
	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'LOCALIZADOS " . mysql_num_rows($rsBoletos_a_vencer) . " BOLETOS HÁ 5 DIAS DE VENCER')");
	
	while($boletos_a_vencer=mysql_fetch_array($rsBoletos_a_vencer)){

	
		// ############################## LOG ###############################
		if($show_log == true){
			echo "enviando e-mails com boletos a vencer para " . $boletos_a_vencer["email"] . "<BR><BR>";
			ob_flush();
		}
		// LOG DE ACESSOS
		//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'ENVIANDO EMAIL BOLETO A VENCER PARA " . $boletos_a_vencer["email"] . "(id: " . $boletos_a_vencer['id'] . ")')");

		// ############################## LOG ###############################

		//Componente de Envio de e-mail.
		$Assinante = $boletos_a_vencer["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = date('d/m/Y',strtotime($boletos_a_vencer["data_pagamento"]));
		$emailuser = $boletos_a_vencer["email"];

		$assuntoMail = "Boleto a vencer";
		// VARIAVEIS QUE SÃO UTILIZADAS NO CORPO DO EMAIL
		$sql_dados_empresa = "SELECT * FROM dados_da_empresa WHERE id = " . $boletos_a_vencer["id"];
		$linha_dados_empresa = mysql_fetch_array(mysql_query($sql_dados_empresa));
		$id_usuario_boleto = $boletos_a_vencer["id"];
		$valor_boleto = $mensalidade_formatada;
		$mes_boleto = date('m',strtotime($boletos_a_vencer["data_pagamento"]));
		$data_pagamento_boleto = date('m',strtotime($boletos_a_vencer["data_pagamento"]));
//		if(($boletos_a_vencer["status"] == 'ativo') || ($boletos_a_vencer["status"] == 'inativo')){
//			include '../mensagens/boleto_a_vencer.php';
//		}
		//include '../mensagens/componente_envio.php';
//		include '../mensagens/componente_envio_novo.php';


		if(($boletos_a_vencer["status"] == 'ativo') || ($boletos_a_vencer["status"] == 'inativo')){
			// INSERINDO OS DADOS DO ASSINANTE COM BOLETO A VENCER NA TABELA DE ENVIO DE MENSAGENS
			mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'boleto_a_vencer', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");
		}

	
		$sqlUp = "UPDATE historico_cobranca SET envio_email='enviado' WHERE idHistorico='" . $boletos_a_vencer["idHistorico"] . "'";
		$resultadoUp = mysql_query($sqlUp)
		or die (mysql_error());
	

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


	// ############################## LOG ###############################
	if($show_log == true){
		echo "checando se há usuários demos a vencer (retornou " . mysql_num_rows($rsDemos_a_vencer) . ")<BR><BR>";
			ob_flush();
	}
	// ############################## LOG ###############################
	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'LOCALIZADOS " . mysql_num_rows($rsDemos_a_vencer) . " DEMOS HÁ 5 DIAS DE VENCER')");
	
	while($demos_a_vencer=mysql_fetch_array($rsDemos_a_vencer)){

	
		// ############################## LOG ###############################
		if($show_log == true){
			echo "enviando e-mails com instruções para " . $demos_a_vencer["email"] . "<BR><BR>";
			ob_flush();
		}
		// LOG DE ACESSOS
		//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'ENVIANDO EMAIL COM INSTRUÇÕES DEMO A VENCER PARA " . $demos_a_vencer["email"] . "(id: " . $demos_a_vencer['id'] . ")')");

		// ############################## LOG ###############################

		//Componente de Envio de e-mail.
		$dia_vencimento = $demos_a_vencer["dia"];
		$dias_a_vencer = $demos_a_vencer["diferenca"];
		$Assinante = $demos_a_vencer["assinante"];
		$AssinanteExplode = explode(" ", $Assinante);
		$vencimento = date('d/m/Y',strtotime($demos_a_vencer["data_pagamento"]));
		$emailuser = $demos_a_vencer["email"];		
//		$assuntoMail = "Período de avaliação prestes a vencer";
//		include '../mensagens/demo_a_vencer.php';
//		//include '../mensagens/componente_envio.php';
//		include '../mensagens/componente_envio_novo.php';
	
		// INSERINDO OS DADOS DO ASSINANTE DEMO COM PRAZO DE UTILIZAÇÃO GRATIS A VENCER NA TABELA DE ENVIO DE MENSAGENS
		mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'demo_a_vencer', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");

		$sqlUp = "UPDATE historico_cobranca SET envio_email='enviado' WHERE idHistorico='" . $demos_a_vencer["idHistorico"] . "'";
		$resultadoUp = mysql_query($sqlUp)
		or die (mysql_error());
	

	}

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
				 AND d.forma_pagameto IN ('visa','mastercard','amex','diners','elo')
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

	$quantidade_de_usuarios_cartao = mysql_num_rows($resultado);

	/*
	####################################################################
	INICIA O LOOP PARA PROCEDER COM OS PAGAMENTOS POR CARTAO
	####################################################################
	*/		

	// echo $quantidade_de_usuarios_cartao;

	while($linha=mysql_fetch_array($resultado)) {
						
		$acaoRealizada = "";
		$sql_pagamentos_cartao = "SELECT h.id, h.status_pagamento, h.idHistorico, h.data_pagamento, d.forma_pagameto, d.numero_cartao, l.email, l.assinante FROM login l INNER JOIN  historico_cobranca h ON l.id = h.id INNER JOIN dados_cobranca d ON h.id = d.id WHERE l.id='" . $linha["idUser"] . "' AND h.status_pagamento IN ('vencido')";

				// EM 30/09/2013 - foi solicitado que fizesse a cobrança do cartão somente para pagamentos vencidos - AND h.status_pagamento IN ('vencido','não pago')
				//AND d.forma_pagameto IN ('visa','mastercard')
		$rs_pagamentos_cartao = mysql_query($sql_pagamentos_cartao)
		or die (mysql_error());
		
		// TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
		$total_devendo = mysql_num_rows($rs_pagamentos_cartao);

		/*
		MUDANÇA - POSSIBILIDADE DO USUARIO PODER TER MAIS DE UMA EMPRSA CADASTRADA
							CALCULAR A QUANTIDADE DE EMPRESA QUE O CLIENTE POSSUI E MULTIPLICAR PELA MENSALIDADE
		*/	
		$rsTotalEmpresas = mysql_fetch_array(mysql_query("SELECT COUNT(*) total_empresas FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = '" . $linha["idUser"] . "' AND e.ativa = 1"));
		$total_empresas = $rsTotalEmpresas['total_empresas'];
		$mensalidade = ($mensalidade * $total_empresas);
	

	
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
			//			$valor_a_cobrar = (string)((int)($mensalidade) * $total_devendo) . "00";
			//			$valor_pago = (int)((int)($mensalidade) * $total_devendo);

			// MONTANDO STRING COM OS IDs DOS HISTÓRICOS QUE DEVEM SER ATUALIZADOS
			$idHistoricoAtualizar = "('";
			$arrTestes = array();

			while($pagamentos_cartao = mysql_fetch_array($rs_pagamentos_cartao)){

				// CHECANDO SE EMPRESAS FORAM CADASTRADAS ANTES DO VENCIMENTO DE ALGUM PAGAMENTO NÃO FEITO
				$empresas_para_cobrar = mysql_num_rows(mysql_query("SELECT l.id, DATEDIFF('" . date("Y-m-d",strtotime($pagamentos_cartao['data_pagamento'])) . "',data_desativacao) FROM login l INNER JOIN dados_da_empresa e ON l.id = e.id WHERE l.idUsuarioPai = " . $linha["idUser"] . " AND l.data_inclusao <= '" . ($pagamentos_cartao['data_pagamento']) . "' AND (e.ativa = 1 OR (e.ativa=0 AND DATEDIFF('" . date("Y-m-d",strtotime($pagamentos_cartao['data_pagamento'])) . "',data_desativacao) < 5))"));		
				
				if($empresas_para_cobrar > 0){
					array_push($arrTestes,array('idPagto'=>$pagamentos_cartao['id'],'qtd_empresas'=>$empresas_para_cobrar,'data_pagamento'=>$pagamentos_cartao['data_pagamento']));
				}		

				$forma_pagamento_assinante = $pagamentos_cartao['forma_pagameto'];
				$numeroCartao = ($pagamentos_cartao['numero_cartao'] ? retornaNumeroCartaoMascara($pagamentos_cartao['numero_cartao']) : '');
				$assinante = $pagamentos_cartao['assinante'];
				$email_assinante = $pagamentos_cartao['email'];

				$idHistoricoAtualizar .= $pagamentos_cartao['idHistorico'] . "','";
				
			}
			$idHistoricoAtualizar .= "')";
			
			$idHistoricoAtualizar  = str_replace(",''","",$idHistoricoAtualizar);
			
			$valor_total_devendo = 0;
			foreach($arrTestes as $dados){
				$valor_total_devendo += ($mensalidade_unitaria * $dados['qtd_empresas']);
			}
		
			// CALCULANDO O TOTAL A SER COBRADO COM O TOTAL DE LINHAS QUE O USUARIO POSSUI COM STATUS NÃO PAGO OU VENCIDO
			$valor_a_cobrar = (string)((int)$valor_total_devendo) . "00";
			$valor_pago = (int)((int)$valor_total_devendo);
			
		}

		if( ($forma_pagamento_assinante == 'visa') || ($forma_pagamento_assinante == 'mastercard') || ($forma_pagamento_assinante == 'dinners') || ($forma_pagamento_assinante == 'elo') || ($forma_pagamento_assinante == 'amex') ){	
			// PROCEDE COM A COBRANÇA POR CARTÃO
			// IMPORTANTE: O SCRIPT A SEGUIR É FUNCIONAL APENAS EM PHP 5	

			$sql_cobranca_erro_mesmo_dia = "SELECT count(*) total FROM relatorio_cobranca WHERE id = '" . $linha["idUser"] . "' AND data = '" . date('Y-m-d') . "' AND resultado_acao IN ('2.2','2.3')";	
			
			$rs_cobranca_erro_mesmo_dia = mysql_query($sql_cobranca_erro_mesmo_dia)
			or die (mysql_error());
			
			$linha_cobranca_erro_mesmo_dia = mysql_fetch_array($rs_cobranca_erro_mesmo_dia);

			if($linha_cobranca_erro_mesmo_dia['total'] == 0){
				
				// 16/04/2014 - CHECAGEM PARA VER SE SERÁ COBRADO O MÁXIMO DE 150,00 DO USUARIO
				if(false){

					// LOG DE ACESSOS
					//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $linha['idUser'] . " - OCORREU UM ERRO E SERIA COBRADO " . $valor_a_cobrar . " - COBRANÇA NÃO EFETUADA')");

				}else{
					$XMLtransacao 											= "";
					$retorno_codigo_erro 								= "";
					$retorno_mensagem_erro 							= "";
					$retorno_tid 												= "";
					$retorno_pan 												= "";
					$retorno_pedido 										= "";
					$retorno_valor 											= "";
					$retorno_moeda 											= "";
					$retorno_data_hora 									= "";
					$retorno_descricao 									= "";
					$retorno_idioma 										= "";
					$retorno_bandeira 									= "";
					$retorno_produto 										= "";
					$retorno_parcelas 									= "";
					$retorno_status 										= "";
					$retorno_codigo_autenticacao 				= "";
					$retorno_mensagem_autenticacao 			= "";
					$retorno_data_hora_autenticacao 		= "";
					$retorno_valor_autenticacao 				= "";
					$retorno_eci_autenticacao 					= "";
					$retorno_codigo_autorizacao 				= "";
					$retorno_mensagem_autorizacao 			= "";
					$retorno_data_hora_autorizacao 			= "";
					$retorno_valor_autorizacao 					= "";
					$retorno_lr_autorizacao 						= "";
					$retorno_arp_autorizacao 						= "";
					$retorno_url_autenticacao 					= "";

					$dados_cartao = true;
				
					if($dados_cartao){
						
						/*
						####################################################################
						INÍCIO DO TRATAMENTO DO RETORNO DE ERRO DO COMPONENTE DE PAGAMENTO
						####################################################################
						*/	
						// LOG DE ACESSOS
						//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'RETORNO_CODIGO_ERRO: " . $retorno_codigo_erro . " - RETORNO_CODIGO_AUTORIZACAO: ".$retorno_codigo_autorizacao."')");
						

						require_once "../class/bean.php";
						require_once "../class/cielo.php";
						require_once '../class/pagamento-cartao.php';

						#########################################################################################
						############### Trecho para pagar com o Token ###########################################


						$consulta = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$linha['idUser']."' ");
						$objeto=mysql_fetch_array($consulta);

						$plano_usuario = $objeto['plano'];
						$plano_meses = $Config->verMeses($plano_usuario); //RECUPERA MESES DO PLANO
						$plano_valor = $Config->verValor($plano_usuario); // RECUPERA VALOR DO PLANO

						$consulta = mysql_query("SELECT l.id FROM login l INNER JOIN dados_da_empresa d ON l.id = d.id WHERE l.idUsuarioPai = " . $linha['idUser'] . " AND d.ativa = 1");
						$empresas_vinculadas = mysql_num_rows($consulta);

						$id_atualizar_historico = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$linha['idUser']."' AND status_pagamento = 'não pago' ");						
						$total_devendo = mysql_num_rows($id_atualizar_historico);

						//CALCULA VALOR A PAGAR
						$valor_pago = (int) ($Config->calcularValorEmpresas($plano_valor, $empresas_vinculadas));
						
						$valor_a_cobrar = $valor_pago;

						$Pagamento = new Pagamento(); //OBJETO DOS PAGAMENTOS

						//CALCULA QUANTOS MESES SERÃO SALVOS NO PRÓXIMO PAGAMENTO
						$meses_a_somar = $Pagamento->calcularMesesSomarBoleto($plano_meses, $total_devendo);

						// PEGANDO O ULTIMO NAO PAGO OU VENCIDO PARA DETERMINAR A PROXIMA DATA DE VENCIMENTO
						$linhaData = mysql_fetch_array(mysql_query("SELECT MAX(data_pagamento) data_pagamento FROM historico_cobranca WHERE id='" . $linha["idUser"] . "' AND status_pagamento IN ('vencido', 'não pago', 'pendente') ORDER BY idHistorico DESC LIMIT 0,1"));

						$dataPagamento=date('Y-m-d',(mktime(0,0,0,date('m',strtotime($linhaData["data_pagamento"]))+$meses_a_somar,date('d',strtotime($linhaData["data_pagamento"])),date('Y',strtotime($linhaData["data_pagamento"])))));

						$data_historico_aux = explode('-', $linhaData["data_pagamento"]);
						$data_historico_aux = $data_historico_aux[0].$data_historico_aux[1];


						//MAL Pega o token de pagamento cadastrado para o usuário
						$consulta_token_usuario = mysql_query("SELECT * FROM token_pagamento WHERE id_user = '".$linha["idUser"]."' ");
						$dados_cartao_user=mysql_fetch_array($consulta_token_usuario);				

						$token = $dados_cartao_user['token'];
						
						// if( $linha['idUser'] == 3889 ){
						// 	$valor_pago = 25.86;
						// 	$valor_a_cobrar = $valor_pago;
						// }

						$bandeira = $dados_cartao_user['bandeira'];
						$numeroCartao = $dados_cartao_user['numero_cartao'];
						$valor_pago = $valor_a_cobrar;
						$mensalidade = $valor_a_cobrar;
						//Cria um objeto que armazenará os dados do cartao para enviar ao pagamento
						$cartao = new Dados_cartao();
						// $mensalidade = 1;
						$cartao->setValor($mensalidade);//Seta o valor, que deverá estar no formato: 5900, sem virgula ou ponto(Para fins de teste, o valor dever)
						$cartao->setBandeira($bandeira);//Seta a bandeira 
						$cartao->setValorFinal($mensalidade);

						// //Cria um objeto para o pagamento
						$pagamento = new Pagamento_cartao();
						//Define o token para a cobrança
						$pagamento->setToken($token);

						// echo $token;

						//Tenta realizar a cobrança
						$pagamento->pagarComToken($cartao);

						$data_pagamento_cartao = $pagamento->getData();

						// exit();						
						################ Fim do trecho para pagar com o token de pagamento ######################
						#########################################################################################


						#########################################################################################
						################ Início do trecho do tratamento do retorno ##############################
						

						$retorno_codigo_erro = $pagamento->getCodigoErro();
						$retorno_codigo_autorizacao = $pagamento->getStatus();
						$retorno_tid = $pagamento->getTid();

						$pagementos_pendentes = mysql_query("SELECT * FROM tabela WHERE coluna = '".$valor."' ");
						$objeto=mysql_fetch_array($pagementos_pendentes);

						// echo $retorno_codigo_erro;

						$XmlResposta = $pagamento->getXmlRetorno();

						$inserir_log_cartao = mysql_query("INSERT INTO `log_cartao`(`id`, `id_user`, `erro`, `retorno_codigo` , `resultado`) VALUES ( '','".$linha["idUser"]."','".$retorno_codigo_erro."','".$retorno_codigo_autorizacao."','".$XmlResposta."' )");
						$log_cartao=mysql_fetch_array($inserir_log_cartao);
							



						
						// Se não ocorreu erro exibe parâmetros
						if (($retorno_codigo_erro == '') && ($retorno_codigo_autorizacao != '5')) { //Código 5 equivale a transação não autorizada
					
							if(is_null($retorno_tid) || $retorno_tid == ""){
	
								// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
								$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico ,  data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao) VALUES ('" . $linha["idUser"] . "','".$data_historico_aux."' ,'" . $data_pagamento_cartao . "', '" . $forma_pagamento_assinante . "', '2.4', 'não enviado', '', " . $valor_pago . ", '" . $numeroCartao . "')";
								$resultadoup = mysql_query($sqlup)
								or die (mysql_error());
	
								// // ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS (tid nulo)
								$sqlup = "UPDATE historico_cobranca SET status_pagamento='pendente', tipo_cobranca='" . $forma_pagamento_assinante . "' WHERE id='" . $linha["idUser"] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";
								$resultadoup = mysql_query($sqlup)
								or die (mysql_error());
								
							}else{
									
								// ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS FILTRADOS NO INICIO DO LOOP (vencidos e não pagos)
								$sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='" . $forma_pagamento_assinante . "' WHERE id='" . $linha["idUser"] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";
								$resultadoup = mysql_query($sqlup)
								or die (mysql_error());

				
								//Envio de e-mail alertando a cobrança em seu cartão.
								$Assinante = $assinante;
								$AssinanteExplode = explode(" ", $Assinante);
								$emailuser = $email_assinante;
								
								$valor_boleto = $mensalidade_formatada;

								// INSERINDO OS DADOS DO ASSINANTE COM PAGAMENTO POR CARTÃO EFETIVADO NA TABELA DE ENVIO DE MENSAGENS
								mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'cartao_autorizado', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");
						
				
								// ATUALIZANDO OS HISTORICOS DE COBRANÇA INFORMANDO O ENVIO DO EMAIL
								$sqlUp = "UPDATE historico_cobranca SET envio_email='enviado' WHERE idHistorico IN " . $idHistoricoAtualizar . "";
								$resultadoUp = mysql_query($sqlUp)
								or die (mysql_error());					
					
								// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
								$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico ,  data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao) VALUES ('" . $linha["idUser"] . "','".$data_historico_aux."' ,'" . $data_pagamento_cartao . "', '" . $forma_pagamento_assinante . "', '2.1', 'enviado', '";
	
								$sqlup .= $retorno_tid;
	
								$sqlup .= "', " . $valor_pago . ", '" . $numeroCartao . "')";
								$resultadoup = mysql_query($sqlup)
								or die (mysql_error());
								
							}
						
						} 
						
						
						
						//       Caso retorne com erro.          Caso o cartão ocorra uma transação não autorizada.
						else if(($retorno_codigo_erro != '') || ($retorno_codigo_autorizacao == '5')) {
				
							if($retorno_codigo_autorizacao == '5'){ // este código refere-se ao status não autorizado retorno do componente de pagamento locaweb
								
								$acao_relatorio = '2.3'; // Cobrança de cartão com erro (não autorizado)
		
								// ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS FILTRADOS NO INICIO DO LOOP COM ERRO DE NÃO AUTORIZADO (vencidos)
								$sqlup = "UPDATE historico_cobranca SET status_pagamento='não pago', tipo_cobranca='" . $forma_pagamento_assinante . "' WHERE id='" . $linha["idUser"] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";
								$resultadoup = mysql_query($sqlup)
								or die (mysql_error());
			
									
							}else{
				
								$acao_relatorio = '2.6'; // Cartão com erro
				
							}
		
							// LOG DE ACESSOS
							//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $linha['idUser'] . " - PAGAMENTO(S) " . mysql_real_escape_string($idHistoricoAtualizar) . ", NÃO AUTORIZADO(S) - ACAO: " . $acao_relatorio . " - MOTIVO: " . $retorno_mensagem_erro . "')");
		
							
							$sql_checa_envio_email = "SELECT count(*) total FROM historico_cobranca WHERE idHistorico IN " . $idHistoricoAtualizar . " AND envio_email = 'enviado'";
							
							$rs_checa_envio_email = mysql_query($sql_checa_envio_email)
							or die (mysql_error());
							
							$linha_checa_envio_email = mysql_fetch_array($rs_checa_envio_email);
							
							if($linha_checa_envio_email['total'] == 0){
								//Componente de Envio de e-mail.
								$Assinante = $assinante;
								$AssinanteExplode = explode(" ", $Assinante);
								$emailuser = $email_assinante;
								//$assuntoMail = "Erro ao cobrar sua assinatura";
								//include '../mensagens/cartao_erro.php';
								////include '../mensagens/componente_envio.php';	
								//include '../mensagens/componente_envio_novo.php';

								// INSERINDO OS DADOS DO ASSINANTE COM PAGAMENTO POR CARTÃO COM ERRO NA TABELA DE ENVIO DE MENSAGENS
								mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'cartao_nao_autorizado', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . $data_pagamento_cartao . "'");

							}
							
							// ATUALIZANDO OS HISTORICOS DE COBRANÇA INFORMANDO O ENVIO DO EMAIL
							$sqlUp = "UPDATE historico_cobranca SET envio_email='enviado' WHERE idHistorico IN " . $idHistoricoAtualizar . "";
							$resultadoUp = mysql_query($sqlUp)
							or die (mysql_error());
				
							// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
							$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico ,  data, tipo_cobranca, resultado_acao, envio_email, numero_cartao) VALUES ('" . $linha["idUser"] . "','".$data_historico_aux."' ,'" . $data_pagamento_cartao . "', '" . $forma_pagamento_assinante . "', '" . $acao_relatorio . "', 'enviado', '" . $numeroCartao . "')";
							$resultadoup = mysql_query($sqlup)
							or die (mysql_error());			
							
						}

						
					} else{

						// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
						$sqlup = "INSERT INTO relatorio_cobranca (id, idHistorico ,  data, tipo_cobranca, resultado_acao, envio_email, tid, valor_pago, numero_cartao) VALUES ('" . $linha["idUser"] . "','".$data_historico_aux."' ,'" . $data_pagamento_cartao . "', '" . $forma_pagamento_assinante . "', '2.5', 'não enviado', '', " . $valor_pago . ", '" . $numeroCartao . "')";
						$resultadoup = mysql_query($sqlup)
						or die (mysql_error());

						// ATUALIZANDO OS STATUS DOS HISTÓRICOS DE PAGAMENTOS (tid nulo)
						$sqlup = "UPDATE historico_cobranca SET status_pagamento='erro XML', tipo_cobranca='" . $forma_pagamento_assinante . "' WHERE id='" . $linha["idUser"] . "' AND idHistorico IN " . $idHistoricoAtualizar . "";
						$resultadoup = mysql_query($sqlup)
						or die (mysql_error());
						
						
					}
					################ Início do trecho do tratamento do retorno ##############################
					#########################################################################################
					
				}
			}	
		}
		
		// CHECANDO SE JÁ EXISTE HISTÓRICO A VENCER
		$sqlChecaAVencer = "SELECT * FROM historico_cobranca WHERE id='" . $linha["idUser"] . "' AND status_pagamento='a vencer'
							LIMIT 0,1";
		$resultadoChecaAVencer = mysql_query($sqlChecaAVencer)
		or die (mysql_error());

		// CHECANDO SE JÁ EXISTE HISTÓRICO PAGO
		$sqlChecaPago = "SELECT * FROM historico_cobranca WHERE id='" . $linha["idUser"] . "' AND status_pagamento='pago'
							LIMIT 0,1";
		$resultadoChecaPago = mysql_query($sqlChecaPago)
		or die (mysql_error());
		
		if(	($linha['status'] == 'demo' && $retorno_codigo_erro == '' && $retorno_codigo_autorizacao != '5') || ($linha['status'] != 'demo')){ 
			if(mysql_num_rows($resultadoChecaAVencer) <= 0){// tirado em 21/01/2014 && mysql_num_rows($resultadoChecaVencidos) <= 0){
				mysql_query("INSERT INTO historico_cobranca (id, data_pagamento, status_pagamento) VALUES ('" . $linha["idUser"] . "', '$dataPagamento', 'a vencer')");
			}
		}

		//MAL colocar como pagos os pagamentos anteriores
		while ($array_id_atualizar_hstorico=mysql_fetch_array($id_atualizar_historico)) {
			mysql_query("UPDATE historico_cobranca SET status_pagamento = 'pago' WHERE idHistorico = '".$array_id_atualizar_hstorico['idHistorico']."'");
		}
	

	}

	/*
	####################################################################
	LOOP PARA PROCEDER COM A COBRANÇA
	####################################################################
	*/		

	/*
	####################################################################
	LOOP PARA ALTERAÇÃO DE STATUS DE LOGINS
	####################################################################
	*/		
	// ############################## LOG ###############################
	if($show_log == true){
		echo "Procedendo com a alteração de status de login dos usuários (BOLETO E CARTÃO)<BR><BR>";
			ob_flush();
	}
	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'PROCESSANDO A ALTERAÇÃO DOS STATUS DE LOGIN')");

	// ############################## LOG ###############################


	/*
	####################################################################
	DESATIVAÇÃO DE USUARIOS
	####################################################################
	*/
	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'DESATIVAÇÃO DE USUARIOS')");

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
							AND l.status IN ('ativo','demo')
	";
	//AND l.status <>  'cancelado'
	$rs_checa_desativado = mysql_query($sql_checa_desativacao);
	while($checa_desativacao = mysql_fetch_array($rs_checa_desativado)){
				
		switch($checa_desativacao['status']){
			
			case 'ativo':
	
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $checa_desativacao['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $checa_desativacao['email'];
//				$assuntoMail = "Conta Inativa";
//				include '../mensagens/conta_inativa.php';
//				//include '../mensagens/componente_envio.php';
//				include '../mensagens/componente_envio_novo.php';
	
				// INSERINDO OS DADOS DO ASSINANTE QUE PASSOU DE ATIVO PARA INATIVO NA TABELA DE ENVIO DE MENSAGENS
				mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'assinatura_inativa', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");

	
				// ############################## LOG ###############################
				if($show_log == true){
					echo "enviando email de conta inativa para: " . ($emailuser) . "<BR><BR>";
			ob_flush();
				}
				// ############################## LOG ###############################
	
							
				// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$novo_status = 'inativo';
	
				// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$acao_relatorio = '3';
				
				$atualiza_login = true;
				$insere_relatorio = false;

				// INSERE NA TABELA DE METRICAS
				mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $checa_desativacao['id'] . ",'abandonou','" . date('Y-m-d') . "')");

				// ############################## LOG ###############################
				if($show_log == true){
					echo "desativar usuario " . $checa_desativacao['id'] . "<BR><BR>";
			ob_flush();
				}
				// LOG DE ACESSOS
				//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_desativacao['id'] . " - DE ativo PARA inativo. EMAIL ENVIADO PARA " . $emailuser . "')");

				// ############################## LOG ###############################
		
			break;
			
			case 'demo':
	
				//Envio de e-mail alertando o usuário sobre a inatividade.
				$Assinante = $checa_desativacao['assinante'];
				$AssinanteExplode = explode(" ", $Assinante);
				$emailuser = $checa_desativacao['email'];
//				$assuntoMail = "Período de avaliação expirado";
//				include '../mensagens/demo_inativo.php';
//				//include '../mensagens/componente_envio.php';
//				include '../mensagens/componente_envio_novo.php';
	
				// INSERINDO OS DADOS DO ASSINANTE QUE PASSOU DE DEMO PARA DEMO INATIVO NA TABELA DE ENVIO DE MENSAGENS
				mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'demo_inativo', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");


				// ############################## LOG ###############################
				if($show_log == true){
					echo "enviando email de demo inativo para " . ($emailuser) . "<BR><BR>";
			ob_flush();
				}
				// ############################## LOG ###############################

							
				// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$novo_status = 'demoInativo';
	
				// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
				$acao_relatorio = '4';
				
				$atualiza_login = true;
				$insere_relatorio = false;

				// INSERE NA TABELA DE METRICAS
				mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $checa_desativacao['id'] . ",'não convertido','" . date('Y-m-d') . "')");


				// ############################## LOG ###############################
				if($show_log == true){
					echo "desativar usuario " . $checa_desativacao['id'] . "<BR><BR>";
			ob_flush();
				}
				// LOG DE ACESSOS
				//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_desativacao['id'] . " - DE demo PARA demoInativo. EMAIL ENVIADO PARA " . $emailuser . "')");

				// ############################## LOG ###############################

			break;
			
			case 'inativo':

				$atualiza_login = false;
				$insere_relatorio = false;

				// ############################## LOG ###############################
				if($show_log == true){
					echo "usuario " . $checa_desativacao['id'] . " já é inativo<BR><BR>";
			ob_flush();
				}
				// LOG DE ACESSOS
				//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_desativacao['id'] . " - JÁ ESTÁ inativo')");

				// ############################## LOG ###############################
			
			break;

			case 'demoInativo':

				$atualiza_login = false;
				$insere_relatorio = false;

				// INSERE NA TABELA DE METRICAS
				//mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $checa_desativacao['id'] . ",'não convertido','" . date('Y-m-d') . "')");

				// ############################## LOG ###############################
				if($show_log == true){
					echo "usuario " . $checa_desativacao['id'] . " já é demo inativo<BR><BR>";
			ob_flush();
				}
				// LOG DE ACESSOS
				//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_desativacao['id'] . " - JÁ ESTÁ demoInativo')");

				// ############################## LOG ###############################
			
			break;
								
		}

		if($atualiza_login == true){

/*
			mysql_query("
							UPDATE login
							SET status = '" . $novo_status . "'
							WHERE id = '" . $checa_desativacao["id"] . "'
			");

			MUDANÇA - USUARIO PODER CADASTRAR MAIS DE UMA EMPRESA
								ATUALIZAR TODOS OS REGISTROS DA TABELA DE LOGIN PARA O NOVO STATUS
								UTILIZAR O CAMPO idUsuarioPai
*/
			mysql_query("
							UPDATE login
							SET status = '" . $novo_status . "'
							WHERE idUsuarioPai = '" . $checa_desativacao["id"] . "'
			");
			
		}
		
		if($insere_relatorio == true){

			// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
			$sqlup = "INSERT INTO relatorio_cobranca (id, data, resultado_acao, tipo_cobranca, envio_email) VALUES ('" . $checa_desativacao["id"] . "', '" . date('Y-m-d') . "', '" . $acao_relatorio . "', '" . $checa_desativacao['forma_pagameto'] . "', 'enviado')";
			$resultadoup = mysql_query($sqlup)
			or die (mysql_error());
	
	
			// ############################## LOG ###############################
			if($show_log == true){
				echo "inserindo registro no relatorio de cobranca informando a desativação da conta do usuario " . $checa_desativacao['id'] . " (afetou: " . mysql_affected_rows() . ")<BR><BR>";
			ob_flush();
			}
			// ############################## LOG ###############################
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
	// LOG DE ACESSOS
	//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'ATIVAÇÃO DE USUARIOS')");

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
							AND l.status IN ('inativo','demoInativo','demo')
	";
//					AND l.status <>  'cancelado'

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
				
				// ############################## LOG ###############################
					if($show_log == true){
						echo "usuario " . $checa_ativacao['id'] . " já é ativo<BR><BR>";
				ob_flush();
					}
					// LOG DE ACESSOS
					//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - JÁ ESTÁ ativo')");

					// ############################## LOG ###############################
			
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
		
						// INSERE NA TABELA DE METRICAS
						mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $checa_ativacao['id'] . ",'ativado','" . date('Y-m-d') . "')");


						// ############################## LOG ###############################
						if($show_log == true){
							echo "ativar usuário " . $checa_ativacao['id'] . " que era demo<BR><BR>";
				ob_flush();
						}
						// LOG DE ACESSOS
						//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - DE demo PARA ativo')");

						// ############################## LOG ###############################
						
					}else{

						$atualiza_login = false;
						$insere_relatorio = false;

						// ############################## LOG ###############################
						if($show_log == true){
							echo "este usuário (" . $checa_ativacao['id'] . ") é um demo e ainda estamos aguardando pagamento<BR><BR>";
				ob_flush();
						}
						// LOG DE ACESSOS
						//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - USUARIO É demo E NÃO POSSUI PAGAMENTOS pagos. CONTINUA demo')");

						// ############################## LOG ###############################

						
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
						$emailuser = $checa_ativacao['email'];
	//					$assuntoMail = "Reativação de conta";
	//					include '../mensagens/conta_reativada.php';
	//					//include '../mensagens/componente_envio.php';
	//					include '../mensagens/componente_envio_novo.php';

						// INSERINDO OS DADOS DO ASSINANTE QUE PASSOU DE INATIVO PARA ATIVO NA TABELA DE ENVIO DE MENSAGENS
						mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'assinatura_reativada', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");

			
						// INSERE NA TABELA DE METRICAS
						mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $checa_ativacao['id'] . ",'recuperado','" . date('Y-m-d') . "')");
		
						// ############################## LOG ###############################
						if($show_log == true){
							echo "enviando email de reativação " . ($emailuser) . "<BR><BR>";
				ob_flush();
						}
						// ############################## LOG ###############################
			
									
						// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
						$novo_status = 'ativo';
			
						// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
						$acao_relatorio = '5';
						
						$atualiza_login = true;
						$insere_relatorio = false;
		
						// ############################## LOG ###############################
						if($show_log == true){
							echo "reativar usuario inativo " . $checa_ativacao['id'] . "<BR><BR>";
				ob_flush();
						}
						// LOG DE ACESSOS
						//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - DE inativo PARA ativo. EMAIL ENVIADO PARA " . $emailuser . "')");
						// ############################## LOG ###############################
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
						
						// INSERE NA TABELA DE METRICAS
						mysql_query("insert into metricas_conversao (id_login, status, data) VALUES (" . $checa_ativacao['id'] . ",'ativado','" . date('Y-m-d') . "')");


						//Envio de e-mail alertando o usuário sobre a inatividade.
						$Assinante = $checa_ativacao['assinante'];
						$AssinanteExplode = explode(" ", $Assinante);
						$emailuser = $checa_ativacao['email'];
	//					$assuntoMail = "Reativação de conta";
	//					include '../mensagens/conta_reativada.php';
	//					//include '../mensagens/componente_envio.php';
	//					include '../mensagens/componente_envio_novo.php';

						// INSERINDO OS DADOS DO ASSINANTE QUE PASSOU DE DEMO INATIVO PARA ATIVO NA TABELA DE ENVIO DE MENSAGENS
						mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'assinatura_reativada', nome = '".$AssinanteExplode[0]."', email = '" . $emailuser . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");
						
			
						// ############################## LOG ###############################
						if($show_log == true){
							echo "enviando email de reativação de demoInativo " . ($emailuser) . "<BR><BR>";
				ob_flush();
						}
						// ############################## LOG ###############################
			
									
						// DETERMINANDO O NOVO STATUS DO USUARIO COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
						$novo_status = 'ativo';
			
						// DETERMINANDO A AÇÃO A SER INSERIDA NO RELATORIO DE COBRANCA COM BASE NO QUE RETORNOU DA CONSULTA DA LINHA 581
						$acao_relatorio = '5';
						
						$atualiza_login = true;
						$insere_relatorio = false;
		
						// ############################## LOG ###############################
						if($show_log == true){
							echo "reativar usuario demoInativo " . $checa_ativacao['id'] . "<BR><BR>";
				ob_flush();
						}
						// LOG DE ACESSOS
						//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - DE demoInativo PARA ativo. EMAIL ENVIADO PARA " . $emailuser . "')");
		
						// ############################## LOG ###############################
					}else{

						$atualiza_login = false;
						$insere_relatorio = false;
						// LOG DE ACESSOS
						//mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO " . $checa_ativacao['id'] . " - demoInativo NÃO POSSUI PAGAMENTOS')");
						
					}
					
				break;
									
			}

			if($atualiza_login == true){

	/*			
				mysql_query("
								UPDATE login
								SET status = '" . $novo_status . "'
								WHERE id = '" . $checa_ativacao["id"] . "'
				");

				MUDANÇA - USUARIO PODER CADASTRAR MAIS DE UMA EMPRESA
									ATUALIZAR TODOS OS REGISTROS DA TABELA DE LOGIN PARA O NOVO STATUS
									UTILIZAR O CAMPO idUsuarioPai
	*/
				mysql_query("
								UPDATE login
								SET status = '" . $novo_status . "'
								WHERE idUsuarioPai = '" . $checa_ativacao["id"] . "'
				");

			
			}
			
			if($insere_relatorio == true){
				
				// INSERINDO O REGISTRO NO RELATÓRIO DE COBRANÇA PARA ESTE PAGAMENTO.
				$sqlup = "INSERT INTO relatorio_cobranca (id, data, resultado_acao, tipo_cobranca, envio_email) VALUES ('" . $checa_ativacao["id"] . "', '" . date('Y-m-d') . "', '" . $acao_relatorio . "','" . $checa_desativacao['forma_pagameto'] . "', 'enviado')";
				$resultadoup = mysql_query($sqlup)
				or die (mysql_error());
		
		
				// ############################## LOG ###############################
				if($show_log == true){
					echo "inserindo registro no relatorio de cobranca do usuario " . $checa_ativacao['id'] . " (afetou: " . mysql_affected_rows() . ")<BR><BR>";
				ob_flush();
				}
				// ############################## LOG ###############################
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
		

		// ############################## LOG ###############################
		if($show_log == true){
			echo "fim da cobrança<BR><BR>";
				ob_end_flush();
		}
		// ############################## LOG ###############################

		
		
	$boletos = new Gerar_boletos();	

	function enviarEmailVencidos(){
		//Pega os usuarios que tiveram suas contas vencidas
		$consulta_demo_info = mysql_query("SELECT *,DATEDIFF(data_pagamento, DATE(now())) from historico_cobranca WHERE DATEDIFF(data_pagamento, DATE(now())) < -1 AND `status_pagamento` = 'vencido'");
		
		while ( $objeto_demo_info = mysql_fetch_array($consulta_demo_info) ) {
			
			$consulta_email_login = mysql_query("SELECT * FROM login WHERE idUsuarioPai = id AND idUsuarioPai = '".$objeto_demo_info['id']."' LIMIT 1");
			$objeto_email_login = mysql_fetch_array($consulta_email_login);

			if( $objeto_email_login['email'] != "" ){

				$consulta_email_enviado = mysql_query("SELECT * FROM envio_emails_cobranca WHERE tipo_mensagem = 'user_vencidos' AND email = '".$objeto_email_login['email']."' AND DATEDIFF(data, DATE(now())) >= -10");
				$objeto_email_enviado = mysql_fetch_array($consulta_email_enviado);
				if( $objeto_email_enviado['email'] == "" )
					mysql_query("INSERT INTO envio_emails_cobranca SET tipo_mensagem = 'user_vencidos', nome = '".$objeto_email_login['assinante']."', email = '" . $objeto_email_login['email'] . "', status = 0, data = '" . date("Y-m-d H:i:s") . "'");

			}

		}
	}
	enviarEmailVencidos();
}




// OS FILTROS
//if($get_assinante != "") {
if($get_email != "") {

	$sql = "SELECT * FROM relatorio_cobranca r INNER JOIN login l ON r.id = l.id WHERE 1 = 1 ";
//	$sql .= " AND l.assinante LIKE '%" . $get_assinante . "%'";
//	$titulo_vermelho = "Busca por Assinante: " . $get_assinante . "";
	$sql .= " AND l.email LIKE '%" . $get_email . "%'";
	$titulo_vermelho = "Busca por E-mail: " . $get_email . "";

} else {

	if( $id != "" ){
		$sql = "SELECT * FROM relatorio_cobranca WHERE 1 = 1 ";
		$sql .= " AND id = '".$id."' ";		
		$titulo_vermelho = "Busca por ID: " . $id . "";
	}
	else{
	
		$sql = "SELECT * FROM relatorio_cobranca WHERE 1 = 1 ";
		$sql .= " AND resultado_acao <> '3'";

		if($tid != "") {

			$sql .= " AND tid='$tid' ";

			$titulo_vermelho = "Busca por TID: " . $tid . "";

		}else{

			if($dataInicio != "" && $dataFim != "" && $dataFim != "--" && $dataFim != "--" ) {
				
				if($tipoData == 'data'){
					$textoTipoData = "Data de Venda";
				}else{
					$textoTipoData = "Data de Pagamento";
				}
				
				$sql .= " AND " . $tipoData . " BETWEEN '$dataInicio' AND '$dataFim'";
			}
			
			if($resultadoAcao != "todos") {
				if($resultadoAcao == 1){
					$sql .= " AND ( resultado_acao = '1.2' OR resultado_acao = '2.3' ) ";
				}
				else if($resultadoAcao == 3){
					$sql .= " AND resultado_acao = '9.9'";
				}
				else if($resultadoAcao == 4){
					$sql .= " AND resultado_acao = '10.1'";
				}
				else{
					$sql .= " AND resultado_acao in ('2.1','2.2','2.3','2.4','2.5')";
				}
			}
		
			$titulo_vermelho = "Relatório " . ($dataInicio == $dataFim ? "do dia " . date('d/m/Y',strtotime($dataInicio)) : "por " . $textoTipoData . " entre " . date('d/m/Y',strtotime($dataInicio)) . " até " . date('d/m/Y',strtotime($dataFim)) );
			
		}
}
}
$sql .= " ORDER BY " . $tipoData . " desc, idRelatorio DESC";


//print_r($sql);

//echo $sql . '<BR><BR>';
// echo $sql;
$resultado = mysql_query($sql)
or die (mysql_error());

?>
<div style="position: relative; float: left; width: 100%;">
    <a class="imagemDica" position="right" div="divFormUpload2" id="btCarregaArquivo2" style="z-index: 99;position: absolute; padding: 5px 5px 0 0;  right: 180px; text-align:right; font-weight: normal; font-size: 11px; text-decoration: underline; color: #336699;">Carregar arquivo CIELO</a>
    
    <a class="imagemDica" position="right" div="divFormUpload" id="btCarregaArquivo" style="z-index: 99;position: absolute; padding: 5px 5px 0 0;  right: 0; text-align:right; font-weight: normal; font-size: 11px; text-decoration: underline; color: #336699;">Carregar arquivo de notas fiscais</a>
    <div class="tituloVermelho">
        <?=$titulo_vermelho?>
    </div>
</div>


<div style="clear: both;"></div>
<table border="0" cellspacing="2" cellpadding="4" style="font-size:11px;" width="100%">
    <tr>
     <th width="7%" align="center">Venda</th>
    <th width="7%" align="center">Pgto.</th>
    <th width="24%" align="center">Razão Social</th>
    <th width="12%" align="center">CNPJ</th>
    <th width="13%" align="center">TID</th>
<!--    <th align="center">Nº cartão</th>-->
    <th width="5%" align="center">Valor</th>
    <th width="11%" align="center">Tipo</th>
    <th width="13%" align="center">Resultado da Ação</th>
    <th width="4%" align="center">nº NF</th>
    </tr> 
<?php
while ($linha=mysql_fetch_array($resultado)) {
	$sql2 = "SELECT * FROM dados_cobranca WHERE id='" . $linha["id"] . "'";
	$resultado2 = mysql_query($sql2)
	or die (mysql_error());
	$linha2=mysql_fetch_array($resultado2);
?>
    <tr class="guiaTabela" style="background-color:#<? 
//		if($linha["id"] == '1248'){
//			echo "ffc3c3";
//		} else {
			if(($linha["resultado_acao"] == "2.1" || $linha["resultado_acao"] == "1.2") && $linha['emissao_NF'] == 0){
				echo "fff6c3";
			} 
			else if(($linha["resultado_acao"] == "9.4" || $linha["resultado_acao"] == "9.5" || $linha["resultado_acao"] == "9.6" || $linha["resultado_acao"] == "9.7") && $linha['emissao_NF'] == 0){
				echo "fdfddd";
			}  
			else if($linha["resultado_acao"] == "9.9" ){
				echo 'ffbebe';
			}
			else {
				echo "FFF";
			}
//		}
		?>" valign="top">
		<td><?=date('d/m/Y',strtotime($linha["data"]))?></td>
		<td><?=($linha["data_pagamento"] != '' ? date('d/m/Y',strtotime($linha["data_pagamento"])) : '')?></td>
		<td><a href="cliente_administrar.php?id=<?=$linha["id"]?>" target="_blank">
		  <?=(strlen($linha2["sacado"]) > 30 ? substr($linha2["sacado"],0,30) . "..." : $linha2["sacado"])?>
		</a></td>
		<td align="center"><?=($linha2["documento"])?></td>
<!--
	    <td align="center"><?php
		switch ($linha["tipo_cobranca"]){
			case 'visa': $forma_pagamento = '<img src="../images/visaicon.png" width="35" height="20" title="Visa" />'; 
			break;
			case 'mastercard': $forma_pagamento = '<img src="../images/mastercardicon.png" width="31" height="20" title="MasterCard" />'; 
			break;
			case 'boleto': $forma_pagamento = '<img src="../images/boletoicon.gif" width="39" height="20" title="Boleto Bancário" />'; 
			break;
			default: $forma_pagamento = ''; break;
		}
		echo $forma_pagameto;
		?></td>
-->
        <td align="center" class="col_tid" id="idRel_<?=$linha["idRelatorio"]?>"><?=$linha['tid'] != '' ? $linha['tid'] : '';?></td>

        <!--<td>
        <?php
        echo ($linha['numero_cartao'] ? ($linha['numero_cartao']) : '');
        ?>
        </td>-->
        <td align="right">
        <?php
        echo $linha['valor_pago'] != '' && (int)$linha['valor_pago'] != '0' ? number_format($linha['valor_pago'],2,",",".") : '';
        ?>
        </td>
       <td><?php
		switch ($linha["resultado_acao"]){
			case '1.2': $resultado_acao = 'Mensalidade'; break; 				// vai
			case '2.1': $resultado_acao = 'Mensalidade'; break; 					// vai
			case '2.2': $resultado_acao = 'Mensalidade'; break;						// vai
			case '2.3': $resultado_acao = 'Mensalidade'; break;	// vai
			case '2.4': $resultado_acao = 'Mensalidade'; break;	// vai
			case '2.5': $resultado_acao = 'Mensalidade'; break;	// vai
			case '2.6': $resultado_acao = 'Mensalidade'; break;	// vai
			case '9.3': $resultado_acao = 'Contador'; break;
			case '9.4': $resultado_acao = 'A - Empresa'; break;
			case '9.5': $resultado_acao = 'A - Sociedade'; break;
			case '9.6': $resultado_acao = 'Decore'; break;
			case '9.7': $resultado_acao = 'DBE'; break;
			case '9.9': $resultado_acao = 'Mensalidade'; break; 
			case '10.1': $resultado_acao = 'Certificado Digital'; break; 

		}

		// switch ($linha["resultado_acao"]){
		// 	case '1': $resultado_acao = 'Envio de boleto'; break; 							// não vai
		// 	case '1.2': $resultado_acao = 'Boleto com sucesso'; break; 				// vai
		// 	case '2.1': $resultado_acao = 'Cartão com sucesso'; break; 					// vai
		// 	case '2.2': $resultado_acao = 'Cartão com erro'; break;						// vai
		// 	case '2.3': $resultado_acao = 'Cartão não autorizado'; break;	// vai
		// 	case '2.4': $resultado_acao = 'TID em branco ou nulo'; break;	// vai
		// 	case '2.5': $resultado_acao = 'ERRO retorno cobrança'; break;	// vai
		// 	case '2.6': $resultado_acao = 'Token não encontrado'; break;	// vai
		// 	case '3': $resultado_acao = 'Desativação de conta'; break;								// não vai
		// 	case '4': $resultado_acao = 'Demo expirado'; break;										// não vai
		// 	case '5': $resultado_acao = 'Reativação de conta'; break;	// não vai
		// 	case '6': $resultado_acao = 'Conta cancelada'; break;								// não vai
		// 	case '7': $resultado_acao = 'Conta reativada'; break;								// não vai
		// 	case '8': $resultado_acao = 'Demo a vencer'; break;							// não vai
		// 	case '9': $resultado_acao = 'Inconsistência boleto'; break; 
		// 	case '9.1': $resultado_acao = 'Boleto valor menor'; break; 
		// 	case '9.2': $resultado_acao = 'Boleto valor maior'; break; 
		// 	case '9.3': $resultado_acao = 'Data do boleto'; break; 
		// 	case '9.9': $resultado_acao = 'Venda cancelada'; break; 
		// 	case '10.1': $resultado_acao = 'Certificado Digital'; break; 

		// }


		echo '<span style="cursor:pointer">'.$resultado_acao.'</span>';
		
		?></td>
		 <td align="left" id="<?=$linha["idRelatorio"]?>" class="resultado_da_acao"><?php


		switch ($linha["resultado_acao"]){
			case '1': $resultado_acao = 'Envio de boleto'; break; 							// não vai
			case '1.2': $resultado_acao = 'Boleto com sucesso'; break; 				// vai
			case '2.1': $resultado_acao = 'Cartão com sucesso'; break; 					// vai
			case '2.2': $resultado_acao = 'Cartão com erro'; break;						// vai
			case '2.3': $resultado_acao = 'Cartão não autorizado'; break;	// vai
			case '2.4': $resultado_acao = 'TID em branco ou nulo'; break;	// vai
			case '2.5': $resultado_acao = 'ERRO retorno cobrança'; break;	// vai
			case '2.6': $resultado_acao = 'Token não encontrado'; break;	// vai
			case '3': $resultado_acao = 'Desativação de conta'; break;								// não vai
			case '4': $resultado_acao = 'Demo expirado'; break;										// não vai
			case '5': $resultado_acao = 'Reativação de conta'; break;	// não vai
			case '6': $resultado_acao = 'Conta cancelada'; break;								// não vai
			case '7': $resultado_acao = 'Conta reativada'; break;								// não vai
			case '8': $resultado_acao = 'Demo a vencer'; break;							// não vai
			case '9': $resultado_acao = 'Inconsistência boleto'; break; 
			case '9.1': $resultado_acao = 'Boleto valor menor'; break; 
			case '9.2': $resultado_acao = 'Boleto valor maior'; break; 
			case '9.3': $resultado_acao = 'Boleto com sucesso'; break;
			case '9.4': $resultado_acao = 'Boleto com sucesso'; break; 
			case '9.5': $resultado_acao = 'Boleto com sucesso'; break; 
			case '9.6': $resultado_acao = 'Boleto com sucesso'; break; 
			case '9.7': $resultado_acao = 'Boleto com sucesso'; break; 
			case '9.9': $resultado_acao = 'Venda cancelada'; break; 
			case '10.1': $resultado_acao = 'Boleto com sucesso'; break; 

		}	
		echo '<span style="cursor:pointer">'.$resultado_acao.'</span>';

		 ?>
        <td align="center" class="col_nf" id="idRel_<?=$linha["idRelatorio"]?>"><?=($linha["numero_NF"] > 0 ? str_pad($linha["numero_NF"],4,"0",STR_PAD_LEFT) : '');?></td>

<?php
//        <td align="center">
//        if($linha["resultado_acao"] == "2.1" || $linha["resultado_acao"] == "1.2"){
//	        <div id="linkNF_ ? = $linha["idRelatorio"] ? ">
//	        <a class="marcaEmissaoNF" href="#"> ? echo $linha['emissao_NF'] == 0 ? "N" : "S" ? </a></div>
//        }
//        </td>
?>

    </tr>
<?php } ?>
  </table>  
</div>

<script>

$(document).ready(function(e) {
	
	var resultado_acao_anterior;

	$(".resultado_da_acao span").click(function() {
		var valor = $(this).html();
		resultado_acao_anterior = valor;
		var item = $(this).parent();
		$(item).empty();
		$.ajax({
			url:'ajax.php'
			, data: 'acao=resultado_acao&resultado_acao_anterior='+valor
			, type: 'post'
			, async: true
			, cache:false
			, success: function(retorno){
				$(item).append(retorno);
				console.log($(item).find("select").focus());
			}
		});
	});
	


$.fn.extend({
  enviaNumeroNF: function (id_pagto, objCampo) {
	$.ajax({
	  url:'cad_nf.php'
	  , data: 'id_pagto='+id_pagto+'&valor=' + objCampo.val()
	  , type: 'post'
	  , async: true
	  , cache:false
	  , success: function(retorno){
			objCampo.parent().html(retorno);
	  }
	});  
  }
  ,enviaNumeroTID: function (id_pagto, objCampo) {
//alert(id_pagto);
//alert(objCampo.val());
	$.ajax({
	  url:'cad_tid.php'
	  , data: 'id_pagto='+id_pagto+'&valor=' + objCampo.val()
	  , type: 'post'
	  , async: true
	  , cache:false
	  , success: function(retorno){
		//if(retorno.length > 0){
			objCampo.parent().html(retorno);
			if(confirm('Deseja alterar para o status "Cartão com sucesso"?')){
				$.ajax({
				  url:'cad_status.php'
				  , data: 'id_pagto='+id_pagto+'&valor=2.1'
				  , type: 'post'
				  , async: true
				  , cache:false
				  , success: function(retorno){
					if(retorno != ''){
						location.href = 'cobranca.php<?=($_GET['dataInicio'] != '' || $_GET['dataFim'] != '' || $_GET['acao'] != '' || $_GET['tid'] != '' || $_GET['email'] != '' || $_GET['tipoData'] != '' ? '?dataInicio=' . $_GET['dataInicio'] . '&dataFim=' . $_GET['dataFim'] . '&acao=' . $_GET['acao'] . '&tid=' . $_GET['tid'] . '&email=' . $_GET['email'] . '&tipoData=' . $_GET['tipoData'] : '')?>';
					}
				  }
				});  
			}
		//}
	  }
	});  
  }
  ,escondeCampoTexto: function(classe_colunas) {
	var classe = classe_colunas.attr('class');

	if(classe_colunas.find('input[type="text"]').val() != ""){
		if(classe == 'col_nf'){
			classe_colunas.html(("000" + classe_colunas.find('input[type="text"]').val()).substr(-4));
		}else{
			if(classe == 'col_tid'){
				classe_colunas.html((classe_colunas.find('input[type="text"]').val()));
			}else{
				classe_colunas.html();
			}
		}
	}
  }

  ,montaCampoTexto: function(classe_objeto, value_campo) {
	  if(classe_objeto == 'col_nf'){
		  return '<input style="width:45px; text-align: center; font-size: 100%" type="text" name="numero_nf_cobranca" id="numero_nf_cobranca" value="' + value_campo + '">';
	  }
	  if(classe_objeto == 'col_tid'){
		  return '<input style="width:90%; text-align: center; font-size: 100%" type="text" name="numero_tid_cobranca" id="numero_tid_cobranca" value="' + value_campo + '">';
	  }
  }
});


	
	$('.col_tid').each(function(index, element) {
       
	    $(this).css('cursor','pointer'); // css da celula
		
		$(this).bind('click',function(e){ // evento click na celula
		
			e.preventDefault();
			
			var linha_selecionada = ($('.col_tid').index(this)); // pegando o indice da linha selecionada

			$('.col_tid').each(function(index, element) { // transformando os campos texto em texto puro
//alert($(this).attr('class'));
//				$(this).escondeCampoTexto($(this).attr('class'));
				$(this).escondeCampoTexto($(this));

			});
			
			var conteudo_atual = $(this).html(); // separando o conteudo da celula para colocar no value do campo
			
			if(parseInt(conteudo_atual) >= 0 || conteudo_atual == ""){ // se for numero
								
				$(this).empty().html($(this).montaCampoTexto($(this).attr('class'),conteudo_atual));// cria o campo texto com o conteudo da celula
				
				$(this).children('input[type="text"]').addClass('campoNumeroTIDCobranca'); // adicionando classe para aceitar somente numeros

				$('#numero_tid_cobranca').select(); // selecionando o conteudo do campo para uma edição mais rápida

				$('#numero_tid_cobranca').bind('blur',function(e){ // no blur do campo

					var id_pagto = $(this).parent().attr('id').replace("idRel_",""); // pegando o id do paramento
	
					$(this).enviaNumeroTID(id_pagto, $(this)); // cadastra o numero da nota

					//$(this).parent().html($(this).val()); // tirando o campo texto da celula

				});
			
			}
		});
			
    });

	
	$('.col_nf').each(function(index, element) {
       
	   var $this = $(this);
	   
	    $this.css('cursor','pointer') // css da celula
		.bind('click',function(e){ // evento click na celula
			e.preventDefault();
			var linha_selecionada = ($('.col_nf').index($this)); // pegando o indice da linha selecionada
			$('.col_nf').each(function(index, element) { // transformando os campos texto em texto puro
				//$(this).escondeCampoTexto($(this));
			});
			var conteudo_atual = $this.html(); // separando o conteudo da celula para colocar no value do campo
//			if(conteudo_atual != ''){ // se for numero
				
				$this.empty().html($this.montaCampoTexto($this.attr('class'), conteudo_atual));// cria o campo texto com o conteudo da celula
				
				$this.children('input[type="text"]').addClass('campoNumeroNFCobranca'); // adicionando classe para aceitar somente numeros

				$('#numero_nf_cobranca').select(); // selecionando o conteudo do campo para uma edição mais rápida

				$('#numero_nf_cobranca').bind('blur',function(e){ // no blur do campo
					var $campo = $(this);
					var id_pagto = $campo.parent().attr('id').replace("idRel_",""); // pegando o id do paramento
					$campo.enviaNumeroNF(id_pagto, $campo); // cadastra o numero da nota
					$this.escondeCampoTexto($this);
					//$(this).parent().html($(this).val()); // tirando o campo texto da celula

				});
			
//			}
		});
			
    });
		
    $('.marcaEmissaoNF').click(function(e){
		e.preventDefault();
		var id_pagto = $(this).parent().attr('id').replace("linkNF_","");
		var url = location.href.replace("#","");
		var separador = "";
		(url.search(/\?/i) > 0 ? separador = "&" : separador = "?")
		
		if(id_pagto != '' && id_pagto != 'undefined'){
			location.href=(url + separador + "toggleNF=" + id_pagto);
		}
	});
});

</script>
<?php include '../rodape.php' ?>