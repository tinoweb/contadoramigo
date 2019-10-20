<?php

class DadosContadorBalancoVo {
	
	private	$Id;
	private	$Nome;
	private	$CRC;
	private	$Endereco;
	private	$Cidade;
	private	$Estado;
	private	$CEP;
	private	$Documento;
	private	$Documento2;
	private	$UF;
	private	$UserId;
	private	$Bairro;
	private	$Ativo;
	private	$TipoDoc;
	private	$Sexo;
	
	public function getId(){
		return $this->Id;  
	}
	
	public function setId($id){
		$this->Id = $id;
	}

	public function getNome(){
		return $this->Nome;  
	}
	
	public function setNome($nome){
		$this->Nome = $nome;
	}
	
	public function gatCRC(){
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
	
	public function getCEP() {
		return $this->CEP;
	}
	
	public function setCEP($cep) {
		$this->CEP = $cep;
	}	
	
	
	public function getCidade() {
		return $this->Cidade;
	}
	
	public function setCidade($cidade) {
		$this->Cidade = $cidade;
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
	
	public function getUF(){
		return $this->UF;  
	}
	
	public function setUF($uf){
		$this->UF = $uf;
	}
	
	public function getUserId(){
		return $this->UserId;  
	}
	
	public function setUserId($userId){
		$this->UserId = $userId;
	}
	
	public function getBairro(){
		return $this->Bairro;  
	}
	
	public function setBairro($bairro){
		$this->Bairro = $bairro;
	}
	
	public function getAtivo(){
		return $this->Ativo;  
	}
	
	public function setAtivo($ativo){
		$this->Ativo = $ativo;
	}
	
	public function getTipoDoc() {
		return $this->TipoDoc;	
	}
	
	public function setTipoDoc($tipoDoc) {
		$this->TipoDoc = $tipoDoc;	
	}
	
	public function getSexo() {
		return $this->Sexo;	
	}
	
	public function setSexo($sexo) {
		$this->Sexo = $sexo;	
	}			
}