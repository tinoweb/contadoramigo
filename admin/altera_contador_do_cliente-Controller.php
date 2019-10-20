<?php
/**
 * Autor: Átano de Farias
 * Data: 21/02/2017 
 */	
require_once('../DataBaseMySQL/ClientePremium.php');

class AlteraContadorCliente {
	
	function __construct(){
		
		if(isset($_POST['userId']) && !empty($_POST['userId'])){
			
			$userId = $_POST['userId'];
			$contadorId = $_POST['contadorId'];
						
			$clientePremium = new ClientePremium();
			
			// Altera o contador responsável pelo cliente.
			$clientePremium->AtualizaContadorPremium($userId, $contadorId);
		}
	}
	
	// Monta a tabela com todos os cliente Premium.
	public function MontaTabelaComClientesPremium() {
		
		$tagTr = "";		
		
		$clientePremium = new ClientePremium();
		
		$listaPremium = $clientePremium->PegaListaClientesPremium();
		
		// Verifica se existe lista de premium.
		if($listaPremium) {
			
			foreach($listaPremium as $val) {
				
				$tagTr .= "<tr>"
					."	<td class='td_calendario'>".$val['id']."</td> "
					."	<td class='td_calendario' align='left'>"
					."		<form action='realiza_acesso_cliente.php' method='post' target='_blank' style='margin-bottom: 0px;' >"	
					."			<input type='hidden' name='clienteid' value='".$val['id']."' /> "
					."			<input type='hidden' name='sessionId' value='".$val['contadorId']."' /> "	
					."			<button class='gridButton' type='submit' style='color: #3D6D9E; text-decoration: underline; background:none;'>".$val['assinante']."</button>"
					."		</form> "
					."  </td>"
					."	<td class='td_calendario' align='center'>".$val['status']."</td>"
					."	<td class='td_calendario' align='center'>".$val['uf']."</td>"
					."	<td class='td_calendario' align='center'><a href='/admin/altera_contador_pagamento.php?id=".$val['id']."'>ver lista</a></td>" 
					."	<td class='td_calendario' align='center'>"
					."		<form id='formAlterar_".$val['id']."' method='post' action='/admin/altera_contador_do_cliente.php'>"
					.$this->MontaSelectComContador($val['contadorId'])
					."			<input type='hidden' name='userId' value='".$val['id']."'>"
					."		</form>"
					."	</td>"
					."	<td class='td_calendario' align='center'><button type='buttom' class='btAlterar' data-ref='#formAlterar_".$val['id']."'>Alterar</button></td>"
					."</tr>	";	
			}
		}
		
		// cabeçalho da tabela.	
		$tagTable = " <table style='width: 100%;' cellpadding='5'> "
					."		<tr> "	
					."        	<th align='center'>Id</th> "
					."        	<th align='left'>Assinante</th> "
					."        	<th align='center'>status</th> "
					."        	<th align='center'>Estado</th> "
					."        	<th align='center'>Pagamentos</th> "			
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