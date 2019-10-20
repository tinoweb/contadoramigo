<?php
/**
 * autor: Átano de Farias Jacinto
 * data: 10/11/2017
 */
// Realiza a requisição do arquivo que retorna o objeto com os dados do funcionário.
require_once('DataBaseMySQL/DadosDARF.php');

class DARFPagamento {
	
	/*** Atributos. ***/
	private $IdPagto = "";
	private $Id = "";
	private $Nome = "";
	private $Periodo = "";
	private $Tipo = "";
	private $CPF = "";
	private $Dependentes = "";
	private $OP_Simples = "";
	private $CodigoServico = "";
	private $DescricaoServico = "";
	private $DescDep = 0;
	private $ValorBruto = 0;
	private $INSS = 0;
	private $IR = 0;
	private $ISS = 0;
	private $ValorLiquido = 0;
	private $DataPagto = "";
	private $DataEmissao = "";
	private $TotalValorBruto = 0;
	private $TotalINSS = 0;
	private $Total_Desc_Dep = 0;
	private $TotalIR = 0;
	private $TotalISS = 0;
	private $TotalValorLiquido = 0;
	private $TipoAnterior = "";
	private $CodigoServicoAnterior = "";
	private $TotalGeralIR = 0;
	private $AuxDataEmissao = '0000-00-00';
	private $CodigoServicoArray = "";
	private $DescricaoServicoArray = "";
	private $Script = "";
	private $Converter = "";
	private $DataAux = "";
	private $ArrayPagto = array();
	private $AuxCount = 0;
	private $IdPagtoAux = 0;	
		
		
	// Atributo publico para apresentar o conteudo da pagina, ou seja as tabelas de pagamento.
	public $MostraTabelasPagamentos = "";
	
	/*** Funções ***/
	
	// Método para construção das tabelas com as linhas de pagamentos da DARF.
	public function __construct() {
	
		// Pega o mês para realizar a filtragem dos dados.
		if(isset($_POST['mes']) && !empty($_POST['mes'])) {
			
			$mes = $_POST['mes'];
			
			// USADA NO TUTORIAL DOS DARF
			$_SESSION['mes_DARF_userSessao'] = $_POST['mes']; 
		} else {
			if(isset($_SESSION['mes_DARF_userSessao']) && !empty($_SESSION['mes_DARF_userSessao'])){
				$mes = $_SESSION['mes_DARF_userSessao'];
			}
		}
		
		// Pega o ano para realizar a filtragem dos dados.
		if(isset($_POST['ano']) && !empty($_POST['ano']) ) {
			
			$ano = $_POST['ano'];

			// USADA NO TUTORIAL DOS DARF
			$_SESSION['ano_DARF_userSessao'] = $_POST['ano']; 
		} else {
			if(isset($_SESSION['ano_DARF_userSessao']) && !empty($_SESSION['ano_DARF_userSessao'])){
				$ano = $_SESSION['ano_DARF_userSessao'];
			}
		}
		
		if(!empty($ano) && !empty($mes)) {
			
			$data1 = $ano.'-'.$mes.'-01';
			$data2 = $ano.'-'.$mes.'-31';
						
			// Pega a data da edição
			$this->AuxDataEmissao = $ano.'-'.$mes.'-'.date("d");
			
		} else {
			$data1 =  date("Y").'-01-01';
			$data2 = date("Y").'-12-31';
		}
		
		// Pega o ID Da empresa.
		$empresaId = $_SESSION["id_empresaSecao"];
		
		// Instância da classe que manipula os dados do funcionário.
		$dadosDARF = new DadosDARF;
		
		// pega dados de pagamento do Funcionario.
		$pagtoFuncionario = $dadosDARF->PegaListaPagamentoFuncionario($empresaId, $data1, $data2);
		
		// Pega os dados de pagamento (pró-labore, Autônomos, pessoa jurídica, distr. de lucros).	
		$dadosPagamentos = $dadosDARF->PegaListaPagamentos($empresaId, $mes, $ano);
		
		// Se existir dados é reiniciada a sessão da darf.
		if($dadosPagamentos || $pagtoFuncionario){
			
			// Reinicia a sessão.
			$_SESSION['dados_DARF_userSessao'] = array();
		
			// Verifica se existe dados.
			if($pagtoFuncionario) {

				// Chama o metodo para montar as tabelas e passa para o atributo que fara a apresentado na tela.
				$this->NormalizaPagamentosArray($pagtoFuncionario, 'funcionario');
			}

			// Verifica se existe dados.
			if($dadosPagamentos) {

				// Chama o metodo para montar as tabelas e passa para o atributo que fara a apresentado na tela.
				$dadosPagtosAux2 = $this->NormalizaPagamentosArray($dadosPagamentos);

			};
				
			$this->MostraTabelasPagamentos .= $this->MontaTabelasPagamentos();
		}
	}
	
