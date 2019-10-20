<?php
/**
 * Controller da página da index.php
 * Autor: Átano de Farias
 * Data: 30/03/2017 
 */	
require_once('../Model/CobrancaContador/CobrancaContadorData.php');
require_once('../Model/PagamentoContador/PagamentoContadorData.php');

// Método para gerar o filtro.
function gerafiltro() {
	
	$ano = "";
	$atualAno = date('Y');

	// Pega os dados do contador da sessão.
	$DadosContador = json_decode($_SESSION['DadosContador']);
	
	// Pega o dados do contador.
	$contadorId = $DadosContador->contadorId;
	
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
				<b>Ano:</b> 
				<select id="selAno" name="ano">'.$ano.'</select>
				<button id="btnFiltar" type="submit">Pesquisar</button>
			</form>';	
	
	return $tagFiltro;
}

// Pega as tabelas com os pagamentos.
function geraTabelasPagamento() {
	
	$anoAux = date('Y');
	$tabela = '';
	
	// Define o ano e o mês.
	if(isset($_GET['ano']) && !empty($_GET['ano'])){
		$anoAux = $_GET['ano'];
	}
	
	// Pega os dados do contador da sessão.
	$DadosContador = json_decode($_SESSION['DadosContador']);
	
	// Pega o dados do contador.
	$contadorId = $DadosContador->contadorId;

	for($i = 12; $i > 0; $i--) {
		$tabela .= pegaTabelasLista($contadorId, $i, $anoAux);
	}
	
	return $tabela;
}

// Pega a lista de tabelas. 
function pegaTabelasLista($contadorId, $mes, $ano) {
	
	$tagTable = '';
	$textTotal = '';
	$tagTR = "";
	$valorLiquido = 0;
	$valorTotal = 0;
	$valorSoma = '';
	$tagFooter = '';
	$tipoCobranca = "";
	
	// Instancia a classe responsavel por pegar os dados. 
	$data = new CobrancaContadorData();
	
	$cobrancaContador = $data->getCobrancaContador($contadorId, $mes, $ano);
	
	// Verifica se existe dados e monta as linhas da tabela.
	if($cobrancaContador) {
		
		foreach($cobrancaContador as $val) {
			
			// Variável que recebe o valor do status.
			$statusServico = '';			
			
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
			
			
			// Verifica se o link da nota fisca eletronica foi informada.
			if($val->getLinkNFE()) {
				$linkNFE = "		<a href='".$val->getLinkNFE()."' id='linkNF_".$val->getCobrancaContadorId()."' target='_blank' data-id='".$val->getCobrancaContadorId()."'>"
							."		<i class='fa fa-angle-double-right iconesAzuis iconesMed' style='font-size: 16px;'></i>"
							."	</a>";
			} else {
				$linkNFE = "		<a id='linkNF_".$val->getCobrancaContadorId()."' target='_blank' data-id='".$val->getCobrancaContadorId()."' style='display:none;'>"
							."		<i class='fa fa-angle-double-right iconesAzuis iconesMed' style='font-size: 16px;'></i>"
							."	</a>";
			}
		
			// Variável com a linhas da tabela.
			$tagTR .= " <tr> "
					."	<td class='td_calendario' align='center'>".$val->getCobrancaContadorId()."</td>"
					."	<td class='td_calendario' align='center'>".date('d/m/Y', strtotime($datePagamento))."</td> "
					."	<td class='td_calendario' align='left'> ".$val->getAssinante()." </td> "
					."	<td class='td_calendario' align='center'>".$tipoCobranca."</td> "
					."	<td class='td_calendario' align='center'> R$ ".$valorTotal."</td> "
					."	<td class='td_calendario' align='center'> R$ ".$valorLiquido."</td> "
					."	<td class='td_calendario' align='center'>"
					."		<i id='btAlt_".$val->getCobrancaContadorId()."' class='linkNFE fa fa-pencil-square-o' style='font-size: 16px; color:#024A68; cursor:pointer;' data-id='".$val->getCobrancaContadorId()."'></i>"
					."		<input type='text' class='inputOutEdit' id='edit_".$val->getCobrancaContadorId()."' data-id='".$val->getCobrancaContadorId()."' value='".$val->getLinkNFE()."' style='display:none;'  />"
					.$linkNFE
					."		<img id='loading_".$val->getCobrancaContadorId()."' src='../images/loading.gif' width='16' height='16' style='display:none;' />"
					."	</td> "
					." </tr> ";
		}
		
		// Verifica se dever ser apresentador o valor ser pago.
		if(!empty($valorSoma)) {
			$tagFooter = " <tr class='linhaStatus'> "
						."	<td style='text-align:right; border-left:1px solid #f5f6f7;'><b>Valor Pago:</b></td> "
						."	<td colspan='3' class='formTabela' align='left'>".PegaValorPago($contadorId, $mes, $ano)."</td> "
						."	<td style='text-align:right;'><b>Valor a Pagar:</b></td> "
						."	<td>R$ ".number_format($valorSoma,2,',','.')."</td> "
						."	<td style='border-right:1px solid #f5f6f7;'></td> "
						." </tr> ";
		}
	
		// Monta a tabela com os dados.
		$tagTable = '<table class="tablePagamento" style="width: 100%; margin-bottom: 10px;" cellpadding="5" cellspacing= "0"> 
					<tr>         	
						<th colspan="5" align="left" style="background: none; color: #C00; font-size: 14px; padding-left: 0px;"><b>'.PegaMesAno($mes).'</b></th>
					</tr>
					<tr>
						<th style="width:10%; text-align: center;">Pagto Id</th>
						<th style="width:15%; text-align: center;">Data Pagamento</th>
						<th style="width:25%; text-align: left;">Assinante</th>
						<th style="width:15%; text-align: center;">Serviço</th>
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
function PegaValorPago($contadorId, $mes, $ano) {
	
	$out = '';
	
	// Formata mês e ano para o formato 00/0000
	$mesAno = str_pad($mes, 2 , "0", STR_PAD_LEFT).'/'.$ano;
	
	$pagamentoContador = new PagamentoContadorData();
	
	$dados = $pagamentoContador->PegaPagamentoContador($contadorId, $mesAno); 
	
	$out = "R$ ".number_format($dados->getValorpagamento(),2,',','.');

	return $out;
}

