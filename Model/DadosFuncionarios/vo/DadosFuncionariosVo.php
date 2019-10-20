<?php
/**
 *	Autor: Átano de Farias Jacinto.
 *	Data: 07/07/2017
 */
class DadosFuncionariosVo {
	
	private $FuncionarioId;
	public function getFuncionarioId(){
		return $this->FuncionarioId;		
	}
	public function setFuncionarioId($funcionarioId){
		$this->FuncionarioId = $funcionarioId;		
	}
	
	private $UserId;
	public function getUserId(){
		return $this->UserId;		
	}
	public function setUserId($userId){
		$this->UserId = $userId;		
	}
	
	private $DataAdmissao;
	public function getDataAdmissao(){
		return $this->DataAdmissao;		
	}
	public function setDataAdmissao($dataAdmissao){
		$this->DataAdmissao = $dataAdmissao;		
	}
	
	private $Nome;
	public function getNome(){
		return $this->Nome;		
	}
	public function setNome($nome){
		$this->Nome = $nome;		
	}	
	
	private $Sexo;
	public function getSexo(){
		return $this->Sexo;		
	}
	public function setSexo($sexo){
		$this->Sexo = $sexo;		
	}		
	
	private $Nacionalidade;
	public function getNacionalidade(){
		return $this->Nacionalidade;		
	}
	public function setNacionalidade($nacionalidade){
		$this->Nacionalidade = $nacionalidade;		
	}	
	
	private $Naturalidade;
	public function getNaturalidade(){
		return $this->Naturalidade;		
	}
	public function setNaturalidade($naturalidade){
		$this->Naturalidade = $naturalidade;		
	}
		
	private $EstadoCivil;
	public function getEstadoCivil(){
		return $this->EstadoCivil;		
	}
	public function setEstadoCivil($estadoCivil){
		$this->EstadoCivil = $estadoCivil;		
	}
		
	private $CodigoCBO;
	public function getCodigoCBO(){
		return $this->CodigoCBO;		
	}
	public function setCodigoCBO($codigoCBO){
		$this->CodigoCBO = $codigoCBO;		
	}
	
	private $CPF;
	public function getCPF(){
		return $this->CPF;		
	}
	public function setCPF($cpf){
		$this->CPF = $cpf;		
	}
	
	private $RG;
	public function getRG(){
		return $this->RG;		
	}
	public function setRG($rg){
		$this->RG = $rg;		
	}
	
	private $CTPS;
	public function getCTPS(){
		return $this->CTPS;		
	}
	public function setCTPS($ctps){
		$this->CTPS = $ctps;		
	}
	
	private $SerieCTPS;
	public function getSerieCTPS(){
		return $this->SerieCTPS;		
	}
	public function setSerieCTPS($serieCTPS){
		$this->SerieCTPS = $serieCTPS;		
	}
		
	private $UfCTPS;
	public function getUfCTPS(){
		return $this->UfCTPS;		
	}
	public function setUfCTPS($ufCTPS){
		$this->UfCTPS = $ufCTPS;		
	}
		
	private $DataEmissao;
	public function getDataEmissao(){
		return $this->DataEmissao;		
	}
	public function setDataEmissao($dataEmissao){
		$this->DataEmissao = $dataEmissao;		
	}
	
	private $OrgaoExpeditor;
	public function getOrgaoExpeditor(){
		return $this->OrgaoExpeditor;		
	}
	public function setOrgaoExpeditor($orgaoExpeditor){
		$this->OrgaoExpeditor = $orgaoExpeditor;		
	}
		
	private $DataNascimento;
	public function getDataNascimento(){
		return $this->DataNascimento;		
	}
	public function setDataNascimento($dataNascimento){
		$this->DataNascimento = $dataNascimento;		
	}
		
	private $Endereco;
	public function getEndereco(){
		return $this->Endereco;		
	}
	public function setEndereco($endereco){
		$this->Endereco = $endereco;		
	}
		
	private $Bairro;
	public function getBairro(){
		return $this->Bairro;		
	}
	public function setBairro($bairro){
		$this->Bairro = $bairro;		
	}
	
	private $CEP;
	public function getCEP(){
		return $this->CEP;		
	}
	public function setCEP($cep){
		$this->CEP = $cep;		
	}
	
	private $Cidade;
	public function getCidade(){
		return $this->Cidade;		
	}
	public function setCidade($cidade){
		$this->Cidade = $cidade;		
	}
		
	private $Estado;
	public function getEstado(){
		return $this->Estado;		
	}
	public function setEstado($estado){
		$this->Estado = $estado;		
	}
	
	private $PrefTelefone;
	public function getPrefTelefone(){
		return $this->PrefTelefone;		
	}
	public function setPrefTelefone($pref){
		$this->PrefTelefone = $pref;		
	}
	
	private $Telefone;
	public function getTelefone(){
		return $this->Telefone;		
	}
	public function setTelefone($fone){
		$this->Telefone = $fone;		
	}
	
	private $Funcao;
	public function getFuncao(){
		return $this->Funcao;		
	}
	public function setFuncao($funcao){
		$this->Funcao = $funcao;		
	}
	
