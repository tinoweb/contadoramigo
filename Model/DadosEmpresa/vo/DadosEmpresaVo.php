<?php

class DadosEmpresaVo {

	private $Id;
	private $RazaoSocial;
	private $NomeFantasia;
	private $CNPJ;
	private $InscricaoNoCCM;
	private $InscricaoEstadual;
	private $TipoEndereco;
	private $Endereco;
	private $Numero;
	private $Complemento;
	private $Bairro;
	private $CEP;
	private $Cidade;
	private $Estado;
	private $PrefTelefone;
	private $Telefone;
	private $RamoAtividade;
	private $CodigoAtividadePrefeitura;
	private $RegimeTributacao;
	private $InscritaComo;
	private $RegistroNire;
	private $NumeroCartorio;
	private $RegistroCartorio;
	private $DataCriacao;
	private $Ativa;
	private $DataDesativacao;


	public function getId() {
		return $this->Id;
	}
	public function setId($id) {
		$this->Id = $id;
	}
	public function getRazaoSocial() {
		return $this->Nome;
	}
	public function setRazaoSocial($nome) {
		$this->Nome = $nome;	
	}
	public function getNomeFantasia() {
		return $this->NomeFantasia;	
	}
	public function setNomeFantasia($nomeFantasia) {
		$this->NomeFantasia = $nomeFantasia;	
	}
	public function getCNPJ() {
		return $this->CNPJ;	
	}
	public function setCNPJ($cnpj) {
		$this->CNPJ = $cnpj;
	}
	public function getInscricaoNoCCM() {
		return $this->InscricaoNoCCM;	
	}
	public function setInscricaoNoCCM($inscricaoNoCCM) {
		$this->InscricaoNoCCM = $inscricaoNoCCM;
	}
	public function getInscricaoEstadual() {
		return $this->InscricaoEstadual;
	}
	public function setInscricaoEstadual($inscricaoEstadual) {
		$this->InscricaoEstadual = $inscricaoEstadual;	
	}	
	public function getInfoPreliminar() {
		return $this->InfoPreliminar;
	}
	public function setInfoPreliminar($infoPreliminar) {
		$this->InfoPreliminar = $infoPreliminar;
	}	
	public function getTipoEndereco() {
		return $this->TipoEndereco;	
	}
	public function setTipoEndereco($tipoEndereco) {
		$this->TipoEndereco = $tipoEndereco;	
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
	public function getEstado() {
		return $this->Estado;
	}
	public function setEstado($estado) {
		$this->Estado = $estado;
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
	public function getRamoAtividade() {
		return $this->RamoAtividade;
	}
	public function setRamoAtividade($ramoAtividade) {
		$this->RamoAtividade = $ramoAtividade;
	}
	public function getCodigoAtividadePrefeitura() {
		return $this->CodigoAtividadePrefeitura;
	}
	public function setCodigoAtividadePrefeitura($codigoAtividadePrefeitura) {
		$this->CodigoAtividadePrefeitura = $codigoAtividadePrefeitura;
	}
	public function getRegimeTributacao() {
		return $this->RegimeTributacao;
	}
	public function setRegimeTributacao($regimeTributacao) {
		$this->RegimeTributacao = $regimeTributacao;
	}
	public function getInscritaComo() {
		return $this->InscritaComo;
	}
	public function setInscritaComo($inscritaComo) {
		$this->InscritaComo = $inscritaComo;
	}
	public function getRegistroNire() {
		return $this->RegistroNire;
	}
	public function setRegistroNire($registroNire) {
		$this->RegistroNire = $registroNire;
	}
	public function getNumeroCartorio() {
		return $this->NumeroCartorio;
	}
	public function setNumeroCartorio($numeroCartorio) {
		$this->NumeroCartorio = $numeroCartorio;
	}
	public function getRegistroCartorio() {
		return $this->RegistroCartorio;
	}
	public function setRegistroCartorio($registroCartorio) {
		$this->RegistroCartorio = $registroCartorio;
	}
	public function getDataCriacao() {
		return $this->DataCriacao;
	}
	public function setDataCriacao($dataCriacao) {
		$this->DataCriacao = $dataCriacao;
	}
	public function getAtiva() {
		return $this->Ativa;
	}
	public function setAtiva($ativa) {
		$this->Ativa = $ativa;
	}
	public function getDataDesativacao() {
		return $this->DataDesativacao;
	}
	public function setDataDesativacao($dataDesativacao) {
		$this->DataDesativacao = $dataDesativacao;
	}
	
}