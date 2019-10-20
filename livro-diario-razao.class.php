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
		private $id;
		private $categoria;
		private $descricao;
		private $valor;
		private $data;
		private $emprestimo_carencia;
		private $data_pagamento_contas_PR;
		private $data_vencimento_Contas_PR;
		private $relacao_cr_db;
				
		function getid(){
			return $this->id;
		}
		function setid($string){
			$this->id = $string;
		}
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
			
			if( $item['entrada'] > 0 ) {
				$this->valor = $item['entrada'];
			} else {
				$this->valor = $item['saida'];
			}
		}
		function getcategoria(){
			return $this->categoria;
		}
		function setcategoria($string){
			$this->categoria = $string;
		}
				
		public function getdata_pagamento_contas_PR(){
			return $this->data_pagamento_contas_PR;
		}
		public function setdata_pagamento_contas_PR($val){
			$this->data_pagamento_contas_PR = $val;
		}
		
		public function getdata_vencimento_Contas_PR(){
			return $this->data_vencimento_Contas_PR;
		}
		public function setdata_vencimento_Contas_PR($val){
			$this->data_vencimento_Contas_PR = $val;
		}
		
		public function getemprestimo_carencia(){
			return $this->emprestimo_carencia;
		}
		public function setemprestimo_carencia($val){
			$this->emprestimo_carencia = $val;
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
			$consulta = mysql_query("SELECT * FROM livro_diario_plano_conta WHERE codigo = '".$string."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			$item = new Itens_plano_contas($objeto_consulta);
			if( mysql_num_rows($consulta) > 0 )
				return $item->getdescricao();
			else
				return NULL;
		}		
		//Função que retorna a descricao de uma categoria de CR atraves do codigo
		function getNomeCategoriaCR($string){
			$consulta = mysql_query("SELECT * FROM livro_diario_plano_conta WHERE codigo = '".$string."' ");
			$objeto_consulta = mysql_fetch_array($consulta);
			$item = new Itens_plano_contas($objeto_consulta);
			if( mysql_num_rows($consulta) > 0 )
				return $item->getdescricao();
			else
				return NULL;
		}		
		//Cosulta os dados de uma determinada relação de acordo com a categoria da relacao
		function sqlGetRelacaoCategoria($categoria,$tipo,$item){
				
			if('Serviços prestados em geral' == $categoria) {
				
				if(!empty($item['categoria_secundaria_1']) || !empty($item['categoria_secundaria_2']) ) {
					$categoria = "Clientes Contas Pagas";
				} else {
					$categoria = "Clientes";
				}
				$tipo = 0;
				
			}
			
			if( $tipo == 3 ){
				
				if(!empty($item['categoria_secundaria_1']) || !empty($item['categoria_secundaria_2']) ) {
					$categoria = "Clientes Contas Pagas";
				} else {
					$categoria = "Clientes";
				}
				$tipo = 0;
			}
			$order = '';
			if( $tipo == 4 ){
				$tipo = 0;
				$order = 'ORDER BY id ASC LIMIT 1';

			}
			if( $tipo == 5 ){
				$order = 'ORDER BY id DESC LIMIT 1';
				$tipo = 0;
			}
	
			$consulta = mysql_query("SELECT * FROM livro_diario_relacao WHERE categoria = '".$categoria."' AND tipo = '".$tipo."' ".$order." ");
			$objeto_consulta = mysql_fetch_array($consulta);
			return new Relacao_livro_diario($objeto_consulta);
		}
		//verifica se a agua e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoAgua(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND (categoria = 'Água' OR categoria = 'Água a pagar' OR categoria = 'Água pago' )");
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
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND (categoria = 'Aluguel de softwares' OR categoria = 'Aluguel de softwares a pagar' OR categoria = 'Aluguel de softwares pago' ) ");
			$objeto_consulta = mysql_fetch_array($consulta);
			if( mysql_num_rows($consulta) == 0 )
				return 2;	
			if( $objeto_consulta['tipo'] == 'custo' )
				return 1;
			else
				return 2;
		}//verifica se combustível e custo ou despesa para o CNAE principal do usuario
		function definirCondicaoCombustivel(){
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND (categoria = 'Combustível' OR categoria = 'Combustível a pagar' OR categoria = 'Combustível pago') ");
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
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND (categoria = 'Internet' OR categoria = 'Internet apagar' OR categoria = 'Internet pago' )");
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
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND (categoria = 'Manutenção de equipamentos' OR categoria = 'Manutenção de equipamentos a pagar' OR categoria = 'Manutenção de equipamentos pago') ");
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
			$consulta = mysql_query("SELECT * FROM livro_diario_custo_despesa WHERE cnae = '".$this->getcnae()."' AND ( categoria = 'Manutenção de Veículos' OR categoria = 'Manutenção de Veículos a pagar' OR categoria = 'Manutenção de Veículos pago') ");
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
		function normalizarCategoria($string){
			if( $string == 'Licença ou aluguel de softwares' )
				return 'Aluguel de software';
			if( $string == 'Licença ou aluguel de software' )
				return 'Aluguel de software';
			if( $string == 'Pagto. de salários' )
				return 'Pagto. de Salários';
			if( $string == 'Pgto. de salários' )
				return 'Pagto. de Salários';
			if( $string == 'Despesas bancárias' )
				return 'Taxas e Comissões Bancárias';
			if( $string == 'Vendas' )
				return 'Vendas de Bens Patrimoniais';

			return $string;
		}
		//Define o tipo do item da categoria, caso nao exista tipos diferentes para a mesma categoria, retorna sempre 0
		function definirTipo($categoria,$item){
			$consulta = mysql_query("SELECT * FROM livro_diario_relacao WHERE categoria = '".$categoria."'  ");
			if( $categoria == 'Outros' )
				if( $item['entrada'] > 0 ){
					// echo 'entrada'.$item['entrada'].'<br>';
					return 4;
				}
				else{
					// echo 'saida'.$item['saida'].'<br>';
					return 5;

				}
			if( mysql_num_rows($consulta) == 2 ){
				if( in_array($categoria, array('Aluguel de software', 'Aluguel de software a pagar', 'Aluguel de software pago')) )
					return $this->definirCondicaoAluguelSoftware();
				if( in_array($categoria,array('Água','Água a pagar','Água pago')) )
					return $this->definirCondicaoAgua();
				if( in_array($categoria, array('Combustível', 'Combustível a pagar', 'Combustível pago')) )
					return $this->definirCondicaoCombustivel();
				if( $categoria == 'Empréstimos' )
					return $this->definirCondicaoEmprestimo();
				if( in_array($categoria, array('Estagiários', 'Estagiários a pagar', 'Estagiários pago')) )
					return $this->definirCondicaoEstagiario();
				if( in_array($categoria, array('Internet', 'Internet a pagar', 'Internet pago')) )
					return $this->definirCondicaoInternet();
				if( in_array($categoria, array('Manutenção de equipamentos', 'Manutenção de equipamentos a pagar', 'Manutenção de equipamentos pago')) )
					return $this->definirCondicaoManutencaoEquipamento();
				if( in_array($categoria, array('Manutenção de Veículos', 'Manutenção de Veículos a pagar', 'Manutenção de Veículos pago')) )
					return $this->definirCondicaoManutencaoVeiculos();
				if( in_array($categoria, array('Pagto. de Salários', 'Pagto. de Salários a pagar', 'Pagto. de Salários pago')) )
					return $this->definirCondicaoPagamentoSalarios();
				if( in_array($categoria, array('Pgto. a autônomos e fornecedores', 'Pgto. a autônomos e fornecedores a pagar', 'Pgto. a autônomos e fornecedores pago')) )
					return $this->definirCondicaoPagamentosAutonomosFornecedores();
				if( in_array($categoria, array('Pró-Labore', 'Pró-Labore a pagar', 'Pró-Labore pago')) )
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
		function getRelacao($categoria, $item){
			//$categoria = $this->normalizarCategoria($categoria);
			$itemReturn = $this->sqlGetRelacaoCategoria($categoria,$this->definirTipo($categoria,$item),$item);
			return $itemReturn;
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
		function PegaJurosAmortizacao($empresaId, $livroCaixaId) {
			
			$juros = 0;
		
			$consulta = "SELECT * FROM dados_do_emprestimo WHERE empresaId = '".$empresaId."' AND livro_caixa_id = '".$livroCaixaId."'";

			$resultado = mysql_query($consulta);

			if( mysql_num_rows($resultado) > 0 ){
				$row = mysql_fetch_array($resultado);
				
				$juros = $row['juros'];
			}

			return $juros;
		}
		function PegaDadosContasAReceberEAPagar($empresaId, $livroCaixaId) {
			
			$row = '';
		
			$consulta = " SELECT * FROM user_".$empresaId."_livro_caixa c, lancamento_contas_pagar_receber l "
					." WHERE c.id = l.livro_caixa_id "
					." AND l.livro_caixa_id_pagamento = '".$livroCaixaId."'"
					." AND l.empresaId = '".$empresaId."'";
			
			$resultado = mysql_query($consulta);

			if( mysql_num_rows($resultado) > 0 ){
				$row = mysql_fetch_array($resultado);
			}

			return $row;
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
		function selected($string1,$string2){
			if( $string1 == $string2 ){
				return 'selected="selected"';
			}
		}
		function getSqlCategorias(){
			return  mysql_query("SELECT * FROM `livro_diario_plano_conta` WHERE tipo_plano_conta = 'principal' group by codigo order by descricao ASC");
		}
		function getCategorias(){
			$categorias = $this->getSqlCategorias();
			$string = '';
			$array_marcacao = array();
			while( $categoria = mysql_fetch_array($categorias) ){
				if( $array_marcacao[$categoria['codigo']] != 1 ){
					$array_marcacao[$categoria['codigo']] = 1;
					$string .= '<option value="'.$categoria['codigo'].'" '.$this->selected($categoria['codigo'],$_GET['codigo']).'>'.$categoria['descricao'].'</option>';
				}
			}
			return $string;
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
		function listarItensDiario(){
			
			$out = "";
			
			
			$datas = new Datas();
			$out .= '<table border="0" cellspacing="2" cellpadding="4" style="font-size: 12px;"><tbody>';
			$out .= '	<tr>
						<th align="left" width="70">Data</th>
						<th align="left" width="30">Nº</th>
						<th align="left" width="250">Descrição</th>
						<th align="left" width="250">Debitado</th>
						<th align="left" width="250">Creditado</th>
						<th align="right" width="70">Valor</th>
					</tr>';
			
			$livro_diario = new Livro_diario();
			
			//Percorre cada item exibindo a data, numero da categoria e categoria para creditado e debitado, descrição do item e valor
			foreach ($this->array_itens as $item ) {
				
				$out .= '<tr id="item_'.$item->getid().'">'
					.'<td class="td_calendario" align="left">'.$datas->desconverterData($item->getdata()).'</td>'
					.'<td class="td_calendario" align="right">'.$item->getid().'</td>'
					.'<td class="td_calendario" align="left">'.$item->getcategoria().' - '.$item->getdescricao().'</td>'
					.'<td class="td_calendario" align="left">'.$item->getrelacao_cr_db()->getDb().' - '.$livro_diario->getNomeCategoriaDB($item->getrelacao_cr_db()->getDb()).'</td>'
					.'<td class="td_calendario" align="left">'.$item->getrelacao_cr_db()->getCr().' - '.$livro_diario->getNomeCategoriaCR($item->getrelacao_cr_db()->getCr()).'</td>'
					.'<td class="td_calendario" align="right">'.$this->setMoney($item->getvalor()).'</td>'
					.'</tr>';
			}
			$out .= ' </tbody></table>';
			
			echo $out;
		}
		//Função que lista os itens do livro diario
		function CSVListarItensDiario(){
			$datas = new Datas();
			$string = '';
			$string .= 'Data;Nº;Debitado;Creditado;Descrição;Valor
			';
			$livro_diario = new Livro_diario();
			//Percorre cada item exibindo a data, numero da categoria e categoria para creditado e debitado, descrição do item e valor

			foreach ($this->array_itens as $item ) {
				
				$string .= ''.$datas->desconverterData($item->getdata()).';';
				$string .= ''.$item->getid().';';
				$string .= ''.$item->getrelacao_cr_db()->getDb().' - '.$livro_diario->getNomeCategoriaDB($item->getrelacao_cr_db()->getDb()).';';
				$string .= ''.$item->getrelacao_cr_db()->getCr().' - '.$livro_diario->getNomeCategoriaCR($item->getrelacao_cr_db()->getCr()).';';
				$string .= ''.$item->getcategoria().' - '.$item->getdescricao().';';
				$string .= ''.$this->setMoney($item->getvalor()).'
				';
				
			}
			return $string;
		}
		//Função que lista os itens do livro diario
		function listarItensRazao(){
			
			$code = isset($_GET['codigo']) ? $_GET['codigo'] : '';
			
			$datas = new Datas();
			$livro_diario = new Livro_diario();
			
			$out = '<span class="tituloVermelho">'.$livro_diario->getNomeCategoriaDB($code).'</span><br>'
				 .'<table border="0" cellspacing="2" cellpadding="4" style="font-size: 12px;margin-top:10px;width:100%"><tbody>'
				 .'	<tr>
						<th align="left" width="70">Data</th>
						<th align="right" width="30">Nº</th>
						<th align="left" width="350">Descrição</th>
						<th align="right" width="200">Debitado</th>
						<th align="right" width="200">Creditado</th>
						<th align="right" width="70">Saldo</th>
					</tr>';
			//Percorre cada item exibindo a data, numero da categoria e categoria para creditado e debitado, descrição do item e valor
			$saldo = 0;

			foreach ($this->array_itens as $item ) {
				
				if( substr($item->getrelacao_cr_db()->getDb(), 0, -3) == $code ){
					$saldo = $saldo + $item->getvalor();
					$out .= '<tr>'
					.' <td class="td_calendario" align="left">'.$datas->desconverterData($item->getdata()).'</td>'
					.' <td class="td_calendario" align="right"><a target="_blank" href="livro-diario.php?DataInicio='.$datas->desconverterData($item->getdata()).'&DataFim='.$datas->desconverterData($item->getdata()).'&codigo_consulta='.$item->getid().'" title="">'.$item->getid().'</a></td>'
					.' <td class="td_calendario" align="left">'.$item->getcategoria().' - '.$item->getdescricao().'</td>'
					.' <td class="td_calendario" align="right">'.$this->setMoney($item->getvalor()).'</td>'
					.' <td class="td_calendario" align="right"></td>'
					.' <td class="td_calendario" align="right">'.$this->setMoney($saldo).'</td>'
					.'</tr>';
					
					$linha_cinza = false;
				}
				
				if( substr($item->getrelacao_cr_db()->getCr(), 0, -3) == $code ){
					$saldo = $saldo - $item->getvalor();
					
					$out .= ' <tr>'
					.' <td class="td_calendario" align="left">'.$datas->desconverterData($item->getdata()).'</td>'
					.' <td class="td_calendario" align="right"><a target="_blank" href="livro-diario.php?DataInicio='.$datas->desconverterData($item->getdata()).'&DataFim='.$datas->desconverterData($item->getdata()).'&codigo_consulta='.$item->getid().'" title="">'.$item->getid().'</a></td>'
					.' <td class="td_calendario" align="left">'.$item->getcategoria().' - '.$item->getdescricao().'</td>'
					.' <td class="td_calendario" align="right"></td>'
					.' <td class="td_calendario" align="right">'.$this->setMoney($item->getvalor()).'</td>'
					.' <td class="td_calendario" align="right">'.$this->setMoney($saldo).'</td>'
					.' </tr>';
					
					$linha_cinza = false;
				}
			}
			if( !isset($code) || !isset($linha_cinza) ){
				$out .= '<td class="td_calendario" align="right" style="height:17px"></td>'
				.' <td class="td_calendario" align="right" style="height:17px"></td>'
				.' <td class="td_calendario" align="right" style="height:17px"></td>'
				.' <td class="td_calendario" align="right" style="height:17px"></td>'
				.' <td class="td_calendario" align="right" style="height:17px"></td>'
				.' <td class="td_calendario" align="right" style="height:17px"></td>';
			}
			$out .= '</tbody></table>';
			
			
			echo $out;
		}
		function CSVItensRazao(){
			$datas = new Datas();
			$livro_diario = new Livro_diario();
			$string = '';
			$string.= '	Data;Nº;Debitado;Creditado;Descrição;Saldo;
						';
			//Percorre cada item exibindo a data, numero da categoria e categoria para creditado e debitado, descrição do item e valor
			$saldo = 0;

			foreach ($this->array_itens as $item ) {
				if( $item->getrelacao_cr_db()->getDb() == $_GET['codigo'] ){
					$saldo = $saldo + $item->getvalor();
					$string.= ''.$datas->desconverterData($item->getdata()).';';
					$string.= ''.$item->getid().';';
					$string.= ''.$this->setMoney($item->getvalor()).';';
					$string.= ';';
					$string.= ''.$item->getcategoria().' - '.$item->getdescricao().';';
					$string.= ''.$this->setMoney($saldo).'
					';
				}
				if( $item->getrelacao_cr_db()->getCr() == $_GET['codigo'] ){
					$saldo = $saldo - $item->getvalor();
					$string.= ''.$datas->desconverterData($item->getdata()).';';
					$string.= ''.$item->getid().';';
					$string.= ';';
					$string.= ''.$this->setMoney($item->getvalor()).';';
					$string.= ''.$item->getcategoria().' - '.$item->getdescricao().';';
					$string.= ''.$this->setMoney($saldo).'
					';
				}
			}
			return $string;
		}
		
		//Função que lista os itens do livro diario
		function PegaEstruturaDelancamentoComContas(){
			return $this->array_itens;
		}		
				
		//Lista o itens debitados
		function listarItensDb(){
			$livro_diario = new Livro_diario();
			echo '<table border="0" cellspacing="2" cellpadding="4" style="font-size: 12px;"><tbody>';
			echo '	<tr>
						<th align="left" width="200">Categoria</th>
						<th align="left" width="100">Valor</th>
					</tr>';
			//Ordena os itens pela chave
			ksort($this->array_db);
			//Percorre cada item do array e monta o livro razao com os itens agrupados
			foreach ($this->array_db as $categoria_debitada => $total) {
				echo '<tr>';
				//Pega o nome da categoria para o numero do item
				echo '<td class="td_calendario"><strong>'.$livro_diario->getNomeCategoriaDB($categoria_debitada).'</strong></td><td class="td_calendario" align="right"><strong>'.$this->setMoney($total).'</strong></td>';
				echo '</tr>';
				//Pega o nome da categoria do item do livro caixa relacionado com a categoria debitada
				$categorias = $this->array_db_sub[$categoria_debitada];
				//Ordena o itens
				ksort($categorias);
				//Percorre cada item do livro caixa relacionado com a categoria debitada
				foreach ($categorias as $categoria => $valor) {
					echo '<tr>';
					echo '<td class="td_calendario" style="padding-left:10px;">'.$categoria.'</td>';
					echo '<td class="td_calendario" align="right">'.$this->setMoney($valor).'</td>';
					echo '</tr>';
				}
				// echo '<td class="td_calendario" style="padding-left:10px;margin-bottom:10px;"><strong>Total</strong></td><td class="td_calendario" align="right"><strong>'.$this->setMoney($total).'</strong></td>';
				echo '<tr><td></td><td></td></tr>';
			}
			//Mostra o total debitado para todas as categorias
			echo '<tr><td class="td_calendario"><strong>Total Debitado</strong></td><td class="td_calendario" align="right"><strong>'.$this->setMoney($this->getDb()).'</strong></td></tr>';
			echo '</tbody></table>';
		}
		//Lista os itens creditados
		function listarItensCr(){
			$livro_diario = new Livro_diario();
			echo '<table border="0" cellspacing="2" cellpadding="4" style="font-size: 12px;"><tbody>';
			echo '	<tr>
						<th align="left" width="200">Categoria</th>
						<th align="left" width="100">Valor</th>
					</tr>';
			//Ordena os itens pela chave
			ksort($this->array_cr);
			//Percorre cada item do array e monta o livro razao com os itens agrupados
			foreach ($this->array_cr as $categoria_creditada => $total) {
				echo '<tr>';
				//Pega o nome da categoria para o numero do item
				echo '<td class="td_calendario"><strong>'.$livro_diario->getNomeCategoriaCR($categoria_creditada).'</strong></td><td class="td_calendario" align="right"><strong>'.$this->setMoney($total).'</strong></td>';
				echo '</tr>';
				//Pega o nome da categoria do item do livro caixa relacionado com a categoria Creditada
				$categorias = $this->array_cr_sub[$categoria_creditada];
				//Ordena o itens
				ksort($categorias);
				//Percorre cada item do livro caixa relacionado com a categoria creditada
				foreach ($categorias as $categoria => $valor) {
					echo '<tr>';
					echo '<td class="td_calendario" style="padding-left:10px;">'.$categoria.'</td>';
					echo '<td class="td_calendario" align="right">'.$this->setMoney($valor).'</td>';
					echo '</tr>';
				}
				// echo '<td class="td_calendario" style="padding-left:10px;"><strong>Total</strong></td><td class="td_calendario" align="right"><strong>'.$this->setMoney($total).'</strong></td>';
				echo '<tr><td></td><td></td></tr>';
			}
			//Mostra o total creditado para todas as categorias
			echo '<tr><td class="td_calendario"><strong>Total Creditado</strong></td><td class="td_calendario" align="right"><strong>'.$this->setMoney($this->getCr()).'</strong></td></tr>';
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
	function getAnoInicio(){
		if( isset($_GET['DataInicio']) )
			return $_GET['DataInicio'];
		else
			return '01/'.date("m").'/'.date("Y");
	}
	function getAnoFim(){
		if( isset($_GET['DataFim']) )
			return $_GET['DataFim'];
		else
			return date("d").'/'.date("m").'/'.date("Y");
	}
	function selected($string1,$string2){
		if( $string1 == $string2 ){
			echo 'selected="selected"';
		}
	}
	function getTipo(){
		if( isset($_GET['tipo']) )
			return $_GET['tipo'];
		else
			return 'diario';
	}
?>