	// Método criado para montar a estrutura de pagamentos em um array.
	public function MontaTabelasPagamentos() {	
		
		// Define as variaveis com vazias.
		$out = "";
		
		$this->ZeraAtributos();
		
		//Percorre a lista com os pagamntos.
		foreach($this->ArrayPagto as $val) {
						
			// Chama o método para passar os dados de pagamento para os atributos da classe.  
			$this->SetDadosPagamentosTabela($val);
			
			// Monta o cabeçalo da tabela.
			if($this->TipoAnterior != $this->Tipo || ($this->CodigoServico != $this->CodigoServicoAnterior)){

				if(!empty($this->TipoAnterior)){

					// Cria a sessão para gera o boleto Darf.
					$this->CriaSessaoDarf();					

					// Realiza a inclusão do rodapé da tabela com os valores total do valor bruto e total do IR.
					$out .= $this->RodapeTabela();

					// Chama o método para verificar se a darf esta em atraso 
					$out .= $this->ChecVencimentoDarf();
					
					// Define o botão que gera a darf.
					$out .= $this->IncluiBotaoTabela();
	
					// Inclui o js com a ação do botão
					$out .= $this->AcaoButaoTabela();
										
					/***************** Ação em js *********/                   

					$this->TotalValorBruto = 0;
					$this->TotalINSS = 0;
					$this->Total_Desc_Dep = 0;
					$this->TotalIR = 0;
					$this->TotalISS = 0;
					$this->TotalValorLiquido = 0;
				}

				$out .= $this->PegaTituloPagamentoTabela();	

				$out .= "<table width='100%' cellpadding='5' style='margin-bottom:10px;'>";

				// Chama o metodo para pegar o titulo das colunas.	
				if($this->Tipo == 'pessoa jurídica') {
					$out .= "<tr><th width='62%'>Nome</th><th width='9%'>Data da NF</th><th width='9%'>IR</th></tr>";
				}else{
					$out .= "<tr><th width='62%'>Nome</th><th width='9%'>Data</th><th width='9%'>IR</th></tr>";
				}

			}
			
			/*** Monta as linhas com o valor bruto e com o valor do IR ***/
			// Verifica como será apresentada a linhas da grid na tabela de acordo com tipo de pessoa fisica ou jurídica.
			
			
			$out .= $this->LinhaTD($this->Nome, $this->DataPagto, $this->ValorBruto, $this->IR);
			
			// Pega os totais
			$this->Total_Desc_Dep += $this->DescDep;
			$this->TotalINSS += $this->INSS;
			$this->TotalValorBruto += $this->ValorBruto;
			$this->TotalIR += $this->IR;
			$this->TotalISS += $this->ISS;
			$this->TotalValorLiquido += ($this->OP_Simples == 0 ? $this->ValorLiquido : $this->ValorBruto);	

			$this->TotalGeralIR += $this->TotalIR;

			// Os valores para a pesquisa. 
			$this->TipoAnterior = $this->Tipo;
			$this->CodigoServicoAnterior = $this->CodigoServico;
			$this->DescricaoServicoAnterior = $this->DescricaoServico;
		}
		
		// Cria a sessão para gera o boleto Darf.
		$this->CriaSessaoDarf();					

		// Realiza a inclusão do rodapé da tabela com os valores total do valor bruto e total do IR.
		$out .= $this->RodapeTabela();

		// Chama o método para verificar se a darf esta em atraso 
		$out .= $this->ChecVencimentoDarf();

		// Define o botão que gera a darf.
		$out .= $this->IncluiBotaoTabela();

		// Inclui o js com a ação do botão
		$out .= $this->AcaoButaoTabela();
				
		// Retorna o dados da tabela.
		return $out;
		
	}
	
