<?php 

	class Demos {
		
		private $busca;
		private $pagina;
		private $demos;
		
		private $itens_pagina;

		private $isBusca;
		private $isPagina;
		function setIsBusca($string){
			$this->isBusca = $string;
		}
		function getIsBusca(){
			return $this->isBusca;
		}
		function setIsPagina($string){
			$this->isPagina = $string;
		}
		function getIsPagina(){
			return $this->isPagina;
		}
		function setBusca($string){
			$this->busca = $string;
		}
		function geraTimestamp($data) {
			$partes = explode('-', $data);
			return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
		}
		function diferencaData($dataFinal,$dataInicial){
			$dataFinal = $this->geraTimestamp($dataFinal);
			$dataInicial = $this->geraTimestamp($dataInicial);
			$diferenca = $dataFinal - $dataInicial;
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); 
			return $dias;
		}
		function getBusca(){
			if( $this->busca == 'demo' )
				return "status = 'demo'";
			if( $this->busca == 'demoInativo' )
				return "status = 'demoInativo'";
			if( $this->busca == 'todos' )
				return "status = 'demoInativo' OR status = 'demo'";
		}
		function setPagina($string){
			$this->pagina = $string;
		}
		function temEmpresa($id){
			$consulta = mysql_query("SELECT * FROM login WHERE id != idUsuarioPai AND idUsuarioPai = '".$id."' ");
			$objeto=mysql_fetch_array($consulta);
			if( $objeto['id'] != '' )
				return 'E';
			


		}
		function getPagina(){
			return $this->pagina;
		}
		function getPaginacao(){
			$filtro = $this->getBusca();
			$consulta = mysql_query("SELECT * FROM login WHERE id = idUsuarioPai AND (".$filtro.") ORDER BY data_inclusao DESC  ");
			return mysql_num_rows($consulta);
		}
		function getObs($id){
			$consulta = mysql_query("SELECT * FROM obs_demos WHERE id = '".$id."' ");
			$objeto=mysql_fetch_array($consulta);
			return $objeto['texto'];
		}
		function desconverterData($data){
			$data = explode(' ', $data);
			$aux = explode('-', $data[0]);
			return $aux[2].'/'.$aux[1].'/'.$aux[0];
		}
		function getDemos(){
			if( $this->getIsBusca() )
				return $this->selectBusca();
			else
				return $this->select();
		}
		function select(){
			
			$paginacao = $this->definePagina();
			$filtro = $this->getBusca();
			$consulta = mysql_query("SELECT * FROM login WHERE id = idUsuarioPai AND (".$filtro.") ORDER BY data_inclusao DESC  ".$paginacao." ");

			return $consulta;
		}
		function definePagina(){
			return 'LIMIT '.$this->itens_pagina*($this->getPagina()-1).','.$this->itens_pagina;
		}
		function getTelefone($id){
			$consulta = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$id."' ");
			$objeto=mysql_fetch_array($consulta);
			$telefone = "";
			if($objeto["telefone"] != ""){
				if(strlen($objeto["telefone"]) == 8){
					$telefone = "(" . $objeto["pref_telefone"] . ") " . substr($objeto["telefone"],0,4) . "-" . substr($objeto["telefone"],-4);
				}else{
					$telefone = "(" . $objeto["pref_telefone"] . ") " . substr($objeto["telefone"],0,5) . "-" . substr($objeto["telefone"],-4);
				}
			}
			return $telefone;
		}
		function __construct(){
			
			$this->itens_pagina = 100;

			$this->setIsPagina(false);
			$this->setPagina(1);
			$this->setBusca("demo");

			if( isset( $_GET['itens'] ) ):
				
				$this->itens_pagina = $_GET['itens'];
			
			endif;

			if( isset( $_GET['pagina'] ) ):
				$this->setIsPagina(true);
				$this->setPagina($_GET['pagina']);
			endif;

			if( isset( $_GET['filtro'] ) ):
				$this->setBusca($_GET['filtro']);
			endif;

		}
	}

?>