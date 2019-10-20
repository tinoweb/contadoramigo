<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

// Inicia a sessão.
session_start();

// Realiza a requisição do arquivo que realiza a conexão como banco de dados.
require_once('conect.php') ;

// Realiza a requisição do arquivo que possui a classe para definir numero por extenso.
require_once('classes/numero_extenso_2.php'); 

// Realiza a requisição do arquivo responsavel por manipular os dados para gerar o pdf.
require_once('classes/fpdf/fpdf.php'); 

// Realiza a requisição do arquivo que retorna o objeto com os dados de pagamento do funcionário.
require_once('Model/PagamentoFuncionario/PagamentoFuncionarioData.php');

// Realiza a requisição do arquivo que retorna os dados da empresa.
require_once('Model/DadosEmpresa/DadosEmpresaData.php');

// Realiza a requisição do arquivo que retorna os dados do funcionario.
require_once('Model/DadosFuncionarios/DadosFuncionariosData.php');

/** Adiciona a classe para controlar a rotação no texto **/

class PDF extends FPDF {
	
	var $angle=0;

	function Rotate($angle,$x=-1,$y=-1) {
		if($x==-1)
			$x=$this->x;
		if($y==-1)
			$y=$this->y;
		if($this->angle!=0)
			$this->_out('Q');
		$this->angle=$angle;
		if($angle!=0)
		{
			$angle*=M_PI/180;
			$c=cos($angle);
			$s=sin($angle);
			$cx=$x*$this->k;
			$cy=($this->h-$y)*$this->k;
			$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
	}

	function _endpage()
	{
		if($this->angle!=0)
		{
			$this->angle=0;
			$this->_out('Q');
		}
		parent::_endpage();
	}

	function RotatedText($x,$y,$txt,$angle) {
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}

	function RotatedImage($file,$x,$y,$w,$h,$angle) {
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->Image($file,$x,$y,$w,$h);
		$this->Rotate(0);
	}
}

/** 
 * Classe criada para fazer o controle da geração do holerite.
 */
class GeraHoleriteFuncionario {
	
	private $NomeEmpresa = '';
	private $EnderecoEmpresa = '';
	private $CNPJEmpresa = '';
	private $MesAno = '';
	private $CodigoFuncionario = '';
	private $NomeFuncionario = '';
	private $CBOFuncionario = '';
	private $FuncaoFuncionario = '';
	private $Codigo = '';
	private $Descricao = '';
	private $Referencia = '';
	private $Provento = '';
	private $Desconto = '';
	private $Mensagem = '';
	private $TotalVencimento = '';
	private $TotalDescontos = '';
	private $LiquidoReceber = '';
	private $SalarioBase = '';
	private $BaseCalcInss = '';
	private $BaseCalcFgts = '';
	private $FGTSMes = '';
	private $BaseCalcIrrf = '';
	private $FaixaIrrf = '';
	
	public function __construct() {
		
		// Verifica se código do pagamento foi informado.
		if(isset($_GET['pagtoId'])) {

			// Instancia de Classes 
			$pagamentoFuncionario = new PagamentoFuncionarioData();
			$empresaData = new DadosEmpresaData();
			$funcionarioData = new DadosFuncionariosData();

			// Pega os dados de pagamento do funcionario.
			$dadosPagamento = $pagamentoFuncionario->PegaPagamentoFuncionario($_GET['pagtoId']);

			// Verifica se existe dados de pagamento para o funcionario informado.
			if($dadosPagamento) {
			
				// Pega os dados da empresa.
				$dadosEmpregado = $empresaData->GetDataDadosEmpresa($dadosPagamento->getEmpresaId());

				// Pega os dados do funcionário.
				$dadosFuncionario = $funcionarioData->PegaDadosFuncionario($dadosPagamento->getFuncionarioId());

				// Dados da Empresa.
				$this->NomeEmpresa = $dadosEmpregado->getRazaoSocial();
				$this->EnderecoEmpresa = $dadosEmpregado->getEndereco();
				$this->CNPJEmpresa = $dadosEmpregado->getCNPJ();

				// Mes e ano do Holerite.
				$this->MesAno = $this->PegaMesAno($dadosPagamento->getDataReferencia());

				// Dados do Funcionário.
				$this->CodigoFuncionario = $dadosFuncionario->getFuncionarioId();
				$this->NomeFuncionario = $dadosFuncionario->getNome();
				$this->CBOFuncionario = $dadosFuncionario->getCodigoCBO();
				$this->FuncaoFuncionario = $dadosFuncionario->getFuncao();

				// Atraves do tipo de pagamento sera definido o tipo de holerite a ser gerado Salário ou 13°.

				// Passa para a variável o nome do tipo de pagamento.
				$metodoCalculoPagamento = $dadosPagamento->getTipoPagto();
			
				// Chama o metodo responsavel por fazer o calculo.
				$this->$metodoCalculoPagamento($dadosPagamento);
				
				// Chama o método criado para gerar o holerite.
				$this->GeraHolerite();	
			}
		}	
	}
	
