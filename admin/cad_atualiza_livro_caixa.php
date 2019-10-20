<?php

require_once("../conect.php");
require_once("../datas.class.php");

$datas = new Datas();

$teste = '';

function formataDataDB($data){
	$dia = substr($data,0,2);
	$mes = substr($data,3,2);
	$ano = substr($data,-4);
	$retorno = $ano.'-'.$mes.'-'.$dia;
	return $retorno;
}

function formataDataTela($data){
	$dia = substr($data,-2);
	$mes = substr($data,5,2);
	$ano = substr($data,0,4);
	$retorno = $dia.'/'.$mes.'/'.$ano;
	return $retorno;
}

function formataValor($valor){
	$retorno = str_replace(",",".",str_replace(".","",$valor));
	return $retorno;
}

$sql_configuracoes = "SELECT * FROM configuracoes WHERE configuracao = 'mensalidade'";
$rsConfiguracoes = mysql_fetch_array(mysql_query($sql_configuracoes));
$mensalidade = formataValor($rsConfiguracoes['valor']);

$dtInicio = formataDataDB($_POST['dtInicio']);
$dtFim = formataDataDB($_POST['dtFim']);
$descBB = formataValor($_POST['descBB']);
$descCielo = formataValor($_POST['descCielo']);

// CHECANDO SE HÁ ALGUM PAGAMENTO SEM O NUMERO DA NOTA FISCAL PARA O PERIODO SELECIONADO
$queryCheckNumeroNF = "
	SELECT 
		data_pagamento data, 
		count(*) total
	FROM 
		relatorio_cobranca
	WHERE 
		data_pagamento BETWEEN '" . $dtInicio . "' AND '" . $dtFim . "'
		AND data_pagamento IS NOT NULL
		AND numero_NF = 0
		AND resultado_acao IN ('1.2','2.1')
	GROUP BY 1
";
$resultadoCheckNF = mysql_query($queryCheckNumeroNF);

if(mysql_num_rows($resultadoCheckNF) > 0){

	$mensagemRetorno = "";
	while($linha = mysql_fetch_array($resultadoCheckNF)){
		$mensagemRetorno .= 'Existem ' . str_pad($linha['total'],3," ",STR_PAD_LEFT) . ' pagamentos sem o número de nota fiscal na data de ' . formataDataTela($linha['data']) . '<br>';
	}
	echo json_encode(array('status'=>'' . $mensagemRetorno . 'Não foi possível atualizar o livro caixa!<br>Selecione outro período e tente novamente'));
	exit;

}
// FIM CHECANDO SE HÁ ALGUM PAGAMENTO SEM O NUMERO DA NOTA FISCAL PARA O PERIODO SELECIONADO

// Variável com o resultado da ação dos servços.
$resultadoAcaoSerico = "'8.2','8.3','8.4','8.5','8.6','8.7','8.8','8.9','9.0','9.4','9.5','9.6','9.7','9.8','9.9','11.0','11.1','11.2','11.3','11.4'";