	// Metodo criado para incluir o botão da tabela.
	private function IncluiBotaoTabela() {
		
		// Auxiliar para identificação da ação.
		$this->AuxCount = $this->AuxCount + 1;
		$this->IdPagtoAux = $this->IdPagto.$this->AuxCount;
		
		return '<center>
					<button class="enviarDarf'.$this->IdPagtoAux.'">Gerar Darf</button>
				</center>
				<div style="clear:both;margin-bottom:20px;"></div>';
	}
	
	// Metodo criado para verificar se a Darf esta em atraso
	private function ChecVencimentoDarf() {
		
		$datas = new Datas();
	
		$out = '';
		
		// Verifica se esta a DARF esta em atraso.
		if($datas->diferencaData(date("Y-m-d"),$this->CalcularDataVencimento($this->AuxDataEmissao)) > 0 ){

			// Inclui a mensagem da darf em atraso.
			$out = '<div style="float: left;margin-top: 3px;margin-right: 10px;color:#a61d00">
						DARF em atraso. Informe a data em  que pretende pagar o imposto para calculo de juros e correção: 
					</div>
					<input name="data_emprestimo" id="data_darf'.$this->IdPagtoAux.'" class="campoData" type="text" size="10" style="float: left;">
					<div style="clear:both;margin-bottom:10px;"></div>';

			$this->Script = "var data = $('#data_darf".$this->IdPagtoAux."').val();
							if( data === '' ){
								alert('Informe a data em que pretende efetuar o pagamento.');
							$('#data_darf".$this->IdPagtoAux."').focus();return;	
						} ";

			$this->Converter = 'var converter="&converter=converter";';
			$this->DataAux = $this->CalcularDataVencimento($this->AuxDataEmissao);
		}
		
		
		return $out;
	}
		
	// Método criado para passar as linhas da tabela.
	private function LinhaTD($nome, $data, $valorBruto, $IR) {
		
		// Monta a linha da tabela da darf referente os pagamento. 
		$out = " <tr>"
				."	<td class='td_calendario'>".$nome."</td>"
				."	<td class='td_calendario' align='right'>".$data."</td>"
				."	<td class='td_calendario' align='right'>".number_format($IR,2,',','.')."</td>"
				." </tr>";
		
		// Realiza o retorn da linha.
		return $out;
	}
	
	// Método criado para passar o rodapé das tabelas.
	private function RodapeTabela(){
	
		$out = "<tr>
			<th style='background-color: #999; font-weight: normal' align='right' colspan='2'>Totais:&nbsp;</th>
			<th style='background-color: #999; font-weight: normal' align='right'>".number_format($this->TotalIR,2,',','.')."</th>
		</tr></table>";
	
		return $out;
	}
	
	// Método criado para retorna o titulo de pagamento da tabelas. 
	private function PegaTituloPagamentoTabela() {
		
		// Define a variavel de retorno vazia.
		$out = '<div class="tituloAzulPequeno">';
		
		// Verifica qual titulo será retornado.
		switch($this->Tipo){
			case 'funcionários e pró-labore':
				$out .= 'Funcionários e Pró Labore: Rendimento do trabalho assalariado';
			break;
			case 'Estagiários':
				$out .= 'Estagiários';
			break;
			case 'Autônomos':
				$out .= 'Autônomos: Rendimento do trabalho sem vínculo empregatício';
			break;
			case 'distr. de lucros':
				$out .= 'Distribuição de Lucros';
			break;			
			case 'pessoa jurídica':
				if($this->CodigoServico != ''){
					$out .= 'Pessoa Jurídica: '.$this->DescricaoServico;
				}else{
					$out .= 'Pessoas Jurídicas Isentas';
				}
			break;
		}
		
		$out .= '</div>';
		
		// Retorna o titulo da tabela.
		return $out;
	}

