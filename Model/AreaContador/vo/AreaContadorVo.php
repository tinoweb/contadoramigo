<?php
/**
	 * Classe Para realizar o encapsulamento
	 * autor: Atano de Farias Jacinto
	 * Date: 21/02/2017
	 */
	
class AreaContadorClientesVo {
	
	private $ClienteId;
	private $Nome; 
	private $Assinante; 
	private $Email; 
	private $Status; 
	private $DataInclusao; 
	private $PrefTelefone;
	private $Telefone; 
	private $Documento;
	private $NumeroDeEmpresas; 
	
	public function setClienteId($clienteId) {
		$this->ClienteId = $clienteId;
	}
	
	public function getClienteId() {
		return $this->ClienteId;
	} 	
	
	public function setNome($nome) {
		$this->Nome = $nome;		
	}
	
	public function getNome() {
		return $this->Nome;
	}

	public function setAssinatura($assinatura) {
		$this->Assinatuara = $assinatura;	
	}
	
	public function getAssinatura() {
		return $this->Assinatura;	
	}
	
	public function setEmail($email) {
		$this->Email = $email;
	}
	
	public function getEmail() {
		return $this->Email;	
	}
	
	public function setStatus($status) {
		$this->Status = $status;		
	}
	
	public function getStatus() {
		return $this->Status;	
	}
	
	public function setDataInclusao($dataInclusao) {
		$this->DataInclusao = $dataInclusao;		
	}
	
	public function getDataInclusao() {
		return $this->DataInclusao;	
	}
	
	public function setPrefTelefone($prefTelefone) {
		$this->PrefTelefone = $prefTelefone;		
	}
	
	public function getPrefTelefone() {
		return $this->PrefTelefone;	
	}
	
	public function setTelefone($telefone) {
		$this->Telefone = $telefone;		
	}
	
	public function getTelefone() {
		return $this->Telefone;	
	}
	
	public function setDocumento($documento) {
		$this->Documento = $documento		
	}
	
	public function getDocumento() {
		return $this->Documento;	
	}
	
	public function setNumeroDeEmpresas($numeroDeEmpresa) {
		$this->NumeroDeEmpresas = $numeroDeEmpresa;		
	}
	
	public function getNumeroDeEmpresas() {
		return $this->NumeroDeEmpresa;	
	}	 
}