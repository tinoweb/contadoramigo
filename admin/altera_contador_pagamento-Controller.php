<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */	
require_once('../DataBaseMySQL/ClientePremium.php');

class AlteraContadorCliente {
	
	function __construct(){
		
		if(isset($_POST['cobrancaContadorId']) && !empty($_POST['cobrancaContadorId'])){

			// Pega o código do pagamento.
			$cobrancaContadorId = $_POST['cobrancaContadorId'];
			
			// Pega o código do contador
			$contadorId = $_POST['contadorId'];
						
			$clientePremium = new ClientePremium();
			
			// Altera o contador.
			$clientePremium->AtualizaContadorPagamentoPremium($cobrancaContadorId, $contadorId);			
		}
	}
	
	// Monta a tabela com todos os cliente Premium.
	public function MontaTabelaComClientesPremium() {
		
		// Pega o codigo do cliente.
		$id	= (isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '');
		
		$tagTr = "";		
		
		$clientePremium = new ClientePremium();
		
		$pagamentos = $clientePremium->PegaDadosPagamentoPremium($id);
		
		// Verifica se existe lista de premium.
		if($pagamentos) {			
			foreach($pagamentos as $val) {
				
				$data = !empty($val['data']) ? date('d/m/Y', strtotime($val['data'])) : '';
				$dataPagto = !empty($val['data_pagamento']) ? date('d/m/Y', strtotime($val['data_pagamento'])) : '';
				
				$tipo = $val['tipo'] == 'ComplementarPremium' ? 'Complementar' : $val['tipo'];
				
				$tagTr .= "<tr>"
					."	<td class='td_calendario'>".$data."</td> "
					."	<td class='td_calendario'>".$dataPagto."</td> "
					."	<td class='td_calendario' align='left'>".substr($val['assinante'],0,25)."</td>"
					."	<td class='td_calendario' align='center'>R$ ".number_format($val['valor_total'],2,',','.')."</td>"
					."	<td class='td_calendario' align='center'>R$ ".number_format($val['valor_liquido'],2,',','.')."</td>"
					."	<td class='td_calendario' align='center'>".$val['tipo_cobranca']."</td>"
					."	<td class='td_calendario' align='center'>".$tipo."</td>"
					."	<td class='td_calendario' align='center'>"
					."		<form id='formAlterar_".$val['cobrancaContadorId']."' method='post' action='/admin/altera_contador_pagamento.php?id=".$id."'>"
					.$this->MontaSelectComContador($val['contadorId'])
					."			<input type='hidden' name='cobrancaContadorId' value='".$val['cobrancaContadorId']."'>"
					."		</form>"
					."	</td>"
					."	<td class='td_calendario' align='center'><button type='buttom' class='btAlterar' data-ref='#formAlterar_".$val['cobrancaContadorId']."'>Alterar</button></td>"
					."</tr>	";	
			}
		}
		
		// cabeçalho da tabela.	
		$tagTable = " <table style='width: 100%;' cellpadding='5'> "
					."		<tr> "
					."        	<th align='center'>Data Venda</th> "
					."        	<th align='center'>Data Pagto</th> "
					."        	<th align='left'>Assinante</th> "
					."        	<th align='center'>Valor Total</th> "
					."        	<th align='center'>Valor Líquido</th> "
					."        	<th align='center'>Forma Pagto</th> "
					."        	<th align='center'>Tipo</th> "			
					."        	<th align='center'>Contador</th> "
					."        	<th align='center'>Ação</th> "
					."		</tr> "
					.$tagTr
					."	</table>";	
		
		// Retorna a tabela.
		return $tagTable;
	}
	
	public function QuantidadePremium(){

		$clientePremium = new ClientePremium();
		
		return "<b>Quantidade de Premium: </b>".$clientePremium->PegaQuantidadeClientesPremium();
	}
	
	private function MontaSelectComContador($contadorId){
		
		$option = '';
			
		$clientePremium = new ClientePremium();
		
		$listaContador = $clientePremium->pegaListaComDadosContador();
		
		if($listaContador){
			foreach($listaContador as $var){
				
				// Monta um array quebrando pelo espaço.
				$name = explode(' ', $var['nome']);
				
				// Pega so os dois Primeiros nome.
				$nomeContador = $name[0].' '.$name[1];
				
				if($contadorId == $var['id']){
					$option .= "<option value='".$var['id']."' selected>".$nomeContador."</option>";
				} else {
					$option .= "<option value='".$var['id']."'>".$nomeContador."</option>";
				}
			}
		}
		
		$select = " <select name='contadorId'> "
				." <option value=''></option>"
				.$option
				."</select>";
		
		return $select;
	}
}