	// Método utilizadopara gera a sessão com os dados do para gerar a DARF.
	function CriaSessaoDarf() {

		// Define qual sera o código e descrição do serviço que será apresentado na proxima página.
		if(($this->TipoAnterior != 'pessoa jurídica') || ($this->TipoAnterior == 'pessoa jurídica' && !empty($this->CodigoServicoAnterior))){
			
			switch($this->TipoAnterior){
				case 'funcionários e pró-labore':
					$this->CodigoServicoArray = "0561";
					$this->DescricaoServicoArray = "IRRF - Rendimento do trabalho assalariado";
				break;
				case 'Autônomos':
					$this->CodigoServicoArray = "0588";
					$this->DescricaoServicoArray = "IRRF - Rendimento do trabalho sem vínculo empregatício";
				break;
				default:
					$this->CodigoServicoArray = $this->CodigoServicoAnterior;
					$this->DescricaoServicoArray = $this->DescricaoServico;							
				break;
			}

			// Adiciona ao array da sessão o código serviço, descrição, tipo e o valor ir que sera apresentado no proximo página.
			array_push($_SESSION['dados_DARF_userSessao'], array('codigo_servico'=>$this->CodigoServicoArray,'descricao_servico'=>$this->DescricaoServicoArray,'tipo'=>$this->TipoAnterior,'valor'=>$this->TotalIR));
		}	
	}
	
	// Método criado para carregar os atributos com o valor do pagamento para normalizar o array.
	private function SetDadosPagamentos($dados) {
		
		$this->IdPagto = (isset($dados['id_pagto']) ? $dados['id_pagto'] : "");
		$this->Id = (isset($dados['id']) ? $dados['id'] : "");
		$this->Nome = (isset($dados['nome']) ? $dados['nome'] : "");
		$this->Periodo = (isset($dados['periodo']) ? $dados['periodo'] : "");
				
		if(isset($dados['tipo']) && $dados['tipo'] == 'pró-labore'){
			$this->Tipo = 'funcionários e pró-labore';
		} else {
			$this->Tipo = (isset($dados['tipo']) ? $dados['tipo'] : "");
		}
		
		$this->CPF = (isset($dados['cpf']) ? $dados['cpf'] : "");
		$this->Dependentes = (isset($dados['dependentes']) ? $dados['dependentes'] : "");
		$this->OP_Simples = (isset($dados['op_simples']) ? $dados['op_simples'] : "");
		$this->CodigoServico = (isset($dados['codigo_servico']) ? $dados['codigo_servico'] : "");
		$this->DescricaoServico = (isset($dados['descricao_servico']) ? $dados['descricao_servico'] : "");
		$this->DescDep = (isset($dados['desconto_dependentes']) ? $dados['desconto_dependentes'] : 0);
		$this->ValorBruto = (isset($dados['valor_bruto']) ? $dados['valor_bruto'] : 0);
		$this->INSS = (isset($dados['INSS']) ? $dados['INSS'] : 0);
		$this->IR = (isset($dados['IR']) ? $dados['IR'] : 0);
		$this->ISS = (isset($dados['ISS']) ? $dados['ISS'] : 0);
		$this->ValorLiquido = (isset($dados['valor_liquido']) ? $dados['valor_liquido'] : 0);
		$this->DataPagto = (isset($dados['data_pagto']) ? date("d/m/Y",strtotime($dados['data_pagto'])) : "");
		$this->DataEmissao = (isset($dados['data_emissao']) ? date("d/m/Y",strtotime($dados['data_emissao'])) : "");
		
	}
	
	// Método criado para carregar os atributos com o valor do pagamento dos funcionarios para normalizar o array.
	private function SetDadosPagamentosFuncionario($dados) {
		
		$this->IdPagto = 'f'.(isset($dados['pagtoId']) ? $dados['pagtoId'] : "");
		$this->Id = (isset($dados['idFuncionario']) ? $dados['idFuncionario'] : "");
		$this->Nome = (isset($dados['nome']) ? $dados['nome'] : "");
		$this->Periodo = "";
		$this->Tipo = 'funcionários e pró-labore';
		$this->CPF = (isset($dados['cpf']) ? $dados['cpf'] : "");
		$this->Dependentes = "";
		$this->OP_Simples = "";
		$this->CodigoServico = "";
		$this->DescricaoServico = "";
		$this->DescDep = 0;
		$this->ValorBruto = (isset($dados['valor_bruto']) ? $dados['valor_bruto'] : 0);
		$this->INSS = (isset($dados['INSS']) ? $dados['INSS'] : 0);
		
		// Soma o ir do salario com o ir de ferias.
		$ir = (isset($dados['IR']) ? $dados['IR'] : 0) + (isset($dados['IRFerias']) ? $dados['IRFerias'] : 0);		
		$this->IR = $ir;
		
		$this->ISS = 0;
		$this->ValorLiquido = (isset($dados['valor_liquido']) ? $dados['valor_liquido'] : 0);
		$this->DataPagto = (isset($dados['data_pagto']) ? date("d/m/Y",strtotime($dados['data_pagto'])) : "");
		$this->DataEmissao = "";
	}
	
