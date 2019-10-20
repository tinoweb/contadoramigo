<?php

	// pega os dados de pagamento.
	require_once('Model/TokenPagamento/TokenPagamentoData.php');
	
	// Pega os dados do cliente.
	require_once('Model/DadosContratoContadorCliente/DadosContratoContadorClienteData.php');
	
	class CadastroDadosCobrancaCliente {
		
		public function PegaDadosCliente($id_user) {
			
			$cliente = new DadosContratoContadorClienteData(); 
			
			$dadosCliente = $cliente->GetDataDadosCliente($id_user);

			return $dadosCliente;
		}
		
		// Método para definir tags como selecionado. 
		public function selected($value, $prev){
		    return $value == $prev ? ' selected="selected"' : '';
		}
		
		// Pega os dados de pagamento do token.
		public function PegaDadosToken($id_user){
			
			$token = new TokenPagamentoData();
			
			$dados = $token->PegaDadosTokenPagamento($id_user);
			
			return $dados;
		}
		
		//Busca no banco todos os estados e suas siglas para preenchimento de forms
		function getEstados(){
			$arrEstados = array();
			$sql        = "SELECT * FROM estados ORDER BY sigla";
			$result = mysql_query($sql) or die(mysql_error());
			while ($estados = mysql_fetch_array($result)) {
			    array_push($arrEstados, array(
			        'id' => $estados['id'],
			        'sigla' => $estados['sigla']
			    ));
			}
			
			return $arrEstados;
		}
	}
?>
