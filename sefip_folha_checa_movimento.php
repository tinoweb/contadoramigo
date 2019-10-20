<?php

	session_start(); 
	include 'conect.php' ;

	// Variáveis que recebe.
	$statusFuncionario = false;
	$statusAutonomos = false;
	$statusSocio = false;
	$statusExisteFuncionario = true;

	// Pega mes e ano.
	$mes = $_REQUEST['mes'];
	$ano = $_REQUEST['ano'];

	// Verifica se a validação e para competência 13
	if( $mes == 13 ) {
		
		$funcionario = '';
		
		// pega todos funcionário cadastrado e não demitidos.
		$sql1 = " SELECT * FROM dados_do_funcionario
				WHERE id = '".$_SESSION['id_empresaSecao']."' 
				AND nome IS NOT NULL 
				AND ctps IS NOT NULL
				AND (data_admissao <= '".$ano."-12-31' OR data_admissao <= '".$ano."-12-31')
				AND (data_demissao IS NULL OR (data_demissao >= '".$ano."-11-01' OR data_demissao > '".$ano."-12-01'))";

		$resultado1 = mysql_query($sql1);

		// Verifica se existe funcionário cadastrado ou se todos funcionario não foram demitido.
		if( mysql_num_rows($resultado1) > 0 ) {

			// Pega o lançamento de pagamento para o funcionário.
			$sql2 = " SELECT idFuncionario, nome FROM dados_do_funcionario 
					WHERE id = '".$_SESSION['id_empresaSecao']."' 
					AND idFuncionario NOT IN ( 
						SELECT funcionarioId FROM dados_pagamentos_funcionario 
						WHERE empresaId = '".$_SESSION['id_empresaSecao']."'
						AND tipoPagamento = 'decimoTerceiro'
						AND YEAR(data_referencia) = '".$ano."'
						AND (MONTH(data_referencia) = 11 OR MONTH(data_referencia) = 12))
					AND (data_admissao <= '".$ano."-12-31' OR data_admissao <= '".$ano."-12-31')
					AND (data_demissao IS NULL OR (data_demissao >= '".$ano."-11-01' OR data_demissao > '".$ano."-12-01'))";

			$resultado2 = mysql_query($sql2);

			// Verifica se existe lançamento de pagamento para o funcionário.
			if( mysql_num_rows($resultado2) > 0 ) {

				while($array = mysql_fetch_array($resultado2)) {
					
					if(empty($funcionario)) {
						$funcionario = $array['nome'];
					} else {
						$funcionario .= ','.$array['nome'];
					}
					
				}
			} else {
				// Define o status do funcionario como verdadeiro, pois existe pagamento no mes informado.
				$statusFuncionario = true;				
			}
						
			if( $statusFuncionario ) {
				echo json_encode(array('status'=>'ok'));
				exit;
			} else {
				// Bloqueia, pois não foi encontrado nenhum lançamento de pagamento para funcionario. 
				echo json_encode(array('status'=>'erroSemPagatoFunc','nomesFuncionario'=>$funcionario));	
				exit;
			}		

		} else {
			// Por não existir cadastro sera enviado para tutorial sem movimentação.
			echo json_encode(array('status'=>'erroSemFuncionario13'));
			exit;
		}
	} else {
		
		$funcionario = '';

		// pega todos funcionário cadastrado e não demitidos.
		$sql1 = " SELECT * FROM dados_do_funcionario
				WHERE id = '".$_SESSION['id_empresaSecao']."' 
				AND nome IS NOT NULL 
				AND ctps IS NOT NULL
				AND data_admissao <= '".$ano."-".$mes."-31'
				AND (data_demissao IS NULL OR data_demissao > '".$ano."-".$mes."-01')";

		$resultado1 = mysql_query($sql1);
		
		// Verifica se existe funcionário cadastrado ou se todos funcionario não foram demitido.
		if( mysql_num_rows($resultado1) > 0 ) {

			// Pega 
			$sql2 = " SELECT idFuncionario, nome FROM dados_do_funcionario 
					WHERE id = '".$_SESSION['id_empresaSecao']."' 
					AND idFuncionario NOT IN ( 
						SELECT funcionarioId FROM dados_pagamentos_funcionario 
						WHERE empresaId = '".$_SESSION['id_empresaSecao']."'
						AND tipoPagamento = 'salario'
						AND YEAR(data_referencia) = '".$ano."'
						AND MONTH(data_referencia) = '".$mes."' ) 
					AND (data_admissao != 0000-00-00 AND data_admissao <= '".$ano."-".$mes."-31')
					AND (data_demissao IS NULL OR data_demissao > '".$ano."-".$mes."-01')";
			
			$resultado2 = mysql_query($sql2);
			
			// Verifica se existe lançamento de pagamento para o funcionário.
			if( mysql_num_rows($resultado2) > 0 ) {

				while($array = mysql_fetch_array($resultado2)) {
					
					if(empty($funcionario)) {
						$funcionario = $array['nome'];
					} else {
						$funcionario .= ','.$array['nome'];
					}
					
				}
			} else {
				// Define o status do funcionario como verdadeiro, pois existe pagamento no mes informado.
				$statusFuncionario = true;				
			}
			
			// CHECANDO SE HÁ MOVIMENTO PARA O MÊS SELECIONADO
			$sql3 = " SELECT * FROM dados_pagamentos 
						WHERE id_login='" . $_REQUEST['id'] ."'
						AND id_autonomo <> 0
						AND YEAR(data_pagto) = '".$ano."'
						AND MONTH(data_pagto) = '".$mes."' ";

			$resultado3 = mysql_query($sql3) or die (mysql_error());

			if(mysql_num_rows($resultado3) > 0){

				// Define o status do autônomos como verdadeiro, pois existe pagamento no mes informado.
				$statusAutonomos = true;
			}

			// CHECANDO SE HÁ PAGAMENTO DE PRO-LABORE NO MES
			$sql4 = "	SELECT * FROM dados_pagamentos
						WHERE id_login='" . $_REQUEST['id'] ."'
						AND id_socio <> 0
						AND YEAR(data_pagto) = '".$ano."'
						AND MONTH(data_pagto) = '".$mes."' ";

			$resultado4 = mysql_query($sql4) or die (mysql_error());

			if(mysql_num_rows($resultado4) > 0){

				// Define o status do sócio como verdadeiro, pois existe pagamento no mes informado.
				$statusSocio = true;
			}			
			
			/**
			 * Define a ação da página 
			 */

			// Libera, pois a lançamento de pagamento para os socios, funcionários e autônomos.
			if( $statusSocio && $statusAutonomos && $statusFuncionario ) {
				echo json_encode(array('status'=>'ok'));
				exit;
			} // Exibe a mensagem, pois existe o lançamento de pagamento para o autônomos e funcionários.
			elseif( !$statusSocio && $statusAutonomos && $statusFuncionario ) {
				$msg = "'Não foi detectado nenhum recolhimento de pró-labore para este mês. Deseja continuar mesmo assim?'";		
				echo json_encode(array('status'=>'erroProLabore','msg'=>$msg,'erro'=>'01'));
				exit;
			} // Liberado, pois a lançamento de pagamento para os socios e funcionários.
			elseif( $statusSocio && !$statusAutonomos && $statusFuncionario ) {
				echo json_encode(array('status'=>'ok'));
				exit;
			} // Bloqueia, pois não existe lançamento de pagamento para o funcionario.
			elseif( $statusSocio && $statusAutonomos && !$statusFuncionario ) {	
				echo json_encode(array('status'=>'erro','nomesFuncionario'=>$funcionario,'erro'=>'02' ));
				exit;
			} // Exibe a mensagem, pois existe o lançamento de pagamento para o funcionário.
			elseif( !$statusSocio && !$statusAutonomos && $statusFuncionario ) {
				$msg = "'Não foi detectado nenhum recolhimento de pró-labore para este mês. Deseja continuar mesmo assim?'";	
				echo json_encode(array('status'=>'erroProLabore','msg'=>$msg,'erro'=>'03'));
				exit;	
			} // Bloqueia, pois não existe lançamento de pagamento para o funcionario.
			elseif( $statusSocio && !$statusAutonomos && !$statusFuncionario ) {	
				echo json_encode(array('status'=>'erro','nomesFuncionario'=>$funcionario,'erro'=>'04'));
				exit;
			} // Bloqueia, pois não existe lançamento de pagamento para o funcionario e para o sócio
			elseif( !$statusSocio && $statusAutonomos && !$statusFuncionario ) {	
				echo json_encode(array('status'=>'erro','nomesFuncionario'=>$funcionario,'erro'=>'05'));
				exit;	
			} // Bloqueia, pois não foi encontrado nenhum lançamento de pagamento.  
			else {
				echo json_encode(array('status'=>'erro', 'nomesFuncionario'=>$funcionario));
				exit;
			}			

		} else {
			
			// CHECANDO SE HÁ MOVIMENTO PARA O MÊS SELECIONADO
			$sql3 = " SELECT * FROM dados_pagamentos 
						WHERE id_login='" . $_REQUEST['id'] ."'
						AND id_autonomo <> 0
						AND YEAR(data_pagto) = '".$ano."'
						AND MONTH(data_pagto) = '".$mes."' ";

			$resultado3 = mysql_query($sql3) or die (mysql_error());

			if(mysql_num_rows($resultado3) > 0){

				// Define o status do autônomos como verdadeiro, pois existe pagamento no mes informado.
				$statusAutonomos = true;
			}

			// CHECANDO SE HÁ PAGAMENTO DE PRO-LABORE NO MES
			$sql4 = "	SELECT * FROM dados_pagamentos
						WHERE id_login='" . $_REQUEST['id'] ."'
						AND id_socio <> 0
						AND YEAR(data_pagto) = '".$ano."'
						AND MONTH(data_pagto) = '".$mes."' ";

			$resultado4 = mysql_query($sql4) or die (mysql_error());

			if(mysql_num_rows($resultado4) > 0){

				// Define o status do sócio como verdadeiro, pois existe pagamento no mes informado.
				$statusSocio = true;
			}
			
			
			/**
			 * Define a ação da página 
			 */

			// Libera, pois a lançamento de pagamento para os socios, funcionários e autônomos.
			if( $statusSocio && $statusAutonomos ) {
				echo json_encode(array('status'=>'ok'));
				exit;
			} // Exibe a mensagem, pois existe o lançamento de pagamento para o autônomos e funcionários.
			elseif( !$statusSocio && $statusAutonomos ) {
				$msg = "'Não foi detectado nenhum recolhimento de pró-labore para este mês. Deseja continuar mesmo assim?'";		
				echo json_encode(array('status'=>'erroProLabore','msg'=>$msg,'erro'=>'06'));
				exit;
			} // Liberado, pois a lançamento de pagamento para os socios e funcionários.
			elseif( $statusSocio && !$statusAutonomos ) {
				echo json_encode(array('status'=>'ok'));
				exit;	
			} // Bloqueia, pois não foi encontrado nenhum lançamento de pagamento.  
			else {
				$msg = "'Não foi detectado nenhum recolhimento de pró-labore para este mês. Deseja gerar uma sefip sem movimento?'";		
				echo json_encode(array('status'=>'erroSemProLaboreSocio','msg'=>$msg,'erro'=>'06'));
				exit;
			}				
		}
	}
 ?>