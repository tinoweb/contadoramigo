<?php 
/**
 * Autor: Átano de Farias Jacinto
 * data: 04/05/2017
 */
 
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL); 
 
session_start();

require_once('../DataBasePDO/ServicoAvulso.php');
require_once('../DataBasePDO/CobrancaContador.php');
	
// Chama o método responsavel. 
if(isset($_POST['method']) && !empty($_POST['method'])){
	
	$metodo = $_POST['method'];
	
	// Chama o método informado no ajax.
	echo $metodo();	
}
	
// Método criado para atualizar o status.
function AtualizaStatusServicoAvulso(){	
	
	// Verifica se o status e o id da linha do pagamento foi informado.
	if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['status']) && !empty($_POST['status'])){
		
		// pega o Id
		$id = $_POST['id'];
		
		// Pega o status.
		$status = $_POST['status']; 	
		
		$servicoAvulso = new ServicoAvulso(); 
		
		$servicoAvulso->atualizaStatus($id, $status);
		
		return  json_encode(array( 'status' => $status ));
	}
}

// Método criado para atualizar o Link da Nota Fiscal Eletronica.
function AtualizaLinkNFE(){
	
	if(isset($_POST['cobrancaContadorId']) && !empty($_POST['cobrancaContadorId']) && isset($_POST['linkNFE'])){
		
		$id = $_POST['cobrancaContadorId']; 
		$linkNFE = $_POST['linkNFE'];
		
		$cobrancaContador = new CobrancaContador(); 
		
		$cobrancaContador->AtualizaLinqueNFE($id, $linkNFE);
		
		return  json_encode(array( 'linkNFE' => $linkNFE ));
	}
}

function atualizaBola(){
		
	if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['statusBola']) && !empty($_POST['statusBola'])){
		
		$id = $_POST['id'];
		$statusBola = $_POST['statusBola'];		
		
		$servicoAvulso = new ServicoAvulso();
		
		$servicoAvulso->atualizaStatusBola($id, $statusBola);
		
		return json_encode(array('statusBola' => $statusBola));
	}
	
}

?>
