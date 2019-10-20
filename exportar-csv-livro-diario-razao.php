<?php 
	
	session_start();
	include 'conect.php';
	include 'datas.class.php';
	include 'livro-diario-razao.class.php';
	
	$livro_diario = new Livro_diario();
	$plano_contas = new Plano_de_contas();
	$intervalo_inicio = getAnoInicio();
	$intervalo_fim = getAnoFim();
	$tipo = getTipo();
	
	
	//Função que retorna o sql com os itens do livro caixa para um determinado intervalo
	function getSqlLivroCaixa( $intervalo_inicio , $intervalo_fim  ){
		$datas = new Datas();

//		return mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa WHERE ( data >= '".$datas->converterData($intervalo_inicio)."' AND data <= '".$datas->converterData($intervalo_fim)."' ) AND categoria != 'Devolução de adiantamento' ORDER BY data ASC");
				
		$query = " SELECT lc.*, pagar.categoria_secundaria_1, pagto.categoria_secundaria_2 "
			." FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa lc "
			." LEFT JOIN lancamento_contas_pagar_receber pagar ON pagar.livro_caixa_id = lc.id AND pagar.empresaId = '".$_SESSION['id_empresaSecao']."' "
			." LEFT JOIN lancamento_contas_pagar_receber pagto ON pagto.livro_caixa_id_pagamento = lc.id AND pagto.empresaId = '".$_SESSION['id_empresaSecao']."' "
			." WHERE ( data >= '".$datas->converterData($intervalo_inicio)."' AND data <= '".$datas->converterData($intervalo_fim)."' ) "
			." AND lc.categoria != '' "
			." AND lc.categoria != 'Repasse a terceiros' "
			." ORDER BY lc.data ASC ";
		
		return mysql_query($query);
	}

	// Método com a categorias do contas a receber
	function listaClientes($categoria) {
		
		$clientes = array();
		
		// Monta a consulta para pegar a lista de clientes.
		$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND status = 'A' ORDER BY apelido";
		$rs_clientes = mysql_query($sql_clientes);
		if(mysql_num_rows($rs_clientes) > 0){
			while($dados_clientes = mysql_fetch_array($rs_clientes)){
				$clientes[] = $dados_clientes['apelido'];
			}
		}

		return in_array($categoria, $clientes);
	}
	
	// Método com a categorias do contas a pagar
	function ListaContasAPagar($categoria){
		
		$listaContasAPagar = array(
			'Aluguel'
			,'Aluguel de software'
			,'Água'
			,'Combustível'
			,'Comissões'
			,'Condomínio'
			,'Contador'
			,'Cursos e treinamentos'
			,'Energia elétrica'
			,'Estagiários'
			,'Impostos e encargos'
			,'Internet'
			,'Marketing e publicidade'
			,'Material de escritório'
			,'Pagto. de Salários'
			,'Pgto. a autônomos e fornecedores'
			,'Pró-Labore'
			,'Segurança'
			,'Seguros'
			,'Telefone'
			,'Transportadora / Motoboy'
		);
		
		
		return in_array($categoria, $listaContasAPagar);
	}

	// Método criado para poder normalizar a apresentação na tebela do livro diario referente a contas a pagar e contas a receber.
	function NormalizaContasApagarEContasReceber($item){
		
		// Verifica se e se o lançamento e um pagamento ou a ser pago. 
		if(isset($item['categoria_secundaria_2']) &&  !empty($item['categoria_secundaria_2'])){

			// Cria um array com os dados para poder normalizar o a visualização da linha na tabela.
			$itemArray = array(
				'id'=>$item['id'],
				'data'=>$item['data'],
				'entrada'=>$item['entrada'],
				'saida'=>$item['saida'],
				'documento_numero'=>$item['documento_numero'],
				'descricao'=>$item['descricao'], 
				'categoria'=>$item['categoria_secundaria_2'], // Inclui a categoria referente ao pagamento. 
				'categoriaAux'=>$item['categoria'] // A index categoria auxiliar e usada para pegar a categoria real do registro pai. 
			); 

		} // Lançamento a ser pago.
		else {

			// Cria um array com os dados para poder normalizar o a visualização da linha na tabela.
			$itemArray = array(
				'id'=>$item['id'],
				'data'=>$item['data'],
				'entrada'=>$item['entrada'],
				'saida'=>$item['saida'],
				'documento_numero'=>$item['documento_numero'],
				'descricao'=>$item['descricao'], 
				'categoria'=>$item['categoria_secundaria_1'], // Inclui a categoria referente a ser pago. 
				'categoriaAux'=>$item['categoria'] // A index categoria auxiliar e usada para pegar a categoria real do registro pai. 
			);			
		}
		
		
		return $itemArray;		
	}

	// Método criado para montar a linhas da tabela do livro diário.
	function MontaLinhaTabela($livro_diario, $item, $plano_contas ) {

		// Verifica se a categoria a ser usada é a principal ou secundaria para normalizar os lançamentos de contas a pagar e receber.
		$categoria = isset($item['categoriaAux']) ? $item['categoriaAux'] : $item['categoria'];
		
		//Define o tipo do user que recebeu o pagamento.
		$livro_diario->settipo_user_pagamento($livro_diario->ifPagamentoTipo($item));

		//Pega a relação Creditdo-Debitado para a categoria do item
		$categorias_livro = $livro_diario->getRelacao($item['categoria'],$item);
		//Cria um item do livro caixa para armazenar os dados
		$item_livro_caixa = new Item_livro_diario();
		//Define a categoria do item
		$item_livro_caixa->setcategoria($categoria);
		//Define a descrição do item
		$item_livro_caixa->setdescricao($item['descricao']);
		//Define o valor do item
		$item_livro_caixa->setvalor($item);
		//Define a data do item
		$item_livro_caixa->setdata($item['data']);
		//Define o ID do item
		$item_livro_caixa->setid($item['id']);
		//Pega os dados da relação Creditado-Debitado para a categoria do item
		$item_livro_caixa->setrelacao_cr_db($categorias_livro);
		//Insere o item para montar o livro diário
		$plano_contas->setItem($item_livro_caixa);
		//Insere o item para montar o livro Razão	
		$plano_contas->setItemAgrupado($item['categoria'],$categorias_livro,$item_livro_caixa->getvalor());
	}	

	//Define o CNAR principal do usuario
	$livro_diario->setcnae('6311-9/00');

	//Faz a consulta no banco, retornando o SQL com os itens do livro caixa que correnpndem ao intervalo selecionado
	$livro_caixa = getSqlLivroCaixa($intervalo_inicio,$intervalo_fim);
	//Percorre cada item adicionando na categoria correspondente para Credito e débito
	while( $item = mysql_fetch_array($livro_caixa) ){
		
		//Se for empréstimo define o prazo de pagamento para o emprestimo do usuario
		$livro_diario->setprazo($livro_diario->ifEmprestimo($item));
		//Define o id de quem recebeu o pagamento para consultar se é administrativo ou operacionais 
		$livro_diario->setid_user_pagamento($livro_diario->ifPagamento($item));
		//Se for pagamento seta os dados de pagamento
		$livro_diario->ifPagamento($item);
		//Define o tipo do user que rcebeu o pagamento
		//$livro_diario->settipo_user_pagamento($livro_diario->ifPagamentoTipo($item));
		//Se for pagamento seta o tipo de pagamento
		$livro_diario->ifPagamentoTipo($item);

		// Verifica se tem tratamento para categoria.
		if($item['categoria'] == 'Empréstimo (amortização)') {

			// Pega o juros da amortização.
			$juros = $livro_diario->PegaJurosAmortizacao($_SESSION['id_empresaSecao'], $item['id']);

			// Subitrai do valor amortizado o juros.
			$item['saida'] = $item['saida'] - $juros;

			$itemAmortizacao = array(
				'id'=>$item['id'],
				'data'=>$item['data'],
				'entrada'=>$item['entrada'],
				'saida'=>$juros,
				'documento_numero'=>$item['documento_numero'],
				'descricao'=>$item['descricao'],
				'categoria'=>'Juros do Empréstimo'
			);

			// Inclui a linha da amortização.
			MontaLinhaTabela( $livro_diario, $item, $plano_contas );				

			// Inclui a linha do juros. 
			MontaLinhaTabela( $livro_diario, $itemAmortizacao, $plano_contas );

		} else if( $item['categoria'] == 'Serviços prestados em geral' || listaClientes($item['categoria']) ) {

			// Chama o método para poder normalizar o contas a receber.	
			$itemNormalizado = NormalizaContasApagarEContasReceber($item);

			// Incluir a linha de contas a receber na tabela do livro diário.
			MontaLinhaTabela($livro_diario, $itemNormalizado, $plano_contas );

		} else if(ListaContasAPagar($item['categoria'])){

			// Chama o método para poder normalizar o contas a pagar.	
			$itemNormalizado = NormalizaContasApagarEContasReceber($item);

			// Incluir a linha de serviços em geral com a data da nota.
			MontaLinhaTabela($livro_diario, $itemNormalizado, $plano_contas );				

		} else {

			// Inclui a linha do livro diário.
			MontaLinhaTabela($livro_diario, $item, $plano_contas );
		}
	}

	if( $tipo == 'diario' ){
		$fileName = 'Livro Diário '.$intervalo_inicio.' até '.$intervalo_fim;
		$string = $plano_contas->CSVListarItensDiario();
	}
	if( $tipo == 'razao' ){
		$fileName = 'Livro Razão '.$intervalo_inicio.' até '.$intervalo_fim;
		$string = $plano_contas->CSVItensRazao();
	}
	// echo $string;
	

	function encode($text){	
		$enc = mb_detect_encoding($text, "UTF-8,ISO-8859-1");
		return iconv($enc, "ISO-8859-1", $text);	
	}



	header("Content-Type: application/csv; charset=WINDOWS-1252,UTF-8");
	//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=".$fileName.".csv"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	echo encode($string);

?>
