<?php 
	
	class Dados_boletos{
		
		private $id;
		private $id_user;
		private $id_historico;
		private $nosso_numero;
		private $status;
		private $tipo;
		private $plano;
		private $valor;
		private $multa;
		private $mora;
		private $vencimento;
		private $data_geracao;
		private $remessa_aceita;

		function __construct( $dados=NULL ){
			if( $dados != NULL ){
				$this->setid($dados['id']);
				$this->setid_user($dados['id_user']);
				$this->setid_historico($dados['id_historico']);
				$this->setnosso_numero($dados['nosso_numero']);
				$this->setstatus($dados['status']);
				$this->settipo($dados['tipo']);
				$this->setplano($dados['plano']);
				$this->setvalor($dados['valor']);
				$this->setmulta($dados['multa']);
				$this->setmora($dados['mora']);
				$this->setvencimento($dados['vencimento']);
				$this->setdata_geracao($dados['data_geracao']);
				$this->setremessa_aceita($dados['remessa_aceita']);
			}
		}
		function toMoney(){
			
			return "R$ ".number_format($this->getvalor(),2,',','.');
		
		}
		function getCompetencia(){
			$consulta = mysql_query("SELECT * FROM historico_cobranca WHERE idHistorico = '".$this->getid_historico()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['data_pagamento'];
		}
		function getremessa_aceita(){
			if( $this->remessa_aceita == 0 )
				return "Pendente";
			if( $this->remessa_aceita == 2 )
				return "Aceita";
			if( $this->remessa_aceita == 3 )
				return "Recusada";
		}
		function setremessa_aceita($string){
			$this->remessa_aceita = $string;
		}
		function getdata_geracao(){
			$aux = explode(' ', $this->data_geracao);
			return $aux[0];
		}
		function setdata_geracao($string){
			$this->data_geracao = $string;
		}
		function getvencimento(){
			return $this->vencimento;
		}
		function setvencimento($string){
			$this->vencimento = $string;
		}
		function getmora(){
			return $this->mora;
		}
		function setmora($string){
			$this->mora = $string;
		}
		function getmulta(){
			return $this->multa;
		}
		function setmulta($string){
			$this->multa = $string;
		}
		function getvalor(){
			return $this->valor;
		}
		function setvalor($string){
			$this->valor = $string;
		}
		function getplano(){
			return $this->plano;
		}
		function setplano($string){
			$this->plano = $string;
		}
		function gettipo(){
			return $this->tipo;
		}
		function settipo($string){
			$this->tipo = $string;
		}
		function getstatus(){
			return $this->status;
		}
		function setstatus($string){
			$this->status = $string;
		}
		function getnosso_numero(){
			return $this->nosso_numero;
		}
		function setnosso_numero($string){
			$this->nosso_numero = $string;
		}
		function getid_historico(){
			return $this->id_historico;
		}
		function setid_historico($string){
			$this->id_historico = $string;
		}
		function getUser(){
			$consulta = mysql_query("SELECT * FROM dados_da_empresa WHERE id = '".$this->getid_user()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( $objeto_consulta['razao_social'] != '' )
				return $objeto_consulta['razao_social'];
			else{
				$consulta = mysql_query("SELECT * FROM dados_cobranca WHERE id = '".$this->getid_user()."' ");
				$objeto_consulta = mysql_fetch_array($consulta);
				return $objeto_consulta['assinante'];
			}
		}
		function getid_user(){
			return $this->id_user;
		}
		function setid_user($string){
			$this->id_user = $string;
		}
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
	}

?>