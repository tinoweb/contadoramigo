<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */
class PagamentoFeriasVo {
		
	private $FeriasId;
	public function getFeriasId() {
		return $this->FeriasId;
	} 
	public function setFeriasId($val){
		$this->FeriasId = $val;
	}	
	
	private $EmpresaId;
	public function getEmpresaId() {
		return $this->EmpresaId;
	} 
	public function setEmpresaId($val){
		$this->EmpresaId = $val;
	}	
	
	private $FuncionarioId;
	public function getFuncionarioId() {
		return $this->FuncionarioId;
	} 
	public function setFuncionarioId($val){
		$this->FuncionarioId = $val;
	}	
	
	private $FuncionarioNome;
	public function getFuncionarioNome() {
		return $this->FuncionarioNome;
	} 
	public function setFuncionarioNome($val){
		$this->FuncionarioNome = $val;
	}	
	
	private $DataInclusao;
	public function getDataInclusao() {
		return $this->DataInclusao;
	} 
	public function setDataInclusao($val){
		$this->DataInclusao = $val;
	}
	
	private $DataPagto;
	public function getDataPagto() {
		return $this->DataPagto;
	} 
	public function setDataPagto($val){
		$this->DataPagto = $val;
	}
	
	private $DataInicio;
	public function getDataInicio() {
		return $this->DataInicio;
	} 
	public function setDataInicio($val){
		$this->DataInicio = $val;
	}	
		
	private $DataFim;
	public function getDataFim() {
		return $this->DataFim;
	} 
	public function setDataFim($val){
		$this->DataFim = $val;
	}	
	
	private $DiasFerias;
	public function getDiasFerias() {
		return $this->DiasFerias;
	} 
	public function setDiasFerias($val){
		$this->DiasFerias = $val;
	}
	
	private $SalarioFuncionario;
	public function getSalarioFuncionario() {
		return $this->SalarioFuncionario;
	} 
	public function setSalarioFuncionario($val){
		$this->SalarioFuncionario = $val;
	}
	
	private $ValorFeriasMes1;
	public function getValorFeriasMes1() {
		return $this->ValorFeriasMes1;
	} 
	public function setValorFeriasMes1($val){
		$this->ValorFeriasMes1 = $val;
	}
	
	private $ValorFeriasMes2;
	public function getValorFeriasMes2() {
		return $this->ValorFeriasMes2;
	} 
	public function setValorFeriasMes2($val){
		$this->ValorFeriasMes2 = $val;
	}	
		
	private $ValorFerias;
	public function getValorFerias() {
		return $this->ValorFerias;
	} 
	public function setValorFerias($val){
		$this->ValorFerias = $val;
	}	
	
	private $VendaUmTercoFerias;
	public function getVendaUmTercoFerias() {
		return $this->VendaUmTercoFerias;
	} 
	public function setVendaUmTercoFerias($val){
		$this->VendaUmTercoFerias = $val;
	}	
	
	private $ValorUmTercoFerias;
	public function getValorUmTercoFerias() {
		return $this->ValorUmTercoFerias;
	} 
	public function setValorUmTercoFerias($val){
		$this->ValorUmTercoFerias = $val;
	}	
	
	private $ReferenciaINSS;
	public function getReferenciaINSS() {
		return $this->ReferenciaINSS;
	} 
	public function setReferenciaINSS($val){
		$this->ReferenciaINSS = $val;
	}	
	
	private $ValorINSS;
	public function getValorINSS() {
		return $this->ValorINSS;
	} 
	public function setValorINSS($val){
		$this->ValorINSS = $val;
	}	
	
	private $ReferenciaIR;
	public function getReferenciaIR() {
		return $this->ReferenciaIR;
	} 
	public function setReferenciaIR($val){
		$this->ReferenciaIR = $val;
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
	public function setFaixaIR($val){
		$this->FaixaIR = $val;
	}	
	
	private $ValorLiquido;
	public function getValorLiquido() {
		return $this->ValorLiquido;
	} 
	public function setValorLiquido($val){
		$this->ValorLiquido = $val;
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
	
	private $NumeroDependentes;
	public function getNumeroDependentes() {
		return $this->NumeroDependentes;
	} 
	public function setNumeroDependentes($val){
		$this->NumeroDependentes = $val;
	}
	
	private $DescontoDepValor;
	public function getDescontoDepValor() {
		return $this->DescontoDepValor;
	} 
	public function setDescontoDepValor($val){
		$this->DescontoDepValor = $val;
	}

	private $DataFeriasAbonoInicio;
	public function getDataFeriasAbonoInicio() {
		return $this->DataFeriasAbonoInicio;
	}
	public function setDataFeriasAbonoInicio($val) {
		$this->DataFeriasAbonoInicio = $val; 
	}	
		
	private $DataFeriasAbonoFim;
	public function getDataFeriasAbonoFim() {
		return $this->DataFeriasAbonoFim;
	}
	public function setDataFeriasAbonoFim($val) {
		$this->DataFeriasAbonoFim = $val; 
	}
		
	private $ReferenciaSecundarioINSS;
	public function getReferenciaSecundarioINSS(){
		return $this->ReferenciaSecundarioINSS;
	}
	public function setReferenciaSecundarioINSS($val){
		return $this->ReferenciaSecundarioINSS = $val;
	}
	
	private $ValorSecundarioINSS;
	public function getValorSecundarioINSS() {
		return $this->ValorSecundarioINSS;
	}
	public function setValorSecundarioINSS($val){
		$this->ValorSecundarioINSS = $val;	
	}
	
	private $ValorPensao;
	public function getValorPensao() {
		return $this->ValorPensao;
	} 
	public function setValorPensao($val){
		$this->ValorPensao = $val;
	}
		
	private $ReferenciaPensao;
	public function getReferenciaPensao() {
		return $this->ReferenciaPensao;
	} 
	public function setReferenciaPensao($val){
		$this->ReferenciaPensao = $val;
	}
	
	private $LivroCaixaId;
	public function getLivroCaixaId() {
		return $this->LivroCaixaId;
	}
	public function setLivroCaixaId($val) {
		$this->LivroCaixaId = $val;
	}
}