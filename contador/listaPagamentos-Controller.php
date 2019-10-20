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

	$tabela = pegaTabelasLista($contadorId);
	
	return $tabela;
}

// Pega a lista de tabelas. 
function pegaTabelasLista($contadorId) {
	
	$tagTable = '';
	$tagTR = '';
	$saldo = '';
	$tipoLancamento = 'pago';
	
	$ano = (isset($_GET['ano']) && is_numeric($_GET['ano']) ? $_GET['ano'] : '');
	
	
	// Instancia a classe responsavel por pegar os dados. 
	$data = new CobrancaContadorData();
	
	$cobrancaContador = $data->PegaTodosDadosPagamento($contadorId, $tipoLancamento, $ano);
		
	// Pega saldo anterior de comissão.
	$saldo = $data->pegaSaldoAPagar($contadorId, $ano);	
	
	// Verifica se existe dados e monta as linhas da tabela.
	if($cobrancaContador) {
		
		$tagTR = GeraLinhasTabela($cobrancaContador, $tipoLancamento, $saldo);
		
		// Monta a tabela com os dados.
		$tagTable = '<table class="tablePagamento" style="width: 100%; margin-bottom: 10px;" cellpadding="5" cellspacing= "0"> 
					<tr>
						<th style="width:10%; text-align: center;">Data Pagto</th>
						<th style="width:23%; text-align: left;">Assinante</th>
						<th style="width:14%; text-align: center;">Serviço</th>
						<th style="width:12%; text-align: center;">Valor total</th>
						<th style="width:12%; text-align: center;">A receber</th>
						<th style="width:12%; text-align: center;">Valor Pago</th>
						<th style="width:12%; text-align: center;">Saldo</th>
						<th style="width:5%; text-align: center;">nº NF</th>
					</tr>
					'.$tagTR.'
				</table>';		
	
	}			
	return $tagTable;
}

