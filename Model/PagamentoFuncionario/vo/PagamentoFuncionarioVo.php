<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */
class PagamentoFuncionarioVo {
	
	private $PagtoId;
	public function getPagtoId() {
		return $this->PagtoId;
	} 
	public function setPagtoId($pagtoId){
		$this->PagtoId = $pagtoId;
	}
	
	private $EmpresaId;
	public function getEmpresaId() {
		return $this->EmpresaId;
	} 
	public function setEmpresaId($empresaId){
		$this->EmpresaId = $empresaId;
	}	
	
	private $FuncionarioId;
	public function getFuncionarioId() {
		return $this->FuncionarioId;
	} 
	public function setFuncionarioId($funcionarioId){
		$this->FuncionarioId = $funcionarioId;
	}

	private $FuncionarioNome;
	public function getFuncionarioNome() {
		return $this->FuncionarioNome;
	} 
	public function setFuncionarioNome($funcionarioNome){
		$this->FuncionarioNome = $funcionarioNome;
	}	
	
	private $DataPagto;
	public function getDataPagto() {
		return $this->DataPagto;
	} 
	public function setDataPagto($dataPagto){
		$this->DataPagto = $dataPagto;
	}
	
	private $DataReferencia;
	public function getDataReferencia() {
		return $this->DataReferencia;
	} 
	public function setDataReferencia($dataReferencia){
		$this->DataReferencia = $dataReferencia;
	}	
	
	private $DataEmissao;
	public function getDataEmissao() {
		return $this->DataEmissao;
	} 
	public function setDataEmissao($dataEmissao){
		$this->DataEmissao = $dataEmissao;
	}	
	
	private $ValorBruto;
	public function getValorBruto() {
		return $this->ValorBruto;
	} 
	public function setValorBruto($valorBruto){
		$this->ValorBruto = $valorBruto;
	}	
	
	private $ValorSalario;
	public function getValorSalario() {
		return $this->ValorSalario;
	} 
	public function setValorSalario($valorSalario){
		$this->ValorSalario = $valorSalario;
	}
	
	private $ValorFamilia;
	public function getValorFamilia() {
		return $this->ValorFamilia;
	} 
	public function setValorFamilia($valorFamilia){
		$this->ValorFamilia = $valorFamilia;
	} 

	private $ValorMaternidade;
	public function getValorMaternidade() {
		return $this->ValorMaternidade;
	} 
	public function setValorMaternidade($valorMaternidade){
		$this->ValorMaternidade = $valorMaternidade;
	}
	
	private $ValorAbono;
	public function getValorAbono() {
		return $this->ValorAbono;
	} 
	public function setValorAbono($valorAbono){
		$this->ValorAbono = $valorAbono;
	}
	
	private $ValorBonus;
	public function getValorBonus() {
		return $this->ValorBonus;
	} 
	public function setValorBonus($valorBonus){
		$this->ValorBonus = $valorBonus;
	}	
	
	private $ReferenciaINSS;
	public function getReferenciaINSS() {
		return $this->ReferenciaINSS;
	} 
	public function setReferenciaINSS($referenciaINSS){
		$this->ReferenciaINSS = $referenciaINSS;
	}	
	
	private $ValorINSS;
	public function getValorINSS() {
		return $this->ValorINSS;
	} 
	public function setValorINSS($valorINSS){
		$this->ValorINSS = $valorINSS;
	}	
	
	private $ReferenciaIR;
	public function getReferenciaIR() {
		return $this->ReferenciaIR;
	} 
	public function setReferenciaIR($referenciaIR){
		$this->ReferenciaIR = $referenciaIR;
	}	
	
	private $ValorIR;
	public function getValorIR() {
		return $this->ValorIR;
	} 
	public function setValorIR($val){
		$this->ValorIR = $val;
	}	
	
	private $FaixaIR;
	public function getFaixaIR() {
		return $this->FaixaIR;
	} 
	public function setFaixaIR($faixaIR){
		$this->FaixaIR = $faixaIR;
	}	
	private $ReferenciaVR;
	public function getReferenciaVR() {
		return $this->ReferenciaVR;
	} 
	public function setReferenciaVR($referenciaVR){
		$this->ReferenciaVR = $referenciaVR;
	}	
	
	private $ValorVR;
	public function getValorVR() {
		return $this->ValorVR;
	} 
	public function setValorVR($valorVR){
		$this->ValorVR = $valorVR;
	}
	
	private $ReferenciaVT;
	public function getReferenciaVT() {
		return $this->ReferenciaVT;
	} 
	public function setReferenciaVT($referenciaVT){
		$this->ReferenciaVT = $referenciaVT;
	}	
	
	private $ValorVT;
	public function getValorVT() {
		return $this->ValorVT;
	} 
	public function setValorVT($valorVT){
		$this->ValorVT = $valorVT;
	}	
	
	private $Valorliquido;
	public function getValorliquido() {
		return $this->Valorliquido;
	} 
	public function setValorliquido($valorliquido){
		$this->Valorliquido = $valorliquido;
	}
	
	private $Mensagem;
	public function getMensagem() {
		return $this->Mensagem;
	} 
	public function setMensagem($mensagem){
		$this->Mensagem = $mensagem;
	}
	
