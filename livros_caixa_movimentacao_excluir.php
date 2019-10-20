<?php 

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

	include 'session.php';
	include 'conect.php';
	include 'check_login.php';

	// Método criado para pegar o jusros pago.
	function cadsatrarImobilizados(){
		if( $_POST['selCategoria'] == 'Imóveis' || $_POST['selCategoria'] == 'Veículos' || $_POST['selCategoria'] == 'Móveis e utensílios' || $_POST['selCategoria'] == 'Máquinas e equipamentos' )
			return true;
		else
			return false;	
	}

	// Método criado para pegar o jusros pago.
	function AtualizaJurosAmortizacao($empresaId, $registroPaiId, $valorAmortizacao, $saldoRemanescente, $emprestimoId, $dataEmprestimo, $dataInclusao) {
		
		// inicia o valor do juro com zero.
		$juros = 0;
		
		// Pega a ultima amortização realizada ao empréstimo 
		$qry = "SELECT * FROM dados_do_emprestimo 
				WHERE empresaId = ".$empresaId." 
				AND registroPaiId = ".$registroPaiId." 
				AND tipo = 'amortizacao'
				AND emprestimoId != '".$emprestimoId."'
				AND data_emprestimo <= '".$dataEmprestimo."'
				ORDER BY data_emprestimo DESC, data_inclusao DESC
				LIMIT 1;";
				
		// Executa a consulta.
		$consulta = mysql_query($qry);
		
		// Verifica se já existe armotização do emprestimo e pega o valor amortizado.
		if(mysql_num_rows($consulta) > 0 ){
			
			$amortizacaoAnterior = mysql_fetch_array($consulta);
			
			// Pega o total amortizado informando o saldo remanescente anterior e subtraindo pelo atual.
			$totalAmortizado = $amortizacaoAnterior['saldo_remanescente'] - $saldoRemanescente;
					
			// Pega o juros informando o valor amortização e subtraindo pelo total amortizado.			
			$juros = $valorAmortizacao - $totalAmortizado;

		} else {
		
			// Pega o total empréstimo.
			$qry = "SELECT SUM(entrada) as entrada FROM dados_do_emprestimo
						WHERE empresaId = ".$empresaId."
						AND (emprestimoId = ".$registroPaiId." OR registroPaiId = ".$registroPaiId.") 
						AND tipo = 'emprestimo';";
			
			// Executa a consulta.
			$consulta = mysql_query($qry);

			// Verifica se existe o empréstimo.
			if(mysql_num_rows($consulta) > 0 ){
				
				$emprestimo = mysql_fetch_array($consulta);			
								
				// Pega o total amortizado informando o valor empréstimo e subtraindo pelo saldo remanescente. 
				$totalAmortizado = $emprestimo['entrada'] - $saldoRemanescente;
								
				// Pega o juros informando o valor amortização e subtraindo pelo total amortizado.			
				$juros = $valorAmortizacao - $totalAmortizado;
			}
		}
		
		
		// Realiza a atualizaão do juros
		$update = "UPDATE dados_do_emprestimo 
				SET juros = '".$juros."'
				WHERE empresaId = '".$empresaId."'
				AND emprestimoId = '".$emprestimoId."'";

		// Execulta a ataulização
		mysql_query($update);	
	}

	// inicia a atualização do juros da armortização.
	function IniciaAtualizacaoJuros($empresaId, $registroPaiId){
						
		// Pega a lista de amortização.
		$sql = "SELECT * FROM dados_do_emprestimo 
				WHERE empresaId = ".$empresaId." 
				AND tipo = 'amortizacao' 
				AND registroPaiId = ".$registroPaiId."
				ORDER BY data_emprestimo ASC, data_inclusao ASC;";

		$resultado = mysql_query($sql) or die (mysql_error());

		if( mysql_num_rows($resultado) > 0 ) {

			while($array = mysql_fetch_array($resultado)){

				// Realiza o calculo do juros das amortizações incluidas no banco de dados.
				AtualizaJurosAmortizacao($array['empresaId'], $array['registroPaiId'], $array['amortizacao'], $array['saldo_remanescente'], $array['emprestimoId'], $array['data_emprestimo'],$array['data_inclusao']);
			}
		}
	}

	function VerificaEmprestimoComAmortização($empresaId, $livroCaixaId){
		
		$out = false;
		
		// Prepara consulta do emprestimo.
		$query = "SELECT * FROM dados_do_emprestimo
				WHERE empresaId = '".$empresaId."'
				AND	livro_caixa_id ='".$livroCaixaId."'
				AND tipo = 'emprestimo' 
				AND registroPaiId IS NULL;";
		
		// Execulta consulta.
		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0 ) {
			
			$row = mysql_fetch_array($resultado);
					
				// Prepara consulta para verificar se o emprestimo tem amortização.
				$qry = "SELECT * FROM dados_do_emprestimo
				WHERE registroPaiId = '".$row['emprestimoId']."';";

				// Execulta consulta.
				$result = mysql_query($qry);

				if( mysql_num_rows($result) > 0 ) {
					$out = mysql_fetch_array($result);
				}
		}
		
		return $out;		
	}

	// Verifica se dever excluir também o lançamento do contas a pagar e receber.
	function DefineExclusaoContasApagarEReceber($id){
	
		// Pega o relacionamento do contas a pagar ou receber.
		$sel = "SELECT * FROM lancamento_contas_pagar_receber
				WHERE empresaId = '".$_SESSION['id_empresaSecao']."'
				AND livro_caixa_id_pagamento = '". $id ."'
				AND inclusao = 'livro caixa'"; 

		// Execulta consulta.
		$result = mysql_query($sel);

		if( mysql_num_rows($result) > 0 ) {

			$dados = mysql_fetch_array($result);
			
			// Se o contas a pagar ou a receber form incluido pelo livro caixa realiza a exclusão.
			$del = "DELETE FROM lancamento_contas_pagar_receber
					WHERE empresaId = '".$_SESSION['id_empresaSecao']."'
					AND livro_caixa_id_pagamento = '". $id ."'
					AND inclusao = 'livro caixa'"; 

			echo $del;
			
			// Execulta a exclusão do relacionamento com o contas a pagar e receber.
			mysql_query($del) or die (mysql_error());
			
			// Exclui o lançamento do contas a pagar e a receber.
			$sql = "DELETE FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE id='" . $dados['livro_caixa_id'] . "'";
			$resultado = mysql_query($sql) or die (mysql_error());

		} else {
				
			// Quebra o relacionamento do  pagamento do contas a pagar ou a receber com o livro caixa quando o lançamento não for feito pelo livro caixa.
			$update = "UPDATE lancamento_contas_pagar_receber
					SET livro_caixa_id_pagamento = NULL 
					WHERE empresaId = '".$_SESSION['id_empresaSecao']."'
					AND livro_caixa_id_pagamento = '". $id ."'";

			mysql_query($update) or die (mysql_error());
		}
	}

	//Trecho que exclui todos os arquivos para um lançamento
	$linha = $_GET["linha"];


	$statusEmprestimo = VerificaEmprestimoComAmortização($_SESSION['id_empresaSecao'], $linha);

	// Verifica se o emprestimo não tem armotização assim permitindo a exclusão do mesmo.
	if($statusEmprestimo){
		
		if($statusEmprestimo['amortizacao'] > 0){
			$_SESSION['erroEmprestimo'] = 'excluir_amortizacao';
		} else {
			$_SESSION['erroEmprestimo'] = 'excluir_complementar';
		}
		
		header('Location: livros_caixa_movimentacao.php' );
		
		die();
	}

	//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
	//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

	// - Essa trecho de codigo trabalha excluir o arquivo do banco e do servidor
	// Define o status do contas a paragar ou a receber como null caso a linha seja excluida.

	//Trecho que exclui arquivos individualmente
	if( isset($_GET['acao']) ){

		$consulta = mysql_query("SELECT * FROM comprovantes WHERE id = '".$_GET['id_arquivo']."' AND id_user = '".$_SESSION["id_empresaSecao"]."' ");
		$objeto=mysql_fetch_array($consulta);
		
		if($objeto['nome'] != '' ){
			if( file_exists('upload/comprovantes/'.$objeto['nome']) ){
				unlink('upload/comprovantes/'.$objeto['nome']);
			}
			$consulta = mysql_query("DELETE FROM comprovantes WHERE id = '".$_GET['id_arquivo']."' AND id_user = '".$_SESSION["id_empresaSecao"]."' ");
			$resultado = mysql_query($consulta);
		}

		header('Location: livros_caixa_movimentacao.php?dataInicio='.$_GET['dataInicio'].'&dataFim='.$_GET['dataFim'].'&editar='.$_GET['editar'] );
		return;

	}

	//Deleta os anexos
	$consulta = mysql_query("SELECT * FROM comprovantes WHERE id_lancamento = '".$linha."' AND id_user = '".$_SESSION["id_empresaSecao"]."' ");
	        		
	while( $objeto=mysql_fetch_array($consulta) ){ 
		if( file_exists('upload/comprovantes/'.$objeto['nome']) )
			unlink('upload/comprovantes/'.$objeto['nome']);
		 
	}

	$consulta = mysql_query("DELETE FROM comprovantes WHERE id_lancamento = '".$linha."' AND id_user = '".$_SESSION["id_empresaSecao"]."' ");
	$resultado = mysql_query($consulta);

	//FIM
	$sql = "DELETE FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE id='" . $linha . "'";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	$select2 = "SELECT * FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa WHERE id='" . $linha . "'";
	$resul2 = mysql_query($select2)	or die (mysql_error());

	// Verifica se o item foi excluido para poder realizae a exclusão dos dados auxiliar.
	if(mysql_num_rows($resul2) == 0){

		// inicia a variável como false para não permitir a atualização do juros caso não exista id pai.
		$registroPaiId = false;

		// pesquisa para pegar o id do registro pai.
		$qry2 = "SELECT registroPaiId FROM dados_do_emprestimo
				WHERE empresaId = '".$_SESSION['id_empresaSecao']."'
				AND	livro_caixa_id ='".$linha."'
				AND registroPaiId IS NOT NULL;";

		// Execulta consulta.
		$result2 = mysql_query($qry2);

		if( mysql_num_rows($result2) > 0 ) {

			$array = mysql_fetch_array($result2);

			$registroPaiId = $array['registroPaiId'];
		}		
		
		// Realiza a exclusão do emprestimo caso exista.
		mysql_query("DELETE FROM dados_do_emprestimo
					WHERE empresaId = '".$_SESSION['id_empresaSecao']."'
					AND	livro_caixa_id ='". $linha ."'");

		// Apos exluir a linhas do emprestimo e necessario atualizar o juros da tabela.
		if($registroPaiId){
			IniciaAtualizacaoJuros($_SESSION['id_empresaSecao'], $registroPaiId); 
		}		

		// Chama o método que quebra o relacionamento do contas a pagar ou receber ou exluir tambem o lançamento do contas a pagar e receber. 
		DefineExclusaoContasApagarEReceber($linha);

		mysql_query("DELETE FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa_emprestimos WHERE id_item='" . $linha . "'");

		mysql_query("DELETE FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa_bens WHERE id_item='" . $linha . "'");

		mysql_query("DELETE FROM imobilizados WHERE id_user = " . $_SESSION['id_empresaSecao'] . " AND id_livro_caixa = '" . $linha . "'");		
	}

	if(isset($_GET["del_pagto"])){

		// EXCLUINDO REGISTRO DA FOLHA DE PAGAMENTO
		@mysql_query("DELETE FROM dados_pagamentos WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND idLivroCaixa = " . $linha);

	}else{

		@mysql_query("UPDATE dados_pagamentos SET idLivroCaixa = 0 WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND idLivroCaixa = " . $linha);

	}

	header('Location: livros_caixa_movimentacao.php' );
?>