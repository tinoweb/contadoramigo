<?php 
	
	session_start();

	include 'conect.php';

	if( isset( $_POST['sugerirValorMercado'] ) ):
		include 'datas.class.php';
		$datas = new Datas();
		$valor = $_POST['valor'];
		$data = $datas->converterData($_POST['data']);
		$item = $_POST['item'];
		$quantidade = $_POST['quantidade'];
		$ano = $_POST['ano'];
		$id = $_POST['id'];
		// var_dump($_POST);
		$tabela_depreciacao = array();

		$vida_util = array();
		$vida_util['Veículos'] = floatval(5);
		$vida_util['Imóveis (prédios)'] = floatval(25);
		$vida_util['Móveis e utensílios'] = floatval(10);
		$vida_util['Computadores e periféricos'] = floatval(5);
		$vida_util['Máquinas e equipamentos'] = floatval(10);

		$tabela_depreciacao['Veículos'] = floatval(0.2);
		$tabela_depreciacao['Imóveis (prédios)'] = floatval(0.04);
		$tabela_depreciacao['Móveis e utensílios'] = floatval(0.1);
		$tabela_depreciacao['Computadores e periféricos'] = floatval(0.2);
		$tabela_depreciacao['Máquinas e equipamentos'] = floatval(0.1);
		$valor_total_item = 0;
		if( floatval($ano) - floatval($data) <= $vida_util[$item] ){
			$datas = new Datas();
			while( $datas->diferencaData($data, $ano.'-12-31' ) <= 0){
				$data = $datas->somarMes($data,1);
				$aux = $aux + 1;
			}
			$meses_depreciacao = $aux - 1;
			$depreciacao = floatval($valor) * floatval($tabela_depreciacao[$item]);
			$depreciacao = $depreciacao / 12;
			$total_depreciacao = $depreciacao * $meses_depreciacao;
			if( $total_depreciacao == 0 )
				$total_depreciacao = 0;
			$valor_total_item = $valor - $total_depreciacao;
		}

		mysql_query("UPDATE imobilizados SET valor_mercado = '".($valor_total_item/$quantidade)."' WHERE id_user = ". $_SESSION['id_empresaSecao']." AND id = '".$id."' ");
		
		echo number_format(($valor_total_item/$quantidade),2,',','.');
	
	endif;

	if( isset( $_POST['editar_item_emprestimo'] ) ):

		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];
		$acao = array();

		if( $campo == 'data' ){
			include 'datas.class.php';
			$datas = new Datas();
			$valor = $datas->converterData($valor);
		}

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id = '".$id."' ");
		
		// echo json_encode($acao);

	endif;

	if( isset( $_POST['editar_item_imobilizado'] ) ):

		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];
		$ano = $_POST['ano'];
		$acao = array();

		if( $campo == 'data' ){
			include 'datas.class.php';
			$datas = new Datas();
			$valor = $datas->converterData($valor);
		}
		

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id = '".$id."' ");
		
		echo json_encode($acao);

	endif;


	if( isset( $_POST['editar_item_intengivel'] ) ):

		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];
		$ano = $_POST['ano'];
		$acao = array();

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id = '".$id."' ");
		
		echo json_encode($acao);

	endif;

	if( isset( $_POST['removerEmprestimo'] ) ):
		
		mysql_query("DELETE FROM `user_". $_SESSION['id_empresaSecao']."_livro_caixa` WHERE id =  '".$_POST['id']."'");
		mysql_query("DELETE FROM `user_". $_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` WHERE id_item = '".$_POST['id']."' ");
	
	endif;

	if( isset( $_POST['inserirEmprestimo'] ) ):
		
		mysql_query("INSERT INTO `user_". $_SESSION['id_empresaSecao']."_livro_caixa` ( `categoria`) VALUES ('Empréstimos')");

		mysql_query("INSERT INTO `user_". $_SESSION['id_empresaSecao']."_livro_caixa_emprestimos`(`id_item`) VALUES ('".mysql_insert_id()."')");
	
	endif;

	if( isset( $_POST['removerIntangivel'] ) ):
		
		mysql_query("DELETE FROM `intangiveis` WHERE id_user = '". $_SESSION['id_empresaSecao']."' AND id =  '".$_POST['id']."'");
	
	endif;

	if( isset( $_POST['inserirIntangivel'] ) ):
		
		mysql_query("INSERT INTO `intangiveis`(`id_user`) VALUES ('". $_SESSION['id_empresaSecao']."')");
	
	endif;
	
	if( isset( $_POST['removerImobilizado'] ) ):
		
		mysql_query("DELETE FROM `imobilizados` WHERE id_user = '". $_SESSION['id_empresaSecao']."' AND id =  '".$_POST['id']."'");
	
	endif;

	if( isset( $_POST['inserirImobilizado'] ) ):
		
		mysql_query("INSERT INTO `imobilizados`(`id_user`) VALUES ('". $_SESSION['id_empresaSecao']."')");
	
	endif;

	if( isset( $_POST['verificacaoBoletos'] ) ):
		
		$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE id = '".$_POST['id_user']."' AND idHistorico != '".$_POST['id_historico']."' AND (status_pagamento = 'não pago' OR status_pagamento = 'vencido') AND idHistorico < '".$_POST['id_historico']."' ");
		if( mysql_num_rows($consulta) > 0 )
			echo 'erro';
		else
			echo 'ok';
	
	endif;

	if( isset( $_POST['editar_item_balanco'] ) ):
		
		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];
		$ano = $_POST['ano'];
		$acao = array();
		if( $id == '' ){
				mysql_query("INSERT INTO `balanco_patrimonial` (`id_user`, `ano`, `a_c_caixa_equivalente_caixa`, `a_c_contas_receber`, `a_c_estoques`, `a_c_outros_creditos`, `a_c_despesas_exercicio_seguinte`, `a_c_total`, `a_n_c_contas_receber`, `a_n_c_investimentos`, `a_n_c_imobilizado`, `a_n_c_intangivel`, `a_n_c_depreciacao`, `a_n_c_total`, `p_c_fornecedores`, `p_c_emprestimos_bancarios`, `p_c_obrigacoes_sociais_impostos`, `p_c_contas_pagar`, `p_c_lucros_distribuir`, `p_c_provisoes`, `p_c_total`, `p_n_c_contas_pagar`, `p_n_c_financiamentos_bancarios`, `p_n_c_total`, `p_l_capital_social`, `p_l_reservas_capital`, `p_l_ajustes_avaliacao_patrimonial`, `p_l_reservas_lucro`, `p_l_lucros_acumulados`, `p_l_prejuizos_acumulados`, `p_l_total`) VALUES ('".$_SESSION['id_empresaSecao']."', '".$ano."', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');");
			$id = mysql_insert_id();
			$acao = array("0"=>"gerarId","1"=>$id);
		}

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id = '".$id."' ");
		
		echo json_encode($acao);

	endif;
	
	if( isset( $_POST['varificarDuplicidadePagamentos'] ) ):
		//Define o campo que sera verificado na tabela de pagamentos para verificar se o pagamento ja existe ou nao
		function definirCampo($categoria,$id_login,$id){
			if( $categoria == 'Estagiários' )
				return 'estagiario';
			if( $categoria == 'Pgto. a autônomos e fornecedores' ){
				$consulta = mysql_query("SELECT * FROM dados_autonomos WHERE id_login = '".$id_login."' AND id = '".$id."' ");
				if( mysql_num_rows($consulta) > 0 )
					return 'autonomo';
				$consulta = mysql_query("SELECT * FROM dados_pj WHERE id_login = '".$id_login."' AND id = '".$id."' ");
				if( mysql_num_rows($consulta) > 0 )
					return 'pj';
			}
			if( $categoria == 'Pagto. de salários' )
				return 'funcionario';
			if( $categoria == 'Pró-Labore' )
				return 'socio';
		}
		//Define a frase do beneficiário de acordo com o tipo 
		function definirBeneficiario($tipo){
			
				if( $tipo == 'estagiario' )
					return "estagiário";
				if( $tipo == 'autonomo' )
					return "autônomo";
				if( $tipo == 'pj' )
					return "fornecedor";
				if( $tipo == 'funcionario' )
					return "funcionário";
				if( $tipo == 'socio' )
					return "sócio";
		}
		//Funcao que verifica se o pagamento em questao ja esta cadastrado, se nao estiver, retorna erro e avisa o usuario, se estiver permite o cadastro no livro caixa
		function verificarExistenciaPagamento($id_user,$valor,$data,$categoria,$beneficiario,$tipo){
			$consulta = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$id_user."' AND id_".$tipo." = '".$beneficiario."' AND valor_liquido = '".$valor."' AND ( DATEDIFF(data_pagto,'".$data."') >= 0 AND DATEDIFF(data_pagto,'".$data."') <= 0 ) AND idLivroCaixa = 0 ");
			if( mysql_num_rows($consulta) == 0 )
				return true;
		}
		include 'datas.class.php';
		$datas = new Datas();

		$id_user = $_SESSION['id_empresaSecao'];
		$valor = str_replace(',', '.', str_replace('.', '', $_POST['valor']));
		$data = $datas->converterData($_POST['data']);
		$categoria = $_POST['categoria'];
		$beneficiario = $_POST['beneficiario'];

		$tipo = definirCampo($categoria,$id_user,$beneficiario);
		$frase = '';
		if( verificarExistenciaPagamento($id_user,$valor,$data,$categoria,$beneficiario,$tipo) ){
			 $frase = 'Todo pagamento a '.definirBeneficiario($tipo).' precisa ser registrado primeiramente em pagamentos/'.definirBeneficiario($tipo).', para que sejam calculadas as devidas retenções de impostos. Aparentemente este lançamento ainda não foi registrado lá. Faça isso o quanto antes para que o livro caixa coincida com sua folha de pagamento.';
			 $resultado = 'erro';
		}
		else{
			$resultado = 'ok';
		}

		echo json_encode(array("0"=>$resultado,"1"=>$frase));

		// echo $id_user.'<br>';
		// echo $valor.'<br>';
		// echo $data.'<br>';
		// echo $categoria.'<br>';
		// echo $beneficiario.'<br>';
	
	endif;

	if( isset( $_POST['selectPagamentos'] ) ):
		
		$id = $_SESSION['id_empresaSecao'];
		$string = '<option value="">selecione</option>';
		$frase_erro = '';
		if( $_POST['tabela'] == 'estagiarios' ){
			$consulta = mysql_query("SELECT * FROM estagiarios WHERE id_login = '".$id."' ");
			if( mysql_num_rows($consulta) == 0 )
				$frase_erro = 'Clique <a href="meus_dados_estagiarios.php" title="Cadastrar Estagiário" target="_blank">aqui</a> para cadastrar um estagiário';
			else
				while( $objeto_consulta = mysql_fetch_array($consulta) )
					$string .= '<option value="'.$objeto_consulta['id'].'">'.$objeto_consulta['nome'].'</option>';
		}
		if( $_POST['tabela'] == 'autonomos_fornecedores' ){
			$consulta = mysql_query("SELECT id,id_login,nome,cpf FROM dados_autonomos WHERE dados_autonomos.id_login = '".$id."' UNION SELECT id,id_login,nome,cnpj FROM dados_pj WHERE dados_pj.id_login = '".$id."' order by nome");
			if( mysql_num_rows($consulta) == 0 )
				$frase_erro = 'Clique <a href="meus_dados_autonomos.php" title="Cadastrar Autônomo" target="_blank">aqui</a> para cadastrar um autonomo ou <a href="meus_dados_pj.php" title="Cadastrar Fornecedor" target="_blank">aqui</a> para cadastrar um fornecedor';
			else
			while( $objeto_consulta = mysql_fetch_array($consulta) )
				$string .= '<option value="'.$objeto_consulta['id'].'">'.$objeto_consulta['nome'].'</option>';
		}
		if( $_POST['tabela'] == 'funcionarios' ){
			$consulta = mysql_query("SELECT * FROM dados_do_funcionario WHERE id = '".$id."' ");
			if( mysql_num_rows($consulta) == 0 )
				$frase_erro = 'Clique <a href="meus_dados_estagiarios.php" title="Cadastrar Funcionario" target="_blank">aqui</a> para cadastrar um funcionário';
			else
			while( $objeto_consulta = mysql_fetch_array($consulta) )
				$string .= '<option value="'.$objeto_consulta['idFuncionario'].'">'.$objeto_consulta['nome'].'</option>';
		}
		if( $_POST['tabela'] == 'socios' ){
			$consulta = mysql_query("SELECT * FROM dados_do_responsavel WHERE id = '".$id."' ");
			if( mysql_num_rows($consulta) == 0 )
				$frase_erro = 'Clique <a href="meus_dados_socio.php" title="Cadastrar Sócio/Resposável"s target="_blank">aqui</a> para cadastrar um sócio/responsável';
			else
			while( $objeto_consulta = mysql_fetch_array($consulta) )
				$string .= '<option value="'.$objeto_consulta['idSocio'].'">'.$objeto_consulta['nome'].'</option>';
		}
		if( $frase_erro != '' ){
			echo json_encode(array("0"=>"erro","1"=>$frase_erro));
		}
		else
			echo json_encode(array("0"=>$string));
	
	endif;

	if( isset( $_POST['getCidadeSelect'] ) ):
		include 'cidade-estado.class.php';
		$id_estado = $_POST['id'] ;
		$cidade = new Cidade($id_estado); 
		echo $cidade->getoptions();	
	endif;

	if( isset( $_POST['converterToMoney'] ) ):
		echo 'R$ '.number_format(floatval($_POST['valor'])*floatval($_POST['pendencias']),2,',','.');
	endif;

	if( isset( $_POST['atualizarMinhaConta'] ) ):
		
		$id_user = $_SESSION['id_userSecaoMultiplo'];
		$tabela = $_POST['tabela'];
		$campo = $_POST['campo'];
		$valor = $_POST['valor'];
		if( $campo == "uf" )
			$valor = strtoupper($_POST['valor']);

	
		if( $tabela == 'dados_cobranca' && $campo == "assiante" ){
			mysql_query("UPDATE login SET ".$campo." = '".$valor."' WHERE id = '".$id_user."' ");		
		}
		else if( $tabela == 'dados_cobranca' && $campo == "uf" ){
			$cidade = explode(';',$_POST['valor']);
			$cidade = $cidade[1];
			mysql_query("UPDATE ".$tabela." SET ".$campo." = '".$cidade."' WHERE id = '".$id_user."' ");
		}
		else
			mysql_query("UPDATE ".$tabela." SET ".$campo." = '".$valor."' WHERE id = '".$id_user."' ");

	endif;

	function calcularDataVencimento($data){

		$datas = new Datas();

		$aux_data_vencimento = explode(' ', $data);
		$aux_data_vencimento = explode('-',$aux_data_vencimento[0]);

		$dia = $aux_data_vencimento[2]; 

		$intervalo = '';

		if( $dia >= 1 && $dia <= 10 ){
			$intervalo = '10';

		}
		if( $dia >= 11 && $dia <= 20 ){
			$intervalo = '20';

		}
		if( $dia >= 21 ){
			$intervalo = '1';
			$data_aux = $aux_data_vencimento[0].'-'.$aux_data_vencimento[1].'-20';
			$data = $datas->somarData($data_aux,30);
			$aux_data_vencimento = explode(' ', $data);
			$aux_data_vencimento = explode('-',$aux_data_vencimento[0]);
		}

		

		$data_aux = $aux_data_vencimento[0].'-'.$aux_data_vencimento[1].'-'.$intervalo;

		
		
		return $datas->desconverterData($datas->somarDiasUteis($data_aux,3));

	}

	if( isset( $_POST['gpsCalcularJuros'] ) ):

		include 'datas.class.php';	
		$datas = new Datas();

		//Pega o valor passado via POST
		$valor = floatval(str_replace(',', '.', str_replace('.', '', $_POST['valor'])));
		//Pega a data de apuração passada via POST
		$data = $datas->converterData($_POST['data']);
		//Pega a data que acomntecerá o pagamento passada via POST
		$data2 = $datas->converterData($_POST['data2']);
		//Cria um objeto que manipula datas
		$datas = new Datas();
		//Define uma data neutra para soma de 1 mes
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-',$aux);
		//Define o proximo mes
		$mes = intval($data_aux[1]) + 1;
		$ano = $data_aux[0];
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';
		//Verifica a quantidade de dias para clculo dos juros e mora
		$dias = $datas->diferencaData($data2,$data_vencimento);
		$mes_final = explode('-', $data2);
		$mes_final = intval($mes_final[1]) - 1;
		$juros = 0;
		$valor_selic = 0;
		$periodo = array();
		if( $dias > 0 ){
			//Pega cada mes entre o inicial e o final - 1 
			while ($datas->diferencaData($data_vencimento,$data2) < -31 ){

				$aux1 = explode('-', $data_vencimento);
				$data_vencimento = $aux1[0].'-'.$aux1[1].'-20';
				$data_vencimento = $datas->somarData($data_vencimento,30);
				$aux = explode('-', $data_vencimento);
				$meses = $aux[1];
				$ano = $aux[0];
				//calcula cada mes * selic correspondente e acumula
				if( $periodo[$meses.$ano] != 'usado' ){
					$consulta = mysql_query("SELECT * FROM selic WHERE ano = '".$ano."' AND mes = '".$meses."' ");
					$periodo[$meses.$ano] = 'usado';
					$objeto=mysql_fetch_array($consulta);
					$valor_selic = $objeto['valor'];
					$juros = $juros + (floatval(str_replace(',','.',$valor_selic))/100) * $valor;
					// echo $valor_selic.'<br>';
					// echo $meses.$ano.'<br>';
					// echo $juros.'<br>';
				}
			}
			if( $mes < $mes_final + 2 )
				$juros = ($juros) + 0.01 * $valor;//Soma o juros (selic + 10%) do valor total
			else
				$juros = ( $juros ) + 0.01 * $valor;//Soma o juros (selic) do valor total
		}
		$array = array();
		// $juros = floor(($juros * 100))/100;
		$valor_juros = $juros;

		$datas = new Datas();
		$data_aux = explode('-', $data);
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';
		$aux = $datas->somarData($data_aux,30);
		$data_aux = explode('-',$aux);
		$mes = $data_aux[1];
		$ano = $data_aux[0];
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';
		$fim_de_semana = 0;
		//Se dia 20 cai em fim de semana, leva até o primeiro dia util antes do fim de semana e subtrai e faz a conta começar na segunda feira
		while( !$datas->ifDiaUtil($data_vencimento) ){
			$data_vencimento = $datas->subtrairData($data_vencimento,1);
			$fim_de_semana = true;
		}
		if( $fim_de_semana ){
			$data_vencimento = $datas->somarData($data_vencimento,1);
			while( !$datas->ifDiaUtil($data_vencimento) )
				$data_vencimento = $datas->somarData($data_vencimento,1);
			$data_vencimento = $datas->subtrairData($data_vencimento,1);
		}
		$dias = $datas->diferencaData($data2,$data_vencimento);
		$mora = 0;
		if( $dias > 0 ){	
			$juros = $dias * 0.0033;
			if( $juros > 0.2 ){
				$mora = floatval($valor) * 0.2;
			}
			else{
				$mora = $juros * floatval($valor);
			}
		}
		// $mora = floor(($mora * 100))/100;
		$valor_mora = $mora;
		// $valor_juros = $datas->diferencaData('2016-10-19','2016-09-20');
		$array[0] = number_format($valor_mora+$valor_juros,2,',','.');
		$array[1] = number_format( $valor , 2 , ',' , '.');

		$array[2] = number_format( $valor + $valor_mora + $valor_juros ,2,',','.');
		$array[3] = $valor_mora;
		$array[4] = $valor_juros;
		echo json_encode( $array );

	endif;

	if( isset( $_POST['calcularDataDevolucaoPraz'] ) ):
		
		include 'datas.class.php';	
		$data = new Datas();

		$data_inicial = $data->converterData($_POST['data_emprestimo']);
		$prazo = intval($_POST['prazo']);

		echo json_encode(array($data->desconverterData($data->somarData($data_inicial,$prazo))));
	
	endif;

	if( isset( $_POST['calcularValidadeJuros'] ) ):
		
		include 'datas.class.php';	
		$data = new Datas();

		$data_inicio = $data->converterData($_POST['data_emprestimo']);
		$data_fim = $data->converterData($_POST['devolucao_emprestimo']);
		$juros = floatval(str_replace(',', '.' , $_POST['juros_emprestimo'] ));
		$array = array();



		$selic = mysql_query("SELECT * FROM selic WHERE ano = '".$data->getAno($data_inicio)."' AND mes = '".$data->getMes($data->subtrairData($data->primeiroDiaMes($data_inicio),1))."' ");
		$objeto_selic = mysql_fetch_array($selic);

		$taxa = floatval(str_replace(',', '.',$objeto_selic['valor']));
		$periodo = 	
			floatval($data->diasRestantesMes($data_inicio)) / floatval($data->diasPorMes($data_inicio)) +
			floatval($data->diferencaMeses($data->somarData($data->ultimoDiaMes($data_inicio),1),$data_fim)) +
			floatval($data->diasTranscorridosMes($data_fim)) / floatval($data->diasPorMes($data_fim)) ;
		
		$formula = 	floatval(pow( ( 1 + $taxa / 100 ) , $periodo )) - 1;

		$juros_maximo = floatval( number_format( $formula*100 , 2 , '.' , '' ) ) ;

		if( $juros > $juros_maximo )
			$array[0] = 'erro';
		else
			$array[0] = 'ok';
		$array[1] = $juros_maximo;
		echo json_encode($array);

	endif;

	if( isset( $_POST['calcularJurosInicial'] ) ):
		
		include 'datas.class.php';	
		$data = new Datas();

		$data_inicio = $data->converterData($_POST['data_emprestimo']);
		$data_fim = $data->converterData($_POST['devolucao_emprestimo']);

		$array = array();

		$taxa = 1;
		$periodo = 	
			floatval($data->diasRestantesMes($data_inicio)) / floatval($data->diasPorMes($data_inicio)) +
			floatval($data->diferencaMeses($data->somarData($data->ultimoDiaMes($data_inicio),1),$data_fim)) +
			floatval($data->diasTranscorridosMes($data_fim)) / floatval($data->diasPorMes($data_fim)) ;
		
		$formula = 	floatval(pow( ( 1 + $taxa / 100 ) , $periodo )) - 1;

		$array[0] = floatval( number_format( $formula*100 , 2 , '.' , '' ) ) ;
		echo json_encode($array);
	
	endif;

	if( isset( $_POST['calcularIOF'] ) ):
		
		include 'datas.class.php';	
		$data = new Datas();		

		$valor = floatval(str_replace(",",".",str_replace(".","",$_POST['valor'])));
		$data1 = $data->converterData($_POST['data1']);
		$data2 = $data->converterData($_POST['data2']);
		$juros = floatval(str_replace(",",".",$_POST['juros']))/100;

		$valor_liquido = $valor;

		$tipo = $_POST['tipo'];

		$valorIR = $juros * $valor;

		$dias = $data->diferencaData($data2,$data1);

		$erro = 0;
		if( $dias <= 0 )
			$erro = 1;

		$array = array();

		//Define a aliquota em relação ao valor e tipo de emprestimo(tipo = PF->PJ ou PJ->PF)
		if( $tipo == 1 && $valor > 30000 ){
			$aliquota_dia_iof = 0.000041;
		}
		else if( $tipo == 2 && $valor > 30000 ){
			$aliquota_dia_iof = 0.000082;
		}
		else if( $tipo == 2 && $valor <= 30000 ){
			$aliquota_dia_iof = 0.000082;	
		}
		else if( $tipo == 1 && $valor <= 30000 ){
			$aliquota_dia_iof = 0.0000137;	
		}
		//multiplica o numero de dias do emprestimo pela aliquota, determinada pelo valor e tipo de emprestimo(tipo = PF->PJ ou PJ->PF)
		$aux = floatval($dias * $aliquota_dia_iof);
		if( $aux > 0.015 )
			$aux = 0.015;

		//Define o valor do IFO -> // [(dias * aliquota) + 0.38%] * valor
		$valorIOF = (0.0038 + $aux) * $valor;
		// [ (dias * aliquota) + 0.38% ] * valor
		

		//Pega o valor total com juros
		$valor = $valor * $juros + $valor;
		//Define a data de pagamento da IOF
		$data1 = explode(' ', $data1);
		//Pega o dia
		$aux_ano = explode('-', $data1[0]);

		$data1 = calcularDataVencimento($data1[0]);

		$ano = $aux_ano[0];


		//Pega os dados do IRRF no banco
		$consulta = mysql_query("SELECT * FROM tabelas WHERE ano_calendario = '".$ano."' ");
		$objeto=mysql_fetch_array($consulta);

		// 22,5%, em aplicações com prazo de até 180 dias;
		// 20%, em aplicações com prazo de 181 dias até 360 dias;
		// 17,5%, em aplicações com prazo de 361 dias até 720 dias;
		// 15%, em aplicações com prazo acima de 720 dias.
		if( $dias <= 180 ){

			$aliquota = 22.5;
			$desconto = 0;

		}
		else if( $dias <= 360 ){
			
			$aliquota = 20;
			$desconto = 0;

		}
		else if( $dias <= 720 ){
			
			$aliquota = 17.5;
			$desconto = 0;

		}
		else{
			
			$aliquota = 15;
			$desconto = 0;

		} 

		$valorIR = $valorIR * $aliquota/100 - $desconto;
		$valorIR = number_format($valorIR,2,',','.');

		// }

		//Salva o valor do IOF
		if( $tipo == 1 ){
			$array[0] = "Isento";
			$valorIOF = 0;
		}
		else
			$array[0] = "".number_format($valorIOF,2,',','.');
		//Salva o valor total com os juros
		$array[1] = number_format($valor,2,',','.');
		//Salva a data de pagamento do IOF
		$array[2] = $data1;
		//Salva a data de pagamento do emprestimo
		$array[3] = $_POST['data2'];

		$array[4] = "".$valorIR;

		$array[5] = number_format( ($valor_liquido) , 2 , ',' , '.'  );

		$array[6] = $erro;

		// if( $valor < 0 || $valorIOF < 0 )
			// $array[2] = 1;			

		echo json_encode($array);
	
	endif;

	if( isset( $_POST['getDadosSocio'] ) ):
		
		$id_user = $_SESSION['id_empresaSecao'];

		$id = $_POST['id'];
		$consulta = mysql_query("SELECT nome,endereco,cep,estado,cidade,rg,cpf,nacionalidade,profissao,estado_civil FROM dados_do_responsavel WHERE idSocio = '".$id."' AND id = '".$id_user."' ");
		$objeto=mysql_fetch_array($consulta);

		echo json_encode($objeto);
	
	endif;

	if( isset( $_POST['editar_item'] ) ):
		
		// include '../conect.php';

		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id = '".$id."' ");
			
	endif;

	if( isset( $_POST['editar_item2'] ) ):
		
		// include '../conect.php';

		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id_user = '".$id."' ");
			
	endif;

	if( isset( $_POST['editar_item_demos'] ) ):
		
		$id = $_POST['id'];
		$valor = $_POST['valor'];
		$campo = $_POST['campo'];
		$tabela = $_POST['tabela'];

		$consulta = mysql_query("SELECT * FROM ".$tabela." WHERE id = '".$id."' ");
		$objeto=mysql_fetch_array($consulta);

		if( $objeto['id'] == '' )
			mysql_query("INSERT INTO `".$tabela."`(`id`, `texto`) VALUES ('".$id."','".$tabela."')");

		mysql_query("UPDATE ".$tabela." SET `".$campo."`= '".$valor."' WHERE id = '".$id."' ");
		echo 'aki';
	endif;


	//Traz as cidades do estado escolhido
	if( isset( $_POST['uf'] ) ):
		
		$consulta = mysql_query("SELECT * FROM estados WHERE sigla = '".$_POST['uf']."' ");
		$objeto=mysql_fetch_array($consulta);

		$consulta = mysql_query("SELECT * FROM cidades WHERE id_uf = '".$objeto['id']."' ");
		while( $objeto=mysql_fetch_array($consulta) ){
			echo '<option value="'.$objeto['cidade'].'">'.$objeto['cidade'].'</option>';
		}

	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'salvarTipoContador' ):

		$_SESSION['tipoContador'] = $_POST['tipo'];

	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'salvarContador' ):
		
		$tipo = $_POST['tipo'];
		$nome = $_POST['nome'];
		$crc = $_POST['crc'];
		$endereco = $_POST['endereco'];
		$cidade = $_POST['cidade'];
		$estado = $_POST['estado'];
		$cep = $_POST['cep'];
		$id_user = $_SESSION['id_empresaSecao'];
		$id = $_POST['id_item'];

		if( $id == '-' )
			$consulta = mysql_query("INSERT INTO `dados_contador_balanco`(`tipo`,`nome`, `crc`, `endereco`, `cidade`, `estado`, `cep`, `id_user`) VALUES ('".$tipo."','".$nome."','".$crc."','".$endereco."','".$cidade."','".$estado."','".$cep."','".$id_user."')");
		else
			$consulta = mysql_query("UPDATE `dados_contador_balanco` SET `tipo`='".$tipo."',`nome`='".$nome."',`crc`='".$crc."',`endereco`='".$endereco."',`cidade`='".$cidade."',`estado`='".$estado."',`cep`='".$cep."',`id_user`='".$id_user."' WHERE id = '".$id."' ");
	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'excluirItemId' ):
		
		$id_item = $_POST['id_item'];

		$id = $_POST['id'];

		$consulta = mysql_query("SELECT * FROM anexos_balanco_patrimonial WHERE id = '".$id_item."' ");
		$objeto=mysql_fetch_array($consulta);

		$file_name = $objeto['arquivo'];

		unlink("upload/anexos_balanco/".$file_name);

		$consulta = mysql_query("DELETE FROM `anexos_balanco_patrimonial` WHERE id = '".$id_item."' AND id_user = '".$id."'");
	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'gerarItensAnexo' ):
		
		$string = 'Não existem anexos para este item';

		$consulta = mysql_query("SELECT * FROM `anexos_balanco_patrimonial` WHERE  ano = '".$_POST['ano']."' AND id_user = '".$_POST['id']."' AND tipo = '".$_POST['tipo']."'  ");
		if( mysql_num_rows($consulta) > 0 )
			$string = '';
		while( $objeto=mysql_fetch_array($consulta) ){

			$string .= '<tr><td><i nome-arquivo="'.$objeto['arquivo'].'" class="fa fa-times excluirAnexoBalanco" id="'.$objeto['id'].'" aria-hidden="true" style="color:red;font-size:15px;"></i>&nbsp;<a href="upload/anexos_balanco/'.$objeto['arquivo'].'" download>'.substr($objeto['arquivo'],0,40).'</a></td></tr>';

		}

		echo $string;
	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'atualizarCampoBalanco' ):
			
		$id = $_POST['id'];

		$ano = $_POST['ano'];

		$campo = $_POST['campo'];

		$valor = $_POST['valor'];

		$valor = str_replace('.', '', $valor);

		$valor = str_replace(',', '.', $valor);
		// echo $valor;
		$consulta = mysql_query("SELECT * FROM balanco_patrimonial WHERE id_user = '".$id."' AND ano = '".$ano."' ");
		$objeto=mysql_fetch_array($consulta);

		if( $objeto['id'] == '' ){
			
			mysql_query("INSERT INTO `balanco_patrimonial`(`id`, `id_user`, `ano`) VALUES ('','".$id."','".$ano."')");

		}
		else{
			mysql_query("UPDATE `balanco_patrimonial` SET `".$campo."` = '".$valor."' WHERE id_user = '".$id."' AND ano = '".$ano."' ");
			// echo "UPDATE `balanco_patrimonial` SET `".$campo."` = '".$valor."' WHERE id_user = '".$id."' AND ano = '".$ano."' ";
			
		}
	
	endif;
	
	function verificarCadastroLivroCaixa($item,$data,$valor){
		if( $item == 'Imóveis (prédios)' ){
			$categoria = 'Imóveis';
			$subcategoria = 'Apartamento';
			$existe_categoria = true;
		}
		if( $item == 'Veículos' ){
			$categoria = 'Veículos';
			$existe_categoria = false;
		}
		if( $item == 'Móveis e utensílios' ){
			$categoria = 'Móveis e utensílios';
			$existe_categoria = false;
		}
		if( $item == 'Computadores e periféricos' ){
			$categoria = 'Máquinas e equipamentos';
			$subcategoria = 'Computadores e periféricos';
			$existe_categoria = true;
		}
		if( $item == 'Máquinas e equipamentos' ){
			$categoria = 'Máquinas e equipamentos';
			$subcategoria = 'Máquinas e equipamentos';
			$existe_categoria = true;
		}
		if( $item == 'Terreno' ){
			$categoria = 'Imóveis';
			$subcategoria = 'Terreno';
			$existe_categoria = true;
		}
		$consulta = mysql_query("SELECT * FROM `user_".$_SESSION['id_empresaSecao']."_livro_caixa` WHERE categoria = '".$categoria."' AND saida = '".$valor."' AND data = '".$data."' ");
		// echo("SELECT * FROM `user_".$_SESSION['id_empresaSecao']."_livro_caixa` WHERE categoria = '".$categoria."' AND saida = '".$valor."' AND data = '".$data."' ");
		$livro_caixa = mysql_fetch_array($consulta);
		if( mysql_num_rows($consulta) > 0 ){
			if( $existe_categoria ){		
				$consulta = mysql_query("SELECT * FROM `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` WHERE id_item = '".$livro_caixa['id']."' AND tipo = '".$subcategoria."'");
				// echo ("SELECT * FROM `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` WHERE id_item = ".$livro_caixa['id']." AND tipo = '".$subcategoria."'");
				$bens = mysql_fetch_array($consulta);
				if( mysql_num_rows($consulta) == 1 )
					return true;
				else 
					return false;
			}
			else
				return true;

		}
		return false;
	}

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'salvarImobilizados' ):
		
		include 'datas.class.php';
		$datas = new Datas();

		$id_user = $_POST['id'];
		
		$ano = $_POST['ano'];
		
		$itens = $_POST['itens'];

		$linha = explode('_:_', $itens);

		// $consulta = mysql_query("DELETE FROM `imobilizados` WHERE id_user = '".$id_user."' AND ano = '".$ano."' ");
		// $objeto=mysql_fetch_array($consulta);

		$string = "(";
		$erro = array();
		$erro[0] = 'ok';
		foreach ($linha as $key) {
			
			$aux = explode("_;_", $key);

			$item = $aux[0];
			$quantidade = $aux[1];
			$valor = trim(str_replace(',', '.', str_replace('.', '',$aux[2])));
			$ano_item = $aux[3];
			$id_item = $aux[4];

			$aux = explode('/',$ano_item);
			$data = $datas->converterData($ano_item);
			$ano_item = $aux[2];


			if( !verificarCadastroLivroCaixa($item,$data,$valor) ){
				if( $item != '' ){
					$erro[0] = 'erro';
					$erro[] = $item;
				}				
			}
			if( $item != '' ){
				if( $id_item == '' ){
					mysql_query("INSERT INTO `imobilizados`(`id_user`, `ano`, `item`, `quantidade`, `valor`, `ano_item`, `data`) VALUES ('".$id_user."','".$ano."','".$item."','".$quantidade."','".$valor."','".$ano_item."','".$data."')");
					$string .= mysql_insert_id().',';
				}
				else{
					mysql_query("UPDATE `imobilizados` SET `item`='".$item."',`quantidade`='".$quantidade."',`valor`='".$valor."',`ano_item`='".$ano_item."',`data`='".$data."' WHERE id = '".$id_item."' ");
					$string .= $id_item.',';
				}
			}
		
			
		}

		$string .= "-1)";

		mysql_query("DELETE FROM `imobilizados` WHERE id NOT IN ".$string." AND id_user = '".$id_user."' AND ano = '".$ano."' ");
		
		echo json_encode($erro);
	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'salvarIntangiveis' ):
		
		$id_user = $_POST['id'];
		$ano = $_POST['ano'];

		$itens = $_POST['itens'];

		$linha = explode('_:_', $itens);

		// $consulta = mysql_query("DELETE FROM `intangiveis` WHERE id_user = '".$id_user."' AND ano = '".$ano."' ");
		// $objeto=mysql_fetch_array($consulta);

		$string = "(";

		foreach ($linha as $key) {
			
			$aux = explode("_;_", $key);

			$item = $aux[0];
			$quantidade = $aux[1];
			$valor = str_replace(',', '.', str_replace('.', '',$aux[2]));
			$ano_item = $aux[3];
			$id_item = $aux[4];

			if( $item != '' ){
				if( $id_item == '' ){
					mysql_query("INSERT INTO `intangiveis`(`id_user`, `ano`, `item`, `quantidade`, `valor`, `ano_item`) VALUES ('".$id_user."','".$ano."','".$item."','".$quantidade."','".$valor."','".$ano_item."')");
					$string .= mysql_insert_id().',';
				}
				else{
					mysql_query("UPDATE `intangiveis` SET `item`='".$item."',`quantidade`='".$quantidade."',`valor`='".$valor."',`ano_item`='".$ano_item."' WHERE id = '".$id_item."' ");
					$string .= $id_item.',';
				}
			}
			
		}

		$string .= "-1)";

		mysql_query("DELETE FROM `intangiveis` WHERE id NOT IN ".$string." AND id_user = '".$id_user."' AND ano = '".$ano."' ");
	
	endif;


	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'salvarContingencia' ):
		
		$id_user = $_POST['id'];
		$ano = $_POST['ano'];

		$texto = $_POST['texto'];

		mysql_query("DELETE FROM contingenciass WHERE id_user = '".$id_user."' AND ano = '".$ano."'");
			
		$consulta = mysql_query("INSERT INTO `contingenciass`( `id_user`, `ano`, `texto`) VALUES ( '".$id_user."','".$ano."','".$texto."' )");
		$objeto=mysql_fetch_array($consulta);
	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'atualizaItemLivroCaixa' ):
		
		$id_user = $_SESSION['id_empresaSecao'];
		
		$novo_id = $_POST['novo_id'];
		$tipo_categoria = $_POST['tipo_categoria'];
		$nova_categoria = $_POST['nova_categoria'];
		$novo_valor = $_POST['novo_valor'];

		$novo_valor = str_replace(',', '.', str_replace('.', '', $novo_valor));

		if( $tipo_categoria == 'saida' ){
			mysql_query("UPDATE `user_".$id_user."_livro_caixa` SET `entrada`=0,`saida`= '".$novo_valor."',`categoria`='".$nova_categoria."' WHERE  id = '".$novo_id."' ");
			
		}
		if( $tipo_categoria == 'entrada' ){
			mysql_query("UPDATE `user_".$id_user."_livro_caixa` SET `entrada`='".$novo_valor."',`saida`= '0',`categoria`='".$nova_categoria."' WHERE  id = '".$novo_id."' ");
		}

	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'atualizarDataLivroCaixa' ):
		
		$id_user = $_SESSION['id_empresaSecao'];
		
		$nova_data = $_POST['nova_data'];
		$novo_id = $_POST['novo_id'];
		
		$nova_data = explode( '/', $nova_data);
		$nova_data = $nova_data[2].'-'.$nova_data[1].'-'.$nova_data[0];

		mysql_query("UPDATE `user_".$id_user."_livro_caixa` SET `data`= '".$nova_data."' WHERE  id = '".$novo_id."' ");
	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'atualizarDescricaoLivroCaixa' ):
		
		$id_user = $_SESSION['id_empresaSecao'];
		
		$nova_descricao = $_POST['nova_descricao'];
		$novo_id = $_POST['novo_id'];
	

		mysql_query("UPDATE `user_".$id_user."_livro_caixa` SET `descricao`= '".$nova_descricao."' WHERE  id = '".$novo_id."' ");
	
	endif;

	if( isset( $_POST['acao'] ) && $_POST['acao'] == 'atualizarDocLivroCaixa' ):
		
		$id_user = $_SESSION['id_empresaSecao'];
		
		$novo_doc = $_POST['novo_doc'];
		$novo_id = $_POST['novo_id'];
	

		mysql_query("UPDATE `user_".$id_user."_livro_caixa` SET `documento_numero`= '".$novo_doc."' WHERE  id = '".$novo_id."' ");
	
	endif;

?>
