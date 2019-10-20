<?
/**
 * Classe criada para poder pegar os dados do balanço.
 * Data: 15/05/2018
 */
require_once('DataBaseMySQL/BalancoPatrimonial.php');
require_once('livro-diario-razao.class.php');
require_once('dre.class.php');

//	ini_set('display_errors',1);
//	ini_set('display_startup_erros',1);
//	error_reporting(E_ALL);

class Balanco_Patrimonial {
		
	/***** ATIVO *****/
	
	/** Ativo circulante **/ 
	private $Circulante_A = 0;
	public function getCirculante_A(){
		
		// Pega o total do ativo circulante.
		$this->setCirculante_A();
		
		return $this->Circulante_A;	
	}
	public function setCirculante_A(){
		
		// Soma os itens do ativo circulante.
		$this->Circulante_A = $this->getCaixa_e_Equivalentes_de_Caixa()
		+ $this->getContas_a_Receber_C()
		+ $this->getEstoques()
		+ $this->getOutros_Creditos()
		+ $this->getDespesas_do_Exercicio_Seguinte();
	}
	
	/** Caixa e Equivalentes de Caixa **/	
	private $Caixa_e_Equivalentes_de_Caixa = 0;
	public function getCaixa_e_Equivalentes_de_Caixa(){
		return $this->Caixa_e_Equivalentes_de_Caixa;	
	}
	
	// Método utilizado para setar o valor do caixa onde caixa e uma conta devedora. 
	public function setCaixa_e_Equivalentes_de_Caixa($object){
		
		// Credita no caixa ou subitrai do caixa.
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '1.1.1') {
			$this->Caixa_e_Equivalentes_de_Caixa -= $object->getvalor();
		}
		
