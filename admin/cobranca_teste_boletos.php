<?php 
include '../conect.php';

include '../session.php';

include('../classes/phpmailer.class.php');
	
include 'check_login.php';

include 'header.php';
$show_log = true;
	

	function ProcessaArquivoRetorno($vTemp_name, $vNome_arquivo, $ignora_upload, $vMensalidade){

		//ob_flush();

		$retorno = "";
		
		// ATRIBUINDO O ARQUIVO À VARIÁVEL
		$arquivo = file($vNome_arquivo);
		
		// PROCESSANDO O RODAPÉ (LINHAS PENULTIMA E ULTIMA(
		$total_linhas_arquivo = (int)(substr($arquivo[count($arquivo)-1],23,6));
		$total_pagamentos = (($total_linhas_arquivo - 4)/2);

//		printf("<BR>" . ($total_linhas_arquivo ) . "<BR>");

	
		// PROCESSANDO O CABEÇALHO (LINHAS 1 E 2)
		$data_pagamento = (substr($arquivo[0],143,8));

		$data_pagto = date('Y-m-d',strtotime((substr($data_pagamento,4,4)) . "-" . substr($data_pagamento,2,2) . "-" . substr($data_pagamento,0,2)));
		$data_hoje = date('Y-m-d');
		
		// VARIAVEL QUE CONTROLA QUANTOS PAGAMENTOS FORAM PROCESSADOS PARA CHECAGEM FINAL DA QUANTIDADE DE PAGAMENTOS PELA QUANTIDADE DE PAGAMENTOS PROCESSADOS
		$pagamentos_processados = 0;
		
		// PERCORRENDO O ARQUIVO - TIRANDO AS 2 PRIMEIRAS (CABEÇALHO) E AS 2 ULTIMAS (RODAPE) LINHAS PARA TRAZER DADOS REFERENTES AO PAGAMENTO
		for($i=0; $i<=($total_linhas_arquivo); $i++){

//			printf("<BR>" . ($total_linhas_arquivo) . "<BR>");

//			printf("<BR> LINHA PROCESSADA: " . $arquivo[$i] . "<BR>");

			// CHEGANDO SE A LINHA EM QUESTÃO É DE PAGAMENTO (POSSUI O "T" NA POSIÇÃO 13)
			if(substr($arquivo[$i],13,1) == "T"){
				// ATRIBUINDO VALORES ÀS VARIÁVEIS QUE SERÃO CADASTRADAS NA TABELA DE COBRANÇA
				//echo '<strong>id_usuario:</strong> ' . (int)(substr($arquivo[$i],44,4)) . "<br>";
				//echo '<strong>mes: </strong>' . (int)(substr($arquivo[$i],48,2)) . "<br>";
				
				$id_user = (int)(substr($arquivo[$i],44,4));
				$mes_boleto = (string)(substr($arquivo[$i],48,2));
				$ano_boleto = (string)(substr($arquivo[$i],50,4));
				$valor_pago = (int)(substr($arquivo[$i],81,13));
				$valor_decimal_pago = (int)(substr($arquivo[$i],94,2));


				// PEGANDO DADOS DO USUARIO NO BANCO DE DADOS
				$sql_dados_usuario = "SELECT * FROM login WHERE id = '" . $id_user . "'";
				$rs_dados_usuario = mysql_query($sql_dados_usuario);
				$linha_dados_usuario = mysql_fetch_array($rs_dados_usuario);
				
				$status_usuario = $linha_dados_usuario['status'];
				
				/*
				A VARIAVEL mes_boleto CONTROLA SE A BAIXA DEVE SER FEITA EM UM HISTÓRICO ESPECÍFICO - pagamento de 50,00 - OU NOS x ULTIMOS QUE NÃO ESTAO COM STATUS PAGO OU PERDOADO
				*/
				
/*				printf($id_user);
				printf($mes_boleto);
				printf($ano_boleto);
exit;*/
				

				// CHECANDO SE JÁ EXISTE PAGAMENTO PARA ESTE USUARIO NESTE MES
				//$sqlChecaPagamentoMes = "SELECT * FROM relatorio_cobranca WHERE id='" . $id_user . "' AND MONTH(data)='" . $mes_boleto . "' AND YEAR(data)='" . $ano_boleto . "' LIMIT 0,1";
//				printf("<br>".$sqlChecaPagamentoMes."<BR>");
				//$resultadoChecaPagamentoMes = mysql_query($sqlChecaPagamentoMes)
				//or die (mysql_error());
				
//				printf("valor: " . $valor_pago."<BR>");
//				printf("mens: " . $vMensalidade."<BR>");
				//exit;
				
				$loop_pagamentos = (int)($valor_pago / 55);
				
				
				// CRIANDO LOOP PARA DAR BAIXA EM QUANTOS PARAMENTOS FOREM NECESSÁRIOS DEPENDENDO DO VALOR PAGO
				//for($p=1; $p <= $loop_pagamentos; $p++){

				
				if($mes_boleto == '00'){
					
					

				print_r('???');
				exit;

					
					// PEGANDO OS ULTIMOS PAGAMENTOS VENCIDOS OU NAO PAGOS - DEPENDE DO VALOR PAGO PARA PEGAR OS x ULTIMOS
					$sql = "SELECT * FROM historico_cobranca WHERE id='" . $id_user . "' AND status_pagamento NOT IN ('pago','perdoado') ORDER BY idHistorico LIMIT 0,".$loop_pagamentos;
//					printf("<br>".$sql."<BR>");
					$resultado = mysql_query($sql)
					or die (mysql_error());

					$pagamentos_localizados = mysql_num_rows($resultado);

					
					/*
					FICOU DEFINIDO QUE SE O BOLETO SE REFERIR A MAIS DE UM PAGAMENTO E HOUVER MENOS PAGAMENTOS PASSÍVEIS DE ALTERAÇÃO DE STATUS DE PAGAMENTO, DEVE SER INSERIDO UM REGISTRO NO RELATORIO DE COBRANÇA INFORMANDO A INCONSISTÊNCIA OCORRIDA
					*/
					if($loop_pagamentos < $pagamentos_localizados){
						
						//Mensagem a exibir no relatório final.
						// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTA INCONSISTENCIA
						// EM 19/01/2015 - FOI SOLICITADO QUE FOSSE TIRADO OS STATUS 9.1 e 9.2 E COLOCASSE EM UMA COLUNA DE OBSERVAÇÃO


/*
						$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, observacao) VALUES ('" . $id_user . "', '" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', '', " . $valor_pago . ".00,'Boleto valor menor')";
						//$RETORNO .= $sqlup . "<br>";
		//					printf("<br>".$sqlRelCobranca."<BR>");
						$resultadoup = mysql_query($sqlRelCobranca)
						or die (mysql_error());
*/

						
					}else{
						
						if($loop_pagamentos > mysql_num_rows($resultado)){
	
							//Mensagem a exibir no relatório final.
							// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTA INCONSISTENCIA


/*
							$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago, observacao) VALUES ('" . $id_user . "', '" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', '', " . $valor_pago . ".00,'Boleto valor maior')";
							//$RETORNO .= $sqlup . "<br>";
			//					printf("<br>".$sqlRelCobranca."<BR>");
							$resultadoup = mysql_query($sqlRelCobranca)
							or die (mysql_error());
*/

						
						} else {

							while($linha=mysql_fetch_array($resultado)){
						
							// ATUALIZA O HISTORICO DE COBRANCA
/*
							 $sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='boleto', envio_email='enviado' WHERE idHistorico='" . $linha['idHistorico'] . "'";
								$resultadoup = mysql_query($sqlup)
								or die (mysql_error());
*/
							}
							
							//Mensagem a exibir no relatório final.
							// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTE PAGAMENTO

/*
							$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago) VALUES ('" . $id_user . "', '" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', 'enviado', " . $valor_pago . ".00)";
							$resultadoup = mysql_query($sqlRelCobranca)
							or die (mysql_error());
*/
						
						}


					}
					
				} else{
				
	
					$sql_checamesboleto = "SELECT * FROM historico_cobranca WHERE MONTH(data_pagamento) = '" . $mes_boleto . "' AND YEAR(data_pagamento) = '" . $ano_boleto . "' AND id = '" . $id_user . "'";
					$rs_checaMesBoleto = mysql_query($sql_checamesboleto);
					// CHECANDO SE LOCALIZA O MES A SER QUITADO
					if(mysql_num_rows($rs_checaMesBoleto) > 0){

						$checaMesBoleto = mysql_fetch_array($rs_checaMesBoleto);
						if($checaMesBoleto['status_pagamento'] != 'pago' && $checaMesBoleto['status_pagamento'] != 'perdoado'){

	
							$sqlup = "UPDATE historico_cobranca SET status_pagamento='pago', tipo_cobranca='boleto', envio_email='enviado' WHERE MONTH(data_pagamento) = '" . $mes_boleto . "' AND YEAR(data_pagamento) = '" . $ano_boleto . "' AND id = '" . $id_user . "'";
	
							// LOG DE ACESSOS
							mysql_query("insert into log_cobranca (id_user, acao) VALUES (999999,'USUARIO: " . $id_user . " - MARCANDO PAGAMENTO " . $linha['idHistorico'] . " COMO PAGO')");
							
							$resultadoup = mysql_query($sqlup)
							or die (mysql_error());
		
							//Mensagem a exibir no relatório final.
							// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ESTE PAGAMENTO
							$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, data, data_pagamento, tipo_cobranca, resultado_acao, envio_email, valor_pago) VALUES ('" . $id_user . "', '" . $data_hoje . "', '" . $data_hoje . "', 'boleto', '1.2', 'enviado', " . $valor_pago . ".00)";
							$resultadoup = mysql_query($sqlRelCobranca)
							or die (mysql_error());
							
							$enviarEmail = true;
							
						}

					}else{

						//Mensagem a exibir no relatório final.
						// INSERINDO LINHA NO RELATORIO DE COBRANCA INFORMANDO ERRO NA DATA DO BOLETO DESTE PAGAMENTO

/*
						$sqlRelCobranca = "INSERT INTO relatorio_cobranca (id, data, tipo_cobranca, resultado_acao, envio_email, valor_pago) VALUES ('" . $id_user . "', '" . $data_hoje . "', 'boleto', '9.3', '', " . $valor_pago . ".00)";
						$resultadoup = mysql_query($sqlRelCobranca)
						or die (mysql_error());
*/

					}
					
										
				}
				
				$pagamentos_processados++;
			}
		}
		// FIM DO FOR - PERCORRENDO O ARQUIVO - TIRANDO AS 2 PRIMEIRAS (CABEÇALHO) E AS 2 ULTIMAS (RODAPE) LINHAS PARA TRAZER DADOS REFERENTES AO PAGAMENTO

		
		// CHECAGEM FINAL 
		if($total_pagamentos === $pagamentos_processados){
			// ATUALIZANDO OS DADOS DO ARQUIVO NO BANCO
//			mysql_query("UPDATE arquivos_retorno_banco SET data_processamento = '" . date('Y-m-d H:i:s') . "', status = 'processado' WHERE nome = '" . str_replace('arquivos_retorno/','',$vNome_arquivo) . "'");
		}else{
//			mysql_query("UPDATE arquivos_retorno_banco SET data_processamento = '" . date('Y-m-d H:i:s') . "', status = 'processado com erro' WHERE nome = '" . str_replace('arquivos_retorno/','',$vNome_arquivo) . "'");
		}
		
		
	}




	
		// PROCESSA 1 ARQUIVO
		if(isset($_REQUEST['arq']) && $_REQUEST['arq'] != ''){
		
			$nome_arquivo = ('arquivos_retorno/'.$_REQUEST['arq']);
			$nome_arquivo_proc = ('arquivos_retorno/proc_'. $_REQUEST['arq']);
	
			// CHECANDO SE O ARQUIVO EXISTE PARA PROCESSAR
			ProcessaArquivoRetorno('',$nome_arquivo, true, $mensalidade);

			// ############################## LOG ###############################
			if($show_log == true){
				echo "processado o arquivo " . $nome_arquivo . "<BR><BR>";
			}
			// ############################## LOG ###############################


		} else {
		// PROCESSA TODOS OS ARQUIVOS
			$rsArquivos = mysql_query("SELECT * FROM arquivos_retorno_banco WHERE status = 'carregado' ORDER BY data_carga");
			if (mysql_num_rows($rsArquivos) > 0) {
				while($arquivos=mysql_fetch_object($rsArquivos)){
	
					$nome_arquivo = ('arquivos_retorno/'.$arquivos->nome);
					$nome_arquivo_proc = ('arquivos_retorno/proc_'. $arquivos->nome);
					
					ProcessaArquivoRetorno('',$nome_arquivo, true, $mensalidade);

					// ############################## LOG ###############################
					if($show_log == true){
						echo "processado o arquivo " . $nome_arquivo . "<BR><BR>";
					}
					// ############################## LOG ###############################
					
				}

			}
			
		}
?>