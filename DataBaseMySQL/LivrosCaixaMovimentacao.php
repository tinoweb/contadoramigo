<?php
/**
 *	Classe criada para manipular os dados dos emprestimos.
 *	Data: 01/03/2018
 */
class LivrosCaixaMovimentacao {
	
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
	
	// Pega a linha de emprestimo no livro caixa.
	public function PegalancamentoDeAcordoIdIlinha($empresaId, $id) {
	
		$row = false;
		
		$sql = "SELECT * FROM user_".$empresaId."_livro_caixa WHERE id='".$id."'";

		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$row = mysql_fetch_array($resultado);
		}
		
		return $row;
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
		
		$consulta = "SELECT * FROM dados_do_emprestimo 
					WHERE empresaId = '".$empresaId."' 
					AND livro_caixa_id = '".$livroCaixaId."'
					AND livro_caixa_id > 0";
				
		$resultado = mysql_query($consulta);
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}
	
	// Pega a linha do empréstimo relacionada com o livro caixa.
	public function PegaLinhaEmprestimoRelacionadaComLivroCaixa2($empresaId, $livroCaixaId){
		
		$rows = false;
		
		$consulta = "SELECT * FROM dados_do_emprestimo 
					WHERE empresaId = '".$empresaId."' 
					AND livro_caixa_id = '".$livroCaixaId."'
					AND livro_caixa_id > 0
					AND tipo = 'emprestimo'";
				
		$resultado = mysql_query($consulta);
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}
	
	// Pega a linha do empréstimo relacionada com o livro caixa.
	public function PegaLinhaAmortizacaoEmprestimo($empresaId, $livroCaixaId){
		
		$rows = false;
		
		$consulta = "SELECT * FROM dados_do_emprestimo 
					WHERE empresaId = '".$empresaId."' 
					AND livro_caixa_id = '".$livroCaixaId."'
					AND livro_caixa_id > 0
					AND tipo = 'amortizacao'";
				
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
	
	public function lancamentoContasReceber ($empresaId, $insert_id, $categoria_secundaria){
		
		$insert = "INSERT INTO lancamento_contas_pagar_receber (empresaId, livro_caixa_id, livro_caixa_id_pagamento, data_inclusao, categoria_secundaria_2, tipo) VALUES ('".$empresaId."', '".$insert_id."', NULL , NOW(), '".$categoria_secundaria."', 'contas a receber')";
						
		// Execulta a inclusão dos dados.	
		mysql_query($insert);
			
	}
	
	// Pega a data do Lançamento de contas a receber
	public function PegaListaLancamentoContasAPagar($empresaId, $categoria) {
		
		$rows = false;

		$sql = " SELECT lancamento.*, receber.* FROM lancamento_contas_pagar_receber lancamento 
				LEFT JOIN user_".$empresaId."_livro_caixa receber ON receber.id = lancamento.livro_caixa_id
				LEFT JOIN user_".$empresaId."_livro_caixa pago ON pago.id = lancamento.livro_caixa_id_pagamento 
				WHERE lancamento.tipo = 'contas a pagar'
				AND receber.categoria = '".$categoria."' 
				AND pago.id IS NULL";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}	
	
	// Pega a data do Lançamento de contas a receber
	public function PegaDataLancamentoContasAPagar($empresaId, $livroCaixaId) {
		
		$rows = false;

		$sql = "SELECT * FROM lancamento_contas_pagar_receber l 
				LEFT JOIN user_".$empresaId."_livro_caixa u ON u.id = l.livro_caixa_id
				WHERE l.tipo = 'contas a pagar'
				AND l.livro_caixa_id_pagamento = '".$livroCaixaId."'
				AND l.livro_caixa_id_pagamento > 0";

		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}	
	
	// Pega a data do Lançamento de contas a receber
	public function PegaListaLancamentoContasAReceber($empresaId, $categoria) {
		
		$rows = false;

		$sql = " SELECT lancamento.*, receber.* FROM lancamento_contas_pagar_receber lancamento 
				LEFT JOIN user_".$empresaId."_livro_caixa receber ON receber.id = lancamento.livro_caixa_id
				LEFT JOIN user_".$empresaId."_livro_caixa pago ON pago.id = lancamento.livro_caixa_id_pagamento 
				WHERE lancamento.tipo = 'contas a receber'
				AND receber.categoria = '".$categoria."'
				AND pago.id IS NULL";

		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			while($array = mysql_fetch_array($resultado)){
				$rows[] = $array ;
			}
		}
		
		return $rows;
	}
	
	// Pega a data do Lançamento de contas a receber
	public function PegaDataLancamentoContasAReceber($empresaId, $livroCaixaId) {
		
		$rows = false;

		$sql = "SELECT * FROM lancamento_contas_pagar_receber l 
				LEFT JOIN user_".$empresaId."_livro_caixa u ON u.id = l.livro_caixa_id
				WHERE l.tipo = 'contas a receber'
				AND l.livro_caixa_id_pagamento = '".$livroCaixaId."'
				AND l.livro_caixa_id_pagamento > 0";
	
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}
	
	// Pega a data do Lançamento de contas a receber ou apagar
	public function PegaLancamentoContasAReceberUoAPagar($empresaId, $livroCaixaId) {
		
		$rows = false;

		$sql = "SELECT * FROM lancamento_contas_pagar_receber l 
				LEFT JOIN user_".$empresaId."_livro_caixa u ON u.id = l.livro_caixa_id
				WHERE l.livro_caixa_id_pagamento = '".$livroCaixaId."'
				AND l.livro_caixa_id_pagamento > 0";
	
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
	}	
	
	// Pega a data do Lançamento de contas a receber
	public function PegaLancamentoContasAPagar($empresaId, $livroCaixaId) {
		
		$rows = false;

		$sql = "SELECT * FROM lancamento_contas_pagar_receber 
				WHERE empresaId = '".$empresaId."' 
				AND livro_caixa_id = '".$livroCaixaId."'
				AND livro_caixa_id > 0
				AND tipo = 'contas a pagar'";
		
		$resultado = mysql_query($sql) or die (mysql_error());
		
		if( mysql_num_rows($resultado) > 0 ){
			$rows = mysql_fetch_array($resultado);
		}
		return $rows;
	}
	
	// Método para pegar os clientes
	public function PegaDadosContasAReceber($empresaId, $periodoAno, $periodoMes) {
	
		$rows = false;				
		
		$query = " SELECT * FROM user_".$empresaId."_livro_caixa "
				." WHERE MONTH(data) =  '".$periodoMes."' AND YEAR(data) = '".$periodoAno."' "
				." ORDER BY data DESC";		

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
		
		$query = "SELECT data FROM user_".$empresaId."_livro_caixa ORDER BY data DESC LIMIT 1";
		
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
		
		$query = "SELECT data FROM user_".$empresaId."_livro_caixa ORDER BY data ASC LIMIT 1";
		
		// Executa a busca
		$resultado = mysql_query($query);
		
		if(mysql_num_rows($resultado) > 0){
			$rows = mysql_fetch_array($resultado);
		}
		
		return $rows;
		
	}
	
	// Método criado para pegar o apelido dos clientes
	public function PegaApelidoCliente($userIdSecaoMultiplo){
		
		$rows = false;
		
		$query = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = ".$userIdSecaoMultiplo." AND status = 'A' ORDER BY apelido";

		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;
	}
	
	// Pega os dados dos estagiarios.
	public function PegaListaEstagiarios($empresaId) {
		
		$rows = false;
		
		$query = "SELECT * FROM estagiarios WHERE id_login = '".$empresaId."'";

		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;
		
	}
	
	// Pega a lista de Pgto. a autônomos e fornecedores
	public function PegaListaAutonomos($empresaId, $id) {
		
		$rows = false;
		
		$query = "SELECT id,id_login,nome,cpf FROM dados_autonomos WHERE  dados_autonomos.id_login = '".$empresaId."' UNION SELECT id,id_login,nome,cnpj FROM dados_pj WHERE id = '".$id."' AND dados_pj.id_login = '".$empresaId."' order by nome";

		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;
		
	}
	
	// Pega a lista de Pagto. de salários
	public function PegaListaFuncionarios($empresaId) {

		$rows = false;
		
		$query = "SELECT * FROM dados_do_funcionario WHERE id = '".$empresaId."'";

		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;		
		
	}
	
	// Pega lista de Pró-Labore
	public function PegaListaResponsavel($empresaId) {

		$rows = false;
		
		$query = "SELECT * FROM dados_do_responsavel WHERE id = '".$empresaId."'";

		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0) {
			while($linha = mysql_fetch_array($resultado)){
				$rows[] = $linha;
			}
		}
		return $rows;		
		
	}
	
	// Pega lista de Pró-Labore
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
}