<?php

class CobrancaContadorVo {
	
	private $CobrancaContadorId;
	private $IdRelatorio;
	private $ResultadoAcao;
	private $ValorPago;
	private $DataPagamento;
	private $TipoPlano;
	private $Plano;
	private $EmissaoNF;
	private $UsurId;
	private $Assinante;
	private $Documento;
	private $Tipo;
	private $ServicoName;
	private $ValorStatus;
	private $ValorTotal;
	private $ValorLiquido;
	private $LinkNFE;
	private $StatusServico;
	private $TipoLancamento;

	public function getLinkNFE() {
		return $this->LinkNFE;	
	}

	public function setLinkNFE($linkNFE) {
		$this->LinkNFE = $linkNFE;
	}
	
	public function getCobrancaContadorId() {
		return $this->CobrancaContadorId;
	}
	public function setCobrancaContadorId($cobrancaContadorId) {
		$this->CobrancaContadorId = $cobrancaContadorId;
	}
	public function getIdRelatorio() {
		return $this->IdRelatorio;
	}
	public function setIdRelatorio($idRelatorio) {
		$this->IdRelatorio = $idRelatorio;
	}
	public function getResultadoAcao() {
		return $this->ResultadoAcao;
	}
	public function setResultadoAcao ($resultadoAcao) {
		$this->ResultadoAcao = $resultadoAcao;
	}
	public function getValorPago() {
		return $this->ValorPago;
	}
	public function setValorPago($valorPago) {
		$this->ValorPago = $valorPago;
	}
	public function getValorTotal() {
		return $this->ValorTotal;
	}
	public function setValorTotal($valorTotal) {
		$this->ValorTotal = $valorTotal;
	}	
	public function getValorLiquido() {
		return $this->ValorLiquido;
	}
	public function setValorLiquido($valorLiquido) {
		$this->ValorLiquido = $valorLiquido;
	}	
	public function getDataPagamento() {
		return $this->DataPagamento ;
	}
	public function setDataPagamento($dataPagamento) {
		$this->DataPagamento = $dataPagamento;
	}
	public function getTipoPlano() {
		return $this->TipoPlano;
	}
	public function setTipoPlano ($tipoPlano) {
		$this->TipoPlano = $tipoPlano;
	}
	public function getPlano() {
		return $this->Plano;
	}
	public function setPlano ($plano) {
		$this->Plano = $plano;
	}
	public function getEmissaoNF() {
		return $this->EmissaoNF;
	}
	public function setEmissaoNF($emissaoNF) {
		$this->EmissaoNF = $emissaoNF;
	}
	public function getUsurId() {
		return $this->UsurId;
	}
	public function setUsurId($usurid) {
		$this->UsurId = $usurid;
	}
	public function getAssinante() {
		return $this->Assinante;
	}
	public function setAssinante ($assinante) {
		$this->Assinante = $assinante;
	}		
	public function getDocumento() {
		return $this->Documento;
	}
	public function setDocumento ($documento) {
		$this->Documento = $documento;
	}
	public function getTipo() {
		return $this->Tipo;
	}
	public function setTipo ($tipo) {
		$this->Tipo = $tipo;
	}
	public function getServicoName() {
		return $this->ServicoName;
	}
	public function setServicoName ($servicoName) {
		$this->ServicoName = $servicoName;
	}
	public function getValorStatus() {
		return $this->ValorStatus;
	}
	public function setValorStatus($valorStatus) {
		$this->ValorStatus = $valorStatus;
	}
	public function getStatusServico() {
		return $this->StatusServico;
	}
	public function setStatusServico($statusServico) {
		$this->StatusServico = $statusServico;
	}	
	public function getTipoLancamento() {
		return $this->TipoLancamento;
	}
	public function setTipoLancamento($tipoLancamento) {
		$this->TipoLancamento = $tipoLancamento;
	}
}