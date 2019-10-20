<?php
/**
 *	Classe criada para manipular os dados dos emprestimos.
 *	Data: 23/01/2018
 */
class LivroCaixa {
	
	// Método criado para pegar os dados dos emprestimos.
	public function PegaMenorAno($emprestimo) {
		
		$rows = false;

		$sql = " SELECT  YEAR(MIN(data_emprestimo)) as ano FROM dados_do_emprestimo 
				 WHERE empresaId = ".$emprestimo." 
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
	
	// Pega a data do Lançamento duplo
	public function PegaDataLancamentoDuplo($empresaId, $livroCaixaId) {
		
		$rows = false;

		$sql = "SELECT * FROM lancamento_duplo_livro_caixa WHERE empresaId = '".$empresaId."' AND livro_caixa_id = '".$livroCaixaId."'";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}
	
	public function PegaListaClienteDaEmpresa($empresaId) {

		$rows = false;

		$sql = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".$empresaId." AND status = 'A' ORDER BY apelido";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array['apelido'] ;
			}
		}
		
		return $rows;
	}
	
	// Método criado para fazer a inclusão de dados no livro caixa do cliente
	public function InclusaoNoLivroCaixa($empresaId, $data, $entrada, $saida, $documento, $descricao, $categoria) {
		 
		$insert = "INSERT INTO user_".$empresaId."_livro_caixa (id, data, entrada, saida, documento_numero, descricao, categoria) "
			."VALUES (NULL, '".$data."', '".$entrada."', '".$saida."', '".$documento."', '".$descricao."', '".$categoria."')";		
		
				
		// Execulta a inclusão dos dados.	
		mysql_query($insert) or die(mysql_error());
	
		return mysql_insert_id();
	}
	
	public function lancamentoContasReceber ($empresaId, $insert_id, $categoria_secundaria, $vencimento){
		
		$insert = "INSERT INTO lancamento_contas_pagar_receber (empresaId, livro_caixa_id, livro_caixa_id_pagamento, data_inclusao, categoria_secundaria_1, tipo, vencimento) VALUES ('".$empresaId."', '".$insert_id."', NULL , NOW(), '".$categoria_secundaria."', 'contas a receber', '".$vencimento."')";
						
		// Execulta a inclusão dos dados.	
		mysql_query($insert) or die(mysql_error());
	
		return mysql_insert_id();
			
	}
	
	// Método para pegar os clientes
	public function PegaDadosContasAReceber($empresaId, $periodoAno, $periodoMes) {
	
		$rows = false;
		
		$condicaoMes = '';
		
		if($periodoMes != 'todos'){
			$condicaoMes = " AND MONTH(data) = '".$periodoMes."'";
		}
		
		$query = "SELECT ulc.id, ulc.data, ulc.entrada, ulc.descricao, ulc.categoria, ulc.documento_numero FROM lancamento_contas_pagar_receber lcpr "
		." JOIN user_".$empresaId."_livro_caixa ulc ON ulc.id = lcpr.livro_caixa_id "
		." WHERE tipo = 'contas a receber'"
		.$condicaoMes 
		." AND YEAR(data) = '".$periodoAno."'" 
		." ORDER BY data DESC ";			

		// Executa a busca
		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;		
	}
	
	// Método para pegar a data do último registro na tabela
	public function pegaDadosContasAReceberUltimo($empresaId){
		
		$rows = false;
		
		$query = "SELECT ulc.data FROM lancamento_contas_pagar_receber lcpr"
				." JOIN user_".$empresaId."_livro_caixa ulc ON ulc.id = lcpr.livro_caixa_id "
				." WHERE tipo = 'contas a receber' "
				." ORDER BY ulc.data DESC LIMIT 1";		
		
		
		// Executa a busca
		$resultado = mysql_query($query);
		
		if(mysql_num_rows($resultado) > 0){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;						
						
	}
	
	// Método para pegar o ano do primeiro lançamento
	public function pegaDadosContasAReceberPrimeiro($empresaId){
		
		$rows = false;
		
		$query = "SELECT ulc.data FROM lancamento_contas_pagar_receber lcpr"
				." JOIN user_".$empresaId."_livro_caixa ulc ON ulc.id = lcpr.livro_caixa_id "
				." WHERE tipo = 'contas a receber' "
				." ORDER BY ulc.data ASC LIMIT 1";				
		
		// Executa a busca
		$resultado = mysql_query($query);
		
		if(mysql_num_rows($resultado) > 0){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
		
	}
			
	// Método para pegar a data do último registro na tabela
	public function pegaDadosContasAPagarUltimo($empresaId){
		
		$rows = false;
		
		$query = "SELECT ulc.data FROM lancamento_contas_pagar_receber lcpr"
				." JOIN user_".$empresaId."_livro_caixa ulc ON ulc.id = lcpr.livro_caixa_id "
				." WHERE tipo = 'contas a pagar' "
				." ORDER BY ulc.data DESC LIMIT 1";				
		
		// Executa a busca
		$resultado = mysql_query($query);
		
		if(mysql_num_rows($resultado) > 0){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;						
						
	}
	
	// Método para pegar o ano do primeiro lançamento
	public function pegaDadosContasAPagarPrimeiro($empresaId){
		
		$rows = false;
		
		$query = "SELECT ulc.data FROM lancamento_contas_pagar_receber lcpr"
				." JOIN user_".$empresaId."_livro_caixa ulc ON ulc.id = lcpr.livro_caixa_id "
				." WHERE tipo = 'contas a pagar' "
				." ORDER BY ulc.data ASC LIMIT 1";		
		
		// Executa a busca
		$resultado = mysql_query($query);
		
		if(mysql_num_rows($resultado) > 0){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
		
	}
	
	// Método para pegar os clientes
	public function PegaDadosContasAPagar($empresaId, $periodoAno, $periodoMes) {
	
		$rows = false;
		
		if($periodoMes != 'todos'){
			$condicaoMes = " AND MONTH(data) = '".$periodoMes."'";
		}
		
		$query = "SELECT ulc.id, ulc.data, ulc.saida, ulc.descricao, ulc.categoria, ulc.documento_numero FROM lancamento_contas_pagar_receber lcpr "
			." JOIN user_".$empresaId."_livro_caixa ulc ON ulc.id = lcpr.livro_caixa_id "
			." WHERE tipo = 'contas a pagar'"
			.$condicaoMes 
			." AND YEAR(data) = '".$periodoAno."' "
			." ORDER BY data DESC ";				

		// Executa a busca
		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;		
	}
	
	public function lancamentoContasPagar ($empresaId, $insert_id, $categoria_secundaria, $vencimento){
		
		$insert = "INSERT INTO lancamento_contas_pagar_receber (empresaId, livro_caixa_id, livro_caixa_id_pagamento, data_inclusao, categoria_secundaria_1, tipo, vencimento) VALUES ('".$empresaId."', '".$insert_id."', NULL , NOW(), '".$categoria_secundaria."', 'contas a pagar', '".$vencimento."')";
						
		// Execulta a inclusão dos dados.	
		mysql_query($insert);
		
		return mysql_insert_id();
	}
	
	public function excluiContasAPagarLivroCaixa($id, $empresaId){
		$query = "DELETE FROM user_".$empresaId."_livro_caixa WHERE id = ".$id."";
		
		// Executa a exclusão dos dados			
		mysql_query($query) or die(mysql_error());
	
	}
	
	public function excluiContasAPagarReceber($idLivroCaixa, $empresaId){
		$query = "DELETE FROM lancamento_contas_pagar_receber WHERE empresaId = ".$empresaId." AND livro_caixa_id = ".$idLivroCaixa."";
		
		// Executa a exclusão dos dados			
		mysql_query($query) or die(mysql_error());
	}
	
	// Verifica se o id 'livro_caixa_lancamento_id' esta preenchido 
	public function idLancamentoExistente($empresaId, $idLivroCaixa){
		
		$rows = false;
		
		$query = "SELECT lcpr.livro_caixa_id_pagamento FROM lancamento_contas_pagar_receber lcpr "
				." JOIN user_".$empresaId."_livro_caixa ulc ON ulc.id = lcpr.livro_caixa_id_pagamento"
				." WHERE lcpr.livro_caixa_id_pagamento IS NOT NULL "
				." AND lcpr.livro_caixa_id = ".$idLivroCaixa.""
				." AND lcpr.empresaId = ".$empresaId."";		
										
		$resultado = mysql_query($query);
		
		if(mysql_num_rows($resultado) > 0){
			$rows = mysql_fetch_array($resultado);
		}
				
		return $rows;
	}
	
	// Pega os dados do sócio de acordo com o seu ID.
	public function PegaDadosSocio($idSocio, $empresaId){
		
		$rows = '';
		
		$query = " SELECT * FROM dados_do_responsavel WHERE idSocio = '".$idSocio."' AND id = '".$empresaId."'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}	
	
	// Pega os dados do funcionário de acordo com o seu ID.
	public function PegaDadosFuncionario($idFuncionario, $empresaId){
		
		$rows = '';
		
		$query = "SELECT * FROM dados_do_funcionario WHERE idFuncionario ='".$idFuncionario."' AND id = '".$empresaId."'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
	
	// Pega os dados do fornecedor ou do autônomos de acordo com o seu ID.
	public function PegaDadosAutonomos($id, $empresaId){
		
		$rows = '';
		
		$query = "SELECT id,id_login,nome,cpf FROM dados_autonomos WHERE id = '".$id."' AND dados_autonomos.id_login = '".$empresaId."' UNION SELECT id,id_login,nome,cnpj FROM dados_pj WHERE id = '".$id."' AND dados_pj.id_login = '".$empresaId."' order by nome";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
	
	// Pega os dados do stagiario de acordo com o seu ID.
	public function PegaDadosEstagiarios($id, $empresaId){
		
		$rows = '';
		
		$query = "SELECT * FROM estagiarios WHERE id_login = '".$empresaId."' AND id = '".$id."'";

		$consulta = mysql_query($query);	
		
		if( mysql_num_rows($consulta) > 0 ){
			$rows = mysql_fetch_array($consulta);
		}
			
		return $rows;
	}
	
	public function Comprovantes($id, $empresaId) {
			
		$rows = false;
		
		$query = "SELECT * FROM comprovantes WHERE id_lancamento = '".$id."' AND id_user = '".$empresaId."' ";

		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;		
		
	}
	
	public function InclusaoComprovantes($empresaId, $last_id, $file) {
						
		$insert = "INSERT INTO `comprovantes`(`id_user`,`id_lancamento`, `nome`, `data`) VALUES ('".$empresaId."','".$last_id."','".$file."',NOW() )"; 
		
		// Execulta a inclusão dos dados.	
		mysql_query($insert) or die(mysql_error());
	
		return mysql_insert_id();		
		
	}
	
	public function ExcluirComprovantes($id, $empresaId) {
		
		$delete = "DELETE FROM comprovantes WHERE id = '".$id."' AND id_user = '".$empresaId."'";
		
		mysql_query($delete) or die(mysql_error());
		
	}
	
	// Pega a data do Lançamento de contas a receber ou apagar
	public function PegaPagamentoDeContasAReceberUoAPagar($empresaId, $livroCaixaId) {
		
		$rows = false;

		$sql = "SELECT * FROM lancamento_contas_pagar_receber l 
				LEFT JOIN user_".$empresaId."_livro_caixa u ON u.id = l.livro_caixa_id_pagamento
				WHERE l.livro_caixa_id = '".$livroCaixaId."'
				AND l.livro_caixa_id > 0";
	
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}	
}