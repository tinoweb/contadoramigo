<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */	
require_once('../DataBaseMySQL/ServicosAvulso.php');

class AlteraContadorServicos{
	
	function __construct() {
       
		// Verifica se foi passado via POST os dados para atualizar os dados.
		if( isset($_POST['servicoId']) && !empty($_POST['servicoId']) && isset($_POST['cobrancaContadorId']) && !empty($_POST['cobrancaContadorId'])) {
			
			$servicoId = $_POST['servicoId'];
			$cobrancaContadorId = $_POST['cobrancaContadorId'];
			$contadorId = $_POST['contadorId'];
						
			$servicosAvulso = new ServicosAvulso();
			
			// Realiza a alteração do contador respônsavel pelo servio.
			$servicosAvulso->AtualizaContadorServico($servicoId, $contadorId);
		
			// Realiza a alteração do contador no pagamento do serviço.
			$servicosAvulso->AtualizaContadorPagamentoServico($cobrancaContadorId, $contadorId);
		}
   	}
	
	// Monta a tabela com todos os serviço.
	public function MontaTabelaComServicos() {
		
		$tagTr = "";		
		
		$servicosAvulso = new ServicosAvulso();
		
		$listaServicos = $servicosAvulso->PegaTodasServicosEClientes();
		
		// Verifica se existe lista de serviço.
		if($listaServicos) {
			
			foreach($listaServicos as $val) {
				
				$tagTr .= "<tr>"
					."	<td class='td_calendario'>".$val['id_user']."</td> "
					."	<td class='td_calendario' align='left'>"
					."		<form action='realiza_acesso_cliente.php' method='post' target='_blank' style='margin-bottom: 0px;' >"	
					."			<input type='hidden' name='clienteid' value='".$val['id_user']."' /> "
					."			<input type='hidden' name='sessionId' value='".$val['contadorId']."' /> "	
					."			<button class='gridButton' type='submit' style='color: #3D6D9E; text-decoration: underline; background:none;'>".$val['assinante']."</button>"
					."		</form> "
					."  </td>"
					."	<td class='td_calendario' align='center'>".$val['servico_name']."</td>" 
					."	<td class='td_calendario' align='center'>".date('d/m/Y', strtotime($val['data']))."</td>" 
					."	<td class='td_calendario' align='center'>".$val['uf']."</td>"
					."	<td class='td_calendario' align='center'>"
					."		<form id='formContador_".$val['id']."' method='post' action=''>"
					.$this->MontaSelectComContador($val['contadorId'])
					."			<input type='hidden' name='servicoId' value='".$val['id']."'>"
					."			<input type='hidden' name='cobrancaContadorId' value='".$val['cobrancaContadorId']."'>"
					."		</form>"
					."	</td>"
					."	<td class='td_calendario' align='center'><button type='button' style='cursor:pointer;' class='btAlterar' data-ref='#formContador_".$val['id']."'>Alterar</button></td>"
					."</tr>	";	
			}
		}
		
		// cabeçalho da tabela.	
		$tagTable = " <table style='width: 100%;' cellpadding='5'> "
					."		<tr> "	
					."        	<th align='center'>Id</th> "
					."        	<th align='left'>Assinante</th> "
					."        	<th align='center'>Serviço</th> "
					."        	<th align='center'>Data</th> "
					."        	<th align='center'>Estado</th> "
					."        	<th align='center'>Contador</th> "
					."        	<th align='center'>Ação</th> "
					."		</tr> "
					.$tagTr
					." </table>";	
		
		// Retorna a tabela.
		return $tagTable;
	}
	
	public function QuantidadeServicos(){

		$servicosAvulso = new ServicosAvulso();
		
		return "<b>Serviços em aberto: </b>".$servicosAvulso->PegaQuantidadeServicos();
	}
	
	private function MontaSelectComContador($contadorId){
		
		$option = '';
			
		$servicosAvulso = new ServicosAvulso();
		
		$listaContador = $servicosAvulso->pegaListaComDadosContador();
		
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
			."<option value=''></option>"
			.$option
			."</select>";
		
		return $select;
	}
}