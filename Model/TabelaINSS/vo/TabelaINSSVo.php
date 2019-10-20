<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 11/07/2017
 */
class TabelaINSSVo {
	
	private $InssId;
	public function getInssId() {
		return $this->InssId;
	} 
	public function setInssId($inssId){
		$this->InssId = $inssId;
	}
	
	private $Ano;
	public function getAno() {
		return $this->Ano;
	}	
	public function setAno($ano){
		$this->Ano = $ano;
	}
	
	private $Valor;
	public function getValor() {
		return $this->Valor;
	}	
	public function setValor($valor){
		$this->Valor = $valor;
	}	
		
	private $Porcentagem;
	public function getPorcentagem() {
		return $this->Porcentagem;
	}	
	public function setPorcentagem($porcentagem){
		$this->Porcentagem = $porcentagem;
	}	
	
	
}