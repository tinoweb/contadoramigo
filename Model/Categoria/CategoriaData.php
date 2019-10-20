<?php
/**
 * Classe para realizar a conexao com o banco de dados.
 * Autor: Átano de Farias
 * Data: 17/02/2017 
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'admin') {
	require_once('../DataBasePDO/Categoria.php');
	require_once('../Model/Categoria/vo/CategoriaVo.php');
} else {
	require_once('DataBasePDO/Categoria.php');
	require_once('Model/Categoria/vo/CategoriaVo.php');
}

class CategoriaData {
	
	public function pegaTodosCategorias() {
		
		$categoria = new  Categoria();
		
		$todasCategorias = $categoria->PegaTodasCategorias();
		
		$out = false;
		
		if($todasCategorias){
			
			foreach($todasCategorias as $val) {
			
				$vo = new CategoriaVo();

				$vo->setCategoriaId($val['categoriaId']);
				$vo->setCategoriaNome($val['categoriaNome']);
				$vo->setCategoriaAtivo($val['categoriaAtivo']); 
				$vo->setCategoriaTipo($val['categoriaTipo']);
				$vo->setCategoriaData($val['categoriaData']);
				
				$out[] = $vo;
			}
		}
		
		return $out;
	}
}

