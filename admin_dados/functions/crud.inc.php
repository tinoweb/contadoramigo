<?php
	//error_reporting(0);
	class Banco {
		//Propriedades
		public $conexao			= NULL;
		public $dataset			= NULL;
		public $affected_rows	= -1;

		public function insert($objeto){
			$sql = "INSERT INTO ".$objeto->tabela." (";
			for($i=0; $i<count($objeto->valor_campos); $i++):
				$sql .= key($objeto->valor_campos);
				if($i<(count($objeto->valor_campos)-1)):
					$sql .= ", ";
				else:
					$sql .= ") ";
				endif;
				next($objeto->valor_campos);
			endfor;
			reset($objeto->valor_campos); //Coloca o ponteiro no primeiro registro denovo
			$sql .= "VALUES (";
			for($i=0; $i<count($objeto->valor_campos); $i++):
				$sql .= is_numeric($objeto->valor_campos[key($objeto->valor_campos)]) ?
					$objeto->valor_campos[key($objeto->valor_campos)] : //Verifica se é numerico
					"'".$objeto->valor_campos[key($objeto->valor_campos)]."'"; //Se for texto
				if($i<(count($objeto->valor_campos)-1)):
					$sql .= ", ";
				else:
					$sql .= ") ";
				endif;
				next($objeto->valor_campos);
			endfor;
			//echo $sql;
			unset($_POST);
			return $this->executaSQL($sql);
		}//Insert
		
		public function update($objeto){
			$sql = "UPDATE ".$objeto->tabela." SET ";
			for($i=0; $i<count($objeto->valor_campos); $i++):
				$sql .= key($objeto->valor_campos)."=";
				$sql .= is_numeric($objeto->valor_campos[key($objeto->valor_campos)]) ?
					$objeto->valor_campos[key($objeto->valor_campos)] : //Verifica se é numerico
					"'".$objeto->valor_campos[key($objeto->valor_campos)]."'"; //Se for texto
				if($i<(count($objeto->valor_campos)-1)):
					$sql .= ", ";
				else:
					$sql .= " ";
				endif;
				next($objeto->valor_campos);
			endfor;
			$sql .= "WHERE ".$objeto->campoID."=";
			$sql .= is_numeric($objeto->valorID) ? $objeto->valorID : "'".$objeto->valorID."'";
			//echo $sql;
			unset($_POST);
			return $this->executaSQL($sql);
		}//Update
		
		public function delete($objeto){
			$sql = "DELETE FROM ".$objeto->tabela." ";
			$sql .= "WHERE ".$objeto->campoID."=";
			$sql .= is_numeric($objeto->valorID) ? $objeto->valorID : "'".$objeto->valorID."'";
			//echo $sql;
			unset($_POST);
			return $this->executaSQL($sql);
		}//Delete
		
		public function selecionarTudo($objeto){
			$sql = "SELECT * FROM ".$objeto->tabela;
			if($objeto->extra_query!=NULL):
				$sql .= " ".$objeto->extra_query;
			endif;
			//echo $sql;
			return $this->executaSQL($sql);
		}//Seleciona TUDO
		
		public function selecionarCampos($objeto){
			$sql = "SELECT ";
			for($i=0; $i<count($objeto->valor_campos); $i++):
				$sql .= key($objeto->valor_campos);
				if($i<(count($objeto->valor_campos)-1)):
					$sql .= ", ";
				else:
					$sql .= " ";
				endif;
				next($objeto->valor_campos);
			endfor;
			$sql .= " FROM ".$objeto->tabela;
			$sql .= " WHERE ".$objeto->campoID."=";
			$sql .= is_numeric($objeto->valorID) ? $objeto->valorID : "'".$objeto->valorID."'";
			if($objeto->extra_query!=NULL):
				$sql .= " ".$objeto->extra_query;
			endif;
			//echo $sql;
			return $this->executaSQL($sql);
		}//Seleciona Campos
		
		public function tratarErro($arquivo=NULL,$rotina=NULL,$numErro=NULL,$msgErro=NULL,$geraExcept=FALSE){
			if($arquivo==NULL) $arquivo="Arquivo não informado.";
			if($rotina==NULL) $rotina="Rotina não informada.";
			$resultado = '
				Houve um erro com os seguintes detalhes:<br>
				<b>Arquivo</b> '.$arquivo.'<br>
				<b>Rotina</b> '.$rotina.'<br>
				<b>Código</b> '.$numErro.'<br>
				<b>Mensagem</b> '.$msgErro.'
			';
			if($geraExcept==FALSE):
				echo($resultado);
			else:
				die($resultado);
			endif;
		}//TratarErro
		
		public function executaSQL($sql=NULL){
			//echo $sql;
			if($sql!=NULL):
				$query = mysql_query($sql) or $this->tratarErro(__FILE__,__FUNCTION__);
				$this->affected_rows = mysql_affected_rows(); //Quantas linhas foram afetadas
				if($this->affected_rows < 1) $this->affected_rows = 0;
				if(substr(trim(strtolower($sql)),0,6)=='select'):
					$this->dataset = $query;
					return $query;
				else:
					return $this->affected_rows;
				endif;
			else:
				$this->tratarErro(__FILE__,__FUNCTION__,NULL,'Comando SQL não informado.',FALSE);
			endif;
		}//Executa SQL
		
		public function retornaDados($tipo=NULL){
			switch (strtolower($tipo)):
				case "array":
					return mysql_fetch_array($this->dataset);
				break;
				case "assoc":
					return mysql_fetch_assoc($this->dataset);
				break;
				case "object":
					return mysql_fetch_object($this->dataset);
				break;
				default:
					return mysql_fetch_object($this->dataset);
				break;
				
			endswitch;
		}//RetornaDados
	}//Classe Banco//
?>