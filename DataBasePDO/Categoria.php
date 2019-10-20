<?php
/**
 * Autor: Átano de Farias
 * Data: 15/02/2017 
 */	
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'contador') {
	require_once('../conect.PDO.php');
} else {
	require_once('conect.PDO.php');
}

class Categoria extends AccessDB {
	
	function PegaTodasCategorias() {
	
		$query = $this->PDO->prepare('SELECT * FROM categoria ORDER BY categoriaNome ASC');
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	function PegaCategoria($categoriaId) {
	
		$query = $this->PDO->prepare(' SELECT * FROM categoria '
			.' WHERE categoriaId = '.$categoriaId.'; ');
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	function IncluirCategoria($model) {
	
		$query = prepare(' INSERT INTO categoria (categoriaNome, categoriaAtivo, categoriaTipo, categoriaData) '
			.'values (:categoriaNome, :categoriaAtivo, :categoriaTipo, NOW())');
		 
		 
		$params = array(
		 	'categoriaNome' => $model->getCategoriaNome(),
		 	'categoriaAtivo' => $model->getCategoriaAtivo(), 
		 	'categoriaTipo' => $model->getCategoriaTipo()
		);
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $this->PDO->lastInsertId();; 
	}
	
	function AlterarCategoria($model) {
	
		$query = $this->PDO->prepare(' UPDATE categoria '
			.' SET categoriaNome = :categoriaNome, '  
			.'     categoriaAtivo = :categoriaAtivo, '
			.'     categoriaTipo = :categoriaTipo '
			.' WHERE categoriaId = :categoriaId; ');

		$params = array(
			'categoriaId' => $model->getCategoriaId(),
		 	'categoriaNome' => $model->getCategoriaNome(),
		 	'categoriaAtivo' => $model->getCategoriaAtivo(), 
		 	'categoriaTipo' => $model->getCategoriaTipo()
		);
		  
		if(!$query->execute($params)){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		} 
	}
	
	function ExcluirCategoria($categoriaId) {
	
		$query = $this->PDO->prepare('DELETE FROM categoria '
			.' WHERE categoriaId = '.$categoriaId.'; ');
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}		
	
}


