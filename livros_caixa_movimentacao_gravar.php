<?php

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

	session_start();
	include "conect.php";
	//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
		//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

		// - Essa trecho de codigo trabalha para inserir os uploads no banco e no servidor
	class UploadAnexos{
			
			public $nomes;

			private function Normaliza($string) {
				$table = array('Š'=>'S','š'=>'s','Đ'=>'Dj','đ'=>'dj','Ž'=>'Z','ž'=>'z','Č'=>'C','č'=>'c','Ć'=>'C','ć'=>'c','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E',
				'Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss',
				'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
				'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','Ŕ'=>'R','ŕ'=>'r','?'=>'');
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

							$ret = move_uploaded_file($__FILES["tmp_name"][$i],"upload/comprovantes/".$file_name);

						 }
					}
				}
			}
			
		}

	//FIM

	$ID = $_POST["hidID"];
	$Linha = $_POST["hidLinha"];
	$Valor = str_replace(",",".",str_replace(".","",$_POST["txtValor"]));
	$TipoLancamento = $_POST["selTipoLancamento"];
	$DocumentoNumero = mysql_real_escape_string($_POST["txtDocumentoNumero"]);
	$Descricao = mysql_real_escape_string($_POST["txtDescricao"]);
	$EmprestimoPaiId = isset($_POST['registroPaiIdAmortizacao']) && !empty($_POST['registroPaiIdAmortizacao']) ? $_POST['registroPaiIdAmortizacao'] : false;
	$emprestimoId = isset($_POST['emprestimoId']) && !empty($_POST['emprestimoId']) ? $_POST['emprestimoId'] : false;

	if(isset($_POST["selCategoria"]) && $_POST["selCategoria"] != ""){
		$categoria = ", categoria = '" . mysql_real_escape_string($_POST["selCategoria"]) . "'";
	}
	$dia = substr($_POST["txtData"], 0, 2);
	$mes = substr($_POST["txtData"], 3, 2);
	$ano = substr($_POST["txtData"], 6, 4);
	$Data = date('Y-m-d',mktime(0,0,0,$mes,$dia,$ano));

	if ($TipoLancamento == 'entrada') {
		$entrada = $Valor;
		$saida = 0;
	}
	else {
		$entrada = 0;
		$saida = $Valor;
	}

	if($EmprestimoPaiId && $emprestimoId) {

		// Prepara consulta do emprestimo.
		$query = "SELECT * FROM dados_do_emprestimo 
				WHERE tipo = 'amortizacao' 
				AND registroPaiId = '".$EmprestimoPaiId."'
				AND emprestimoId != '".$emprestimoId."'
				AND data_emprestimo = '".$Data."';";
		
		// Execulta consulta.
		$resultado = mysql_query($query);
		
		if( mysql_num_rows($resultado) > 0 ) {
			
			$_SESSION['erroEmprestimo'] = 'editar';
		
			header('Location: livros_caixa_movimentacao.php' );

			die();
		}
	}

	function isPagamento(){
		if( $_POST['selCategoria'] == 'Estagiários' )
			return true;
		if( $_POST['selCategoria'] == 'Pgto. a autônomos e fornecedores' )
			return true;
		// if( $_POST['selCategoria'] == 'Pagto. de salários' )
		// 	return true;
		if( $_POST['selCategoria'] == 'Pró-Labore' )
			return true;
		return false;
	}
	if( isPagamento() ){
		$Descricao = $_POST['nome_pagamento'];
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

	function isBens(){
		if( $_POST['selCategoria'] == 'Imóveis' || $_POST['selCategoria'] == 'Máquinas e equipamentos' )
			return true;
		else
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

	if( isEmprestimo() ){
		if ($consulta = mysql_query("SHOW TABLES LIKE 'user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos' ")) {
		    if( mysql_num_rows($consulta) == 0 ) {
		        mysql_query("CREATE TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` (`id` int(11) NOT NULL,`id_item` int(11) NOT NULL,`valor_pago` float NOT NULL,`meses` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");
				mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` ADD PRIMARY KEY (`id`); ");
				mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
		    }
		    $consulta = mysql_query("SELECT * FROM `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` WHERE id_item = '".$Linha."' ");
		    $objeto_consulta = mysql_fetch_array($consulta);
		    $Valor_pago = str_replace(",",".",str_replace(".","",$_POST["valor_pago"]));
		    if( $objeto_consulta['id'] == '' )
		    	mysql_query("INSERT INTO `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` (`id_item`, `meses`, `valor_pago`) VALUES ( '".$Linha."' , '".$_POST['prazo_carencia']."', '".$Valor_pago."' )");
		    else
				mysql_query("UPDATE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos` SET `meses`= '".$_POST['prazo_carencia']."' , `valor_pago`= '".$Valor_pago."' WHERE id_item = '".$Linha."'   ");
		}
	}
	if( isBens() ){
		$consulta = mysql_query("SHOW TABLES LIKE 'user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens' ");
	    if( mysql_num_rows($consulta) == 0 ) {
	        mysql_query("CREATE TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` (`id` int(11) NOT NULL,`id_item` int(11) NOT NULL,`valor_pago` float NOT NULL,`tipo` varchar(50) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;");
			mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` ADD PRIMARY KEY (`id`); ");
			mysql_query("ALTER TABLE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
	    }
	    $consulta = mysql_query("SELECT * FROM `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` WHERE id_item = '".$Linha."' ");
	    $objeto_consulta = mysql_fetch_array($consulta);
	    if( $objeto_consulta['id'] == '' )
	    	mysql_query("INSERT INTO `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` (`id_item`, `tipo`) VALUES ( '".$Linha."' , '".$_POST['tipo_bem']."' )");
	    else
			mysql_query("UPDATE `user_".$_SESSION['id_empresaSecao']."_livro_caixa_bens` SET `tipo`= '".$_POST['tipo_bem']."' WHERE id_item = '".$Linha."'   ");
	}
	else{

		mysql_query("DELETE FROM user_" . $_SESSION['id_empresaSecao'] . "_livro_caixa_bens WHERE id_item='" . $Linha . "'");
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

		mysql_query("	UPDATE `imobilizados` 
							SET `ano`='".$ano."', `item`='".$item."', `quantidade`='".$quantidade."', `valor`='".$valor."', `ano_item`='".$ano_item."', `data`='".$data."' 
						WHERE id_livro_caixa = '".$Linha."' AND id_user = '".$ID."' ");
	}
	else{
		mysql_query("DELETE FROM tabela WHERE id_livro_caixa = '".$Linha."' AND id_user = '".$ID."'  ");
	}

	$sql = "UPDATE user_" . $ID . "_livro_caixa SET 
			data='$Data'
			, entrada='$entrada'
			, saida='$saida'
			, documento_numero='$DocumentoNumero'
			, descricao='$Descricao' 
			" . $categoria . "
			WHERE id='$Linha'";
			
	$resultado = mysql_query($sql)
	or die (mysql_error());

	/**** Emprestimos - INICIO **********************************************************/
		
	$consulta = "SELECT * FROM dados_do_emprestimo WHERE empresaId = '".$ID."' AND livro_caixa_id = '".$Linha."'";

	echo $consulta;


	$resultado = mysql_query($consulta);

	if( mysql_num_rows($resultado) > 0 ) {
		
		if(isEmprestimo()){

			// Pega a linhas do emprestimo.
			$emprestimoId = $_POST['emprestimoId'];

			$carencia = (isset($_POST['prazo_carencia']) && !empty($_POST['prazo_carencia']) ? "'".$_POST['prazo_carencia']."'" : "NULL");
			$apelido = (isset($_POST['apelido']) && !empty($_POST['apelido']) ? "'".$_POST['apelido']."'" : "NULL");

			if($_POST['acaoEmprestimo'] == 'editarEmprestimo') {		

				$update = "UPDATE dados_do_emprestimo SET 
							data_emprestimo = '".$Data."',
							apelido = ".$apelido.",
							carencia = ".$carencia.",
							entrada = '".$entrada."'
						WHERE empresaId = '".$ID."'
						AND emprestimoId = '".$emprestimoId."'";

				echo $update;
				
				// Execulta a ataulização
				mysql_query($update);
				
				// Chama o método para atualizar o juros da armotização do empréstimo.
				IniciaAtualizacaoJuros($ID, $emprestimoId);
		
			} elseif($_POST['acaoEmprestimo'] == 'editarComplementar') {	
				
				$update = "UPDATE dados_do_emprestimo SET 
							data_emprestimo = '".$Data."',
							entrada = '".$entrada."'
						WHERE empresaId = '".$ID."'
						AND emprestimoId = '".$emprestimoId."'";

				// Execulta a ataulização
				mysql_query($update);
				
				$registroPaiId = $_POST['registroPaiId'];
				
				// Chama o método para atualizar o juros da armotização.
				IniciaAtualizacaoJuros($ID, $registroPaiId);
			}
		}

		if(isAmortizacaoEmprestimo()) {

			$emprestimoId = $_POST['emprestimoId'];

			if($_POST['acaoAmortizacao'] == 'editarAmortizacao') {	

				$saldoRemanescente = str_replace(',','.',str_replace('.','',$_POST['saldoRemanescente']));
				$juros = 0;
				$registroPaiId = "'".$_POST['registroPaiIdAmortizacao']."'";

				$update = "UPDATE dados_do_emprestimo 
						   SET data_emprestimo = '".$Data."',
							   amortizacao = '".$saida."',
							   juros = '".$juros."',
							   saldo_remanescente = '".$saldoRemanescente."'
							WHERE empresaId = '".$ID."'
							AND emprestimoId = '".$emprestimoId."'";

				// Execulta a ataulização
				mysql_query($update);
		
				// Chama o método para atualizar o juros da armotização.
				IniciaAtualizacaoJuros($ID, $registroPaiId);
			}
		}

	} else {

		if(isEmprestimo()){

			$carencia = (isset($_POST['prazo_carencia']) && !empty($_POST['prazo_carencia']) ? "'".$_POST['prazo_carencia']."'" : "NULL");
			$apelido = (isset($_POST['apelido']) && !empty($_POST['apelido']) ? "'".$_POST['apelido']."'" : "NULL");
			$registroPaiId = (isset($_POST['registroPaiId']) && !empty($_POST['registroPaiId']) ? "'".$_POST['registroPaiId']."'" : "NULL");

			$insert = "INSERT INTO dados_do_emprestimo (empresaId, livro_caixa_id, data_inclusao, data_emprestimo, apelido, carencia, entrada, tipo, registroPaiId) VALUES ('".$ID."', '".$Linha."' , NOW(), '".$Data."',	".$apelido.", ".$carencia.", '".$entrada."','emprestimo', ".$registroPaiId.")";

			mysql_query($insert);
		}

		if(isAmortizacaoEmprestimo()) {

			$saldoRemanescente = str_replace(',','.',str_replace('.','',$_POST['saldoRemanescente']));
			$juros = 0;
			$registroPaiId = "'".$_POST['registroPaiIdAmortizacao']."'";
			
			$insert = "INSERT INTO dados_do_emprestimo (empresaId, livro_caixa_id, data_inclusao, data_emprestimo, amortizacao, juros, saldo_remanescente, tipo, registroPaiId) VALUES ('".$ID."', '".$Linha."' , NOW(), '".$Data."',	'".$saida."', '".$juros."', '".$saldoRemanescente."', 'amortizacao', ".$registroPaiId.")";

			mysql_query($insert);
			
			// Chama o método para atualizar o juros da armotização.
			IniciaAtualizacaoJuros($ID, $registroPaiId);
		}
	}

	/**** Empréstioms - FIM ***********************************************************/

	// Verifica se a alteração do lançamento que esta sendo realizada tem relacionado um lançamento de contas a pagar e a receber.
	if(isContasAReceber() || isContasAPagar()){
		
		// Prepara a consulta para pegar os dados do lançamento e verifica ser ele se relaciona com lançamento de contas a pagar e receber.
		$select2 = "SELECT * FROM lancamento_contas_pagar_receber WHERE empresaId = '".$ID."' AND livro_caixa_id_pagamento = '".$Linha."'";
		
		$result2 = mysql_query($select2);

		if( mysql_num_rows($result2) > 0 ) {
						
			$entrada = $saida = 0;
			
			// Pega os dados do relacionamento do contas a pagar ou a receber com o pagamento.
			$dados = mysql_fetch_array($result2);
			
			// pega o código da empresa.
			$empresaId = $_SESSION['id_empresaSecao'];

			// Converte a data para ser incluida no banco de dados.
			$data = date('Y-m-d', strtotime(str_replace('/','-',$_POST['dataNotaFiscal'])));
			$vencimento = date('Y-m-d', strtotime(str_replace('/','-',$_POST['dataVencimento'])));
			
			// Valor do documento.
			$valorOriginal = str_replace(",",".",str_replace(".","",$_POST["valorOriginal"]));	
			
			// Define se o valor a ser informado e de entrada ou saida.
			if( isContasAPagar() ) {
				$saida = $valorOriginal;
			} else {
				$entrada = $valorOriginal;
			}
			
			$update2 = "UPDATE user_" . $ID . "_livro_caixa 
					SET data = '".$data."'
						, saida = '".$saida."'
						, entrada = '".$entrada."'
						, documento_numero = '".$DocumentoNumero."'
						, descricao = '".$Descricao."'
					WHERE id='".$dados['livro_caixa_id']."'";
			
			mysql_query($update2) or die (mysql_error());
			
			$update3 = "UPDATE lancamento_contas_pagar_receber 
						SET vencimento = '".$vencimento."'
						WHERE empresaId = '".$ID."' 
						AND livro_caixa_id_pagamento = '".$Linha."'";
			
			mysql_query($update3) or die (mysql_error());			
				
		}	
	}

	//INICIO - Alteração dia 06/05/2016 - Uploads de arquivos junto com o lancamento
	//Arquivos: livros_caixa_movimentacao.php, livros_caixa_movimentacao_inserir.php, livros_caixa_movimentacao_gravar.php, livros_caixa_movimentacao_excluir.php

	// - Essa trecho de codigo trabalha para inserir os uploads no banco e no servidor

	$last_id = $Linha;

	$uploadAnexos = new UploadAnexos();
	$uploadAnexos->execute($last_id,$ID,$DocumentoNumero,$Data);
	$files = $uploadAnexos->nomes;
	foreach ($files as $file) {
		// echo "INSERT INTO `comprovantes`(`id`, `id_user`,`id_lancamento`, `nome`, `data`) VALUES ( '','".$ID."','".$last_id."','".$file."','' )";
		$consulta = mysql_query("SELECT * FROM comprovantes WHERE nome = '".$file."' AND id_lancamento = '".$last_id."' AND id_user = '".$ID."' ");

		$num_rows = mysql_num_rows($consulta);

		if($num_rows == 0){
			$consulta = mysql_query("INSERT INTO `comprovantes`(`id`, `id_user`,`id_lancamento`, `nome`, `data`) VALUES ( '','".$ID."','".$last_id."','".$file."','".date('Y-m-d H:m:s')."' )");
			$objeto=mysql_fetch_array($consulta);
		}
	}

	header('Location: livros_caixa_movimentacao.php' );
	?>