<?php
/**
 * Controller da página da index.php
 * Autor: Átano de Farias
 * Data: 26/04/2017 
 */	
require_once('../Model/DadosContador/DadosContadorData.php'); 
require_once('../Model/CobrancaContador/CobrancaContadorData.php');
require_once('../Model/PagamentoContador/PagamentoContadorData.php');

function pegaNomeContador() {
	
	if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) {
	
		// Pega o dados do contador.
		$contadorId = $_GET['contadorId'];
	
		$dadosContador =  new DadosContadorData();
		$dados = $dadosContador->GetNameContador($contadorId);
		
		$nome = explode(" ", $dados->getNome());
		
		$nome = $nome[0]." ".$nome[1];
		
		return $nome;
	}	
}

// Método para gerar o filtro.
function gerafiltro() {
	
	if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) {
	
		$ano = "";
		$atualAno = date('Y');
	
		// Pega o dados do contador.
		$contadorId = $_GET['contadorId'];
		
		// Instancia a classe responsavel por pegar os dados. 
		$data = new CobrancaContadorData();
		
		// Pega o ano da primeira inclusao do pagamento.
		$anoInicial = $data->getAno($contadorId);	
		
		// Define o ano e o mês.
		if(isset($_GET['ano']) && !empty($_GET['ano']) && isset($_GET['mes']) && !empty($_GET['mes'])){
			$atualAno = $_GET['ano'];
		}
		
		// Pega o ano atual.
		if(!$anoInicial){
			$anoInicial = $atualAno;
		}	
		
		// Cria option com itens do select como do ano.
		for($i = $anoInicial; $i <= date('Y'); $i++){
			if($atualAno == $i){
				$ano .= "<option value='".$i."' selected='selected'>".$i."</option>";
			} else  {
				$ano .= "<option value='".$i."'>".$i."</option>";
			}
		}	
			
		// Metodo tag Filtro.
		$tagFiltro = '<form method="get" action="" >
						<input type="hidden" name="contadorId" value="'.$_GET['contadorId'].'">
						<b>Ano:</b> 
						<select id="selAno" name="ano">'.$ano.'</select>
						<button id="btnFiltar" type="submit">Filtrar</button>
					</form>';	
		
		return $tagFiltro;
	}
}

// Pega as tabelas com os pagamentos.
function geraTabelasPagamento() {
	
	if(isset($_GET['contadorId'])&&!empty($_GET['contadorId'])) {
		
		$anoAux = date('Y');
		$tabela = '';
		
		// Define o ano e o mês.
		if(isset($_GET['ano']) && !empty($_GET['ano'])){
			$anoAux = $_GET['ano'];
		}
	
		// Pega o dados do contador.
		$contadorId = $_GET['contadorId'];
	
		for($i = 12; $i > 0; $i--) {
			$tabela .= pegaTabelasLista($contadorId, $i, $anoAux);
		}
		
		return $tabela;
	}
}

// Pega a lista de tabelas. 
function pegaTabelasLista($contadorId, $mes, $ano) {
	
	$tagTable = '';
	$textTotal = '';
	$tagTR = '';
	$valorLiquido = 0;
	$valorTotal = 0;
	$valorSoma = '';
	$tagFooter = '';
	$tipoCobranca = '';
	$listaPagamentoId = '';
	$linkNFE = "";
	
	// Instancia a classe responsavel por pegar os dados. 
	$data = new CobrancaContadorData();
	
	$cobrancaContador = $data->getCobrancaContador($contadorId, $mes, $ano);
	
	// Verifica se existe dados e monta as linhas da tabela.
	if($cobrancaContador) {
	
		foreach($cobrancaContador as $val) {
			
			// Variável que recebe o valor do status.
			$statusServico = '';
			
			// pega os IDS.
			if($listaPagamentoId) {
				$listaPagamentoId .= ",".$val->getCobrancaContadorId();
			} else {
				$listaPagamentoId .= $val->getCobrancaContadorId();
			}
			
			switch($val->getResultadoAcao()) {
				case '1.2':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Premium";
		
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					
					break;					
				case '2.1':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Premium";
		
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					
					break;
				case '7.1':
					// Define o nome do Tipo de pagamento 
					$tipoCobranca = 'Complementar';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;					
				case '8.2':
					// Define o nome do Tipo de pagamento 
					$tipoCobranca = 'Gfip';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				case '8.3':	
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Simples';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				case '8.4': 
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Defis';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;	
				case '8.5':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Rais';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				case '8.6':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Dirf';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				case '8.7': 
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Baixa Individual';
								
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				case '8.8': 
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'Baixa Sociedade';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				case '8.9': 
					// Define o nome do Tipo de pagamento
					$tipoCobranca = 'MEI para ME';
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				
				case '9.0':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "CPOM";
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					
					break;
				case '9.3':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Avulso";
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					
					break;
				case '9.4':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Abertura Individual";
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					
					break;
				case '9.5':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "Abertura Sociedade";
					
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					break;
				case '9.6':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "DECORE";
		
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
						
					break;
				case '9.7':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = "DBE";
		
					$valorSoma += $val->getValorLiquido();
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
					
					break;
				case '9.9':
					// Define o nome do Tipo de pagamento
					$tipoCobranca = $statusServico = "Cancelada";
					
					// Formata o valor Líquido a pagar.
					$valorLiquido = '0,00';
					$valorTotal = '0,00';
					
					break;
			}		
		
			// pega a data de pagamento.
			$datePagamento = str_replace('/', '-', $val->getDataPagamento());
			
			$linkNFE = '';
			
			// Verifica se o link da nota fisca eletronica foi informada.
			if($val->getLinkNFE()) {
				$linkNFE = "		<a href='".$val->getLinkNFE()."' target='_blank'>"
							."		<i class='fa fa-file-text-o icone-download' style='font-size: 16px;' aria-hidden='true'></i>"
							."	</a>";
			}

			if($val->getStatusServico() == 'Concluído' && $statusServico != 'Cancelada') {
				$statusServico = 'Concluído';
			} else if($val->getStatusServico() == 'Em Aberto' && $statusServico != 'Cancelada'){
				$statusServico = 'Em Aberto';
			} else if($statusServico != 'Cancelada') {
				$statusServico = 'N/A';
			}
			   
			// Variável com a linhas da tabela.
			$tagTR .= " <tr> "
					."	<td class='td_calendario' align='center'>".date('d/m/Y', strtotime($datePagamento))."</td> "
					."	<td class='td_calendario' align='left'> ".$val->getAssinante()." </td> "
					."	<td class='td_calendario' align='center'>".$tipoCobranca."</td> "
					."	<td class='td_calendario' align='center'>".$statusServico."</td>"
					."	<td class='td_calendario' align='center'> R$ ".$valorTotal."</td> "
					."	<td class='td_calendario' align='center'> R$ ".$valorLiquido."</td> "
					."	<td class='td_calendario' align='center'>".$linkNFE."</td>"
					." </tr> ";
		}
		
		// Verifica se dever ser apresentador o valor ser pago.
		if(!empty($valorSoma)) {
			$tagFooter = MontaBarraStatus($valorSoma, $mes, $ano, $listaPagamentoId);				
		}
	
		// Monta a tabela com os dados.
		$tagTable = '<table class="tablePagamento" style="width: 100%; margin-bottom: 10px;" cellpadding="5" cellspacing= "0"> 
					<tr>         	
						<th colspan="5" style="background: none; color: #C00; font-size: 14px; padding-left: 0px; text-align: left;"><b>'.PegaMesAno($mes).'</b></th>
					</tr>
					<tr>         	
						<th style="width:15%; text-align: center;">Data Pagamento</th>
						<th style="width:20%; text-align: left;">Assinante</th>
						<th style="width:15%; text-align: center;">Serviço</th>
						<th style="width:10%; text-align: center;">Status</th>
						<th style="width:15%; text-align: center;">Valor Total</th>
						<th style="width:15%; text-align: center;">Valor líquido a receber</th>
						<th style="width:10%; text-align: center;">nº NF</th>
					</tr>
					'.$tagTR.'
					'.$tagFooter.'
				</table>';
	}			
	return $tagTable;
}

