<?php

class LoginVo {

	private $Id;
	private $Nome;
	private $Assinante;
	private $Email;
	private $Senha;
	private $Status;
	private $InfoPreliminar;
	private $DataInclusao;
	private $IdPlano;
	private $SessionID;
	private $IdUsuarioPai;

	public function getId() {
		return $this->Id;
	}
	public function setId($id) {
		$this->Id = $id;
	}
	public function getNome() {
		return $this->Nome;
	}
	public function setNome($nome) {
		$this->Nome = $nome;	
	}
	public function getAssinante() {
		return $this->Assinante;	
	}
	public function setAssinante($assinante) {
		$this->Assinante = $assinante;	
	}
	public function getEmail() {
		return $this->Email;	
	}
	public function setEmail($email) {
		$this->Email = $email;
	}
	public function getSenha() {
		return $this->Senha;	
	}
	public function setSenha($senha) {
		$this->Senha = $senha;
	}
	public function getStatus() {
		return $this->Status;
	}
	public function setStatus($status) {
		$this->Status = $status;	
	}	
	public function getInfoPreliminar() {
		return $this->InfoPreliminar;
	}
	public function setInfoPreliminar($infoPreliminar) {
		$this->InfoPreliminar = $infoPreliminar;
	}	
	public function getDataInclusao() {
		return $this->DataInclusao;	
	}
	public function setDataInclusao($dataInclusao) {
		$this->DataInclusao = $dataInclusao;	
	}
	public function getIdPlano() {
		return $this->IdPlano;
	}
	public function setIdPlano($idPlano) {
		$this->IdPlano = $idPlano;	
	}	
	public function getSessionID() {
		return $this->SessionID;
	}
	public function setSessionID($sessionId) {
		$this->SessionID = $sessionId;
	}
	public function getIdUsuarioPai() {
		return $this->IdUsuarioPai;
	}
	public function setIdUsuarioPai($idUsuarioPai) {
		$this->IdUsuarioPai = $idUsuarioPai;
	}		
}