	// Método criado para pegar o mês e o ano.
	private function PegaMesAno($data) {

		// Define um array com os meses.	
		$meses = array(
			1 => 'Janeiro',
			2 => 'Fevereiro',
			3 => 'Março',
			4 => 'Abril',
			5 => 'Maio',
			6 => 'Junho',
			7 => 'Julho',
			8 => 'Agosto',
			9 => 'Setembro',
			10 => 'Outubro',
			11 => 'Novembro',
			12 => 'Dezembro'
		);

		$dataAux = explode('-',$data);
		$ano = $dataAux[0];
		$n = (int) $dataAux[1];
		$mes = $meses[$n];
		
		return $mes.'/'.$ano;
	} 
	
	
	// Método criado para gera o holerite.
	private function GeraHolerite() {
		
		// Define o tipo de folha.
		$pdf = new PDF("P","mm","A4");

		$pdf->SetTopMargin(10);
		$pdf->AddPage();

		/** Define as celulas **/

		// SELECIONANDO FONTE PARA O HEADER ESQUERDO
		$pdf->SetFont('arial','B',8); 

		//
		$pdf->SetFillColor(225,225,225);

		// CELULA QUE ENVOLVE O TEXTO 
		$pdf->Cell(170,18,'','TRBL','L',1);

		// CELULA DO CAMPO DE ASSINATURA. 
		$pdf->Cell(3,3,'','','R',1);

		// CELULA DO CAMPO DE ASSINATURA. 
		$pdf->Cell(20,125,'','TRBL','R',1);

		// Define o tamanho da fonte.
		$pdf->SetFont('arial','',6);

		// CONTEÚDO
		$pdf->SetXY(12,10);
		$pdf->Write(8,utf8_decode("EMPREGADOR")); 

		// Titulo Nome
		$pdf->SetXY(12,13);
		$pdf->Write(8,utf8_decode("Nome:")); 

		// Nome
		$pdf->SetXY(25,13);
		$pdf->Write(8,utf8_decode($this->NomeEmpresa)); 

		// Titulo Endereço
		$pdf->SetXY(12,17);
		$pdf->Write(8,utf8_decode("Endereço:"));

		// Endereço
		$pdf->SetXY(25,17);
		$pdf->Write(8,utf8_decode($this->EnderecoEmpresa));

		// Titulo CNPJ
		$pdf->SetXY(12,21);
		$pdf->Write(8,utf8_decode("CNPJ")); 

		// CNPJ
		$pdf->SetXY(25,21);
		$pdf->Write(8,utf8_decode($this->CNPJEmpresa)); 

		//Recibo de Pagamento de Salário
		$pdf->SetFont('arial','',10); // SELECIONANDO FONTE PARA O HEADER DIREITO
		$pdf->SetXY(125,11); // DEFINE A POSIÇÃO DO TEXTO 
		$pdf->Write(8,utf8_decode("Recibo de Pagamento de Salário")); // ESCREVE O TEXTO.

		// Referente ao Mês / Ano
		$pdf->SetFont('arial','',6);
		$pdf->SetXY(153,15);
		$pdf->Write(8,utf8_decode("Referente ao Mês / Ano")); 

		// Define o mes
		$pdf->SetFont('arial','',10);
		$pdf->SetXY(153,20);
		$pdf->Write(8,utf8_decode($this->MesAno)); 

		// Define o tanho da linha.
		$pdf->Ln(10);

		$pdf->SetFont('arial','',6);
		$pdf->Cell(170,10,'',1,'L',1);

		// Titulo cóidigo
		$pdf->SetXY(12,30);
		$pdf->Write(8,utf8_decode("CÓDIGO")); 

		// cóidigo
		$pdf->SetXY(12,33);
		$pdf->Write(8,utf8_decode($this->CodigoFuncionario)); 

		// titulo nome do funcionário
		$pdf->SetXY(25,30);
		$pdf->Write(8,utf8_decode("NOME DO FUNCIONÁRIO"));

		// Nome do funcionário
		$pdf->SetXY(25,33);
		$pdf->Write(8,utf8_decode($this->NomeFuncionario)); 

		// Titulo CBO
		$pdf->SetXY(100,30);
		$pdf->Write(8,utf8_decode("CBO")); 

		// CBO
		$pdf->SetXY(100,33);
		$pdf->Write(8,utf8_decode($this->CBOFuncionario)); 

		// Titulo função
		$pdf->SetXY(125,30);
		$pdf->Write(8,utf8_decode("FUNÇÃO")); 

		// Titulo função
		$pdf->SetXY(125,33);
		$pdf->Write(8,utf8_decode($this->FuncaoFuncionario)); 

		$pdf->Ln(9);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(15,5,'',1,'L',1);
		$pdf->Cell(75,5,'','TRB','L',1);
		$pdf->Cell(26,5,'','TRB','L',1);
		$pdf->Cell(27,5,'','TRB','L',1);
		$pdf->Cell(27,5,'','TRB','L',1);

		// Titulo CÓD
		$pdf->SetXY(12,41);
		$pdf->Write(8,utf8_decode("CÓD")); 

		// Titulo Descrição
		$pdf->SetXY(25,41);
		$pdf->Write(8,utf8_decode("Descrição")); 

		// Titulo Referência
		$pdf->SetXY(107,41);
		$pdf->Write(8,utf8_decode("Referência")); 

		// Titulo Proventos
		$pdf->SetXY(132,41);
		$pdf->Write(8,utf8_decode("Proventos")); 

		// Titulo Descontos
		$pdf->SetXY(160,41);
		$pdf->Write(8,utf8_decode("Descontos")); 

		$pdf->Ln(6);

		// Campo que recebera valores.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(15,67,'',1,'L',1);
		$pdf->Cell(75,67,'','TRB','L',1);
		$pdf->Cell(26,67,'','TRB','L',1);
		$pdf->Cell(27,74,'','TRB','L',1);
		$pdf->Cell(27,74,'','TRB','L',1);

		// cóidigo
		$pdf->SetXY(12,48);
		$pdf->Write(8,utf8_decode($this->Codigo[1])); 
		$pdf->SetXY(12,52);
		$pdf->Write(8,utf8_decode($this->Codigo[2])); 
		$pdf->SetXY(12,56);
		$pdf->Write(8,utf8_decode($this->Codigo[3])); 
		$pdf->SetXY(12,60);
		$pdf->Write(8,utf8_decode($this->Codigo[4])); 
		$pdf->SetXY(12,64);
		$pdf->Write(8,utf8_decode($this->Codigo[5])); 
		$pdf->SetXY(12,68);
		$pdf->Write(8,utf8_decode($this->Codigo[6])); 
		$pdf->SetXY(12,72);
		$pdf->Write(8,utf8_decode($this->Codigo[7])); 
		$pdf->SetXY(12,76);
		$pdf->Write(8,utf8_decode($this->Codigo[8])); 
		$pdf->SetXY(12,80);
		$pdf->Write(8,utf8_decode($this->Codigo[9])); 
		$pdf->SetXY(12,84);
		$pdf->Write(8,utf8_decode($this->Codigo[10])); 
		$pdf->SetXY(12,88);
		$pdf->Write(8,utf8_decode($this->Codigo[11])); 
		$pdf->SetXY(12,92);
		$pdf->Write(8,utf8_decode($this->Codigo[12]));
		$pdf->SetXY(160,96);
		$pdf->Write(8,utf8_decode($this->Codigo[13]));
		$pdf->SetXY(160,100);
		$pdf->Write(8,utf8_decode($this->Codigo[14]));
		$pdf->SetXY(160,104);
		$pdf->Write(8,utf8_decode($this->Codigo[15]));		

		// Descrição
		$pdf->SetXY(25,48);
		$pdf->Write(8,utf8_decode($this->Descricao[1])); 
		$pdf->SetXY(25,52);
		$pdf->Write(8,utf8_decode($this->Descricao[2])); 
		$pdf->SetXY(25,56);
		$pdf->Write(8,utf8_decode($this->Descricao[3])); 
		$pdf->SetXY(25,60);
		$pdf->Write(8,utf8_decode($this->Descricao[4])); 
		$pdf->SetXY(25,64);
		$pdf->Write(8,utf8_decode($this->Descricao[5])); 
		$pdf->SetXY(25,68);
		$pdf->Write(8,utf8_decode($this->Descricao[6])); 
		$pdf->SetXY(25,72);
		$pdf->Write(8,utf8_decode($this->Descricao[7])); 
		$pdf->SetXY(25,76);
		$pdf->Write(8,utf8_decode($this->Descricao[8])); 
		$pdf->SetXY(25,80);
		$pdf->Write(8,utf8_decode($this->Descricao[9])); 
		$pdf->SetXY(25,84);
		$pdf->Write(8,utf8_decode($this->Descricao[10])); 
		$pdf->SetXY(25,88);
		$pdf->Write(8,utf8_decode($this->Descricao[11]));
		$pdf->SetXY(25,92);
		$pdf->Write(8,utf8_decode($this->Descricao[12]));
		$pdf->SetXY(160,96);
		$pdf->Write(8,utf8_decode($this->Descricao[13]));
		$pdf->SetXY(160,100);
		$pdf->Write(8,utf8_decode($this->Descricao[14]));
		$pdf->SetXY(160,104);
		$pdf->Write(8,utf8_decode($this->Descricao[15]));		

		// Referência
		$pdf->SetXY(107,48);
		$pdf->Write(8,utf8_decode($this->Referencia[1])); 
		$pdf->SetXY(107,52);
		$pdf->Write(8,utf8_decode($this->Referencia[2])); 
		$pdf->SetXY(107,56);
		$pdf->Write(8,utf8_decode($this->Referencia[3])); 
		$pdf->SetXY(107,60);
		$pdf->Write(8,utf8_decode($this->Referencia[4])); 
		$pdf->SetXY(107,64);
		$pdf->Write(8,utf8_decode($this->Referencia[5])); 
		$pdf->SetXY(107,68);
		$pdf->Write(8,utf8_decode($this->Referencia[6])); 
		$pdf->SetXY(107,72);
		$pdf->Write(8,utf8_decode($this->Referencia[7])); 
		$pdf->SetXY(107,76);
		$pdf->Write(8,utf8_decode($this->Referencia[8])); 
		$pdf->SetXY(107,80);
		$pdf->Write(8,utf8_decode($this->Referencia[9])); 
		$pdf->SetXY(107,84);
		$pdf->Write(8,utf8_decode($this->Referencia[10])); 
		$pdf->SetXY(107,88);
		$pdf->Write(8,utf8_decode($this->Referencia[11]));
		$pdf->SetXY(107,92);
		$pdf->Write(8,utf8_decode($this->Referencia[12]));
		$pdf->SetXY(160,96);
		$pdf->Write(8,utf8_decode($this->Referencia[13]));
		$pdf->SetXY(160,100);
		$pdf->Write(8,utf8_decode($this->Referencia[14]));
		$pdf->SetXY(160,104);
		$pdf->Write(8,utf8_decode($this->Referencia[15]));
		
		// Proventos
		$pdf->SetXY(132,48);
		$pdf->Write(8,utf8_decode($this->Provento[1])); 
		$pdf->SetXY(132,52);
		$pdf->Write(8,utf8_decode($this->Provento[2])); 
		$pdf->SetXY(132,56);
		$pdf->Write(8,utf8_decode($this->Provento[3])); 
		$pdf->SetXY(132,60);
		$pdf->Write(8,utf8_decode($this->Provento[4])); 
		$pdf->SetXY(132,64);
		$pdf->Write(8,utf8_decode($this->Provento[5])); 
		$pdf->SetXY(132,68);
		$pdf->Write(8,utf8_decode($this->Provento[6])); 
		$pdf->SetXY(132,72);
		$pdf->Write(8,utf8_decode($this->Provento[7])); 
		$pdf->SetXY(132,76);
		$pdf->Write(8,utf8_decode($this->Provento[8])); 
		$pdf->SetXY(132,80);
		$pdf->Write(8,utf8_decode($this->Provento[9])); 
		$pdf->SetXY(132,84);
		$pdf->Write(8,utf8_decode($this->Provento[10])); 
		$pdf->SetXY(132,88);
		$pdf->Write(8,utf8_decode($this->Provento[11]));
		$pdf->SetXY(132,92);
		$pdf->Write(8,utf8_decode($this->Provento[12]));
		$pdf->SetXY(160,96);
		$pdf->Write(8,utf8_decode($this->Provento[13]));
		$pdf->SetXY(160,100);
		$pdf->Write(8,utf8_decode($this->Provento[14]));
		$pdf->SetXY(160,104);
		$pdf->Write(8,utf8_decode($this->Provento[15]));
		
		// Descontos
		$pdf->SetXY(160,48);
		$pdf->Write(8,utf8_decode($this->Desconto[1])); 
		$pdf->SetXY(160,52);
		$pdf->Write(8,utf8_decode($this->Desconto[2])); 
		$pdf->SetXY(160,56);
		$pdf->Write(8,utf8_decode($this->Desconto[3])); 
		$pdf->SetXY(160,60);
		$pdf->Write(8,utf8_decode($this->Desconto[4])); 
		$pdf->SetXY(160,64);
		$pdf->Write(8,utf8_decode($this->Desconto[5])); 
		$pdf->SetXY(160,68);
		$pdf->Write(8,utf8_decode($this->Desconto[6])); 
		$pdf->SetXY(160,72);
		$pdf->Write(8,utf8_decode($this->Desconto[7])); 
		$pdf->SetXY(160,76);
		$pdf->Write(8,utf8_decode($this->Desconto[8])); 
		$pdf->SetXY(160,80);
		$pdf->Write(8,utf8_decode($this->Desconto[9])); 
		$pdf->SetXY(160,84);
		$pdf->Write(8,utf8_decode($this->Desconto[10])); 
		$pdf->SetXY(160,88);
		$pdf->Write(8,utf8_decode($this->Desconto[11]));
		$pdf->SetXY(160,92);
		$pdf->Write(8,utf8_decode($this->Desconto[12]));
		$pdf->SetXY(160,96);
		$pdf->Write(8,utf8_decode($this->Desconto[13]));
		$pdf->SetXY(160,100);
		$pdf->Write(8,utf8_decode($this->Desconto[14]));
		$pdf->SetXY(160,104);
		$pdf->Write(8,utf8_decode($this->Desconto[15]));	
		
		// Linhas
		$pdf->Ln(10);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(116,14,'',1,'L',1);
		$pdf->Cell(27,14,'','TRB','L',1);
		$pdf->Cell(27,14,'','TRB','L',1);

		// Titulo Mensagem
		$pdf->SetXY(12,112);
		$pdf->Write(8,utf8_decode("Mensagem"));

		// Mensagem
		$pdf->SetXY(12,115);
		$pdf->Write(8,utf8_decode($this->Mensagem));

		// Titulo Total dos Vencimentos
		$pdf->SetXY(126,112);
		$pdf->Write(8,utf8_decode("Total dos Vencimentos")); 

		// Total dos Vencimentos
		$pdf->SetXY(132,115);
		$pdf->Write(8,utf8_decode($this->TotalVencimento)); 


		// Titulo Total dos Descontos
		$pdf->SetXY(154,112);
		$pdf->Write(8,utf8_decode("Total dos Descontos"));

		// Total dos Descontos
		$pdf->SetXY(160,115);
		$pdf->Write(8,utf8_decode($this->TotalDescontos));

		// Titulo Proventos
		$pdf->SetXY(127,121);
		$pdf->Write(8,utf8_decode("Líquido a Receber->")); 

		// Titulo Descontos
		$pdf->SetXY(160,121);
		$pdf->Write(8,utf8_decode($this->LiquidoReceber)); 

		// Linhas
		$pdf->Ln(7);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(170,7,'',1,'L',1);

		// Titulo Salário Base
		$pdf->SetXY(12,126);
		$pdf->Write(8,utf8_decode("Salário Base")); 

		// Salário Base
		$pdf->SetXY(14,129);
		$pdf->Write(8,utf8_decode($this->SalarioBase)); 

		// Titulo Base Cálc. INSS
		$pdf->SetXY(35,126);
		$pdf->Write(8,utf8_decode("Base Cálc. INSS"));

		// Base Cálc. INSS
		$pdf->SetXY(39,129);
		$pdf->Write(8,utf8_decode($this->BaseCalcInss));

		// Titulo Base Cálc.FGTS
		$pdf->SetXY(63,126);
		$pdf->Write(8,utf8_decode("Base Cálc.FGTS")); 

		// Total Base Cálc.FGTS
		$pdf->SetXY(69,129);
		$pdf->Write(8,utf8_decode($this->BaseCalcFgts)); 

		// Titulo FGTS do Mês
		$pdf->SetXY(95,126);
		$pdf->Write(8,utf8_decode("FGTS do Mês"));

		// Total FGTS do Mês
		$pdf->SetXY(100,129);
		$pdf->Write(8,utf8_decode($this->FGTSMes));

		// Titulo Base Cálc. IRRF
		$pdf->SetXY(126,126);
		$pdf->Write(8,utf8_decode("Base Cálc. IRRF")); 

		// Total dos Vencimentos
		$pdf->SetXY(132,129);
		$pdf->Write(8,utf8_decode($this->BaseCalcIrrf)); 

		// Titulo Faixa IRRF
		$pdf->SetXY(159,126);
		$pdf->Write(8,utf8_decode("Faixa IRRF"));

		// Total dos Descontos
		$pdf->SetXY(165,129);
		$pdf->Write(8,utf8_decode($this->FaixaIrrf));

		// Campo de Assinatura 
		$pdf->SetFont('Arial','',6);
		$pdf->RotatedText(188,130,utf8_decode('DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMINADA NESTE RECIBO.'),90);
		$pdf->SetFont('Arial','',7);

		// Campo de data.
		$pdf->SetFont('Arial','',6);
		$pdf->RotatedText(194,130,'____/____/________',90);
		$pdf->RotatedText(198,125,'DATA',90);

		// Campo de assiantura.
		$pdf->SetFont('Arial','',6);
		$pdf->RotatedText(194,80,'_________________________________________________',90);
		$pdf->RotatedText(198,70,utf8_decode('ASSINATURA DO FUNCIONÁRIO'),90);

		// Titulo Salário Base
		$pdf->SetXY(9,133);
		$pdf->Write(8,utf8_decode("1ª VIA - EMPREGADOR"));

		// Titulo Salário Base
		$pdf->SetXY(20,135);
		$pdf->Write(8,utf8_decode("------")); 

		// Titulo Salário Base
		$pdf->SetXY(180,135);
		$pdf->Write(8,utf8_decode("------")); 

		/** Segundo via do Empregado **/

		// Linhas
		$pdf->Ln(8);

		// SELECIONANDO FONTE PARA O HEADER ESQUERDO
		$pdf->SetFont('arial','B',8); 
		
		// CELULA QUE ENVOLVE O TEXTO 
		$pdf->Cell(170,18,'','TRBL','L',1);

		// CELULA DO CAMPO DE ASSINATURA. 
		$pdf->Cell(3,3,'','','R',1);

		// CELULA DO CAMPO DE ASSINATURA. 
		$pdf->Cell(20,125,'','TRBL','R',1);	
		
		// Define o tamanho da fonte.
		$pdf->SetFont('arial','',6);

		// CONTEÚDO
		$pdf->SetXY(12,143);
		$pdf->Write(8,utf8_decode("EMPREGADOR")); 

		// Titulo Nome
		$pdf->SetXY(12,146);
		$pdf->Write(8,utf8_decode("Nome:")); 

		// Nome
		$pdf->SetXY(25,146);
		$pdf->Write(8,utf8_decode($this->NomeEmpresa)); 

		// Titulo Endereço
		$pdf->SetXY(12,150);
		$pdf->Write(8,utf8_decode("Endereço:"));

		// Endereço
		$pdf->SetXY(25,150);
		$pdf->Write(8,utf8_decode($this->EnderecoEmpresa));

		// Titulo CNPJ
		$pdf->SetXY(12,154);
		$pdf->Write(8,utf8_decode("CNPJ")); 

		// CNPJ
		$pdf->SetXY(25,154);
		$pdf->Write(8,utf8_decode($this->CNPJEmpresa)); 

		// SELECIONANDO FONTE PARA O HEADER DIREITO
		$pdf->SetFont('arial','',10);
		$pdf->SetXY(113,144);
		$pdf->Write(8,utf8_decode("Demonstrativo de Pagamento de Salário")); 

		// Referente ao Mês / Ano
		$pdf->SetFont('arial','',6);
		$pdf->SetXY(153,148);
		$pdf->Write(8,utf8_decode("Referente ao Mês / Ano")); 

		// Define o mes
		$pdf->SetFont('arial','',10);
		$pdf->SetXY(153,153);
		$pdf->Write(8,utf8_decode($this->MesAno)); 

		// Define o tanho da linha.
		$pdf->Ln(10);

		$pdf->SetFont('arial','',6);
		$pdf->Cell(170,10,'',1,'L',1);

		// Titulo cóidigo
		$pdf->SetXY(12,163);
		$pdf->Write(8,utf8_decode("CÓDIGO")); 

		// cóidigo
		$pdf->SetXY(12,166);
		$pdf->Write(8,utf8_decode($this->CodigoFuncionario)); 

		// titulo nome do funcionário
		$pdf->SetXY(25,163);
		$pdf->Write(8,utf8_decode("NOME DO FUNCIONÁRIO"));

		// Nome do funcionário
		$pdf->SetXY(25,166);
		$pdf->Write(8,utf8_decode($this->NomeFuncionario)); 

		// Titulo CBO
		$pdf->SetXY(100,163);
		$pdf->Write(8,utf8_decode("CBO")); 

		// CBO
		$pdf->SetXY(100,166);
		$pdf->Write(8,utf8_decode($this->CBOFuncionario)); 

		// Titulo função
		$pdf->SetXY(125,163);
		$pdf->Write(8,utf8_decode("FUNÇÃO")); 

		// Titulo função
		$pdf->SetXY(125,166);
		$pdf->Write(8,utf8_decode($this->FuncaoFuncionario)); 

		$pdf->Ln(9);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(15,5,'',1,'L',1);
		$pdf->Cell(75,5,'','TRB','L',1);
		$pdf->Cell(26,5,'','TRB','L',1);
		$pdf->Cell(27,5,'','TRB','L',1);
		$pdf->Cell(27,5,'','TRB','L',1);

		// Titulo CÓD
		$pdf->SetXY(12,174);
		$pdf->Write(8,utf8_decode("CÓD")); 

		// Titulo Descrição
		$pdf->SetXY(25,174);
		$pdf->Write(8,utf8_decode("Descrição")); 

		// Titulo Referência
		$pdf->SetXY(107,174);
		$pdf->Write(8,utf8_decode("Referência")); 

		// Titulo Proventos
		$pdf->SetXY(132,174);
		$pdf->Write(8,utf8_decode("Proventos")); 

		// Titulo Descontos
		$pdf->SetXY(160,174);
		$pdf->Write(8,utf8_decode("Descontos")); 

		$pdf->Ln(6);

		// Campo que recebera valores de vencimento, desconto, etc.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(15,67,'',1,'L',1);
		$pdf->Cell(75,67,'','TRB','L',1);
		$pdf->Cell(26,67,'','TRB','L',1);
		$pdf->Cell(27,74,'','TRB','L',1);
		$pdf->Cell(27,74,'','TRB','L',1);

		// cóidigo
		$pdf->SetXY(12,180);
		$pdf->Write(8,utf8_decode($this->Codigo[1]));
		$pdf->SetXY(12,184);
		$pdf->Write(8,utf8_decode($this->Codigo[2]));		
		$pdf->SetXY(12,188);
		$pdf->Write(8,utf8_decode($this->Codigo[3])); 
		$pdf->SetXY(12,192);
		$pdf->Write(8,utf8_decode($this->Codigo[4])); 
		$pdf->SetXY(12,196);
		$pdf->Write(8,utf8_decode($this->Codigo[5])); 
		$pdf->SetXY(12,200);
		$pdf->Write(8,utf8_decode($this->Codigo[6])); 
		$pdf->SetXY(12,204);
		$pdf->Write(8,utf8_decode($this->Codigo[7])); 
		$pdf->SetXY(12,208);
		$pdf->Write(8,utf8_decode($this->Codigo[8])); 
		$pdf->SetXY(12,212);
		$pdf->Write(8,utf8_decode($this->Codigo[9])); 
		$pdf->SetXY(12,216);
		$pdf->Write(8,utf8_decode($this->Codigo[10])); 
		$pdf->SetXY(12,220);
		$pdf->Write(8,utf8_decode($this->Codigo[11])); 
		$pdf->SetXY(12,224);
		$pdf->Write(8,utf8_decode($this->Codigo[12])); 
		$pdf->SetXY(12,228);
		$pdf->Write(8,utf8_decode($this->Codigo[13]));
		$pdf->SetXY(12,232);
		$pdf->Write(8,utf8_decode($this->Codigo[14]));
		$pdf->SetXY(12,236);
		$pdf->Write(8,utf8_decode($this->Codigo[15]));
		
		// Descrição
		$pdf->SetXY(25,180);
		$pdf->Write(8,utf8_decode($this->Descricao[1]));
		$pdf->SetXY(25,184);
		$pdf->Write(8,utf8_decode($this->Descricao[2]));		
		$pdf->SetXY(25,188);
		$pdf->Write(8,utf8_decode($this->Descricao[3])); 
		$pdf->SetXY(25,192);
		$pdf->Write(8,utf8_decode($this->Descricao[4])); 
		$pdf->SetXY(25,196);
		$pdf->Write(8,utf8_decode($this->Descricao[5])); 
		$pdf->SetXY(25,200);
		$pdf->Write(8,utf8_decode($this->Descricao[6])); 
		$pdf->SetXY(25,204);
		$pdf->Write(8,utf8_decode($this->Descricao[7])); 
		$pdf->SetXY(25,208);
		$pdf->Write(8,utf8_decode($this->Descricao[8])); 
		$pdf->SetXY(25,225);
		$pdf->Write(8,utf8_decode($this->Descricao[9])); 
		$pdf->SetXY(25,216);
		$pdf->Write(8,utf8_decode($this->Descricao[10])); 
		$pdf->SetXY(25,220);
		$pdf->Write(8,utf8_decode($this->Descricao[11])); 
		$pdf->SetXY(25,224);
		$pdf->Write(8,utf8_decode($this->Descricao[12])); 
		$pdf->SetXY(25,228);
		$pdf->Write(8,utf8_decode($this->Descricao[13]));
		$pdf->SetXY(25,232);
		$pdf->Write(8,utf8_decode($this->Descricao[14]));
		$pdf->SetXY(25,236);
		$pdf->Write(8,utf8_decode($this->Descricao[15]));	
		
		// Referência
		$pdf->SetXY(107,180);
		$pdf->Write(8,utf8_decode($this->Referencia[1]));
		$pdf->SetXY(107,184);
		$pdf->Write(8,utf8_decode($this->Referencia[2]));		
		$pdf->SetXY(107,188);
		$pdf->Write(8,utf8_decode($this->Referencia[3])); 
		$pdf->SetXY(107,192);
		$pdf->Write(8,utf8_decode($this->Referencia[4])); 
		$pdf->SetXY(107,196);
		$pdf->Write(8,utf8_decode($this->Referencia[5])); 
		$pdf->SetXY(107,200);
		$pdf->Write(8,utf8_decode($this->Referencia[6])); 
		$pdf->SetXY(107,204);
		$pdf->Write(8,utf8_decode($this->Referencia[7])); 
		$pdf->SetXY(107,208);
		$pdf->Write(8,utf8_decode($this->Referencia[8])); 
		$pdf->SetXY(107,225);
		$pdf->Write(8,utf8_decode($this->Referencia[9])); 
		$pdf->SetXY(107,216);
		$pdf->Write(8,utf8_decode($this->Referencia[10])); 
		$pdf->SetXY(107,220);
		$pdf->Write(8,utf8_decode($this->Referencia[11])); 
		$pdf->SetXY(107,224);
		$pdf->Write(8,utf8_decode($this->Referencia[12])); 
		$pdf->SetXY(107,228);
		$pdf->Write(8,utf8_decode($this->Referencia[13]));
		$pdf->SetXY(107,232);
		$pdf->Write(8,utf8_decode($this->Referencia[14]));
		$pdf->SetXY(107,236);
		$pdf->Write(8,utf8_decode($this->Referencia[15]));		
		
		// Proventos
		$pdf->SetXY(132,180);
		$pdf->Write(8,utf8_decode($this->Provento[1]));
		$pdf->SetXY(132,184);
		$pdf->Write(8,utf8_decode($this->Provento[2]));		
		$pdf->SetXY(132,188);
		$pdf->Write(8,utf8_decode($this->Provento[3])); 
		$pdf->SetXY(132,192);
		$pdf->Write(8,utf8_decode($this->Provento[4])); 
		$pdf->SetXY(132,196);
		$pdf->Write(8,utf8_decode($this->Provento[5])); 
		$pdf->SetXY(132,200);
		$pdf->Write(8,utf8_decode($this->Provento[6])); 
		$pdf->SetXY(132,204);
		$pdf->Write(8,utf8_decode($this->Provento[7])); 
		$pdf->SetXY(132,208);
		$pdf->Write(8,utf8_decode($this->Provento[8])); 
		$pdf->SetXY(132,225);
		$pdf->Write(8,utf8_decode($this->Provento[9])); 
		$pdf->SetXY(132,216);
		$pdf->Write(8,utf8_decode($this->Provento[10])); 
		$pdf->SetXY(132,220);
		$pdf->Write(8,utf8_decode($this->Provento[11])); 
		$pdf->SetXY(132,224);
		$pdf->Write(8,utf8_decode($this->Provento[12])); 
		$pdf->SetXY(132,228);
		$pdf->Write(8,utf8_decode($this->Provento[13]));
		$pdf->SetXY(132,232);
		$pdf->Write(8,utf8_decode($this->Provento[14]));
		$pdf->SetXY(132,236);
		$pdf->Write(8,utf8_decode($this->Provento[15]));			
		
		// Descontos
		$pdf->SetXY(160,180);
		$pdf->Write(8,utf8_decode($this->Desconto[1]));
		$pdf->SetXY(160,184);
		$pdf->Write(8,utf8_decode($this->Desconto[2]));		
		$pdf->SetXY(160,188);
		$pdf->Write(8,utf8_decode($this->Desconto[3])); 
		$pdf->SetXY(160,192);
		$pdf->Write(8,utf8_decode($this->Desconto[4])); 
		$pdf->SetXY(160,196);
		$pdf->Write(8,utf8_decode($this->Desconto[5])); 
		$pdf->SetXY(160,200);
		$pdf->Write(8,utf8_decode($this->Desconto[6])); 
		$pdf->SetXY(160,204);
		$pdf->Write(8,utf8_decode($this->Desconto[7])); 
		$pdf->SetXY(160,208);
		$pdf->Write(8,utf8_decode($this->Desconto[8])); 
		$pdf->SetXY(160,225);
		$pdf->Write(8,utf8_decode($this->Desconto[9])); 
		$pdf->SetXY(160,216);
		$pdf->Write(8,utf8_decode($this->Desconto[10])); 
		$pdf->SetXY(160,220);
		$pdf->Write(8,utf8_decode($this->Desconto[11])); 
		$pdf->SetXY(160,224);
		$pdf->Write(8,utf8_decode($this->Desconto[12])); 
		$pdf->SetXY(160,228);
		$pdf->Write(8,utf8_decode($this->Desconto[13]));
		$pdf->SetXY(160,232);
		$pdf->Write(8,utf8_decode($this->Desconto[14]));
		$pdf->SetXY(160,236);
		$pdf->Write(8,utf8_decode($this->Desconto[15]));
		
		// Linhas
		$pdf->Ln(11);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(116,14,'',1,'L',1);
		$pdf->Cell(27,14,'','TRB','L',1);
		$pdf->Cell(27,14,'','TRB','L',1);

		// Titulo Mensagem
		$pdf->SetXY(12,245);
		$pdf->Write(8,utf8_decode("Mensagem"));

		// Mensagem
		$pdf->SetXY(12,249);
		$pdf->Write(8,utf8_decode($this->Mensagem));

		// Titulo Total dos Vencimentos
		$pdf->SetXY(126,245);
		$pdf->Write(8,utf8_decode("Total dos Vencimentos")); 

		// Total dos Vencimentos
		$pdf->SetXY(132,248);
		$pdf->Write(8,utf8_decode($this->TotalVencimento)); 

		// Titulo Total dos Descontos
		$pdf->SetXY(154,245);
		$pdf->Write(8,utf8_decode("Total dos Descontos"));

		// Total dos Descontos
		$pdf->SetXY(160,248);
		$pdf->Write(8,utf8_decode($this->TotalDescontos));

		// Titulo Proventos
		$pdf->SetXY(127,253);
		$pdf->Write(8,utf8_decode("Líquido a Receber->")); 

		// Titulo Descontos
		$pdf->SetXY(160,253);
		$pdf->Write(8,utf8_decode($this->LiquidoReceber)); 

		// Linhas
		$pdf->Ln(8);

		// Define cabeçalho.
		$pdf->SetFont('arial','',7);
		$pdf->Cell(170,7,'',1,'L',1);

		// Titulo Salário Base
		$pdf->SetXY(12,259);
		$pdf->Write(8,utf8_decode("Salário Base")); 

		// Salário Base
		$pdf->SetXY(14,262);
		$pdf->Write(8,utf8_decode($this->SalarioBase)); 

		// Titulo Base Cálc. INSS
		$pdf->SetXY(35,259);
		$pdf->Write(8,utf8_decode("Base Cálc. INSS"));

		// Base Cálc. INSS
		$pdf->SetXY(39,262);
		$pdf->Write(8,utf8_decode($this->BaseCalcInss));

		// Titulo Base Cálc.FGTS
		$pdf->SetXY(63,259);
		$pdf->Write(8,utf8_decode("Base Cálc.FGTS")); 

		// Total Base Cálc.FGTS
		$pdf->SetXY(69,262);
		$pdf->Write(8,utf8_decode($this->BaseCalcFgts)); 

		// Titulo FGTS do Mês
		$pdf->SetXY(95,259);
		$pdf->Write(8,utf8_decode("FGTS do Mês"));

		// Total FGTS do Mês
		$pdf->SetXY(100,262);
		$pdf->Write(8,utf8_decode($this->FGTSMes));

		// Titulo Base Cálc. IRRF
		$pdf->SetXY(126,259);
		$pdf->Write(8,utf8_decode("Base Cálc. IRRF")); 

		// Total dos Vencimentos
		$pdf->SetXY(132,262);
		$pdf->Write(8,utf8_decode($this->BaseCalcIrrf)); 

		// Titulo Faixa IRRF
		$pdf->SetXY(159,259);
		$pdf->Write(8,utf8_decode("Faixa IRRF"));

		// Total dos Descontos
		$pdf->SetXY(165,262);
		$pdf->Write(8,utf8_decode($this->FaixaIrrf));

		// Campo de Assinatura
		$pdf->SetFont('Arial','',6);
		$pdf->RotatedText(188,263,utf8_decode('DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMINADA NESTE RECIBO.'),90);
		$pdf->SetFont('Arial','',7);

		// Campo de data.
		$pdf->SetFont('Arial','',6);
		$pdf->RotatedText(194,263,'____/____/________',90);
		$pdf->RotatedText(198,258,'DATA',90);

		// Campo de assiantura.
		$pdf->SetFont('Arial','',6);
		$pdf->RotatedText(194,204,'_________________________________________________',90);
		$pdf->RotatedText(198,194,utf8_decode('ASSINATURA DO FUNCIONÁRIO'),90);

		// 2ª VIA - EMPREGADO
		$pdf->SetXY(9,266);
		$pdf->Write(8,utf8_decode("2ª VIA - EMPREGADO"));

		// realiza a impressao no navegador
		//$pdf->Output();

		// Gera o holerite em pdf.
		$pdf->Output("holerite_".str_replace(' ','-',$nomeFuncionario). $this->MesAno . ".pdf","D");
	}
	