// Método criado para montar as linhas da tabela.
function GeraLinhasTabela($cobrancaContador, $tipoLancamento, $saldo) {

	$textTotal = '';
	$tagTR = '';
	$valorLiquido = 0;
	$valorTotal = 0;
	$tagFooter = '';	
	$valorTotal = 0;
	$valorSoma = '';
	$tipoCobranca = "";
	$out = false;
	
	$valorSoma = $saldo;
		
	if($cobrancaContador) {
	
		foreach($cobrancaContador as $val) {
			
			$dataPagamentoAux = '';
			
			// Variável que recebe o valor do status.
			$statusServico = '';
			
			// Verifica se existe algum cancelamento no pagamento.
			if( $val->getResultadoAcao() == '9.9') {
		
				// Define o nome do Tipo de pagamento
				$tipoCobranca = PegaServicoNome($val->getTipoAcao());
				$statusServico = "Cancelada";
										
				// Formata o valor Líquido a pagar.
				$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
				$valorTotal = number_format($val->getValorTotal(),2,",",".");
				
			} else {
				
				// Define o nome do Tipo de pagamento
				$tipoCobranca = PegaServicoNome($val->getTipoAcao());

				$valorSoma += $val->getValorLiquido();

				// Formata o valor Líquido a pagar.
				$valorLiquido = number_format($val->getValorLiquido(),2,",",".");
				$valorTotal = number_format($val->getValorTotal(),2,",",".");
			}
			
			// pega a data de pagamento.
			$datePagamento = str_replace('/', '-', $val->getDataPagamento());

			if($val->getDataPagamento()) {
				$dataPagamentoAux = date('d/m/Y', strtotime($datePagamento)); 
			}
			
			// Verifica se o link da nota fisca eletronica foi informada.
			if($val->getLinkNFE()) {
				$linkNFE = "		<a href='".$val->getLinkNFE()."' id='linkNF_".$val->getCobrancaContadorId()."' target='_blank' data-id='".$val->getCobrancaContadorId()."'>"
							."		<i class='fa fa-file-text-o icone-download' style='font-size: 16px;' aria-hidden='true'></i>"
							."	</a>";
				
				$linkColor = "color:#024A68;";
			} else {
				$linkNFE = "		<a id='linkNF_".$val->getCobrancaContadorId()."' target='_blank' data-id='".$val->getCobrancaContadorId()."' style='display:none;'>"
							."		<i class='fa fa-file-text-o icone-download' style='font-size: 16px;' aria-hidden='true'></i>"
							."	</a>";
				
				$linkColor = "color:#AAA;";
			}
		
			if($val->getTipoLancamento() == $tipoLancamento) {
				
				$valorSoma -= $val->getValorTotal();
					
				// Formata o valor Líquido a pagar.
				$valorTotal = number_format($val->getValorTotal(),2,",",".");
				
				$out .= " <tr class='linhaStatus'> "
						."	<td class='td_calendario' align='center'>".$dataPagamentoAux."</td> "
						."	<td colspan='4' style='text-align:right;'></td> "
						."	<td>R$ ".$valorTotal."</td> "
						."	<td>R$ ".number_format($valorSoma,2,',','.')."</td> "
						."	<td></td> "
						." </tr> ";
		
			} elseif($val->getResultadoAcao() == '9.9') {
				
				// Variável com a linhas da tabela.
				$out .= " <tr class='linhaEstorno'> "
					."	<td class='td_calendario' align='center'>".$dataPagamentoAux."</td> "
					."	<td class='td_calendario' align='left'> ".$val->getAssinante()." </td> "
					."	<td class='td_calendario' align='center'>".$tipoCobranca."</td> "
					."	<td class='td_calendario' align='center'> R$ ".$valorTotal."</td> "
					."	<td class='td_calendario' align='center'> R$ ".$valorLiquido."</td> "
					."	<td class='td_calendario' align='center'></td> "
					."	<td class='td_calendario' align='center'> R$ ".number_format($valorSoma,2,',','.')."</td> "
					."	<td class='td_calendario' align='center'>".$linkNFE."</td>"
					." </tr> ";
				
			} else {
			
				// Variável com a linhas da tabela.
				$out .= " <tr> "
						."	<td class='td_calendario' align='center'>".$dataPagamentoAux."</td> "
						."	<td class='td_calendario' align='left'> ".$val->getAssinante()." </td> "
						."	<td class='td_calendario' align='center'>".$tipoCobranca."</td> "
						."	<td class='td_calendario' align='center'> R$ ".$valorTotal."</td> "
						."	<td class='td_calendario' align='center'> R$ ".$valorLiquido."</td> "
						."	<td class='td_calendario' align='center'></td> "
						."	<td class='td_calendario' align='center'> R$ ".number_format($valorSoma,2,',','.')."</td> "
						."	<td class='td_calendario' align='center'>"
						."		<i id='btAlt_".$val->getCobrancaContadorId()."' class='linkNFE fa fa-file-text-o icone-download' style='font-size: 16px; ".$linkColor." cursor:pointer;' data-id='".$val->getCobrancaContadorId()."'></i>"
						."		<input type='text' class='inputOutEdit' size='20' id='edit_".$val->getCobrancaContadorId()."' data-id='".$val->getCobrancaContadorId()."' value='".$val->getLinkNFE()."' style='display:none;'  />"
						/*.$linkNFE*/
						."		<img id='loading_".$val->getCobrancaContadorId()."' src='../images/loading.gif' width='16' height='16' style='display:none;' />"
						."	</td> "
						." </tr> ";
			}
		}
	}
	
	return $out;
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
	
	$dados = $pagamentoContador->PegaListaPagamentoContador($contadorId, $mesAno); 
	
	$out = "R$ ".number_format($dados->getValorpagamento(),2,',','.');

	return $out;
}

function PegaServicoNome($servico) {
	
	// Variaável que recebe o nome da ação.
	$tipo = $servico;

	// Array criado para normalizar o nome dos tipo.			
	$arrayTipo = array('A_empresa'=>'Abertura Individual'
				 ,'A_sociedade'=>'Abertura Sociedade'
				 ,'Simples_DAS'=>'Simples'
				 ,'decore'=>'DECORE'
				 ,'Gfip_GPS'=>'Gfip'
				 ,'F_empresa'=>'Baixa Individual'
				 ,'F_sociedade'=>'Baixa Sociedade'
				 ,'MEI-ME'=>'MEI para ME'
				 ,'Rais_negativa'=>'Rais'
				 ,'ComplementarPremium'=>'Complementar'
				 ,'certificado'=>'Certificado Digital'
				 ,'servico_geral'=>'serviço geral'
				 ,'DCTF'=>'DCTFDES'
				 ,'DeSTDA'=>'DeSTDA'
				 ,'IRPF'=>'IRPF'
				 ,'regularizacao_emp'=>'regularização de empresa'	   
				 ,'funcionario_C_D'=>'C/D funcionário');
	

	// Verifica se sera necessario normalizar o nome ação.
	if(isset($arrayTipo[$tipo])) {
		$tipo = $arrayTipo[$tipo];
	}
	
	return $tipo;
}