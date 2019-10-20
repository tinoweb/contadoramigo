<?php 
	
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);
	
	require_once('../Model/LivroCaixa/LivroCaixaData.php');	
	
	$livroCaixaData = new LivroCaixaData();
	
	$categoriaAntiga = '';
	$categoriaCorrigida = '';
	$userIds = '';
	
	if(isset($_POST['categoriaCorrigida']) && !empty($_POST['categoriaCorrigida'])) {
		
		foreach($_POST as $key => $val){
			
			if( $key == 'categoriaCorrigida' ) {
				$categoriaCorrigida = $val;
			} elseif( $key == 'categoriaAntiga' ){
				$categoriaAntiga = $val;	
			} else {
				$userIds[] = $val;
			}
			
		}
		
		$livroCaixaData->atualizaCategoria($categoriaAntiga, $categoriaCorrigida, $userIds);
	}
	
	
	header('Location: /admin/tabelasLivroCaixaCategoria.php?categoria='.str_replace(' ','+',$categoriaCorrigida));
?>