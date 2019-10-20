<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

session_start();

require_once "conect.php";
require_once "DataBaseMySQL/TipoDePlano.php";
require_once "DataBaseMySQL/ContratoAceito.php";

// Pega os dados de contas em atraso.
require_once "DataBaseMySQL/HistoricoCobranca.php";

// Pega os dados.
$idUser = $_SESSION['id_userSecaoMultiplo']; //código do usuario. 
$tipoPlano = $_POST['tipo_plano']; // plano
$contadorId = (isset($_POST['contadorId']) && !empty($_POST['contadorId']) ? $_POST['contadorId'] : null ); // código do contador
$valor = $_POST['valor']; // Valor da Assinatura do plano.
$assinatura = $_POST['radPlano']; //pega a assinatura.

//Invoca a classe para manipulacao dos dados relacionado ao tipo do plano. 
$tipoDePlano = new TipoDePlano();

// Invoca a classe manipula os das do historio de cobrançq.
$historioco = new HistoricoCobranca(); 

$vencidaNaoPago = $historioco->pegaHistoricoVencidaNaoPago($idUser);

// Verifica se o cliente não tem pendencias de pagamento 
if(!$vencidaNaoPago) {

	//Realiza a alteracao do tipo do plano em dados da cobrança.
	$tipoDePlano->alteraTipoPlanoAssinatura($tipoPlano, $contadorId, $idUser, $assinatura);
	
	// Caso o tipo do plano seja premiun ele define o contrato com aceito.
	if($tipoPlano == 'P' && !$tipoDePlano->UsuarioEPremio($idUser)) {
		
		$contratoAceito = new ContratoAceito();
		
		if($idUser) {
			
			$contratoId = $_POST['contratoId'];
			
			echo "<pre>";
				print_r($_POST);
			echo "</pre>";
			
			
	echo "passou aqui";		
			// grava contrato aceito.
			echo $contratoAceito->InclirContratoAceito($idUser, $contratoId, $contadorId, $valor);
		}
	}
	
} else {
	
	// Cria uma sessão informando que o cliente possui pedencias de pagamento.
	$_SESSION['pendenciasdepagamento'] = 'pendente';
}

//header('Location: minha_conta.php' );
?>