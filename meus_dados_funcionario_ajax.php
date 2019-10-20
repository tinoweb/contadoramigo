<?php 
/**
 * Autor: Átano de Farias Jacinto. 
 * Data: 12/12/2017
 */

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// Realiza a inclusão do arquivo de conexão com o banco.
require_once ('conect.php');

class CheckFuncionario {
	
	public $Teste;
	
	// Método criado para verificar se a data de demissão e maior a data de competencia do pagamento do funcionario. 
	public function CheckDemissao($empresaId, $funcionarioId, $mes, $ano) {

		$query = "SELECT * FROM dados_pagamentos_funcionario p
				WHERE p.empresaId = '".$empresaId."' 
				AND funcionarioId = '".$funcionarioId."'
				AND YEAR(data_referencia) = '".$ano."'
				AND MONTH(data_referencia) >= '".$mes."'";
	
		$consulta = mysql_query($query);
		if( mysql_num_rows($consulta) > 0 ){
			return array('status'=>'ExisteDados');
		}
		
		return array('status'=>'SemDados');
	}
		
	// Método criado para verificar se a data de demissão e maior a data de competencia do pagamento do funcionario. 
	public function checkDataDemissao($funcionarioId, $data) {

		$out = false;
		
		$query = "SELECT * FROM dados_do_funcionario 
			WHERE idFuncionario = '".$funcionarioId."' 
			AND data_demissao <= '".$data."'";
		
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			$out = true;
		}
		
		return $out;
	}
	
	// Método criado para verificar se a data de admissão e menor que a data de competencia do pagamento do funcionário. 
	public function checkDataAdmissao($funcionarioId, $data) {

		$out = false;
		
		$query = " SELECT * FROM dados_do_funcionario 
			WHERE idFuncionario = '".$funcionarioId."' 
			AND data_admissao > '".$data."'";
		
		$consulta = mysql_query($query);		
		if( mysql_num_rows($consulta) > 0 ){
			$out = true;
		}
		
		return $out;
	}
	
	public function CheckPagtoFuncionario($empresaId, $funcionarioId){
		
		$status = false;
		
		$query = "SELECT * FROM `dados_pagamentos_funcionario` WHERE `empresaId` = '".$empresaId."' AND `funcionarioId` = '".$funcionarioId."'";
		
		$resultado = mysql_query($query);
		
		if(mysql_num_rows($resultado) > 0) {
			$status = true;
		}
		
		return $status;
	}
}

// Verifica qual método sera usado.
if(isset($_POST['method']) && !empty($_POST['method'])) {
	
	if($_POST['method'] == 'checkDemissao' ) {
		
		$empresaId = $_POST['empresaId'];
		$funcionarioId = $_POST['funcionarioId'];
		$data = str_replace('/','-', $_POST['data']);
		$mes = date('m', strtotime($data));
		$ano = date('Y', strtotime($data));
				
		// Instância a classe.
		$checkFuncionario = new CheckFuncionario();		
		
		$returnArray = $checkFuncionario->CheckDemissao($empresaId, $funcionarioId, $mes, $ano);
		
		echo json_encode($returnArray);
	}
	
	// Verifica se a competência esta de acordo com a data de demissão e a data admissão.
	if($_POST['method'] == 'checkDataAdmissaoDemissao' ) {
		
		$empresaId = $_POST['empresaId'];
		$funcionarioId = $_POST['funcionarioId'];
		
		if($_POST['statusFerias'] == 'sim'){
			$data = str_replace('/','-', $_POST['data']);
			// Pega a data com o ultimo dia do mês 
			$data1 = date('Y-m-t', strtotime($data)); 
			// Pega a data com o primeiro dia do mes.
			$data2 = date('Y-m-d', strtotime($data)); 		
		} else {			
			$data = str_replace('/','-', $_POST['data']);
			// Pega a data com o ultimo dia do mês 
			$data1 = date('Y-m-t', strtotime("01-".$data)); 
			// Pega a data com o primeiro dia do mes.
			$data2 = date('Y-m-d', strtotime("01-".$data));
		}
		
		// Instância a classe.
		$checkFuncionario = new CheckFuncionario();		
		
		// Verifica se a data admissão.
		$returnAdmissao = $checkFuncionario->checkDataAdmissao($funcionarioId, $data1);
		
		// Verifica se a data demissão.
		$returnDemissao = $checkFuncionario->checkDataDemissao($funcionarioId, $data2);
		
		if($returnAdmissao) {
			$returnArray = array('status'=>'admissao', 'teste'=>'');
		} // Verifica 
		else if($returnDemissao) {
			$returnArray = array('status'=>'demissao', 'teste'=>'');
		} else {
			$returnArray = array('status'=>'ok', 'teste'=>'');
		}
		
		echo json_encode($returnArray);
	}	
	
	if($_POST['method'] == 'CheckPagtoFuncionario' ) {
		
		$empresaId = $_POST['empresaId'];
		$funcionarioId = $_POST['funcionarioId'];
		
		$status = 'sem dados';
				
		// Instância a classe.
		$checkFuncionario = new CheckFuncionario();		
		
		if($checkFuncionario->CheckPagtoFuncionario($empresaId, $funcionarioId)){
			$status = 'com dados';
		};
		
		echo json_encode(array('status'=>$status));
	}
}

?>