// Query para pegar os dados de pagamentos.
$query = "

	SELECT
		r.data_pagamento
		, (CASE WHEN r.resultado_acao IN('7.1',".$resultadoAcaoSerico.") 
					AND c.contadorId != 13
					AND r.tipo NOT IN('Premium','Standard')
					AND r.tipo_cobranca = 'boleto' THEN 'avulso-boleto'
				WHEN r.resultado_acao IN(".$resultadoAcaoSerico.")
					AND c.contadorId != 13
					AND r.tipo NOT IN('Premium','Standard')
					AND r.tipo_cobranca <> 'boleto' THEN 'avulso-cartao'
				WHEN r.resultado_acao IN('1.2','9.9') AND r.tipo IN('Standard') AND r.tipo_cobranca = 'boleto' THEN 'boleto'
				WHEN r.resultado_acao IN('1.2','9.9') AND r.tipo IN('Premium','ComplementarPremium') AND r.tipo_cobranca = 'boleto' THEN 'boleto'
				WHEN r.resultado_acao IN('7.1',".$resultadoAcaoSerico.") 
					AND c.contadorId = 13
					AND r.tipo NOT IN('Premium','Standard')
					AND r.tipo_cobranca = 'boleto' THEN 'boleto'
				WHEN r.resultado_acao IN('2.1','9.9') AND r.tipo IN('Standard') AND r.tipo_cobranca <> 'boleto' THEN 'cartao'
				WHEN r.resultado_acao IN('2.1','9.9') AND r.tipo IN('Premium') AND r.tipo_cobranca <> 'boleto' THEN 'cartao'
				WHEN r.resultado_acao IN(".$resultadoAcaoSerico.")
					AND c.contadorId = 13
					AND r.tipo NOT IN('Premium','Standard')
					AND r.tipo_cobranca <> 'boleto' THEN 'cartao'				
				END) tipo
		, count(*) qtd
		, (CASE WHEN r.tipo IN('Premium','ComplementarPremium') AND r.resultado_acao IN ('1.2','2.1','9.9') THEN  'Premium'
				WHEN r.tipo IN('Standard') AND r.resultado_acao IN ('1.2','2.1','9.9') THEN  'Standard'
				WHEN r.resultado_acao IN(".$resultadoAcaoSerico.")
					AND c.contadorId = 13
					AND r.tipo NOT IN('Premium','Standard')
					AND (r.tipo_cobranca = 'boleto' OR r.tipo_cobranca <> 'boleto') THEN 'Standard'
				END) plano
		,SUM(r.valor_pago) total
	FROM relatorio_cobranca r
	LEFT JOIN cobranca_contador c ON c.idRelatorio = r.idRelatorio	
	WHERE
		r.data_pagamento BETWEEN '".$dtInicio."' AND '".$dtFim."'
		AND r.data_pagamento IS NOT NULL
		AND ((r.numero_NF <> 0 AND r.resultado_acao IN ('1.2','2.1','9.9')) OR (r.resultado_acao IN('7.1',".$resultadoAcaoSerico.") AND r.tipo NOT IN('Premium','Standard')))
	GROUP BY 1,2,4;

";

$teste = $query;

// execulta a consulta.
$resultado = mysql_query($query);

$insertBoleto = 0;
$insertCartao = 0;
$insertRepasseB = 0;
$insertRepasseC = 0;

$arrayResultado = '';
$data_pagamento = '';
$qtdBoleto = 0;
$qtdPreminumBoleto = 0;
$qtdPreminumCartao = 0;
$qtdAvulsoCartao = 0;
$qtdAvulsoBoleto = 0;
$valorBoleto = 0;
$valorCartao = 0;
$valorPremiumBoleto = 0;
$valorPremiumCartao = 0;
$valorAvulsoCartao = 0;
$valorAvulsoBoleto = 0;
$statusBoleto = false;
$statusCartao = false;	
$totalSecudario = 0;
$metadePreminumBoleto = 0;
$metadePreminumCartao = 0;

