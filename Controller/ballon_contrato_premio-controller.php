<?php
/**
 * Classe de controle para o contrato premio 
 * autor: Atano de Farias Jacinto
 * Date: 22/03/2017
 */
require_once('Model/DadosContratoContadorCliente/DadosContratoContadorClienteData.php');

class BallonContratoPremioController {
	
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
				
				$dadoscontador = false;
				
				// Pega os dados do contadores referente ao estado do cliente.
				//$dadoscontador = $objectData->GetDataDadosContadorUF($dadosUsuario->getUF());
				
				// Condição temporaria: passa os cliente para o willian; 
//				if($dadosUsuario->getUF() == 'SP') {
//					// Pega os dados do contadores referente ao estado do cliente.
//					$dadoscontador = $objectData->GetDataDadosContadorUF($dadosUsuario->getUF());		
//				}
				$dadoscontador = false;				
				
				// Caso não seja encontrado o ele passa para o id do adinaldo. 
				if(!$dadoscontador) {				
					
					// Pega os dados do contadora Yasmim = id 11.
					$dadoscontador = $objectData->GetDataDadosContadorId(13);
				}
				
				$dados['contador'] = $dadoscontador;
			}
		}
		
		return $dados;	

	}
	
	public function geraLinkparaboleto() {
		
		$out = "<input id='statusInput' type='hidden' value='NO' />";
		
		if(isset($_GET['tipo']) && !empty($_GET['tipo']) && isset($_GET['valor']) && !empty($_GET['valor']) &&isset($_GET['data']) && !empty($_GET['data']) && isset($_GET['id_user']) && !empty($_GET['id_user'])) {
			
			$tipo = $_GET['tipo']; 
			$valor = $_GET['valor']; 
			$data = $_GET['data'];
			$idUser = $_GET['id_user'];
			
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
		
		if(isset($_GET['tipo']) && !empty($_GET['tipo'])) {
			switch($_GET['tipo']){
				case 'AbertAltEmpresa':
					$out = "Preenchimento do requerimento de abertura ou alteração de empresa na Junta Comercial, bem como a elaboração do DBE correspondente.";
					break;
				case 'AbertAltSociedade':
					$out = "Preenchimento do requerimento de abertura ou alteração de sociedade na Junta Comercial, bem como a elaboração do DBE correspondente.";
					break;									
				case 'decore':
					$out = "Elaboração de DECORE - Declaração Comprobatória de Percepção de Rendimentos.";
					break;
				case 'DBE':
					$out = "Elaboração de DBE - Documento Básico de Entrada.";
					break;
			}	
		}
		
		return $out;
	}
	
	// formata o valor pago para o contratado.
	public function getValorPagoContratado(){
		$out =  'R$ '.number_format($_GET['valor'], 2,',','.').' ('.GExtenso::moeda(number_format(str_replace(",",".",str_replace(".","",$_GET['valor'])),2,"","")).')';
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
