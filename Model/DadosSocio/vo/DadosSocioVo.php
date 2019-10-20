<?php
/**
 * Autor: Átano de Farias Jacinto.
 * Data: 04/08/2017
 */
class DadosSocioVo {

	private $SocioId;
	public function getSocioId() {
		return $this->SocioId;	
	}
	public function setSocioId($val) {
		$this->SocioId = $val;
	}
		
	private $Id;
	public function getId() {
		return $this->Id;	
	}
	public function setId($val) {
		$this->Id = $val;
	}
	
	private $DataAdmissao;
	public function getDataAdmissao() {
		return $this->DataAdmissao;	
	}
	public function setDataAdmissao($val) {
		$this->DataAdmissao = $val;
	}
		
	private $Responsavel;
	public function getResponsavel() {
		return $this->Responsavel;	
	}
	public function setResponsavel($val) {
		$this->Responsavel = $val;
	}
	
	private $Nome;
	public function getNome() {
		return $this->Nome;	
	}
	public function setNome($val) {
		$this->Nome = $val;
	}
	
	private $Sexo;
	public function getSexo() {
		return $this->Sexo;	
	}
	public function setSexo($val) {
		$this->Sexo = $val;
	}
	
	private $Nacionalidade;
	public function getNacionalidade() {
		return $this->Nacionalidade;	
	}
	public function setNacionalidade($val) {
		$this->Nacionalidade = $val;
	}
	
	private $Naturalidade;
	public function getNaturalidade() {
		return $this->Naturalidade;	
	}
	public function setNaturalidade($val) {
		$this->Naturalidade = $val;
	}
	
	private $EstadoCivil;
	public function getEstadoCivil() {
		return $this->EstadoCivil;	
	}
	public function setEstadoCivil($val) {
		$this->EstadoCivil = $val;
	}
	
	private $Profissao;
	public function getProfissao() {
		return $this->Profissao;	
	}
	public function setProfissao($val) {
		$this->Profissao = $val;
	}
	
	private $CPF;
	public function getCPF() {
		return $this->CPF;	
	}
	public function setCPF($val) {
		$this->CPF = $val;
	}
	
	private $RG;
	public function getRG() {
		return $this->RG;	
	}
	public function setRG($val) {
		$this->RG = $val;
	}
	
	private $RNE;
	public function getRNE() {
		return $this->RNE;	
	}
	public function setRNE($val) {
		$this->RNE = $val;
	}
	
	private $DataEmissao;
	public function getDataEmissao() {
		return $this->DataEmissao;	
	}
	public function setDataEmissao($val) {
		$this->DataEmissao = $val;
	}
	
	private $OrgaoExpeditor;
	public function getOrgaoExpeditor() {
		return $this->OrgaoExpeditor;	
	}
	public function setOrgaoExpeditor($val) {
		$this->OrgaoExpeditor = $val;
	}
	
	private $DataNascimento;
	public function getDataNascimento() {
		return $this->DataNascimento;	
	}
	public function setDataNascimento($val) {
		$this->DataNascimento = $val;
	}
	
	private $Endereco;
	public function getEndereco() {
		return $this->Endereco;	
	}
	public function setEndereco($val) {
		$this->Endereco = $val;
	}
	
	private $Bairro;
	public function getBairro() {
		return $this->Bairro;	
	}
	public function setBairro($val) {
		$this->Bairro = $val;
	}
	
	private $CEP;
	public function getCEP() {
		return $this->CEP;	
	}
	public function setCEP($val) {
		$this->CEP = $val;
	}
	
	private $Cidade;
	public function getCidade() {
		return $this->Cidade;	
	}
	public function setCidade($val) {
		$this->Cidade = $val;
	}
	
	private $Estado;
	public function getEstado() {
		return $this->Estado;	
	}
	public function setEstado($val) {
		$this->Estado = $val;
	}
	
	private $PrefTelefone;
	public function getPrefTelefone() {
		return $this->PrefTelefone;	
	}
	public function setPrefTelefone($val) {
		$this->PrefTelefone = $val;
	}
	
	private $Telefone;
	public function getTelefone() {
		return $this->Telefone;	
	}
	public function setTelefone($val) {
		$this->Telefone = $val;
	}
	
	private $EnderecoEmpresa;
	public function getEnderecoEmpresa() {
		return $this->EnderecoEmpresa;	
	}
	public function setEnderecoEmpresa($val) {
		$this->EnderecoEmpresa = $val;
	}
	
	private $CodigoCBO;
	public function getCodigoCBO() {
		return $this->CodigoCBO;	
	}
	public function setCodigoCBO($val) {
		$this->CodigoCBO = $val;
	}
	
	private $Funcao;
	public function getFuncao() {
		return $this->Funcao;	
	}
	public function setFuncao($val) {
		$this->Funcao = $val;
	}
	
	private $RetiraProLabore;
	public function getRetiraProLabore() {
		return $this->RetiraProLabore;	
	}
	public function setRetiraProLabore($val) {
		$this->RetiraProLabore = $val;
	}	
	
	private $ProLabore;
	public function getProLabore() {
		return $this->ProLabore;	
	}
	public function setProLabore($val) {
		$this->ProLabore = $val;
	}
	
	private $Nit;
	public function getNit() {
		return $this->Nit;	
	}
	public function setNit($val) {
		$this->Nit = $val;
	}
	
	private $Dependentes;
	public function getDependentes() {
		return $this->Dependentes;	
	}
	public function setDependentes($val) {
		$this->Dependentes = $val;
	}
	
	private $Pensao;
	public function getPensao() {
		return $this->Pensao;	
	}
	public function setPensao($val) {
		$this->Pensao = $val;
	}
	
	private $PercPensao;
	public function getPercPensao() {
		return $this->PercPensao;	
	}
	public function setPercPensao($val) {
		$this->PercPensao = $val;
	}
	
	private $Status;
	public function getStatus() {
		return $this->Status;	
	}
	public function setStatus($val) {
		$this->Status = $val;
	}
	
	private $IdBanco;
	public function getIdBanco() {
		return $this->IdBanco;	
	}
	public function setIdBanco($val) {
		$this->IdBanco = $val;
	}
	
	private $TipoConta;
	public function getTipoConta() {
		return $this->TipoConta;	
	}
	public function setTipoConta($val) {
		$this->TipoConta = $val;
	}
	
	private $Agencia;
	public function getAgencia() {
		return $this->Agencia;	
	}
	public function setAgencia($val) {
		$this->Agencia = $val;
	}	
	
	private $DigAgencia;
	public function getDigAgencia() {
		return $this->DigAgencia;	
	}
	public function setDigAgencia($val) {
		$this->DigAgencia = $val;
	}	
	
	private $Conta;
	public function getConta() {
		return $this->Conta;	
	}
	public function setConta($val) {
		$this->Conta = $val;
	}	
	
	private $DigConta;
	public function getDigConta() {
		return $this->DigConta;	
	}
	public function setDigConta($val) {
		$this->DigConta = $val;
	}
	
	private $Tipo;
	public function getTipo() {
		return $this->Tipo;	
	}
	public function setTipo($val) {
		$this->Tipo = $val;
	}
}