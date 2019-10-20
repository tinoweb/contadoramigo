<?php

class DadosContratoContadorVo {

	private $Id;
	private $Tipo;
	private $Nome;
	private $CRC;
	private $Endereco;
	private $Bairro;
	private $Cidade;
	private $UF;
	private $CEP;
	private $Estado;
	private $IdUser;
	private $TipoDoc;
	private $Documento;
	private $Documento2;
	private $UserId;
	private $Sex;

	public function getId() {
		return $this->Id;
	}
	public function setId($id) {
		$this->Id = $id;
	}
	public function getTipo() {
		return $this->Tipo;
	}
	public function setTipo($tipo) {
		$this->Tipo = $tipo;
	}	
	public function getNome() {
		return $this->Nome;
	}
	public function setNome($nome) {
		$this->Nome = $nome;
	}
	public function getCRC() {
		return $this->CRC;
	}
	public function setCRC($crc) {
		$this->CRC = $crc;
	}	
	public function getEndereco() {
		return $this->Endereco;
	}
	public function setEndereco($endereco) {
		$this->Endereco = $endereco;	
	}
	public function getCidade() {
		return $this->Cidade;
	}
	public function getBairro() {
		return $this->Bairro;
	}
	public function setBairro($bairro) {
		$this->Bairro = $bairro;
	}
	public function setCidade($cidade) {
		$this->Cidade = $cidade;
	}
	public function getUF() {
		return $this->UF;
	}
	public function setUF($estado) {
		$this->UF = $estado;
	}
	public function getCEP() {
		return $this->CEP;
	}
	public function setCEP($cep) {
		$this->CEP = $cep;
	}
	public function getEstado() {
		return $this->Estado;
	}
	public function setEstado($estado) {
		$this->Estado = $estado;
	}
	public function getIdUser() {
		return $this->IdUser;
	}
	public function setIdUser($idUser) {
		$this->IdUser = $idUser;
	}
	public function getTipoDoc() {
		return $this->TipoDoc;
	}
	public function setTipoDoc($tipoDoc) {
		$this->TipoDoc= $tipoDoc;
	}	
	public function getDocumento() {
		return $this->Documento;
	}
	public function setDocumento($documento) {
		$this->Documento = $documento;
	}
	public function getDocumento2() {
		return $this->Documento2;
	}
	public function setDocumento2($documento2) {
		$this->Documento2 = $documento2;
	}	
	public function getUserId() {
		return $this->UserId;
	}
	public function setUserId($userId) {
		$this->UserId = $userId;
	}
	public function getSex() {
		return $this->Sex;
	}
	public function setSex($sex) {
		$this->Sex= $sex;
	}
}