	// Método criado para definir os dados do 13° a ser pago.
	private function decimoTerceiro($dadosPagamento) {
		
			// Variável para receber o valor da primeira parcela do 13°.
			$valorPrimeiraParcela = 0;
		
			// Passa o valor do vencimento para uma variável que servira usada para os calculos.
			$totalVencimentoAux = $dadosPagamento->getValorBruto();
		
			// Define o vencimento já formatado como moeda.
			$this->TotalVencimento = number_format($totalVencimentoAux,2,",",".");
		
			// Pega o total de descontos.
			$totalDescontosAux = $dadosPagamento->getValorINSS() + $dadosPagamento->getValorIR() + $dadosPagamento->getValorPensao() + $dadosPagamento->getValorVR() + $dadosPagamento->getValorVT() + $dadosPagamento->getValorFaltas();	

			$this->TotalDescontos = number_format($totalDescontosAux,2,",",".");
			$this->LiquidoReceber = number_format(($totalVencimentoAux - $totalDescontosAux),2,",",".");
			
			// Define o valor do salário do funcionario já formatado como moeda.
			$this->SalarioBase = number_format($dadosPagamento->getValorSalarioFuncionario(),2,",",".");
		
			// Na primeira parcela não e tem a Base de Calculo do INSS e do IRRF.
			if($dadosPagamento->getParcelaDecimo() == 'primeira') {
				
				// Passa para os atributos de INSS e 
				$this->BaseCalcInss = number_format(0,2,",",".");
				$this->BaseCalcIrrf = number_format(0,2,",",".");
				$this->FaixaIrrf = 0;
				
				// A Base de cálculo do FGTS e em cima do vencimento referente ao valor da segunda parcela
				$this->BaseCalcFgts = number_format($totalVencimentoAux,2,",",".");
			
				// o valor do FGTS do mês e referente a 8% da base de calculo FGTS 
				$this->FGTSMes = number_format((($totalVencimentoAux * 8) / 100),2,",",".");
				
				// Pega metade dos meses.
				$mesesNum = str_replace('.', ',', ($dadosPagamento->getMesesTrabalhado() / 2));
				
				// Verifica se e mês ou meses
				$mesesTrabalhado = $mesesNum.($mesesNum > 1 ? ' Meses' : ' Mês' );
				
				// Define a descriçãopara o 13° salario.
				$descricao13 = '1°parcela 13° Salário';
				
			} elseif($dadosPagamento->getParcelaDecimo() == 'segunda') {
				
				// A Base de cálculo do INSS e em cima do vencimento.
				$this->BaseCalcInss = number_format($totalVencimentoAux,2,",",".");
				
				// Define a base de calculo do IRRF
				$this->BaseCalcIrrf = $dadosPagamento->getValorBruto() - $dadosPagamento->getValorINSS() - $dadosPagamento->getDescontoDepValor() - $dadosPagamento->getValorPensao();
				
				// Formata a base de calculo do IRRF como moeda.
				$this->BaseCalcIrrf = number_format($this->BaseCalcIrrf,2,",",".");
				
				// Pega a faxa do IR.
				$this->FaixaIrrf = $dadosPagamento->getFaixaIR();	
				
				// Por ser a segunda parcela deve pegar 50% do valor.
				$valoBaseCalcFGTS = $totalVencimentoAux / 2;
				
				// A Base de cálculo do FGTS e em cima do vencimento referente ao valor da segunda parcela
				$this->BaseCalcFgts = number_format($valoBaseCalcFGTS,2,",",".");
			
				// o valor do FGTS do mês e referente a 8% da base de calculo FGTS 
				$this->FGTSMes = number_format((($valoBaseCalcFGTS * 8) / 100),2,",",".");
				
				// Verifica se e mês ou meses
				$mesesTrabalhado = $dadosPagamento->getMesesTrabalhado().($dadosPagamento->getMesesTrabalhado() > 1 ? ' Meses' : ' Mês' );
				
				// Define a descriçãopara o 13° salario.
				$descricao13 = '13° Salário';
			} else {
				
				// A Base de cálculo do INSS e em cima do vencimento.
				$this->BaseCalcInss = number_format($totalVencimentoAux,2,",",".");
				
				// Define a base de calculo do IRRF
				$this->BaseCalcIrrf = $dadosPagamento->getValorBruto() - $dadosPagamento->getValorINSS() - $dadosPagamento->getDescontoDepValor() - $dadosPagamento->getValorPensao();
				
				// Formata a base de calculo do IRRF como moeda.
				$this->BaseCalcIrrf = number_format($this->BaseCalcIrrf,2,",",".");
				
				// Pega a faxa do IR.
				$this->FaixaIrrf = $dadosPagamento->getFaixaIR();	
				
				// A Base de cálculo do FGTS e em cima do vencimento referente ao valor da segunda parcela
				$this->BaseCalcFgts = number_format($totalVencimentoAux,2,",",".");
			
				// O valor do FGTS do mês e referente a 8% da base de calculo FGTS 
				$this->FGTSMes = number_format((($totalVencimentoAux * 8) / 100),2,",",".");
				
				// Verifica se e mês ou meses
				$mesesTrabalhado = $dadosPagamento->getMesesTrabalhado().($dadosPagamento->getMesesTrabalhado() > 1 ? ' Meses' : ' Mês' );
				
				// Define a descriçãopara o 13° salario.
				$descricao13 = '13° Salário';
			}
			
			// Define a quantidade de linas de vencimentos, descontos, etc.
			$this->Descricao = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'', 10=>'', 11=>'', 12=>'', 13=>'', 14=>'', 15=>'' );
			$this->Codigo = $this->Referencia = $this->Provento = $this->Desconto = $this->Descricao;