	private $PIS;
	public function getPIS(){
		return $this->PIS;		
	}
	public function setPIS($pis){
		$this->PIS = $pis;		
	}
		
	private $Dependentes;
	public function getDependentes(){
		return $this->Dependentes;		
	}
	public function setDependentes($dependentes){
		$this->Dependentes = $dependentes;		
	}
		
	private $Pensao;
	public function getPensao(){
		return $this->Pensao;		
	}
	public function setPensao($pensao){
		$this->Pensao = $pensao;		
	}
	
	private $ValorSalario;
	public function getValorSalario(){
		return $this->ValorSalario;
	}
	public function setValorSalario($valorSalario){
		$this->ValorSalario = $valorSalario;
	}
		
	private $ValeRefeicao;
	public function getValeRefeicao(){
		return $this->ValeRefeicao;		
	}
	public function setValeRefeicao($valeRefeicao){
		$this->ValeRefeicao = $valeRefeicao;		
	}
	
	private $ValeRefeicaoPorc;
	public function getValeRefeicaoPorc(){
		return $this->ValeRefeicaoPorc;		
	}
	public function setValeRefeicaoPorc($valeRefeicaoPorc){
		$this->ValeRefeicaoPorc = $valeRefeicaoPorc;		
	}
	
	private $PercPensao;
	public function getPercPensao(){
		return $this->PercPensao;		
	}
	public function setPercPensao($percPensao){
		$this->PercPensao = $percPensao;		
	}
	
	
	private $ValeTransporte;
	public function getValeTransporte(){
		return $this->ValeTransporte;		
	}
	public function setValeTransporte($valeTransporte){
		$this->ValeTransporte = $valeTransporte;		
	}
	
	private $Status;
	public function getStatus(){
		return $this->Status;		
	}
	public function setStatus($status){
		$this->Status = $status;		
	}
	
	private $BancoId;
	public function getBancoId(){
		return $this->BancoId;		
	}
	public function setBancoId($bancoId){
		$this->BancoId = $bancoId;		
	}
	
	private $TipoConta;
	public function getTipoConta(){
		return $this->TipoConta;		
	}
	public function setTipoConta($tipoConta){
		$this->TipoConta = $tipoConta;		
	}
	
	private $Agencia;
	public function getAgencia(){
		return $this->Agencia;		
	}
	public function setAgencia($agencia){
		return $this->Agencia = $agencia;		
	}
	
	private $DigAgencia;
	public function getDigAgencia(){
		return $this->DigAgencia;		
	}
	public function setDigAgencia($digAgencia){
		$this->DigAgencia = $digAgencia;		
	}
	
	private $Conta;
	public function getConta(){
		return $this->Conta;		
	}
	public function setConta($conta){
		$this->Conta = $conta;		
	}
	
	private $DigConta;
	public function getDigConta(){
		return $this->DigConta;		
	}
	public function setDigConta($digConta){
		return $this->DigConta = $digConta;		
	}
	
	private $JornadaTrabalhoDiaria;
	public function getJornadaTrabalhoDiaria(){
		return $this->JornadaTrabalhoDiaria;		
	}
	public function setJornadaTrabalhoDiaria($jornadaTrabalhoDiaria){
		return $this->JornadaTrabalhoDiaria = $jornadaTrabalhoDiaria;		
	}
		
	private $InicioJornada;
	public function getInicioJornada(){
		return $this->InicioJornada;		
	}
	public function setInicioJornada($inicioJornada){
		return $this->InicioJornada = $inicioJornada;		
	}	
	
	private $FimJornada;
	public function getFimJornada(){
		return $this->FimJornada;		
	}
	public function setFimJornada($fimJornada){
		return $this->FimJornada = $fimJornada;		
	}		
		
	private $InicioIntervalo;
	public function getInicioIntervalo(){
		return $this->InicioIntervalo;		
	}
	public function setInicioIntervalo($inicioIntervalo){
		return $this->InicioIntervalo = $inicioIntervalo;		
	}	
	
	private $FimIntervalo;
	public function getFimIntervalo(){
		return $this->FimIntervalo;		
	}
	public function setFimIntervalo($fimIntervalo){
		return $this->FimIntervalo = $fimIntervalo;		
	}	
		
	private $TrabalhoSabado;
	public function getTrabalhoSabado(){
		return $this->TrabalhoSabado;		
	}
	public function setTrabalhoSabado($trabalhoSabado){
		return $this->TrabalhoSabado = $trabalhoSabado;		
	}
	
	private $InicioHorarioSabado;
	public function geInicioHorarioSabado(){
		return $this->InicioHorarioSabado;		
	}
	public function setInicioHorarioSabado($inicioHorarioSabado){
		return $this->InicioHorarioSabado = $inicioHorarioSabado;		
	}

	private $FimHorarioSabado;
	public function getFimHorarioSabado(){
		return $this->FimHorarioSabado;		
	}
	public function setFimHorarioSabado($fimHorarioSabado){
		return $this->FimHorarioSabado = $fimHorarioSabado;		
	}
}