		// Debita no caixa ou soma ao caixa.
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '1.1.1') {
			$this->Caixa_e_Equivalentes_de_Caixa += $object->getvalor();
		}
	}
	
	/** Contas a Receber circulante **/
	private $Contas_a_Receber_C = 0;
	public function getContas_a_Receber_C(){
		return $this->Contas_a_Receber_C;
	}
	
	// Método criado para somar ou subtrair o valor do contas a receber circulante. 
	public function setContas_a_Receber_C($object, $ano){

		// Credita no contas a receber(Subtrai o valor que já foi recebido).
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '1.1.2') {
			$this->Contas_a_Receber_C -= $object->getvalor();
		}

		// Debita no contas a receber (Soma o total do valor a ser recebido).
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '1.1.2') {
			$this->Contas_a_Receber_C += $object->getvalor();
		}
		
	}
	
	/** Estoques **/
	private $Estoques = 0;
	public function getEstoques(){
		return $this->Estoques;	
	}
	public function setEstoques($object){

		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '1.1.3') {
			$this->Estoques -= $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '1.1.3') {
			$this->Estoques += $object->getvalor();
		}
	}
	
	/** Outros Créditos **/
	private $Outros_Creditos = 0;
	public function getOutros_Creditos(){
		return $this->Outros_Creditos;	
	}
	public function setOutros_Creditos($object){
	
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '1.1.4') {
			$this->Outros_Creditos -= $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '1.1.4') {
			$this->Outros_Creditos += $object->getvalor();
		}
	}
	
	/** Despesas já paga do Exercício Seguinte **/
	private $Despesas_do_Exercicio_Seguinte = 0;
	public function getDespesas_do_Exercicio_Seguinte(){
		return $this->Despesas_do_Exercicio_Seguinte;	
	}
	public function setDespesas_do_Exercicio_Seguinte($object){
		
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '0.0.0') {
			$this->Despesas_do_Exercicio_Seguinte -= $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '0.0.0') {
			$this->Despesas_do_Exercicio_Seguinte += $object->getvalor();
		}
	}	

	// Não Circulante
	private $Nao_Circulante = 0;
	public function getNao_Circulante(){
		
		// Pega o total de ativo circulante.
		$this->setNao_Circulante();
		
		return $this->Nao_Circulante;	
	}
	public function setNao_Circulante(){
		
		// Soma os itens do ativos não circulante. 
		$this->Nao_Circulante += $this->getContas_a_Receber_NC() 
		+ $this->getInvestimentos()
		+ $this->getImobilizado()
		+ $this->getIntangivel()
		+ $this->getDepreciacao_e_Amortizacao_Acumuladas();
	}
	
	/** Contas a Receber não circulante **/
	private $Contas_a_Receber_NC = 0;
	public function getContas_a_Receber_NC(){
		return $this->Contas_a_Receber_NC;	
	}
	public function setContas_a_Receber_NC($object, $ano){
		 
		// Debita em Contas a receber não cerculante.
		if($object->getrelacao_cr_db()->getDb() == '1.3.1.01') {
			$this->Contas_a_Receber_NC += $object->getvalor();
		}
	}
	
	private $Investimentos = 0;
	public function getInvestimentos(){
		return $this->Investimentos;	
	}
	public function setInvestimentos($object){
				
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '1.3.2') {
			$this->Investimentos -= $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '1.3.2') {
			$this->Investimentos += $object->getvalor();
		}
	}
	
	private $Imobilizado = 0;
	public function getImobilizado(){
		return $this->Imobilizado;	
	}
	public function setImobilizado($empresaId, $ano){
		
		$vida_util = array();
		$vida_util['Veículos'] = floatval(5);
		$vida_util['Imóveis (prédios)'] = floatval(25);
		$vida_util['Móveis e utensílios'] = floatval(10);
		$vida_util['Computadores e periféricos'] = floatval(5);
		$vida_util['Máquinas e equipamentos'] = floatval(10);

		// Instância da classe do balanço patrimonial.
		$instancia = new BalancoPatrimonial();
		
		$dados = $instancia->PegaDadosBensImobilizados($empresaId, $ano);
		
		// Verifica se existe lançamento de bens imobilizados. 
		if($dados){
			
			foreach( $dados as $val ){
				
				$vidaUtilVal = (isset($vida_util[$val['item']]) ? $vida_util[$val['item']] : '');
			
				if( floatval($ano) - floatval(date('Y', strtotime($val['data']))) <= $vidaUtilVal )
					$this->Imobilizado += ( floatval($val['quantidade']) * floatval($val['valor']) );

			}
		}
	}
	
	private $Intangivel = 0;
	public function getIntangivel(){
		return $this->Intangivel;	
	}
	public function setIntangivel($empresaId, $ano){
		
		$vida_util = array();
		$vida_util['Software'] = floatval(5);
		$vida_util['Marca'] = floatval(99999);
		$vida_util['Patente'] = floatval(10);
		$vida_util['Direitos autorais'] = floatval(99999);
		$vida_util['Licenças'] = floatval(10);
		$vida_util['Pesquisa e desenvolvimento'] = floatval(10);

		// Instância da classe do balanço patrimonial.
		$instancia = new BalancoPatrimonial();		
				
		$dados = $instancia->PegaDadosBensIntangiveis($empresaId, $ano);

		// Verifica se existe lançamento de bens intangíveis 
		if($dados){
			
			foreach( $dados as $val ){
				
				$vidaUtilVal = (isset($vida_util[$val['item']]) ? $vida_util[$val['item']] : '');
								
				if( floatval($ano) - floatval(date('Y', strtotime($val['data']))) <= $vidaUtilVal )
					$this->Intangivel +=  ( floatval($val['quantidade']) * floatval($val['valor']) );	
			}
		}
	}
	
	private $Depreciacao_e_Amortizacao_Acumuladas;
	public function getDepreciacao_e_Amortizacao_Acumuladas(){
		return $this->Depreciacao_e_Amortizacao_Acumuladas;	
	}
	public function setDepreciacao_e_Amortizacao_Acumuladas($object){

		// Credita
		if($object->getrelacao_cr_db()->getCr() == '1.3.3.06' || $object->getrelacao_cr_db()->getCr() == '1.3.4.02') {
			$this->Depreciacao_e_Amortizacao_Acumuladas -= $object->getvalor();
		}
		
		// Debita 
		if($object->getrelacao_cr_db()->getDb() == '1.3.3.06' || $object->getrelacao_cr_db()->getDb() == '1.3.4.02') {
			$this->Depreciacao_e_Amortizacao_Acumuladas += $object->getvalor();
		}
	}
	
	private $Total_Ativo = 0;
	public function getTotal_Ativo(){
		return $this->Total_Ativo;	
	}
	public function setTotal_Ativo($val){
		$this->Total_Ativo = $val;
	}
	
	/** PASSIVO E PATRIMÔNIO LÍQUIDO **/
	// Circulante
	private $Circulante_P = 0;
	public function getCirculante_P(){
		
		// Pega o total de passivo circulante.
		$this->setCirculante_P();
		
		return $this->Circulante_P;	
	}
	public function setCirculante_P(){
		
		// Soma os itens do passivo circulante.
		$this->Circulante_P = $this->getFornecedores() 
		+ $this->getEmprestimos_Bancarios() 
		+ $this->getObrigacoes_Sociais_e_Impostos_a_Recolher() 
		+ $this->getContas_a_Pagar_C() 
		+ $this->getLucros_a_Distribuir() 
		+ $this->getProvisoes();
	}

	private $Fornecedores = 0;
	public function getFornecedores(){
		return $this->Fornecedores;	
	}
	public function setFornecedores($object){
		
		// Credita em fornecedores
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '2.1.1') {
			$this->Fornecedores += $object->getvalor();
		}
		
		// Debita de fornecedores
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '2.1.1') {
			$this->Fornecedores -= $object->getvalor();
		}
	}

	
	private $Emprestimos_Bancarios = 0;
	public function getEmprestimos_Bancarios(){
		return $this->Emprestimos_Bancarios;	
	}
	public function setEmprestimos_Bancarios($object){
		
		// Credita no emprestimo (recebe o valor do emprestimo)
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '2.1.2') {
			$this->Emprestimos_Bancarios += $object->getvalor();
		}
		
		// Debita do emprestimo (Efetua o pagamento total ou parcial do emprestimo).
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '2.1.2') {
			$this->Emprestimos_Bancarios -= $object->getvalor();
		}
	}

	private $Obrigacoes_Sociais_e_Impostos_a_Recolher = 0;
	public function getObrigacoes_Sociais_e_Impostos_a_Recolher(){
		return $this->Obrigacoes_Sociais_e_Impostos_a_Recolher;	
	}
	public function setObrigacoes_Sociais_e_Impostos_a_Recolher($object){
		
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '0.0.0') {
			$this->Obrigacoes_Sociais_e_Impostos_a_Recolher -= $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '0.0.0') {
			$this->Obrigacoes_Sociais_e_Impostos_a_Recolher += $object->getvalor();
		}
	}

	/** Contas a pagar circulante **/
	private $Contas_a_Pagar_C = 0;
	public function getContas_a_Pagar_C(){
		return $this->Contas_a_Pagar_C;
	}
	public function setContas_a_Pagar_C($object, $ano){
		
		// Credita em contas a pagar (Soma as contasa pagar).
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '2.1.5') {
			$this->Contas_a_Pagar_C += $object->getvalor();
		}
		
		// Debita em contas a pagar (subtrai o valor já pago).
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '2.1.5') {
			$this->Contas_a_Pagar_C -= $object->getvalor();
		}		
	}

	private $Lucros_a_Distribuir = 0;
	public function getLucros_a_Distribuir(){
		return $this->Lucros_a_Distribuir;	
	}
	public function setLucros_a_Distribuir($object){
		
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '0.0.0') {
			$this->Lucros_a_Distribuir += $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '0.0.0') {
			$this->Lucros_a_Distribuir -= $object->getvalor();
		}
	}

	private $Provisoes = 0;
	public function getProvisoes(){
		return $this->Provisoes;	
	}
	public function setProvisoes($object){
		
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '0.0.0') {
			$this->Provisoes += $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '0.0.0') {
			$this->Provisoes -= $object->getvalor();
		}
	}

	/*** Não Circulante ***/
	private $Nao_Circulante_P = 0;
	public function getNao_Circulante_P(){
		
		// Pega o total do passivo não Circulante
		$this->setNao_Circulante_P();
		
		return $this->Nao_Circulante_P;	
	}
	public function setNao_Circulante_P(){
		
		// Soma os itens do passivo não circulante.
		$this->Nao_Circulante_P = $this->getContas_a_Pagar_NC() 
		+ $this->getFinanciamentos_Bancarios();
	}
	
	/** Contas a pagar não circulante **/
	private $Contas_a_Pagar_NC = 0;
	public function getContas_a_Pagar_NC(){
		return $this->Contas_a_Pagar_NC;	
	}
	public function setContas_a_Pagar_NC($object, $ano){
		
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '2.2.2') {
			$this->Contas_a_Pagar_NC += $object->getvalor();
		}
	}

	/** Financiamentos Bancários **/
	private $Financiamentos_Bancarios = 0;
	public function getFinanciamentos_Bancarios(){
		return $this->Financiamentos_Bancarios;	
	}
	public function setFinanciamentos_Bancarios($object){
		
		// Credita em financiamento (recebe o valor do financiamento);
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '2.2.1') {
			$this->Financiamentos_Bancarios += $object->getvalor();
		}
	}

	/** PATRIMÔNIO LÍQUIDO **/
	// Define o total do patrimônio liquido.
	private $Patrimonio_Liquido = 0;
	public function getPatrimonio_Liquido(){
		
		// Pega o total do patrimônio líquido
		$this->setPatrimonio_Liquido();
		
		return $this->Patrimonio_Liquido;	
	}
	public function setPatrimonio_Liquido(){
		
		// Soma o total do patrimônio liquido.
		$this->Patrimonio_Liquido = $this->getCapital_Social() 
		+ $this->getReservas_de_Capital() 
		+ $this->getAjustes_de_Avaliacao_Patrimonial() 
		+ $this->getReservas_de_Lucros() 
		+ $this->getLucros_Acumulados() 
		+ $this->getPrejuizos_Acumulados();
	}

	private $Capital_Social = 0;
	public function getCapital_Social(){
		return $this->Capital_Social;	
	}
	public function setCapital_Social(){
		
		$this->Capital_Social = '';
	}

	private $Reservas_de_Capital = 0;
	public function getReservas_de_Capital(){
		return $this->Reservas_de_Capital;	
	}
	public function setReservas_de_Capital($object){
		
		// Credita
		if($object->getrelacao_cr_db()->getCr() == '2.3.2.01') {
			$this->Reservas_de_Capital -= $object->getvalor();
		}
		
		// Debita 
		if($object->getrelacao_cr_db()->getDb() == '2.3.2.01') {
			$this->Reservas_de_Capital += $object->getvalor();
		}
	}

	private $Ajustes_de_Avaliacao_Patrimonial = 0;
	public function getAjustes_de_Avaliacao_Patrimonial(){
		return $this->Ajustes_de_Avaliacao_Patrimonial;	
	}
	public function setAjustes_de_Avaliacao_Patrimonial($object){
		
		// Credita
		if(substr($object->getrelacao_cr_db()->getCr(), 0, -3) == '0.0.0') {
			$this->Ajustes_de_Avaliacao_Patrimonial -= $object->getvalor();
		}
		
		// Debita 
		if(substr($object->getrelacao_cr_db()->getDb(), 0, -3) == '0.0.0') {
			$this->Ajustes_de_Avaliacao_Patrimonial += $object->getvalor();
		}
	}

	private $Reservas_de_Lucros = 0;
	public function getReservas_de_Lucros(){
		return $this->Reservas_de_Lucros;	
	}
	public function setReservas_de_Lucros($object){
		
		// Credita
		if($object->getrelacao_cr_db()->getCr() == '2.3.2.02') {
			$this->Reservas_de_Lucros -= $object->getvalor();
		}
		
		// Debita 
		if($object->getrelacao_cr_db()->getDb() == '2.3.2.02') {
			$this->Reservas_de_Lucros += $object->getvalor();
		}
	}

	private $Lucros_Acumulados = 0;
	public function getLucros_Acumulados(){
		return $this->Lucros_Acumulados;	
	}
	public function setLucros_Acumulados($object){
		
		$dre = new Gerar_DRE();
		$dre->setano($ano);
		$dre->gerarDre();	
		
		if( $dre->getResultadoDre() > 0 ) {
			$this->Lucros_Acumulados += $dre->getResultadoDre();
		}
	}

	private $Prejuizos_Acumulados = 0;
	public function getPrejuizos_Acumulados(){
		return $this->Prejuizos_Acumulados;	
	}
	public function setPrejuizos_Acumulados($object){
		
		// Credita no prejuizos acumulados.
		if($object->getrelacao_cr_db()->getCr() == '2.3.3.02') {
			$this->Prejuizos_Acumulados -= $object->getvalor();
		}
		
		// Debita no prejuizos acumulados.
		if($object->getrelacao_cr_db()->getDb() == '2.3.3.02') {
			$this->Prejuizos_Acumulados += $object->getvalor();
		}
	}

	private $Total_Passivo = 0;
	public function getTotal_Passivo(){
		return $this->Total_Passivo;	
	}
	public function setTotal_Passivo($val){
		$this->Total_Passivo = $val;
	}
	
	// Propriedate de teste para o ajax.
	public $teste = 0;
		
	/** Pega os valores de acordo com os campos solicitado no balanço **/
	private function RealizaCalculoBalanco(){
		
		// Define o status como false.
		$status = true;
		
		// variável usada para definir a mensagem.
		$msg = '';

		if( isset($_SESSION['id_empresaSecao']) && !empty($_SESSION['id_empresaSecao']) && isset($_POST['ano']) && !empty($_POST['ano']) ){
			
			$empresaId = $_SESSION['id_empresaSecao'];
			
			$ano = $_POST['ano'];
			
			// Prepara os dados do balanço.
			$this->GeraDadosBalancoPatrimonial($empresaId, $ano);

			// Grava os dados do balanço.
			$insertId = $this->GravaDadosBanlancoPatrimonial($empresaId, $ano);
			
			$insertId = true;
			
			if($insertId){
				$status  = true;	
			}
			
		} else {
			$msg = "A empresa não foi localizada ou o ano não informado.";
		}
		
		// Retorna um array em json com o estatus e a mensagem.
		return json_encode(array('status'=>$status,'mensagem'=>$msg,'teste'=>$this));
	}
	
	// Método criado para montar a estrutura completa do lancamento
	private function MontaRelacionamentoLancamento($livro_diario, $item, $plano_contas ) {

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
		
		if(isset($item['dataPagtoContas_PR'])) {
			// Define a data do pagamento de contas a receber ou apagar. 
			$item_livro_caixa->setdata_pagamento_contas_PR($item['dataPagtoContas_PR']);
		}
		
		if(isset($item['dataVencimentoContas_PR'])){
			// Define a data de vencimento de contas a pagar ou a receber
			$item_livro_caixa->setdata_vencimento_Contas_PR($item['dataVencimentoContas_PR']);
		}
		
		if(isset($item['emprestimo_carencia'])){
			// Define a carencia se for empréstimo.	
			$item_livro_caixa->setemprestimo_carencia($item['emprestimo_carencia']);
		}
		
		//Pega os dados da relação Creditado-Debitado para a categoria do item
		$item_livro_caixa->setrelacao_cr_db($categorias_livro);
		//Insere o item para montar o livro diário
		$plano_contas->setItem($item_livro_caixa);
		//Insere o item para montar o livro Razão	
		$plano_contas->setItemAgrupado($item['categoria'],$categorias_livro,$item_livro_caixa->getvalor());
	}	

	// Pega os valores do balanço.
	private function GeraDadosBalancoPatrimonial($empresaId, $ano){
			
		// Instância da classe do balanço patrimonial.
		$balancoPatrimonial = new BalancoPatrimonial();
		
		$livro_diario = new Livro_diario();
		$plano_contas = new Plano_de_contas();		
		
		// Pega os lancamentos
		$lancamento = $balancoPatrimonial->PegaLancamentoAno($ano);
		
		foreach($lancamento as $item) {
			
			//Se for empréstimo define o prazo de pagamento para o emprestimo do usuario
			$livro_diario->setprazo($item['emprestimo_carencia']);
			
			//Define o id de quem recebeu o pagamento para consultar se é administrativo ou operacionais 
			$livro_diario->setid_user_pagamento($livro_diario->ifPagamento($item));
			
			//Se for pagamento seta os dados de pagamento
			$livro_diario->ifPagamento($item);
			
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
				$this->MontaRelacionamentoLancamento( $livro_diario, $item, $plano_contas );				

				// Inclui a linha do juros. 
				$this->MontaRelacionamentoLancamento( $livro_diario, $itemAmortizacao, $plano_contas );

			} else if( $this->ChecaSeContasApagarOuReceber($item)) {
				
				// Chama o método para poder normalizar o contas a receber.	
				$itemNormalizado = $this->NormalizaContasApagarEContasReceber($item);

				// Incluir a linha de contas a receber na tabela do livro diário.
				$this->MontaRelacionamentoLancamento($livro_diario, $itemNormalizado, $plano_contas );
				
			} else {
				
				// Inclui a linha do livro diário.
				$this->MontaRelacionamentoLancamento($livro_diario, $item, $plano_contas );
			}
		}

		$array_itens = $plano_contas->PegaEstruturaDelancamentoComContas();
				