// Método criado para pegar o nome do mês com o ano.
function PegaMesAno($mes){

	$meses = array(
		1 => 'Janeiro',
		'Fevereiro',
		'Março',
		'Abril',
		'Maio',
		'Junho',
		'Julho',
		'Agosto',
		'Setembro',
		'Outubro',
		'Novembro',
		'Dezembro'
	);

	$literal = $meses[$mes];

	return $literal;	
}

// Método que pega o Status.
function MontaBarraStatus($valorSoma, $mes, $ano, $listaPagamentoId) {
	
	$out = '';
	$acao = '';
	$pagamentoId = '';
	$contadorId = '';
	$dataPagamento = '';
	$valorPagamento = '';
	$idsCobrancaContador = '';
	
	// Formata mês e ano para o formato 00/0000
	$mesAno = str_pad($mes, 2 , "0", STR_PAD_LEFT).'/'.$ano;
	
	$contadorId = $_GET['contadorId'];
	
	$pagamentoContador = new PagamentoContadorData();
	
	$dados = $pagamentoContador->PegaPagamentoContador($contadorId, $mesAno); 
	
	if(!empty($dados->getPagamentoId())) {
		$acao = 'update';
		$pagamentoId = $dados->getPagamentoId();
		$dataPagamento = $mesAno;
		$idsCobrancaContador = $listaPagamentoId;
	} else {
		$acao = 'insert';
		$dataPagamento = $mesAno;
		$idsCobrancaContador = $listaPagamentoId;
	}
	
	$out = " <tr class='linhaStatus'> "
			."	<td colspan='3' style='text-align:left; border-left:1px solid #f5f6f7; padding-left: 20px;'>"
			."		<form id='formGrava_".$mes."' action='pagamentocontador_salva.php' method='get' style='margin-bottom: 0px;'>"
			."			<b>Valor Pago:</b> R$ <input id='valorPagamento' class='current' type='text' name='valorPagamento' style='width:80px;' value='".number_format($dados->getValorpagamento(),2,',','.')."' />"
			."			<input type='hidden' name='acao' value='".$acao."' /> "
			."			<input type='hidden' name='pagamentoId' value='".$pagamentoId."' /> "
			."			<input type='hidden' name='contadorId' value='".$contadorId."' /> "
			."			<input type='hidden' name='dataPagamento' value='".$dataPagamento."' /> "
			."			<input type='hidden' name='idsCobrancaContador' value='".$idsCobrancaContador."' /> "
			."			<a class='gravaValor iconeSalva' data-mes='".$mes."' style='margin-left:2px; cursor: pointer;'><i class='fa fa-angle-double-right iconesMed iconeSalvaIco'></i></a>"
			."		</form>"
			."	</td> "
			."	<td class='formTabela' align='left'></td>"
			."	<td style='text-align:right;'><b>Valor a Pagar:</b></td> "
			."	<td>R$ ".number_format($valorSoma,2,',','.')."</td> "
			."	<td style='border-right:1px solid #f5f6f7;'></td> "
			." </tr> ";
	
	return $out;
}