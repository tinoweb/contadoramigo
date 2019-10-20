<?php 
/**
 * Classe criada para realizar o controle dos dados Pagamento do contador.
 * Autor: Átano de Farias
 * Data: 28/04/2017
 */
class PagamentoContadorVo{
	
	private $PagamentoId;
	private $ContadorId;
	private $DataPagamento;
	private	$Valorpagamento;
	private $IdsCobrancaContador;

	public function getPagamentoId() {
		return $this->PagamentoId;
	}
	
	public function setPagamentoId($pagamentoId) {
		$this->PagamentoId = $pagamentoId;
	}
	
	public function getContadorId() {
		return $this->ContadorId;
	}
	
	public function setContadorId($contadorId) {
		$this->ContadorId= $contadorId;
	}	
	
	public function getdataPagamento() {
		return $this->DataPagamento;
	}
	
	public function setDataPagamento($dataPagamento) {
		$this->DataPagamento = $dataPagamento;
	}
	
	public function getValorpagamento() {
		return $this->Valorpagamento;
	}
	
	public function setValorpagamento($valorpagamento) {
		$this->Valorpagamento = $valorpagamento;
	}
	
	public function getIdsCobrancaContador() {
		return $this->IdsCobrancaContador;
	}
	
	public function setIdsCobrancaContador($idsCobrancaContador) {
		$this->IdsCobrancaContador = $idsCobrancaContador;
	}	
}