	// Método criado para carregar os atributos com o valor do pagamento para poder montar as tabelas.
	private function SetDadosPagamentosTabela($dados) {
		$this->IdPagto = $dados['codPagto'];
		$this->Id = $dados['cod_S_F'];
		$this->Nome = $dados['Nome'];
		$this->Periodo = $dados['Periodo'];
		$this->Tipo = $dados['tipo'];
		$this->CPF = $dados['cpf'];
		$this->Dependentes = $dados['dependentes'];
		$this->OP_Simples = $dados['OP_Simples'];
		$this->CodigoServico = $dados['codigoServico'];
		$this->DescricaoServico = $dados['descricaoServico'];
		$this->DescDep = $dados['descDep'];
		$this->ValorBruto = $dados['valorBruto'];
		$this->INSS = $dados['inss'];
		$this->IR = $dados['ir'];
		$this->ISS =$dados['iss'];
		$this->ValorLiquido = $dados['valorLiquido'];
		$this->DataPagto = $dados['dataPagto'];
		$this->DataEmissao = $dados['dataEmissao'];
	}
		
	// Método criado para normalizar os dados de pagamento em um unico array.	
	private function NormalizaPagamentosArray($pagamentos, $tabelaPagamento = 'pagamentos'){
		
		$pagtoArray = array();
		
		foreach($pagamentos as $val) {
			
			if($tabelaPagamento == 'funcionario') {

				// Chama o método para passar os dados de pagamento para os atributos da classe.  
				$this->SetDadosPagamentosFuncionario($val);

			} else {

				// Chama o método para passar os dados de pagamento para os atributos da classe.  
				$this->SetDadosPagamentos($val);
			}

			// Monta array com os dados de pagamento.		
			$this->ArrayPagto[] = array('codPagto'=>$this->IdPagto
				,'cod_S_F'=>$this->Id
				,'Nome'=>$this->Nome 
				,'Periodo'=>$this->Periodo 
				,'tipo'=>$this->Tipo 
				,'cpf'=>$this->CPF 
				,'dependentes'=>$this->Dependentes 
				,'OP_Simples'=>$this->OP_Simples 
				,'codigoServico'=>$this->CodigoServico 
				,'descricaoServico'=>$this->DescricaoServico 
				,'descDep'=>$this->DescDep 
				,'valorBruto'=>$this->ValorBruto 
				,'inss'=>$this->INSS 
				,'ir'=>$this->IR 
				,'iss'=>$this->ISS 
				,'valorLiquido'=>$this->ValorLiquido 
				,'dataPagto'=>$this->DataPagto 
				,'dataEmissao'=>$this->DataEmissao);
		}
	}
	
	// Método criado para zera os atributos.
	private function ZeraAtributos() {
		$this->IdPagto = "";
		$this->IdPagtoAux = 0;
		$this->Id = "";
		$this->Nome = "";
		$this->Periodo = "";
		$this->Tipo = "";
		$this->CPF = "";
		$this->Dependentes = "";
		$this->OP_Simples = "";
		$this->CodigoServico = "";
		$this->DescricaoServico = "";
		$this->DescDep = 0;
		$this->ValorBruto = 0;
		$this->INSS = 0;
		$this->IR = 0;
		$this->ISS = 0;
		$this->ValorLiquido = 0;
		$this->DataPagto = "";
		$this->DataEmissao = "";
		$this->TotalValorBruto = 0;
		$this->TotalINSS = 0;
		$this->Total_Desc_Dep = 0;
		$this->TotalIR = 0;
		$this->TotalISS = 0;
		$this->TotalValorLiquido = 0;
		$this->TipoAnterior = "";
		$this->CodigoServicoAnterior = "";
		$this->TotalGeralIR = 0;
		$this->CodigoServicoArray = "";
		$this->DescricaoServicoArray = "";
		$this->Script = "";
		$this->Converter = "";
		$this->DataAux = "";
	}	
	
