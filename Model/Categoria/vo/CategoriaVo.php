<?php

class CategoriaVo {

	private $CategoriaId;
	private $CategoriaNome;
	private $CategoriaAtivo;
	private $CategoriaTipo;
	private $CategoriaData;
	
	public function getCategoriaId(){
		return $this->CategoriaId;  
	}
	
	public function setCategoriaId($categoriaId){
		$this->CategoriaId = $categoriaId;
	}

	public function getCategoriaNome(){
		return $this->CategoriaNome;  
	}
	
	public function setCategoriaNome($categoriaNome){
		$this->CategoriaNome = $categoriaNome;
	}
	
	public function getCategoriaAtivo(){
		return $this->CategoriaAtivo;  
	}
	
	public function setCategoriaAtivo($categoriaAtivo){
		$this->CategoriaAtivo = $categoriaAtivo;
	}
	
	public function getCategoriaTipo(){
		return $this->CategoriaTipo;  
	}
	
	public function setCategoriaTipo($categoriaTipo){
		$this->CategoriaTipo = $categoriaTipo;
	}
	
	public function getCategoriaData(){
		return $this->CategoriaData;  
	}
	
	public function setCategoriaData($categoriaData){
		$this->CategoriaData = $categoriaData;
	}
}