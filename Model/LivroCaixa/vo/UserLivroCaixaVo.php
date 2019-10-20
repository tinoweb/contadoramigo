<?php

class UserLivroCaixaVo {

	private $Id;
	private $Data;
	private $Entrada;
	private $Saida;
	private $DocumentoNumero;
	private $Descricao;
	private $Categoria;
	
	public function getId(){
		return $this->Id;  
	}
	
	public function setId($id){
		$this->Id = $id;
	}
	
	public function getData(){
		return $this->Data;
	}
	
	public function setData($data){
		$this->Data = $data;
	}
	
	public function getEntrada(){
		return $this->Entrada;	
	}
	
	public function setEntrada($entrada){
		$this->Entrada = $entrada;
	}
	
	public function getSaida(){
		return $this->Saida;
	}
	
	public function setSaida($saida){
		$this->Saida = $saida;
	}
	
	public function getDocumentoNumero(){
		return $this->DocumentoNumero;
	}
	
	public function setDocumentoNumero($documentoNumero){
		$this->DocumentoNumero = $documentoNumero;	
	}
	
	public function getDescricao(){
		return $this->Descricao;	
	}
	
	public function setDescricao($descricao){
		$this->Descricao = $descricao;	
	}
	
	public function getCategoria(){
		return $this->Categoria;
	}
	
	public function setcategoria($categoria){
		$this->Categoria = $categoria;
	}
}