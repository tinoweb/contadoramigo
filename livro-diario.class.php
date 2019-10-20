<?php 

	class Itens_plano_contas{
		private $id;
		private $codigo;
		private $descricao;
		function __construct($dados=NULL){
			if( $dados != NULL ){
				$this->setid($dados['id']);
				$this->setcodigo($dados['codigo']);
				$this->setdescricao($dados['descricao']);
			}
		}
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
		function getcodigo(){
			return $this->codigo;
		}
		function setcodigo($string){
			$this->codigo = $string;
		}
		function getdescricao(){
			return $this->descricao;
		}
		function setdescricao($string){
			$this->descricao = $string;
		}
	}
	class Item_livro_diario{
		private $categoria;
		private $descricao;
		private $valor;
		private $data;
		private $relacao_cr_db;
		function getdescricao(){
			return $this->descricao;
		}
		function setdescricao($string){
			$this->descricao = $string;
		}
		function getdata(){
			return $this->data;
		}
		function setdata($string){
			$this->data = $string;
		}
		function getvalor(){
			return $this->valor;
		}
		function setvalor($item){
			if( $item['entrada'] > 0 )
				$this->valor = $item['entrada'];
			else
				$this->valor = $item['saida'];
		}
		function getcategoria(){
			return $this->categoria;
		}
		function setcategoria($string){
			$this->categoria = $string;
		}
		function getrelacao_cr_db(){
			return $this->relacao_cr_db;
		}
		function setrelacao_cr_db($string){
			$this->relacao_cr_db = $string;
		}
	}
	class Relacao_livro_diario{
		private $id;
		private $categoria;
		private $db;
		private $cr;
		private $condicao;
		private $tipo;
		function getcategoria(){
			return $this->categoria;
		}
		function setcategoria($string){
			$this->categoria = $string;
		}
		function getdb(){
			return $this->db;
		}
		function setdb($string){
			$this->db = $string;
		}
		function getcr(){
			return $this->cr;
		}
		function setcr($string){
			$this->cr = $string;
		}
		function getcondicao(){
			return $this->condicao;
		}
		function setcondicao($string){
			$this->condicao = $string;
		}
		function gettipo(){
			return $this->tipo;
		}
		function settipo($string){
			$this->tipo = $string;
		}
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
		function __construct($dados=NULL){
			if( $dados != NULL ){
				$this->setid($dados['id']);
				$this->setcategoria($dados['categoria']);
				$this->setdb($dados['db']);
				$this->setcr($dados['cr']);
				$this->setcondicao($dados['condicao']);
				$this->settipo($dados['tipo']);
			}
		}
	}
	class Livro_diario{
		private $id_user;
		private $prazo;
		private $cnae;
		private $id_user_pagamento;
		private $tipo_user_pagamento;
		function gettipo_user_pagamento(){
			return $this->tipo_user_pagamento;
		}
		function settipo_user_pagamento($string){
			$this->tipo_user_pagamento = $string;
		}
		function getid_user_pagamento(){
			return $this->id_user_pagamento;
		}
		function setid_user_pagamento($string){
			$this->id_user_pagamento = $string;
		}
		function getcnae(){
			return $this->cnae;
		}
		function setcnae($string){
			$this->cnae = $string;
		}
		function getprazo(){
			return $this->prazo;
		}
		function setprazo($string){
			$this->prazo = $string;
		}
		function getid_user(){
			return $this->id_user;
		}
		function setid_user($string){
			$this->id_user = $string;
		}
		//Função que retorna a descricao de uma categoria de DB atraves do codigo
		function getNomeCategoriaDB($string){
			$consulta = mysql_query("SELECT * FROM livro_diario_db WHERE codigo = '".$string."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			$item = new Itens_plano_contas($objeto_consulta);
			if( mysql_num_rows($consulta) > 0 )
				return $item->getdescricao();
			else
				return NULL;
		}		
		//Função que retorna a descricao de uma categoria de CR atraves do codigo
		function getNomeCategoriaCR($string){
			$consulta = mysql_query("SELECT * FROM livro_diario_cr WHERE codigo = '".$string."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			$item = new Itens_plano_contas($objeto_consulta);
			if( mysql_num_rows($consulta) > 0 )
				return $item->getdescricao();
			else
				return NULL;
		}		
		//Cosulta os dados de uma determinada relação de acordo com a categoria da relacao
		function sqlGetRelacaoCategoria($categoria,$tipo){
			if( $tipo == 3 ){
				$categoria = "Clientes";
				$tipo = 0;
			}
			$consulta = mysql_query("SELECT * FROM livro_diario_relacao WHERE categoria = '".$categoria."' AND tipo = '".$tipo."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return new Relacao_livro_diario($objeto_consulta);
		}
		//verifica se a agua e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoAgua(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND categoria = 'Água' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			else
				return 2;			
		}
		//verifica se aluguel de software e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoAluguelSoftware(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND categoria = 'Aluguel de softwares' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			else
				return 2;
		}//verifica se combustível e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoCombustivel(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND categoria = 'Combustível' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			else
				return 2;
		}
		//Retorna o tipo de usuario que recebeu o pagamento atraves da tabela e id passados
		function definirCondicaoEstagiario(){
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Administrativas' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Administrativas e Operacionais' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Operacionais' )
				return 2;
			return 1;
		}
		//verifica se a internet e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoInternet(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND categoria = 'Internet' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			else
				return 2;			
		}
		//verifica se a Manutenção de equipamentos e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoManutencaoEquipamento(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND categoria = 'Manutenção de equipamentos' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			else
				return 2;
		}
		//Define a condição para a categoria manutenção de veículos
		function definirCondicaoManutencaoVeiculos(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND categoria = 'Combustível' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			return 2;
		}
		//Define as condições para categoria pagamentos de salários
		function definirCondicaoPagamentoSalarios(){
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Administrativas' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Administrativas e Operacionais' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Operacionais' )
				return 2;
			return 1;
		}
		//Define as condições para categoria pagamentos a autonomos e fornecedores
		function definirCondicaoPagamentosAutonomosFornecedores(){
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Administrativas' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Administrativas e Operacionais' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Operacionais' )
				return 2;
			return 1;
		}
		//Define as condições para categoria pagamentos de pro labore
		function definirCondicaoProLabora(){
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Administrativas' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Administrativas e Operacionais' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Operacionais' )
				return 2;
			return 1;
		}
		//Define as condições para categoria pagamentos de vale refeicao
		function definirCondicaoValeRefeicao(){
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Administrativas' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Administrativas e Operacionais' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Operacionais' )
				return 2;
			return 1;
		}
		//Define as condições para categoria pagamentos de vale transporte
		function definirCondicaoValeTransporte(){
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Administrativas' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Administrativas e Operacionais' )
				return 1;
			if( $this->verificarAdministrativoOperacional() == 'Exclusivamente Operacionais' )
				return 2;
			return 1;
		}
		//Retorna o tipo de usuario que recebeu o pagamento atraves da tabela e id passados
		function verificarAdministrativoOperacional(){
			if( $this->gettipo_user_pagamento() == 'estagiario' )
				return $this->getSqlUserPagamento('estagiarios','id');
			if( $this->gettipo_user_pagamento() == 'autonomo' )
				return $this->getSqlUserPagamento('dados_autonomos','id');
			if( $this->gettipo_user_pagamento() == 'socio' )
				return $this->getSqlUserPagamento('dados_do_responsavel','idSocio');
			if( $this->gettipo_user_pagamento() == 'fornecedor' )
				return $this->getSqlUserPagamento('dados_pj','id');
		}
		//Retorna o tipo do usuario que recebeu o pagamento, se é administrativo ou operacional
		function getSqlUserPagamento($tabela,$campo){
			$consulta = mysql_query("SELECT * FROM ".$tabela." WHERE ".$campo." = '".$this->getid_user_pagamento()."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return $objeto_consulta['tipo'];
		}
		//verifica se Viagens e deslocamentos e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoViagensDeslocamentos(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND categoria = 'Viagens e deslocamentos' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			else
				return 2;
		}
		//Define se pega o tipo 1 ou do 2 emprestimo de acordo com o prazo de pagamento
		function definirCondicaoEmprestimo(){
			if( $this->getprazo() < 12 )
				return 1;
			else if( $this->getprazo() >= 12 )
				return 2;
		}
		//Função que normaliza algumas categorias com nomes diferentes, porém referenteas ao mesmo item