//		echo "<pre>";
//			print_r($array_itens);
//		echo "</pre>";
				
		// Percorre os lançamento e inclui o valor em sua devida conta.
		foreach($array_itens as $object) {
			
			/** Ativo circulante **/			
			// Pega os lançamentos da conta : Caixa e Equivalentes de Caixa
			$this->setCaixa_e_Equivalentes_de_Caixa($object);

			// Pega os lançamentos da conta : Contas a Receber
			$this->setContas_a_Receber_C($object, $ano);
			
			// Pega os lançamentos da conta : Estoques
			//$this->setEstoques($object);
			
			// Pega os lançamentos da conta : Outros Créditos
			//$this->setOutros_Creditos($object);
			
			// Pega os lançamentos da conta : Despesas já paga do Exercício Seguinte
			//$this->setDespesas_do_Exercicio_Seguinte($object);
						
			/** Ativo não circulante **/
			// Pega os lançamentos da conta : Contas a Receber
			//$this->setContas_a_Receber_NC($object, $ano);
						
			// Pega os lançamentos da conta : (-) Depreciação e Amortização Acumuladas
			$this->setDepreciacao_e_Amortizacao_Acumuladas($object);
						
			/** Passivo circulante **/
			// Pega os lançamentos da conta : Fornecedores
			$this->setFornecedores($object);
			
			// Pega os lançamentos da conta : Empréstimos Bancários
			$this->setEmprestimos_Bancarios($object);
			
			// Pega os lançamentos da conta : Obrigações Sociais e Impostos a Recolher
			$this->setObrigacoes_Sociais_e_Impostos_a_Recolher($object);

			// Pega os lançamentos da conta : Contas a Pagar
			$this->setContas_a_Pagar_C($object, $ano);
			
			// Pega os lançamentos da conta : Lucros a Distribuir
			$this->setLucros_a_Distribuir($object);
			
			// Pega os lançamentos da conta : Provisões (cíveis, fiscais, trabalhistas, etc)
			//$this->setProvisoes($object);
						
			/** Passivo não circulante **/
			// Pega os lançamentos da conta : Contas a Pagar
			//$this->setContas_a_Pagar_NC($object, $ano);

			// Pega os lançamentos da conta : Financiamentos Bancários
			$this->setFinanciamentos_Bancarios($object);
		}
		
		// Pega os lançamentos da conta : Investimentos
		//$this->setInvestimentos($object);

		// Pega os lançamentos da conta : Imobilizado
		$this->setImobilizado($empresaId, $ano);

		// Pega os lançamentos da conta : Intangível
		$this->setIntangivel($empresaId, $ano);
		
		/** Patrimônio líquido **/ 
		// Pega os lançamentos da conta : Capital Social
		//$this->setCapital_Social($ano);

		// Pega os lançamentos da conta : Reservas de Capital
		//$this->setReservas_de_Capital($object);

		// Pega os lançamentos da conta : Ajustes de Avaliação Patrimonial
		//$this->setAjustes_de_Avaliacao_Patrimonial($object);

		// Pega os lançamentos da conta : Reservas de Lucros
		//$this->setReservas_de_Lucros($object);

		// Pega os lançamentos da conta : Lucros Acumulados
		//$this->setLucros_Acumulados($object);			

		// Pega os lançamentos da conta : (-) Prejuízos Acumulados
		//$this->setPrejuizos_Acumulados($object);
		