	private $ValorPensao;
	public function getValorPensao() {
		return $this->ValorPensao;
	} 
	public function setValorPensao($valorPensao){
		$this->ValorPensao = $valorPensao;
	}
		
	private $ReferenciaPensao;
	public function getReferenciaPensao() {
		return $this->ReferenciaPensao;
	} 
	public function setReferenciaPensao($referenciaPensao){
		$this->ReferenciaPensao = $referenciaPensao;
	}
		
	private $NumeroDependentes;
	public function getNumeroDependentes() {
		return $this->NumeroDependentes;
	} 
	public function setNumeroDependentes($numeroDependentes){
		$this->NumeroDependentes = $numeroDependentes;
	}
	
	private $DescontoDepValor;
	public function getDescontoDepValor() {
		return $this->DescontoDepValor;
	} 
	public function setDescontoDepValor($descontoDepValor){
		$this->DescontoDepValor = $descontoDepValor;
	}	
	
	private $DiasTrabalhado;
	public function getDiasTrabalhado(){
		return $this->DiasTrabalhado;	
	}	
	public function setDiasTrabalhado($diasTrabalhado){
		$this->DiasTrabalhado = $diasTrabalhado;	
	}
	
	private $Faltas;
	public function getFaltas(){
		return $this->Faltas;		
	}
	public function setFaltas($faltas){
		$this->Faltas = $faltas;
	}
	
	private $ValorFaltas;
	public function getValorFaltas(){
		return $this->ValorFaltas;		
	}
	public function setValorFaltas($valorFaltas){
		$this->ValorFaltas = $valorFaltas;
	}
	
	private $TipoPagto;
	public function getTipoPagto(){
		return $this->TipoPagto;		
	}
	public function setTipoPagto($tipoPagto){
		$this->TipoPagto = $tipoPagto;
	}
	
	private $ParcelaDecimo;	
	public function getParcelaDecimo(){
		return $this->ParcelaDecimo;
	}
	public function setParcelaDecimo($parcelaDecimo) {
		$this->ParcelaDecimo = $parcelaDecimo;
	}
		
	private $MesesTrabalhado;
	public function getMesesTrabalhado(){
		return $this->MesesTrabalhado;
	}
	public function setMesesTrabalhado($mesesTrabalhado) {
		$this->MesesTrabalhado = $mesesTrabalhado;
	}
	
	private $ValorSalarioFuncionario; 
	public function getValorSalarioFuncionario(){
		return $this->ValorSalarioFuncionario;
	}
	public function setValorSalarioFuncionario($valorSalarioFuncionario) {
		$this->ValorSalarioFuncionario = $valorSalarioFuncionario;
	}
	
	private $FeriasId;
	public function getFeriasId(){
		return $this->FeriasId;
	}
	public function setFeriasId($val) {
		$this->FeriasId = $val;
	}	
	
	private $DiasFerias;
	public function getDiasFerias() {
		return $this->DiasFerias;
	} 
	public function setDiasFerias($val){
		$this->DiasFerias = $val;
	}	
	
	private $ValorFerias;
	public function getValorFerias() {
		return $this->ValorFerias;
	} 
	public function setValorFerias($val){
		$this->ValorFerias = $val;
	}	
	
	private $ValorUmTercoFerias;
	public function getValorUmTercoFerias() {
		return $this->ValorUmTercoFerias;
	} 
	public function setValorUmTercoFerias($val){
		$this->ValorUmTercoFerias = $val;
	}
	
	private $VendaUmTercoFerias;
	public function getVendaUmTercoFerias() {
		return $this->VendaUmTercoFerias;
	} 
	public function setVendaUmTercoFerias($val){
		$this->VendaUmTercoFerias = $val;
	}	
	
	private $ValorFeriasVendida;
	public function getValorFeriasVendida(){
		return $this->ValorFeriasVendida;
	}
	public function setValorFeriasVendida($val){
		$this->ValorFeriasVendida = $val;
	}
	
	private $ValorUmTercoFeriasVendida;
	public function getValorUmTercoFeriasVendida(){
		return $this->ValorUmTercoFeriasVendida;	
	}
	public function setValorUmTercoFeriasVendida($val){
		$this->ValorUmTercoFeriasVendida = $val;
	}
	
	private $ReferenciaIRFerias;
	public function getReferenciaIRFerias() {
		return $this->ReferenciaIRFerias;
	} 
	public function setReferenciaIRFerias($val){
		$this->ReferenciaIRFerias = $val;
	}	
	
	private $ValorIRFerias;
	public function getValorIRFerias() {
		return $this->ValorIRFerias;
	} 
	public function setValorIRFerias($val){
		$this->ValorIRFerias = $val;
	}	
	
	private $LiquidoFerias;
	public function getLiquidoFerias() {
		return $this->LiquidoFerias;
	}
	public function setLiquidoFerias($val) {
		$this->LiquidoFerias = $val;
	}
	private $LivroCaixaId;
	public function getLivroCaixaId() {
		return $this->LivroCaixaId;
	}
	public function setLivroCaixaId($val) {
		$this->LivroCaixaId = $val;
	}
}