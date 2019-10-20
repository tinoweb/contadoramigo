<?php
/**
 * Classe criada para manipular os dados do balanço patrimonial.
 */
class BalancoPatrimonial {
	
	// Método criado para realizar a inclusão dos dados do balanco patrimonial
	public function InclusaoDadosBalancoPatrimonial( $empresaId, $ano, $a_c_caixa_equivalente_caixa, $a_c_contas_receber, $a_c_estoques, $a_c_outros_creditos, $a_c_despesas_exercicio_seguinte, $a_c_total, $a_n_c_contas_receber, $a_n_c_investimentos, $a_n_c_imobilizado, $a_n_c_intangivel, $a_n_c_depreciacao, $a_n_c_total, $p_c_fornecedores, $p_c_emprestimos_bancarios, $p_c_obrigacoes_sociais_impostos, $p_c_contas_pagar, $p_c_lucros_distribuir, $p_c_provisoes, $p_c_total, $p_n_c_contas_pagar, $p_n_c_financiamentos_bancarios, $p_n_c_total, $p_l_capital_social, $p_l_reservas_capital, $p_l_ajustes_avaliacao_patrimonial, $p_l_reservas_lucro, $p_l_lucros_acumulados, $p_l_prejuizos_acumulados, $p_l_total ) {
		
		$insert = "INSERT INTO balanco_patrimonial (id_user
						,ano
						,a_c_caixa_equivalente_caixa
						,a_c_contas_receber
						,a_c_estoques
						,a_c_outros_creditos
						,a_c_despesas_exercicio_seguinte
						,a_c_total
						,a_n_c_contas_receber
						,a_n_c_investimentos
						,a_n_c_imobilizado
						,a_n_c_intangivel
						,a_n_c_depreciacao
						,a_n_c_total
						,p_c_fornecedores
						,p_c_emprestimos_bancarios
						,p_c_obrigacoes_sociais_impostos
						,p_c_contas_pagar
						,p_c_lucros_distribuir
						,p_c_provisoes
						,p_c_total
						,p_n_c_contas_pagar
						,p_n_c_financiamentos_bancarios
						,p_n_c_total
						,p_l_capital_social
						,p_l_reservas_capital
						,p_l_ajustes_avaliacao_patrimonial
						,p_l_reservas_lucro
						,p_l_lucros_acumulados
						,p_l_prejuizos_acumulados
						,p_l_total
					) VALUES ('".$empresaId."'
						,'".$ano."'
						,'".$a_c_caixa_equivalente_caixa."'
						,'".$a_c_contas_receber."'
						,'".$a_c_estoques."'
						,'".$a_c_outros_creditos."'
						,'".$a_c_despesas_exercicio_seguinte."'
						,'".$a_c_total."'
						,'".$a_n_c_contas_receber."'
						,'".$a_n_c_investimentos."'
						,'".$a_n_c_imobilizado."'
						,'".$a_n_c_intangivel."'
						,'".$a_n_c_depreciacao."'
						,'".$a_n_c_total."'
						,'".$p_c_fornecedores."'
						,'".$p_c_emprestimos_bancarios."'
						,'".$p_c_obrigacoes_sociais_impostos."'
						,'".$p_c_contas_pagar."'
						,'".$p_c_lucros_distribuir."'
						,'".$p_c_provisoes."'
						,'".$p_c_total."'
						,'".$p_n_c_contas_pagar."'
						,'".$p_n_c_financiamentos_bancarios."'
						,'".$p_n_c_total."'
						,'".$p_l_capital_social."'
						,'".$p_l_reservas_capital."'
						,'".$p_l_ajustes_avaliacao_patrimonial."'
						,'".$p_l_reservas_lucro."'
						,'".$p_l_lucros_acumulados."'
						,'".$p_l_prejuizos_acumulados."'
						,'".$p_l_total."');";

		// Execulta a inclusão dos dados.
		mysql_query($insert);
		
