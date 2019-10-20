<?php
/**
 * Classe para realizar a conexao com o banco de dados.
 * Autor: Átano de Farias
 * Data: 08/02/2017 
 */
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'admin') {
	require_once('../DataBasePDO/LivroCaixa.php');
	require_once('../Model/LivroCaixa/vo/UserLivroCaixaVo.php');
} else {
	require_once('DataBasePDO/LivroCaixa.php');
	require_once('Model/LivroCaixa/vo/UserLivroCaixaVo.php');
}

class LivroCaixaData {
	
	public function pegaTodosIdEmpresa () {
	
		try {
		
			$livroCaixa = new  LivroCaixa();
			
			return $livroCaixa->GetAllUserId();
				
		} catch (Exception $e) {	
			Throw new Exception($e->getMessage());
		}
		
	}
	
	public function VerificaTabelasVazias($statusVazio = false, $status = false) {
	
		$livroCaixa = new  LivroCaixa();
		
		$todosId = $livroCaixa->GetAllUserId($status);
		
		$tabelas = array();
		
		foreach($todosId as $val) {
	
			$result =  $livroCaixa->GetFirstRowTable($val['id']);
	
			if( $result != "NoExists") {
				
				if($result == $statusVazio) {
					$tabelas[] = array('id' => $val['id'],'razaoSocial' => $val['razao_social'], 'statusLog' => $val['status']);
				}
				
			}
		}
	
		return $tabelas;
	}
	
	public function tabelaCategorias($categoria) {
		
		$livroCaixa = new  LivroCaixa();
		
		$IdEmpresa = $livroCaixa->GetAllUserId();
		
		$out = false;
		
		foreach($IdEmpresa as $val){
	
			$result =  $livroCaixa->GetFirstRowTable($val['id']);
	
			if( $result != "NoExists") {
				
				$arr = $livroCaixa->GetCategoriaTable($val['id'], $categoria);
				$arruser = $livroCaixa->GetAllUserData($val['id']);
				
				if($arr) {
					$arruser['dadosLivroCaixa'] = $arr;
					$out[] =  $arruser;				
				}
			}
		}
		
		return $out;
	}	
	
	public function categoriasNaoPadronizadas() {
		
		$livroCaixa = new  LivroCaixa();
		
		$IdEmpresa = $livroCaixa->GetAllUserId();
		
		$out = false;
		
		foreach($IdEmpresa as $val){
	
			$result =  $livroCaixa->GetFirstRowTable($val['id']);
	
			if( $result != "NoExists") {
				
				$arr = $livroCaixa->GetCategoriaNaoPadronizadas($val['id']);
				$arruser = $livroCaixa->GetAllUserData($val['id']);
				
				if($arr) {
					$arruser['dadosLivroCaixa'] = $arr;
					$out[] =  $arruser;				
				}
			}
		}
		
		return $out;
	}
	
	
	public function ListaCategoriasNaoPadronizadas() {
		
		$livroCaixa = new  LivroCaixa();
		
		$IdEmpresa = $livroCaixa->GetAllUserId();
		
		$out = array();
		
		foreach($IdEmpresa as $val){
	
			$result =  $livroCaixa->GetFirstRowTable($val['id']);
	
			if( $result != "NoExists") {
				
				$arr = $livroCaixa->GetCategoriaNaoPadronizadas($val['id']);
				
				foreach($arr as $ar) {
					if (!in_array($ar['categoria'], $out)) { 
						$out[] = $ar['categoria'];
					}		
				}
			}
		}
		
		return $out;
	}
	
	public function atualizaCategoria($categoriaAntiga, $categoriaCorrigida, $userIds) {
		
		$livroCaixa = new  LivroCaixa();
		
		foreach($userIds as $key => $val) {
			$livroCaixa->atualizaCategoriaTables($categoriaAntiga, $categoriaCorrigida, $val);	
		}	
	}	
	
	public function ExcluiAsTabelasVazias() {
	
		if($_POST) {
			foreach($_POST as $val) {
				if(is_numeric($val)) {
					$arrayId[] = $val;
				}
			}
		}
		
		$livroCaixa = new  LivroCaixa();
		$livroCaixa->DropMultipleTables($arrayId);	
	}
	
}