// Monta a estrutura para atualizar a tabela do livro caixa do contador amigo.
while($linhaPgto = mysql_fetch_array($resultado)){
	
	//Verifica se a data do pagamento e a mesma para os outros pagamento da mesma data.
	if($data_pagamento == $linhaPgto['data_pagamento']) {
		
		// Pega a quantidade e o valor dos pagamento.
		if($linhaPgto['tipo'] == 'boleto') {
			
			// Verifica se o pagamento com o boleto e o premium caso seja o premium  ele pega metade do valor.
			if($linhaPgto['plano'] == 'Premium') {			
				$qtdPreminumBoleto = $linhaPgto['qtd'];
				$metadePreminumBoleto = number_format(($linhaPgto['total'] / 2),2,'.','');
				$valorPremiumBoleto = $linhaPgto['total'];
			} else {
				$qtdBoleto = $linhaPgto['qtd'];
				$valorBoleto = $linhaPgto['total'];
			}
			
			// Define o status do boleto como verdadeiro para informar que sera por ele que serra feita a inclusão da taxa bancaria.
			$statusBoleto = true;
			
		} elseif($linhaPgto['tipo'] == 'cartao') {
			
			// Verifica se o pagamento com o cartão e o premium caso seja o premium  ele pega metade do valor.
			if($linhaPgto['plano'] == 'Premium') {			
				$qtdPremiumCartao = $linhaPgto['qtd'];
				$metadePreminumCartao = number_format(($linhaPgto['total'] / 2),2,'.','');
				$valorPremiumCartao = $linhaPgto['total'];
			} else {
				$qtdCartao = $linhaPgto['qtd'];
				$valorCartao = $linhaPgto['total'];
			}
			
			// Define o status do boleto como verdadeiro para informar que sera por ele que serra feita a inclusão da taxa bancaria.
			$statusCartao = true;
			
		} elseif($linhaPgto['tipo'] == 'avulso-boleto') {
			$qtdAvulsoBoleto = $linhaPgto['qtd'];
			$valorAvulsoBoleto = $linhaPgto['total'];
		} elseif($linhaPgto['tipo'] == 'avulso-cartao') {
			$qtdAvulsoCartao = $linhaPgto['qtd'];
			$valorAvulsoCartao = $linhaPgto['total'];
		}
	} else {
		
		// Monta o array com os elementos do pagamento de boleto
		if($valorBoleto > 0 || $valorPremiumBoleto > 0) {
			
			// Pega a quantidade de boletos.
			$qtd = $qtdBoleto + $qtdPreminumBoleto + $qtdAvulsoBoleto;
			
			// Pega o valor total de boleto Standard e Premium.
			$total = $valorBoleto + ($valorPremiumBoleto - $metadePreminumBoleto);
			
			// Monta o array 			
			$arrayResultado[] = array('data_pagamento'=>$data_pagamento,'tipo'=>'boleto','qtdBoleto'=>$qtd,'total'=>$total);
		}
		// Monta o array com os elementos do pagamento de Cartão
		if($valorCartao > 0 || $valorPremiumCartao > 0) {
			
			// Pega o valor total do cartão para pagamento Standard e Premium.
			$total = $valorCartao + ($valorPremiumCartao - $metadePreminumCartao);
			
			// Pega o total secundadio para calcular também os avulsos
			$totalSecudario = $total + $valorAvulsoCartao + $metadePreminumCartao;
			$arrayResultado[] = array('data_pagamento'=>$data_pagamento,'tipo'=>'cartao','qtdCartao'=>$qtd,'total'=>$total,'totalSecudario' => $totalSecudario);
		}
		// Monta o array com os elementos do pagamento de valor a repassar
		if($metadePreminumBoleto > 0 || $valorAvulsoBoleto > 0) {

			// Pega a quantidade de boletos caso não tenha sido informado os boleto standard e premium.
			$qtdBoleto = $qtdAvulsoBoleto + $qtdPreminumBoleto;

			// Pega o total de avulsos e premium.
			$total = $valorAvulsoBoleto + $metadePreminumBoleto;

			// monta o array.
			$arrayResultado[] = array('data_pagamento'=>$data_pagamento, 'tipo'=>'avulso-boleto', 'qtdBoleto'=>$qtdBoleto, 'total'=>$total, 'statusBoleto'=>$statusBoleto);			
		} 
		if($metadePreminumCartao > 0 || $valorAvulsoCartao > 0) {

			// Pega o total de avulsos e premium.
			$total = $valorAvulsoCartao + $metadePreminumCartao;

			// Pega o total secundario para realiza o calculo da cielo caso nao foi informado no pagamento com o cartão. 
			$totalSecudario = $total;

			// monta o array.
			$arrayResultado[] = array('data_pagamento'=>$data_pagamento, 'tipo'=>'avulso-cartao', 'total'=>$total, 'statusCartao'=>$statusCartao, 'totalSecudario' => $totalSecudario);
		}
		
		// Zera os valores.
		$qtdBoleto = 0;
		$qtdPreminumBoleto = 0;
		$qtdPreminumCartao = 0;
		$qtdAvulsoCartao = 0;
		$qtdAvulsoBoleto = 0;
		$valorBoleto = 0;
		$valorCartao = 0;
		$valorPremiumBoleto = 0;
		$valorPremiumCartao = 0;
		$valorAvulsoCartao = 0;
		$valorAvulsoBoleto = 0;
		$statusBoleto = false;
		$statusCartao = false;
		$totalSecudario = 0;
		$metadePreminumBoleto = 0;
		$metadePreminumCartao = 0;		
		
		// Pega a data de pagamento.
		$data_pagamento = $linhaPgto['data_pagamento'];
		
		// Este condição e realizada para efetuar a primeira inclusão de dados.
		if($linhaPgto['tipo'] == 'boleto') {
			
			// Verifica se o pagamento com o boleto e o premium caso seja o premium  ele pega metade do valor.
			if($linhaPgto['plano'] == 'Premium') {		
				$qtdPreminumBoleto = $linhaPgto['qtd'];
				$metadePreminumBoleto = number_format(($linhaPgto['total'] / 2),2,'.','');
				$valorPremiumBoleto = $linhaPgto['total'];
			} else {
				$qtdBoleto = $linhaPgto['qtd'];
				$valorBoleto = $linhaPgto['total'];
			}
			
			// Define o status do boleto como verdadeiro para informar que sera por ele que serra feita a inclusão da taxa bancaria.
			$statusBoleto = true;
			
		} elseif($linhaPgto['tipo'] == 'cartao') {
			
			// Verifica se o pagamento com o cartão e o premium caso seja o premium  ele pega metade do valor.
			if($linhaPgto['plano'] == 'Premium') {			
				$qtdPremiumCartao = $linhaPgto['qtd'];
				$metadePreminumCartao = number_format(($linhaPgto['total'] / 2),2,'.','');
				$valorPremiumCartao = $linhaPgto['total'];
			} else {
				$qtdCartao = $linhaPgto['qtd'];
				$valorCartao = $linhaPgto['total'];
			}
			
			// Define o status do boleto como verdadeiro para informar que sera por ele que serra feita a inclusão da taxa bancaria.
			$statusCartao = true;
			
		} elseif($linhaPgto['tipo'] == 'avulso-boleto') {
			$qtdAvulsoBoleto = $linhaPgto['qtd'];
			$valorAvulsoBoleto = $linhaPgto['total'];
		} elseif($linhaPgto['tipo'] == 'avulso-cartao') {
			$qtdAvulsoCartao = $linhaPgto['qtd'];
			$valorAvulsoCartao = $linhaPgto['total'];
		}
	}
}

