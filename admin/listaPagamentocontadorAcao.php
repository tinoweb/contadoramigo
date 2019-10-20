<?php 
/**
 * Realiza a gravação do pagamento para o contador.
 */
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

// Realiza a requizição do arquivo responsavel por faser a manipulação de dados no banco.
require('../conect.php');

// Realiza a requizição do arquivo responsavel por faser a manipulação de dados no banco.
require('../DataBaseMySQL/CobrancaContador.php');

if( isset($_POST['gravarPagamento']) ) {

	$returnId = false; 
	
	// Define o tipo do lancamento se e um pagamento ou se e comissão.
	$tipoLancamento = 'pago';

	// Verifica se os dados solicitador foram informados via post.
	if(isset($_POST['dataPagamento']) && !empty($_POST['dataPagamento'] && isset($_POST['contadorId']) && !empty($_POST['contadorId']) && isset($_POST['valorPago']) && !empty($_POST['valorPago']))) {

		$contadorId = $_POST['contadorId'];
		$valorTotal = str_replace(',','.',str_replace('.','',$_POST['valorPago']));;
		$valorLiquido = 0;
		$desconto = 0;
		$dataPagamento = str_replace('/','-',$_POST['dataPagamento']);
		echo $dataPagamento;
		$dataPagamento = date('Y-m-d', strtotime($dataPagamento));
		
		// Instancia da classe que realiza a manipulação dos dados.
		$cobrancaContador = new CobrancaContador();

		// Realiza a inclusão do pagamento para o contador.
		$returnId = $cobrancaContador->IncluiLancamentoPagamento($contadorId, $valorTotal, $valorLiquido, $desconto, $dataPagamento, $tipoLancamento);

		// Se retrona o dados do contador realiza o redireciopnamento para pagina do contador.
		if($returnId) {
			// Redireciona para a tela de pagamento para o contador.
			header('location: /admin/listapagamentocontador.php?contadorId='.$_POST['contadorId']);
		}	


	} else {

		// Verifica para onde deve ser feito o redirecionamento.	
		if(isset($_POST['contadorId']) && !empty($_POST['contadorId'])) {
			
			// Cria uma sessão co a mensagem de erro.
			$_SERVER['erroMesagem'] = 'Data ou valor não e valido';			
			
			// Redireciona para a tela de pagamento para o contador.
			header('location: /admin/listapagamentocontador.php?contadorId='.$_POST['contadorId']);
		} else {
			// Redireciona para a tela de clientes.
			header('location: /admin/clientes_lista.php');
		}
	}
}

if(isset($_POST['excluirPagamento'])) { 
	
	// Verifica se o codigo de pagamento ou 
	if(isset($_POST['contadorId']) && !empty($_POST['contadorId']) && isset($_POST['pagamentoId']) && !empty($_POST['pagamentoId'])) {
		$contadorId = $_POST['contadorId'];
		$pagamentoId = $_POST['pagamentoId'];
		
		// Instancia da classe que realiza a manipulação dos dados.
		$cobrancaContador = new CobrancaContador();

		$cobrancaContador->ExcluiPagamentoParaContador($pagamentoId, $contadorId);
		
		// Redireciona para a tela de pagamento para o contador.
		header('location: /admin/listapagamentocontador.php?contadorId='.$_POST['contadorId']);
		
	} else {
		
		// Verifica para onde deve ser feito o redirecionamento.
		if(isset($_POST['contadorId']) && !empty($_POST['contadorId'])) {
			
			// Cria uma sessão co a mensagem de erro.
			$_SERVER['erroMesagem'] = 'Código do pagamento não informado.';	
			
			// Redireciona para a tela de pagamento para o contador.
			header('location: /admin/listapagamentocontador.php?contadorId='.$_POST['contadorId']);
		} else {
			// Redireciona para a tela de clientes.
			header('location: /admin/clientes_lista.php');
		}
	}
}

if( isset($_POST['gravarComissao']) ) {

	$returnId = false; 
	
	// Define o tipo do lancamento se e um pagamento ou se e comissão.
	$tipoLancamento = 'comissao';

	// Verifica se os dados solicitador foram informados via post.
	if(isset($_POST['dataPagamento']) && !empty($_POST['dataPagamento'] && isset($_POST['contadorId']) && !empty($_POST['contadorId']) && isset($_POST['valorPago']) && !empty($_POST['valorPago']))) {

		$contadorId = $_POST['contadorId'];
		$valorTotal = str_replace(',','.',str_replace('.','',$_POST['valorPago']));;
		$valorLiquido = 0;
		$desconto = 0;
		$dataPagamento = str_replace('/','-',$_POST['dataPagamento']);
		echo $dataPagamento;
		$dataPagamento = date('Y-m-d', strtotime($dataPagamento));
		
		// Instancia da classe que realiza a manipulação dos dados.
		$cobrancaContador = new CobrancaContador();

		// Realiza a inclusão do pagamento para o contador.
		$returnId = $cobrancaContador->IncluiLancamentoPagamento($contadorId, $valorTotal, $valorLiquido, $desconto, $dataPagamento, $tipoLancamento);

		// Se retrona o dados do contador realiza o redireciopnamento para pagina do contador.
		if($returnId) {
			// Redireciona para a tela de pagamento para o contador.
			header('location: /admin/listapagamentocomissao.php?contadorId='.$_POST['contadorId']);
		}	


	} else {

		// Verifica para onde deve ser feito o redirecionamento.	
		if(isset($_POST['contadorId']) && !empty($_POST['contadorId'])) {
			
			// Cria uma sessão co a mensagem de erro.
			$_SERVER['erroMesagem'] = 'Data ou valor não e valido';			
			
			// Redireciona para a tela de pagamento para o contador.
			header('location: /admin/listapagamentocomissao.php?contadorId='.$_POST['contadorId']);
		} else {
			// Redireciona para a tela de clientes.
			header('location: /admin/clientes_lista.php');
		}
	}
}

if(isset($_POST['excluirComissao'])) { 
	
	// Verifica se o codigo de pagamento ou 
	if(isset($_POST['contadorId']) && !empty($_POST['contadorId']) && isset($_POST['pagamentoId']) && !empty($_POST['pagamentoId'])) {
		$contadorId = $_POST['contadorId'];
		$pagamentoId = $_POST['pagamentoId'];
		
		// Instancia da classe que realiza a manipulação dos dados.
		$cobrancaContador = new CobrancaContador();

		$cobrancaContador->ExcluiPagamentoParaContador($pagamentoId, $contadorId);
		
		// Redireciona para a tela de pagamento para o contador.
		header('location: /admin/listapagamentocomissao.php?contadorId='.$_POST['contadorId']);
		
	} else {
		
		// Verifica para onde deve ser feito o redirecionamento.
		if(isset($_POST['contadorId']) && !empty($_POST['contadorId'])) {
			
			// Cria uma sessão co a mensagem de erro.
			$_SERVER['erroMesagem'] = 'Código do pagamento não informado.';	
			
			// Redireciona para a tela de pagamento para o contador.
			header('location: /admin/listapagamentocomissao.php?contadorId='.$_POST['contadorId']);
		} else {
			// Redireciona para a tela de clientes.
			header('location: /admin/clientes_lista.php');
		}
	}
}