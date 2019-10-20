<?php 
	
	class Estado{
		private $sql;
		private $sigla;
		private $script;
		private $options;
		private $id_select_origem;
		private $id_select_destino;
		function getid_select_destino(){
			return $this->id_select_destino;
		}
		function setid_select_destino($string){
			$this->id_select_destino = $string;
		}
		function getid_select_origem(){
			return $this->id_select_origem;
		}
		function setid_select_origem($string){
			$this->id_select_origem = $string;
		}
		function getoptions(){
			return $this->options;
		}
		function setoptions($string){
			$this->options = $string;
		}
		function getsql(){
			return $this->sql;
		}
		function setsql($string){
			$this->sql = $string;
		}
		function getsigla(){
			return $this->sigla;
		}
		function setsigla($string){
			$this->sigla = $string;
		}
		function getscript(){
			return $this->script;
		}
		function setscript($string){
			$this->script = $string;
		}
		function verificarSelected($string1,$string2){
			if( $string1 == $string2 )
				echo 'selected';
		}
		function gerarSql(){
			$consulta = mysql_query("SELECT * FROM estados ORDER by id ASC ");
			$this->setsql($consulta);
		}
		function gerarOptions(){
			$estados = $this->getsql();
			$string = '';
			while( $estado = mysql_fetch_array($estados) ){
				$string .= '
					<option value="'.$estado['id'].';'.$estado['sigla'].';'.$estado['estado'].'">'.$estado['estado'].'</option>
				';
			}
			$this->setoptions($string);
		}
		function gerarScript(){
			
			$string = "
					$( document ).ready(function() {
						$('#".$this->getid_select_origem()."').change(function() { 
							var id = $('#".$this->getid_select_origem()."').val();
							id = id.split(';');
							id = id[0];
							$.ajax({
								url:'ajax.php'
								, data: 'getCidadeSelect=getCidadeSelect&id='+id
								, type: 'post'
								, async: true
								, cache:false
								, success: function(retorno){
									$('#".$this->getid_select_destino()."').empty();
									$('#".$this->getid_select_destino()."').append(retorno);				    
								}
							});
						});	
					});
			";
			$this->setscript($string);
		
		}
		function __construct($id_origem,$id_destino){
			$this->setid_select_origem($id_origem);
			$this->setid_select_destino($id_destino);
			$this->gerarSql();
			$this->gerarOptions();
			$this->gerarScript();
		}
	}
	class Cidade{
		private $id;
		private $id_uf;
		private $cidade;
		private $options;
		function getoptions(){
			return $this->options;
		}
		function setoptions($string){
			$this->options = $string;
		}
		function getcidade(){
			return $this->cidade;
		}
		function setcidade($string){
			$this->cidade = $string;
		}
		function getid_uf(){
			return $this->id_uf;
		}
		function setid_uf($string){
			$this->id_uf = $string;
		}
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
		function getsql(){
			
			$consulta = mysql_query("SELECT * FROM cidades WHERE id_uf = '".$this->getid_uf()."' ");
			return $consulta;
		
		}
		function gerarOptions(){	
			$cidades = $this->getsql();
			$string = '';
			while( $cidade = mysql_fetch_array($cidades) ){
				$string .= '
					<option value="'.$cidade['cidade'].'">'.$cidade['cidade'].'</option>
				';
			}
			$this->setoptions($string);
		}
		function __construct($id_uf){
			$this->setid_uf($id_uf);
			$this->gerarOptions();
		}

	}
?>