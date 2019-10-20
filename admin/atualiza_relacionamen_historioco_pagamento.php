<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

class AtualizaRelacionamentoPagamentoHistorico {
		
	function __construct(){
				
		if(isset($_POST['userId']) && isset($_POST['idHistorico']) && isset($_POST['idRelatorioOld']) && isset($_POST['idRelatorio'])){
						
			// 			
			if(is_numeric($_POST['idRelatorioOld']) && $_POST['idRelatorio'] == 'sem' ){
				
				// Pega os dados que auxilia na atualização.
				$idRelatorioOld = $_POST['idRelatorioOld'];
				$idUser = $_POST['userId'];
				
				$this->RemoveIdhistorico($idRelatorioOld, $idUser);
				
			} //Atualiza o id do historico no relatorio de cobrança.
			elseif(is_numeric($_POST['idRelatorioOld']) && is_numeric($_POST['idRelatorio']) && $_POST['idRelatorioOld'] != $_POST['idRelatorio']) {
				
				// Pega os dados que auxilia na atualização.
				$idRelatorioOld = $_POST['idRelatorioOld'];
				$idUser = $_POST['userId'];
				$idRelatorio = $_POST['idRelatorio']; 
				$idHistorico = $_POST['idHistorico'];
								
				// Antes de atualizar id do historico remove o id do historico do pagamento anterior. 
				$this->RemoveIdhistorico($idRelatorioOld, $idUser);
				
				// atualiza o id do historico.
				$this->AtualizaIdHistorico($idRelatorio, $idHistorico, $idUser);
				
			}elseif(empty($_POST['idRelatorioOld']) && is_numeric($_POST['idRelatorio'])) {
				
				// Pega os dados que auxilia na atualização.
				$idUser = $_POST['userId'];
				$idRelatorio = $_POST['idRelatorio']; 
				$idHistorico = $_POST['idHistorico'];				
				
				$this->AtualizaIdHistorico($idRelatorio, $idHistorico, $idUser);
			}
			
			header('Location: /admin/cliente_administrar.php?id='.$_POST['userId']);
		}		
	}
	
	// Função para atualizar id do historico do pagamento. 
	private function AtualizaIdHistorico($idRelatorio, $idHistorico, $idUser) {
		
		include '../conect.php';
		
		$update = "UPDATE relatorio_cobranca SET idHistorico = '".$idHistorico."' WHERE idRelatorio = '".$idRelatorio."' AND id = '".$idUser."';";

		//echo $update;
		
		mysql_query($update) or die (mysql_error());
		
		
	}
	
	// Remove o id do historico de pagamento.
	private function RemoveIdhistorico($idRelatorio, $idUser) {
		
		include '../conect.php';
		
		$update = "UPDATE relatorio_cobranca SET idHistorico = '' WHERE idRelatorio = '".$idRelatorio."' AND id = '".$idUser."';";

		//echo $update;
		
		mysql_query($update) or die (mysql_error());
		
	}
}

$Atualiza =  new AtualizaRelacionamentoPagamentoHistorico();

?>