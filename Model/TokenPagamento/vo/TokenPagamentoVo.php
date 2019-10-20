<?php
/**
 * Classe Para realizar o encapsulamento
 * autor: Atano de Farias Jacinto
 * Date: 06/06/2017
 */
class TokenPagamentoVo {
		
	private $Id;
	private $IdUser;
	private $Token;
	private $NumeroCartao;
	private $NomeTitular;
	private $Bandeira;
	private $DataCriacao;

	function getId() {
		return $this->Id;
	}
	
	function setId($id) {
		$this->Id = $id;
	}
	
	function getIdUser() {
		return $this->IdUser;
	}
	
	function setIdUser($idUserv) {
		$this->IdUser = $idUserv;
	}	
	
	function getToken() {
		return $this->Token;
	}
	
	function setToken($token) {
		$this->Token = $token;
	}

	function getNumeroCartao() {
		return $this->NumeroCartao;
	}
	
	function setNumeroCartao($numeroCartao) {
		$this->NumeroCartao = $numeroCartao;
	}

	function getNomeTitular() {
		return $this->NomeTitular;
	}
	
	function setNomeTitular($nomeTitular) {
		$this->NomeTitular = $nomeTitular;
	}

	function getBandeira() {
		return $this->Bandeira;
	}
	
	function setBandeira($bandeira) {
		$this->Bandeira = $bandeira;
	}

	function getDataCriacao() {
		return $this->DataCriacao;
	}
	
	function setDataCriacao($dataCriacao) {
		$this->DataCriacao = $dataCriacao;
	}		
}