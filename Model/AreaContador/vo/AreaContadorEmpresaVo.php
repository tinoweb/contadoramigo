<?php

class AreaContadorEmpresaVo {
	/**
	 * Classe Para realizar o encapsulamento
	 * autor: Atano de Farias Jacinto
	 * Date: 21/02/2017
	 */
	private $EmpresaId;
	private $RazaoSocial; 
	private $Doc;
	private $NomeFantasia; 
	private $Ativa;
	private $DataDesativacao;
	private $DataInclusao;
	private $QuantidadeItemLista;
	
	public function setEmpresaId($empresaId) {
		$this->EmpresaId = $empresaId;
	}
	
	public function getEmpresaId() {
		return $this->EmpresaId;
	} 	
	
	public function setNome($nome) {
		$this->Nome = $nome;		
	}
	
	public function getNome() {
		return $this->Nome;
	}

	public function setRazaoSocial($razaoSocial) {
		$this->RazaoSocial = $razaoSocial;	
	}
	
	public function getRazaoSocial() {
		return $this->RazaoSocial;	
	}
	
	public function setDoc($doc) {
		$this->Doc = $doc;
	}
	
	public function getDoc() {
		return $this->Doc;	
	}
	
	public function setNomeFantasia($nomeFantasia) {
		$this->NomeFantasia = $nomeFantasia;		
	}
	
	public function getNomeFantasia() {
		return $this->NomeFantasia;	
	}
	
	public function setAtivo($ativo) {
		$this->Ativo = $ativo;		
	}
	
	public function getAtivo() {
		return $this->Ativo;	
	}
	
	public function setDataDesativacao($dataDesativacao) {
		$this->DataDesativacao = $dataDesativacao;		
	}
	
	public function getDataInclusao() {
		return $this->DataInclusao;	
	}
	
	public function setQuantidadeItemLista($quantidadeItemLista) {
		$this->QuantidadeItemLista = $quantidadeItemLista;		
	}
	
	public function getQuantidadeItemLista() {
		return $this->QuantidadeItemLista;	
	}
}