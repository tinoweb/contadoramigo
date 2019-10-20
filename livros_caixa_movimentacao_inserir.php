  <?php

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

	include "conect.php";

	session_start();


	//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento 
	//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php
	// - Essa trecho de codigo trabalha para inserir os uploads no banco e no servidor

	//Classe que realiza o upload de arquivos
	class UploadAnexos{
		
		public $nomes;

		private function Normaliza($string) {
			$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
			'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
			'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r');
			return strtr($string, $table);
		}
		
		private function extensao($file){

			$ext = explode(".", $file);
			$tam = count($ext);
			$ext = strtolower($ext[$tam-1]); 

			if( $ext != 'pdf' && $ext != 'doc' && $ext != 'jpg' && $ext != 'gif' && $ext != 'png' )
				return false;

			return $ext;

		}
		private function nomeArquivo($file,$ext){
			$nome = explode(".".$ext, $file);
			return $nome[0];
		}

		private function ifArquivoValido($file){
			if( $file != 'pdf' && $file != 'doc' && $file != 'jpg' && $file != 'gif' && $file != 'png' )
				return false;

			return true;
		}

		function execute($last_id,$ID,$doc,$Data){			
			
			foreach ($_FILES as $__FILES) {
				for ($i=0; $i < count($__FILES["name"]); $i++) { 
					$size_file = $__FILES['size'][$i];	

					if($size_file > 1000000){
						// header('Location: livros_caixa_movimentacao.php?dataInicio='.$_GET['dataInicio'].'&dataFim='.$_GET['dataFim'].'&editar='.$_GET['editar'].'
						header('Location: livros_caixa_movimentacao.php?erro_file="'.$__FILES["name"][$i].'"');
						exit();
					}
				}
			}


			$this->nomes = array();
			foreach ($_FILES as $__FILES) {
				
				for ($i=0; $i < count($__FILES["name"]); $i++) { 
					
					$file = $this->extensao($__FILES["name"][$i]);
					if( $this->ifArquivoValido($file) ){
						$id = '10';

						$name = $__FILES["name"][$i];
						$extensao = $this->extensao($file);
						$file_name = $this->nomeArquivo($name,$extensao);

						$file_name = utf8_encode($this->Normaliza($file_name."_".$last_id.".".$extensao));
						
						$this->nomes[] = $file_name;
						if( file_exists('upload/comprovantes/'.$file_name) )
							unlink('upload/comprovantes/'.$file_name);

						$ret = move_uploaded_file($__FILES["tmp_name"][$i],"upload/comprovantes/".$file_name);

					 }
				}
			}
		}
		
	}

	//FIM
	function isPagamento(){
		if( $_POST['selCategoria'] == 'Estagiários' )
			return true;
		if( $_POST['selCategoria'] == 'Pgto. a autônomos e fornecedores' )
			return true;
		if( $_POST['selCategoria'] == 'Pagto. de Salários' )
		 	return true;
		if( $_POST['selCategoria'] == 'Pró-Labore' )
			return true;
		if( $_POST['selCategoria'] == 'Distribuição de lucros' )
			return true;
		return false;
	}
	function isEmprestimo(){
		if( $_POST['selCategoria'] == 'Empréstimos' && $_POST['selTipoLancamento'] == 'entrada'  )
			return true;
		return false;	
	}
	function isAmortizacaoEmprestimo() {
		
		if( $_POST['selCategoria'] == 'Empréstimo (amortização)' && $_POST['selTipoLancamento'] == 'saida'  ) {
			return true;
		}
		
		return false;
	}
	function isContasAReceber() {
		
		$clientes = array();
		
		// Monta a consulta para pegar a lista de clientes.
		$sql_clientes = "SELECT DISTINCT apelido FROM dados_clientes WHERE id_login = " . $_SESSION['id_empresaSecao'] . " AND status = 'A' ORDER BY apelido";
		$rs_clientes = mysql_query($sql_clientes);
		if(mysql_num_rows($rs_clientes) > 0){
			while($dados_clientes = mysql_fetch_array($rs_clientes)){
				$clientes[] = $dados_clientes['apelido'];
			}
		}
		
		if( ($_POST['selCategoria'] == 'Serviços prestados em geral' || in_array($_POST['selCategoria'],$clientes)) && $_POST['selTipoLancamento'] == 'entrada'  ) {
			return true;
		}
		return false;
	}

	function isContasAPagar(){
		
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
			,'Impostos e encargos'
			,'Internet'
			,'Marketing e publicidade'
			,'Material de escritório'
			,'Segurança'
			,'Seguros'
			,'Telefone'
			,'Transportadora / Motoboy'
		);
		
		// Verifica se a categoria esta na lista.
		if(in_array($_POST['selCategoria'], $listaContasAPagar) && $_POST['selTipoLancamento'] == 'saida'){
			return true;
		}
		
		return false;
	}

	function isBens(){
		if( $_POST['selCategoria'] == 'Imóveis' || $_POST['selCategoria'] == 'Máquinas e equipamentos' )
			return true;
		return false;	
	}
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

	$ID = $_POST["hidID"];
	$Valor = str_replace(",",".",str_replace(".","",$_POST["txtValor"]));
	$TipoLancamento = $_POST["selTipoLancamento"];
	$DocumentoNumero = mysql_real_escape_string($_POST["txtDocumentoNumero"]);
	if($DocumentoNumero == ''){
		$DocumentoNumero = mysql_real_escape_string($_POST["hidNDocs"]);
	}
	if(isset($_POST["selCategoria"]) && $_POST["selCategoria"] != ""){
		$categoria = ", categoria = '" . mysql_real_escape_string($_POST["selCategoria"]) . "'";
	}
	$Descricao = mysql_real_escape_string($_POST["txtDescricao"]);
	$dia = substr($_POST["txtData"], 0, 2);
	$mes = substr($_POST["txtData"], 3, 2);
	$ano = substr($_POST["txtData"], 6, 4);
	$Data = date('Y-m-d',mktime(0,0,0,$mes,$dia,$ano));
	$EmprestimoPaiId = isset($_POST['registroPaiIdAmortizacao']) && !empty($_POST['registroPaiIdAmortizacao']) ? $_POST['registroPaiIdAmortizacao'] : false;

	if($EmprestimoPaiId) {

		// Prepara consulta do emprestimo.
		$queryEmp = "SELECT * FROM dados_do_emprestimo 
				WHERE tipo = 'amortizacao' 
				AND registroPaiId = '".$EmprestimoPaiId."'
				AND data_emprestimo = '".$Data."';";
		
		// Execulta consulta.
		$resulEmp = mysql_query($queryEmp);

		if( mysql_num_rows($resulEmp) > 0 ) {
			
			$_SESSION['erroEmprestimo'] = 'incluir';
		
			header('Location: livros_caixa_movimentacao.php' );

			die();
		}
	}

	if( isPagamento() ){
		$Descricao = $_POST['nome_pagamento'];
	}
	
	// caso exista um lançamento de contas a receber ou a pagar
	if(isContasAReceber() || isContasAPagar()){
		
		$contasAreceberId = '';
		
		if(isset($_POST['contasAreceberId']) && !empty($_POST['contasAreceberId'])) {
			$contas_rec_Pag_id = $_POST['contasAreceberId']; 
		}
		
		if(isset($_POST['contasApagarId']) && !empty($_POST['contasApagarId'])) {
			$contas_rec_Pag_id = $_POST['contasApagarId']; 
		}		
		
		$selectLC = "SELECT * FROM user_".$ID."_livro_caixa WHERE id='".$contas_rec_Pag_id."'";

		$resultado = mysql_query($selectLC) or die (mysql_error());

		if( mysql_num_rows($resultado) > 0 ){
			
			$row = mysql_fetch_array($resultado);
						
			$Descricao = $row['descricao'];
			$DocumentoNumero = $row['documento_numero'];
		}
	}

	$sql = "INSERT INTO user_" . $ID . "_livro_caixa SET
		data = '$Data'
		, $TipoLancamento = '$Valor'
		, documento_numero = '$DocumentoNumero'
		, descricao = '$Descricao'
		" . $categoria . "
		";
	$resultado = mysql_query($sql)
	or die (mysql_error());

	// Pega a linas que foi incluida no livro caixa.
	$livro_caixa_id = mysql_insert_id();

	//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento 
	//Arquivos: livros_caixa_movimentacao.php livros_caixa_movimentacao.php livros_caixa_movimentacao.php
	// - Essa trecho de codigo trabalha para inserir os uploads no banco e no servidor

	$last_id = mysql_insert_id();

	if( isEmprestimo() ){
		if ($consulta = mysql_query("SHOW TABLES LIKE 'user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos' ")) {
		    mysql_num_rows($consulta);
		    if( mysql_num_rows($consulta) == 0 ) {
		        mysql_query("CREATE TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` (`id` int(11) NOT NULL,`id_item` int(11) NOT NULL,`valor_pago` float NOT NULL,`meses` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");
				mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` ADD PRIMARY KEY (`id`); ");
				mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
		    }
		}
		
		$Valor_pago = str_replace(",",".",str_replace(".","",$_POST["valor_pago"]));
		mysql_query("INSERT INTO `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` (`id_item`, `meses`, `valor_pago`) VALUES ( '".$last_id."' , '".$_POST['prazo_carencia']."', '".$Valor_pago."' )");

		if($livro_caixa_id) {

			$carencia = (isset($_POST['prazo_carencia']) && !empty($_POST['prazo_carencia']) ? "'".$_POST['prazo_carencia']."'" : "NULL");
			$apelido = (isset($_POST['apelido']) && !empty($_POST['apelido']) ? "'".$_POST['apelido']."'" : "NULL");
			$registroPaiId = (isset($_POST['registroPaiId']) && !empty($_POST['registroPaiId']) ? "'".$_POST['registroPaiId']."'" : "NULL");

			$insert = "INSERT INTO dados_do_emprestimo (empresaId, livro_caixa_id, data_inclusao, data_emprestimo, apelido, carencia, entrada, tipo, registroPaiId) VALUES ('".$_SESSION['id_empresaSecao']."', '".$livro_caixa_id."' , NOW(), '".$Data."',	".$apelido.", ".$carencia.", '".$Valor."','emprestimo', ".$registroPaiId.")";
			
			mysql_query($insert);
		}
	}

	if(isAmortizacaoEmprestimo()) {
		
		if($livro_caixa_id) {
			
			$empresaId = $_SESSION['id_empresaSecao'];
			$saldoRemanescente = str_replace(',','.',str_replace('.','',$_POST['saldoRemanescente']));
			$juros = 0;
			$registroPaiId = "'".$_POST['registroPaiIdAmortizacao']."'";

			// Prepara a instrução para inclusão dos dados de amortização do empréstimo.
			$insert = "INSERT INTO dados_do_emprestimo (empresaId, livro_caixa_id, data_inclusao, data_emprestimo, amortizacao, juros, saldo_remanescente, tipo, registroPaiId) VALUES ('".$empresaId."', '".$livro_caixa_id."' , NOW(), '".$Data."',	'".$Valor."', '".$juros."', '".$saldoRemanescente."', 'amortizacao', ".$registroPaiId.")";
			
			mysql_query($insert);
						
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
	}
	
	// Verifica se o pagamento pertence ao contas a receber.
	if(isContasAReceber()){
		
		// pega o código da empresa.
		$empresaId = $_SESSION['id_empresaSecao'];
		
		// Verifica se devera incluir junto com o pagamento o contas a receber ou se devera relacionar o contas pagamento com o contas a receber	
		if(isset($_POST['contasAreceberId']) && !empty($_POST['contasAreceberId'])) {
			
			// Realiza o relacionamento do contas a receber com o contas a pagar.
			
			$categoria_secundaria_2 = 'Clientes Contas Pagas';
			
			$update = "UPDATE lancamento_contas_pagar_receber 
			   SET livro_caixa_id_pagamento = '".$livro_caixa_id."',
			   		categoria_secundaria_2 = '".$categoria_secundaria_2."'
			   WHERE empresaId = '".$empresaId."' 
			   AND livro_caixa_id = '".$_POST['contasAreceberId']."'";
			
			mysql_query($update) or die (mysql_error());

		} // Inclui o lançamento do contas a receber.
		else {	
		
			// Inicia a variavel como falso.
			$insert_id = false;

			// Converte a data para ser incluida no banco de dados.
			$dataNotaFiscal = date('Y-m-d', strtotime(str_replace('/','-',$_POST['dataNotaFiscal'])));

			// Valor do documento.
			$valorOriginal = str_replace(",",".",str_replace(".","",$_POST["valorOriginal"]));			
			
			$insert1 = "INSERT INTO user_" . $ID . "_livro_caixa SET
						data = '$dataNotaFiscal'
					, $TipoLancamento = '$valorOriginal'
					, documento_numero = '$DocumentoNumero'
					, descricao = '$Descricao'
					" . $categoria . "";
			
			$resultado = mysql_query($insert1) or die (mysql_error());

			// Pega a linas que foi incluida no livro caixa.
			$insert_id = mysql_insert_id();

			if($insert_id) {
				
				$categoria_secundaria_1 = 'Cliente Contas a Pagar';
				$categoria_secundaria_2 = 'Clientes Contas Pagas';
				$vencimento = date('Y-m-d', strtotime(str_replace('/','-',$_POST['dataVencimento'])));

				// Cria o relacionamento com o pagamento con lançamento do documento.
				$insert2 = "INSERT INTO lancamento_contas_pagar_receber (empresaId, livro_caixa_id, livro_caixa_id_pagamento, data_inclusao, categoria_secundaria_1, categoria_secundaria_2, tipo, inclusao, vencimento) VALUES ('".$empresaId."', '".$insert_id."', '".$livro_caixa_id."' , NOW(), '".$categoria_secundaria_1."', '".$categoria_secundaria_2."', 'contas a receber', 'livro caixa', '".$vencimento."')";
				
				// Execulta a inclusão dos dados.
				mysql_query($insert2);
			}
		}
	}

	// Verifica se o pagamento pertence ao contas a pagar
	if(isContasAPagar()) {
		
		// pega o código da empresa.
		$empresaId = $_SESSION['id_empresaSecao'];
		
		// Verifica se devera incluir junto com o pagamento o contas a pagar ou se devera relacionar o contas pagamento com o contas a pagar	
		if(isset($_POST['contasApagarId']) && !empty($_POST['contasApagarId'])) {
			
			$categoria_secundaria_2 = $_POST["selCategoria"].' pago';
			
			// Realiza o relacionamento do contas a pagar com o contas a pagar.
			$update = "UPDATE lancamento_contas_pagar_receber 
			   SET livro_caixa_id_pagamento = '".$livro_caixa_id."',
			  		categoria_secundaria_2 = '".$categoria_secundaria_2."'
			   WHERE empresaId = '".$empresaId."' 
			   AND livro_caixa_id = '".$_POST['contasApagarId']."'";
			
			mysql_query($update) or die (mysql_error());

		} // Inclui o lançamento do contas a pagar.
		else {	
		
			// Inicia a variavel como falso.
			$insert_id = false;

			// Converte a data para ser incluida no banco de dados.
			$dataNotaFiscal = date('Y-m-d', strtotime(str_replace('/','-',$_POST['dataNotaFiscal'])));

			// Valor do documento.
			$valorOriginal = str_replace(",",".",str_replace(".","",$_POST["valorOriginal"]));
			
			$insert1 = "INSERT INTO user_" . $ID . "_livro_caixa SET
						data = '$dataNotaFiscal'
					, $TipoLancamento = '$valorOriginal'
					, documento_numero = '$DocumentoNumero'
					, descricao = '$Descricao'
					" . $categoria . "";
			
			$resultado = mysql_query($insert1) or die (mysql_error());

			// Pega a linas que foi incluida no livro caixa.
			$insert_id = mysql_insert_id();		

			if($insert_id) {
				
				$categoria_secundaria_1 = $_POST["selCategoria"].' a pagar';
				$categoria_secundaria_2 = $_POST["selCategoria"].' pago';
				$vencimento = date('Y-m-d', strtotime(str_replace('/','-',$_POST['dataVencimento'])));
				
				// Cria o relacionamento com o pagamento con lançamento do documento.
				$insert2 = "INSERT INTO lancamento_contas_pagar_receber (empresaId, livro_caixa_id, livro_caixa_id_pagamento, data_inclusao, categoria_secundaria_1, categoria_secundaria_2, tipo, inclusao, vencimento) VALUES ('".$empresaId."', '".$insert_id."', '".$livro_caixa_id."' , NOW(), '".$categoria_secundaria_1."', '".$categoria_secundaria_2."', 'contas a pagar', 'livro caixa', '".$vencimento."')";
								
				// Execulta a inclusão dos dados.
				mysql_query($insert2) or die (mysql_error());
			}
		}
	}

	// exit();
	if( isBens() ){
		$consulta = mysql_query("SHOW TABLES LIKE 'user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens' ");
	    mysql_num_rows($consulta);
	    if( mysql_num_rows($consulta) == 0 ) {
	        mysql_query("CREATE TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` (`id` int(11) NOT NULL,`id_item` int(11) NOT NULL,`valor_pago` float NOT NULL,`tipo` varchar(50) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");
			mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` ADD PRIMARY KEY (`id`); ");
			mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
	    }
		mysql_query("INSERT INTO `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens`(`id_item`, `tipo`) VALUES ( '".$last_id."' , '".$_POST['tipo_bem']."' )");
	}

	if( cadsatrarImobilizados() ){
		include 'datas.class.php';
		$datas = new Datas();

		function getCategoria($string,$categoria){
			if( $string == 'Terreno' )
				return 'Terreno';
			if( $string == 'Apartamento' )
				return 'Imóveis (prédios)';
			if( $string == 'Máquinas e equipamentos' )
				return 'Máquinas e equipamentos';
			if( $string == 'Computadores e periféricos' )
				return 'Computadores e periféricos';
			return $categoria;
		}

		$id_user = $_SESSION['id_empresaSecao'];
		$ano = $datas->getAno($Data);
		$item = getCategoria($_POST['tipo_bem'],$_POST["selCategoria"]);
		$quantidade = 1;
		$valor = $Valor;
		$ano_item = $datas->getAno($Data);
		$data = $Data;

		mysql_query("	INSERT INTO `imobilizados` 
							(`id_user`, `ano`, `item`, `quantidade`, `valor`, `ano_item`, `data`, `id_livro_caixa`) 
						VALUES 
							('".$id_user."','".$ano."','".$item."','".$quantidade."','".$valor."','".$ano_item."','".$data."','".$last_id."')");
	}

	$uploadAnexos = new UploadAnexos();
	$uploadAnexos->execute($last_id,$ID,$DocumentoNumero,$Data);
	   
	$files = $uploadAnexos->nomes;
	foreach ($files as $file) {
		// echo "INSERT INTO `comprovantes`(`id`, `id_user`,`id_lancamento`, `nome`, `data`) VALUES ( '','".$ID."','".$last_id."','".$file."','' )";
		$consulta = mysql_query("INSERT INTO `comprovantes`(`id`, `id_user`,`id_lancamento`, `nome`, `data`) VALUES ( '','".$ID."','".$last_id."','".$file."','".date('Y-m-d H:m:s')."' )");
		$objeto=mysql_fetch_array($consulta);

	}

	//FIM
	header('Location: livros_caixa_movimentacao.php' );
?>