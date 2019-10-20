<?php
/**
 *	Classe criada para manipular os dados dos emprestimos.
 *	Data: 23/01/2018
 */
class Emprestimos {
	
	// Método criado para pegar os dados dos emprestimos.
	public function PegaMenorAno($empresaId) {
		
		$rows = false;

		$sql = " SELECT  YEAR(MIN(data_emprestimo)) as ano FROM dados_do_emprestimo 
				 WHERE empresaId = ".$empresaId." 
				 AND apelido IS NOT NULL 
				 AND tipo = 'emprestimo'";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}
	
	// Método criado para pegar os dados dos emprestimos.
	public function PegaMaiorAno($empresaId) {
		
		$rows = false;

		$sql = " SELECT  YEAR(MAX(data_emprestimo)) as ano FROM dados_do_emprestimo 
				 WHERE empresaId = ".$empresaId." 
				 AND apelido IS NOT NULL 
				 AND tipo = 'emprestimo'";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}	
	
	// Método criado para pegar os dados dos emprestimos.
	public function PegaEmprestimos($emprestimo, $ano) {
		
		$rows = false;

		$sql = " SELECT * FROM dados_do_emprestimo 
				 WHERE empresaId = ".$emprestimo." 
				 AND apelido IS NOT NULL AND tipo = 'emprestimo'
				 AND YEAR(data_emprestimo) = ".$ano."
				 ORDER BY data_emprestimo DESC, apelido ASC;";
	
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}
	
	// Método criado para pegar os dados dos emprestimos Complementar.
	public function PegaEmprestimosComplementar($emprestimo, $registroPaiId) {
		
		$rows = false;

		$sql = "SELECT * FROM dados_do_emprestimo 
				WHERE empresaId = ".$emprestimo." 
				AND tipo = 'emprestimo' 
				AND registroPaiId = ".$registroPaiId."
				ORDER BY data_emprestimo ASC;";
	
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}	
		
	// Método criado para pegar as amortizações dos emprestimos.
	public function PegaAmortizacaoEmprestimos($emprestimo, $registroPaiId) { 

		$rows = false;

		$sql = "SELECT * FROM dados_do_emprestimo 
				WHERE empresaId = ".$emprestimo." 
				AND tipo = 'amortizacao' 
				AND registroPaiId = ".$registroPaiId."
				ORDER BY data_emprestimo ASC;";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}
	
	// Pega a linha do empréstimo relacionada com o livro caixa.
	public function PegaLinhaEmprestimoRelacionadaComLivroCaixa($empresaId, $livroCaixaId){
		
		$rows = false;
		
		$consulta = "SELECT * FROM dados_do_emprestimo WHERE empresaId = '".$empresaId."' AND livro_caixa_id = '".$livroCaixaId."'";
				
		$resultado = mysql_query($consulta);
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}
	
	// Pega o emprestimo ou amortização pelo Id.
	public function PegaEmprestimoAmortizacaoPorId($emprestimoId){
		
		$rows = false;
		
		$consulta = "SELECT * FROM dados_do_emprestimo WHERE emprestimoId = '".$emprestimoId."'";
				
		$resultado = mysql_query($consulta);
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}
	
	// Pega uma lista de emprestimos principais do cliente.
	public function PegaListaDeEmprestimos($empresaId) {
		
		$rows = false;

		$sql = "SELECT * FROM dados_do_emprestimo 
				WHERE empresaId = '".$empresaId."' 
				AND apelido IS NOT NULL 
				AND tipo = 'emprestimo'";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}

}