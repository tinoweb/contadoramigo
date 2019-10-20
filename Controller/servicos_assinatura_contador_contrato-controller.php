<?php
/**
 * Classe Responsavel pelas ações do usuário.
 * autor: Atano de Farias Jacinto
 * Date: 16/03/2017
 */
require_once('Model/DadosContratoContadorCliente/DadosContratoContadorClienteData.php');

// Realiza a inclusão classe .
require_once('classes/numero_extenso.php'); 

class ServicosAssinaturaContadorContrato {
	
	
	public function pegaDadosParaContrato($userId){
		
		$dadoscontador = false;
		
		$objectData = new DadosContratoContadorClienteData(); 
		
		// Pega os dados do usuário
		$dadosUsuario = $objectData->GetDataDadosCliente($userId);

		$dados = "";

		// Verifica se houve retorno dos dados do usuário.
		if($dadosUsuario) {

			$dados['cliente'] = $dadosUsuario;
			
			// Verifica se o estado foi informado.
			if($dadosUsuario->getUF()) {
				
				// Pega os dados do contadores referente ao estado do cliente.
				//$dadoscontador = $objectData->GetDataDadosContadorUF($dadosUsuario->getUF());	
				
				// condiçao temporaria: A variável foi setada como falsa para todos os serviços irem para o carlos e não verificar o estado.
				$dadoscontador = false;
				
				// Caso não sejá encontrado o contador do estado.
				if(!$dadoscontador) {				

					// Passa direto para o id do contador informado no método.
					$dadoscontador = $objectData->GetDataDadosContadorId(9);
				}
				
				$dados['contador'] = $dadoscontador;
			}
		}
		
		return $dados;	

	}
	
	public function geraLinkparaboleto() {
		
		$out = "<input id='statusInput' type='hidden' value='NO' />";
		
		if(isset($_SESSION['tipo']) && !empty($_SESSION['tipo']) && isset($_SESSION['valor']) && !empty($_SESSION['valor']) &&isset($_SESSION['data']) && !empty($_SESSION['data']) && isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])) {
			
			$tipo = $_SESSION['tipo']; 
			$valor = $_SESSION['valor']; 
			$data = $_SESSION['data'];
			$idUser = $_SESSION['id_user'];
			
			$out = "<input type='hidden' name='tipo' value='".$tipo."' />"
				  ."<input type='hidden' name='valor' value='".$valor."' />"
				  ."<input type='hidden' name='data' value='".$data."' />"
				  ."<input type='hidden' name='id_user' value='".$idUser."' />"
				  ."<input id='statusInput' type='hidden' value='OK' />";
			
		}
		
		return $out;
	}
	
	// Pega o o serviço 
	public function getServicoTexto() {
			
		$out = "";
		
		if(isset($_SESSION['tipo']) && !empty($_SESSION['tipo'])) {
			switch($_SESSION['tipo']){
				case 'A_empresa':
					$out = "Preenchimento do requerimento de abertura ou alteração de empresa na Junta Comercial, bem como a elaboração do DBE correspondente.";
					break;
				case 'A_sociedade':
					$out = "Preenchimento do requerimento de abertura ou alteração de sociedade na Junta Comercial, bem como a elaboração do DBE correspondente.";
					break;									
				case 'decore':
					$out = "Elaboração de DECORE - Declaração Comprobatória de Percepção de Rendimentos.";
					break;
				case 'DBE':
					$out = "Elaboração de DBE - Documento Básico de Entrada.";
					break;
				case 'Gfip_GPS':
					$out = "Envio da Gfip e geração da GPS.";
					break;
				case 'Simples_DAS':
					$out = "Apuração do Simples e geração da DAS.";
					break;
				case 'Defis':
					$out = "Preechimento e transmição Defis.";
					break;	
				case 'Rais_negativa':
					$out = "Preechimento e transmição Rais negativa.";
					break;			
				case 'Dirf':
					$out = "Preechimento e transmição Dirf.";
					break;	
				case 'MEI-ME':
					$out = "Transformação de MEI em ME.";
					break;		
				case 'F_empresa':
					$out = "Fechamento de firma individual.";
					break;	
				case 'F_sociedade':
					$out = "Fechamento de sociedade empresária.";
					break;
				case 'CPOM':
					$out = "Cadastro no CPOM.";
					break;
				case 'funcionario_C_D':
					$out = "Contratação e demissão funcionário";
					break;
				case 'regularizacao_emp';
					$out = "Regularização de empresa optante pelo Simples, sem funcionários.";
					break;
				case 'DCTF';
					$out = "Preenchimento e envio da declaração de débitos e créditos tributários federais.";
					break;
				case 'DeSTDA';
					$out = "Preenchimento e envio da declaração de substituição tributária, diferencial de alíquota e antecipação.";
					break;
				case 'IRPF';
					$out = "Declaração de ajuste do imposto de renda da pessoa física.";
					break;
			}	
		}
		
		return $out;
	}
	
	// formata o valor pago para o contratado.
	public function getValorPagoContratado(){
		
		$out =  'R$ '.number_format($_SESSION['valor'], 2,',','.').' ('.GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_SESSION['valor'])),2,"","")).')';
		
		return $out;
	}
	
	// Método que define uma formatacao para data. Ex: 01 de janeiro de 2020
	public function DataAtuala() {
		
		switch(date("m")) {
			case 1:
				$mes = 'Janeiro';
				break;
			case 2:
				$mes = 'Fevereiro';
				break;
			case 3:
				$mes = 'Março';
				break;
			case 4:
				$mes = 'Abril';
				break;
			case 5:
				$mes = 'Maio';
				break;
			case 6:
				$mes = 'Junho';
				break;
			case 7:
				$mes = 'Julho';
				break;
			case 8:
				$mes = 'Agosto';
				break;
			case 9:
				$mes = 'Setembro';
				break;
			case 10:
				$mes = 'Outubro';
				break;
			case 11:
				$mes = 'Novembro';
				break;
			case 12:
				$mes = 'Dezembro';
				break;			
		}
		
		
		$ano = date("Y");
		$dia = date("d");
	
		return $dia." de ".$mes." de ".$ano ;
	}
}
?>