		// Retorna o id da inclusão.
		return mysql_insert_id();
	}
	
	// Método criado para realizar a atualização do dados do balanco patrimonial.
	public function AtualizaDadosBalancoPatrimonial( $id, $empresaId, $ano, $a_c_caixa_equivalente_caixa, $a_c_contas_receber, $a_c_estoques, $a_c_outros_creditos, $a_c_despesas_exercicio_seguinte, $a_c_total, $a_n_c_contas_receber, $a_n_c_investimentos, $a_n_c_imobilizado, $a_n_c_intangivel, $a_n_c_depreciacao, $a_n_c_total, $p_c_fornecedores, $p_c_emprestimos_bancarios, $p_c_obrigacoes_sociais_impostos, $p_c_contas_pagar, $p_c_lucros_distribuir, $p_c_provisoes, $p_c_total, $p_n_c_contas_pagar, $p_n_c_financiamentos_bancarios, $p_n_c_total, $p_l_capital_social, $p_l_reservas_capital, $p_l_ajustes_avaliacao_patrimonial, $p_l_reservas_lucro, $p_l_lucros_acumulados, $p_l_prejuizos_acumulados, $p_l_total ) {
		
		// Define o select de pesquisa.
		$update = "UPDATE balanco_patrimonial
				SET id_user = '".$empresaId."'
					,ano = '".$ano."'
					,a_c_caixa_equivalente_caixa = '".$a_c_caixa_equivalente_caixa."'
					,a_c_contas_receber = '".$a_c_contas_receber."'
					,a_c_estoques = '".$a_c_estoques."'
					,a_c_outros_creditos = '".$a_c_outros_creditos."'
					,a_c_despesas_exercicio_seguinte = '".$a_c_despesas_exercicio_seguinte."'
					,a_c_total = '".$a_c_total."'
					,a_n_c_contas_receber = '".$a_n_c_contas_receber."'
					,a_n_c_investimentos = '".$a_n_c_investimentos."'
					,a_n_c_imobilizado = '".$a_n_c_imobilizado."'
					,a_n_c_intangivel = '".$a_n_c_intangivel."'
					,a_n_c_depreciacao = '".$a_n_c_depreciacao."'
					,a_n_c_total = '".$a_n_c_total."'
					,p_c_fornecedores = '".$p_c_fornecedores."'
					,p_c_emprestimos_bancarios = '".$p_c_emprestimos_bancarios."'
					,p_c_obrigacoes_sociais_impostos = '".$p_c_obrigacoes_sociais_impostos."'
					,p_c_contas_pagar = '".$p_c_contas_pagar."'
					,p_c_lucros_distribuir = '".$p_c_lucros_distribuir."'
					,p_c_provisoes = '".$p_c_provisoes."'
					,p_c_total = '".$p_c_total."'
					,p_n_c_contas_pagar = '".$p_n_c_contas_pagar."'
					,p_n_c_financiamentos_bancarios = '".$p_n_c_financiamentos_bancarios."'
					,p_n_c_total = '".$p_n_c_total."'
					,p_l_capital_social = '".$p_l_capital_social."'
					,p_l_reservas_capital = '".$p_l_reservas_capital."'
					,p_l_ajustes_avaliacao_patrimonial = '".$p_l_ajustes_avaliacao_patrimonial."'
					,p_l_reservas_lucro = '".$p_l_reservas_lucro."'
					,p_l_lucros_acumulados = '".$p_l_lucros_acumulados."'
					,p_l_prejuizos_acumulados = '".$p_l_prejuizos_acumulados."'
					,p_l_total = '".$p_l_total."'
				WHERE id = '".$id."'
				AND ano = '".$ano."'";
		
		// Execulta a atualização.
		$result = mysql_query($update);
	}
	
	// Pega os dados do banco de dados.
	public function PegaDadosBalancoPatrimonial($empresaId, $ano) {
		
		$out = false;
		
		// Define o select de pesquisa.
		$sql = "SELECT * FROM balanco_patrimonial WHERE id_user = ".$empresaId." AND ano = '".$ano."'";
		
		// Execulta a query.
		$result = mysql_query($sql);
		
		// Verifica se existe dados.
		if( mysql_num_rows($result) > 0 ) {
			$out = $array;
		}
		
		// Retorna uma lista de array.
		return $out;
	}
	
	// Pega os dados do banco de dados.
	public function ExcluiOsDadosDoBalanco($empresaId, $ano) {
		
		$out = false;
		
		// Define o select de pesquisa.
		$sql = "DELETE FROM balanco_patrimonial WHERE id_user = ".$empresaId." AND ano = '".$ano."'";
		
		// Execulta a query.
		$result = mysql_query($sql);
	}
	
	public function PegaLancamentoAno($ano){
		
		$out = false;
		
		$sql = "SELECT lancamento.*
					, lancPagtoContasPR.data as dataPagtoContas_PR
					, pagar.vencimento as dataVencimentoContas_PR
					, pagar.categoria_secundaria_1
					, pagto.categoria_secundaria_2
					, emp.carencia as emprestimo_carencia
				FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa lancamento 
				LEFT JOIN lancamento_contas_pagar_receber pagar ON pagar.livro_caixa_id = lancamento.id AND pagar.empresaId = '".$_SESSION['id_empresaSecao']."' 
				LEFT JOIN lancamento_contas_pagar_receber pagto ON pagto.livro_caixa_id_pagamento = lancamento.id AND pagto.empresaId = '".$_SESSION['id_empresaSecao']."' 
				LEFT JOIN user_".$_SESSION['id_empresaSecao']."_livro_caixa lancPagtoContasPR ON lancPagtoContasPR.id = pagar.livro_caixa_id_pagamento
				LEFT JOIN dados_do_emprestimo emp ON emp.livro_caixa_id = lancamento.id  AND emp.empresaId = '".$_SESSION['id_empresaSecao']."'
				WHERE ( lancamento.data >= '".$ano."-01-01' AND lancamento.data <= '".$ano."-12-31' )
				AND lancamento.categoria != ''
				AND lancamento.categoria != 'Repasse a terceiros' 
				ORDER BY lancamento.data ASC;";		
		
		// Execulta a query.
		$result = mysql_query($sql);
		
		// Verifica se existe dados.
		if( mysql_num_rows($result) > 0 ) {
			
			// Percorre a lista 
			while($array = mysql_fetch_array($result)){
				$out[] = $array;
			}
		}
		
		// Retorna uma lista de array.
		return $out;
	}
	
	public function PegaListaClientesPeloApelido($categoria) {
		
		$clientes = array();
		
		// Monta a consulta para pegar a lista de clientes.
		$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND status = 'A' ORDER BY apelido";
		$rs_clientes = mysql_query($sql_clientes);
		if(mysql_num_rows($rs_clientes) > 0){
			while($dados_clientes = mysql_fetch_array($rs_clientes)){
				$clientes[] = $dados_clientes['apelido'];
			}
		}

		return $clientes;
	}
	
	// Pega os dados da tabela de bens intagiveis.
	public function PegaDadosBensIntangiveis($empresaId, $ano){
	
		$rows = false;
		
		$query = "SELECT * FROM intangiveis WHERE id_user = '".$empresaId."' AND YEAR(data) <= '".$ano."'";		
		
		$consulta = mysql_query($query);
		
		if(mysql_num_rows($consulta) > 0){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array;
			}
		}
		
		return $rows;
	}
	
	// Pega os dados da tabela de bens imobilizados.
	public function PegaDadosBensImobilizados($empresaId, $ano){

		$rows = false;

		$query = "SELECT * FROM imobilizados WHERE id_user = '".$empresaId."' AND YEAR(data) <= '".$ano."'";		

		$consulta = mysql_query($query);

		if(mysql_num_rows($consulta) > 0){
			while($array = mysql_fetch_array($consulta)){
				$rows[] = $array;
			}
		}

		return $rows;
	}
}
?>