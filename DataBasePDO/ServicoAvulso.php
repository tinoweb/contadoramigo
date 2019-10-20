<?php
/**
 * Autor: Átano de Farias
 * Data: 03/05/2017 
 */	
require_once('../conect.PDO.php');

class ServicoAvulso extends AccessDB {
	
	// Pega os servicos avulso.
	public function ListaServicoAvulso($contadorId, $idUser = 0, $data1 = '', $data2 = '', $assinante = '', $status = '') {

		$qry = "SELECT sa.id 
					,sa.id_user 
					,dc.assinante
					,pref_telefone
					,telefone
					,sa.contadorId 
					,sa.servico_name 
					,sa.data 
					,sa.valor
					,sa.observacao
					,sa.status
					,sa.status_bola
					,l.status as loginStatus
					,l.email
					,sa.cobrancaContadorId 
					,sa.tipoPagamento 
					FROM servico_avulso sa
					JOIN dados_cobranca dc ON dc.id = sa.id_user 
					JOIN login l ON l.id = dc.id
					JOIN cobranca_contador cc ON cc.cobrancaContadorId = sa.cobrancaContadorId
					JOIN relatorio_cobranca rc ON rc.idRelatorio = cc.idRelatorio
					WHERE sa.contadorId = ".$contadorId."
					AND (sa.tipoPagamento = 'cartao' OR sa.tipoPagamento = 'boleto' AND rc.data_pagamento IS NOT NULL)";
			
				
		// Verifica se o filtro e por data
		if($idUser) {
			$qry .= ' AND sa.id_user = '.$idUser;
		}
		// Verifica se o filtro e por usuário.
		if($data1) {
			$qry .= ' AND sa.data > "'.$data1.'"';
		}
		// Verifica se a data final foi informada
		if($data2) {
			$qry .= ' AND sa.data < "'.$data2.'"';
		}
		// Verifica se o status foi definido
		if(empty($status)) {
			$qry .= ' AND sa.status = "Em Aberto"';				
		} elseif($status != "todos") {
			$qry .= ' AND sa.status = "'.$status.'"';
		}
		// Verifica se o assinante foi definido
		if($assinante) {
			$qry .= ' AND dc.assinante LIKE "%'.$assinante.'%" ';	
		}
		$qry .= ' ORDER BY data DESC LIMIT 500; '; 
		
		$query = $this->PDO->prepare($qry);
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC); 
		 
	}
	
	// Realiza a atualização do status.
	public function atualizaStatus($id, $status) {
	
		$query = $this->PDO->prepare("UPDATE servico_avulso SET status = '".$status."' WHERE id = ".$id );
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
	
	// Realiza a atualizacao da observação
	public function atualizaObservacao($id, $observacao){
		
		$query = $this->PDO->prepare("UPDATE servico_avulso SET observacao = '".$observacao."' WHERE id = ".$id);
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
	
	// Realiza a atauliazação do status Bola
	public function atualizaStatusBola($id, $statusBola){
		
		$query = $this->PDO->prepare("UPDATE servico_avulso SET status_bola = '".$statusBola."' WHERE id = ".$id);
				
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}

	// Pega Todos usuarios.
	public function PegaNomeAssinante($id) {
		
		$query = $this->PDO->prepare('SELECT assinante FROM dados_cobranca WHERE id = :id ;');
		
		$params = array('id' => $id);
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
		 
	}
		
}