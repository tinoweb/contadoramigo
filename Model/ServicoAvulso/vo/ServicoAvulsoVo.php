<?php
/**
 * Classe Para realizar o encapsulamento
 * autor: Atano de Farias Jacinto
 * Date: 21/02/2017
 */
class ServicoAvulsoVo {

	private $Id;
	private $IdUser;
	private $ContadorId;
	private $ServicoName;
	private $Data;
	private $Valor;
	private $Status;
	private $UserName;
	private $CobrancaContadorId;
	private $LoginStatus;
	private $Email;
	private $PrefTelefone;
	private $Telefone;
	private $Observacao;
	private $StatusBola;
	 
	public function getId() {
		return $this->Id;
	}
	public function setId($id) {
		$this->Id = $id;		
	}	 
	public function getIdUser() {
		return $this->IdUser;
	}
	public function setIdUser($idUser) {
		$this->IdUser = $idUser;		
	}
	public function getUserName() {
		return $this->UserName;
	}
	public function setUserName($userName) {
		$this->UserName = $userName;		
	}
	public function getContadorId() {
		return $this->ContadorId;
	}
	public function setContadorId($contadorId) {
		$this->ContadorId = $contadorId;		
	}	 
	public function getServicoName() {
		return $this->ServicoName;
	}	
	public function setServicoName($servicoName) {
		$this->ServicoName = $servicoName;		
	}
	public function getData() {
		return $this->Data;
	}
	public function setData($data) {
		$this->Data = $data;		
	}
	public function getValor() {
		return $this->Valor;
	}
	public function setValor($valor) {
		$this->Valor = $valor;		
	}
	public function getStatus() {
		return $this->Status;
	}
	public function setStatus($status) {
		$this->Status = $status;		
	}
	public function getLoginStatus() {
		return $this->LoginStatus;
	}
	public function setLoginStatus($loginStatus) {
		$this->LoginStatus = $loginStatus;		
	}	
	public function getCobrancaContadorId() {
		return $this->CobrancaContadorId;
	}
	public function setCobrancaContadorId($cobrancaContadorId) {
		$this->CobrancaContadorId = $cobrancaContadorId;	
	}
	public function getEmail() {
		return $this->Email;
	}
	public function setEmail($email) {
		$this->Email = $email;	
	}	
	public function getPrefTelefone() {
		return $this->PrefTelefone;
	}
	public function setPrefTelefone($prefTelefone) {
		$this->PrefTelefone = $prefTelefone;	
	}	
	public function getTelefone() {
		return $this->Telefone;
	}
	public function setTelefone($telefone) {
		$this->Telefone = $telefone;	
	}
	public function getObservacao(){
		return $this->Observacao;
	}
	public function setObservacao($observacao){
		$this->Observacao = $observacao;
	}
	public function getStatusBola(){
		return $this->StatusBola;
	}
	public function setStatusBola($statusBola){
		$this->StatusBola = $statusBola;
	}
}