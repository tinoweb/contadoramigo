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
	
		$tabela = '';
	
		// Pega o dados do contador.
		$contadorId = $_GET['contadorId'];
	
		$tabela = pegaTabelasLista($contadorId);
		
		return $tabela;
	}
}

// Pega a lista de tabelas. 
function pegaTabelasLista($contadorId) {
	
	$tagTable = '';
	$tagTR = '';
	$saldo = 0;
	$tipoLancamento = 'comissao';
	
	$ano = (isset($_GET['ano']) && is_numeric($_GET['ano']) ? $_GET['ano'] : '');
	
	// Instancia a classe responsavel por pegar os dados. 
	$data = new CobrancaContadorData();
	
	$cobrancaContador = $data->PegaTodosDadosPagamento($contadorId, $tipoLancamento, $ano);
		
	// Pega saldo anterior de comissão.
	$saldo = $data->PegaSaldoComissao($contadorId, $ano);
	
	// Verifica se existe dados e monta as linhas da tabela.
	if($cobrancaContador) {
		
		$tagTR = GeraLinhasTabela($cobrancaContador, $tipoLancamento, $saldo);
	
		// Monta a tabela com os dados.
		$tagTable = '<table class="tablePagamento" style="width: 100%; margin-bottom: 10px;" cellpadding="5" cellspacing= "0"> 
					<tr>         	
						<th style="width:10%; text-align: center;">Data Pagto</th>
						<th style="width:20%; text-align: left;">Assinante</th>
						<th style="width:15%; text-align: center;">Serviço</th>
						<th style="width:8%; text-align: center;">Status</th>
						<th style="width:10%; text-align: center;">Valor Total</th>
						<th style="width:10%; text-align: center;">Comissão</th>
						<th style="width:11%; text-align: center;">Valor Recebido</th>
						<th style="width:10%; text-align: center;">Saldo</th>
						<th style="width:6%; text-align: center;">Ação</th>
					</tr>
					'.$tagTR.'
				</table>';

	}			
	return $tagTable;

}

// Método criado para montar as linhas da tabela.
function GeraLinhasTabela($cobrancaContador, $tipoLancamento, $saldo) {

	$valorLiquido = 0;
	$comissao = 0;
	$valorTotal = 0;
	$tagFooter = '';
	$tipoCobranca = '';
	$linkNFE = "";
	$out = false;
	
	// Pega saldo anterior de comissão.
	$valorSoma = $saldo;
	
	// Verifica se existe dados e monta as linhas da tabela.
	if($cobrancaContador) {
	
		foreach($cobrancaContador as $val) {
			
			$dataPagamentoAux = '';
			
			// Variável que recebe o valor do status.
			$statusServico = '';
			
			// Verifica se existe algum cancelamento no pagamento.
			if( $val->getResultadoAcao() == '9.9') {
		
				// subtrai o valor liquido do valor total.	
				$comissao = $val->getValorTotal() - $val->getValorLiquido();
				
				// Define o nome do Tipo de pagamento
				$tipoCobranca = PegaServicoNome($val->getTipoAcao());
				$statusServico = "Cancelada";
										
				// Formata o valor Líquido a pagar.
				$valorLiquido = number_format($comissao,2,",",".");
				$valorTotal = number_format($val->getValorTotal(),2,",",".");
				
			} else {
				
				if($val->getTipoLancamento() != $tipoLancamento) {
					// Define o nome do Tipo de pagamento
					$tipoCobranca = PegaServicoNome($val->getTipoAcao());

					$comissao = $val->getValorTotal() - $val->getValorLiquido();

					$valorSoma += $comissao;

					// Formata o valor Líquido a pagar.
					$valorLiquido = number_format($comissao,2,",",".");
					$valorTotal = number_format($val->getValorTotal(),2,",",".");
				}
			}
			
			// pega a data de pagamento.
			$datePagamento = str_replace('/', '-', $val->getDataPagamento());

			if($val->getDataPagamento()) {
				$dataPagamentoAux = date('d/m/Y', strtotime($datePagamento)); 
			}
			
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
						
			if($val->getTipoLancamento() == $tipoLancamento) {
				
				$valorSoma -= $val->getValorTotal();
					
				// Formata o valor Líquido a pagar.
				$valorTotal = number_format($val->getValorTotal(),2,",",".");
				
				$out .= " <tr class='linhaStatus'> "
						."	<td class='td_calendario' align='center'>".$dataPagamentoAux."</td> "
						."	<td colspan='5' style='text-align:right;'></td> "
						."	<td> R$ ".$valorTotal."	</td> "
						."	<td>R$ ".number_format($valorSoma,2,',','.')."</td> "
						."	<td> "
						."		<a class='excluirPagamento' title='Excluir' style='cursor: pointer' date-pagamentoId='".$val->getCobrancaContadorId()."'> "
						."			<i class='fa fa-trash-o iconesAzul iconesGrd'></i>"
						."		</a>"
						."	</td> "
						." </tr> ";
		
			} // Verifica se o pagamento do serviço foi cancelado para realizar o estorno.
			elseif($val->getResultadoAcao() == '9.9') {
				
				// Variável com a linhas da tabela.
				$out .= " <tr class='linhaEstorno'> "
					."	<td class='td_calendario' align='center'>".$dataPagamentoAux."</td> "
					."	<td class='td_calendario' align='left'> ".$val->getAssinante()." </td> "
					."	<td class='td_calendario' align='center'>".$tipoCobranca."</td> "
					."	<td class='td_calendario' align='center'>".$statusServico."</td>"
					."	<td class='td_calendario' align='center'> R$ ".$valorTotal."</td> "
					."	<td class='td_calendario' align='center'> R$ ".$valorLiquido."</td> "
					."	<td class='td_calendario' align='center'></td> "
					."	<td class='td_calendario' align='center'> R$ ".number_format($valorSoma,2,',','.')."</td> "
					."	<td class='td_calendario' align='center'></td>"
					." </tr> ";
			} else {
				
				// Variável com a linhas da tabela.
				$out .= " <tr> "
					."	<td class='td_calendario' align='center'>".$dataPagamentoAux."</td> "
					."	<td class='td_calendario' align='left'> ".$val->getAssinante()." </td> "
					."	<td class='td_calendario' align='center'>".$tipoCobranca."</td> "
					."	<td class='td_calendario' align='center'>".$statusServico."</td>"
					."	<td class='td_calendario' align='center'> R$ ".$valorTotal."</td> "
					."	<td class='td_calendario' align='center'> R$ ".$valorLiquido."</td> "
					."	<td class='td_calendario' align='center'></td> "
					."	<td class='td_calendario' align='center'> R$ ".number_format($valorSoma,2,',','.')."</td> "
					."	<td class='td_calendario' align='center'></td>"
					." </tr> ";
			}
		}
	}			
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