/*		echo "<pre>";
			print_r($this);
		echo "</pre>";*/
	}
	
	// Realiza a gravação dos dados patrimonial.
	private function GravaDadosBanlancoPatrimonial($empresaId, $ano){

		// Ativo circulante.
		$a_c_caixa_equivalente_caixa = $this->getCaixa_e_Equivalentes_de_Caixa();
		$a_c_contas_receber = $this->getContas_a_Receber_C();
		$a_c_estoques = $this->getEstoques();
		$a_c_outros_creditos = $this->getOutros_Creditos();
		$a_c_despesas_exercicio_seguinte = $this->getDespesas_do_Exercicio_Seguinte();
		
		// Total do ativo circulante 
		$a_c_total = $this->getCirculante_A();

		// Ativo não circulante.
		$a_n_c_contas_receber = $this->getContas_a_Receber_NC();
		$a_n_c_investimentos = $this->getInvestimentos();
		$a_n_c_imobilizado = $this->getImobilizado();
		$a_n_c_intangivel = $this->getIntangivel();
		$a_n_c_depreciacao = $this->getDepreciacao_e_Amortizacao_Acumuladas();

		//Total do ativo não circulante.
		$a_n_c_total = $this->getNao_Circulante();

		// Passivo circulante.
		$p_c_fornecedores = $this->getFornecedores();
		$p_c_emprestimos_bancarios = $this->getEmprestimos_Bancarios();
		$p_c_obrigacoes_sociais_impostos = $this->getObrigacoes_Sociais_e_Impostos_a_Recolher();

		$p_c_contas_pagar = $this->getContas_a_Pagar_C();
		$p_c_lucros_distribuir = $this->getLucros_a_Distribuir();
		$p_c_provisoes = $this->getProvisoes();

		// Total do passivo circulante.
		$p_c_total = $this->getCirculante_P();

		// Passivo não circulante.
		$p_n_c_contas_pagar = $this->getContas_a_Pagar_NC();
		$p_n_c_financiamentos_bancarios = $this->getFinanciamentos_Bancarios();

		//total do passivo não circulante.
		$p_n_c_total = $this->getNao_Circulante_P();

		// Patrimônio líquido.
		$p_l_capital_social = $this->getCapital_Social();
		$p_l_reservas_capital = $this->getReservas_de_Capital();
		$p_l_ajustes_avaliacao_patrimonial = $this->getAjustes_de_Avaliacao_Patrimonial();
		$p_l_reservas_lucro = $this->getReservas_de_Lucros();
		$p_l_lucros_acumulados = $this->getLucros_Acumulados();
		$p_l_prejuizos_acumulados = $this->getPrejuizos_Acumulados();

		// Total do patrimônio líquido.
		$p_l_total = $this->getPatrimonio_Liquido();
		
		// Instância da classe do balanço patrimonial.
		$balancoPatrimonial = new BalancoPatrimonial();
		
		// Exclui os dados do balanço de acordo com o ano e id do usuário.
		//$balancoPatrimonial->ExcluiOsDadosDoBalanco($empresaId, $ano);
		
		$dadosBalancoPatrimonial = $balancoPatrimonial->PegaDadosBalancoPatrimonial($empresaId, $ano);
		
		if($dadosBalancoPatrimonial){
			
			$id = $dadosBalancoPatrimonial['id'];
			$p_l_capital_social = $dadosBalancoPatrimonial['p_l_reservas_capital'];			
			
			$balancoPatrimonial->AtualizaDadosBalancoPatrimonial( $id, $empresaId, $ano, $a_c_caixa_equivalente_caixa, $a_c_contas_receber, $a_c_estoques, $a_c_outros_creditos, $a_c_despesas_exercicio_seguinte, $a_c_total, $a_n_c_contas_receber, $a_n_c_investimentos, $a_n_c_imobilizado, $a_n_c_intangivel, $a_n_c_depreciacao, $a_n_c_total, $p_c_fornecedores, $p_c_emprestimos_bancarios, $p_c_obrigacoes_sociais_impostos, $p_c_contas_pagar, $p_c_lucros_distribuir, $p_c_provisoes, $p_c_total, $p_n_c_contas_pagar, $p_n_c_financiamentos_bancarios, $p_n_c_total, $p_l_capital_social, $p_l_reservas_capital, $p_l_ajustes_avaliacao_patrimonial, $p_l_reservas_lucro, $p_l_lucros_acumulados, $p_l_prejuizos_acumulados, $p_l_total );
			
		} else {
			
			$p_l_capital_social = 0; 
			
			// Chama o método para realizar a inclusão dos dados do balanco patrimonial.
			$status = $balancoPatrimonial->InclusaoDadosBalancoPatrimonial( $empresaId, $ano, $a_c_caixa_equivalente_caixa, $a_c_contas_receber, $a_c_estoques, $a_c_outros_creditos, $a_c_despesas_exercicio_seguinte, $a_c_total, $a_n_c_contas_receber, $a_n_c_investimentos, $a_n_c_imobilizado, $a_n_c_intangivel, $a_n_c_depreciacao, $a_n_c_total, $p_c_fornecedores, $p_c_emprestimos_bancarios, $p_c_obrigacoes_sociais_impostos, $p_c_contas_pagar, $p_c_lucros_distribuir, $p_c_provisoes, $p_c_total, $p_n_c_contas_pagar, $p_n_c_financiamentos_bancarios, $p_n_c_total, $p_l_capital_social, $p_l_reservas_capital, $p_l_ajustes_avaliacao_patrimonial, $p_l_reservas_lucro, $p_l_lucros_acumulados, $p_l_prejuizos_acumulados, $p_l_total );
		}
		
			

		
		return $status;
	}	
	
	// Método criado para vefica se o lancamento e contas a para ou contas a receber.
	private function ChecaSeContasApagarOuReceber($item) {

		// Inicia o status vazio.
		$status = false;
		
		// Instância da classe do balanço patrimonial.
		$instancia = new BalancoPatrimonial();
		
		// Verifica se o lancamento é um contas a receber.
		if( $item['categoria'] == 'Serviços prestados em geral' || $instancia->PegaListaClientesPeloApelido($item['categoria']) ) {	
			
			// Verifica se a categoria secundaria foi informada para poder nomalizar quando for criar o relacionamento do plano de contas. 
			if(!empty($item['categoria_secundaria_1']) || !empty($item['categoria_secundaria_2']) ) {
				$status = true;
			} 

		} // Verifica se o lancamento é um contas a .
		else if(in_array($item['categoria'], $this->ListaContasAPagar($categoria))){
			
			// Verifica se a categoria secundaria foi informada para poder nomalizar quando for criar o relacionamento do plano de contas. 
			if(!empty($item['categoria_secundaria_1']) || !empty($item['categoria_secundaria_2']) ) {
				$status = true;
			}
		}
		
		return $status;
	}
	
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
				
		return $listaContasAPagar;
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
				'categoriaAux'=>$item['categoria'], // A index categoria auxiliar e usada para pegar a categoria real do registro pai.
				'dataPagtoContas_PR'=>$item['dataPagtoContas_PR'],
				'dataVencimentoContas_PR'=>$item['dataVencimentoContas_PR']
			);			
		}
		
		
		return $itemArray;		
	}
	
	// Método criador para verificar requisição ajax.
	public function ControleAjax($method){
		// Chama o método informado pelo ajax.
		return $this->$method();
	}
}
?>