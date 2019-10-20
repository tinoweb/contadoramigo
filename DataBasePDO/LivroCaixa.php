<?php
/**
 * Autor: Átano de Farias
 * Data: 15/02/2017 
 */	
$requestURI = explode("/", $_SERVER['REQUEST_URI']);

if($requestURI[1] == 'admin') {
	require_once('../conect.PDO.php');
} else {
	require_once('conect.PDO.php');
}

class LivroCaixa extends AccessDB {
	
	public function GetAllUserId($status = false) {
		 
		 $qry = " SELECT Emp.id, Emp.razao_social, Log.status FROM dados_da_empresa Emp "
				." JOIN login Log on Log.id = Emp.id ";
	
		if($status) {
			$qry .= " WHERE Log.status = '".$status."'";
		} 
		
		$query = $this->PDO->prepare($qry);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
		 
	}

	public function GetAllUserData($id) {
		 
		 $qry = " SELECT Emp.id, Emp.razao_social, Log.status FROM dados_da_empresa Emp "
				." JOIN login Log on Log.id = Emp.id "
				." WHERE Log.id = $id";
	
		$query = $this->PDO->prepare($qry);
		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);	 
	}

	public function GetFirstRowTable($id) {

		$query = $this->PDO->prepare(' SELECT * FROM user_'.$id.'_livro_caixa LIMIT 0, 1; ');

		if(!$query->execute()){
			return "NoExists";
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function CheckIfExistTable($id) {

		$query = $this->PDO->prepare(' SELECT * FROM user_'.$id.'_livro_caixa LIMIT 0, 1; ');

		if(!$query->execute()){
			return "NoExists";
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function GetDataLivroCaixa($id) {

		$query = $this->PDO->prepare(' SELECT * FROM user_'.$id.'_livro_caixa');

		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function GetCategoriaTable($id, $categoria) {

		$query = $this->PDO->prepare(' SELECT * FROM user_'.$id.'_livro_caixa WHERE categoria = "'.$categoria.'"; ');
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function GetCategoriaNaoPadronizadas($id) {
		
		$qry = ' SELECT * FROM user_'.$id.'_livro_caixa ' 
			   .' WHERE categoria NOT IN (SELECT categoriaNome FROM categoria) '
			   .' AND categoria NOT IN (SELECT apelido FROM dados_clientes WHERE id_login = '.$id.'); ';
	
		$query = $this->PDO->prepare($qry);
		
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);	
			
	}
	
	public function atualizaCategoriaTables($categoriaAntiga, $categoriaCorrigida, $val) {
		
			
		$qry = " UPDATE user_".$val."_livro_caixa "
			  ." SET categoria = '".$categoriaCorrigida."'"
			  ." WHERE categoria = '".$categoriaAntiga."'; ";
	

		$query = $this->PDO->prepare($qry);
	
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
	
	public function DropMultipleTables($arrayId) {
		
		$qry = ''; 
	  		
		foreach($arrayId as $val){
			$qry .= ' DROP TABLE IF EXISTS user_'.$val.'_livro_caixa; ';		
		}
	
		if($qry){
			$query = $this->PDO->prepare($qry);
		} 
		// executa a query		  
		if(!$query->execute()){
			$info = $query->errorInfo();
			throw new Exception($info[2]);
		}
	}
}


