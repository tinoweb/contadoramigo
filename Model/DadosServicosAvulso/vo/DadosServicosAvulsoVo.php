<?php
/**
 * Objeto de dados.
 * autor: Átano de Farias Jacinto
 * Date: 23/06/2017
 */
class DadosServicosAvulsoVo{

	private $ServicoId;
	private $Nome;
	private $NomeResumido;
	private $Valor;
	private $Tipo;
	private $ContratoId;
	private $HelpDesk;

	public function getServicoId() {
		return $this->ServicoId;
	}
	public function setServicoId($id) {
		$this->ServicoId = $id;
	}
	public function getNome() {
		return $this->Nome;
	}
	public function setNome($nome) {
		$this->Nome = $nome;
	}
	public function getNomeResumido() {
		return $this->NomeResumido;
	}
	public function setNomeResumido($nomeResumido) {
		$this->NomeResumido = $nomeResumido;
	}
	public function getValor() {
		return $this->Valor;
	}
	public function setValor($valor) {
		$this->Valor = $valor;
	}
	public function getTipo() {
		return $this->Tipo;
	}
	public function setTipo($tipo) {
		$this->Tipo = $tipo;
	}
	public function getContratoId() {
		return $this->ContratoId;
	}
	public function setContratoId($contratoId) {
		$this->ContratoId = $contratoId;
	}
	public function getHelpDesk() {
		return $this->HelpDesk;
	}
	public function setHelpDesk($helpDesk) {
		$this->HelpDesk = $helpDesk;
	}
}