			// Define o inicio da linha do holerite.
			$numLinha = 1;

			if($dadosPagamento->getValorSalario() > 0){
				$this->Codigo[$numLinha] = '001';
				$this->Referencia[$numLinha] = $mesesTrabalhado;
				$this->Descricao[$numLinha] = $descricao13;
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorSalario(),2,',','.');
				$numLinha += 1;
			}
		
			if($dadosPagamento->getParcelaDecimo() == 'segunda'){
				
				// Pega 50% do salário que e referente a primeira parcela do salário.
				$valorPrimeiraParcela = $dadosPagamento->getValorBruto() / 2;
				
				$this->Codigo[$numLinha] = '016';
				$this->Descricao[$numLinha] = 'ADIANTAMENTO 13° Salário';
				$this->Desconto[$numLinha] = number_format($valorPrimeiraParcela,2,',','.');
				$numLinha += 1;	
			}
		
			if($dadosPagamento->getValorINSS() > 0){
				$this->Codigo[$numLinha] = '009';
				$this->Descricao[$numLinha] = 'INSS SOBRE 13° Salário';	
				$this->Referencia[$numLinha] = $dadosPagamento->getReferenciaINSS().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorINSS(),2,',','.');
				$numLinha += 1;	
			}		
		
			if($dadosPagamento->getValorIR() > 0){
				$this->Codigo[$numLinha] = '010';
				$this->Descricao[$numLinha] = 'IRRF SOBRE 13° Salário';	
				$this->Referencia[$numLinha] = $dadosPagamento->getReferenciaIR().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorIR(),2,',','.');
				$numLinha += 1;	
			}		
	}

	// Método criado para definir os dados do Salário a ser pago.
	private function salario($dadosPagamento) {
		
			// Passa o valor do vencimento para uma variável que servira usada para os calculos.
			$totalVencimentoAux = $dadosPagamento->getValorBruto();
		
			// Define o vencimento já formatado como moeda.
			$this->TotalVencimento = number_format($dadosPagamento->getValorBruto(),2,",",".");
		
			// Pega o total de desconto.
			$totalDescontosAux = $dadosPagamento->getValorINSS() + $dadosPagamento->getValorIR() + $dadosPagamento->getValorIRFerias() + $dadosPagamento->getValorPensao() + $dadosPagamento->getValorVR() + $dadosPagamento->getValorVT() + $dadosPagamento->getValorFaltas();	
					
			if($dadosPagamento->getValorFerias() > 0) {
				
				// Pega o Liquido de Ferias	
				$liquidoFerias = $dadosPagamento->getLiquidoFerias();
				
				// Valor liquido a receber.
				$this->LiquidoReceber = number_format(($totalVencimentoAux - $totalDescontosAux - $liquidoFerias),2,",",".");				

				// Formata total de desconto
				$this->TotalDescontos = number_format(($totalDescontosAux + $liquidoFerias),2,",",".");
				
				// Pega Abase do FGTS (O Abono e 1/3 abono não entra no calculo do FGTS).
				$baseCalculo = $totalVencimentoAux - ($dadosPagamento->getValorFeriasVendida() + $dadosPagamento->getValorUmTercoFeriasVendida());
				
				// Pega a base de calculo do INSS sem abono e bonus abono de ferias.
				$baseCalcFgts =$BaseCalcInss = $totalVencimentoAux - ($dadosPagamento->getValorFeriasVendida() + $dadosPagamento->getValorUmTercoFeriasVendida() + $dadosPagamento->getValorBonus() + $dadosPagamento->getValorAbono());	
								
				// A Base de cálculo do INSS e em cima do vencimento.
				$this->BaseCalcInss = number_format($BaseCalcInss,2,",",".");
		
				// A Base de cálculo do FGTS e em cima do vencimento referente ao valor da segunda parcela
				$this->BaseCalcFgts = number_format($baseCalcFgts,2,",",".");				
				
				// O valor do FGTS do mês e referente a 8% da base de calculo FGTS 
				$this->FGTSMes = number_format((($baseCalcFgts * 8) / 100),2,",",".");

				// Define a base de calculo do IRRF
				$this->BaseCalcIrrf = $dadosPagamento->getValorBruto() - $dadosPagamento->getValorINSS() - $dadosPagamento->getDescontoDepValor() - $dadosPagamento->getValorPensao() - $totalFeriasAux;

				if($this->BaseCalcIrrf <= 0) {
					$this->BaseCalcIrrf = 0;
				}
				
				// Formata a base de calculo do IRRF como moeda.
				$this->BaseCalcIrrf = number_format($this->BaseCalcIrrf,2,",",".");			
				

			} else {
				
				// Formata total de desconto
				$this->TotalDescontos = number_format($totalDescontosAux,2,",",".");
				
				// Valor liquido a receber.
				$this->LiquidoReceber = number_format(($totalVencimentoAux - $totalDescontosAux),2,",",".");
				
				// Informa que o liquido de férias e zero.
				$liquidoFerias = 0;
				
				$baseCalcFgts = $BaseCalcInss = $totalVencimentoAux - ($dadosPagamento->getValorFeriasVendida() + $dadosPagamento->getValorUmTercoFeriasVendida() + $dadosPagamento->getValorBonus() + $dadosPagamento->getValorAbono());

				// A Base de cálculo do INSS e em cima do vencimento.
				$this->BaseCalcInss = number_format($BaseCalcInss,2,",",".");

				// A Base de cálculo do FGTS e em cima do vencimento referente ao valor da segunda parcela
				$this->BaseCalcFgts = number_format($baseCalcFgts,2,",",".");

				// O valor do FGTS do mês e referente a 8% da base de calculo FGTS 
				$this->FGTSMes = number_format((($baseCalcFgts * 8) / 100),2,",",".");

				// Define a base de calculo do IRRF
				$this->BaseCalcIrrf = $dadosPagamento->getValorBruto() - $dadosPagamento->getValorINSS() - $dadosPagamento->getDescontoDepValor() - $dadosPagamento->getValorPensao();

				// Formata a base de calculo do IRRF como moeda.
				$this->BaseCalcIrrf = number_format($this->BaseCalcIrrf,2,",",".");
			}
				
				
			// Define o valor do salário do funcionario já formatado como moeda.
			$this->SalarioBase = number_format($dadosPagamento->getValorSalarioFuncionario(),2,",",".");

			// Pega a faxa do IR.
			$this->FaixaIrrf = $dadosPagamento->getFaixaIR();	

			// Define a quantidade de linas de vencimentos, descontos, etc.
			$this->Descricao = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'', 10=>'', 11=>'', 12=>'', 13=>'', 14=>'', 15=>'');
			$this->Codigo = $this->Referencia = $this->Provento = $this->Desconto = $this->Descricao;

			// Define o inicio da linha do holerite.
			$numLinha = 1;
			
			// Valor Salario.
			if($dadosPagamento->getValorSalario() > 0){
				$this->Codigo[$numLinha] = '001';
				$this->Referencia[$numLinha] = $dadosPagamento->getDiasTrabalhado().($dadosPagamento->getDiasTrabalhado() > 1 ? ' Dias' : ' Dia' );
				$this->Descricao[$numLinha] = 'Salário';
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorSalario(),2,',','.');
				$numLinha += 1;
			} else {
				$this->Codigo[$numLinha] = '001';
				$this->Referencia[$numLinha] = 0;
				$this->Descricao[$numLinha] = 'Salário';
				$this->Provento[$numLinha] = number_format(0,2,',','.');
				$numLinha += 1;				
			}

			// Valor Ferias.
			if($dadosPagamento->getValorFerias() > 0){
				$this->Codigo[$numLinha] = '002';
				$this->Referencia[$numLinha] = $dadosPagamento->getDiasFerias().($dadosPagamento->getDiasFerias() > 1 ? ' Dias' : ' Dia' );
				$this->Descricao[$numLinha] = 'Férias';
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorFerias(),2,',','.');
				$numLinha += 1;
			}		
		
			if($dadosPagamento->getValorUmTercoFerias() > 0){
				$this->Codigo[$numLinha] = '003';
				$this->Referencia[$numLinha] = '';
				$this->Descricao[$numLinha] = 'Ferias 1/3 adcional';
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorUmTercoFerias(),2,',','.');
				$numLinha += 1;
			}	
		
			if($dadosPagamento->getVendaUmTercoFerias() == 'S') {
		
				if($dadosPagamento->getValorFeriasVendida() > 0){
					$this->Codigo[$numLinha] = '004';
					$this->Referencia[$numLinha] = '10 Dias';
					$this->Descricao[$numLinha] = 'Abono Pecuniário';
					$this->Provento[$numLinha] = number_format($dadosPagamento->getValorFeriasVendida(),2,',','.');
					$numLinha += 1;
				}		

				if($dadosPagamento->getValorUmTercoFeriasVendida() > 0){
					$this->Codigo[$numLinha] = '005';
					$this->Referencia[$numLinha] = '';
					$this->Descricao[$numLinha] = 'Abono 1/3 adciona';
					$this->Provento[$numLinha] = number_format($dadosPagamento->getValorUmTercoFeriasVendida(),2,',','.');
					$numLinha += 1;
				}	
			}
	
			if($dadosPagamento->getValorFamilia() > 0){
				$this->Codigo[$numLinha] = '006';
				$this->Descricao[$numLinha] = 'Salário Familía';
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorFamilia(),2,',','.');
				$numLinha += 1;		
			}

			if($dadosPagamento->getValorMaternidade() > 0){
				$this->Codigo[$numLinha] = '007';
				$this->Descricao[$numLinha] = 'Salário Maternidade';
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorMaternidade(),2,',','.');
				$numLinha += 1;	
			}

			if($dadosPagamento->getValorAbono() > 0){
				$this->Codigo[$numLinha] = '008';
				$this->Descricao[$numLinha] = 'Abono Salárial';
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorAbono(),2,',','.');
				$numLinha += 1;	
			}

			if($dadosPagamento->getValorBonus() > 0){
				$this->Codigo[$numLinha] = '017';
				$this->Descricao[$numLinha] = 'Bônus';
				$this->Provento[$numLinha] = number_format($dadosPagamento->getValorBonus(),2,',','.');
				$numLinha += 1;	
			}		
		
			if($dadosPagamento->getValorINSS() > 0){
				$this->Codigo[$numLinha] = '009';
				$this->Descricao[$numLinha] = 'INSS';	
				$this->Referencia[$numLinha] = $dadosPagamento->getReferenciaINSS().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorINSS(),2,',','.');
				$numLinha += 1;	
			}

			if($dadosPagamento->getValorIR() > 0){
				$this->Codigo[$numLinha] = '010';
				$this->Descricao[$numLinha] = 'IRRF';	
				$this->Referencia[$numLinha] = $dadosPagamento->getReferenciaIR().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorIR(),2,',','.');
				$numLinha += 1;	
			}
		
			if($dadosPagamento->getValorIRFerias() > 0){
				$this->Codigo[$numLinha] = '011';
				$this->Descricao[$numLinha] = 'IRRF Ferias';	
				$this->Referencia[$numLinha] = $dadosPagamento->getReferenciaIRFerias().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorIRFerias(),2,',','.');
				$numLinha += 1;	
			}		
		
			if($dadosPagamento->getValorPensao() > 0){
				$this->Codigo[$numLinha] = '012';
				$this->Descricao[$numLinha] = 'Pensão';	
				$this->Referencia[$numLinha] = $dadosPagamento->getReferenciaPensao().'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorPensao(),2,',','.');
				$numLinha += 1;
			}	

			if($dadosPagamento->getValorVR() > 0){
				$this->Codigo[$numLinha] = '013';
				$this->Descricao[$numLinha] = 'Vale Refeição';	
				$this->Referencia[$numLinha] = number_format($dadosPagamento->getReferenciaVR(),2,',','.').'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorVR(),2,',','.');
				$numLinha += 1;
			}

			if($dadosPagamento->getValorVT() > 0){
				$this->Codigo[$numLinha] = '014';
				$this->Descricao[$numLinha] = 'Vale Transporte';	
				$this->Referencia[$numLinha] = number_format($dadosPagamento->getReferenciaVT(),2,',','.').'%';
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorVT(),2,',','.');
				$numLinha += 1;
			}

			if($dadosPagamento->getFaltas() > 0){
				$this->Codigo[$numLinha] = '015';
				$this->Descricao[$numLinha] = 'Faltas';	
				$this->Referencia[$numLinha] = $dadosPagamento->getFaltas().($dadosPagamento->getFaltas() > 1 ? ' Dias' : ' Dia' );
				$this->Desconto[$numLinha] = number_format($dadosPagamento->getValorFaltas(),2,',','.');
				$numLinha += 1;
			}
		
			if($liquidoFerias > 0) {
				$this->Codigo[$numLinha] = '016';
				$this->Descricao[$numLinha] = 'Liquido de Férias';	
				$this->Referencia[$numLinha] = '';
				$this->Desconto[$numLinha] = number_format($liquidoFerias,2,',','.');
				$numLinha += 1;	
			}
	}
}

// Realiza a chamada da classe.
$geraHolerite = new GeraHoleriteFuncionario();

