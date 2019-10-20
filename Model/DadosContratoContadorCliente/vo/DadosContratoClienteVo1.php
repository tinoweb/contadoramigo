<?php

class DadosContratoClienteVo {

	private $Id;
	private $Assinante;
	private $Preftelefone;
 	private $telefone;
	private $Documento;
	private $Sacado;
	private $Endereco;
	private $Numero;
	private $Complemento;
	private $Bairro;
	private $CEP;
	private $Cidade;
	private $UF;
	private $Tipo;
	private $Nome;
	private $Email;

	public function getId() {
		return $this->Id;
	}
	public function setId($id) {
		$this->Id = $id;
	}
	public function getAssinante() {
		return $this->Assinante;	
	}
	public function setAssinante($assinante) {
		$this->Assinante = $assinante;	
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
	public function getDocumento() {
		return $this->Documento;
	}
	public function setDocumento($documento) {
		$this->Documento= $documento;
	}	
	public function getSacado() {
		return $this->Sacado;
	}
	public function setSacado($sacado) {
		$this->Sacado = $sacado;	
	}
	public function getEndereco() {
		return $this->Endereco;
	}
	public function setEndereco($endereco) {
		$this->Endereco = $endereco;	
	}	
	public function getNumero() {
		return $this->Numero;
	}
	public function setNumero($numero) {
		$this->Numero = $numero;
	}
	public function getComplemento() {
		return $this->Complemento;
	}
	public function setComplemento($complemento) {
		$this->Complemento = $complemento;
	}
	public function getBairro() {
		return $this->Bairro;
	}
	public function setBairro($bairro) {
		$this->Bairro = $bairro;
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
	public function getUF() {
		return $this->UF;
	}
	public function setUF($estado) {
		$this->UF = $estado;
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
	public function getEmail() {
		return $this->Email;	
	}
	public function setEmail($email) {
		$this->Email = $email;
	}	
}