// Monta a ultima linha do array que ficou sem ser montada no loop.
if($valorBoleto > 0 || $valorPremiumBoleto > 0) {

	// Pega a quantidade de boletos.
	$qtd = $qtdBoleto + $qtdPreminumBoleto + $qtdAvulsoBoleto;

	// Pega o valor total de boleto Standard e Premium.
	$total = $valorBoleto + ($valorPremiumBoleto - $metadePreminumBoleto);

	// Monta o array 			
	$arrayResultado[] = array('data_pagamento'=>$data_pagamento,'tipo'=>'boleto','qtdBoleto'=>$qtd,'total'=>$total);
}
// Monta o array com os elementos do pagamento de Cartão
if($valorCartao > 0 || $valorPremiumCartao > 0) {

	// Pega o valor total do cartão para pagamento Standard e Premium.
	$total = $valorCartao + ($valorPremiumCartao - $metadePreminumCartao);

	// Pega o total secundadio para calcular também os avulsos
	$totalSecudario = $total + $valorAvulsoCartao + $metadePreminumCartao;
	$arrayResultado[] = array('data_pagamento'=>$data_pagamento,'tipo'=>'cartao','qtdCartao'=>$qtd,'total'=>$total,'totalSecudario' => $totalSecudario);
}
// Monta o array com os elementos do pagamento de valor a repassar
if($metadePreminumBoleto > 0 || $valorAvulsoBoleto > 0) {

	// Pega a quantidade de boletos caso não tenha sido informado os boleto standard e premium.
	$qtdBoleto = $qtdAvulsoBoleto + $qtdPreminumBoleto;

	// Pega o total de avulsos e premium.
	$total = $valorAvulsoBoleto + $metadePreminumBoleto;

	// monta o array.
	$arrayResultado[] = array('data_pagamento'=>$data_pagamento, 'tipo'=>'avulso-boleto', 'qtdBoleto'=>$qtdBoleto, 'total'=>$total, 'statusBoleto'=>$statusBoleto);			
} 
if($metadePreminumCartao > 0 || $valorAvulsoCartao > 0) {

	// Pega o total de avulsos e premium.
	$total = $valorAvulsoCartao + $metadePreminumCartao;

	// Pega o total secundario para realiza o calculo da cielo caso nao foi informado no pagamento com o cartão. 
	$totalSecudario = $total;

	// monta o array.
	$arrayResultado[] = array('data_pagamento'=>$data_pagamento, 'tipo'=>'avulso-cartao', 'total'=>$total, 'statusCartao'=>$statusCartao, 'totalSecudario' => $totalSecudario);
}