//		function normalizarCategoria($string){
//			if( $string == 'Licença ou aluguel de softwares' )
//				return 'Aluguel de softwares';
//			if( $string == 'Pagto. de salários' )
//				return 'Pagto. de Salários';
//			if( $string == 'Pgto. de salários' )
//				return 'Pagto. de Salários';
//			return $string;
//		}
		//Define o tipo do item da categoria, caso nao exista tipos diferentes para a mesma categoria, retorna sempre 0
		function definirTipo($categoria){
			$consulta = mysql_query("SELECT * FROM livro_diario_relacao WHERE categoria = '".$categoria."'  ");
			if( mysql_num_rows($consulta) == 2 ){
				if( $categoria == 'Aluguel de softwares' )
					return $this->definirCondicaoAluguelSoftware();
				if( $categoria == 'Água' )
					return $this->definirCondicaoAgua();
				if( $categoria == 'Combustível' )
					return $this->definirCondicaoCombustivel();
				if( $categoria == 'Empréstimos' )
					return $this->definirCondicaoEmprestimo();
				if( $categoria == 'Estagiários' )
					return $this->definirCondicaoEstagiario();
				if( $categoria == 'Internet' )
					return $this->definirCondicaoInternet();
				if( $categoria == 'Manutenção de equipamentos' )
					return $this->definirCondicaoManutencaoEquipamento();
				if( $categoria == 'Manutenção de Veículos' )
					return $this->definirCondicaoManutencaoVeiculos();
				if( $categoria == 'Pagto. de Salários' )
					return $this->definirCondicaoPagamentoSalarios();
				if( $categoria == 'Pgto. a autônomos e fornecedores' )
					return $this->definirCondicaoPagamentosAutonomosFornecedores();
				if( $categoria == 'Pró-Labore' )
					return $this->definirCondicaoProLabora();
				if( $categoria == 'Vale-Refeição' )
					return $this->definirCondicaoValeRefeicao();
				if( $categoria == 'Vale-Transporte' )
					return $this->definirCondicaoValeTransporte();
				if( $categoria == 'Viagens e deslocamentos' )
					return $this->definirCondicaoViagensDeslocamentos();
			}
			else{
				$consulta = mysql_query("SELECT * FROM dados_clientes WHERE id_login = '".$_SESSION['id_empresaSecao']."' ");
				while( $objeto_consulta = mysql_fetch_array($consulta) ){
					if( $objeto_consulta['apelido'] == $categoria ){
						return 3;
					}
				}
				return 0;
			}		
		}
		//Pega o objeto relacao livro diario de acordo com a categoria
		function getRelacao($categoria){
			//$categoria = $this->normalizarCategoria($categoria);
			$item = $this->sqlGetRelacaoCategoria($categoria,$this->definirTipo($categoria));
			return $item;
		}
		function __construct(){
		}
		function ifEmprestimo($item){
			$livro_caixa_emprestimos = mysql_query("SELECT * FROM user_".$_SESSION['id_empresaSecao']."_livro_caixa_emprestimos WHERE id_item = '".$item['id']."' ");
			$objeto_consulta = mysql_fetch_array($livro_caixa_emprestimos);
			if( mysql_num_rows($livro_caixa_emprestimos) > 0 )
				return $objeto_consulta['meses'];
			else 
				return 0;
		}
		function ifPagamento($item){
			$livro_caixa_emprestimos = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$_SESSION['id_empresaSecao']."' AND idLivroCaixa = '".$item['id']."' ");
			$objeto_consulta = mysql_fetch_array($livro_caixa_emprestimos);
			if( mysql_num_rows($livro_caixa_emprestimos) > 0 ){
				if( $objeto_consulta['id_autonomo'] != 0 )
					return $objeto_consulta['id_autonomo'];
				if( $objeto_consulta['id_socio'] != 0 )
					return $objeto_consulta['id_socio'];
				if( $objeto_consulta['id_lucro'] != 0 )
					return $objeto_consulta['id_lucro'];
				if( $objeto_consulta['id_estagiario'] != 0 )
					return $objeto_consulta['id_estagiario'];
				if( $objeto_consulta['id_pj'] != 0 )
					return $objeto_consulta['id_pj'];
			}
		}
		function ifPagamentoTipo($item){
			$livro_caixa_emprestimos = mysql_query("SELECT * FROM dados_pagamentos WHERE id_login = '".$_SESSION['id_empresaSecao']."' AND idLivroCaixa = '".$item['id']."' ");
			$objeto_consulta = mysql_fetch_array($livro_caixa_emprestimos);
			if( mysql_num_rows($livro_caixa_emprestimos) > 0 ){
				if( $objeto_consulta['id_autonomo'] != 0 )
					return 'autonomo';
				if( $objeto_consulta['id_socio'] != 0 )
					return 'socio';
				if( $objeto_consulta['id_estagiario'] != 0 )
					return 'estagiario';
				if( $objeto_consulta['id_pj'] != 0 )
					return 'fornecedor';
			}
		}
	}
	class Plano_de_contas{
		private $array_db;
		private $array_db_sub;
		private $array_cr;
		private $array_cr_sub;
		private $array_itens;
		function getarray_itens(){
			return $this->array_itens;
		}
		function setarray_itens($string){
			$this->array_itens[] = $string;
		}
		function getarray_db(){
			return $this->array_db;
		}
		function setarray_db($string){
			$this->array_db[] = $string;
		}
		function getarray_cr(){
			return $this->array_cr;
		}
		function setarray_cr($string){
			$this->array_cr[] = $string;
		}
		function getarray_cr_sub(){
			return $this->array_cr_sub;
		}
		function setarray_cr_sub($string){
			$this->array_cr_sub = $string;
		}
		function getarray_db_sub(){
			return $this->array_db_sub;
		}
		function setarray_db_sub($string){
			$this->array_db_sub = $string;
		}
		//Debita um item
		function debitar($categoria,$item,$valor){
			//Acumula o item no plano de contas na categoria correspondente, caso não exissta, cria uma posição para o item
			if( isset($this->array_db[$item->getdb()]) )
				$this->array_db[$item->getdb()] = floatval($this->array_db[$item->getdb()]) + floatval($valor);
			else
				$this->array_db[$item->getdb()] = floatval($valor);
			//Acumula o item nas subcategorias para o item no plano de contas, caso nao exista, cria a posição
			if( isset($this->array_db_sub[$item->getdb()]) ){//Se ja existe, pega o array
				$array = $this->array_db_sub[$item->getdb()];
				if( isset($array[$categoria]) )//se ja existe a categoria no array, acumula
					$array[$categoria] = $array[$categoria] + $valor;
				else{//se nao existe, cria e atribui o valor
					$array[$categoria] = $valor;
				}
				//Seta o array para a categoria correspondente
				$this->array_db_sub[$item->getdb()] = $array;
			}
			else{
				$array = array();
				$array[$categoria] = $valor;
				$this->array_db_sub[$item->getdb()] = $array;
			}
		}
		//Credita um item
		function creditar($categoria,$item,$valor){
			//Acumula o item no plano de contas na categoria correspondente, caso não exissta, cria uma posição para o item
			if( isset($this->array_cr[$item->getcr()]) )
				$this->array_cr[$item->getcr()] = floatval($this->array_cr[$item->getcr()]) + floatval($valor);
			else
				$this->array_cr[$item->getcr()] = floatval($valor);
			//Acumula o item nas subcategorias para o item no plano de contas, caso nao exista, cria a posição
			if( isset($this->array_cr_sub[$item->getcr()]) ){//Se ja existe, pega o array
				$array = $this->array_cr_sub[$item->getcr()];
				if( isset($array[$categoria]) )//se ja existe a categoria no array, acumula
					$array[$categoria] = $array[$categoria] + $valor;
				else{//se nao existe, cria e atribui o valor
					$array[$categoria] = $valor;
				}
				//Seta o array para a categoria correspondente
				$this->array_cr_sub[$item->getcr()] = $array;
			}
			else{
				$array = array();
				$array[$categoria] = $valor;
				$this->array_cr_sub[$item->getcr()] = $array;
			}
		}
		function setItemAgrupado($categoria,$item,$valor){
			$this->debitar($categoria,$item,$valor);
			$this->creditar($categoria,$item,$valor);
		}
		//Insere um credito e um debito
		function setItem($item){
			$this->setarray_itens($item);
		}
		//Pega o total de debitos
		function getDb(){
			$total = 0;
			foreach ($this->array_db as $value) {
				$total = $total + $value;
			}
			return $total;
		}
		//Pega o total de cretidos
		function getCr(){
			$total = 0;
			foreach ($this->array_cr as $value) {
				$total = $total + $value;
			}
			return $total;
		}
		//Retorna o valor no formato de dinheiro
		function setMoney($string){
			return number_format( $string , 2 , ',' , '.' );
		}
		//Função que lista os itens do livro diario
		function listarItens(){
			$datas = new Datas();
			echo '<table><tbody>';
			echo '	<tr>
						<th align="left" width="100">Data</th>
						<th align="left" width="250">Creditado</th>
						<th align="left" width="250">Debitado</th>
						<th align="left" width="300">Descricao</th>
						<th align="left" width="100">Valor</th>
					</tr>';
			$livro_diario = new Livro_diario();
			//Percorre cada item exibindo a data, numero da categoria e categoria para creditado e debitado, descrição do item e valor
			foreach ($this->array_itens as $item ) {
				echo '<tr>';
				echo '<td align="left">'.$datas->desconverterData($item->getdata()).'</td>';
				echo '<td align="left">'.$item->getrelacao_cr_db()->getDb().' - '.$livro_diario->getNomeCategoriaDB($item->getrelacao_cr_db()->getDb()).'</td>';
				echo '<td align="left">'.$item->getrelacao_cr_db()->getCr().' - '.$livro_diario->getNomeCategoriaCR($item->getrelacao_cr_db()->getCr()).'</td>';
				echo '<td align="left">'.$item->getdescricao().'</td>';
				echo '<td align="right">'.$this->setMoney($item->getvalor()).'</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		//Lista o itens debitados
		function listarItensDb(){
			$livro_diario = new Livro_diario();
			echo '<table><tbody>';
			//Ordena os itens pela chave
			ksort($this->array_db);
			//Percorre cada item do array e monta o livro razao com os itens agrupados
			foreach ($this->array_db as $categoria_debitada => $total) {
				echo '<tr>';
				//Pega o nome da categoria para o numero do item
				echo '<td>'.$livro_diario->getNomeCategoriaDB($categoria_debitada).'</td><td></td>';
				echo '</tr>';
				//Pega o nome da categoria do item do livro caixa relacionado com a categoria debitada
				$categorias = $this->array_db_sub[$categoria_debitada];
				//Ordena o itens
				ksort($categorias);
				//Percorre cada item do livro caixa relacionado com a categoria debitada
				foreach ($categorias as $categoria => $valor) {
					echo '<tr>';
					echo '<td style="padding-left:10px;">'.$categoria.'</td>';
					echo '<td align="right">'.$this->setMoney($valor).'</td>';
					echo '</tr>';
				}
				echo '<td style="padding-left:10px;">	Total</td><td align="right">'.$this->setMoney($total).'</td>';
				echo '<tr><td></td><td></td></tr>';
			}
			//Mostra o total debitado para todas as categorias
			echo '<tr><td>Total Creditado</td><td align="right">'.$this->setMoney($this->getDb()).'</td></tr>';
			echo '</tbody></table>';
		}
		//Lista os itens creditados
		function listarItensCr(){
			$livro_diario = new Livro_diario();
			echo '<table><tbody>';
			//Ordena os itens pela chave
			ksort($this->array_cr);
			//Percorre cada item do array e monta o livro razao com os itens agrupados
			foreach ($this->array_cr as $categoria_creditada => $total) {
				echo '<tr>';
				//Pega o nome da categoria para o numero do item
				echo '<td>'.$livro_diario->getNomeCategoriaCR($categoria_creditada).'</td><td></td>';
				echo '</tr>';
				//Pega o nome da categoria do item do livro caixa relacionado com a categoria Creditada
				$categorias = $this->array_cr_sub[$categoria_creditada];
				//Ordena o itens
				ksort($categorias);
				//Percorre cada item do livro caixa relacionado com a categoria creditada
				foreach ($categorias as $categoria => $valor) {
					echo '<tr>';
					echo '<td style="padding-left:10px;">'.$categoria.'</td>';
					echo '<td align="right">'.$this->setMoney($valor).'</td>';
					echo '</tr>';
				}
				echo '<td style="padding-left:10px;">	Total</td><td align="right">'.$this->setMoney($total).'</td>';
				echo '<tr><td></td><td></td></tr>';
			}
			//Mostra o total creditado para todas as categorias
			echo '<tr><td>Total Creditado</td><td align="right">'.$this->setMoney($this->getCr()).'</td></tr>';
			echo '</tbody></table>';
		}
		//Construtor da função, inicializando as variaveis que serão usadas
		function __construct(){
			//Inicia cada um dos arrays
			$this->array_db = array();
			$this->array_cr = array();
			$this->array_db_sub = array();
			$this->array_cr_sub = array();
			$this->array_itens = array();
		}
	}
?>