<?php

	include("../conect.php");
	function formataData($data){
		$aux = explode('-', $data);
		return $aux[2].'/'.$aux[1].'/'.$aux[0];
	}
	if( $_GET['identificacao'] == 4843543 ){

		//Realiza a consulta para verificar se o boleto ja foi adicionado a remessa
		$consulta = mysql_query("SELECT * FROM boleto WHERE numdoc_get  = '".$_GET['numdoc']."' ");
		$objeto=mysql_fetch_array($consulta);

		if( isset( $objeto['numdoc_get'] ) && $objeto['numdoc_get'] != '' && floatval(str_replace(',', '.', $objeto['valor_get'])) == floatval(str_replace(',', '.', $_GET['valor'])) ):

			$user = $objeto['user'];
			$valor_get = floatval(str_replace(',', '.', $objeto['valor_get']));
			$numdoc_get = $objeto['numdoc_get'];
			$sacado_get = $objeto['sacado_get'];
			$enderecosac_get = $objeto['enderecosac_get'];
			$bairrosac_get = $objeto['bairrosac_get'];
			$cidadesac_get = $objeto['cidadesac_get'];
			$cepsac_get = $objeto['cepsac_get'];
			$ufsac_get = $objeto['ufsac_get'];
			$datadoc_get = $objeto['datadoc_get'];
			$gerar_multa = $objeto['gerar_multa'];
			$vencto_get = formataData($objeto['vencto_get']);


		else:

			if( isset( $objeto['id'] ) ){
				mysql_query("DELETE FROM boleto WHERE id = '".$objeto['id']."' ");
			}

			$user = $_GET['user'];
			$valor_get = floatval(str_replace(',', '.', $_GET['valor']));
			$numdoc_get = $_GET['numdoc'];
			$sacado_get = $_GET['sacado'];
			$enderecosac_get = $_GET['enderecosac'];
			$bairrosac_get = $_GET['bairrosac'];
			$cidadesac_get = $_GET['cidadesac'];
			$cepsac_get = $_GET['cepsac'];
			$ufsac_get = $_GET['ufsac'];
			$datadoc_get = $_GET['datadoc'];
			$vencto_get = $_GET['vencto'];
			$gerar_multa = $_GET['gerar_multa'];
		
		endif;




		$logoloja_get = $_GET['logoloja'];

		// ID DO CLIENTE
		$id_user = 9;
		//BUSCAR PROXIMO VENCIMENTO DO CLIENTE
		$dataPagamento = $datadoc_get;
		$data_vencimento = $vencto_get;
		$data_pagamento = $dataPagamento;
		$mes_boleto = date('m',strtotime($dataPagamento));
		//PEGAR MENSALIDADE DO CLIENTE
		$mensalidade = $valor_get;		
		//PEGAR O NOME DO CLIENTE
		$nome_do_cliente = $sacado_get;
		//PEGAR O ENDEREÇO DO CLIENTE
		$endereco_cliente = $enderecosac_get.' - '.$bairrosac_get;
		$endereco2_cliente = "CEP: ".$cepsac_get." - ".$cidadesac_get." - ".$ufsac_get."";
		//GERAR O NOSSO NUMERO, COMPOSIÇÃO: 2263282(codigo do cedente)000000(Digitos do id do cliente alinhados a esquerda com zeros a esquerda)1016(mes 10 ano 2016, data do mes a colocar como pago no historico)
		// $nosso_numero = urlencode(str_pad($id_user . $mes_boleto . date('y',strtotime($data_pagamento)), 10, "0", STR_PAD_LEFT));
		$nosso_numero = $numdoc_get;

	}
	else{
		return;
	}

	$dias_de_prazo_para_pagamento = 5;
	$taxa_boleto = 0;
	$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
	$data_venc = $data_vencimento;
	$valor_cobrado = $mensalidade; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
	$valor_cobrado = str_replace(",", ".",$valor_cobrado);
	$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
	$dadosboleto["nosso_numero"] = $nosso_numero;
	$dadosboleto["numero_documento"] = $numdoc_get;
	
	$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
	$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
	// DADOS DO SEU CLIENTE
	$dadosboleto["sacado"] = $nome_do_cliente;
	$dadosboleto["endereco1"] = $endereco_cliente;
	$dadosboleto["endereco2"] = $endereco2_cliente;
	// INFORMACOES PARA O CLIENTE
	$dadosboleto["demonstrativo1"] = "";
	$dadosboleto["demonstrativo2"] = "";
	$dadosboleto["demonstrativo3"] = "";
	// INSTRUÇÕES PARA O CAIXA
	if( $gerar_multa == "true" ){
		$dadosboleto["instrucoes1"] = "Após o vencimento cobrar multa de 2%, mais juros de mora de R$ 0,22/dia.";
	}
	else{

		$consulta_observacoes = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$user."' ");
		$objeto_observacoes = mysql_fetch_array($consulta_observacoes);
		$plano_user = $objeto_observacoes['plano'];

		$aux_valor_observacoes = floatval($valor_get );
		while ( floatval($aux_valor_observacoes) - 59 > 0 ) {
			$aux_valor_observacoes = floatval($aux_valor_observacoes) - 59;
		}
		$multa_observacoes = 0;
		$valor_original = 0;
		if( $plano_user == 'mensalidade' ){
			$multa_observacoes = 1.18;
			$valor_original = 59;
		}
		if( $plano_user == 'trimestral' ){
			$multa_observacoes = 3.54;
			$valor_original = 177;
		}
		if( $plano_user == 'semestral' ){
			$multa_observacoes = 6.9;
			$valor_original = 354;
		}
		if( $plano_user == 'anual' ){
			$multa_observacoes = 11.7;
			$valor_original = 588;
		}

		$mora = floatval(floatval($aux_valor_observacoes) - floatval($multa_observacoes));




		while ( floatval($aux_valor_observacoes) - 59 > 0 ) {
			$aux_valor_observacoes = floatval($aux_valor_observacoes) - 59;
		}
		$frase_descricao = "(Valor original + multa";
		$frase_mora = '';
		$frase_multa = "Multa por atraso... : ".number_format($multa_observacoes,2,',','.');
		if( $mora > 0 ){
			$frase_mora = "Encargos.............. : ".number_format($mora,2,',','.')."<br>";
			$frase_descricao .= " + encargos";
		}
		$frase_valor_original = "Valor original........ : ".number_format($valor_original,2,',','.');

		$frase_descricao .= ")";
		$string = "

			Boleto reemitido com data de vencimento e valor atualizados<br>
			".$frase_descricao."<br>
			".$frase_valor_original."<br>
			".$frase_multa."<br>
			".$frase_mora."
		";

		// $aux_valor_observacoes = floatval($valor_get % 59);
		// echo $aux_valor_observacoes;
		$dadosboleto["instrucoes1"] = $string;

		$dados_user_aux = mysql_query("SELECT * FROM login WHERE id = '".$user."' ");
			
		$usuario=mysql_fetch_array($dados_user_aux);

		if( $usuario['status'] == 'demoInativo' || $usuario['status'] == 'demo' || $usuario['status'] == 'cancelado' ){
			$dadosboleto["instrucoes1"] = "";
		}


	}
	
	$dados_user_aux = mysql_query("SELECT * FROM login WHERE id = '".$user."' ");
	$usuario=mysql_fetch_array($dados_user_aux);
	if( $usuario['status'] == 'demoInativo' || $usuario['status'] == 'demo' || $usuario['status'] == 'cancelado' ){
		$dadosboleto["instrucoes1"] = "";
	}

	$dadosboleto["instrucoes2"] = "";
	$dadosboleto["instrucoes3"] = "";
	$dadosboleto["instrucoes4"] = "";
	// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
	$dadosboleto["quantidade"] = "";
	$dadosboleto["valor_unitario"] = "";
	$dadosboleto["aceite"] = "N";		
	$dadosboleto["especie"] = "R$";
	$dadosboleto["especie_doc"] = "";
	// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
	// DADOS DA SUA CONTA - BANCO DO BRASIL
	$dadosboleto["agencia"] = "2962"; // Num da agencia, sem digito
	$dadosboleto["conta"] = "17877"; 	// Num da conta, sem digito
	// DADOS PERSONALIZADOS - BANCO DO BRASIL
	$dadosboleto["convenio"] = "2850943";  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
	$dadosboleto["contrato"] = ""; // Num do seu contrato
	$dadosboleto["carteira"] = "17";
	$dadosboleto["variacao_carteira"] = "";  // Variação da Carteira, com traço (opcional)
	// TIPO DO BOLETO
	$dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
	$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos
	/*
	#################################################
	DESENVOLVIDO PARA CARTEIRA 18
	- Carteira 18 com Convenio de 8 digitos
	  Nosso número: pode ser até 9 dígitos
	- Carteira 18 com Convenio de 7 digitos
	  Nosso número: pode ser até 10 dígitos
	- Carteira 18 com Convenio de 6 digitos
	  Nosso número:
	  de 1 a 99999 para opção de até 5 dígitos
	  de 1 a 99999999999999999 para opção de até 17 dígitos
	#################################################
	*/
	// SEUS DADOS
	$dadosboleto["identificacao"] = "Contador Amigo";
	$dadosboleto["cpf_cnpj"] = "96533310000140";
	$dadosboleto["endereco"] = "Av. das Nacoes Unidas, 8501 17 Andar";
	$dadosboleto["cidade_uf"] = "Sao Paulo / SP";
	$dadosboleto["cedente"] = "Contador Amigo Ltda. - ME";
	// NÃO ALTERAR!

	if( $valor_get == 0 ){
		echo 'Ocorreu um erro ao gerar o Boleto. Verifique se preencheu corretamente todos os dados de cobrança e tente novamente. Se o erro persistir, entre em contato com o <a href="suporte.php" title="Help Desk">Help Desk</a>';
		return;
	}

	include("include/funcoes_bb.php"); 

	// include("../conect.php");

	//Realiza a consulta para verificar se o boleto ja foi adicionado a remessa
	$consulta = mysql_query("SELECT * FROM boleto WHERE numdoc_get  = '".$numdoc_get."' ");
	$objeto=mysql_fetch_array($consulta);
	//Caso o boleto ja esteja cadastrado, informa que é segunda via e finaliza
	if( isset( $objeto['numdoc_get'] ) && $objeto['numdoc_get'] != '' ):
		
		mysql_query("INSERT INTO `log_acessos`(`id`, `id_user`, `acao`) VALUES ('','".$user."','MINHA CONTA: BOLETO VENCIDO A EMITIR: ".$numdoc_get." ')");

		if( strtotime($objeto['vencto_get']) < strtotime(date("Y-m-d")) ){
			include 'header_restrita.php';
			echo '<title>Recalcular Boleto Vencido</title><style>body{margin:0}</style>';
			echo '<div style="background-color: #f5f6f7;padding:20px">';
			echo '<img src="http://ambientedeteste2.hospedagemdesites.ws/images/logo.png" alt="Contador Amigo" width="400" height="68" border="0" style="margin-bottom:5px"><br>';
			echo '</div><div style="font-family: Arial, Helvetica, sans-serif;	font-size: 15px;color: #666666;background-color: #f5f6f7;padding:20px;">';
			echo 'Boleto Vencido!<br><br>';
			echo 'Para gerar novo boleto, informe abaixo,  em Linha Digitável do Título, o número: ';
			echo ' '.str_replace('.', ' ', $objeto['linha_digitavel']).'<br>Os demais campos podem ficar em  branco.';
			echo '</div>';
			echo '<iframe src="https://www63.bb.com.br/portalbb/boleto/boletos/hc21e,802,3322,10343.bbx" style="width: 100%;height: 250%;"></iframe>';
		}
		else{
			echo '<h1>2º via<h1>';
			include("include/layout_bb.php");
		}
		return;
	else:

		$data = explode('/', $vencto_get);
		$vencto_get = $data[2].$data[1].$data[0];
		//Caso o boleto esteja sendo gerado pela primeira vez, insere na remessa
		$campos = "(`id`, `user`, `valor_get`,`gerar_multa`, `numdoc_get`, `sacado_get`, `enderecosac_get`, `bairrosac_get`, `cidadesac_get`, `cepsac_get`, `ufsac_get`, `datadoc_get`, `vencto_get`, `linha_digitavel`, `nosso_numero`, `remessa_gerada`)";
		$values = "(
						'',
						'".$user."',
						'".$valor_get."',
						'".$gerar_multa."',
						'".$numdoc_get."',
						'".$sacado_get."',
						'".$enderecosac_get."',
						'".$bairrosac_get."',
						'".$cidadesac_get."',
						'".$cepsac_get."',
						'".$ufsac_get."',
						'".$datadoc_get."',
						'".$vencto_get."',
						'".$dadosboleto["linha_digitavel"]."',
						'".$dadosboleto["nosso_numero"]."',
						'0'
					)";
		// if( isset($_GET['promo']) && $_GET['promo'] == true ){
		// 	include("include/layout_bb.php");
		// }
		// else{
		mysql_query("INSERT INTO `log_acessos`(`id`, `id_user`, `acao`) VALUES ('','".$user."','MINHA CONTA: BOLETO A EMITIR: ".$numdoc_get." ')");
		mysql_query("INSERT INTO `boleto` ".$campos." VALUES ".$values."");
		include("include/layout_bb.php");
		// }
	endif;

?>