// Grava os dados no livreo caixa.
foreach($arrayResultado as $val) { 
		
	$multiplicador = 1;
	$desconto = 0;
	
	if($val['tipo'] == 'boleto'){
		$acao = "1.2";
		$descricao_livro_caixa = "Contador Amigo (boleto BB)";
	}elseif($val['tipo'] == 'cartao'){
		$acao = "2.1";
		$descricao_livro_caixa = "Contador Amigo (Cielo)";
	}elseif($val['tipo'] == 'avulso-boleto'){
		$acao = false;
		$descricao_livro_caixa = "Intermediação de Serviços(boleto BB)";
	}elseif($val['tipo'] == 'avulso-cartao'){	
		$acao = false;
		$descricao_livro_caixa = "Intermediação de Serviços (Cielo)";
	}
	
	// ser for boleto
//	if($val['tipo'] == 'boleto' || $val['tipo'] == 'avulso-boleto') {
//		// Adiciona um dia mais na data de pagamento, pois o banco do brasil so esta repassando o valor para conta no dia seguinte.
//		$dataPagto = $datas->somarDiasUteis($val['data_pagamento'], 1);
//	} else {
		$dataPagto = $val['data_pagamento'];
//	}
 
	// CHECANDO SE HÁ ALGUMA ENTRADA NO LIVRO CAIXA PARA O DIA
	$qry = "SELECT * FROM user_9_livro_caixa 
			 WHERE data = '".$dataPagto."' 
			 AND descricao = '" . $descricao_livro_caixa . "' 
			 AND (categoria IN ('Contador Amigo','Valor a Repassar','Taxas e Comissões Bancárias') 
			 OR categoria IN ('Contador Amigo','Valor a Repassar','Taxas e Comissões Bancárias'))";

	$checkData = mysql_query($qry);

	if(mysql_num_rows($checkData) <= 0){
		
		// Pega o valor a ser pago
		$valor = $val['total'];
		
		// Verifica qual ser o tipo de inclusão.
		if($acao == '1.2') {

			// PEGANDO OS DADOS DAS NOTAS FISCAIS NO DIA
			$queryNFs = "
				SELECT numero_NF
				FROM relatorio_cobranca
				WHERE data_pagamento = '" . $val['data_pagamento'] . "'
				AND data_pagamento IS NOT NULL
				AND numero_NF <> 0
				AND tipo_cobranca = 'boleto'
			";

			$rsNFs = mysql_query($queryNFs);
			$arrNfs = array();

			while($linhaNFs = mysql_fetch_array($rsNFs)){
				array_push($arrNfs,$linhaNFs['numero_NF']);
			}
			
			mysql_query("INSERT INTO user_9_livro_caixa SET data = '" . $dataPagto . "', entrada = " . $valor . ", descricao = '" . $descricao_livro_caixa . "', categoria = 'Contador Amigo', documento_numero = '" . implode(", ",$arrNfs) . "'");
		
		} elseif($acao == '2.1') {
			
			// PEGANDO OS DADOS DAS NOTAS FISCAIS NO DIA
			$queryNFs = "
				SELECT numero_NF
				FROM relatorio_cobranca
				WHERE data_pagamento = '" . $val['data_pagamento'] . "'
				AND data_pagamento IS NOT NULL
				AND numero_NF <> 0
				AND tipo_cobranca != 'boleto'
			";

			$rsNFs = mysql_query($queryNFs);
			$arrNfs = array();

			while($linhaNFs = mysql_fetch_array($rsNFs)){
				array_push($arrNfs,$linhaNFs['numero_NF']);
			}			

			mysql_query("INSERT INTO user_9_livro_caixa SET data = '" . $val['data_pagamento'] . "', entrada = " . $valor . ", descricao = '" . $descricao_livro_caixa . "', categoria = 'Contador Amigo', documento_numero = '" . implode(", ",$arrNfs) . "'");			
		
		} elseif($val['tipo'] == 'avulso-boleto') {
			
			// Pega os ids dos cliente para incluir no documento do valor a repassar reverente ao boleto.
			$qryCliente = " SELECT r.id FROM relatorio_cobranca r
							LEFT JOIN cobranca_contador c ON c.idRelatorio = r.idRelatorio
							WHERE r.tipo_cobranca = 'boleto' 
							AND r.data_pagamento = '".$val['data_pagamento']."'
							AND ((r.resultado_acao IN('7.1',".$resultadoAcaoSerico.") AND r.tipo NOT IN('Premium','Standard') AND c.contadorId != 13) OR (r.tipo = 'Premium' AND r.resultado_acao IN('1.2','9.9') AND r.numero_NF <> 0)); ";
			
			$listIds = mysql_query($qryCliente);
			
			// Seta a variável como array
			$clienteIds = array();
			
			// Percorre a lista de Id e adiciona a um array.
			while($linhaClienteId = mysql_fetch_array($listIds)){
				array_push($clienteIds,$linhaClienteId['id']);
			}
			
			mysql_query("INSERT INTO user_9_livro_caixa SET data = '" . $dataPagto . "', entrada = " . $valor . ", descricao = '" . $descricao_livro_caixa . "', categoria = 'Valor a Repassar', documento_numero = '" . implode(", ",$clienteIds) . "'");
		
		} elseif($val['tipo'] == 'avulso-cartao') {
			
			// Pega os ids dos cliente para incluir no documento do valor a repassar reverente ao cartão.
			$qryCliente = " SELECT r.id FROM relatorio_cobranca r
							LEFT JOIN cobranca_contador c ON c.idRelatorio = r.idRelatorio
							WHERE r.tipo_cobranca != 'boleto' 
							AND r.data_pagamento = '".$val['data_pagamento']."'
							AND ((r.resultado_acao IN('7.1',".$resultadoAcaoSerico.") AND r.tipo NOT IN('Premium','Standard') AND c.contadorId != 13) OR (r.tipo = 'Premium' AND r.resultado_acao IN('2.1','9.9') AND r.numero_NF <> 0));";
			
			$listIds = mysql_query($qryCliente);
			
			$clienteIds = array();
			
			while($linhaClienteId = mysql_fetch_array($listIds)){
				array_push($clienteIds,$linhaClienteId['id']);
			}			
			
			mysql_query("INSERT INTO user_9_livro_caixa SET data = '" . $val['data_pagamento'] . "', entrada = " . $valor . ", descricao = '" . $descricao_livro_caixa . "', categoria = 'Valor a Repassar', documento_numero = '" . implode(", ",$clienteIds) . "'");
		}
			
		if($val['tipo'] == 'boleto'){
			$insertBoleto += 1;
		} elseif($val['tipo'] == 'cartao') {
			$insertCartao += 1;
		} elseif($val['tipo'] == 'avulso-boleto') {
			$insertRepasseB += 1;
		} elseif($val['tipo'] == 'avulso-cartao') {
			$insertRepasseC += 1;
		}

		// INSERINDO A SAIDA DAS TAXAS DE GERAÇÃO DE BOLETO
		if($acao == '1.2' || isset($val['statusBoleto']) && !$val['statusBoleto']) {
			
			$multiplicador = $val['qtdBoleto'];
			
			mysql_query("INSERT INTO user_9_livro_caixa SET data = '" . $dataPagto . "', saida = " . ($descBB * $multiplicador) . ", descricao = 'Serviço de cobrança (Banco do Brasil)', categoria = 'Taxas e Comissões Bancárias'");
			
		} elseif($acao == '2.1' || isset($val['statusCartao']) && !$val['statusCartao']) {
		
			$desconto = ((floatval($descCielo)/100) * $val['totalSecudario']);

			mysql_query("INSERT INTO user_9_livro_caixa SET data = '" . $val['data_pagamento'] . "', saida = " . $desconto . ", descricao = 'Serviço de cobrança (Cielo)', categoria = 'Taxas e Comissões Bancárias'");
			
		}		
	}
}

if($insertBoleto > 0 || $insertCartao > 0 || $insertRepasseC > 0 || $insertRepasseB > 0){
	$status = "Dados atualizados com sucesso";
}else{
	$status = "Não há lançamentos para serem inseridos no livro caixa";
}

echo json_encode(array('status'=>$status, 'teste'=>$teste));
?>