	// include 'datas.class.php';
	private function CalcularDataVencimento($data){
		
		$data_aux = '';
		
		$data_vencimento = false;

		// Instância a classe responsavel por manipular as datas.
		$datas = new Datas();

		// Monta um array com a data.
		$data_aux = explode('-', $data);

		// Monta data Ex: 2017-01-20 
		$data_aux = $data_aux[0].'-'.$data_aux[1].'-20';

		// Retona a data onde de 30 dias depois.
		$aux = $datas->somarData($data_aux,30);

		// Monta um array com a data.
		$data_aux = explode('-',$aux);

		// Pega o mês.
		$mes = $data_aux[1];

		// Paga o ano.
		$ano = $data_aux[0];

		// Pega a data de vencimento.
		$data_vencimento = $data_aux[0].'-'.$data_aux[1].'-20';

		// Retorna a data do vencimento.
		return $data_vencimento;
		
	}

	// Método criado para pegar o nome do mês 
	private function get_nome_mes($numero_mes){
		$arrMonth = array(
			1 => 'janeiro',
			2 => 'fevereiro',
			3 => 'março',
			4 => 'abril',
			5 => 'maio',
			6 => 'junho',
			7 => 'julho',
			8 => 'agosto',
			9 => 'setembro',
			10 => 'outubro',
			11 => 'novembro',
			12 => 'dezembro'
			);
		return $arrMonth[(int)$numero_mes];
	}

	// Método criado para montar o js para controle das tabelas 
	// Não mudei a programação do js para uma forma mais legivel e menos trabalhosa porque o vitor estava com pressa este codigo deve ser refatorado quando poder.
	function AcaoButaoTabela() {
		
		$out =  <<<__JS__
<script>
	
	$( document ).ready(function() {
		
		$(".enviarDarf{$this->IdPagtoAux}").click(function() {
			gerarDarf{$this->IdPagtoAux}();
		});

		function gerarDarf{$this->IdPagtoAux}(){
			var data = '';
			var data2 = '{$this->DataAux}';
			var converter = '';

__JS__;

		$out .= $this->Script.$this->Converter;
		
		$mes = date('m');
		$ano = date('Y');
		$dia = date('d');	

		if( $this->Converter != '' ){

			$out .= <<<__JS__
		
			data = data.split('/'); 

			if( parseInt(data[1]) <= 0 || parseInt(data[1]) > 12  ){
				alert('Informe uma data válida.');
				return;
			}
			if( parseInt(data[0]) <= 0 || parseInt(data[0]) > 31  ){
				alert('Informe uma data válida.');
				return;
			}

			if( parseInt(data[0]) < {$dia} || parseInt(data[0]) >= 31  ){
				if( parseInt(data[1]) <= {$mes} ){
					alert('A data não pode ser anterior à atual.');
					return;
				}
			}

			if( parseInt(data[1]) < {$mes} || parseInt(data[1]) > 12  ){
				if( parseInt(data[2]) <= ".." ){
					alert('A data não pode ser anterior à atual.');
					return;
				} 
			}

			if( parseInt(data[1]) != {$mes}){
				alert('Mês de pagamento não pode ultrapassar o mês corrente.');
				return;
			}

			if( parseInt(data[2]) < {$ano} ){
				alert('A data não pode ser anterior à atual.');
				return;
			}

			data = data[2]+'-'+data[1]+'-'+data[0];
__JS__;
}
	                	    		
if( $this->TotalIR == 0 ) {
	$out .= 'alert("Não há Darf a ser recolhido no período.");return;';
} elseif( $this->TotalIR < 10 ) {
	$out .= 'alert("O valor do DARF a pagar é inferior a R$ 10, portanto não é necessário fazer a retenção. o alerta aparece na hora que clica no botão para gerar o boleto");return;';
} 	

$out .= <<<__JS__

			abreJanela('gerar-darf-geral.php?data={$this->AuxDataEmissao}&data2='+data+converter+'&codigo_receita={$this->CodigoServicoArray}&valor={$this->TotalIR}','_blank','width=700, height=400, top=150, left=150, scrollbars=yes, resizable=yes');	
		}
	});

</script>
__JS__;
		
		// Retorna o js.
		return $out;
	}
}